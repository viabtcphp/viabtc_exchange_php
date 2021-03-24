<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <title>法币交易<?php echo ' - ' . $_SESSION['SYSCONFIG']['sysconfig_site_name']; ?></title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <link rel='icon' href='/favicon.ico' type='image/x-ico' />

        <link rel="stylesheet" href="<?php echo base_url('static/layui/css'); ?>/layui.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/front'); ?>/style/style.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/front'); ?>/style/tv.css" />

        <!--[if lt IE 9]>
        <script src="<?php echo base_url('static/front'); ?>/js/css3.js"></script>
        <script src="<?php echo base_url('static/front'); ?>/js/html5.js"></script>
        <![endif]-->
    </head>
    <body>

        <?php $this->load->view('front/header'); ?>

        <div class="body_box" style="background: #1f2126;">

            <style type="text/css">
                .otc_banner{ background-image: url('/static/front/images/otc_banner.jpg'); background-size: cover; }
                .otc_banner .center{ width: 1200px; margin: 0 auto; position: relative; height: 210px; }
                .otc_banner .center .otc_banner_text_1{ color: #FFF; font-size: 30px; text-align: center; line-height: 70px; padding-top: 50px; }
                .otc_banner .center .otc_banner_text_2{ color: #FFF; font-size: 20px; text-align: center; }
                .otc_banner .center .otc_type_btn_box{ position: absolute; left: 0px; bottom: 10px; width: 200px; border: #357ce1 solid 1px; }
                .otc_banner .center .otc_type_btn_box .otc_type_btn_item{ line-height: 40px; width: 100px; float: left; text-align: center; background: #34363f; cursor: pointer; color: #FFF; font-size: 12px; }
                .otc_banner .center .otc_type_btn_box .otc_type_btn_item.buy_active{ background: #05c19e; font-size: 16px; }
                .otc_banner .center .otc_type_btn_box .otc_type_btn_item.sell_active{ background: #e04545; font-size: 16px; }

                
            </style>

            <div class="otc_banner">
                <div class="center">
                    <div class="otc_banner_text_1">平台托管 ● 安全放心</div>
                    <div class="otc_banner_text_2">由平台托管数字资产，保障用户资产安全</div>
                    <div class="otc_type_btn_box">
                        <a href="" class="otc_type_btn_item <?php echo $otcType=='buy' ? 'buy_active' : ''; ?>">购买</a>
                        <a href="" class="otc_type_btn_item <?php echo $otcType=='sell' ? 'sell_active' : ''; ?>">出售</a>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>

            <style type="text/css">
                
                .otc_box{ border-bottom: #34363f solid 1px; }
                .otc_box .coin_list_box{ background: rgba(105, 112, 128, .1); }
                .otc_box .coin_list_box .center{ width: 1200px; margin: 0 auto; padding: 30px 0px; }
                .otc_box .coin_list_box .center .tool_box{  }
                .otc_box .coin_list_box .center .tool_box .tool_link_item{ float: right; color: #FFF; background: rgba(53, 124, 225, .8); font-weight: bold; font-size: 12px; cursor: pointer;  margin-left: 20px; line-height: 30px; padding: 0px 20px;  border-radius: 5px; }
                .otc_box .coin_list_box .center .tool_box .tool_link_item:hover{ background: rgba(53, 124, 225, 1); }
                .otc_box .coin_list_box .center .coin_list{ padding-top: 30px; }
                .otc_box .coin_list_box .center .coin_list .coin_item{ display: block; float: left; margin-right: 20px; margin-bottom: 10px; font-size: 20px; font-weight: 500; cursor: pointer; padding-bottom: 5px; border-bottom: rgba(105, 112, 128, 0) solid 2px; }
                .otc_box .coin_list_box .center .coin_list .coin_item:hover{ color: #FFF; }
                .otc_box .coin_list_box .center .coin_list .coin_item.buy_active{ color: #05c19e; border-bottom-color: #05c19e; }
                .otc_box .coin_list_box .center .coin_list .coin_item.sell_active{ color: #e04545; border-bottom-color: #e04545; }
            </style>

            <div class="otc_box">
                
                <div class="coin_list_box">
                    <div class="center">
                        <div class="tool_box">
                            <a class="tool_link_item">法币订单</a>
                            <a class="tool_link_item" href="/otc/pay">收款方式</a>
                            <div class="clear"></div>
                        </div>

                        <div class="coin_list">
                            
                            <a class="coin_item <?php echo $otcType=='buy' ? 'buy_active' : 'sell_active'; ?>">BTC</a>
                            <a class="coin_item">BTC</a>
                            <a class="coin_item">BTC</a>
                            <a class="coin_item">BTC</a>
                            <a class="coin_item">BTC</a>
                            <a class="coin_item">BTC</a>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>

                <style type="text/css">
                    
                    .adv_list{ width: 1200px; margin: 50px auto; min-height: 300px; }
                    .adv_list .adv_line_item{ border-bottom: #34363f solid 1px; padding: 0px 20px; }
                    .adv_list .adv_line_item .item_td{ float: left; width: 200px; padding: 20px 0px; line-height: 40px; color: #a7b7c7; font-size: 14px; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
                    .adv_list .adv_line_item .item_td_right{ float: right; width: 160px; text-align: right; }
                    .adv_list .adv_line_item .title_item_td{ color: #697080; font-size: 12px; line-height: 40px; padding: 0px; }
                    .adv_list .adv_line_item .item_td.merchant{ font-weight: bold; color: #357ce1; }
                    .adv_list .adv_line_item .item_td.buy_active{ color: #05c19e; }
                    .adv_list .adv_line_item .item_td.sell_active{ color: #e04545; }
                    .adv_list .adv_line_item .item_td .otc_trade_btn{ float: right; line-height: 30px; border-radius: 5px; padding: 0px 20px; cursor: pointer; color: #FFF; font-size: 12px; margin-top: 5px; }
                    .adv_list .adv_line_item .item_td .otc_trade_btn.buy_active{ background: rgba(53, 124, 225, .8); }
                    .adv_list .adv_line_item .item_td .otc_trade_btn.sell_active{ background: rgba(53, 124, 225, .8); }
                    .adv_list .adv_line_item .item_td .otc_trade_btn:hover{ background: rgba(53, 124, 225, 1); }
                    .adv_list .adv_line_item:hover{ background: rgba(53, 124, 225, .1); }
                    .adv_list .adv_title_line:hover{ background: none; }
                </style>

                <div class="adv_list">

                    <div class="adv_line_item adv_title_line">
                        <div class="item_td title_item_td">承兑商户</div>
                        <div class="item_td title_item_td">数量(USDT)</div>
                        <div class="item_td title_item_td">限额</div>
                        <div class="item_td title_item_td">单价</div>
                        <div class="item_td title_item_td">支付方式</div>
                        <div class="item_td item_td_right title_item_td"></div>
                        <div class="clear"></div>
                    </div>

                    <div class="adv_line_item">
                        <div class="item_td merchant">币圈发财大队</div>
                        <div class="item_td count">3481.415644</div>
                        <div class="item_td limit">5000-24265 CNY</div>
                        <div class="item_td <?php echo $otcType=='buy' ? 'buy_active' : 'sell_active'; ?>">6.97 CNY</div>
                        <div class="item_td"></div>
                        <div class="item_td item_td_right">
                            <a class="otc_trade_btn <?php echo $otcType=='buy' ? 'buy_active' : 'sell_active'; ?>"><?php echo $otcType=='buy' ? '购买' : '出售'; ?> USDT</a>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>


            </div>

        </div>

        <?php $this->load->view('front/footer'); ?>

        <script src="<?php echo base_url('static'); ?>/front/js/bignumber.min.js"></script>
        <script src="<?php echo base_url('static'); ?>/charting_library/charting_library.min.js" async></script>
        <script src="<?php echo base_url('static'); ?>/front/js/tv.js"></script>

        <script type="text/javascript">
            
            //当前栏目
            $('header .left_box .nav_box .nav_item.otc').addClass('active');
        </script>

    </body>
</html>
