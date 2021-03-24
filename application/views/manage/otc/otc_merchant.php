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
            <button class="layui-btn addbtn" id="addbtn" data-title="添加商户">添加商户</button>
            法币交易 > 商户管理
        </div>

        <style type="text/css">
            
            .myuploadbox{ width: 300px; }
            .imgpreview{ height: 36px; margin-left: 10px; position: relative; display: none; border: rgb(230, 230, 230) solid 1px; cursor: pointer; }
            .layui-table .imgpreview{ height: 30px; }
        </style>

        <div class="mainbox">
            
            <table class="layui-table" >
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col style="width: 265px;">
                </colgroup>
                <thead>
                    <tr>
                        <th>用户名</th>
                        <th>商户名称</th>
                        <th>联系方式</th>
                        <th>商户状态</th>
                        <th>操作</th>
                    </tr> 
                </thead>
                <tbody>

                    <?php if(count($userList)){ foreach($userList as $user){ ?>
                    <tr style="position: relative;">
                        <td><?php echo $user['user_name']; ?></td>
                        <td><?php echo $user['user_merchant_name']; ?></td>
                        <td><?php echo $user['user_contact']; ?></td>
                        <td>
                            <a class="layui-btn <?php echo $user['user_merchant_status'] == '1' ? '' : 'layui-btn-danger'; ?> layui-btn-xs"><?php echo $user['user_merchant_status'] == '1' ? '正常' : '封禁'; ?></a>    
                        </td>
                        <td style="position: relative; width: 265px;">

                            <a class="layui-btn layui-btn-xs loginbtn" data-id="<?php echo $user['user_id']; ?>">前台登陆</a>

                            <a class="layui-btn layui-btn-xs sendbtn window_show" data-title="资产详情 - <?php echo $user['user_name']; ?>" data-href="/manage/user/asset/user_asset/<?php echo $user['user_id']; ?>/2">资产详情</a>
                            <a class="layui-btn layui-btn-normal layui-btn-xs editbtn" data-id="<?php echo $user['user_id']; ?>" data-title="编辑商户 - <?php echo $user['user_email']; ?>">编辑商户</a>
                            <a class="layui-btn layui-btn-danger layui-btn-xs delbtn" data-title="解除商户 - <?php echo $user['user_merchant_name']; ?>" data-id="<?php echo $user['user_id']; ?>" data-merchant="<?php echo $user['user_merchant_name']; ?>" data-user-name="<?php echo $user['user_name']; ?>">解除商户</a>
                        </td>
                    </tr>
                    <?php }} ?>
                </tbody>
            </table>

            <!-- 分页 -->
            <?php $this->load->view('manage/paging'); ?>
        </div>

        <!-- 编辑框 -->
        <div class="editbox displaynone" id="editbox">
            
            <div class="layui-form layui-form-pane">

                <input type="hidden" name="user_id" value="0">

                <div class="layui-form-item">
                    <label class="layui-form-label">用户名</label>
                    <div class="layui-input-block">
                        <input type="text" name="user_name" placeholder="请输入平台上的用户名" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">联系方式</label>
                    <div class="layui-input-block">
                        <input type="text" name="user_contact" placeholder="请输入联系方式" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">商户名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="user_merchant_name" placeholder="请输入商户名称" class="layui-input">
                    </div>
                </div>

                <fieldset class="layui-elem-field">
                    <legend>收款方式</legend>
                    <div class="layui-field-box">
                        
                        <div class="layui-tab layui-tab-card">
                            <ul class="layui-tab-title">
                                <?php if(count($otcPayTypeList)){ $i = 0; foreach($otcPayTypeList as $payTypeSymbol => $payTypeItem){ ?>
                                <li class="<?php echo $i==0 ? 'layui-this' : ''; ?>"><?php echo $payTypeItem['name']; ?></li>
                                <?php $i ++; }} ?>
                            </ul>
                            <div class="layui-tab-content">
                                <?php if(count($otcPayTypeList)){ $i = 0; foreach($otcPayTypeList as $payTypeSymbol => $payTypeItem){ ?>
                                <div class="layui-tab-item <?php echo $i==0 ? 'layui-show' : ''; ?> pay_item" id="pay_item_<?php echo $payTypeSymbol; ?>">
                                    <div style="padding: 10px;">

                                        <input class="pay_info_item" type="hidden" name="pay_symbol" value="<?php echo $payTypeSymbol; ?>">

                                        <div class="layui-form-item" pane>
                                            <label class="layui-form-label">启用</label>
                                            <div class="layui-input-block">
                                                <input type="checkbox" class="pay_info_item" name="pay_status" lay-skin="switch" lay-text="开启|关闭" value="0">
                                            </div>
                                        </div>
                                        
                                        <div class="layui-form-item">
                                            <label class="layui-form-label">支持货币</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="pay_unit" class="layui-input readonly pay_info_item" readonly value="<?php echo $payTypeItem['unit']; ?>">
                                            </div>
                                        </div>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label">收款姓名</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="pay_username" placeholder="请输入收款姓名" class="layui-input pay_info_item">
                                            </div>
                                        </div>

                                        <?php if($payTypeItem['bank_name']){ ?>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">银行</label>
                                                <div class="layui-input-block">
                                                    <select name="pay_bank_name" class="pay_info_item">
                                                        <?php if($payTypeItem['bank_name'] && count($payTypeItem['bank_name'])){ foreach ( $payTypeItem['bank_name'] as $bank_nameItem) { ?>
                                                            <option value="<?php  echo $bank_nameItem; ?>"><?php echo $bank_nameItem; ?></option>
                                                        <?php }}; ?>
                                                    </select>
                                                </div>
                                            </div>
                                        <?php } ?>

                                        <?php if($payTypeItem['bank_address']){ ?>
                                            <div class="layui-form-item">
                                            <label class="layui-form-label">开户行</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="pay_bank_address" placeholder="请输入开户行" class="layui-input pay_info_item">
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <div class="layui-form-item">
                                            <label class="layui-form-label">收款帐号</label>
                                            <div class="layui-input-block">
                                                <input type="text" name="pay_account" placeholder="请输入收款帐号" class="layui-input pay_info_item">
                                            </div>
                                        </div>

                                        <?php if($payTypeItem['need_image']){ ?>
                                            <div class="layui-form-item">
                                                <label class="layui-form-label">收款图片</label>
                                                <div class="layui-input-block">
                                                    <input type="hidden" class="pay_info_item" name="pay_image">
                                                    <button type="button" class="layui-btn upload" style="margin-left: 10px;">
                                                        <i class="layui-icon">&#xe67c;</i>上传收款图片
                                                    </button>
                                                    <img src="" class="imgpreview pointer" />
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php $i ++; }} ?>
                            </div>
                        </div>

                    </div>
                </fieldset>

                <div class="layui-form-item" pane>
                    <label class="layui-form-label">商户状态</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="user_merchant_status" lay-skin="switch" checked lay-text="正常|禁用" value="1">
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

            //兼容layui的select的change事件
            form.on('select', function(data){

                $(data.elem).trigger('change');

            });

            //兼容layui的switch
            form.on('switch', function(data){

                if (data.elem.checked) {

                    $(data.elem).val(1).prop('checked', true);
                }else{

                    $(data.elem).val(0).prop('checked', false);
                }
            });

            $('.loginbtn').click(function(){

                var _this = $(this);

                layer.load();

                $.ajax({
                    url: '/manage/user/user/login',
                    type: 'post',
                    data: {
                        'user_id' : _this.attr('data-id')
                    },
                    success: function (data) {
                        
                        window.open('/account', 'target');
                    },
                    complete: function(){

                        layer.closeAll();
                    }
                });
            });


            //添加商户
            $('#addbtn').click(function(){

                var _this = $(this);

                layuiOpenIndex = layer.open({

                    title: _this.attr('data-title'),
                    type: 1,
                    content: $('#editbox'),
                    skin: 'my-layer-green',
                    area: '80%',
                    btnAlign: 'c',
                    btn: ['提交', '取消'],
                    maxmin: true,
                    zIndex: 99,
                    success: function(){

                    },
                    yes: function(){

                        //获取收款信息
                        var payList = {};
                        $('#editbox .pay_item').each(function(){

                            var payItem = {};

                            $(this).find('.pay_info_item').each(function(){

                                payItem[$(this).attr('name')] = $(this).val();
                            });

                            payList[$(this).find('[name=pay_symbol]').val()] = payItem;
                        });

                        var data = {

                            'user_name' : $('#editbox [name=user_name]').val(),
                            'user_contact' : $('#editbox [name=user_contact]').val(),
                            'user_merchant_name' : $('#editbox [name=user_merchant_name]').val(),
                            'user_merchant_status' : $('#editbox [name=user_merchant_status]').val(),
                            'user_merchant_pay' : payList
                        };

                        if (user.checkForm(data)) {

                            layuiLoadIndex = layer.load();

                            $.ajax({
                                url: '/manage/otc/merchant/add',
                                type: 'post',
                                data: data,
                                dataType: 'json',
                                success: function (data) {
                                    
                                    layer.close(layuiLoadIndex);
                                    layer.msg(data.message);

                                    if (data.status) {

                                        layer.close(layuiOpenIndex);
                                        setTimeout(function(){

                                            window.location.reload();
                                        }, 1000);
                                    }
                                },
                                error: function(){

                                    layer.close(layuiLoadIndex);
                                    layer.msg('网络繁忙，请稍后再试');
                                }
                            });
                        }
                    },
                    end: function(){

                        layer.close(layuiOpenIndex);
                        window.location.reload();
                    }
                });
            });


            //编辑用户
            $('.editbtn').click(function(){

                var _this = $(this);

                var _userId = _this.attr('data-id');

                layuiLoadIndex = layer.load();

                //获取数据
                $.ajax({

                    url: '/manage/otc/merchant/one',
                    type: 'post',
                    data: {

                        'user_id' : _userId
                    },
                    dataType: 'json',
                    success: function (data) {

                        if (data.status) {
                            
                            //更新表单
                            $('#editbox [name=user_id]').val(data.user.user_id);
                            $('#editbox [name=user_name]').val(data.user.user_name);
                            $('#editbox [name=user_contact]').val(data.user.user_contact);
                            $('#editbox [name=user_merchant_name]').val(data.user.user_merchant_name);
                            
                            //swtich
                            if (data.user.user_merchant_status == '1') {

                                $('#editbox [name=user_merchant_status]').val(1).prop('checked', true);
                            }else{

                                $('#editbox [name=user_merchant_status]').val(0).prop('checked', false);
                            }

                            //收款信息
                            if (data.user.user_merchant_pay_count > 0) {

                                for(var _index in data.user.user_merchant_pay){

                                    var _pay_item_box = $('#pay_item_' + data.user.user_merchant_pay[_index].pay_symbol);

                                    if (_pay_item_box.length) {

                                        _pay_item_box.find('.pay_info_item').each(function(){

                                            //switch
                                            if ($(this).attr('name') == 'pay_status') {

                                                if (data.user.user_merchant_pay[_index].pay_status == '1') {

                                                    $(this).val(1).prop('checked', true);
                                                }else{

                                                    $(this).val(0).prop('checked', false);
                                                }

                                            //image
                                            }else if($(this).attr('name') == 'pay_image'){

                                                if (data.user.user_merchant_pay[_index].pay_image != '') {

                                                    $(this).val(data.user.user_merchant_pay[_index].pay_image).siblings('img').attr('src', data.user.user_merchant_pay[_index].pay_image).show();
                                                }
                                            }else{

                                                $(this).val(data.user.user_merchant_pay[_index][$(this).attr('name')]);
                                            }
                                        });
                                    }
                                }
                            }

                            form.render();
                            layer.close(layuiLoadIndex);

                            layuiOpenIndex = layer.open({

                                title: _this.attr('data-title'),
                                type: 1,
                                content: $('#editbox'),
                                skin: 'my-layer-yellow',
                                area: '80%',
                                maxHeight: '500px',
                                btnAlign: 'c',
                                btn: ['提交', '取消'],
                                maxmin: true,
                                zIndex: 99,
                                success: function(){

                                },
                                yes: function(){

                                    //获取收款信息
                                    var payList = {};
                                    $('#editbox .pay_item').each(function(){

                                        var payItem = {};

                                        $(this).find('.pay_info_item').each(function(){

                                            payItem[$(this).attr('name')] = $(this).val();
                                        });

                                        payList[$(this).find('[name=pay_symbol]').val()] = payItem;
                                    });

                                    var data = {

                                        'user_id' : $('#editbox [name=user_id]').val(),
                                        'user_name' : $('#editbox [name=user_name]').val(),
                                        'user_contact' : $('#editbox [name=user_contact]').val(),
                                        'user_merchant_name' : $('#editbox [name=user_merchant_name]').val(),
                                        'user_merchant_status' : $('#editbox [name=user_merchant_status]').val(),
                                        'user_merchant_pay' : payList
                                    };

                                    if (user.checkForm(data)) {

                                        layuiLoadIndex = layer.load();

                                        $.ajax({
                                            url: '/manage/otc/merchant/edit',
                                            type: 'post',
                                            data: data,
                                            dataType: 'json',
                                            success: function (data) {
                                                
                                                layer.close(layuiLoadIndex);
                                                layer.msg(data.message);

                                                if (data.status) {

                                                    layer.close(layuiOpenIndex);
                                                    setTimeout(function(){

                                                        window.location.reload();
                                                    }, 1000);
                                                }
                                            },
                                            error: function(){

                                                layer.close(layuiLoadIndex);
                                                layer.msg('网络繁忙，请稍后再试');
                                            }
                                        });
                                    }
                                },
                                end: function(){

                                    layer.close(layuiOpenIndex);
                                    window.location.reload();
                                }
                            });
                        }else{

                            layer.close(layuiLoadIndex);
                            layer.msg(data.message);
                        }
                    },
                    error: function(){

                        layer.close(layuiLoadIndex);
                        layer.msg('网络繁忙，请稍后再试');
                    }
                });

                return false;
            });


            //解除
            $('.delbtn').click(function(){

                var _this = $(this);
                var _userId = _this.attr('data-id');
                var _userName = _this.attr('data-user-name');
                var _merchantName = _this.attr('data-merchant');

                layuiOpenIndex = layer.confirm(

                    '确定要解除该商户 ? <br />用户名：' + _userName + '<br />商户名：' + _merchantName,
                    {
                        title: _this.attr('data-title'),
                        icon: 0,
                        skin: 'my-layer-red'
                    },
                    function(index){

                        layer.close(index);
                        layuiLoadIndex = layer.load();

                        $.ajax({
                            url: '/manage/otc/merchant/delete',
                            type: 'post',
                            data: {
                                'user_id' : _userId
                            },
                            dataType: 'json',
                            success: function (data) {
                                
                                layer.close(layuiLoadIndex);
                                layer.msg(data.message);

                                if (data.status) {

                                    setTimeout(function(){

                                        window.location.reload();
                                    }, 1000);
                                }
                            },
                            error: function(){

                                layer.close(layuiLoadIndex);
                                layer.msg('网络繁忙，请稍后再试');
                            }
                        });
                    }
                );

                return false;
            });

            $('.window_show').click(function(){

                var _this = $(this);

                layuiOpenIndex = layer.open({

                    title: _this.attr('data-title'),
                    type: 2,
                    content : _this.attr('data-href'),
                    icon: 0,
                    maxmin: true,
                    area: ['100%', '100%'],
                    skin: 'my-layer-green',
                    btn: ['关闭'],
                    success : function(_dom, _index){

                        layer.full(_index);
                    }
                });

                return false;
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

            var user = {

                checkForm: function(data){

                    if (data.user_name == '') {

                        layer.msg('请输入用户名');
                        return false;
                    }

                    if (data.user_contact == '') {

                        layer.msg('请输入联系方式');
                        return false;
                    }

                    if (data.user_merchant_name == '') {

                        layer.msg('请输入商户名称');
                        return false;
                    }

                    return true;
                }
            }
        });
    </script>
</body>
</html>