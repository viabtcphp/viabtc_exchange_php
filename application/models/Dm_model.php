<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 合约持仓模型
 */
class Dm_model extends MY_Model {


    /**
     * 数据表名
     */
    public $table = 'ex_dm';


    /**
     * 合约可用余额 = 合约帐户可用资产 + (所有正在持仓的合约盈亏为负的总和)
     * 合约冻结余额 = 所有正在持仓的合约保证金 + 正在委托的合约保证金 + (所有正在持仓的合约盈亏为正的总和)
     * 担保资产率 = 合约可用余额 / 所有正在持仓的合约保证金
     */


    public function __construct(){

        parent::__construct();

        $this->load->model('asset_model');
        $this->load->model('ves_model');

        //设置资产计算保留的小数位精度
        bcscale($this->config->item('ex_asset_scale'));
    }


    /**
     * 数据表字段缺省值数组，首个元素必须为主键字段
     */
    public $fieldsArray = array(

        //字段 => 缺省值
        'dm_id' => 0,                   /*持仓ID*/
        'dm_user' => 0,                 /*用户ID*/
        'dm_coin' => 0,                 /*币种ID*/
        'dm_market' => 0,               /*交易市场ID*/
        'dm_order_amount' => 0,         /*下单量*/
        'dm_order_time' => APP_TIME,    /*下单时间*/
        'dm_open' => 0,                 /*是否已开仓，1为已开仓，0为未开仓*/
        'dm_open_time' => 0,            /*开仓时间*/
        'dm_open_price' => 0,           /*开仓价*/
        'dm_open_amount' => 0,          /*开仓数量*/
        'dm_hold_amount' => 0,          /*实时持仓数量*/
        'dm_trade_type' => 0,           /*交易类型，1为市价，2为限价*/
        'dm_close' => 0,                /*是否已平仓，1为已平仓，0为未平仓*/
        'dm_close_future_price' => 0,   /*预估强平价*/
        'dm_close_price' => 0,          /*平仓价*/
        'dm_close_type' => 0,           /*平仓类型，1为手动平仓，0为爆仓*/
        'dm_close_time' => 0,           /*平仓时间*/
        'dm_direction' => 0,            /*持仓方向，1为买入看涨，0为卖出看空*/
        'dm_multiple' => 0,             /*持仓倍数*/
        'dm_margin' => 0,               /*保证金*/
        'dm_fee' => 0,                  /*手续费*/
        'dm_profit' => 0,               /*盈亏*/
        'dm_last_check_time' => 0,      /*最后一次校验时间*/
        'dm_status' => 1                /*持仓状态，1为正常，0为取消*/
    );


    public $joinTemplate = array(

        //与币种表联表
        array(

            'table'         => 'ex_coin',
            'on_left'       => 'dm_coin',
            'on_right'      => 'coin_id',
            'fields'        => array(

                //查询交易币种标识
                'coin_symbol' => 'dm_coin_symbol'
            )
        ),
        //与用户表联表
        array(

            'table'         => 'ex_user',
            'on_left'       => 'dm_user',
            'on_right'      => 'user_id',
            'fields'        => array(

                //查询交易币种标识
                'user_name' => 'dm_user_name'
            )
        )
    );


    /**
     * 写入合约订单
     * @param  array  $dm 合约订单对象数组
     * @return bool       返回操作结果，操作成功返回true，操作失败返回false
     */
    public function insert($dm){

        $result = FALSE;

        $this->db->trans_start();

        $result = parent::insert($dm);

        if ($result) {
            
            $dm['dm_id'] = $this->db->insert_id();

            //插入时，冻结保证金
            if ($this->asset_model->userDmFrozen($dm)) {
                
                //如果是市价合约，插入就开仓
                if ($dm['dm_trade_type'] === 1) {
                    
                    $result = $this->openDm($dm);
                }
            }
        }

        $this->db->trans_complete();

        return $result;
    }


