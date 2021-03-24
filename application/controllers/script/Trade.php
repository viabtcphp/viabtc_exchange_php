<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 交易命令行控制器
 */
class Trade extends MY_Controller {


    /**
     * 构造函数，初始化
     */
    public function __construct(){

        if (! is_cli()) {
            
            die('ERROR');
        }

        parent::__construct();

        set_time_limit(0);

        //载入模型
        $this->load->model('ves_model');
        $this->load->model('robot_model');
        $this->load->model('market_model');
        $this->load->model('coin_model');
        $this->load->model('dm_model');
        $this->load->model('user_model');
        $this->load->model('huobi_model');
        $this->load->model('order_model');
    }


    public function order(){

        $recordsLimit = 100;

        $recordsTypeArray = array('finished', 'pending');

        while (TRUE) {
            
            $marketList = $this->market_model->getAllActiveMarketList();

            $userIds = $this->user_model->get(FALSE, FALSE, FALSE, FALSE, FALSE, 'user_id');

            if (count($marketList) && count($userIds)) {

                $userIds = array_column($userIds, 'user_id');
                
                foreach ($marketList as $marketItem) {

                    foreach ($userIds as $userId) {
                        
                        if ($userId > 1) {

                            foreach ($recordsTypeArray as $typeItem) {
                                
                                $offset = 0;
                                $recordsCount = $recordsLimit;

                                while ($recordsCount == $recordsLimit) {

                                    $ret = FALSE;

                                    if ($typeItem == 'finished') {
                                        
                                        $ret = $this->ves_model->order_finished(
                                            $userId,
                                            $marketItem['market_stock_symbol'] . $marketItem['market_money_symbol'],
                                            0,
                                            0,
                                            $offset,
                                            $recordsLimit,
                                            0
                                        );
                                    }else{

                                        $ret = $this->ves_model->order_pending(
                                            $userId,
                                            $marketItem['market_stock_symbol'] . $marketItem['market_money_symbol'],
                                            $offset,
                                            $recordsLimit
                                        );
                                    }

                                    if ($ret['code'] > 0) {
                                        
                                        $recordsCount = count($ret['data']['records']);

                                        if ($recordsCount > 0) {

                                            $dealArray = array();
                                            
                                            foreach ($ret['data']['records'] as $orderItem) {

                                                $dealArray[] = array(

                                                    'order_id' => 0,
                                                    'order_stock' => $marketItem['market_stock_coin'],
                                                    'order_money' => $marketItem['market_money_coin'],
                                                    'order_no' => $orderItem['id'],
                                                    'order_source' => isset($orderItem['source']) ? $orderItem['source'] : '',
                                                    'order_side' => $orderItem['side'],
                                                    'order_type' => $orderItem['type'],
                                                    'order_maker_fee' => $orderItem['maker_fee'],
                                                    'order_ctime' => intval($orderItem['ctime']),
                                                    'order_user' => $orderItem['user'],
                                                    'order_ftime' => $typeItem == 'finished' ? intval($orderItem['ftime']) : 0,
                                                    'order_price' => $typeItem == 'finished' ? ($orderItem['type'] == 1 ? $orderItem['price'] : bcdiv($orderItem['deal_money'], $orderItem['deal_stock'], 8)) : ($orderItem['type'] == 1 ? $orderItem['price'] : 0),
                                                    'order_count' => $typeItem == 'finished' ? $orderItem['deal_stock'] : $orderItem['amount'],
                                                    'order_deal_stock' => $orderItem['deal_stock'],
                                                    'order_taker_fee' => $orderItem['taker_fee'],
                                                    'order_deal_money' => $orderItem['deal_money'],
                                                    'order_deal_fee' => $orderItem['deal_fee'],
                                                    'order_left' => $typeItem == 'finished' ? 0 : $orderItem['left'],
                                                    'order_finished' => $typeItem == 'finished' ? 1 : 0
                                                );
                                            }

                                            if ($this->order_model->batchInsertDeal($dealArray)) {
                                                
                                                echo "\r\n[ " . date('Y-m-d H:i:s') . " ] [ " . $marketItem['market_stock_symbol'] . " / " . $marketItem['market_money_symbol'] . " ] [ 用户 " . $userId . " ] [ " . ($typeItem == 'finished' ? '历史订单' : '挂起订单') . " ] [ " . $recordsCount . " 条 ]";
                                            }
                                        }

                                        $offset += $recordsCount;
                                    }else{

                                        $recordsCount = 0;
                                    }
                                }
                            }
                        }
                    }
                }
            }

            sleep(1);
        }
    }


