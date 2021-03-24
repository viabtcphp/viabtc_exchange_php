<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo $_SESSION['SYSCONFIG']['sysconfig_site_name']; ?></title>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/manage'); ?>/style/common.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/manage'); ?>/style/style.css" />

    <link rel="stylesheet" href="<?php echo base_url('static/layui/css'); ?>/layui.css">
    
    <!--[if lt IE 9]>
    <script src="<?php echo base_url('static/manage'); ?>/js/css3.js"></script>
    <script src="<?php echo base_url('static/manage'); ?>/js/html5.js"></script>
    <![endif]-->

    <script src="<?php echo base_url('static/manage'); ?>/js/common.js"></script>
    <script src="<?php echo base_url('static/layui'); ?>/layui.js"></script>

    <script src="/static/manage/js/jquery-1.8.0.min.js"></script>   
    <script src="/static/editor.md/editormd.js"></script>   
</head>
<body>

    <div class="pagebox">
        
        <div class="pagetitle layui-bg-black">
            合约交易 > 历史订单
        </div>

        <style type="text/css">
            
            .myuploadbox{ width: 300px; }
            .imgpreview{ height: 36px; margin-left: 10px; position: relative; display: none; border: rgb(230, 230, 230) solid 1px; cursor: pointer; }
            .layui-table .imgpreview{ height: 30px; }

            .layui-table td{ font-size: 12px; }
        </style>

        <div class="mainbox">

            <style type="text/css">
                .search_box{ margin-bottom: 20px; }
                .search_box .search_value{ width: 300px; float: left; }
                .search_box .search_btn, .search_box .clear_search_btn{ float: left; margin-left: 10px; }
            </style>
            <div class="search_box layui-form">
                <input type="text" id="search_value" placeholder="搜索用户名、手机、邮箱" autocomplete="off" class="layui-input search_value" value="<?php echo $search; ?>">
                <button type="button" class="layui-btn search_btn" id="search_btn" data-url="/manage/dm/dm/history">搜索用户</button>
                <a class="layui-btn layui-btn-normal clear_search_btn" href="/manage/dm/dm/history">清空</a>
                <div class="clear"></div>
            </div>
            
            <table class="layui-table" >
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col style="width: 70px;">
                </colgroup>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>合约币种</th>
                        <th>用户名</th>
                        <th>时间</th>
                        <th>方向</th>
                        <th>倍数</th>
                        <th>类型</th>
                        <th>委托价</th>
                        <th>最新价</th>
                        <th>数量</th>
                        <th>开仓时间</th>
                        <th>平仓时间</th>
                        <th>盈亏</th>
                        <th>状态</th>
                    </tr> 
                </thead>
                <tbody>

                    <?php if(count($dmList)){ foreach($dmList as $dm){ ?>
                    <tr style="position: relative;">
                        <td><?php echo $dm['dm_id']; ?></td>
                        <td><?php echo $dm['dm_coin_symbol']; ?></td>
                        <td><?php echo $dm['dm_user_name']; ?></td>
                        <td><?php echo date('Y/m/d H:i:s', $dm['dm_order_time']); ?></td>
                        <td>
                            <a class="layui-btn <?php echo $dm['dm_direction'] == 1 ? '' : 'layui-btn-danger'; ?> layui-btn-xs"><?php echo $dm['dm_direction'] == '1' ? '做多' : '做空'; ?></a>
                        </td>
                        <td><?php echo $dm['dm_multiple']; ?>×</td>
                        <td><?php echo $dm['dm_trade_type'] == '1' ? '市价' : '限价'; ?></td>
                        <td><?php echo $dm['dm_open_price']; ?></td>
                        <td><?php echo $dm['current_price']; ?></td>
                        <td><?php echo $dm['dm_order_amount']; ?></td>
                        <td><?php echo $dm['dm_status'] == 1 ? date('Y/m/d H:i:s', $dm['dm_open_time']) : '--'; ?></td>
                        <td><?php echo $dm['dm_status'] == 1 ? date('Y/m/d H:i:s', $dm['dm_close_time']) : '--'; ?></td>
                        <td>
                            <?php if($dm['dm_status'] == 1){ ?>
                                <a class="layui-btn <?php echo bccomp($dm['dm_profit'], 0) >= 0 ? '' : 'layui-btn-danger'; ?> layui-btn-xs"><?php echo bccomp($dm['dm_profit'], 0) >= 0 ? ('+' . $dm['dm_profit']) : $dm['dm_profit']; ?></a>
                            <?php }else{ ?>
                                --
                            <?php } ?>
                        </td>
                        <td>
                            <a class="layui-btn <?php echo $dm['dm_status'] == 1 ? '' : 'layui-btn-danger'; ?> layui-btn-xs"><?php echo $dm['dm_status'] == 1 ? ($dm['dm_close_type'] == 1 ? '手动平仓' : '爆仓强平') : '已取消'; ?></a>
                        </td>
                    </tr>
                    <?php }} ?>
                </tbody>
            </table>

            <!-- 分页 -->
            <?php $this->load->view('manage/paging'); ?>
        </div>
    </div>
    
    <script>
      
        //JavaScript代码区域
        layui.use(['element', 'jquery', 'form', 'layer', 'upload', 'laydate'], function () {

            var element = layui.element;
            var form    = layui.form;
            var layer   = layui.layer;
            var upload  = layui.upload;
            var laydate = layui.laydate;
            var $       = layui.$;

            var layuiOpenIndex = 0;
            var layuiLoadIndex = 0;
            
            $('#search_btn').click(function(){

                var searchValue = $.trim($('#search_value').val());

                if (searchValue == '') {

                    layer.msg('请输入搜索内容');
                }else{

                    layuiLoadIndex = layer.load();
                    window.location.href = $(this).attr('data-url') + '?search=' + searchValue;
                }
            });
        });
    </script>
</body>
</html>