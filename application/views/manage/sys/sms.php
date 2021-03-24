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
            系统设置 > 短信设置
        </div>

        <style type="text/css">
            
            .myuploadbox{ width: 300px; }
            .imgpreview{ height: 36px; margin-left: 10px; position: relative; display: none; border: rgb(230, 230, 230) solid 1px; cursor: pointer; }

            .layui-form-item{ margin-top: 10px; }
            .layui-elem-field{ margin-bottom: 30px; }
            .layui-elem-field .layui-field-box{ padding: 10px 30px; }

            .sms-register-link{ color: #2C8FE1; font-weight: bold; }
            .sms-register-link:hover{ color: #267AC0; }

            textarea{ display: block; }

        </style>

        <div class="mainbox">

            <div class="layui-form layui-form-pane">

                <fieldset class="layui-elem-field">
                    <legend>帐户配置 (【 短信宝 】平台，<a class="sms-register-link" href="http://www.smsbao.com/reg?r=11269" target="_blank">点击注册</a> )</legend>
                    <div class="layui-field-box">
                        <div class="layui-form-item">
                            <label class="layui-form-label"><?php echo $sysconfig['sysconfig_sms_account']['sysconfig_title']; ?></label>
                            <div class="layui-input-block">
                                <input type="text" name="sysconfig_sms_account" placeholder="请输入<?php echo $sysconfig['sysconfig_sms_account']['sysconfig_title']; ?>" class="layui-input" value="<?php echo $sysconfig['sysconfig_sms_account']['sysconfig_value']; ?>">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label"><?php echo $sysconfig['sysconfig_sms_password']['sysconfig_title']; ?></label>
                            <div class="layui-input-block">
                                <input type="password" name="sysconfig_sms_password" placeholder="请输入<?php echo $sysconfig['sysconfig_sms_password']['sysconfig_title']; ?>" class="layui-input" value="<?php echo $sysconfig['sysconfig_sms_password']['sysconfig_value']; ?>">
                            </div>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>模板配置</legend>
                    <div class="layui-field-box">

                        <fieldset class="layui-elem-field">
                            <legend>验证码模板</legend>
                            <div class="layui-field-box">

                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label"><?php echo $sysconfig['sysconfig_sms_tpl_validate']['sysconfig_title']; ?></label>
                                    <div class="layui-input-block">
                                        <textarea class="layui-textarea" id="sysconfig_sms_tpl_validate" name="sysconfig_sms_tpl_validate" type="text/plain"><?php echo $sysconfig['sysconfig_sms_tpl_validate']['sysconfig_value']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="layui-elem-field">
                            <legend>注册成功模板</legend>
                            <div class="layui-field-box">

                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label"><?php echo $sysconfig['sysconfig_sms_tpl_register']['sysconfig_title']; ?></label>
                                    <div class="layui-input-block">
                                        <textarea class="layui-textarea" id="sysconfig_sms_tpl_register" name="sysconfig_sms_tpl_register" type="text/plain"><?php echo $sysconfig['sysconfig_sms_tpl_register']['sysconfig_value']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="layui-elem-field">
                            <legend>充值成功模板</legend>
                            <div class="layui-field-box">

                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label"><?php echo $sysconfig['sysconfig_sms_tpl_recharge']['sysconfig_title']; ?></label>
                                    <div class="layui-input-block">
                                        <textarea class="layui-textarea" id="sysconfig_sms_tpl_recharge" name="sysconfig_sms_tpl_recharge" type="text/plain"><?php echo $sysconfig['sysconfig_sms_tpl_recharge']['sysconfig_value']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="layui-elem-field">
                            <legend>提现成功模板</legend>
                            <div class="layui-field-box">

                                <div class="layui-form-item layui-form-text">
                                    <label class="layui-form-label"><?php echo $sysconfig['sysconfig_sms_tpl_withdraw']['sysconfig_title']; ?></label>
                                    <div class="layui-input-block">
                                        <textarea class="layui-textarea" id="sysconfig_sms_tpl_withdraw" name="sysconfig_sms_tpl_withdraw" type="text/plain"><?php echo $sysconfig['sysconfig_sms_tpl_withdraw']['sysconfig_value']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </fieldset>
                
                <div class="clear"></div>
            </div>

            <button class="layui-btn submit-sysconfig">保存设置</button>
        </div>
    </div>
    
    <script>
      
        //JavaScript代码区域
        layui.use(['element', 'jquery', 'form', 'layer'], function () {

            var element = layui.element;
            var form    = layui.form;
            var layer   = layui.layer;
            var $       = layui.$;

            var layuiOpenIndex = 0;
            var layuiLoadIndex = 0;
            var attributeBoxIndex = 0;

            //兼容layui的switch
            form.on('switch', function(data){

                if (data.elem.checked) {

                    $(data.elem).val(1).attr('checked', 'checked');
                }else{

                    $(data.elem).val(0).removeAttr('checked');
                }
            });

            //点击更新配置
            $('.submit-sysconfig').click(function(){

                var data = sysconfig.getData();

                if (sysconfig.check(data)) {

                    //准备提交
                    layuiLoadIndex = layer.load();

                    $.ajax({

                        url: '/manage/sys/sms/edit',
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

                        'sysconfig_sms_account' : $('[name=sysconfig_sms_account]').val(),
                        'sysconfig_sms_password' : $('[name=sysconfig_sms_password]').val(),
                        'sysconfig_sms_tpl_validate' : $('[name=sysconfig_sms_tpl_validate]').val(),
                        'sysconfig_sms_tpl_register' : $('[name=sysconfig_sms_tpl_register]').val(),
                        'sysconfig_sms_tpl_recharge' : $('[name=sysconfig_sms_tpl_recharge]').val(),
                        'sysconfig_sms_tpl_withdraw' : $('[name=sysconfig_sms_tpl_withdraw]').val()
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