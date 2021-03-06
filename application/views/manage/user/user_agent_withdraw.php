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

            .search_box .download_btn{ float: right; }
        </style>

        <div class="mainbox">

            <style type="text/css">
                .search_box{ margin-bottom: 20px; }
                .search_box .search_value{ width: 300px; float: left; }
                .search_box .search_btn, .search_box .clear_search_btn{ float: left; margin-left: 10px; }
            </style>
            
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
                </colgroup>
                <thead>
                    <tr>
                        <th>?????????</th>
                        <th>????????????</th>
                        <th>??????</th>
                        <th>????????????</th>
                        <th>????????????</th>
                        <th>????????????</th>
                        <th>???????????????</th>
                        <th>??????</th>
                    </tr> 
                </thead>
                <tbody>

                    <?php if(count($withdrawList)){ foreach($withdrawList as $withdraw){ ?>
                    <tr style="position: relative;">
                        <td><?php echo $withdraw['withdraw_user_name']; ?></td>
                        <td><?php echo date('Y-m-d H:i', $withdraw['withdraw_time']); ?></td>
                        <td><?php echo $withdraw['withdraw_chain_symbol']; ?></td>
                        <td><?php echo $withdraw['withdraw_amount']; ?></td>
                        <td><?php echo $withdraw['withdraw_finally_amount']; ?></td>
                        <td>
                            <?php echo $withdraw['withdraw_no']; ?>
                            <?php if($withdraw['withdraw_local']){ ?>
                                <span class="layui-btn layui-btn-xs">??????</span>
                            <?php } ?>
                        </td>
                        <td><?php echo $withdraw['withdraw_user_parent_name']; ?></td>
                        <td>

                            <a class="layui-btn 
                                <?php switch($withdraw['withdraw_status']){

                                    case 0:
                                        echo 'layui-btn-warm';
                                    break;

                                    case 1:
                                        echo 'layui-btn-warm';
                                    break;

                                    case 2:
                                        echo 'layui-btn-danger';
                                    break;

                                    case 3:
                                        echo 'layui-btn-warm';
                                    break;

                                    case 4:
                                        echo 'layui-btn-warm';
                                    break;

                                    case 5:
                                        echo 'layui-btn-danger';
                                    break;

                                    case 6:
                                        echo '';
                                    break;

                                    case 7:
                                        echo 'layui-btn-danger';
                                    break;
                                } ?>
                            layui-btn-xs">
                                <?php switch($withdraw['withdraw_status']){

                                    case 0:
                                        echo '?????????';
                                    break;

                                    case 1:
                                        echo '????????????';
                                    break;

                                    case 2:
                                        echo '????????????';
                                    break;

                                    case 3:
                                        echo '???????????????';
                                    break;

                                    case 4:
                                        echo '????????????';
                                    break;

                                    case 5:
                                        echo '????????????';
                                    break;

                                    case 6:
                                        echo '??????????????????';
                                    break;

                                    case 7:
                                        echo '??????????????????';
                                    break;
                                } ?>
                            </a>
                        </td>
                    </tr>
                    <?php }} ?>
                </tbody>
            </table>

            <!-- ?????? -->
            <?php $this->load->view('manage/paging'); ?>
        </div>

    </div>
    
    <script>
      
        //JavaScript????????????
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