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
            法币交易 > 交易币种
        </div>

        <style type="text/css">
            
            .myuploadbox{ width: 300px; }
            .imgpreview{ height: 36px; margin-left: 10px; position: relative; display: none; cursor: pointer; }
            .layui-table .imgpreview{ height: 30px; }
        </style>

        <div class="mainbox">
            
            <table class="layui-table" >
                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col style="width: 90px;">
                </colgroup>
                <thead>
                    <tr>
                        <th>币种</th>
                        <th>法币排序</th>
                        <th>法币交易</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>

                    <?php if(count($coinList)){ foreach($coinList as $coin){ ?>
                    <tr style="position: relative;">
                        <td><?php echo $coin['coin_symbol']; ?></td>
                        
                        <td><?php echo $coin['coin_otc_index']; ?></td>
                        <td>
                            <a class="layui-btn <?php echo $coin['coin_otc'] == '1' ? '' : 'layui-btn-danger'; ?> layui-btn-xs"><?php echo $coin['coin_otc'] == '1' ? '开启' : '关闭'; ?></a> 
                        </td>
                        <td style="position: relative;">
                            <button class="layui-btn layui-btn-warm layui-btn-xs editbtn" data-id="<?php echo $coin['coin_id']; ?>" data-title="编辑币种 - <?php echo $coin['coin_symbol']; ?>">编辑币种</button>
                        </td>
                    </tr>
                    <?php }} ?>
                </tbody>
            </table>
        </div>

        <!-- 编辑框 -->
        <div class="editbox displaynone" id="editbox">
            
            <div class="layui-form layui-form-pane">

                <input type="hidden" name="coin_id" value="0">

                <div class="layui-form-item">
                    <label class="layui-form-label">货币标识</label>
                    <div class="layui-input-block">
                        <input type="text" name="coin_symbol" placeholder="请输入货币标识" class="layui-input" readonly>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">OTC排序</label>
                    <div class="layui-input-block">
                        <input type="text" name="coin_otc_index" placeholder="请输入排序数字，越大越靠前" class="layui-input" value="0" oninput="value=value.replace(/[^\d]/g,''); value=(value==''?0:parseInt(value));">
                    </div>
                </div>

                <div class="layui-form-item" pane>
                    <label class="layui-form-label">OTC交易</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="coin_otc" lay-skin="switch" checked lay-text="开启|关闭" value="1">
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

            //兼容layui的radio
            form.on('radio', function(data){

                $(data.elem).prop('checked', true).siblings('[name=' + $(data.elem).attr('name') + ']').prop('checked', false);
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

            


            //编辑币种
            $('.editbtn').click(function(){

                var _this = $(this);

                var _coinId = _this.attr('data-id');

                layuiLoadIndex = layer.load();

                //获取数据
                $.ajax({

                    url: '/manage/sys/coin/one',
                    type: 'post',
                    data: {

                        'coin_id' : _coinId
                    },
                    dataType: 'json',
                    success: function (data) {

                        if (data.status) {
                            
                            //更新表单

                            $('#editbox [name=coin_id]').val(data.coin.coin_id);
                            $('#editbox [name=coin_symbol]').val(data.coin.coin_symbol);
                            $('#editbox [name=coin_otc_index]').val(data.coin.coin_otc_index);
                            $('#editbox [name=coin_otc]').val(data.coin.coin_otc);

                            if (data.coin.coin_otc == '1') {

                                $('#editbox [name=coin_otc]').val(1).prop('checked', true);
                            }else{

                                $('#editbox [name=coin_otc]').val(0).prop('checked', false);
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

                                    var data = {

                                        'coin_id' : $('#editbox [name=coin_id]').val(),
                                        'coin_symbol' : $('#editbox [name=coin_symbol]').val(),
                                        'coin_otc_index' : $('#editbox [name=coin_otc_index]').val(),
                                        'coin_otc' : $('#editbox [name=coin_otc]').val()
                                    };

                                    layuiLoadIndex = layer.load();

                                    $.ajax({
                                        url: '/manage/otc/coin/edit',
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
                                },
                                end: function(){

                                    layer.close(layuiOpenIndex);
                                    
                                    coin.formRender();
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
        });
    </script>
</body>
</html>