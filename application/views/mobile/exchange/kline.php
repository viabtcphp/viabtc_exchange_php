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
                
                header{ height: 50px; background: #191a1f; padding: 0px 10px; }
                header .market_list_btn{ float: left; font-size: 17px; color: #d5def2; line-height: 50px; font-weight: bold; }
                header .market_list_btn i{ padding-right: 10px; margin-right: 5px; border-right: #697080 solid 1px; }
                header .current_market_rate{ float: left; font-size: 12px; line-height: 20px; margin-left: 10px; color: #05c19e; background: rgba(5, 193, 158, .1); padding: 0px 5px; border-radius: 3px; margin-top: 15px; }
                header .current_market_rate.down{ color: #e04545; background: rgba(212, 48, 42, .1); }

                .price_info_box{ padding: 10px;}
                .price_info_box .info_table_box{ float: right; }
                .price_info_box .info_table_box td{ font-size: 10px; color: #d5def2; text-align: right; line-height: 15px; height: 20px; }
                .price_info_box .info_table_box td.title_item{ padding-right: 10px; text-align: left; color: #697080; }
                .price_info_box .current_price{ line-height: 60px; height: 60px; font-size: 25px; color: #05c19e; }
                .price_info_box .current_price.down{ color: #e04545; }
            </style>
            
            <header>
                <div class="market_list_btn" data-link="/<?php echo $_GET['kline_from'] ?>/<?php echo strtolower($market['market_stock_symbol']); ?>/<?php echo strtolower($market['market_money_symbol']); ?>">
                    <i class="layui-icon layui-icon-return"></i>
                    <?php echo $market['market_stock_symbol']; ?><?php if($_GET['kline_from'] == 'exchange'){ ?>/<?php echo $market['market_money_symbol']; ?><?php }else{ ?> <?php echo lang('view_mobile_kline_1'); ?><?php } ?>
                </div>
                <div class="current_market_rate">--</div>
                <div class="clear"></div>
            </header>

            <div class="price_info_box">
                <div class="info_table_box">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tr>
                            <td class="title_item"><?php echo lang('view_mobile_kline_2'); ?></td>
                            <td class="current_market_24_high">--</td>
                        </tr>
                        <tr>
                            <td class="title_item"><?php echo lang('view_mobile_kline_3'); ?></td>
                            <td class="current_market_24_low">--</td>
                        </tr>
                        <tr>
                            <td class="title_item"><?php echo lang('view_mobile_kline_4'); ?></td>
                            <td class="current_market_24_vol">--</td>
                        </tr>
                    </table>
                </div>
                <div class="current_price current_market_price">--</div>
                <div class="clear"></div>
            </div>

            <style type="text/css">
                
                .tv_box *{margin:0; padding:0; font-size:14px; line-height:1; -webkit-box-sizing:border-box; box-sizing:border-box}
                .tv_box body{background:rgba(52,54,63,.5)}
                .tv_box .show{display:block}
                .tv_box .hide{display:none}
                .tv_box ul li{list-style:none}
                .tv_box .main-wrapper.black{background:#1f2126}
                .tv_box .main-wrapper.black .theme-wrapper{background:#191a1f}
                .tv_box .main-wrapper.blue{background:#171b2b}
                .tv_box .main-wrapper.blue .theme-wrapper{background:#131625}
                .tv_box .tv-wrapper{display:-webkit-box; display:-ms-flexbox; display:flex; -webkit-box-orient:vertical; -webkit-box-direction:normal; -ms-flex-direction:column; flex-direction:column; height:450px}
                .tv_box .tv-wrapper .tv-top-bar{height:35px; border-bottom:1px solid #000; border-top:1px solid #000; font-size:0}
                .tv_box .tv-wrapper .tv-top-bar .border-right{border-right:1px solid #000}
                .tv_box .tv-wrapper .tv-top-bar .border-left{border-left:1px solid #000}
                .tv_box .tv-wrapper .tv-top-bar .tv-interval-list{display:block; vertical-align:top; font-size:0}
                .tv_box .tv-wrapper .tv-top-bar .tv-interval-list .interval-item{display: block; font-size:12px; color:#61688a; line-height: 33px; cursor:pointer; width: calc(100% / 8); float: left; text-align: center; box-sizing: border-box; }
                .tv_box .tv-wrapper .tv-top-bar .tv-interval-list .interval-item:hover{background:rgba(37,42,68,.5)}
                .tv_box .tv-wrapper .tv-top-bar .tv-interval-list .interval-item.active{color:#357ce1; background:rgba(37,42,68,.5)}
                .tv_box .TradingView{-webkit-box-flex:1; -ms-flex:1 1 auto; flex:1 1 auto; position:relative}
                .tv_box #tv-container{position:absolute; top:0; left:0; right:0; bottom:0}

            </style>

            <div class="tv_box">
                <div class="main-wrapper black">
                    <div class="tv-wrapper ">
                        <div class="tv-top-bar">
                            <ul class="tv-interval-list" id="intervalWrapper">
                                <li class="interval-item" data-interval="1" data-chartType="1">1min</li>
                                <li class="interval-item active" data-interval="5" data-chartType="1">5min</li>
                                <li class="interval-item" data-interval="15" data-chartType="1">15min</li>
                                <li class="interval-item" data-interval="30" data-chartType="1">30min</li>
                                <li class="interval-item" data-interval="60" data-chartType="1">1hour</li>
                                <li class="interval-item" data-interval="240" data-chartType="1">4hour</li>
                                <li class="interval-item" data-interval="D" data-chartType="1">1day</li>
                                <li class="interval-item" data-interval="W" data-chartType="1">1week</li>
                                <div class="clear"></div>
                            </ul>
                        </div>
                        <div class="TradingView">
                            <div id="tv-container"></div>
                        </div>
                    </div>
                </div>
            </div>

            <style type="text/css">
                
                .deal_box{ margin: 10px; }
                .deal_box .deal_left{ font-size: 12px; float: left; color: #d5def2; text-align: left; width: 30%; line-height: 30px; }
                .deal_box .deal_center{ font-size: 12px; float: left; color: #05c19e; text-align: left; width: 35%; line-height: 30px; }
                .deal_box .deal_center.price_down{ color: #e04545; }
                .deal_box .deal_right{ font-size: 12px; float: left; color: #d5def2; text-align: right; width: 35%; line-height: 30px; }
                .deal_box .deal_title_item *{ color: #697080; }
            </style>

            <div class="deal_box">
                
                <div class="deal_title_item">
                    <div class="deal_left"><?php echo lang('view_mobile_kline_5'); ?></div>
                    <div class="deal_center"><?php echo lang('view_mobile_kline_6'); ?>(<?php echo $market['market_money_symbol']; ?>)</div>
                    <div class="deal_right"><?php echo lang('view_mobile_kline_7'); ?>(<?php echo $market['market_stock_symbol']; ?>)</div>
                    <div class="clear"></div>
                </div>

                <!-- 
                <div class="deal_item">
                    <div class="deal_left"></div>
                    <div class="deal_center"></div>
                    <div class="deal_right"></div>
                    <div class="clear"></div>
                </div>
                -->
            </div>

        </div>

        <?php $this->load->view('mobile/footer'); ?>

        <script src="<?php echo base_url('static'); ?>/mobile/js/bignumber.min.js"></script>
        <script src="<?php echo base_url('static'); ?>/charting_library/charting_library.min.js?v=1.5" async></script>
        <script src="<?php echo base_url('static'); ?>/mobile/js/tv.js"></script>

        <script type="text/javascript">

            //当前栏目
            $('footer .navitem.<?php echo isset($_GET['kline_from']) ? $_GET['kline_from'] : ''; ?>').addClass('active');

            var ws = null;
            var title_text = $('title').text();

            $('header .market_list_btn').click(function(){

                window.location.href=$(this).attr('data-link');
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

                            id : 4,
                            method : 'deals.query',
                            params : [marketSymbol, 36, 0]
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

                                        $('.current_market_price').text(_result.params[1].last);
                                        $('.current_market_rate').text((_rate > 0 ? '+' : '') + _rate + ' %');

                                        $('.current_market_24_high').text(_result.params[1].high);
                                        $('.current_market_24_low').text(_result.params[1].low);
                                        $('.current_market_24_vol').text(_result.params[1].volume);

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

                                if (_result.id == 4) {

                                    if (_result.result.length > 0) {

                                        var _deals_html = '';

                                        for(var _index in _result.result){

                                            _deals_html += 

                                            '<div class="deal_item">' +
                                                '<div class="deal_left">' + unix2time((parseInt(_result.result[_index]['time']) + timeSub) * 1000) + '</div>' +
                                                '<div class="deal_center ' + (_result.result[_index]['type'] == 'buy' ? 'price_up' : 'price_down') + '">' + _result.result[_index]['price'] + '</div>' +
                                                '<div class="deal_right">' + _result.result[_index]['amount'] + '</div>' +
                                                '<div class="clear"></div>' +
                                            '</div>';
                                        }

                                        $('.deal_title_item').after(_deals_html);
                                    }

                                    var _sendContent = JSON.stringify({

                                        id : 5,
                                        method : 'deals.subscribe',
                                        params : [marketSymbol]
                                    });

                                    ws.send(_sendContent);
                                }

                                if (_result.method == 'deals.update') {

                                    if (_result.params.length > 0 && _result.params[1].length > 0) {

                                        var _deals_html = '';

                                        var _i = 0;

                                        for(var _index in _result.params[1]){
                                            
                                            var _timeText = unix2time((parseInt(_result.params[1][_index]['time']) + timeSub) * 1000);
                                            _timeText = _timeText == '' ? unix2time(Date.now()) : _timeText;

                                            _deals_html += 

                                            '<div class="deal_item">' +
                                                '<div class="deal_left">' + _timeText + '</div>' +
                                                '<div class="deal_center ' + (_result.params[1][_index]['type'] == 'buy' ? 'price_up' : 'price_down') + '">' + _result.params[1][_index]['price'] + '</div>' +
                                                '<div class="deal_right">' + _result.params[1][_index]['amount'] + '</div>' +
                                                '<div class="clear"></div>' +
                                            '</div>';

                                            _i ++;

                                            if (_i == 36) {

                                                break;
                                            }
                                        }

                                        if ($('.deal_item').length > _i) {

                                            for(var i = 1; i <= _i; i ++){

                                                $('.deal_item').eq(36-i).remove();
                                            }

                                            $('.deal_item').eq(0).before(_deals_html);
                                        }else{

                                            $('.deal_item').remove();
                                            $('.deal_title_item').after(_deals_html);
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

                    //tv
                    tvStart(ws, {
                        baseSymbol: '<?php echo $market['market_money_symbol']; ?>', // 计价币种
                        coinSymbol: '<?php echo $market['market_stock_symbol']; ?>', // 交易币种
                        baseScale: <?php echo $market['market_decimal']; ?>, // 交易币种精度
                        coinScale: <?php echo $market['market_decimal']; ?>, // 计价币种精度
                        volumeScale: <?php echo $market['market_decimal']; ?>, // 成交量精度保留
                        interval: '5',
                        language: '<?php echo lang('tv'); ?>'
                    });
                    //tv
                });

            <?php } ?>


        </script>

    </body>
</html>
