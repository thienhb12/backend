<?php /* Smarty version Smarty-3.1.19, created on 2016-09-05 09:10:23
         compiled from "module\admin\templates\account_admin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:550357cd1a5fb11633-18369373%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '08b2510976219071a19c0e456409416f57caaf09' => 
    array (
      0 => 'module\\admin\\templates\\account_admin.tpl',
      1 => 1462648502,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '550357cd1a5fb11633-18369373',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'action_url' => 0,
    'detail' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57cd1a601ea7d8_29593261',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57cd1a601ea7d8_29593261')) {function content_57cd1a601ea7d8_29593261($_smarty_tpl) {?><?php  $_config = new Smarty_Internal_Config($_SESSION['lang_file'], $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars(null, 'local'); ?>
<ul class="breadcrumb">
	<li><a href="index.php?mod=admin"><?php echo $_smarty_tpl->getConfigVariable('home');?>
</a></li>
	<li><?php if ($_GET['task']=='add') {?><?php echo $_smarty_tpl->getConfigVariable('add');?>
<?php } else { ?><?php echo $_smarty_tpl->getConfigVariable('edit');?>
<?php }?> <?php echo $_smarty_tpl->getConfigVariable('accounts');?>
</li>
</ul>
<form class="form-horizontal" method="post" action="<?php echo $_smarty_tpl->tpl_vars['action_url']->value;?>
" role="form" data-toggle="validator">
	<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['id'];?>
">
	<div class="form-group">
		<label for="inputProvince" class="col-sm-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('username');?>
</label>
		<div class="col-sm-5">
		  <input type="text" pattern="<?php echo $_smarty_tpl->getConfigVariable('pattern_username');?>
" class="form-control" required id="username" readonly placeholder="<?php echo $_smarty_tpl->getConfigVariable('username');?>
" name="username" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['username'];?>
">
		  <span class="help-block with-errors"><?php echo $_smarty_tpl->getConfigVariable('required_username');?>
</span>
		</div>
	</div>
    <div class="form-group">
	<label for="type" class="col-sm-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('fullname');?>
</label>
	<div class="col-sm-5">
		 <input type="text" class="form-control" required id="fullname" placeholder="" name="fullname" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['fullname'];?>
">
		 <span class="help-block with-errors"></span>
	</div>
  </div>
  <div class="form-group">
	<label for="type" class="col-sm-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('email');?>
</label>
	<div class="col-sm-5">
		 <input type="email" class="form-control" id="email" required  placeholder="" name="email" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['email'];?>
">
		 <span class="help-block with-errors"></span>
	</div>
  </div>
  <div class="form-group">
	<label for="inputCongtac" class="col-sm-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('status');?>
</label>
	<div class="col-sm-3">
		<select name="status" readonly id="inputCongtac" class="form-control">
			<option value="1"  <?php if ($_smarty_tpl->tpl_vars['detail']->value['Status']=='1') {?>selected<?php }?>><?php echo $_smarty_tpl->getConfigVariable('public_all');?>
</option>
			<option value="0" <?php if ($_smarty_tpl->tpl_vars['detail']->value['Status']=='0') {?>selected<?php }?>><?php echo $_smarty_tpl->getConfigVariable('unpublic_all');?>
</option>
		</select>
	</div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary"><?php echo $_smarty_tpl->getConfigVariable('submit');?>
</button>
      <button type="button" class="btn btn-default back"><?php echo $_smarty_tpl->getConfigVariable('back');?>
</button>
    </div>
  </div>
</form><?php }} ?>
