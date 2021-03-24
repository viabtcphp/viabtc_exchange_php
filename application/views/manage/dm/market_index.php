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
            合约交易 > 市场管理
        </div>

        <style type="text/css">
            
            .myuploadbox{ width: 300px; }
            .imgpreview{ height: 36px; margin-left: 10px; position: relative; display: none; border: rgb(230, 230, 230) solid 1px; cursor: pointer; }
            .layui-table .imgpreview{ height: 30px; }

            .layui-form-switch{ margin-top: 0px; }
        </style>

        <div class="mainbox layui-form">

            <?php if($marketList && count($marketList)){ $i = 1; foreach($marketList as $marketMoneySymbol => $marketListItem){ ?>
                <table class="layui-table" >
                    <colgroup>
                        <col>
                        <col>
                        <col style="width: 160px;">
                        <col style="width: 160px;">
                    </colgroup>
                    <thead>
                        <tr>
                            <th>交易对</th>
                            <th>最低交易量</th>
                            <th>状态</th>
                            <th>操作</th>
                        </tr> 
                    </thead>
                    <tbody>

                        <?php if(count($marketListItem)){ foreach($marketListItem as $market){ ?>
                        <tr style="position: relative;">
                            <td><?php echo $market['market_stock_symbol'] . ' / ' . $market['market_money_symbol']; ?></td>
                            <td><?php echo $market['market_dm_min_amount']; ?></td>
                            <td style="position: relative;">
                                <a class="layui-btn <?php echo $market['market_dm_status'] == '1' ? '' : 'layui-btn-danger'; ?> layui-btn-xs"><?php echo $market['market_dm_status'] == '1' ? '开启' : '关闭'; ?></a> 
                            </td>
                            <td style="position: relative;">
                                <button class="layui-btn layui-btn-warm layui-btn-xs editbtn" data-id="<?php echo $market['market_id']; ?>" data-title="编辑市场 - <?php echo $market['market_stock_symbol'] . ' / ' . $market['market_money_symbol']; ?>">编辑市场</button>
                            </td>
                        </tr>
                        <?php }} ?>
                    </tbody>
                </table>

            <?php $i ++; }} ?>
        </div>

        <!-- 编辑框 -->
        <div class="editbox displaynone" id="editbox">
            
            <div class="layui-form layui-form-pane">

                <input type="hidden" name="market_id" value="0">

                <div class="layui-form-item">
                    <label class="layui-form-label">交易名称</label>
                    <div class="layui-input-block">
                        <input type="text" name="market_symbol" placeholder="请输入交易名称" class="layui-input" value="" readonly>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">最低交易</label>
                    <div class="layui-input-block">
                        <input type="text" name="market_dm_min_amount" placeholder="请输入最低交易" class="layui-input" value="0" onkeyup="format_input_num(this);">
                    </div>
                </div>

                <div class="layui-form-item" pane>
                    <label class="layui-form-label">交易状态</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="market_dm_status" lay-skin="switch" checked lay-text="开启|关闭" value="1">
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


            //编辑市场
            $('.editbtn').click(function(){

                var _this = $(this);

                var _marketId = _this.attr('data-id');

                layuiLoadIndex = layer.load();

                //获取数据
                $.ajax({

                    url: '/manage/dm/market/one',
                    type: 'post',
                    data: {

                        'market_id' : _marketId
                    },
                    dataType: 'json',
                    success: function (data) {

                        if (data.status) {
                            
                            //更新表单

                            $('#editbox [name=market_id]').val(data.market.market_id);
                            $('#editbox [name=market_symbol]').val(data.market.market_symbol);
                            $('#editbox [name=market_dm_min_amount]').val(data.market.market_dm_min_amount);

                            //swtich
                            if (data.market.market_dm_status == '1') {

                                $('#editbox [name=market_dm_status]').val(1).prop('checked', true);
                            }else{

                                $('#editbox [name=market_dm_status]').val(0).prop('checked', false);
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

                                        'market_id' : $('#editbox [name=market_id]').val(),
                                        'market_dm_min_amount' : $('#editbox [name=market_dm_min_amount]').val(),
                                        'market_dm_status' : $('#editbox [name=market_dm_status]').val()
                                    };

                                    if (market.checkForm(data)) {

                                        layuiLoadIndex = layer.load();

                                        $.ajax({
                                            url: '/manage/dm/market/edit',
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
                                    
                                    window.reload();
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


            var market = {

                checkForm: function(data){

                    if (data.market_stock_coin == '0') {

                        layer.msg('请选择交易币种');
                        return false;
                    }

                    if (data.market_money_coin == '0') {

                        layer.msg('请选择结算币种');
                        return false;
                    }

                    if (data.market_decimal == '') {

                        layer.msg('请填写小数位数');
                        return false;
                    }

                    if (data.market_min_amount == '') {

                        layer.msg('请填写最小发布量');
                        return false;
                    }

                    if (data.market_taker_fee == '') {

                        layer.msg('请填写Taker费率');
                        return false;
                    }

                    if (data.market_maker_fee == '') {

                        layer.msg('请填写Maker费率');
                        return false;
                    }

                    return true;
                },

                formRender: function(){

                    

                    form.render();
                }
            }
        });
    </script>
</body>
</html>