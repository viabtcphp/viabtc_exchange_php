<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <title>法币交易<?php echo ' - ' . $_SESSION['SYSCONFIG']['sysconfig_site_name']; ?></title>
        <meta name="keywords" content="" />
        <meta name="description" content="" />
        <link rel='icon' href='/favicon.ico' type='image/x-ico' />

        <link rel="stylesheet" href="<?php echo base_url('static/layui/css'); ?>/layui.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/front'); ?>/style/style.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url('static/front'); ?>/style/tv.css" />

        <!--[if lt IE 9]>
        <script src="<?php echo base_url('static/front'); ?>/js/css3.js"></script>
        <script src="<?php echo base_url('static/front'); ?>/js/html5.js"></script>
        <![endif]-->
    </head>
    <body>

        <?php $this->load->view('front/header'); ?>

        <style type="text/css">
            .body_box{}
        </style>

        <div class="body_box">

            <div class="page_title">法币交易 / 收款设置</div>

            <div class="pay_box">
                
            </div>

        </div>

        <?php $this->load->view('front/footer'); ?>

        <script src="<?php echo base_url('static'); ?>/front/js/bignumber.min.js"></script>
        <script src="<?php echo base_url('static'); ?>/charting_library/charting_library.min.js" async></script>
        <script src="<?php echo base_url('static'); ?>/front/js/tv.js"></script>

        <script type="text/javascript">
            
            //当前栏目
            $('header .left_box .nav_box .nav_item.otc').addClass('active');
        </script>

    </body>
</html>
