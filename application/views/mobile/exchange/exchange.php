<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <title><?php echo $market['market_stock_symbol'] . '/' . $market['market_money_symbol'] . ' - ' . $_SESSION['SYSCONFIG']['sysconfig_site_name']; ?></title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <link rel='icon' href='/favicon.ico' type='image/x-ico' />

        <link rel="stylesheet" href="<?php echo base_url('static/layui/css'); ?>/layui.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/mobile'); ?>/style/style.css?v=1.3" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/mobile'); ?>/style/tv.css" />

        <!--[if lt IE 9]>
        <script src="<?php echo base_url('static/mobile'); ?>/js/css3.js"></script>
        <script src="<?php echo base_url('static/mobile'); ?>/js/html5.js"></script>
        <![endif]-->
    </head>
    <body>

        <?php $this->load->view('mobile/header'); ?>

        <div class="body_box">
            <style type="text/css">
                html,body{ height: 100%; }

                header{ height: 50px; background: #191a1f; padding: 0px 10px; }
                header .kline_btn{ display: block; float: right; font-size: 18px; color: #d5def2; line-height: 48px; font-weight: bold; padding-left: 20px; }
                header .market_list_btn{ float: left; font-size: 17px; color: #d5def2; line-height: 50px; font-weight: bold; }
                header .current_market_rate{ float: left; font-size: 12px; line-height: 20px; margin-left: 10px; color: #05c19e; background: rgba(5, 193, 158, .1); padding: 0px 5px; border-radius: 3px; margin-top: 15px; }
                header .current_market_rate.down{ color: #e04545; background: rgba(212, 48, 42, .1); }
                
                .trade_box{ margin-top: 10px; }
                .trade_box .trade_left{ width: 57%; float: left; }
                .trade_box .trade_right{ width: 43%; float: right; }
                .trade_box .trade_right .price_list_item_price{ float: left; width: 50%; font-size: 12px; text-align: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
                .trade_box .trade_right .price_list_item_count{ float: right; width: calc(50% - 10px); font-size: 12px; text-align: right; padding-right: 10px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
                .trade_box .trade_right .price_list_title_item{ color: #697080; line-height: 20px; }
                .trade_box .trade_right .price_list_item_box{ margin-top: 5px; }
                .trade_box .trade_right .price_list_item{ position: relative; margin-bottom: 1px; line-height: 25px; }
                .trade_box .trade_right .price_list_item .price_list_item_count{ color: #a7b7c7; }
                .trade_box .trade_right .price_list_item .amount_sum_rate_shadow{ position: absolute; top: 0px; right: 0px; height: 100%; transition: 0s; -moz-transition: 0s; -webkit-transition: 0s; -o-transition: 0s; max-width: 100%; }
                .trade_box .trade_right .price_list_sell_item .price_list_item_price{ color: #e04545; }
                .trade_box .trade_right .price_list_sell_item .amount_sum_rate_shadow{ background: rgba(212, 48, 42, .1); }
                .trade_box .trade_right .price_list_buy_item .price_list_item_price{ color: #05c19e; }
                .trade_box .trade_right .price_list_buy_item .amount_sum_rate_shadow{ background: rgba(5, 193, 158, .1); }
                .trade_box .trade_right .current_market_price{ line-height: 30px; font-size: 16px; color: #05c19e; font-weight: bold; }
                .trade_box .trade_right .current_market_price.down{ color: #e04545; }

                .trade_box .trade_left .exchange_box{ padding: 0px 15px 0px 10px; }
                .trade_box .trade_left .exchange_tab_item{ width: calc((100% - 10px) / 2); text-align: center; font-size: 12px; line-height: 40px; background: #34363f; color: #aeb9d8; border-radius: 3px; }
                .trade_box .trade_left .exchange_tab_buy_btn{ float: left; }
                .trade_box .trade_left .exchange_tab_buy_btn.active{ color: #FFF; background: #05c19e; font-size: 16px; }
                .trade_box .trade_left .exchange_tab_sell_btn{ float: right; }
                .trade_box .trade_left .exchange_tab_sell_btn.active{ color: #FFF; background: #e04545; font-size: 16px; }
                .trade_box .trade_left .exchange_tab_content_item_box{ margin-top: 15px; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item{ display: none; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item.active{ display: block; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .input_ele_box{ border: #34363f solid 1px; border-radius: 3px; position: relative; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .select_exchange_type{ display: block; appearance: none; -moz-appearance: none; -webkit-appearance: none; line-height: 40px; font-size: 12px; cursor: pointer; border-radius: 3px; color: #FFF; background: rgba(53, 124, 225, 0.21); text-align: left; text-align-last: left; width: 100%; box-sizing: border-box; padding-left: 10px; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .input_ele_box .input_ele{ display: block;  height: 18px; line-height: 18px; width: calc(100% - 10px); padding: 11px 0px 11px 10px; font-size: 12px;  caret-color: #357ce1; color: #FFF; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .input_ele_box .input_ele:focus{ border-color: #357ce1; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .input_ele_box .input_ele::-webkit-input-placeholder{ color: #697080; font-size: 12px; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .input_ele_box .input_ele:-moz-placeholder{ color: #697080; font-size: 12px; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .input_ele_box .input_ele::-moz-placeholder{ color: #697080; font-size: 12px; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .input_ele_box .input_ele:-ms-input-placeholder{ color: #697080; font-size: 12px; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .input_ele_box .input_ele.readonly{ background: #191a1f; color: #aeb9d8; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .input_ele_box .input_symbol_text{ position: absolute; line-height: 40px; font-size: 12px; color: #697080; top: 0px; right: 10px; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .input_text{ font-size: 12px; color: #aeb9d8; line-height: 25px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .input_text *{ font-size: 12px; color: #aeb9d8; line-height: 25px; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .hold_line{ height: 15px; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .submit_trade_btn{ display: block; line-height: 40px; font-size: 14px; color: #FFF; border-radius: 3px; text-align: center; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .submit_trade_btn.off{ background: #34363f; color: #aeb9d8; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_buy_box .submit_trade_btn{ background: #05c19e; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_sell_box .submit_trade_btn{ background: #e04545; }

            </style>

            <header>
                <a class="kline_btn" href="/exchange/<?php echo strtolower($market['market_stock_symbol']); ?>/<?php echo strtolower($market['market_money_symbol']); ?>?mobile_kline=1&kline_from=exchange"><i class="layui-icon layui-icon-chart"></i></a>
                <div class="market_list_btn">
                    <i class="layui-icon layui-icon-spread-left"></i>
                    <?php echo $market['market_stock_symbol']; ?>/<?php echo $market['market_money_symbol']; ?>
                </div>
                <div class="current_market_rate">--</div>
                <div class="clear"></div>
            </header>

            <div class="trade_box">
                <div class="trade_left">
                    <div class="exchange_box">
                        <div>
                            <div class="exchange_tab_item exchange_tab_buy_btn active" target-content="exchange_buy_box"><?php echo lang('view_mobile_exchange_1'); ?></div>
                            <div class="exchange_tab_item exchange_tab_sell_btn " target-content="exchange_sell_box"><?php echo lang('view_mobile_exchange_2'); ?></div>
                            <div class="clear"></div>
                        </div>
                        <?php if($_SESSION['mobile_trade_type'] == 'limit'){ ?>
                        <div class="exchange_tab_content_item_box exchange_limit">
                            <div class="exchange_tab_content_item exchange_buy_box active" id="exchange_buy_box">
                                <select class="select_exchange_type">
                                    <option value="limit" <?php echo $_SESSION['mobile_trade_type'] == 'limit' ? 'selected' : ''; ?>><?php echo lang('view_mobile_exchange_3'); ?></option>
                                    <option value="market" <?php echo $_SESSION['mobile_trade_type'] == 'market' ? 'selected' : ''; ?>><?php echo lang('view_mobile_exchange_4'); ?></option>
                                </select>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele compute" placeholder="<?php echo lang('view_mobile_exchange_5'); ?>"  id="limit_buy_price" data-price="#limit_buy_price" data-count="#limit_buy_count" data-amount="#limit_buy_amount">
                                    <div class="input_symbol_text"><?php echo $market['market_money_symbol']; ?></div>
                                </div>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele compute" placeholder="<?php echo lang('view_mobile_exchange_6'); ?>"  id="limit_buy_count" data-price="#limit_buy_price" data-count="#limit_buy_count" data-amount="#limit_buy_amount">
                                    <div class="input_symbol_text"><?php echo $market['market_stock_symbol']; ?></div>
                                </div>
                                <div class="input_text"><?php echo lang('view_mobile_exchange_7'); ?> <span class="<?php echo $market['market_money_symbol']; ?>_balance">0.00000000</span> <?php echo $market['market_money_symbol']; ?></div>
                                <div class="hold_line"></div>
                                <div class="input_text"><?php echo lang('view_mobile_exchange_8'); ?> <span id="limit_buy_amount">0.00000000</span> <?php echo $market['market_money_symbol']; ?></div>
                                <?php if(isset($_SESSION['USER'])){ ?>
                                    <div class="submit_trade_btn limit_trade_btn" data-price="#limit_buy_price" data-count="#limit_buy_count" data-type="buy"><?php echo lang('view_mobile_exchange_9'); ?> <?php echo $market['market_stock_symbol']; ?></div>
                                <?php }else{ ?>
                                    <a class="submit_trade_btn" href="/account/login"><?php echo lang('view_mobile_exchange_11'); ?></a>
                                <?php } ?>
                                
                            </div>
                            <div class="exchange_tab_content_item exchange_sell_box" id="exchange_sell_box">
                                <select class="select_exchange_type">
                                    <option value="limit" <?php echo $_SESSION['mobile_trade_type'] == 'limit' ? 'selected' : ''; ?>><?php echo lang('view_mobile_exchange_12'); ?></option>
                                    <option value="market" <?php echo $_SESSION['mobile_trade_type'] == 'market' ? 'selected' : ''; ?>><?php echo lang('view_mobile_exchange_13'); ?></option>
                                </select>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele compute" placeholder="<?php echo lang('view_mobile_exchange_14'); ?>"  id="limit_sell_price" data-price="#limit_sell_price" data-count="#limit_sell_count" data-amount="#limit_sell_amount">
                                    <div class="input_symbol_text"><?php echo $market['market_money_symbol']; ?></div>
                                </div>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele compute" placeholder="<?php echo lang('view_mobile_exchange_15'); ?>"  id="limit_sell_count" data-price="#limit_sell_price" data-count="#limit_sell_count" data-amount="#limit_sell_amount">
                                    <div class="input_symbol_text"><?php echo $market['market_stock_symbol']; ?></div>
                                </div>
                                <div class="input_text"><?php echo lang('view_mobile_exchange_16'); ?> <span class="<?php echo $market['market_stock_symbol']; ?>_balance">0.00000000</span> <?php echo $market['market_stock_symbol']; ?></div>
                                <div class="hold_line"></div>
                                <div class="input_text"><?php echo lang('view_mobile_exchange_17'); ?> <span id="limit_sell_amount">0.00000000</span> <?php echo $market['market_money_symbol']; ?></div>
                                <?php if(isset($_SESSION['USER'])){ ?>
                                    <div class="submit_trade_btn limit_trade_btn" data-price="#limit_sell_price" data-count="#limit_sell_count" data-type="sell"><?php echo lang('view_mobile_exchange_18'); ?> <?php echo $market['market_stock_symbol']; ?></div>
                                <?php }else{ ?>
                                    <a class="submit_trade_btn" href="/account/login"><?php echo lang('view_mobile_exchange_19'); ?></a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php }else{ ?>
                        <div class="exchange_tab_content_item_box exchange_market">
                            <div class="exchange_tab_content_item exchange_buy_box active" id="exchange_buy_box">
                                <select class="select_exchange_type">
                                    <option value="limit" <?php echo $_SESSION['mobile_trade_type'] == 'limit' ? 'selected' : ''; ?>><?php echo lang('view_mobile_exchange_20'); ?></option>
                                    <option value="market" <?php echo $_SESSION['mobile_trade_type'] == 'market' ? 'selected' : ''; ?>><?php echo lang('view_mobile_exchange_21'); ?></option>
                                </select>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele readonly" value="" placeholder="<?php echo lang('view_mobile_exchange_22'); ?>" readonly>
                                </div>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele" placeholder="<?php echo lang('view_mobile_exchange_23'); ?>"  id="market_buy_count">
                                    <div class="input_symbol_text"><?php echo $market['market_money_symbol']; ?></div>
                                </div>
                                <div class="input_text"><?php echo lang('view_mobile_exchange_24'); ?> <span class="<?php echo $market['market_money_symbol']; ?>_balance">0.00000000</span> <?php echo $market['market_money_symbol']; ?></div>
                                <div class="hold_line"></div>
                                <div class="input_text">&nbsp;</div>
                                <?php if(isset($_SESSION['USER'])){ ?>
                                    <div class="submit_trade_btn market_trade_btn" data-input="#market_buy_count" data-type="buy"><?php echo lang('view_mobile_exchange_25'); ?> <?php echo $market['market_stock_symbol']; ?></div>
                                <?php }else{ ?>
                                    <a class="submit_trade_btn" href="/account/login"><?php echo lang('view_mobile_exchange_26'); ?></a>
                                <?php } ?>
                                
                            </div>
                            <div class="exchange_tab_content_item exchange_sell_box" id="exchange_sell_box">
                                <select class="select_exchange_type">
                                    <option value="limit" <?php echo $_SESSION['mobile_trade_type'] == 'limit' ? 'selected' : ''; ?>><?php echo lang('view_mobile_exchange_27'); ?></option>
                                    <option value="market" <?php echo $_SESSION['mobile_trade_type'] == 'market' ? 'selected' : ''; ?>><?php echo lang('view_mobile_exchange_28'); ?></option>
                                </select>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele readonly" value="" placeholder="<?php echo lang('view_mobile_exchange_29'); ?>" readonly>
                                </div>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele" placeholder="<?php echo lang('view_mobile_exchange_30'); ?>"  id="market_sell_count">
                                    <div class="input_symbol_text"><?php echo $market['market_stock_symbol']; ?></div>
                                </div>
                                <div class="input_text"><?php echo lang('view_mobile_exchange_31'); ?> <span class="<?php echo $market['market_stock_symbol']; ?>_balance">0.00000000</span> <?php echo $market['market_stock_symbol']; ?></div>
                                <div class="hold_line"></div>
                                <div class="input_text">&nbsp;</div>
                                <?php if(isset($_SESSION['USER'])){ ?>
                                    <div class="submit_trade_btn market_trade_btn" data-input="#market_sell_count" data-type="sell"><?php echo lang('view_mobile_exchange_32'); ?> <?php echo $market['market_stock_symbol']; ?></div>
                                <?php }else{ ?>
                                    <a class="submit_trade_btn" href="/account/login"><?php echo lang('view_mobile_exchange_33'); ?></a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="trade_right">
                    <div class="price_list_title_box">
                        <div class="price_list_title_item price_list_item_price"><?php echo lang('view_mobile_exchange_34'); ?></div>
                        <div class="price_list_title_item price_list_item_count"><?php echo lang('view_mobile_exchange_35'); ?></div>
                        <div class="clear"></div>
                    </div>
                    <div class="price_list_item_box">
                        <div class="price_list_item price_list_sell_item" id="bid_price_item_4">
                            <div class="price_list_item_price">--</div>
                            <div class="price_list_item_count">--</div>
                            <div class="clear"></div>
                            <div class="amount_sum_rate_shadow"></div>
                        </div>
                        <div class="price_list_item price_list_sell_item" id="bid_price_item_3">
                            <div class="price_list_item_price">--</div>
                            <div class="price_list_item_count">--</div>
                            <div class="clear"></div>
                            <div class="amount_sum_rate_shadow"></div>
                        </div>
                        <div class="price_list_item price_list_sell_item" id="bid_price_item_2">
                            <div class="price_list_item_price">--</div>
                            <div class="price_list_item_count">--</div>
                            <div class="clear"></div>
                            <div class="amount_sum_rate_shadow"></div>
                        </div>
                        <div class="price_list_item price_list_sell_item" id="bid_price_item_1">
                            <div class="price_list_item_price">--</div>
                            <div class="price_list_item_count">--</div>
                            <div class="clear"></div>
                            <div class="amount_sum_rate_shadow"></div>
                        </div>
                        <div class="price_list_item price_list_sell_item" id="bid_price_item_0">
                            <div class="price_list_item_price">--</div>
                            <div class="price_list_item_count">--</div>
                            <div class="clear"></div>
                            <div class="amount_sum_rate_shadow"></div>
                        </div>

                        <div class="current_market_price">--</div>

                        <div class="price_list_item price_list_buy_item" id="ask_price_item_0">
                            <div class="price_list_item_price">--</div>
                            <div class="price_list_item_count">--</div>
                            <div class="clear"></div>
                            <div class="amount_sum_rate_shadow"></div>
                        </div>
                        <div class="price_list_item price_list_buy_item" id="ask_price_item_1">
                            <div class="price_list_item_price">--</div>
                            <div class="price_list_item_count">--</div>
                            <div class="clear"></div>
                            <div class="amount_sum_rate_shadow"></div>
                        </div>
                        <div class="price_list_item price_list_buy_item" id="ask_price_item_2">
                            <div class="price_list_item_price">--</div>
                            <div class="price_list_item_count">--</div>
                            <div class="clear"></div>
                            <div class="amount_sum_rate_shadow"></div>
                        </div>
                        <div class="price_list_item price_list_buy_item" id="ask_price_item_3">
                            <div class="price_list_item_price">--</div>
                            <div class="price_list_item_count">--</div>
                            <div class="clear"></div>
                            <div class="amount_sum_rate_shadow"></div>
                        </div>
                        <div class="price_list_item price_list_buy_item" id="ask_price_item_4">
                            <div class="price_list_item_price">--</div>
                            <div class="price_list_item_count">--</div>
                            <div class="clear"></div>
                            <div class="amount_sum_rate_shadow"></div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>

            <style type="text/css">
                
                .order_box{ margin-top: 15px; }
                .order_box .order_tab_box{ padding: 0px 10px }
                .order_box .order_tab_box .order_tab_item{ width: 50%; float: left; text-align: center; color: #aeb9d8; font-size: 12px; line-height: 40px; border-radius: 3px 3px 0px 0px; border-bottom: #1f2126 solid 2px; }
                .order_box .order_tab_box .order_tab_item.active{ border-bottom: #357ce1 solid 2px; color: #357ce1; font-size: 16px; font-weight: bold; }
                .order_box .order_tab_box .order_tab_content_box{ margin: 10px; }
                .order_box .order_tab_content_box .order_tab_content_item{ display: none; padding: 10px; }
                .order_box .order_tab_content_box .order_tab_content_item.active{ display: block; }
                .order_box .order_tab_content_box .order_tab_content_item .order_item_direction{ color: #aeb9d8; font-size: 12px; line-height: 40px; width: 20%; text-align: left; float: left; }
                .order_box .order_tab_content_box .order_tab_content_item .order_item_price{ color: #aeb9d8; font-size: 12px; line-height: 40px; width: 30%; text-align: left; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
                .order_box .order_tab_content_box .order_tab_content_item .order_item_count{ color: #aeb9d8; font-size: 12px; line-height: 40px; width: 35%; text-align: left; float: left; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
                .order_box .order_tab_content_box .order_tab_content_item .order_item_action{ color: #aeb9d8; font-size: 12px; line-height: 40px; width: 15%; text-align: right; float: left; }
                .order_box .order_tab_content_box .order_tab_content_item .order_item_action span{ color: #357ce1; }
                .order_box .order_tab_content_box .order_tab_content_item .buy{ color: #05c19e; }
                .order_box .order_tab_content_box .order_tab_content_item .sell{ color: #e04545; }
                .order_history_item_title *, .order_current_item_title *{ font-size: 12px; color: #697080 !important; }
                #order_tab_content_history .order_item_direction{ width: 30% !important; }
                #order_tab_content_history .order_item_price{ width: 35% !important; }
                #order_tab_content_history .order_item_count{ width: 35% !important; text-align: right; }

            </style>

            <div class="order_box">
                <div class="order_tab_box">
                    <div class="order_tab_item active" target-content="order_tab_content_current"><?php echo lang('view_mobile_exchange_36'); ?></div>
                    <div class="order_tab_item" target-content="order_tab_content_history"><?php echo lang('view_mobile_exchange_37'); ?></div>
                    <div class="clear"></div>
                </div>
                <div class="order_tab_content_box">
                    <div class="order_tab_content_item active" id="order_tab_content_current">
                        <div class="order_current_item_title">
                            <div class="order_item_direction"><?php echo lang('view_mobile_exchange_38'); ?></div>
                            <div class="order_item_price"><?php echo lang('view_mobile_exchange_39'); ?>(<?php echo $market['market_stock_symbol']; ?>)</div>
                            <div class="order_item_count"><?php echo lang('view_mobile_exchange_40'); ?>(<?php echo $market['market_money_symbol']; ?>)</div>
                            <div class="order_item_action"><?php echo lang('view_mobile_exchange_41'); ?></div>
                            <div class="clear"></div>
                        </div>
                        <!-- 
                        <div class="order_current_item">
                            <div class="order_item_direction"></div>
                            <div class="order_item_price"></div>
                            <div class="order_item_count"></div>
                            <div class="order_item_action">
                                <span data-id="">撤销</span>
                            </div>
                            <div class="clear"></div>
                        </div>
                         -->
                    </div>
                    <div class="order_tab_content_item" id="order_tab_content_history">
                        <div class="order_history_item_title">
                            <div class="order_item_direction"><?php echo lang('view_mobile_exchange_42'); ?></div>
                            <div class="order_item_price"><?php echo lang('view_mobile_exchange_43'); ?>(<?php echo $market['market_money_symbol']; ?>)</div>
                            <div class="order_item_count"><?php echo lang('view_mobile_exchange_44'); ?>(<?php echo $market['market_stock_symbol']; ?>)</div>
                            <div class="clear"></div>
                        </div>
                        <!-- 
                        <div class="order_history_item">
                            <div class="order_item_direction"></div>
                            <div class="order_item_price"></div>
                            <div class="order_item_count"></div>
                            <div class="clear"></div>
                        </div>
                         -->
                    </div>
                </div>
            </div>
        </div>

        <style type="text/css">
            
            .market_list_box_shadow{ position: fixed; left: 0px; top: 0px; height: 100%; width: 100%; background: #FFF; z-index: 998; opacity: .05; display: none; }
            .market_list_box{ position: fixed; left: 0px; top: 0px; height: 100%; width: 70%; background: #191a1f; z-index: 999; overflow-y: auto; display: none; }
            .market_list_box .market_tab_box{ border-bottom: #357ce1 solid 1px; padding: 0px 10px; padding-top: 20px; }
            .market_list_box .market_tab_box .market_tab_item{ line-height: 30px; margin-right: 15px; text-align: center; color: #aeb9d8; font-size: 12px; float: left; font-weight: bold; }
            .market_list_box .market_tab_box .market_tab_item.active{ color: #357ce1; font-size: 16px; }
            .market_list_box .left_bar{ float: left; width: 40%; text-align: left; }
            .market_list_box .right_bar{ float: left; width: 60%; text-align: right; }
            .market_list_box .market_tab_content_item{ display: none; padding-top: 10px; }
            .market_list_box .market_tab_content_item.active{ display: block; }
            .market_list_box .market_tab_content_item .market_line_item{ display: block; height: 50px; border-bottom: #34363f solid 1px; padding: 0px 10px; }
            .market_list_box .market_tab_content_item .market_line_item .left_bar{ font-size: 14px; color: #d5def2; line-height: 50px; }
            .market_list_box .market_tab_content_item .market_line_item .center_bar{ font-size: 14px; color: #d5def2; line-height: 50px; }
            .market_list_box .market_tab_content_item .market_line_item .right_bar{ font-size: 14px; color: #05c19e; line-height: 50px; }
            .market_list_box .market_tab_content_item .market_line_item .right_bar.down{ color: #e04545; }
        </style>
        
        <div class="market_list_box_shadow"></div>
        <div class="market_list_box">
            <div class="market_tab_box">
                <?php if($marketGroup && count($marketGroup)){ $i = 0; foreach($marketGroup as $money => $marketsItem){ ?>
                <div class="market_tab_item <?php echo $i<1?'active':''; ?>" target-content="index_price_list_tab_content_<?php echo $money; ?>"><?php echo $money; ?></div>
                <?php $i++; }} ?>
                <div class="clear"></div>
            </div>
            <?php if($marketGroup && count($marketGroup)){ $i = 0; foreach($marketGroup as $money => $marketsItem){ ?>
            <div class="market_tab_content_item <?php echo $i<1?'active':''; ?>" id="index_price_list_tab_content_<?php echo $money; ?>">
                <?php foreach($marketsItem as $marketItem){ ?>
                <a class="market_line_item" id="market_item_line_<?php echo $marketItem['market_stock_symbol']; ?><?php echo $marketItem['market_money_symbol']; ?>" href="/exchange/<?php echo strtolower($marketItem['market_stock_symbol']); ?>/<?php echo strtolower($marketItem['market_money_symbol']); ?>">
                    <div class="left_bar"><?php echo $marketItem['market_stock_symbol']; ?></div>
                    <div class="right_bar">--</div>
                    <div class="clear"></div>
                </a>
                <?php } ?>
            </div>
            <?php $i++; }} ?>
        </div>

        <?php $this->load->view('mobile/footer'); ?>

        <script src="<?php echo base_url('static'); ?>/mobile/js/bignumber.min.js"></script>

        <script type="text/javascript">

            //当前栏目
            $('footer .navitem.exchange').addClass('active');

            var ws = null;
            var title_text = $('title').text();

            //弹出市场列表
            var market_list_box_shadow = $('.market_list_box_shadow');
            var market_list_box = $('.market_list_box');
            market_list_box.css({left : (0 - market_list_box.width()) + 'px'});
            $('header .market_list_btn').click(function(){

                market_list_box_shadow.stop(true, false).fadeIn();
                market_list_box.stop(true, false).show().animate({left : '0px'});
            });
            market_list_box_shadow.click(function(){
                market_list_box.css({left : (0 - market_list_box.width()) + 'px'});
                market_list_box_shadow.fadeOut();
            });

            $('.price_list_item').click(function(){

                var _price = $(this).find('.price_list_item_price').text();

                if (_price != '--') {

                    $('#limit_buy_price, #limit_sell_price').val(_price);
                }
            });

            //切换限价市价
            $('.select_exchange_type').change(function(){

                var _this = $(this);

                $.ajax({
                    url: '/exchange/select_trade_type',
                    type: 'post',
                    data: {

                        'mobile_trade_type' : _this.val()
                    },
                    success: function (data) {
                        
                        location.reload();
                    }
                });
            });

            //选项卡切换
            $('.market_list_box .market_tab_box .market_tab_item, .trade_box .trade_left .exchange_tab_item, .order_box .order_tab_box .order_tab_item').click(function(){

                var _this = $(this);

                if (! _this.hasClass('active')) {

                    _this.addClass('active').siblings('.active').removeClass('active');
                    $('#' + _this.attr('target-content')).addClass('active').siblings('.active').removeClass('active');
                }
            });

            //监听输入
            $('.trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .input_ele_box .input_ele').keyup(function(){

                var _this = $(this);

                format_input_num(_this[0]);

                if (_this.hasClass('compute')) {

                    computeAmount($(_this.attr('data-price')), $(_this.attr('data-count')), $(_this.attr('data-amount')));
                }
            });

            //限价交易
            $('.limit_trade_btn').click(function(){

                var _this = $(this);

                if (! _this.hasClass('off')) {

                    var _priceObj = $(_this.attr('data-price'));
                    var _countObj = $(_this.attr('data-count'));

                    if(_priceObj.val() == ''){

                        _msg.error('<?php echo lang('view_exchange_65'); ?>' + (_this.attr('data-type') == 'sell' ? '<?php echo lang('view_exchange_66'); ?>' : '<?php echo lang('view_exchange_67'); ?>'));
                        return false;
                    }

                    if(_countObj.val() == ''){

                        _msg.error('<?php echo lang('view_exchange_68'); ?>' + (_this.attr('data-type') == 'sell' ? '<?php echo lang('view_exchange_69'); ?>' : '<?php echo lang('view_exchange_70'); ?>'));
                        return false;
                    }

                    _this.addClass('off');

                    $.ajax({
                        url: '/exchange/trade',
                        type: 'post',
                        data: {
                            'type' : _this.attr('data-type'),
                            'trade_type' : 'limit',
                            'price' : _priceObj.val(),
                            'count' : _countObj.val(),
                            'market_stock' : '<?php echo $market['market_stock_symbol']; ?>',
                            'market_money' : '<?php echo $market['market_money_symbol']; ?>'
                        },
                        dataType: 'json',
                        success: function (data) {
                            
                            if (data.status) {

                                _msg.success(data.message);
                            }else{

                                _msg.error(data.message);
                            }
                        },
                        error: function(){

                            _msg.error('<?php echo lang('view_exchange_71'); ?>');
                        },
                        complete: function(){

                            _this.removeClass('off');
                        }
                    });
                }
            });

            //市价交易
            $('.market_trade_btn').click(function(){

                var _this = $(this);

                if (! _this.hasClass('off')) {

                    var _inputObj = $(_this.attr('data-input'));

                    if(_inputObj.val() == ''){

                        _msg.error('<?php echo lang('view_exchange_72'); ?>' + (_this.attr('data-type') == 'sell' ? '<?php echo lang('view_exchange_73'); ?>' : '<?php echo lang('view_exchange_74'); ?>'));
                        return false;
                    }

                    _this.addClass('off');

                    $.ajax({
                        url: '/exchange/trade',
                        type: 'post',
                        data: {
                            'type' : _this.attr('data-type'),
                            'trade_type' : 'market',
                            'price' : '-',
                            'count' : _inputObj.val(),
                            'market_stock' : '<?php echo $market['market_stock_symbol']; ?>',
                            'market_money' : '<?php echo $market['market_money_symbol']; ?>'
                        },
                        dataType: 'json',
                        success: function (data) {
                            
                            if (data.status) {

                                _msg.success(data.message);
                            }else{

                                _msg.error(data.message);
                            }
                        },
                        error: function(){

                            _msg.error('<?php echo lang('view_exchange_75'); ?>');
                        },
                        complete: function(){

                            _this.removeClass('off');
                        }
                    });
                }
            });

            //计算总额
            function computeAmount(_priceObj, _countObj, _amountObj){

                var _amount = 0;

                if (_priceObj.length > 0 && _countObj.length > 0 && _amountObj.length > 0 && _priceObj.val() != '' && _countObj.val() != '') {

                    var _price = parseFloat(_priceObj.val());
                    var _count = parseFloat(_countObj.val());

                    if (_price > 0 && _count > 0) {

                        _amount = _price * _count;
                    }
                }

                _amountObj.text(_amount.toFixed(8));
            }

            $('#order_tab_content_current').on('click', 'span', function(){

                var _this = $(this);

                if (! _this.hasClass('off')) {

                    _this.addClass('off');

                    $.ajax({
                        url: '/exchange/cancel',
                        type: 'post',
                        data: {
                            'order' : _this.attr('data-id'),
                            'market' : '<?php echo $market['market_stock_symbol']; ?><?php echo $market['market_money_symbol']; ?>'
                        },
                        dataType: 'json',
                        success: function (data) {
                            
                            if (data.status) {

                                _msg.success(data.message);
                                _this.parent().parent().remove();
                            }else{

                                _msg.error(data.message);
                            }
                        },
                        error: function(){

                            _msg.error('<?php echo lang('view_exchange_76'); ?>');
                        },
                        complete: function(){

                            _this.removeClass('off');
                        }
                    });
                }
            });

            <?php if(count($marketSymbolList)){ ?>

                $(window).load(function(){

                    var BN = BigNumber.clone();
                    BN.config({DECIMAL_PLACES : 8});

                    var timeSub = 0;
                    var marketJson = <?php echo json_encode($marketSymbolList); ?>;
                    var marketSymbol = '<?php echo $market['market_stock_symbol'] . $market['market_money_symbol']; ?>';
                    var askMaxAmount = 0;
                    var bidMaxAmount = 0;
                    var askArray = [];
                    var bidArray = [];

                    ws = new WebSocket('<?php echo $this->config->item('ves_ws_host'); ?>');

                    ws.onopen = function(){

                        var _sendContent = JSON.stringify({

                            id : 9,
                            method : 'server.time',
                            params : []
                        });

                        ws.send(_sendContent);

                        var _sendContent = JSON.stringify({

                            id : 1,
                            method : 'today.subscribe',
                            params : marketJson
                        });

                        ws.send(_sendContent);

                        var _sendContent = JSON.stringify({

                            id : 3,
                            method : 'depth.subscribe',
                            params : [marketSymbol, 50, "0"]
                        });

                        ws.send(_sendContent);

                        <?php if(isset($_SESSION['USER'])){ ?>
                            var _sendContent = JSON.stringify({

                                id : 10,
                                method : 'server.auth',
                                params : ['<?php echo $_SESSION['USER']['USER_ACCESS_TOKEN']; ?>', 'web']
                            });

                            ws.send(_sendContent);
                        <?php } ?>
                    }

                    ws.onmessage = function(event){

                        if (typeof event.data === 'string') {
                            
                            var _result = JSON.parse(event.data);

                            if (typeof _result === 'object') {

                                if (_result.id == 9) {

                                    timeSub = parseInt(Date.now() / 1000) - _result.result;
                                }

                                if (_result.id == 10) {

                                    if (_result.result.status == 'success') {

                                        var _sendContent = JSON.stringify({

                                            id : 11,
                                            method : 'order.query',
                                            params : [marketSymbol, 0, 50]
                                        });

                                        ws.send(_sendContent);

                                        var _sendContent = JSON.stringify({

                                            id : 12,
                                            method : 'order.history',
                                            params : [marketSymbol, 0, 0, 0, 50, 0]
                                        });

                                        ws.send(_sendContent);

                                        var _sendContent = JSON.stringify({

                                            id : 14,
                                            method : 'asset.query',
                                            params : ['<?php echo $market['market_stock_symbol']; ?>', '<?php echo $market['market_money_symbol']; ?>']
                                        });

                                        ws.send(_sendContent);

                                        var _sendContent = JSON.stringify({

                                            id : 13,
                                            method : 'asset.subscribe',
                                            params : ['<?php echo $market['market_stock_symbol']; ?>', '<?php echo $market['market_money_symbol']; ?>']
                                        });

                                        ws.send(_sendContent);

                                        var _sendContent = JSON.stringify({

                                            id : 13,
                                            method : 'order.subscribe',
                                            params : [marketSymbol]
                                        });

                                        ws.send(_sendContent);
                                    }
                                }

                                if (_result.id == 14) {

                                    if (_result.error == null) {

                                        for(var _symbol in _result.result){

                                            $('.' + _symbol + '_balance').text(parseFloat(_result.result[_symbol].available).toFixed(8));
                                        }
                                    }
                                }

                                if (_result.method == 'asset.update') {

                                    for(var _symbol in _result.params[0]){

                                        $('.' + _symbol + '_balance').text(parseFloat(_result.params[0][_symbol].available).toFixed(8));
                                    }
                                }

                                if (_result.id == 11) {

                                    if (_result.error == null && _result.result.records.length > 0) {

                                        var _order_html = '';

                                        for(var _index in _result.result.records){

                                            _order_html += 

                                            '<div class="order_current_item order_item_line_' + _result.result.records[_index]['id'] + '">' +
                                                '<div class="order_item_direction ' + (_result.result.records[_index]['side'] == 1 ? 'sell' : 'buy') + '">' + (_result.result.records[_index]['side'] == 1 ? '<?php echo lang('view_exchange_79'); ?>' : '<?php echo lang('view_exchange_80'); ?>') + '</div>' +
                                                '<div class="order_item_price">' + _result.result.records[_index]['price'] + '</div>' +
                                                '<div class="order_item_count">' + _result.result.records[_index]['amount'] + '</div>' +
                                                '<div class="order_item_action">' +
                                                    '<span data-id="' + _result.result.records[_index]['id'] + '"><?php echo lang('view_mobile_exchange_45'); ?></span>' +
                                                '</div>' +
                                                '<div class="clear"></div>' +
                                            '</div>';
                                        }

                                        $('#order_tab_content_current .order_current_item').remove();
                                        $('#order_tab_content_current .order_current_item_title').after(_order_html);
                                    }
                                }

                                if (_result.method == 'order.update') {

                                    if (_result.params[0] == 1) {

                                        var _timeText = unix2datetime((parseInt(_result.params[1]['mtime']) + timeSub) * 1000);
                                        var _rateText = (((_result.params[1]['amount'] - _result.params[1]['left']) / _result.params[1]['amount']) * 100).toFixed(2) + ' %';

                                        var _order_html = 
                                        '<div class="order_current_item order_item_line_' + _result.params[1]['id'] + '">' +
                                            '<div class="order_item_direction ' + (_result.params[1]['side'] == 1 ? 'sell' : 'buy') + '">' + (_result.params[1]['side'] == 1 ? '<?php echo lang('view_exchange_79'); ?>' : '<?php echo lang('view_exchange_80'); ?>') + '</div>' +
                                            '<div class="order_item_price">' + _result.params[1]['price'] + '</div>' +
                                            '<div class="order_item_count">' + _result.params[1]['amount'] + '</div>' +
                                            '<div class="order_item_action">' +
                                                '<span data-id="' + _result.params[1]['id'] + '"><?php echo lang('view_mobile_exchange_46'); ?></span>' +
                                            '</div>' +
                                            '<div class="clear"></div>' +
                                        '</div>';

                                        $('#order_tab_content_current .order_current_item_title').after(_order_html);
                                    }

                                    if (_result.params[0] == 3) {

                                        var _order_line = $('.order_item_line_' + _result.params[1]['id']);

                                        if (_order_line.length > 0) {

                                            _order_line.remove();
                                        }

                                        var _timeText = unix2datetime((parseInt(_result.params[1]['ctime']) + timeSub) * 1000);

                                        var _history_html = 

                                        '<div class="order_history_item">' +
                                            '<div class="order_item_direction ' + (_result.params[1]['side'] == 1 ? 'sell' : 'buy') + '">' + (_result.params[1]['side'] == 1 ? '<?php echo lang('view_exchange_79'); ?>' : '<?php echo lang('view_exchange_80'); ?>') + '</div>' +
                                            '<div class="order_item_price">' + (_result.params[1]['type'] == 2 ? parseFloat((_result.params[1]['deal_money'] / _result.params[1]['deal_stock']).toFixed(8)) : _result.params[1]['price']) + '</div>' +
                                            '<div class="order_item_count">' + _result.params[1]['deal_stock'] + '</div>' +
                                            '<div class="clear"></div>' +
                                        '</div>';

                                        $('#order_tab_content_history .order_history_item_title').after(_history_html);
                                    }
                                }

                                if (_result.id == 12) {

                                    if (_result.error == null && _result.result.records.length > 0) {

                                        var _history_html = '';

                                        for(var _index in _result.result.records){

                                            var _timeText = unix2datetime((parseInt(_result.result.records[_index]['ctime']) + timeSub) * 1000);

                                            _history_html += 

                                            '<div class="order_history_item">' +
                                                '<div class="order_item_direction ' + (_result.result.records[_index]['side'] == 1 ? 'sell' : 'buy') + '">' + (_result.result.records[_index]['side'] == 1 ? '<?php echo lang('view_exchange_79'); ?>' : '<?php echo lang('view_exchange_80'); ?>') + '</div>' +
                                                '<div class="order_item_price">' + (_result.result.records[_index]['type'] == 2 ? parseFloat((_result.result.records[_index]['deal_money'] / _result.result.records[_index]['deal_stock']).toFixed(8)) : _result.result.records[_index]['price']) + '</div>' +
                                                '<div class="order_item_count">' + _result.result.records[_index]['deal_stock'] + '</div>' +
                                                '<div class="clear"></div>' +
                                            '</div>';
                                        }

                                        $('#order_tab_content_history .order_history_item').remove();
                                        $('#order_tab_content_history .order_history_item_title').after(_history_html);
                                    }
                                }

                                if (_result.method == 'today.update') {

                                    var market_line_item = $('#market_item_line_' + _result.params[0]);

                                    market_line_item.children('.right_bar').text(_result.params[1].last);

                                    var _last = BN(_result.params[1].last);
                                    _last = _last.comparedTo(0) == 1 ? _last : 1;
                                    var _rate = _last.minus(BN(_result.params[1].open)).div(_last).times(100).toFixed(2);

                                    var _currentMarket = false;

                                    if (marketSymbol == _result.params[0]) {

                                        $('.current_market_price').text(_result.params[1].last);
                                        $('.current_market_rate').text((_rate > 0 ? '+' : '') + _rate + ' %');

                                        $('title').text(_result.params[1].last + ' ' + title_text);

                                        _currentMarket = true;
                                    }

                                    if (_rate >= 0) {

                                        market_line_item.children('.right_bar').removeClass('down');

                                        if (_currentMarket) {

                                            $('.current_market_price, .current_market_rate').removeClass('down');
                                        }
                                    }else{

                                        market_line_item.children('.right_bar').addClass('down');

                                        if (_currentMarket) {

                                            $('.current_market_price, .current_market_rate').addClass('down');
                                        }
                                    }
                                }

                                if (_result.method == 'depth.update') {

                                    if (_result.params[0]) {

                                        if (typeof _result.params[1].asks === 'object') {

                                            askArray = _result.params[1].asks;

                                            var _amountArray = [];

                                            for(var _index in _result.params[1].asks){

                                                _amountArray.push(_result.params[1].asks[_index][1]);
                                            }

                                            askMaxAmount = Math.max(..._amountArray);

                                            for(var _i = 0; _i < 5; _i ++){

                                                if (typeof _result.params[1].asks[_i] === 'object') {

                                                    $('#bid_price_item_' + _i + ' .price_list_item_price').text(_result.params[1].asks[_i][0]);
                                                    $('#bid_price_item_' + _i + ' .price_list_item_count').text(_result.params[1].asks[_i][1]);
                                                    $('#bid_price_item_' + _i + ' .amount_sum_rate_shadow').css({ width : (_result.params[1].asks[_i][1] / askMaxAmount * 100) + '%' });
                                                }else{

                                                    $('#bid_price_item_' + _i + ' .price_list_item_price').text('--');
                                                    $('#bid_price_item_' + _i + ' .price_list_item_count').text('--');
                                                    $('#bid_price_item_' + _i + ' .amount_sum_rate_shadow').css({ width : '0%' });
                                                }
                                            }
                                        }

                                        if (typeof _result.params[1].bids === 'object') {

                                            bidArray = _result.params[1].bids;

                                            var _amountArray = [];

                                            for(var _index in _result.params[1].bids){

                                                _amountArray.push(_result.params[1].bids[_index][1]);
                                            }

                                            bidMaxAmount = Math.max(..._amountArray);

                                            for(var _i = 0; _i < 5; _i ++){

                                                if (typeof _result.params[1].bids[_i] === 'object') {

                                                    $('#ask_price_item_' + _i + ' .price_list_item_price').text(_result.params[1].bids[_i][0]);
                                                    $('#ask_price_item_' + _i + ' .price_list_item_count').text(_result.params[1].bids[_i][1]);
                                                    $('#ask_price_item_' + _i + ' .amount_sum_rate_shadow').css({ width : (_result.params[1].bids[_i][1] / bidMaxAmount * 100) + '%' });
                                                }else{

                                                    $('#ask_price_item_' + _i + ' .price_list_item_price').text('--');
                                                    $('#ask_price_item_' + _i + ' .price_list_item_count').text('--');
                                                    $('#ask_price_item_' + _i + ' .amount_sum_rate_shadow').css({ width : '0%' });
                                                }
                                            }
                                        }
                                    }else{

                                        if (typeof _result.params[1].asks === 'object') {

                                            for(var _index in _result.params[1].asks){

                                                if (_result.params[1].asks[_index][1] == 0) {

                                                    askArray.splice(_index, 1);
                                                }else{

                                                    if (IsStrInArray(_result.params[1].asks[_index][0], askArray)) {

                                                        askArray[_index] = _result.params[1].asks[_index];
                                                    }else{

                                                        askArray.push(_result.params[1].asks[_index]);
                                                    }
                                                }
                                            }

                                            if (askArray.length > 0) {

                                                askArray.sort(function(x, y){

                                                    return x[0] - y[0];
                                                });

                                                for(var _i = 0; _i < 5; _i ++){

                                                    if (typeof askArray[_i] === 'object') {

                                                        $('#bid_price_item_' + _i + ' .price_list_item_price').text(askArray[_i][0]);
                                                        $('#bid_price_item_' + _i + ' .price_list_item_count').text(askArray[_i][1]);
                                                        $('#bid_price_item_' + _i + ' .amount_sum_rate_shadow').css({ width : (askArray[_i][1] / askMaxAmount * 100) + '%' });
                                                    }else{
                                                        $('#bid_price_item_' + _i + ' .price_list_item_price').text('--');
                                                        $('#bid_price_item_' + _i + ' .price_list_item_count').text('--');
                                                        $('#bid_price_item_' + _i + ' .amount_sum_rate_shadow').css({ width : '0%' });
                                                    }
                                                }
                                            }
                                        }

                                        if (typeof _result.params[1].bids === 'object') {

                                            for(var _index in _result.params[1].bids){

                                                if (_result.params[1].bids[_index][1] == 0) {

                                                    bidArray.splice(_index, 1);
                                                }else{

                                                    if (IsStrInArray(_result.params[1].bids[_index][0], bidArray)) {

                                                        bidArray[_index] = _result.params[1].bids[_index];
                                                    }else{

                                                        bidArray.push(_result.params[1].bids[_index]);
                                                    }
                                                }
                                            }

                                            if (bidArray.length > 0) {

                                                bidArray.sort(function(x, y){

                                                    return y[0] - x[0];
                                                });

                                                for(var _i = 0; _i < 5; _i ++){

                                                    if (typeof bidArray[_i] === 'object') {

                                                        $('#ask_price_item_' + _i + ' .price_list_item_price').text(bidArray[_i][0]);
                                                        $('#ask_price_item_' + _i + ' .price_list_item_count').text(bidArray[_i][1]);
                                                        $('#ask_price_item_' + _i + ' .amount_sum_rate_shadow').css({ width : (bidArray[_i][1] / bidMaxAmount * 100) + '%' });
                                                    }else{

                                                        $('#ask_price_item_' + _i + ' .price_list_item_price').text('--');
                                                        $('#ask_price_item_' + _i + ' .price_list_item_count').text('--');
                                                        $('#ask_price_item_' + _i + ' .amount_sum_rate_shadow').css({ width : '0%' });
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }

                    ws.onclose = function(){

                        ws = new WebSocket('<?php echo $this->config->item('ves_ws_host'); ?>');
                    }

                    function wsPing(){

                        if (ws.readyState == WebSocket.OPEN) {

                            var _sendContent = JSON.stringify({

                                id : 1,
                                method : 'server.ping',
                                params : []
                            });

                            ws.send(_sendContent);
                        }

                        setTimeout(function(){

                            wsPing();
                        }, 5000);
                    }

                    wsPing();
                });

            <?php } ?>


        </script>

    </body>
</html>