    public function huobi(){

        while (TRUE) {
            
            $coinList = $this->coin_model->get();
            $marketList = $this->market_model->get();
            $robotList = $this->robot_model->get();

            if ($coinList && count($coinList) && $marketList && count($marketList) && $robotList && count($robotList)) {

                $coinList = array_column($coinList, NULL, 'coin_id');
                $marketList = array_column($marketList, NULL, 'market_id');
                
                foreach ($robotList as $robot) {
                    
                    if ($robot['robot_status'] == 1) {

                        if ($robot['robot_huobi'] == 1) {
                            
                            $marketSymbol = $coinList[$marketList[$robot['robot_market']]['market_stock_coin']]['coin_symbol'] . $coinList[$marketList[$robot['robot_market']]['market_money_coin']]['coin_symbol'];

                            if (trim($robot['robot_huobi_symbol']) != '') {
                                
                                $marketSymbol = trim($robot['robot_huobi_symbol']);
                            }

                            $price = $this->huobi_model->getSymbolNewPrice(strtolower($marketSymbol));

                            if ($price['status']) {
                                
                                $robotUpdate = array(

                                    'robot_id' => $robot['robot_id'],
                                    'robot_max_price' => $price['data']['ask'],
                                    'robot_min_price' => $price['data']['bid']
                                );

                                $marketSymbol = $coinList[$marketList[$robot['robot_market']]['market_stock_coin']]['coin_symbol'] . ' / ' . $coinList[$marketList[$robot['robot_market']]['market_money_coin']]['coin_symbol'];

                                echo "\r\n[ " . date('Y-m-d H:i:s') . " ] 更新价格 [ " . $marketSymbol . " ] [min: " . $robotUpdate['robot_min_price'] . "] [max: " . $robotUpdate['robot_max_price'] . "]";

                                if ($this->robot_model->update($robotUpdate)) {
                                    
                                    echo ' [ 成功 ]';
                                }else{

                                    echo ' [ 失败 ]';
                                }
                            }
                        }
                    }

                    sleep(1);
                }
            }

            sleep(1);
        }
    }


    public function robot(){

        $tradeTime = 1;
        $tradeUser = 1;
        $tradeDepth = 50;

        while (TRUE) {
            
            $coinList = $this->coin_model->get();
            $marketList = $this->market_model->get();
            $robotList = $this->robot_model->get();

            if ($coinList && count($coinList) && $marketList && count($marketList) && $robotList && count($robotList)) {

                $coinList = array_column($coinList, NULL, 'coin_id');
                $marketList = array_column($marketList, NULL, 'market_id');
                
                foreach ($robotList as $robot) {
                    
                    if ($robot['robot_status'] == 1 && $robot['robot_huobi'] == 1) {
                        
                        $marketSymbol = $coinList[$marketList[$robot['robot_market']]['market_stock_coin']]['coin_symbol'] . $coinList[$marketList[$robot['robot_market']]['market_money_coin']]['coin_symbol'];

                        $marketStockSymbol = $coinList[$marketList[$robot['robot_market']]['market_stock_coin']]['coin_symbol'];
                        $marketMoneySymbol = $coinList[$marketList[$robot['robot_market']]['market_money_coin']]['coin_symbol'];

                        $marketDecimal = $marketList[$robot['robot_market']]['market_decimal'];

                        $marketTakerFee = $marketList[$robot['robot_market']]['market_taker_fee'];
                        $marketMakerFee = $marketList[$robot['robot_market']]['market_maker_fee'];

                        $tradePrice = $this->randomFloat($robot['robot_min_price'], $robot['robot_max_price'], $marketDecimal);
                        $tradeAmount = $this->randomFloat($robot['robot_min_amount'], $robot['robot_max_amount'], $marketDecimal);

                        $ret = $this->ves_model->market_last($marketSymbol);

                        $tradeType = mt_rand(1, 2);

                        if (mt_rand(0, 5) < 1) {
                            
                            if ($ret['code'] > 0) {
                                
                                if (bccomp($tradePrice, $ret['data'], $marketDecimal) > 0) {
                                    
                                    $tradeType = 2;
                                }

                                if (bccomp($tradePrice, $ret['data'], $marketDecimal) < 0) {
                                    
                                    $tradeType = 1;
                                }
                            }
                        }

                        $ret = $this->ves_model->order_depth($marketSymbol, $tradeDepth);

                        if ($ret['code'] > 0) {

                            $tradeAmountTemp = 0;

                            if ($tradeType === 1 && count($ret['data']['bids'])) {
                                
                                foreach ($ret['data']['bids'] as $bid) {
                                    
                                    if (bccomp($tradePrice, $bid[0], $marketDecimal) <= 0) {
                                        
                                        $tradeAmountTemp = bcadd($tradeAmountTemp, $bid[1], $marketDecimal);
                                    }
                                }
                            }

                            if ($tradeType === 2 && count($ret['data']['asks'])) {
                                
                                foreach ($ret['data']['asks'] as $ask) {
                                    
                                    if (bccomp($tradePrice, $ask[0], $marketDecimal) >= 0) {
                                        
                                        $tradeAmountTemp = bcadd($tradeAmountTemp, $ask[1], $marketDecimal);
                                    }
                                }
                            }

                            if (bccomp($tradeAmountTemp, 0, $marketDecimal) > 0) {
                                
                                $tradeAmount = bcadd($tradeAmountTemp, $tradeAmount, $marketDecimal);
                            }
                        }

                        $ret = $this->ves_model->order_put_limit($tradeUser, $marketSymbol, $tradeType, $tradeAmount, $tradePrice, $marketTakerFee, $marketMakerFee, 'robot');

                        echo "\r\n[ " . date('Y-m-d H:i:s') . " ] 机器人" . ($tradeType === 1 ? '卖' : '买') . "单 [ " . $marketSymbol . " ] [price: " . $tradePrice . "] [amount: " . $tradeAmount . "]";

                        if ($ret['code'] > 0) {
                            
                            echo " [ success ]";
                        }else{

                            echo " [ " . $ret['data'] . " ]";;
                        }

                        usleep(500000);
                    }
                }
            }

            sleep(1);
        }
    }


