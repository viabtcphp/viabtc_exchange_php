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

    <!-- 配置文件 -->
    <script type="text/javascript" src="<?php echo base_url('static/ueditor'); ?>/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="<?php echo base_url('static/ueditor'); ?>/ueditor.all.js"></script>
</head>
<body>

    <div class="pagebox">
        
        <div class="pagetitle layui-bg-black">
            申购列表
        </div>

        <style type="text/css">
            
            .myuploadbox{ width: 300px; }
            .imgpreview{ height: 36px; margin-left: 10px; position: relative; display: none; border: rgb(230, 230, 230) solid 1px; cursor: pointer; }
            .layui-table .imgpreview{ height: 30px; }
            .layui-table .sendbtn{ position: absolute; top: 11px; right: 175px; }
            .layui-table .editbtn{ position: absolute; top: 5px; right: 90px; }
            .layui-table .delbtn{ position: absolute; top: 5px; right: 10px; }
        </style>

        <div class="mainbox">
            
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
                        <th>用户</th>
                        <th>时间</th>
                        <th>币种</th>
                        <th>数量</th>
                        <th>比例</th>
                        <th>结果</th>
                    </tr> 
                </thead>
                <tbody>

                    <?php if(count($purchaseList)){ foreach($purchaseList as $purchase){ ?>
                    <tr style="position: relative;">
                        <td><?php echo $purchase['purchase_user_email']; ?></td>
                        <td><?php echo date('Y-m-d H:i', $purchase['purchase_time']); ?></td>
                        <td><?php echo $purchase['purchase_money_symbol']; ?></td>
                        <td><?php echo $purchase['purchase_money_amount']; ?></td>
                        <td><?php echo $purchase['purchase_rate']; ?> <?php echo $purchase['purchase_stock_symbol']; ?> / <?php echo $purchase['purchase_money_symbol']; ?></td>
                        <td><?php echo $purchase['purchase_stock_amount']; ?> <?php echo $purchase['purchase_stock_symbol']; ?></td>
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

        });
    </script>
</body>
</html>