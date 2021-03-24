<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * ves 交互模型
 */
class Ves_model extends MY_Model{


    public function balance_query($user_id, $coin_symbol = FALSE){

        $params[] = intval($user_id);

        if ($coin_symbol) {
            
            $params[] = $coin_symbol . '';
        }

        return $this->do_rpc('balance.query', $params);
    }


    public function balance_update($user_id, $coin_symbol, $business, $business_id, $change, $detail = array()){

        $params = [intval($user_id), $coin_symbol . '', $business . '', intval($business_id), $change . '', $detail];

        return $this->do_rpc('balance.update', $params);
    }


    public function balance_history($user_id, $coin_symbol, $business = '', $offset = 0, $limit = 100){

        $params = [intval($user_id), $coin_symbol . '', $business . '', 0, APP_TIME, intval($offset), intval($limit)];

        return $this->do_rpc('balance.history', $params);
    }


    public function market_kline($market_symbol, $start, $end, $interval){

        $params = [$market_symbol . '', intval($start), intval($end), intval($interval)];

        return $this->do_rpc('market.kline', $params);
    }


    public function market_last($market_symbol){

        $params = [$market_symbol . ''];

        return $this->do_rpc('market.last', $params);
    }


    public function market_deals($market_symbol, $limit = 10, $last_id = 0){

        $params = [$market_symbol . '', intval($limit), intval($last_id)];

        return $this->do_rpc('market.deals', $params);
    }


    public function market_user_deals($user_id, $market_symbol, $offset = 0, $limit = 100){

        $params = [intval($user_id), $market_symbol . '', intval($offset), intval($limit)];

        return $this->do_rpc('market.user_deals', $params);
    }


    public function market_status($market_symbol, $period = 10){

        $params = [$market_symbol . '', intval($period)];

        return $this->do_rpc('market.status', $params);
    }


    public function market_status_today($market_symbol){

        $params = [$market_symbol . ''];

        return $this->do_rpc('market.status_today', $params);
    }


    public function market_list(){

        $params = [];

        return $this->do_rpc('market.list', $params);
    }


    public function order_book($market_symbol, $side, $offset = 0, $limit = 100){

        $params = [$market_symbol . '', intval($side), intval($offset), intval($limit)];

        return $this->do_rpc('order.book', $params);
    }


    public function order_depth($market_symbol, $limit = 5, $interval = 0){

        $params = [$market_symbol . '', intval($limit), $interval . ''];

        return $this->do_rpc('order.depth', $params);
    }


    public function order_pending($user_id, $market_symbol, $offset = 0, $limit = 100){

        $params = [intval($user_id), $market_symbol . '', intval($offset), intval($limit)];

        return $this->do_rpc('order.pending', $params);
    }


    public function order_finished($user_id, $market_symbol, $start_time = 0, $end_time = APP_TIME, $offset = 0, $limit = 100, $side = 0){

        $params = [intval($user_id), $market_symbol . '', intval($start_time), intval($end_time), intval($offset), intval($limit), intval($side)];

        return $this->do_rpc('order.finished', $params);
    }


    public function order_finished_detail($order_id){

        $params = [intval($order_id)];

        return $this->do_rpc('order.finished_detail', $params);
    }


    public function order_cancel($user_id, $market_symbol, $order_id){

        $params = [intval($user_id), $market_symbol . '', intval($order_id)];

        return $this->do_rpc('order.cancel', $params);
    }


    public function order_put_limit($user_id, $market_symbol, $side, $amount, $pride, $taker_fee_rate, $maker_fee_rate, $source = ''){

        $params = [intval($user_id), $market_symbol . '', intval($side), $amount . '', $pride . '', $taker_fee_rate . '', $maker_fee_rate . '' , $source . ''];

        return $this->do_rpc('order.put_limit', $params);
    }


    public function order_put_market($user_id, $market_symbol, $side, $amount, $taker_fee_rate, $source = ''){

        $params = [intval($user_id), $market_symbol . '', intval($side), $amount . '', $taker_fee_rate . '', $source . ''];

        return $this->do_rpc('order.put_market', $params);
    }


    /**
     * 将交易对的价格推到指定的价格
     * @param  string $market_symbol 交易对
     * @param  float  $target_price  价格
     */
    public function put_price_target($market_symbol, $target_price){

        for ($i = 0; $i < $this->config->item('dm_pin_loop'); $i ++) {
            
            $this->order_put_limit(

                1,
                $market_symbol,
                mt_rand(1, 2),
                $this->config->item('dm_pin_amount'),
                $target_price,
                0,
                0
            );

            usleep(500000);
        }

        return TRUE;
    }


    /*请求*/
    public function do_rpc($method, $params){

        try{
            $NODE_HOST = $this->config->item('ves_host');
            $data = array(
                'jsonrpc' => "2.0",
                'method' => $method,
                'params' => $params,
                'id' => APP_TIME,
            );
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $NODE_HOST);
            curl_setopt($ch, CURLOPT_POST, 1);
            $httpHeader[] = 'Content-Type:application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data) );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false); //处理http证书问题
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 300);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $ret = curl_exec($ch);
            if (false === $ret) {
                $code = 0;
                $rst = curl_errno($ch);
            }else{
                $ret = json_decode($ret);
                if(!empty($ret->error->code)){
                    $code = 0;
                    $rst = $ret->error->message;
                }else{
                    $code = 1;
                    $rst = json_decode(json_encode($ret->result), true);
                }
            }
            curl_close($ch);
        }catch(Exception $e){
            $code = 0;
            $rst = $e->getMessage();
        }
        return array(
            'code' => $code,
            'data' => $rst
        );
    }
}
