<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo $_SESSION['SYSCONFIG']['sysconfig_site_name']; ?></title>

    <link rel="stylesheet" href="<?php echo base_url('static/layui/css'); ?>/layui.css">

    <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/manage'); ?>/style/common.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/manage'); ?>/style/style.css" />

    
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
            合约交易 > 合约设置
        </div>

        <style type="text/css">
            
            .myuploadbox{ width: 300px; }
            .imgpreview{ height: 36px; margin-left: 10px; position: relative; border: rgb(230, 230, 230) solid 1px; cursor: pointer; }

            .layui-form-item{ margin-top: 10px; }
            .layui-elem-field{ margin-bottom: 30px; }
            .layui-elem-field .layui-field-box{ padding: 10px 30px; }

            .layui-form-pane .layui-form-label{ width: 125px; }
            .layui-form-pane .layui-input-block{ margin-left: 125px; }

        </style>

        <div class="mainbox">

            <div class="layui-form layui-form-pane">

                <div class="layui-form-item">
                    <label class="layui-form-label"><?php echo $sysconfig['sysconfig_dm_rate_list']['sysconfig_title']; ?></label>
                    <div class="layui-input-block">
                        <input type="text" name="sysconfig_dm_rate_list" placeholder="请输入<?php echo $sysconfig['sysconfig_dm_rate_list']['sysconfig_title']; ?>" class="layui-input" value="<?php echo $sysconfig['sysconfig_dm_rate_list']['sysconfig_value']; ?>">
                        <?php echo $sysconfig['sysconfig_dm_rate_list']['sysconfig_comment']; ?>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label"><?php echo $sysconfig['sysconfig_dm_fee_rate']['sysconfig_title']; ?></label>
                    <div class="layui-input-block">
                        <input type="text" name="sysconfig_dm_fee_rate" placeholder="请输入<?php echo $sysconfig['sysconfig_dm_fee_rate']['sysconfig_title']; ?>" class="layui-input" value="<?php echo $sysconfig['sysconfig_dm_fee_rate']['sysconfig_value']; ?>" onkeyup="format_input_num(this);">
                        <?php echo $sysconfig['sysconfig_dm_fee_rate']['sysconfig_comment']; ?>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label"><?php echo $sysconfig['sysconfig_dm_close_balance_rate']['sysconfig_title']; ?></label>
                    <div class="layui-input-block">
                        <input type="text" name="sysconfig_dm_close_balance_rate" placeholder="请输入<?php echo $sysconfig['sysconfig_dm_close_balance_rate']['sysconfig_title']; ?>" class="layui-input" value="<?php echo $sysconfig['sysconfig_dm_close_balance_rate']['sysconfig_value']; ?>">
                        <?php echo $sysconfig['sysconfig_dm_close_balance_rate']['sysconfig_comment']; ?>
                    </div>
                </div>
                
                <div class="clear"></div>
            </div>

            <button class="layui-btn submit-sysconfig">保存设置</button>
        </div>
    </div>
    
    <script>
      
        //JavaScript代码区域
        layui.use(['element', 'jquery', 'form', 'layer', 'upload'], function () {

            var element = layui.element;
            var form    = layui.form;
            var layer   = layui.layer;
            var upload  = layui.upload;
            var $       = layui.$;

            var layuiOpenIndex = 0;
            var layuiLoadIndex = 0;
            var attributeBoxIndex = 0;

            /*var ueWidth = $('[name=sysconfig_footer_code]').parent().width() - 2;

            var sysconfig_site_about = UE.getEditor('sysconfig_site_about',{initialFrameWidth: ueWidth});
            var sysconfig_site_contact = UE.getEditor('sysconfig_site_contact',{initialFrameWidth: ueWidth});*/

            //兼容layui的switch
            form.on('switch', function(data){

                if (data.elem.checked) {

                    $(data.elem).val(1).attr('checked', 'checked');
                }else{

                    $(data.elem).val(0).removeAttr('checked');
                }
            });


            //创建update实例
            upload.render({

                elem: '.upload',
                url: '/manage/common/upload/images',
                accept: 'images',
                acceptMime: 'image/*',
                multiple: false,
                before: function(obj){

                    layuiLoadIndex = layer.load();
                },
                //上传完毕回调
                done: function(data){

                    layer.close(layuiLoadIndex);

                    if (data.status) {

                        //获取当前触发上传的元素
                        this.item.siblings('input[type=hidden]').val(data.filename[0]).siblings('img').attr('src', data.filename[0]).show();
                    }else{

                        layer.msg(data.message);
                    }
                },
                error: function(){
                    
                    layer.close(layuiLoadIndex);
                    layer.msg('网络繁忙，请稍后再试');
                }
            });

            //预览上传的图片
            $(document).on('click', '.previewitem img, .layui-table .imgpreview, .imgpreview', function(){

                var _this = $(this);

                layer.photos({
                    photos: {

                        "title": "", //相册标题
                        "id": 123, //相册id
                        "start": 0, //初始显示的图片序号，默认0
                        'data' : [

                            {
                                'src' : _this.attr('src')
                            }
                        ]
                    },
                    anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
                });
            });

            //点击更新配置
            $('.submit-sysconfig').click(function(){

                var data = sysconfig.getData();

                if (sysconfig.check(data)) {

                    //准备提交
                    layuiLoadIndex = layer.load();

                    $.ajax({

                        url: '/manage/sys/sysconfig/edit',
                        type: 'post',
                        data: data,
                        dataType: 'json',
                        success: function (data) {
                            
                            layer.close(layuiLoadIndex);
                            layer.msg(data.message);
                        },
                        error: function(){

                            layer.close(layuiLoadIndex);
                            layer.msg('网络繁忙，请稍后再试');
                        }
                    });
                }
            });

            var sysconfig = {

                getData: function(){

                    var data = {

                        'sysconfig_dm_fee_rate' : $('[name=sysconfig_dm_fee_rate]').val(),
                        'sysconfig_dm_rate_list' : $('[name=sysconfig_dm_rate_list]').val(),
                        'sysconfig_dm_close_balance_rate' : $('[name=sysconfig_dm_close_balance_rate]').val()
                    };

                    return data;
                },

                check: function(data){

                    return true;
                }
            }
        });
    </script>
</body>
</html>