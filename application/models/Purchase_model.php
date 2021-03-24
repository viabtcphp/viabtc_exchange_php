<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 认购订单模型
 */
class Purchase_model extends MY_Model {


    /**
     * 数据表名
     */
    public $table = 'ex_purchase';


    /**
     * 数据表字段缺省值数组，首个元素必须为主键字段
     */
    public $fieldsArray = array(

        //字段 => 缺省值
        'purchase_id' => 0,
        'purchase_user' => 0,
        'purchase_stock_coin' => 0,
        'purchase_money_coin' => 0,
        'purchase_stock_amount' => 0,
        'purchase_money_amount' => 0,
        'purchase_rate' => 0,
        'purchase_time' => 0
    );


    /**
     * 通用联表模板
     */
    private $joinTemp =  array(

        //与币种表联表
        array(

            'table'         => 'ex_coin',
            'on_left'       => 'purchase_stock_coin',
            'on_right'      => 'coin_id',
            'fields'        => array(

                //查询交易币种标识
                'coin_symbol' => 'purchase_stock_symbol'
            )
        ),
        array(

            'table'         => 'ex_coin',
            'on_left'       => 'purchase_money_coin',
            'on_right'      => 'coin_id',
            'fields'        => array(

                //查询结算币种标识
                'coin_symbol' => 'purchase_money_symbol'
            )
        ),
        array(

            'table'         => 'ex_user',
            'on_left'       => 'purchase_user',
            'on_right'      => 'user_id',
            'fields'        => array(

                //查询结算币种标识
                'user_email' => 'purchase_user_email'
            )
        )
    );


    /**
     * 后台获取认购订单列表
     * @return array              返回所有认购订单数组
     */
    public function listPurchase($pageIndex = FALSE, $pageSize = FALSE, $user = FALSE){

        $where = FALSE;

        if ($user) {
            
            $where = '`purchase_user`=' . $user;
        }

        $order = '`purchase_time` DESC';

        return $this->get($pageIndex, $pageSize, $where, $order, $this->joinTemp);
    }
}
