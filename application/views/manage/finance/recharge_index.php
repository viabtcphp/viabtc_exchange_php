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
            财务管理 > 充值记录
        </div>

        <style type="text/css">
            
            .myuploadbox{ width: 300px; }
            .imgpreview{ height: 36px; margin-left: 10px; position: relative; display: none; border: rgb(230, 230, 230) solid 1px; cursor: pointer; }
            .layui-table .imgpreview{ height: 30px; }
            .layui-table .sendbtn{ position: absolute; top: 11px; right: 180px; }
            .layui-table .editbtn{ position: absolute; top: 5px; right: 90px; }
            .layui-table .delbtn{ position: absolute; top: 5px; right: 10px; }
        </style>

        <div class="mainbox">

            <style type="text/css">
                .search_box{ margin-bottom: 20px; }
                .search_box .search_value{ width: 300px; float: left; }
                .search_box .search_btn, .search_box .clear_search_btn{ float: left; margin-left: 10px; }
                .search_box .download_btn{ float: right; }
            </style>
            <div class="search_box layui-form">
                <input type="text" id="search_value" placeholder="搜索用户名、手机、邮箱" autocomplete="off" class="layui-input search_value" value="<?php echo $search; ?>">
                <button type="button" class="layui-btn search_btn" id="search_btn" data-url="/manage/finance/recharge">搜索用户</button>
                <a class="layui-btn layui-btn-normal clear_search_btn" href="/manage/finance/recharge">清空</a>

                <a class="layui-btn layui-btn-normal download_btn" download="充值报表_<?php echo date('Y_m_d_H_i_s'); ?>.csv" href="/manage/finance/recharge/download">下载报表</a>
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
                </colgroup>
                <thead>
                    <tr>
                        <th>用户名</th>
                        <th>到帐时间</th>
                        <th>币种</th>
                        <th>充值数量</th>
                        <th>业务流水号</th>
                        <th>上级代理商</th>
                    </tr> 
                </thead>
                <tbody>

                    <?php if(count($rechargeList)){ foreach($rechargeList as $recharge){ ?>
                    <tr style="position: relative;">
                        <td><?php echo $recharge['recharge_user_name']; ?></td>
                        <td><?php echo date('Y-m-d H:i', $recharge['recharge_time']); ?></td>
                        <td><?php echo $recharge['recharge_coin_symbol']; ?></td>
                        <td><?php echo $recharge['recharge_amount']; ?></td>
                        <td><?php echo $recharge['recharge_trade_id']; ?></td>
                        <td><?php echo $recharge['recharge_user_parent_name']; ?></td>
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