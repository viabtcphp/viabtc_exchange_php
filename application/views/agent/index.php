<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo $_SESSION['SYSCONFIG']['sysconfig_site_name']; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('static/layui/css'); ?>/layui.css">
</head>
<body class="layui-layout-body">

    <style type="text/css">
        
        .tab_refresh{ display: inline-block; width: 18px; height: 18px; line-height: 20px; margin-left: 8px; top: 1px; text-align: center; color: #c2c2c2; transition: all .2s;-webkit-transition: all .2s; font-size: 12px; }
        .tab_refresh:hover{ border-radius: 2px; background-color: #009688; color: #FFF; }

        .layui-nav-tree .layui-nav-item dl dd a{ padding-left: 30px; }

        .layui-layout-admin .layui-logo{ text-align: left; width: auto; padding-left: 30px; }

        .layui-body{ left: 0px; }
    </style>

    <div class="layui-layout layui-layout-admin">
        <div class="layui-header">
            <div class="layui-logo"><?php echo $_SESSION['SYSCONFIG']['sysconfig_site_name']; ?> 代理商系统</div>

            <ul class="layui-nav layui-layout-right" lay-filter="my-nav">
                <li class="layui-nav-item"><a target="_blank" href="/">网站首页</a></li>
            </ul>
        </div>

        <div class="layui-body" style="bottom: 0px;">
            <!--tab标签-->
            <div class="layui-tab layui-tab-card" lay-filter="_body" lay-allowclose="true" style="margin: 0px;">
                <ul class="layui-tab-title" style="display: none;"></ul>
                <div class="layui-tab-content" style="padding: 0px;"></div>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url('static/layui'); ?>/layui.js"></script>
    <script>
      
        //JavaScript代码区域
        layui.use(['element', 'jquery'], function () {

            var element = layui.element;
            var $ = layui.$;

            //初始化data-id
            var linkIndex = 0;
            $('.nav-link-item').each(function(){

                $(this).attr('data-id', ++ linkIndex);
            });

            // 配置tab实践在下面无法获取到菜单元素
            $('.nav-link-item').on('click', function () {
                var dataid = $(this);
                //这时会判断右侧.layui-tab-title属性下的有lay-id属性的li的数目，即已经打开的tab项数目
                if ($(".layui-tab-title li[lay-id]").length <= 0) {
                    //如果比零小，则直接打开新的tab项
                    active.tabAdd(dataid.attr("data-url"), dataid.attr("data-id"), dataid.attr("data-title"));
                } else {
                    //否则判断该tab项是否以及存在
                    var isData = false; //初始化一个标志，为false说明未打开该tab项 为true则说明已有
                    $.each($(".layui-tab-title li[lay-id]"), function () {
                        //如果点击左侧菜单栏所传入的id 在右侧tab项中的lay-id属性可以找到，则说明该tab项已经打开
                        if ($(this).attr("lay-id") == dataid.attr("data-id")) {
                            isData = true;
                        }
                    })
                    if (isData) {
                        $('iframe[data-frameid=' + dataid.attr("data-id") + ']').attr('src', $('iframe[data-frameid=' + dataid.attr("data-id") + ']').attr('src'));
                    } else {
                        //标志为false 新增一个tab项
                        active.tabAdd(dataid.attr("data-url"), dataid.attr("data-id"), dataid.attr("data-title"));
                    }
                }
                //最后不管是否新增tab，最后都转到要打开的选项页面上
                active.tabChange(dataid.attr("data-id"));
            });

            var active = {
                //在这里给active绑定几项事件，后面可通过active调用这些事件
                tabAdd: function (url, id, name) {
                    //新增一个Tab项 传入三个参数，分别对应其标题，tab页面的地址，还有一个规定的id，是标签中data-id的属性值
                    //关于tabAdd的方法所传入的参数可看layui的开发文档中基础方法部分
                    element.tabAdd('_body', {
                        title: name + '<i class="layui-icon layui-icon-refresh tab_refresh" title="刷新" data-id="' + id + '"></i>',
                        content: '<iframe data-frameid="' + id + '" scrolling="auto" frameborder="0" src="' + url + '" style="display:block; width:100%; height:100%;"></iframe>',
                        id: id //规定好的id
                    });
                    FrameWH();  //计算ifram层的大小
                },
                tabChange: function (id) {
                    //切换到指定Tab项
                    element.tabChange('_body', id); //根据传入的id传入到指定的tab项
                },
                tabDelete: function (id) {
                    element.tabDelete("_body", id);//删除
                }
            };

            function FrameWH() {
                var h = $(window).height();
                $("iframe").css("height",h - 103 + "px");
            };

            $(window).resize(function(){

                FrameWH();
            });

            //刷新标签页
            $(document).on('click', '.tab_refresh', function(){

                $('iframe[data-frameid=' + $(this).attr('data-id') + ']').attr('src', $('iframe[data-frameid=' + $(this).attr('data-id') + ']').attr('src'));
            });

            //创建初始化tab页
            active.tabAdd('/agent/user/user', 2, '欢迎');
            active.tabChange(2);
        });
    </script>
</body>
</html>