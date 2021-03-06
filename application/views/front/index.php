<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <title><?php echo $_SESSION['SYSCONFIG']['sysconfig_site_name']; ?></title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <link rel='icon' href='/favicon.ico' type='image/x-ico' />
        
        <link rel="stylesheet" href="<?php echo base_url('static'); ?>/layui/css/layui.css">

        <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/front'); ?>/style/style.css" />

        <!--[if lt IE 9]>
        <script src="<?php echo base_url('static/front'); ?>/js/css3.js"></script>
        <script src="<?php echo base_url('static/front'); ?>/js/html5.js"></script>
        <![endif]-->
    </head>
    <body>

        <?php $this->load->view('front/header'); ?>

        <style type="text/css">
            
            /*index*/
            .body_box{ min-width: 1300px; }
            .body_box .index_banner{ margin-top: 1px; height: 500px; }
            .body_box .index_banner .banner_item{ height: 100%; background-position: center; background-repeat: no-repeat; background-size: cover; }
            .body_box .index_banner .layui-carousel{ background: none; }
            .body_box .index_banner .layui-carousel div:not(.banner_item){ background: none; }
            .body_box .index_price_bar{ margin-top: 1px; }
            .body_box .index_price_bar .price_bar_item{ display: block; float: left; background: #191a1f; color: #aeb9d8; cursor: pointer; }
            .body_box .index_price_bar .price_bar_item:hover{ background: #34363f; }
            .body_box .index_price_bar .price_bar_item .item_center{ padding: 20px 15px; border-right: #34363f solid 1px; }
            .body_box .index_price_bar .price_bar_item .item_center .market_price_rate{ font-size: 16px; line-height: 20px; color: #0da88b; float: right; }
            .body_box .index_price_bar .price_bar_item .item_center .market_price_rate.down{ color: #ef5656; }
            .body_box .index_price_bar .price_bar_item .item_center .market_symbol{ font-size: 14px; line-height: 20px; }
            .body_box .index_price_bar .price_bar_item .item_center .market_price{ font-size: 20px; line-height: 50px; color: #d2d6ec }
            .body_box .index_price_bar .price_bar_item .item_center .market_amount{ font-size: 14px; line-height: 20px; }
            .body_box .index_news_box{ margin-top: 1px; background: #191a1f; font-size: 14px; text-align: center; color: #aeb9d8; line-height: 60px; }
            .body_box .index_news_box .news_item{ color: #aeb9d8; font-size: 14px; }
            .body_box .index_news_box .news_item:hover{ color: #3B97E9; }
            .body_box .index_price_list{ background: linear-gradient(-180deg,#182437,#1f1e23); overflow: hidden; padding: 60px 0px 60px 0px; }
            .body_box .index_price_list .center_box{ margin: 0 100px; border: #384862 solid 1px; border-radius: 5px; box-sizing: border-box; }
            .body_box .index_price_list .center_box .tab_box{ background: #384862; padding: 10px 0px 10px 25px; }
            .body_box .index_price_list .center_box .tab_box .tab_item{ display: block; float: left; line-height: 30px; height: 30px; color: #aeb9d8; padding: 0px 0px 3px 0px; margin-right: 20px; cursor: pointer; font-size: 16px; border-bottom: #384862 solid 2px; }
            .body_box .index_price_list .center_box .tab_box .tab_item:hover{ color: #FFF; }
            .body_box .index_price_list .center_box .tab_box .tab_item.active{ color: #FFF; height: 30px; border-bottom: #3B97E9 solid 2px; }
            .body_box .index_price_list .center_box .tab_content .tab_content_item{ display: none; }
            .body_box .index_price_list .center_box .tab_content .tab_content_item.active{ display: block; }
            .body_box .index_price_list .center_box .tab_content .tab_content_item table{ width: 100%; }
            .body_box .index_price_list .center_box .tab_content .tab_content_item table thead{ background: #202a3a; }
            .body_box .index_price_list .center_box .tab_content .tab_content_item table thead th{ color: #bbc6d7; font-size: 14px; font-weight: normal; line-height: 40px; text-align: center; }
            .body_box .index_price_list .center_box .tab_content .tab_content_item table tbody td{ font-size: 16px; color: #FFF; line-height: 20px; padding: 15px 0px; border-top: #384862 solid 1px; text-align: center; }
            .body_box .index_price_list .center_box .tab_content .tab_content_item table tbody td.hold{ width: 25px; }
            .body_box .index_price_list .center_box .tab_content .tab_content_item table tbody tr{ cursor: pointer; }
            .body_box .index_price_list .center_box .tab_content .tab_content_item table tbody tr:hover{ background: rgba(56, 72, 98, .3);  }
            .body_box .index_price_list .center_box .tab_content .tab_content_item table tbody td .small_font{ font-size: 14px; color: #7c7d9a; }
            .body_box .index_price_list .center_box .tab_content .tab_content_item table tbody td .price_up{ background: #0da88b; border: #0da88b solid 1px; border-radius: 3px; color: #FFF; font-size: 12px; line-height: 18px; height: 18px; width: 98px; margin: 0 auto; }
            .body_box .index_price_list .center_box .tab_content .tab_content_item table tbody td .price_up_text{ color: #0da88b; font-size: 16px; }
            .body_box .index_price_list .center_box .tab_content .tab_content_item table tbody td .price_down{ background: #ef5656; border: #ef5656 solid 1px; border-radius: 3px; color: #FFF; font-size: 12px; line-height: 18px; height: 18px; width: 98px; margin: 0 auto; }
            .body_box .index_price_list .center_box .tab_content .tab_content_item table tbody td .price_down_text{ color: #ef5656; font-size: 16px; }
            .body_box .index_info_box_1{ position: relative; padding-top: 150px; background: linear-gradient(-180deg,#182437,#1f1e23); }
            .body_box .index_info_box_1 .info_float_bg{ width: 100%; height: 91px; background: url('/static/front/images/index_bg_1.png') no-repeat center bottom; position: absolute; left: 0px; top: -1px; }
            .body_box .index_info_box_1 .center_box{ width: 1200px; margin: 0 auto; }
            .body_box .index_info_box_1 .center_box .center_text_1{ text-align: center; font-size: 32px; color: #FFF; line-height: 100px; }
            .body_box .index_info_box_1 .center_box .center_text_2{ text-align: center; font-size: 18px; color: #9ea4b1; }
            .body_box .index_info_box_1 .center_box .center_bg{ height: 700px; background: url('/static/front/images/index_global_<?php echo $_SESSION['_language']; ?>.png') no-repeat center; }

            .body_box .index_info_box_2{ background: linear-gradient(-180deg,#182437,#1f1e23); padding: 50px 0px; }
            .body_box .index_info_box_2 .center_text_1{ text-align: center; font-size: 32px; color: #FFF; line-height: 100px; }
            .body_box .index_info_box_2 .center_text_2{ text-align: center; font-size: 18px; color: #9ea4b1; }
            .body_box .index_info_box_2 .center_info{ width: 1200px; margin: 0 auto; padding-top: 50px; }
            .body_box .index_info_box_2 .center_info .info_item{ float: left; width: 25%; }
            .body_box .index_info_box_2 .center_info .info_item .item_bg_1{ width: 78px; height: 78px; margin: 0 auto; background: url('/static/front/images/index_bg_3_1.png') no-repeat center; }
            .body_box .index_info_box_2 .center_info .info_item .item_bg_2{ width: 78px; height: 78px; margin: 0 auto; background: url('/static/front/images/index_bg_3_2.png') no-repeat center; }
            .body_box .index_info_box_2 .center_info .info_item .item_bg_3{ width: 78px; height: 78px; margin: 0 auto; background: url('/static/front/images/index_bg_3_3.png') no-repeat center; }
            .body_box .index_info_box_2 .center_info .info_item .item_bg_4{ width: 78px; height: 78px; margin: 0 auto; background: url('/static/front/images/index_bg_3_4.png') no-repeat center; }
            .body_box .index_info_box_2 .center_info .info_item .item_text_1{ font-size: 20px; color: #FFF; text-align: center; margin-top: 10px; }
            .body_box .index_info_box_2 .center_info .info_item .item_text_2{ font-size: 14px; color: #9ea4b1; text-align: center; padding: 20px 30px; }
            .body_box .index_info_box_3{ position: relative; padding-top: 150px; background: url('/static/front/images/index_bg_4.png') center bottom; }
            .body_box .index_info_box_3 .info_float_bg{ width: 100%; height: 91px; background: url('/static/front/images/index_bg_1.png') no-repeat center bottom; position: absolute; left: 0px; top: -1px; }
            .body_box .index_info_box_3 .info_text_1{ text-align: center; font-size: 32px; color: #282828; line-height: 100px; }
            .body_box .index_info_box_3 .info_text_2{ text-align: center; font-size: 18px; color: #282828; }
            .body_box .index_info_box_3 .center_bg{ height: 500px; background: url('/static/front/images/index_bg_5.png') no-repeat center; }
        </style>

        <div class="body_box">
            
            <!-- ?????? -->
            <div class="index_banner">
                <div class="layui-carousel" id="index-banner">
                    <div carousel-item>

                        <?php if($imageList && count($imageList)){ foreach($imageList as $imageItem){ if($imageItem['article_plate'] == 0){ ?>
                        <div><a><div class="banner_item" style="background-image: url('<?php echo $imageItem['article_content']; ?>')"></div></a></div>
                        <?php }}} ?>
                    </div>
                </div>
            </div>
            
            <!-- ?????????????????? -->
            <div class="index_price_bar">

                <?php if($marketList && count($marketList)){ foreach($marketList as $marketItem){ ?>
                <a class="price_bar_item" href="/exchange/<?php echo strtolower($marketItem['market_stock_symbol']); ?>/<?php echo strtolower($marketItem['market_money_symbol']); ?>" style="width: calc(100% / <?php echo count($marketList); ?>);">
                    <div class="item_center" id="market_item_<?php echo $marketItem['market_stock_symbol']; ?><?php echo $marketItem['market_money_symbol']; ?>">
                        <div class="market_price_rate">--</div>
                        <div class="market_symbol"><?php echo $marketItem['market_stock_symbol']; ?>/<?php echo $marketItem['market_money_symbol']; ?></div>
                        <div class="market_price">--</div>
                        <div class="market_amount"><?php echo lang('view_index_1'); ?> <span class="24h_deal">--</span> <?php echo $marketItem['market_stock_symbol']; ?></div>
                    </div>
                </a>
                <?php }} ?>

                <div class="clear"></div>
            </div>

            <!-- ?????? -->
            <div class="index_news_box">
                <?php echo lang('view_index_2'); ?>
                <?php if($newsList && count($newsList)){ foreach($newsList as $newsItem){ ?>
                <a class="news_item" href="/article/detail/<?php echo $newsItem['article_token']; ?>"><?php echo $newsItem['article_title']; ?></a>&nbsp;&nbsp;/&nbsp;&nbsp;
                <?php }} ?>
                <a class="news_item" href="/article"><?php echo lang('view_index_3'); ?></a>
            </div>
            
            <!-- ???????????? -->
            <div class="index_price_list">
                <div class="center_box">

                    <div class="tab_box">

                        <?php if($marketGroup && count($marketGroup)){ $i = 0; foreach($marketGroup as $money => $marketsItem){ ?>
                        <a class="tab_item <?php echo $i<1?'active':''; ?>" target-content="index_price_list_tab_content_<?php echo $money; ?>"><?php echo $money; ?></a>
                        <?php $i++; }} ?>
                        <div class="clear"></div>
                    </div>

                    <div class="tab_content">

                        <?php if($marketGroup && count($marketGroup)){ $i = 0; foreach($marketGroup as $money => $marketsItem){ ?>
                        <div class="tab_content_item <?php echo $i<1?'active':''; ?>" id="index_price_list_tab_content_<?php echo $money; ?>">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <thead>
                                    <tr>
                                        <th class="hold"></th>
                                        <th style="text-align: left; width: 15%;"><?php echo lang('view_index_4'); ?></th>
                                        <th style="width: 15%; text-align: left;"><?php echo lang('view_index_5'); ?></th>
                                        <th style="width: 10%;"><?php echo lang('view_index_6'); ?></th>
                                        <th style="width: 15%; text-align: right;"><?php echo lang('view_index_7'); ?></th>
                                        <th style="width: 15%; text-align: right;"><?php echo lang('view_index_8'); ?></th>
                                        <th style="text-align: right;"><?php echo lang('view_index_9'); ?></th>
                                        <th class="hold"></th>
                                    </tr> 
                                </thead>
                                <tbody>

                                    <?php foreach($marketsItem as $marketItem){ ?>
                                    <tr id="market_item_line_<?php echo $marketItem['market_stock_symbol']; ?><?php echo $marketItem['market_money_symbol']; ?>" onclick="window.location.href='/exchange/<?php echo strtolower($marketItem['market_stock_symbol']); ?>/<?php echo strtolower($marketItem['market_money_symbol']); ?>';">
                                        <td class="hold"></td>
                                        <td style="text-align: left; width: 15%;">
                                            <?php echo $marketItem['market_stock_symbol']; ?><span class="small_font"> /<?php echo $marketItem['market_money_symbol']; ?></span>
                                        </td>
                                        <td style="width: 15%; text-align: left;">
                                            <div class="market_item_line_price price_up_text">--</div>
                                        </td>
                                        <td style="width: 10%;">
                                            <div class="market_item_line_rate price_up">--</div>
                                        </td>
                                        <td style="width: 15%; text-align: right;" class="24_high">--</td>
                                        <td style="width: 15%; text-align: right;" class="24_low">--</td>
                                        <td style="text-align: right;" class="24_deal">--</td>
                                        <td class="hold"></td>
                                    </tr>
                                    <?php } ?>

                                </tbody>
                            </table>
                        </div>
                        <?php $i++; }} ?>
                    </div>
                </div>
            </div>

            <!-- ????????? -->
            <div class="index_info_box_1">
                <div class="info_float_bg"></div>
                <div class="center_box">
                    <div class="center_text_1"><?php echo lang('view_index_10'); ?></div>
                    <div class="center_text_2"><?php echo lang('view_index_11'); ?></div>
                    <div class="center_bg"></div>
                </div>
            </div>

            <!-- ????????? -->
            <div class="index_info_box_2">
                <div class="center_text_1"><?php echo lang('view_index_12'); ?></div>
                <div class="center_text_2"><?php echo lang('view_index_13'); ?></div>
                <div class="center_info">
                    <div class="info_item">
                        <div class="item_bg_1"></div>
                        <div class="item_text_1"><?php echo lang('view_index_14'); ?></div>
                        <div class="item_text_2"><?php echo lang('view_index_15'); ?></div>
                    </div>
                    <div class="info_item">
                        <div class="item_bg_2"></div>
                        <div class="item_text_1"><?php echo lang('view_index_16'); ?></div>
                        <div class="item_text_2"><?php echo lang('view_index_17'); ?></div>
                    </div>
                    <div class="info_item">
                        <div class="item_bg_3"></div>
                        <div class="item_text_1"><?php echo lang('view_index_18'); ?></div>
                        <div class="item_text_2"><?php echo lang('view_index_19'); ?></div>
                    </div>
                    <div class="info_item">
                        <div class="item_bg_4"></div>
                        <div class="item_text_1"><?php echo lang('view_index_20'); ?></div>
                        <div class="item_text_2"><?php echo lang('view_index_21'); ?></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

            <div class="index_info_box_3">
                <div class="info_float_bg"></div>
                <div class="info_text_1"><?php echo lang('view_index_22'); ?></div>
                <div class="info_text_2"><?php echo lang('view_index_23'); ?></div>
                <div class="center_bg"></div>
            </div>
        </div>
        
        <?php $this->load->view('front/footer'); ?>

        <script src="<?php echo base_url('static'); ?>/layui/layui.js"></script>
        <script src="<?php echo base_url('static'); ?>/front/js/bignumber.min.js"></script>

        <script type="text/javascript">

            //????????????
            $('header .left_box .nav_box .nav_item.home').addClass('active');

            //??????????????????
            $('.body_box .index_price_list .center_box .tab_box .tab_item').click(function(){

                var _this = $(this);

                if (! _this.hasClass('active')) {

                    _this.addClass('active').siblings('.active').removeClass('active');
                    $('#' + _this.attr('target-content')).addClass('active').siblings('.active').removeClass('active');
                }
            });

            <?php if(count($marketSymbolList)){ ?>

                $(window).load(function(){

                    var BN = BigNumber.clone();
                    BN.config({DECIMAL_PLACES : 8});

                    var marketJson = <?php echo json_encode($marketSymbolList); ?>;

                    var ws = null;

                    ws = new WebSocket('<?php echo $this->config->item('ves_ws_host'); ?>');

                    ws.onopen = function(){

                        var _sendContent = JSON.stringify({

                            id : 2,
                            method : 'today.subscribe',
                            params : marketJson
                        });

                        ws.send(_sendContent);
                    }

                    ws.onmessage = function(event){

                        if (typeof event.data === 'string') {
                            
                            var _result = JSON.parse(event.data);

                            if (typeof _result === 'object') {

                                if (_result.method == 'today.update') {

                                    var price_bar_item = $('#market_item_' + _result.params[0]);
                                    var market_line_item = $('#market_item_line_' + _result.params[0]);

                                    price_bar_item.find('.market_price').text(_result.params[1].last);
                                    price_bar_item.find('.24h_deal').text(_result.params[1].volume);

                                    market_line_item.find('.market_item_line_price').text(_result.params[1].last);
                                    market_line_item.find('.24_deal').text(_result.params[1].volume);
                                    market_line_item.find('.24_high').text(_result.params[1].high);
                                    market_line_item.find('.24_low').text(_result.params[1].low);

                                    var _last = BN(_result.params[1].last);
                                    _last = _last.comparedTo(0) == 1 ? _last : 1;

                                    if (_last.minus != undefined) {

                                        var _rate = _last.minus(BN(_result.params[1].open)).div(_last).times(100).toFixed(2);

                                        price_bar_item.find('.market_price_rate').text((_rate > 0 ? '+' : '') + _rate + ' %');
                                        market_line_item.find('.market_item_line_rate').text((_rate > 0 ? '+' : '') + _rate + ' %');

                                        if (_rate >= 0) {

                                            price_bar_item.find('.market_price_rate').removeClass('down');

                                            market_line_item.find('.market_item_line_price').removeClass('price_down_text').addClass('price_up_text');
                                            market_line_item.find('.market_item_line_rate').removeClass('price_down').addClass('price_up');
                                        }else{

                                            price_bar_item.find('.market_price_rate').addClass('down');

                                            market_line_item.find('.market_item_line_price').removeClass('price_up_text').addClass('price_down_text');
                                            market_line_item.find('.market_item_line_rate').removeClass('price_up').addClass('price_down');
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

            //layui
            layui.use(['layer', 'carousel'], function(){

                var carousel = layui.carousel;

                //??????Banner
                carousel.render({
                    elem: '.body_box #index-banner',
                    width: '100%',
                    height: '500px',
                    arrow: 'hover'
                });

                layer.open({
                  title: '???????????????'
                  ,content: '<span style="color: #F00;">?????????????????????????????????????????????<br />DEMO??????????????????????????????</span>'
                });
            });

        </script>

    </body>
</html>