    /**
     * 计算并返回合约盈亏
     * @param  array  $dm           合约对象
     * @param  float  $currentPrice 最新价
     * @return float                返回盈利数值
     */
    public function computDmProfit($dm, $currentPrice){

        $profit = 0;

        if ($dm && $dm['dm_open'] == 1 && $dm['dm_close'] == 0 && $dm['dm_status'] == 1) {
            
            //多头盈利
            $profit = bcmul(bcdiv(bcsub($currentPrice, $dm['dm_open_price']), $dm['dm_open_price']), $dm['dm_open_amount']);

            //空头盈利
            if ($dm['dm_direction'] == 0) {
                
                $profit = bcsub(0, $profit);
            }
        }

        return $profit;
    }


    /**
     * 计算并返回合约爆仓价
     * @param  int    $margin     当前保证金(合约保证金+合约帐户可用余额)
     * @param  float  $openAmount 开仓数量
     * @param  int    $direction  开仓方向
     * @param  float  $openPrice  开仓价
     * @return float              返回爆仓价格
     */
    public function computFutureClosePrice($margin, $openAmount, $direction, $openPrice){

        $closePrice = 0;

        //爆仓差价 = 开仓价 +- ((保证金/开仓数量) * 开仓价)
        $priceSub = bcmul(bcdiv($margin, $openAmount), $openPrice);

        if ($direction > 0) {

            //多单向下爆仓
            $closePrice = bcsub($openPrice, $priceSub);

            if (bccomp($closePrice, 0) < 0) {
                
                $closePrice = '0.00000001';
            }
        }else{

            //空单向上爆仓
            $closePrice = bcadd($openPrice, $priceSub);
        }

        return $closePrice;
    }


    /**
     * 获取单个合约订单
     */
    public function one($dmId, $where = FALSE, $join = FALSE){

        return parent::one($dmId, $where, $this->joinTemplate);
    }


    /**
     * 获取所有未平仓且未取消的合约订单
     * @return array 返回合约订单列表
     */
    public function getActiveDm(){

        $where = '`dm_status`=1 AND `dm_close`=0';

        return $this->get(FALSE, FALSE, $where, FALSE, $this->joinTemplate);
    }


    /**
     * 后台获取委托中的合约订单
     * @param  int    $pageIndex 当前页码
     * @param  int    $pageSize  每页数量
     * @return array             返回合约列表
     */
    public function getDelegateDm($pageIndex, $pageSize, $userIdList = FALSE){

        $where = '`dm_status`=1 AND `dm_open`=0';

        if ($userIdList) {
            
            $where .= ' AND `dm_user` IN (' . implode(',', $userIdList) . ')';
        }

        $order = '`dm_order_time` DESC';

        return $this->get($pageIndex, $pageSize, $where, $order, $this->joinTemplate);
    }


    /**
     * 后台获取委托中的合约订单的数量
     * @return int 返回数量
     */
    public function countDelegateDm($userIdList = FALSE){

        $where = '`dm_status`=1 AND `dm_open`=0';

        if ($userIdList) {
            
            $where .= ' AND `dm_user` IN (' . implode(',', $userIdList) . ')';
        }

        return $this->count($where);
    }


    /**
     * 后台获取持仓中的合约订单
     * @param  int    $pageIndex 当前页码
     * @param  int    $pageSize  每页数量
     * @return array             返回合约列表
     */
    public function getHoldDm($pageIndex, $pageSize, $userIdList = FALSE){

        $where = '`dm_status`=1 AND `dm_open`=1 AND `dm_close`=0';

        if ($userIdList) {
            
            $where .= ' AND `dm_user` IN (' . implode(',', $userIdList) . ')';
        }

        $order = '`dm_open_time` DESC';

        return $this->get($pageIndex, $pageSize, $where, $order, $this->joinTemplate);
    }


