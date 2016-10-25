<?php /* Smarty version Smarty-3.1.19, created on 2016-10-25 04:48:22
         compiled from "module\admin\templates\header_admin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2538457ccf95cd04b12-97797264%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6630f869069828cd920230f21337585123f7f5e2' => 
    array (
      0 => 'module\\admin\\templates\\header_admin.tpl',
      1 => 1477359896,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2538457ccf95cd04b12-97797264',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57ccf95cdd4036_70830275',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ccf95cdd4036_70830275')) {function content_57ccf95cdd4036_70830275($_smarty_tpl) {?><?php  $_config = new Smarty_Internal_Config($_SESSION['lang_file'], $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars(null, 'local'); ?>
<html lang="en">
   <!-- Made by Tran Hoang Thien (thienhb12@gmail.com)-->
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $_smarty_tpl->getConfigVariable('page_title');?>
</title>
    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
    <link href="common/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="common/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="common/assets/css/extras/animate.min.css" rel="stylesheet" type="text/css">
    <link href="common/assets/css/core.css" rel="stylesheet" type="text/css">
    <link href="common/assets/css/components.css" rel="stylesheet" type="text/css">
    <link href="common/assets/css/colors.css" rel="stylesheet" type="text/css">
    <link href="common/assets/css/style.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

  </head>  

  <body>
    <div id="loader-wrapper"> 
        <div id="loader"></div>        
        <div class="loader-section section-left"></div>
        <div class="loader-section section-right"></div>
    </div>

    <div class="navbar navbar-inverse">
      <div class="navbar-header">
        <a class="navbar-brand" href="index.php?mod=admin"><img src="common/assets/images/logo_light.png" alt=""></a>

        <ul class="nav navbar-nav visible-xs-block">
          <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
          <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
      </div>

      <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
          <li><a class="sidebar-control sidebar-main-toggle hidden-xs"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>

        <p class="navbar-text">
          <span class="label bg-success">Online</span>
        </p>

        <div class="navbar-right">
          <ul class="nav navbar-nav">
            <li class="dropdown dropdown-user">
              <a class="dropdown-toggle" data-toggle="dropdown">
                <img src="common/assets/images/demo/users/face11.jpg" alt="">
                <span><?php echo $_SESSION['fullname'];?>
</span>
                <i class="caret"></i>
              </a>

              <ul class="dropdown-menu dropdown-menu-right">
                <li><a href="?mod=admin&&amod=system_config&atask=account&sys=true"><i class="icon-user-plus"></i> <?php echo $_smarty_tpl->getConfigVariable('accounts_info');?>
</a></li>
                <li><a href="?mod=admin&&amod=system_config&atask=account&task=change_password&sys=true"><i class="icon-lock2"></i> <?php echo $_smarty_tpl->getConfigVariable('change_password');?>
</a></li>
                <li class="divider"></li>
                <li><a href="?mod=admin&amod=sys_login&atask=login&task=logout&sys=true"><i class="icon-switch2"></i> Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="page-container" style="min-height:218px">
      <div class="page-content">
<?php }} ?>