    public function clear_order(){

        set_time_limit(0);

        $this->load->model('ves_model');
        $this->load->model('market_model');

        $marketList = $this->market_model->getAllActiveMarketList();

        foreach ($marketList as $market) {

            for ($i=0; $i < 20; $i++) { 
                
                $ret = $this->ves_model->order_pending(1, $market['market_stock_symbol'] . $market['market_money_symbol']);

                if ($ret['code'] > 0 && count($ret['data']['records'])) {
                    
                    foreach($ret['data']['records'] as $pending){

                        $this->ves_model->order_cancel(1, $market['market_stock_symbol'] . $market['market_money_symbol'], $pending['id']);
                    }
                }
            }
        }
    }


    public function local(){

        $tradeTime = 1;
        $tradeUser = 1;
        $tradePriceRateMin = 0.005;
        $tradePriceRateMax = 0.01;
        $tradeDepth = 50;

        $cronRate = 20;

        while (TRUE) {
            
            $coinList = $this->coin_model->get();
            $marketList = $this->market_model->get();
            $robotList = $this->robot_model->get();

            if ($coinList && count($coinList) && $marketList && count($marketList) && $robotList && count($robotList)) {

                $coinList = array_column($coinList, NULL, 'coin_id');
                $marketList = array_column($marketList, NULL, 'market_id');
                
                foreach ($robotList as $robot) {
                    
                    if ($robot['robot_status'] == 1 && $robot['robot_huobi'] == 0) {

                        $cron_end_update = FALSE;
                        
                        $marketSymbol = $coinList[$marketList[$robot['robot_market']]['market_stock_coin']]['coin_symbol'] . $coinList[$marketList[$robot['robot_market']]['market_money_coin']]['coin_symbol'];

                        $marketStockSymbol = $coinList[$marketList[$robot['robot_market']]['market_stock_coin']]['coin_symbol'];
                        $marketMoneySymbol = $coinList[$marketList[$robot['robot_market']]['market_money_coin']]['coin_symbol'];

                        $marketDecimal = $marketList[$robot['robot_market']]['market_decimal'];

                        $marketTakerFee = $marketList[$robot['robot_market']]['market_taker_fee'];
                        $marketMakerFee = $marketList[$robot['robot_market']]['market_maker_fee'];

                        $tradePrice = $this->randomFloat($robot['robot_min_price'], $robot['robot_max_price'], $marketDecimal);
                        $tradeAmount = $this->randomFloat($robot['robot_min_amount'], $robot['robot_max_amount'], $marketDecimal);

                        $tradeType = mt_rand(1, 2);

                        $priceHeight = bcsub($robot['robot_max_price'], $robot['robot_min_price'], $marketDecimal);

                        $ret = $this->ves_model->market_last($marketSymbol);

                        if ($ret['code'] > 0) {

                            if ($robot['robot_cron'] > 0 && time() > $robot['robot_cron_start']) {
                                
                                if (time() <= $robot['robot_cron_end']) {
                                    
                                    $needCron = mt_rand(0, $cronRate);

                                    if ($needCron > 0) {

                                        //概率不执行计划
                                        
                                        echo "\r\n概率不执行计划";

                                        $tradePrice = bccomp($ret['data'], 0, $marketDecimal) > 0 ? $ret['data'] : $tradePrice;

                                        $tradePriceRate = $this->randomFloat($tradePriceRateMin, $tradePriceRateMax, $marketDecimal);

                                        $priceChange = mt_rand(0, 1);

                                        if ($priceChange > 0) {
                                            
                                            $tradePrice = bcadd($tradePrice, bcmul($priceHeight, $tradePriceRate, $marketDecimal), $marketDecimal);
                                        }else{

                                            $tradePrice = bcsub($tradePrice, bcmul($priceHeight, $tradePriceRate, $marketDecimal), $marketDecimal);
                                        }

                                        if (bccomp($robot['robot_max_price'], $tradePrice, $marketDecimal) < 0) {
                                            
                                            $tradePrice = $robot['robot_max_price'];
                                        }

                                        if (bccomp($robot['robot_min_price'], $tradePrice, $marketDecimal) > 0) {
                                            
                                            $tradePrice = $robot['robot_min_price'];
                                        }
                                    }else{

                                        echo "\r\n执行计划";

                                        //剩余时间比例
                                        $cronTimeRate = bcdiv(time() - $robot['robot_cron_start'], $robot['robot_cron_end'] - $robot['robot_cron_start'], 2);

                                        $cronPriceSub = $tradePrice;


                                        if (bccomp($robot['robot_cron_target'], $robot['robot_cron_from'], $marketDecimal) >= 0) {

                                            //拉盘

                                            $cronPriceSub = bcsub($robot['robot_cron_target'], $robot['robot_cron_from'], $marketDecimal);

                                            $tradePrice = bcadd($robot['robot_cron_from'], bcmul($cronPriceSub, $cronTimeRate, $marketDecimal), $marketDecimal);
                                        }else{

                                            //砸盘

                                            $cronPriceSub = bcsub($robot['robot_cron_from'], $robot['robot_cron_target'], $marketDecimal);

                                            $tradePrice = bcsub($robot['robot_cron_from'], bcmul($cronPriceSub, $cronTimeRate, $marketDecimal), $marketDecimal);
                                        }
                                    }
                                }else{

                                    if ((time() - $robot['robot_cron_end']) < 60) {
                                        
                                        //超出时间，必须直接到达预定价格

                                        echo "\r\n计划时间结束，一步到位";

                                        if (bccomp($tradePrice, $robot['robot_cron_target'], $marketDecimal) > 0) {
                                            
                                            $tradeType = 1;
                                        }else{

                                            $tradeType = 2;
                                        }

                                        $tradePrice = $robot['robot_cron_target'];
                                    }

                                    $cron_end_update = TRUE;
                                }
                            }else{
                                
                                $tradePrice = bccomp($ret['data'], 0, $marketDecimal) > 0 ? $ret['data'] : $tradePrice;

                                $tradePriceRate = $this->randomFloat($tradePriceRateMin, $tradePriceRateMax, $marketDecimal);

                                $priceChange = mt_rand(0, 1);

                                if ($priceChange > 0) {
                                    
                                    $tradePrice = bcadd($tradePrice, bcmul($priceHeight, $tradePriceRate, $marketDecimal), $marketDecimal);
                                }else{

                                    $tradePrice = bcsub($tradePrice, bcmul($priceHeight, $tradePriceRate, $marketDecimal), $marketDecimal);
                                }

                                if (bccomp($robot['robot_max_price'], $tradePrice, $marketDecimal) < 0) {
                                    
                                    $tradePrice = $robot['robot_max_price'];
                                }

                                if (bccomp($robot['robot_min_price'], $tradePrice, $marketDecimal) > 0) {
                                    
                                    $tradePrice = $robot['robot_min_price'];
                                }
                            }

                            $ret = $this->ves_model->order_depth($marketSymbol, $tradeDepth);

                            if ($ret['code'] > 0) {

                                $tradeAmountTemp = 0;

                                if ($tradeType === 1 && count($ret['data']['bids'])) {
                                    
                                    foreach ($ret['data']['bids'] as $bid) {
                                        
                                        if (bccomp($tradePrice, $bid[0], $marketDecimal) <= 0) {
                                            
                                            $tradeAmountTemp = bcadd($tradeAmountTemp, $bid[1], $marketDecimal);
                                        }
                                    }
                                }

                                if ($tradeType === 2 && count($ret['data']['asks'])) {
                                    
                                    foreach ($ret['data']['asks'] as $ask) {
                                        
                                        if (bccomp($tradePrice, $ask[0], $marketDecimal) >= 0) {
                                            
                                            $tradeAmountTemp = bcadd($tradeAmountTemp, $ask[1], $marketDecimal);
                                        }
                                    }
                                }

                                if (bccomp($tradeAmountTemp, 0, $marketDecimal) > 0) {
                                    
                                    $tradeAmount = bcadd($tradeAmountTemp, $tradeAmount, $marketDecimal);
                                }
                            }

                            $ret = $this->ves_model->order_put_limit($tradeUser, $marketSymbol, $tradeType, $tradeAmount, $tradePrice, $marketTakerFee, $marketMakerFee, 'robot');

                            echo "\r\n[ " . date('Y-m-d H:i:s') . " ] 机器人" . ($tradeType === 1 ? '卖' : '买') . "单 [ " . $marketSymbol . " ] [price: " . $tradePrice . "] [amount: " . $tradeAmount . "]";

                            if ($ret['code'] > 0) {
                                
                                echo " [ success ]";

                                if ($cron_end_update) {
                                    
                                    $robotUpdate = array(

                                        'robot_id' => $robot['robot_id'],
                                        'robot_cron' => 0
                                    );

                                    $this->robot_model->update($robotUpdate);
                                }
                            }else{

                                echo " [ " . $ret['data'] . " ]";;
                            }
                        }

                        usleep(500000);
                    }
                }
            }

            sleep(1);
        }
    }