    /**
     * 后台获取持仓中的合约订单的数量
     * @return int 返回数量
     */
    public function countHoldDm($userIdList = FALSE){

        $where = '`dm_status`=1 AND `dm_open`=1 AND `dm_close`=0';

        if ($userIdList) {
            
            $where .= ' AND `dm_user` IN (' . implode(',', $userIdList) . ')';
        }

        return $this->count($where);
    }


    /**
     * 后台获取历史合约订单
     * @param  int    $pageIndex 当前页码
     * @param  int    $pageSize  每页数量
     * @return array             返回合约列表
     */
    public function getHistoryDm($pageIndex, $pageSize, $userIdList = FALSE){

        $where = '(`dm_status`=0 OR `dm_close`=1)';

        if ($userIdList) {
            
            $where .= ' AND `dm_user` IN (' . implode(',', $userIdList) . ')';
        }

        $order = '`dm_order_time` DESC';

        return $this->get($pageIndex, $pageSize, $where, $order, $this->joinTemplate);
    }


    /**
     * 后台获取历史合约订单的数量
     * @return int 返回数量
     */
    public function countHistoryDm($userIdList = FALSE){

        $where = '(`dm_status`=0 OR `dm_close`=1)';

        if ($userIdList) {
            
            $where .= ' AND `dm_user` IN (' . implode(',', $userIdList) . ')';
        }

        return $this->count($where);
    }


    /**
     * 获取用户合约订单
     * @param  int   $userId    用户ID
     * @param  int   $marketId  币种ID，可选
     * @param  bool             可选，是否是正在持仓的订单，true是，false否
     * @return array            返回合约列表
     */
    public function getUserDm($userId, $coinId = FALSE, $dmActive = FALSE, $dmDirection = FALSE, $pageIndex = FALSE, $pageSize = FALSE){

        $where = '`dm_user`=' . $userId;

        if ($coinId) {
            
            $where .= ' AND `dm_coin`=' . $coinId;
        }

        if ($dmActive) {
            
            $where .= ' AND `dm_open`=1 AND `dm_close`=0 AND `dm_status`=1';
        }

        if ($dmDirection !== FALSE) {
            
            $where .= ' AND `dm_direction`=' . $dmDirection;
        }

        $order = '`dm_order_time` DESC, `dm_open_time` DESC, `dm_order_time` DESC';

        return $this->get($pageIndex, $pageSize, $where, $order, $this->joinTemplate);
    }


    /**
     * 获取用户合约订单数量
     * @param  int   $userId    用户ID
     * @param  int   $marketId  币种ID，可选
     * @param  bool             可选，是否是正在持仓的订单，true是，false否
     * @return array            返回合约数量
     */
    public function countUserDm($userId, $coinId = FALSE, $dmActive = FALSE, $dmDirection = FALSE){

        $where = '`dm_user`=' . $userId;

        if ($coinId) {
            
            $where .= ' AND `dm_coin`=' . $coinId;
        }

        if ($dmActive) {
            
            $where .= ' AND `dm_open`=1 AND `dm_close`=0 AND `dm_status`=1';
        }

        if ($dmDirection !== FALSE) {
            
            $where .= ' AND `dm_direction`=' . $dmDirection;
        }

        return $this->count($where);
    }


    /**
     * 取消合约订单
     * @param  array  $dmId 合约对象
     * @return boll         返回操作结果，操作成功返回true，操作失败返回false
     */
    public function cancelDm($dm){

        $result = FALSE;

        $this->db->trans_start();

        //取消前，确认该合约订单未开仓
        if ($dm && $dm['dm_open'] == 0) {
            
            //更新合约状态
            $dm['dm_status'] = 0;

            if ($this->update($dm)) {
                
                //取消合约订单时，解除冻结
                if ($this->asset_model->dmCancelFrozen($dm)) {
                    
                    $result = TRUE;
                }
            }
        }

        $this->db->trans_complete();

        return $result;
    }


