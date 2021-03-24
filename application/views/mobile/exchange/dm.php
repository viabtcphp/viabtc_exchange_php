<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <title><?php echo $market['market_stock_symbol'] . '/' . $market['market_money_symbol'] . ' ' . lang('view_dm_1') . ' - ' . $_SESSION['SYSCONFIG']['sysconfig_site_name']; ?></title>
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
                header .market_list_btn{ float: left; font-size: 17px; color: #FFF; background: #357ce1; line-height: 30px; height: 30px; padding: 0px 5px; border-radius: 3px; overflow: hidden; margin-top: 10px; }
                header .current_market_rate{ float: left; font-size: 14px; line-height: 30px; margin-left: 10px; color: #05c19e; background: rgba(5, 193, 158, .1); padding: 0px 10px; border-radius: 3px; margin-top: 10px; }
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
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .input_text{ font-size: 12px; color: #aeb9d8; line-height: 20px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .input_text *{ font-size: 12px; color: #aeb9d8; line-height: 20px; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .hold_line{ height: 15px; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .hold_line_2{ height: 5px; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .submit_trade_btn{ display: block; line-height: 40px; font-size: 14px; color: #FFF; border-radius: 3px; text-align: center; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_tab_content_item .submit_trade_btn.off{ background: #34363f; color: #aeb9d8; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_buy_box .submit_trade_btn{ background: #05c19e; }
                .trade_box .trade_left .exchange_tab_content_item_box .exchange_sell_box .submit_trade_btn{ background: #e04545; }

            </style>

            <header>
                <a class="kline_btn" href="/exchange/<?php echo strtolower($market['market_stock_symbol']); ?>/<?php echo strtolower($market['market_money_symbol']); ?>?mobile_kline=1&kline_from=futures"><i class="layui-icon layui-icon-chart"></i></a>
                <div class="market_list_btn">
                    <i class="layui-icon layui-icon-spread-left"></i>
                    <?php echo $market['market_stock_symbol']; ?> <?php echo lang('view_mobile_dm_1'); ?>
                </div>
                <div class="current_market_rate">--</div>
                <div class="clear"></div>
            </header>

            <style type="text/css">
                .futures_info{ background: #191a1f; margin-top: 1px; box-sizing: border-box; padding: 10px; }
                .futures_info .info_left{ float: left; width: 35%; text-align: left; }
                .futures_info .info_center{ float: left; width: 35%; text-align: left; }
                .futures_info .info_right{ float: right; width: 30%; text-align: right; }
                .futures_info .info_title{ color: #697080; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-size: 12px; }
                .futures_info .info_item{ color: #aeb9d8; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font-size: 10px; }
                .futures_info .hold_line{ height: 10px; }
            </style>

            <div class="futures_info">
                <div class="info_left info_title"><?php echo lang('view_mobile_dm_2'); ?></div>
                <div class="info_center info_title"><?php echo lang('view_mobile_dm_3'); ?></div>
                <div class="info_right info_title"><?php echo lang('view_mobile_dm_4'); ?></div>
                <div class="clear"></div>
                <div class="info_left info_item" id="futures_info_asset_total">--</div>
                <div class="info_center info_item" id="futures_info_asset_active">--</div>
                <div class="info_right info_item" id="futures_info_asset_frozen">--</div>
                <div class="clear"></div>
                <div class="hold_line"></div>
                <div class="info_left info_title"><?php echo lang('view_mobile_dm_5'); ?></div>
                <div class="info_center info_title"><?php echo lang('view_mobile_dm_6'); ?></div>
                <div class="info_right info_title"><?php echo lang('view_mobile_dm_7'); ?></div>
                <div class="clear"></div>
                <div class="info_left info_item" id="futures_info_asset_margin">--</div>
                <div class="info_center info_item"><?php echo $_SESSION['SYSCONFIG']['sysconfig_dm_fee_rate'] * 100; ?> %</div>
                <div class="info_right info_item" id="futures_info_total_profit">--</div>
                <div class="clear"></div>
            </div>

            <style type="text/css">
                .multiple_box{ margin-top: 1px; background: #191a1f; padding-bottom: 10px; }
                .multiple_box .multiple_item{ margin: 10px 0px 0px 10px; float: left; line-height: 30px; border: #357ce1 solid 1px; color: #357ce1; border-radius: 3px; font-size: 12px; width: calc((100% - 50px) / 4); text-align: center; box-sizing: border-box; }
                .multiple_box .multiple_item.active{ background: #357ce1; color: #FFF; font-size: 14px; }
            </style>
            <div class="multiple_box">
                <?php if(count($multipleList)){ $i = 0; foreach($multipleList as $multipleItem){ ?>
                <div class="multiple_item <?php echo $i == 0 ? 'active' : ''; ?>" data-multiple="<?php echo $multipleItem; ?>"><?php echo $multipleItem; ?>×</div>
                <?php $i ++; }} ?>
                <div class="clear"></div>
            </div>

            <div class="trade_box">
                <div class="trade_left">
                    <div class="exchange_box">
                        <div>
                            <div class="exchange_tab_item exchange_tab_buy_btn active" target-content="exchange_buy_box"><?php echo lang('view_mobile_dm_8'); ?></div>
                            <div class="exchange_tab_item exchange_tab_sell_btn " target-content="exchange_sell_box"><?php echo lang('view_mobile_dm_9'); ?></div>
                            <div class="clear"></div>
                        </div>
                        <?php if($_SESSION['mobile_dm_type'] == 'limit'){ ?>
                        <div class="exchange_tab_content_item_box exchange_limit">
                            <div class="exchange_tab_content_item exchange_buy_box active" id="exchange_buy_box">
                                <select class="select_exchange_type">
                                    <option value="limit" <?php echo $_SESSION['mobile_dm_type'] == 'limit' ? 'selected' : ''; ?>><?php echo lang('view_mobile_dm_10'); ?></option>
                                    <option value="market" <?php echo $_SESSION['mobile_dm_type'] == 'market' ? 'selected' : ''; ?>><?php echo lang('view_mobile_dm_11'); ?></option>
                                </select>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele trade_input_price" placeholder="<?php echo lang('view_mobile_dm_12'); ?>"  id="limit_buy_price" data-price="#limit_buy_price" data-count="#limit_buy_count" data-amount="#limit_buy_amount">
                                    <div class="input_symbol_text"><?php echo $market['market_money_symbol']; ?></div>
                                </div>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele compute trade_input_count" placeholder="<?php echo lang('view_mobile_dm_13'); ?>"  id="limit_buy_count" data-price="#limit_buy_price" data-count="#limit_buy_count" data-amount="#limit_buy_amount">
                                    <div class="input_symbol_text"><?php echo $market['market_stock_symbol']; ?></div>
                                </div>
                                <div class="input_text"><?php echo lang('view_mobile_dm_14'); ?> <span class="<?php echo $market['market_stock_symbol']; ?>_balance trade_balance_item">0.00000000</span> <?php echo $market['market_stock_symbol']; ?></div>
                                <div class="hold_line_2"></div>
                                <div class="input_text"><?php echo lang('view_mobile_dm_15'); ?> <span class="trade_margin">0.00000000</span> <?php echo $market['market_stock_symbol']; ?></div>
                                <div class="input_text"><?php echo lang('view_mobile_dm_16'); ?> <span class="trade_fee">0.00000000</span> <?php echo $market['market_stock_symbol']; ?></div>
                                <?php if(isset($_SESSION['USER'])){ ?>
                                    <div class="submit_trade_btn futures_trade_btn" data-trade-type="limit" data-type="buy"><?php echo lang('view_mobile_dm_17'); ?> <?php echo $market['market_stock_symbol']; ?></div>
                                <?php }else{ ?>
                                    <a class="submit_trade_btn" href="/account/login"><?php echo lang('view_mobile_dm_18'); ?></a>
                                <?php } ?>
                                
                            </div>
                            <div class="exchange_tab_content_item exchange_sell_box" id="exchange_sell_box">
                                <select class="select_exchange_type">
                                    <option value="limit" <?php echo $_SESSION['mobile_dm_type'] == 'limit' ? 'selected' : ''; ?>><?php echo lang('view_mobile_dm_19'); ?></option>
                                    <option value="market" <?php echo $_SESSION['mobile_dm_type'] == 'market' ? 'selected' : ''; ?>><?php echo lang('view_mobile_dm_20'); ?></option>
                                </select>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele trade_input_price" placeholder="<?php echo lang('view_mobile_dm_21'); ?>"  id="limit_sell_price" data-price="#limit_sell_price" data-count="#limit_sell_count" data-amount="#limit_sell_amount">
                                    <div class="input_symbol_text"><?php echo $market['market_money_symbol']; ?></div>
                                </div>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele compute trade_input_count" placeholder="<?php echo lang('view_mobile_dm_22'); ?>"  id="limit_sell_count" data-price="#limit_sell_price" data-count="#limit_sell_count" data-amount="#limit_sell_amount">
                                    <div class="input_symbol_text"><?php echo $market['market_stock_symbol']; ?></div>
                                </div>
                                <div class="input_text"><?php echo lang('view_mobile_dm_23'); ?> <span class="<?php echo $market['market_stock_symbol']; ?>_balance trade_balance_item">0.00000000</span> <?php echo $market['market_stock_symbol']; ?></div>
                                <div class="hold_line_2"></div>
                                <div class="input_text"><?php echo lang('view_mobile_dm_24'); ?> <span class="trade_margin">0.00000000</span> <?php echo $market['market_stock_symbol']; ?></div>
                                <div class="input_text"><?php echo lang('view_mobile_dm_25'); ?> <span class="trade_fee">0.00000000</span> <?php echo $market['market_stock_symbol']; ?></div>
                                <?php if(isset($_SESSION['USER'])){ ?>
                                    <div class="submit_trade_btn futures_trade_btn" data-trade-type="limit" data-type="sell"><?php echo lang('view_mobile_dm_26'); ?> <?php echo $market['market_stock_symbol']; ?></div>
                                <?php }else{ ?>
                                    <a class="submit_trade_btn" href="/account/login"><?php echo lang('view_mobile_dm_27'); ?></a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php }else{ ?>
                        <div class="exchange_tab_content_item_box exchange_market">
                            <div class="exchange_tab_content_item exchange_buy_box active" id="exchange_buy_box">
                                <select class="select_exchange_type">
                                    <option value="limit" <?php echo $_SESSION['mobile_dm_type'] == 'limit' ? 'selected' : ''; ?>><?php echo lang('view_mobile_dm_28'); ?></option>
                                    <option value="market" <?php echo $_SESSION['mobile_dm_type'] == 'market' ? 'selected' : ''; ?>><?php echo lang('view_mobile_dm_29'); ?></option>
                                </select>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele trade_input_price readonly" value="" placeholder="<?php echo lang('view_mobile_dm_31'); ?>" readonly>
                                </div>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele compute trade_input_count" placeholder="<?php echo lang('view_mobile_dm_32'); ?>"  id="market_buy_count">
                                    <div class="input_symbol_text"><?php echo $market['market_stock_symbol']; ?></div>
                                </div>
                                <div class="input_text"><?php echo lang('view_mobile_dm_33'); ?> <span class="<?php echo $market['market_stock_symbol']; ?>_balance trade_balance_item">0.00000000</span> <?php echo $market['market_stock_symbol']; ?></div>
                                <div class="hold_line_2"></div>
                                <div class="input_text"><?php echo lang('view_mobile_dm_34'); ?> <span class="trade_margin">0.00000000</span> <?php echo $market['market_stock_symbol']; ?></div>
                                <div class="input_text"><?php echo lang('view_mobile_dm_35'); ?> <span class="trade_fee">0.00000000</span> <?php echo $market['market_stock_symbol']; ?></div>
                                <?php if(isset($_SESSION['USER'])){ ?>
                                    <div class="submit_trade_btn futures_trade_btn" data-input="#market_buy_count" data-type="buy" data-trade-type="market"><?php echo lang('view_mobile_dm_36'); ?> <?php echo $market['market_stock_symbol']; ?></div>
                                <?php }else{ ?>
                                    <a class="submit_trade_btn" href="/account/login"><?php echo lang('view_mobile_dm_37'); ?></a>
                                <?php } ?>
                                
                            </div>
                            <div class="exchange_tab_content_item exchange_sell_box" id="exchange_sell_box">
                                <select class="select_exchange_type">
                                    <option value="limit" <?php echo $_SESSION['mobile_dm_type'] == 'limit' ? 'selected' : ''; ?>><?php echo lang('view_mobile_dm_38'); ?></option>
                                    <option value="market" <?php echo $_SESSION['mobile_dm_type'] == 'market' ? 'selected' : ''; ?>><?php echo lang('view_mobile_dm_39'); ?></option>
                                </select>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele trade_input_price readonly" value="" placeholder="<?php echo lang('view_mobile_dm_40'); ?>" readonly>
                                </div>
                                <div class="hold_line"></div>
                                <div class="input_ele_box">
                                    <input type="number" class="input_ele compute trade_input_count" placeholder="<?php echo lang('view_mobile_dm_41'); ?>"  id="market_sell_count">
                                    <div class="input_symbol_text"><?php echo $market['market_stock_symbol']; ?></div>
                                </div>
                                <div class="input_text"><?php echo lang('view_mobile_dm_42'); ?> <span class="<?php echo $market['market_stock_symbol']; ?>_balance trade_balance_item">0.00000000</span> <?php echo $market['market_stock_symbol']; ?></div>
                                <div class="hold_line_2"></div>
                                <div class="input_text"><?php echo lang('view_mobile_dm_43'); ?> <span class="trade_margin">0.00000000</span> <?php echo $market['market_stock_symbol']; ?></div>
                                <div class="input_text"><?php echo lang('view_mobile_dm_44'); ?> <span class="trade_fee">0.00000000</span> <?php echo $market['market_stock_symbol']; ?></div>
                                <?php if(isset($_SESSION['USER'])){ ?>
                                    <div class="submit_trade_btn futures_trade_btn" data-input="#market_sell_count" data-type="sell" data-trade-type="market"><?php echo lang('view_mobile_dm_45'); ?> <?php echo $market['market_stock_symbol']; ?></div>
                                <?php }else{ ?>
                                    <a class="submit_trade_btn" href="/account/login"><?php echo lang('view_mobile_dm_46'); ?></a>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="trade_right">
                    <div class="price_list_title_box">
                        <div class="price_list_title_item price_list_item_price"><?php echo lang('view_mobile_dm_47'); ?></div>
                        <div class="price_list_title_item price_list_item_count"><?php echo lang('view_mobile_dm_48'); ?></div>
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
                .order_box .order_tab_box .order_tab_item{ width: calc(100% / 3); float: left; text-align: center; color: #aeb9d8; font-size: 12px; line-height: 40px; border-radius: 3px 3px 0px 0px; border-bottom: #1f2126 solid 2px; }
                .order_box .order_tab_box .order_tab_item.active{ border-bottom: #357ce1 solid 2px; color: #357ce1; font-size: 16px; font-weight: bold; }
                .order_box .order_tab_box .order_tab_content_box{ margin: 10px; }
                .order_box .order_tab_content_box .order_tab_content_item{ display: none; padding: 15px 10px 0px 10px; }
                .order_box .order_tab_content_box .order_tab_content_item.active{ display: block; }
                .order_box .order_tab_content_box .order_tab_content_item .order_tab_content_item_item{ margin-bottom: 15px; padding: 10px; border-radius: 3px; box-sizing: border-box; }
                .order_box .order_tab_content_box .order_tab_content_item .order_tab_content_item_item.buy{ background: rgba(5, 193, 158, .1); }
                .order_box .order_tab_content_box .order_tab_content_item .order_tab_content_item_item.sell{ background: rgba(212, 48, 42, .1); }
                .order_box .order_tab_content_box .order_tab_content_item .order_content_item_left{ float: left; width: 35%; font-size: 10px; text-align: left; color: #aeb9d8; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
                .order_box .order_tab_content_box .order_tab_content_item .order_content_item_center{ float: left; width: 35%; font-size: 10px; text-align: left; color: #aeb9d8; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
                .order_box .order_tab_content_box .order_tab_content_item .order_content_item_right{ float: right; width: 30%; font-size: 10px; text-align: right; color: #aeb9d8; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
                .order_box .order_tab_content_box .order_tab_content_item .hold_line{ height: 5px; }
                .order_box .order_tab_content_box .order_tab_content_item .order_content_item_title{ font-size: 12px; color: #697080; }
                .order_box .order_tab_content_box .order_tab_content_item .order_content_item_btn{ text-align: center; background: #357ce1; color: #FFF; line-height: 35px; padding: 0px 20px; border-radius: 3px; margin-top: 10px; }
                .order_box .order_tab_content_box .order_tab_content_item .order_content_item_text.buy{ color: #05c19e; }
                .order_box .order_tab_content_box .order_tab_content_item .order_content_item_text.sell{ color: #e04545; }

            </style>

            <div class="order_box">
                <div class="order_tab_box">
                    <div class="order_tab_item active" target-content="order_tab_content_hold"><?php echo lang('view_mobile_dm_49'); ?></div>
                    <div class="order_tab_item" target-content="order_tab_content_delegate"><?php echo lang('view_mobile_dm_51'); ?></div>
                    <div class="order_tab_item" target-content="order_tab_content_history"><?php echo lang('view_mobile_dm_52'); ?></div>
                    <div class="clear"></div>
                </div>
                <div class="order_tab_content_box">
                    <div class="order_tab_content_item active" id="order_tab_content_hold">
                        <!-- <div class="order_tab_content_item_item">
                            <div class="order_content_item_left order_content_item_title">开仓时间</div>
                            <div class="order_content_item_center order_content_item_title">方向</div>
                            <div class="order_content_item_right order_content_item_title">倍数</div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_text"></div>
                            <div class="order_content_item_center order_content_item_text"></div>
                            <div class="order_content_item_right order_content_item_text"></div>
                            <div class="clear"></div>
                            <div class="hold_line"></div>
                            <div class="order_content_item_left order_content_item_title">开仓数量</div>
                            <div class="order_content_item_center order_content_item_title">持仓数量</div>
                            <div class="order_content_item_right order_content_item_title">开仓均价</div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_text"></div>
                            <div class="order_content_item_center order_content_item_text"></div>
                            <div class="order_content_item_right order_content_item_text"></div>
                            <div class="clear"></div>
                            <div class="hold_line"></div>
                            <div class="order_content_item_left order_content_item_title">最新价</div>
                            <div class="order_content_item_center order_content_item_title">担保资产</div>
                            <div class="order_content_item_right order_content_item_title">手续费</div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_text"></div>
                            <div class="order_content_item_center order_content_item_text"></div>
                            <div class="order_content_item_right order_content_item_text"></div>
                            <div class="clear"></div>
                            <div class="hold_line"></div>
                            <div class="order_content_item_left order_content_item_title">盈亏</div>
                            <div class="order_content_item_center order_content_item_title">预估强平价</div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_text"></div>
                            <div class="order_content_item_center order_content_item_text"></div>
                            <div class="clear"></div>
                            <div class="order_content_item_btn" data-id="">平仓</div>
                            <div class="clear"></div>
                        </div> -->
                    </div>
                    <div class="order_tab_content_item" id="order_tab_content_delegate">
                        <!-- <div class="order_tab_content_item_item">
                            <div class="order_content_item_left order_content_item_title">委托时间</div>
                            <div class="order_content_item_center order_content_item_title">方向</div>
                            <div class="order_content_item_right order_content_item_title">倍数</div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_text"></div>
                            <div class="order_content_item_center order_content_item_text"></div>
                            <div class="order_content_item_right order_content_item_text"></div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_title">委托类型</div>
                            <div class="order_content_item_center order_content_item_title">委托价格</div>
                            <div class="order_content_item_right order_content_item_title">委托数量</div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_text"></div>
                            <div class="order_content_item_center order_content_item_text"></div>
                            <div class="order_content_item_right order_content_item_text"></div>
                            <div class="clear"></div>
                            <div class="order_content_item_btn" data-id="">撤销</div>
                            <div class="clear"></div>
                        </div> -->
                    </div>
                    <div class="order_tab_content_item" id="order_tab_content_history">
                        <!-- 
                        <div class="order_tab_content_item_item">
                            <div class="order_content_item_left order_content_item_title">委托时间</div>
                            <div class="order_content_item_center order_content_item_title">方向</div>
                            <div class="order_content_item_right order_content_item_title">倍数</div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_text"></div>
                            <div class="order_content_item_center order_content_item_text"></div>
                            <div class="order_content_item_right order_content_item_text"></div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_title">委托类型</div>
                            <div class="order_content_item_center order_content_item_title">委托价格</div>
                            <div class="order_content_item_right order_content_item_title">手续费</div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_text"></div>
                            <div class="order_content_item_center order_content_item_text"></div>
                            <div class="order_content_item_right order_content_item_text"></div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_title">开仓时间</div>
                            <div class="order_content_item_center order_content_item_title">开仓数量</div>
                            <div class="order_content_item_right order_content_item_title">最终持仓</div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_text"></div>
                            <div class="order_content_item_center order_content_item_text"></div>
                            <div class="order_content_item_right order_content_item_text"></div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_title">平仓价</div>
                            <div class="order_content_item_center order_content_item_title">平仓时间</div>
                            <div class="order_content_item_right order_content_item_title">盈亏</div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_text"></div>
                            <div class="order_content_item_center order_content_item_text"></div>
                            <div class="order_content_item_right order_content_item_text"></div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_title">状态</div>
                            <div class="clear"></div>
                            <div class="order_content_item_left order_content_item_text"></div>
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
                <div class="market_tab_item active"><?php echo lang('view_mobile_dm_53'); ?></div>
                <div class="clear"></div>
            </div>
            <div class="market_tab_content_item active">
                <?php foreach($marketList as $marketItem){ ?>
                <a class="market_line_item" id="market_item_line_<?php echo $marketItem['market_stock_symbol']; ?><?php echo $marketItem['market_money_symbol']; ?>" href="/futures/<?php echo strtolower($marketItem['market_stock_symbol']); ?>/<?php echo strtolower($marketItem['market_money_symbol']); ?>">
                    <div class="left_bar"><?php echo $marketItem['market_stock_symbol']; ?></div>
                    <div class="right_bar">--</div>
                    <div class="clear"></div>
                </a>
                <?php } ?>
            </div>
        </div>

        <?php $this->load->view('mobile/footer'); ?>

        <script src="<?php echo base_url('static'); ?>/mobile/js/bignumber.min.js"></script>

        <script type="text/javascript">

            var _futures_fee_rate = parseFloat('<?php echo $_SESSION['SYSCONFIG']['sysconfig_dm_fee_rate']; ?>');

            //当前栏目
            $('footer .navitem.futures').addClass('active');

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

            //倍数切换
            $('.multiple_box .multiple_item').click(function(){

                var _this = $(this);

                if (! _this.hasClass('active')) {

                    _this.addClass('active').siblings('.active').removeClass('active');

                    $('.exchange_tab_content_item').each(function(){

                        computeAmount($(this));
                    });
                }
            });

            //切换限价市价
            $('.select_exchange_type').change(function(){

                var _this = $(this);

                $.ajax({
                    url: '/futures/select_dm_type',
                    type: 'post',
                    data: {

                        'mobile_dm_type' : _this.val()
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

                if (_this.hasClass('trade_input_count')) {

                    computeAmount(_this.parent().parent());
                }
            });

            //交易
            $('.futures_trade_btn').click(function(){

                var _this = $(this);

                if (! _this.hasClass('off')) {

                    var _price = _this.parent().find('.trade_input_price').val();
                    var _count = _this.parent().find('.trade_input_count').val();

                    if (_this.attr('data-trade-type') == 'limit') {

                        if(_price == ''){

                            _msg.error('<?php echo lang('view_dm_108'); ?>' + (_this.attr('data-type') == 'sell' ? '<?php echo lang('view_dm_109'); ?>' : '<?php echo lang('view_dm_110'); ?>'));
                            return false;
                        }
                    }

                    if(_count == ''){

                        _msg.error('<?php echo lang('view_dm_111'); ?>' + (_this.attr('data-type') == 'sell' ? '<?php echo lang('view_dm_112'); ?>' : '<?php echo lang('view_dm_113'); ?>'));
                        return false;
                    }

                    _this.addClass('off');

                    $.ajax({
                        url: '/futures/trade',
                        type: 'post',
                        data: {
                            'type' : _this.attr('data-type'),
                            'trade_type' : _this.attr('data-trade-type'),
                            'price' : _price,
                            'count' : _count,
                            'multiple' : $('.multiple_item.active').attr('data-multiple'),
                            'coin' : '<?php echo $market['market_stock_symbol']; ?>'
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

                            _msg.error('<?php echo lang('view_dm_114'); ?>');
                        },
                        complete: function(){

                            _this.removeClass('off');
                        }
                    });
                }
            });

            //计算总额
            function computeAmount(_trade_input_box_obj){

                var _countObj = _trade_input_box_obj.find('.trade_input_count');

                var _fee = 0;
                var _margin = 0;

                if (_countObj.val() != '') {

                    var _count = parseFloat(_countObj.val());

                    if (_count > 0) {

                        _fee = _count * _futures_fee_rate * parseFloat($('.multiple_item.active').attr('data-multiple'));
                        _margin = _count - _fee;
                    }
                }

                _trade_input_box_obj.find('.trade_fee').text(_fee.toFixed(8));
                _trade_input_box_obj.find('.trade_margin').text(_margin.toFixed(8));
            }

            $('#order_tab_content_delegate').on('click', '.order_content_item_btn', function(){

                var _this = $(this);

                if (! _this.hasClass('off')) {

                    _this.addClass('off');

                    $.ajax({
                        url: '/futures/cancel',
                        type: 'post',
                        data: {
                            'order' : _this.attr('data-id'),
                        },
                        dataType: 'json',
                        success: function (data) {
                            
                            if (data.status) {

                                _msg.success(data.message);
                                _this.parent().remove();
                            }else{

                                _msg.error(data.message);
                            }
                        },
                        error: function(){

                            _msg.error('<?php echo lang('view_dm_115'); ?>');
                        },
                        complete: function(){

                            _this.removeClass('off');
                        }
                    });
                }
            });

            $('#order_tab_content_hold').on('click', '.order_content_item_btn', function(){

                var _this = $(this);

                if (! _this.hasClass('off')) {

                    _this.addClass('off');

                    $.ajax({
                        url: '/futures/close',
                        type: 'post',
                        data: {
                            'order' : _this.attr('data-id'),
                        },
                        dataType: 'json',
                        success: function (data) {
                            
                            if (data.status) {

                                _msg.success(data.message);
                                _this.parent().remove();
                            }else{

                                _msg.error(data.message);
                            }
                        },
                        error: function(){

                            _msg.error('<?php echo lang('view_dm_116'); ?>');
                        },
                        complete: function(){

                            _this.removeClass('off');
                        }
                    });
                }
            });

            <?php if(count($marketSymbolList)){ ?>

                $(window).load(function(){

                    var current_price = '--';

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
                    }

                    ws.onmessage = function(event){

                        if (typeof event.data === 'string') {
                            
                            var _result = JSON.parse(event.data);

                            if (typeof _result === 'object') {

                                if (_result.id == 9) {

                                    timeSub = parseInt(Date.now() / 1000) - _result.result;
                                }

                                if (_result.method == 'today.update') {

                                    var market_line_item = $('#market_item_line_' + _result.params[0]);

                                    market_line_item.children('.right_bar').text(_result.params[1].last);

                                    var _last = BN(_result.params[1].last);
                                    _last = _last.comparedTo(0) == 1 ? _last : 1;
                                    var _rate = _last.minus(BN(_result.params[1].open)).div(_last).times(100).toFixed(2);

                                    var _currentMarket = false;

                                    if (marketSymbol == _result.params[0]) {

                                        current_price = _result.params[1].last;

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

                    <?php if(isset($_SESSION['USER'])){ ?>

                        //获取合约信息
                        function auto_info(){

                            $.ajax({
                                url: '/futures/info',
                                type: 'post',
                                data: {
                                    'coin' : '<?php echo $market['market_stock_symbol']; ?>'
                                },
                                dataType: 'json',
                                success: function (data) {
                                    
                                    if (data.status) {

                                        $('#futures_info_asset_total').text(data.data.total_info.asset_total);
                                        $('#futures_info_asset_active, .trade_balance_item').text(data.data.total_info.asset_active);
                                        $('#futures_info_asset_frozen').text(data.data.total_info.asset_frozen);
                                        $('#futures_info_asset_margin').text(data.data.total_info.asset_pledge);
                                        $('#futures_info_total_profit').text(data.data.total_info.total_profit);
                                        $('#futures_info_asset_margin_rate').text(data.data.total_info.asset_pledge_rate);

                                        $('#order_tab_content_hold .order_tab_content_item_item').remove();
                                        if (data.data.hold_order) {

                                            var _html = '';

                                            for(var _index in data.data.hold_order){

                                                _html += 

                                                '<div class="order_tab_content_item_item ' + data.data.hold_order[_index].direction_class + '">' +
                                                    '<div class="order_content_item_left order_content_item_title"><?php echo lang('view_mobile_dm_54'); ?></div>' +
                                                    '<div class="order_content_item_center order_content_item_title"><?php echo lang('view_mobile_dm_55'); ?></div>' +
                                                    '<div class="order_content_item_right order_content_item_title"><?php echo lang('view_mobile_dm_56'); ?></div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_text">' + data.data.hold_order[_index].time + '</div>' +
                                                    '<div class="order_content_item_center order_content_item_text ' + data.data.hold_order[_index].direction_class + '">' + data.data.hold_order[_index].direction + '</div>' +
                                                    '<div class="order_content_item_right order_content_item_text">' + data.data.hold_order[_index].multiple + '</div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="hold_line"></div>' +
                                                    '<div class="order_content_item_left order_content_item_title"><?php echo lang('view_mobile_dm_57'); ?></div>' +
                                                    '<div class="order_content_item_center order_content_item_title"><?php echo lang('view_mobile_dm_58'); ?></div>' +
                                                    '<div class="order_content_item_right order_content_item_title"><?php echo lang('view_mobile_dm_59'); ?></div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_text">' + data.data.hold_order[_index].open_count + '</div>' +
                                                    '<div class="order_content_item_center order_content_item_text">' + data.data.hold_order[_index].hold_count + '</div>' +
                                                    '<div class="order_content_item_right order_content_item_text">' + data.data.hold_order[_index].open_price + '</div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="hold_line"></div>' +
                                                    '<div class="order_content_item_left order_content_item_title"><?php echo lang('view_mobile_dm_61'); ?></div>' +
                                                    '<div class="order_content_item_center order_content_item_title"><?php echo lang('view_mobile_dm_62'); ?></div>' +
                                                    '<div class="order_content_item_right order_content_item_title"><?php echo lang('view_mobile_dm_63'); ?></div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_text current_market_price">' + current_price + '</div>' +
                                                    '<div class="order_content_item_center order_content_item_text">' + data.data.hold_order[_index].margin + '</div>' +
                                                    '<div class="order_content_item_right order_content_item_text">' + data.data.hold_order[_index].fee + '</div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="hold_line"></div>' +
                                                    '<div class="order_content_item_left order_content_item_title"><?php echo lang('view_mobile_dm_64'); ?></div>' +
                                                    '<div class="order_content_item_center order_content_item_title"><?php echo lang('view_mobile_dm_65'); ?></div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_text ' + data.data.hold_order[_index].profit_class + '">' + data.data.hold_order[_index].profit + '</div>' +
                                                    '<div class="order_content_item_center order_content_item_text">' + data.data.hold_order[_index].future_price + '</div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_btn" data-id="' + data.data.hold_order[_index].order + '"><?php echo lang('view_mobile_dm_66'); ?></div>' +
                                                    '<div class="clear"></div>' +
                                                '</div>';
                                            }

                                            $('#order_tab_content_hold').html(_html);
                                        }

                                        $('#order_tab_content_delegate .order_tab_content_item').remove();
                                        if (data.data.delegate_order) {

                                            var _html = '';

                                            for(var _index in data.data.delegate_order){

                                                _html += 

                                                '<div class="order_tab_content_item_item ' + data.data.delegate_order[_index].direction_class + '">' +
                                                    '<div class="order_content_item_left order_content_item_title"><?php echo lang('view_mobile_dm_67'); ?></div>' +
                                                    '<div class="order_content_item_center order_content_item_title"><?php echo lang('view_mobile_dm_68'); ?></div>' +
                                                    '<div class="order_content_item_right order_content_item_title"><?php echo lang('view_mobile_dm_69'); ?></div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_text">' + data.data.delegate_order[_index].time + '</div>' +
                                                    '<div class="order_content_item_center order_content_item_text ' + data.data.delegate_order[_index].direction_class + '">' + data.data.delegate_order[_index].direction + '</div>' +
                                                    '<div class="order_content_item_right order_content_item_text">' + data.data.delegate_order[_index].multiple + '</div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_title"><?php echo lang('view_mobile_dm_70'); ?></div>' +
                                                    '<div class="order_content_item_center order_content_item_title"><?php echo lang('view_mobile_dm_71'); ?></div>' +
                                                    '<div class="order_content_item_right order_content_item_title"><?php echo lang('view_mobile_dm_72'); ?></div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_text">' + data.data.delegate_order[_index].type + '</div>' +
                                                    '<div class="order_content_item_center order_content_item_text">' + data.data.delegate_order[_index].price + '</div>' +
                                                    '<div class="order_content_item_right order_content_item_text">' + data.data.delegate_order[_index].count + '</div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_btn" data-id="' + data.data.delegate_order[_index].order + '"><?php echo lang('view_mobile_dm_73'); ?></div>' +
                                                    '<div class="clear"></div>' +
                                                '</div>';
                                            }

                                            $('#order_tab_content_delegate').html(_html);
                                        }

                                        $('#order_tab_content_history .order_tab_content_item').remove();
                                        if (data.data.history_order) {

                                            var _html = '';

                                            for(var _index in data.data.history_order){

                                                _html += 

                                                '<div class="order_tab_content_item_item ' + data.data.history_order[_index].direction_class + '">' +
                                                    '<div class="order_content_item_left order_content_item_title"><?php echo lang('view_mobile_dm_74'); ?></div>' +
                                                    '<div class="order_content_item_center order_content_item_title"><?php echo lang('view_mobile_dm_75'); ?></div>' +
                                                    '<div class="order_content_item_right order_content_item_title"><?php echo lang('view_mobile_dm_76'); ?></div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_text">' + data.data.history_order[_index].delegate_time + '</div>' +
                                                    '<div class="order_content_item_center order_content_item_text ' + data.data.history_order[_index].direction_class + '">' + data.data.history_order[_index].direction + '</div>' +
                                                    '<div class="order_content_item_right order_content_item_text">' + data.data.history_order[_index].multiple + '</div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_title"><?php echo lang('view_mobile_dm_77'); ?></div>' +
                                                    '<div class="order_content_item_center order_content_item_title"><?php echo lang('view_mobile_dm_78'); ?></div>' +
                                                    '<div class="order_content_item_right order_content_item_title"><?php echo lang('view_mobile_dm_79'); ?></div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_text">' + data.data.history_order[_index].type + '</div>' +
                                                    '<div class="order_content_item_center order_content_item_text">' + data.data.history_order[_index].price + '</div>' +
                                                    '<div class="order_content_item_right order_content_item_text">' + data.data.history_order[_index].fee + '</div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_title"><?php echo lang('view_mobile_dm_81'); ?></div>' +
                                                    '<div class="order_content_item_center order_content_item_title"><?php echo lang('view_mobile_dm_82'); ?></div>' +
                                                    '<div class="order_content_item_right order_content_item_title"><?php echo lang('view_mobile_dm_83'); ?></div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_text">' + data.data.history_order[_index].open_time + '</div>' +
                                                    '<div class="order_content_item_center order_content_item_text">' + data.data.history_order[_index].open_count + '</div>' +
                                                    '<div class="order_content_item_right order_content_item_text">' + data.data.history_order[_index].hold + '</div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_title"><?php echo lang('view_mobile_dm_84'); ?></div>' +
                                                    '<div class="order_content_item_center order_content_item_title"><?php echo lang('view_mobile_dm_85'); ?></div>' +
                                                    '<div class="order_content_item_right order_content_item_title"><?php echo lang('view_mobile_dm_86'); ?></div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_text">' + data.data.history_order[_index].close_price + '</div>' +
                                                    '<div class="order_content_item_center order_content_item_text">' + data.data.history_order[_index].close_time + '</div>' +
                                                    '<div class="order_content_item_right order_content_item_text ' + data.data.history_order[_index].profit_class + '">' + data.data.history_order[_index].profit + '</div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_title"><?php echo lang('view_mobile_dm_87'); ?></div>' +
                                                    '<div class="clear"></div>' +
                                                    '<div class="order_content_item_left order_content_item_text">' + data.data.history_order[_index].status + '</div>' +
                                                    '<div class="clear"></div>' +
                                                '</div>';
                                            }

                                            $('#order_tab_content_history').html(_html);
                                        }
                                    }
                                },
                                complete: function(){

                                    setTimeout(function(){

                                        auto_info();
                                    }, 5000);
                                }
                            });
                        }

                        auto_info();

                    <?php } ?>
                });

            <?php } ?>


        </script>

    </body>
</html>
