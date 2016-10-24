<?php /* Smarty version Smarty-3.1.19, created on 2016-09-05 06:49:19
         compiled from "module\admin\templates\admin_login.html" */ ?>
<?php /*%%SmartyHeaderCode:1839457ccf94f0fb901-66228038%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e85fb57ad76b318ec7c156ad373f7f3be3229397' => 
    array (
      0 => 'module\\admin\\templates\\admin_login.html',
      1 => 1471923266,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1839457ccf94f0fb901-66228038',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'languages' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57ccf94f2b5126_21867326',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ccf94f2b5126_21867326')) {function content_57ccf94f2b5126_21867326($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include 'G:\\workspace\\Backend_dqv2\\core\\Smarty\\libs\\plugins\\function.html_options.php';
?><?php  $_config = new Smarty_Internal_Config($_SESSION['lang_file'], $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars(null, 'local'); ?>
<!DOCTYPE html>
<html lang="en">

<!-- 
	made by tran hoang thien
	thienhb12@gmail.com
 -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Đăng Nhập Hệ Thống</title>

	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="common/assets/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="common/assets/css/bootstrap.css" rel="stylesheet" type="text/css">
	<link href="common/assets/css/core.css" rel="stylesheet" type="text/css">
	<link href="common/assets/css/components.css" rel="stylesheet" type="text/css">
	<link href="common/assets/css/colors.css" rel="stylesheet" type="text/css">
	<link href="common/assets/css/extras/animate.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script type="text/javascript" src="common/assets/js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="common/assets/js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="common/assets/js/core/libraries/bootstrap.min.js"></script>
	<script type="text/javascript" src="common/assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->


	<!-- Theme JS files -->
	<script type="text/javascript" src="common/assets/js/core/app.js"></script>
	<!-- /theme JS files -->

</head>

<body class="login-container">

	<!-- Main navbar -->
	<div class="navbar navbar-inverse">
		<div class="navbar-header">
			<a class="navbar-brand" href=""><img src="common/assets/images/logo_light.png" alt=""></a>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Content area -->
				<div class="content">

					<!-- Simple login form -->
					<form action="<?php echo @constant('SITE_URL');?>
index.php?mod=admin&amod=sys_login&atask=login&task=check_login&sys=true" method="post" name="frmLogin">
						<div class="panel panel-body login-form animated fadeInDown">
							<div class="text-center">
								<div class="icon-object border-slate-300 text-slate-300"><i class="icon-reading"></i></div>
								<h5 class="content-group"><?php echo $_smarty_tpl->getConfigVariable('login_system');?>
</h5>
							</div>	

							<div class="form-group has-feedback has-feedback-left">
								<input type="text" class="form-control" name="txtUsername"  placeholder="Username">
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<input type="password" name="txtPassword" class="form-control" placeholder="Password">
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
							</div>
							<div class="form-group has-feedback has-feedback-left">
								<div class="form-control-feedback">
									<i class="icon-flag3 text-muted"></i>
								</div>
								<select class="form-control" name="language">
									<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['languages']->value),$_smarty_tpl);?>

								</select>
								
							</div>
							<div class="form-group">
								<button type="submit" class="btn btn-primary btn-block"><?php echo $_smarty_tpl->getConfigVariable('login');?>
 <i class="icon-circle-right2 position-right"></i></button>
							</div>
						</div>
					</form>
					<!-- /simple login form -->


					<!-- Footer -->
					<div class="footer text-muted text-center">
						&copy; 2016. <a href="#">HoangThien</a> by <a href="" target="_blank">Developer</a>
					</div>
					<!-- /footer -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>

</html>
<?php }} ?>