    /**
     * 合约平仓
     * @param  array   $dm   合约对象
     * @param  bool    $must 可选，是否为强平，true为强平，false为手动平仓，默认false
     * @return bool          返回操作结果，操作成功返回true，操作失败返回false
     */
    public function closeDm($dm, $must = FALSE, $mustPrice = FALSE){

        $result = FALSE;

        $this->db->trans_start();

        $dm = $this->one($dm['dm_id']);

        if ($dm && $dm['dm_open'] == 1 && $dm['dm_close'] == 0 && $dm['dm_status'] == 1) {

            $check = TRUE;

            //爆仓强平
            if ($must !== FALSE && $mustPrice !== FALSE) {
                
                //获取最新的盈亏，并更新平仓类型
                $dm['dm_profit'] = $this->computDmProfit($dm, $mustPrice);

                //调用爆仓剩余率
                $sysConfig = $this->sysconfig_model->getFormatSysconfig();
                if ($sysConfig) {
                    
                    if (is_numeric($sysConfig['sysconfig_dm_close_balance_rate'])) {
                        
                        //爆仓剩余 = 实际下单数量 * 爆仓剩余率
                        $dm['dm_profit'] = bcsub(

                            bcmul($dm['dm_open_amount'], $sysConfig['sysconfig_dm_close_balance_rate']),
                            $dm['dm_order_amount']
                        );
                    }
                }

                $dm['dm_hold_amount'] = 0;
                $dm['dm_close_type'] = 0;
                $dm['dm_close'] = 1;
                $dm['dm_close_time'] = time();
                $dm['dm_close_price'] = $mustPrice;
            //手动平仓
            }else{

                //获取最新价
                $ret = $this->ves_model->market_last($dm['dm_coin_symbol'] . $this->config->item('dm_money_symbol'));

                if ($ret && isset($ret['code']) && $ret['code'] > 0) {
                    
                    //获取最新的盈亏，并更新平仓类型
                    $dm['dm_profit'] = $this->computDmProfit($dm, $ret['data']);
                    $dm['dm_hold_amount'] = bcadd($dm['dm_open_amount'], $dm['dm_profit']);
                    $dm['dm_close_type'] = 1;
                    $dm['dm_close'] = 1;
                    $dm['dm_close_time'] = time();
                    $dm['dm_close_price'] = $ret['data'];
                }else{

                    $check = FALSE;
                }
            }

            //更新合约状态，并结算资产
            if ($check && $this->update($dm) && $this->asset_model->dmCloseSettlement($dm)) {
                
                $result = TRUE;
            }
        }

        $this->db->trans_complete();

        return $result;
    }


    /**
     * 合约开仓
     * @param  array   $dm              合约订单对象数组
     * @return bool                     返回操作结果，操作成功返回true，操作失败返回false
     */
    public function openDm($dm){

        $result = FALSE;

        $this->db->trans_start();

        $userDmCoinAsset = $this->asset_model->getUserDmAsset($dm['dm_user'], $dm['dm_coin']);

        if ($userDmCoinAsset) {

            if ($dm && $dm['dm_open'] == 0 && $dm['dm_close'] == 0 && $dm['dm_status'] == 1) {

                //计算开仓数据
                $dm['dm_open'] = 1;
                $dm['dm_open_time'] = time();
                $dm['dm_open_amount'] = bcmul($dm['dm_order_amount'], $dm['dm_multiple']);
                $dm['dm_hold_amount'] = $dm['dm_open_amount'];
                $dm['dm_close_future_price'] = $this->computFutureClosePrice(bcadd($userDmCoinAsset['asset_active'], $dm['dm_order_amount']), $dm['dm_open_amount'], $dm['dm_direction'], $dm['dm_open_price']);
                $dm['dm_last_check_time'] = time() - 1;

                //更新合约状态，并扣除手续费
                if ($this->update($dm) && $this->asset_model->userDmSubFee($dm)) {
                    
                    $result = TRUE;
                }
            }
        }

        $this->db->trans_complete();

        return $result;
    }
}
