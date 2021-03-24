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

        <style type="text/css">
            
            .myuploadbox{ width: 300px; }
            .imgpreview{ height: 36px; margin-left: 10px; position: relative; display: none; border: rgb(230, 230, 230) solid 1px; cursor: pointer; }
            .layui-table .imgpreview{ height: 30px; }
            .layui-table .sendbtn{ position: absolute; top: 11px; right: 175px; }
            .layui-table .editbtn{ position: absolute; top: 5px; right: 10px; }
            .layui-table .delbtn{ position: absolute; top: 5px; right: 10px; }
        </style>

        <div class="mainbox">

            <div class="layui-tab layui-tab-card">
                <ul class="layui-tab-title">
                    <li class="<?php echo $plateId == 1 ? 'layui-this' : ''; ?>">币币帐户</li>
                    <li class="<?php echo $plateId == 2 ? 'layui-this' : ''; ?>">法币帐户</li>
                    <li class="<?php echo $plateId == 4 ? 'layui-this' : ''; ?>">合约帐户</li>
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item <?php echo $plateId == 1 ? 'layui-show' : ''; ?>">
                        <table class="layui-table">
                            <colgroup>
                                <col style="width: 110px;">
                                <col>
                                <col>
                                <col>
                                <col>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>图标</th>
                                    <th>币种</th>
                                    <th>总额</th>
                                    <th>可用</th>
                                    <th>冻结</th>
                                </tr> 
                            </thead>
                            <tbody>

                                <?php if(count($userAsset)){ foreach($userAsset as $coin){ ?>
                                <tr style="position: relative;">
                                    <td><img class="imgpreview" src="<?php echo $coin['coin_icon']; ?>" style="display: inline; margin: -20px 0px;"></td>
                                    <td><?php echo $coin['coin_symbol']; ?></td>
                                    <td><?php echo $coin['asset_total']; ?></td>
                                    <td><?php echo $coin['asset_active']; ?></td>
                                    <td><?php echo $coin['asset_frozen']; ?></td>
                                </tr>
                                <?php }} ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="layui-tab-item <?php echo $plateId == 2 ? 'layui-show' : ''; ?>">

                    </div>
                    <div class="layui-tab-item <?php echo $plateId == 4 ? 'layui-show' : ''; ?>">
                        <table class="layui-table">
                            <colgroup>
                                <col style="width: 110px;">
                                <col>
                                <col>
                                <col>
                                <col>
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>图标</th>
                                    <th>币种</th>
                                    <th>总额</th>
                                    <th>可用</th>
                                    <th>冻结</th>
                                </tr> 
                            </thead>
                            <tbody>

                                <?php if(count($userDmAsset)){ foreach($userDmAsset as $dmAsset){ ?>
                                <tr style="position: relative;">
                                    <td><img class="imgpreview" src="<?php echo $dmAsset['market_stock_icon']; ?>" style="display: inline; margin: -20px 0px;"></td>
                                    <td><?php echo $dmAsset['market_stock_symbol']; ?></td>
                                    <td><?php echo $dmAsset['asset_total']; ?></td>
                                    <td><?php echo $dmAsset['asset_active']; ?></td>
                                    <td><?php echo $dmAsset['asset_frozen']; ?></td>
                                </tr>
                                <?php }} ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            
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

            
        });
    </script>
</body>
</html>