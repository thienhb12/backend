<?php /* Smarty version Smarty-3.1.19, created on 2016-09-05 06:49:58
         compiled from "module\tintuc\templates\backend\categories.backend.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1913957ccf976c08df8-92470605%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1a15ac62b429a6713735cf67fdec6843fe60fb24' => 
    array (
      0 => 'module\\tintuc\\templates\\backend\\categories.backend.tpl',
      1 => 1472460178,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1913957ccf976c08df8-92470605',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'url_action' => 0,
    'detail' => 0,
    'lang' => 0,
    'categories' => 0,
    'ordering' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57ccf976d46339_97971398',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ccf976d46339_97971398')) {function content_57ccf976d46339_97971398($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include 'G:\\workspace\\Backend_dqv2\\core\\Smarty\\libs\\plugins\\function.html_options.php';
?><?php  $_config = new Smarty_Internal_Config($_SESSION['lang_file'], $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars(null, 'local'); ?>
<div class="content-wrapper">
	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title">
				<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?php if ($_GET['task']=='add') {?><?php echo $_smarty_tpl->getConfigVariable('add');?>
<?php } else { ?><?php echo $_smarty_tpl->getConfigVariable('edit');?>
<?php }?> <?php echo $_smarty_tpl->getConfigVariable('categories');?>
</span></h4>
			</div>
		<a class="heading-elements-toggle"><i class="icon-more"></i></a></div>
	</div>
	<div class="content">
		<div class="panel panel-flat">
			<div class="panel-heading">
				<div class="heading-elements">
					<ul class="icons-list">
                		<li><a data-action="collapse"></a></li>
                		<li><a data-action="reload"></a></li>
                		<li><a data-action="close"></a></li>
                	</ul>
            	</div>
				<a class="heading-elements-toggle"><i class="icon-more"></i></a>
			</div>
			<div class="panel-body">
				<form class="form-horizontal form-admin" method="post" action="<?php echo $_smarty_tpl->tpl_vars['url_action']->value;?>
" role="form">
					<input type="hidden" name="id" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['id'];?>
">
					<?php if (@constant('MULTI_LANGUAGE')) {?>
						<div class="form-group">
							<label for="lang_id" class="col-sm-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('language');?>
</label>
							 <div class="col-sm-3">
								<select class="form-control" name="lang_id" id="lang_id" required>
									<option value="">----- <?php echo $_smarty_tpl->getConfigVariable('select');?>
 <?php echo $_smarty_tpl->getConfigVariable('language');?>
 -----</option>
									<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['lang']->value,'selected'=>$_smarty_tpl->tpl_vars['detail']->value['lang_id']),$_smarty_tpl);?>

								</select>
							</div>
						</div>
					<?php }?>
					<div class="col-md-12">
						<div class="form-group">
							<label for="parent_id" class="control-label col-lg-2"><?php echo $_smarty_tpl->getConfigVariable('categories');?>
</label>
							<div class="col-lg-10">
								<select class="form-control" name="parent_id" id="parent_id">
									<option value="">----- <?php echo $_smarty_tpl->getConfigVariable('select');?>
 <?php echo $_smarty_tpl->getConfigVariable('categories');?>
 -----</option>
									<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['categories']->value,'selected'=>$_smarty_tpl->tpl_vars['detail']->value['parent_id']),$_smarty_tpl);?>

								</select>
							</div>	
						</div>
					</div>
				
					<div class="col-md-12">
						<div class="form-group">
						    <label for="name" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('name');?>
</label>
						    <div class="col-lg-10">
						      <input type="text" class="form-control" required id="name" placeholder="<?php echo $_smarty_tpl->getConfigVariable('categories');?>
" name="name" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['name'];?>
">
						    </div>
					  	</div>	
				  	</div>

				  	<div class="col-md-12">
					  	<div class="form-group">
							<label for="title" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('title');?>
</label>
							 <div class="col-lg-10">
								<input class="form-control" id="txtLead" name="title" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['title'];?>
" />
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
					  	<div class="form-group">
							<label for="title" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('keyword');?>
</label>
							 <div class="col-lg-10">
								<input class="form-control" id="txtLead" name="keyword" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['keyword'];?>
" />
							</div>
						</div>
					</div>

				  	<div class="col-md-12">
					  	<div class="form-group">
							<label for="title" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('lead');?>
</label>
							 <div class="col-lg-10">
								<textarea class="form-control" id="txtLead" name="lead" value="" /><?php echo $_smarty_tpl->tpl_vars['detail']->value['lead'];?>
</textarea>
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="form-group">
							<label for="txtOrder" class="control-label  col-lg-2"><?php echo $_smarty_tpl->getConfigVariable('ordering');?>
</label>
							<div class="col-lg-10">
								<select class="form-control" name="ordering" id="ordering">
									<option value="">----- <?php echo $_smarty_tpl->getConfigVariable('select');?>
 <?php echo $_smarty_tpl->getConfigVariable('ordering');?>
 -----</option>
									<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['ordering']->value,'selected'=>$_smarty_tpl->tpl_vars['detail']->value['ordering']),$_smarty_tpl);?>

								</select>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('status');?>
</label>
							<div class="col-lg-10">
								<select name="status" class="form-control">
									<option value="1"  <?php if ($_smarty_tpl->tpl_vars['detail']->value['status']=='1') {?>selected<?php }?>><?php echo $_smarty_tpl->getConfigVariable('public_all');?>
</option>
									<option value="0" <?php if ($_smarty_tpl->tpl_vars['detail']->value['status']=='0') {?>selected<?php }?>><?php echo $_smarty_tpl->getConfigVariable('unpublic_all');?>
</option>
								</select>
							</div>
						</div>
					</div>
					<div class="text-right">
						<button type="submit" class="btn btn-primary"><?php echo $_smarty_tpl->getConfigVariable('submit');?>
<i class="icon-arrow-right14 position-right"></i></button>
						<button type="button" class="btn btn-default back" ><?php echo $_smarty_tpl->getConfigVariable('back');?>
</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div><?php }} ?>