    public function usd(){

        while (TRUE) {
            
            $marketList = $this->market_model->getAllMarketList(2);

            if ($marketList && count($marketList)) {
                
                foreach ($marketList as $marketItem) {
                    
                    if ($marketItem['market_money_symbol'] == 'USDT') {
                        
                        $ret = $this->ves_model->market_last($marketItem['market_stock_symbol'] . $marketItem['market_money_symbol']);

                        if ($ret && isset($ret['code']) && $ret['code'] > 0) {
                            
                            $coinItem = array(

                                'coin_id' => $marketItem['market_stock_coin'],
                                'coin_usd' => $ret['data']
                            );

                            echo "\r\n[ " . date('Y-m-d H:i:s') . " ] 更新币种单价 [ " . $marketItem['market_stock_symbol'] . " ] [ " . $ret['data'] . " USD ]";

                            if ($this->coin_model->update($coinItem)) {
                                
                                echo ' [ 成功 ]';
                            }else{

                                echo ' [ 失败 ]';
                            }
                        }
                    }

                    sleep(1);
                }
            }
        }
    }


    public function dm(){

        bcscale($this->config->item('ex_asset_scale'));

        while (TRUE) {
            
            //获取合约列表
            $dmList = $this->dm_model->getActiveDm();

            if ($dmList && count($dmList)) {
                
                foreach ($dmList as $dmItem) {

                    $dm = $this->dm_model->one($dmItem['dm_id']);

                    if ($dm['dm_status'] == 1 && $dm['dm_close'] == 0) {
                        
                        echo "\r\n[ " . date('Y-m-d H:i:s') . " ] [ " . $dm['dm_coin_symbol'] . " 合约 ] [ " . $dm['dm_id'] ." ]";

                        //获取合约对应的用户资产
                        $userDmCoinAsset = $this->asset_model->getUserDmAsset($dm['dm_user'], $dm['dm_coin']);

                        if ($userDmCoinAsset) {

                            $marketSymbol = $dm['dm_coin_symbol'] . $this->config->item('dm_money_symbol');
                            
                            //已开仓合约
                            if ($dm['dm_open'] == 1) {

                                echo ' [ 已开仓 ]';
                                
                                //获取最新强平价
                                $dm['dm_close_future_price'] = $this->dm_model->computFutureClosePrice(

                                    bcadd($userDmCoinAsset['asset_active'], $dm['dm_order_amount']),
                                    $dm['dm_open_amount'],
                                    $dm['dm_direction'],
                                    $dm['dm_open_price']
                                );

                                //获取上一次校验距当前时间差之内的行情
                                $timeSub = time() - $dm['dm_last_check_time'];
                                $ret = $this->ves_model->market_status($marketSymbol, $timeSub);

                                if ($ret && isset($ret['code']) && $ret['code'] > 0) {

                                    //强平条件变量
                                    $mustClose = FALSE;
                                    $mustPrice = FALSE;

                                    if ($ret['data']['low'] != '0' && $ret['data']['high'] != '0') {
                                        
                                        //多单
                                        if ($dm['dm_direction'] == 1) {

                                            echo ' [ 方向: 多 ]';
                                            
                                            //最低价低于强平价，即爆仓
                                            if (bccomp($ret['data']['low'], $dm['dm_close_future_price']) <= 0) {
                                                
                                                $mustClose = TRUE;
                                                $mustPrice = $ret['data']['low'];
                                            }
                                        //空单
                                        }else{

                                            echo ' [ 方向: 空 ]';

                                            //最高价高于强平价，即爆仓
                                            if (bccomp($ret['data']['high'], $dm['dm_close_future_price']) >= 0) {
                                                
                                                $mustClose = TRUE;
                                                $mustPrice = $ret['data']['high'];
                                            }
                                        }
                                    }

                                    echo '[ 当前价: ' . $ret['data']['last'] . ' ] [ 爆仓价: ' . $dm['dm_close_future_price'] . ' ]';

                                    if ($mustClose) {
                                        
                                        if ($this->dm_model->closeDm($dm, TRUE, $mustPrice)) {
                                            
                                            echo ' [ 爆仓成功 ]';
                                        }else{

                                            echo ' [ 爆仓失败 ]';
                                        }
                                    }else{

                                        $dm['dm_profit'] = $this->dm_model->computDmProfit($dm, $ret['data']['last']);
                                        $dm['dm_hold_amount'] = bcadd($dm['dm_open_amount'], $dm['dm_profit']);
                                        $dm['dm_last_check_time'] = time() - 1;

                                        echo ' [ 盈亏: ' . $dm['dm_profit'] . ' ]';

                                        //更新的时候，去除一些字段，防止前台平仓这边继续更新为不平仓
                                        unset($dm['dm_close']);
                                        unset($dm['dm_close_future_price']);
                                        unset($dm['dm_close_price']);
                                        unset($dm['dm_close_type']);
                                        unset($dm['dm_close_time']);
                                        unset($dm['dm_status']);

                                        if ($this->dm_model->update($dm)) {
                                            
                                            echo ' [ 更新成功 ]';
                                        }else{

                                            echo ' [ 更新失败 ]';
                                        }
                                    }
                                }
                            }else{

                                //未开仓委托
                                $timeSub = time() - $dm['dm_last_check_time'];
                                $ret = $this->ves_model->market_status($marketSymbol, $timeSub);

                                if ($ret && isset($ret['code']) && $ret['code'] > 0) {

                                    $openStatus = FALSE;

                                    if ($ret['data']['low'] != '0' && $ret['data']['high'] != '0') {

                                        if ($dm['dm_direction'] == 1) {
                                            
                                            if (bccomp($ret['data']['low'], $dm['dm_open_price']) <= 0) {
                                                
                                                $openStatus = TRUE;
                                            }
                                        }else{

                                            if (bccomp($ret['data']['high'], $dm['dm_open_price']) >= 0) {
                                                
                                                $openStatus = TRUE;
                                            }
                                        }
                                    }

                                    if ($openStatus) {
                                        
                                        $this->dm_model->openDm($dm);
                                    }else{

                                        $dm['dm_last_check_time'] = time() - 1;

                                        //更新的时候，去除一些字段，防止前台取消订单这边继续更新为不取消
                                        unset($dm['dm_status']);

                                        $this->dm_model->update($dm);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            sleep(1);
        }
    }


    private function randomFloat($min, $max, $decimal){

        return bcadd($min, bcmul(bcdiv(mt_rand(), mt_getrandmax(), $decimal), bcsub($max, $min, $decimal), $decimal), $decimal);
    }
}
