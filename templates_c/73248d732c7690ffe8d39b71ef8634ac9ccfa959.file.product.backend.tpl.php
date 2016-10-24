<?php /* Smarty version Smarty-3.1.19, created on 2016-09-06 05:43:14
         compiled from "module\product\templates\backend\product.backend.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3058957ccf96459e7d0-33993600%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '73248d732c7690ffe8d39b71ef8634ac9ccfa959' => 
    array (
      0 => 'module\\product\\templates\\backend\\product.backend.tpl',
      1 => 1473133374,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3058957ccf96459e7d0-33993600',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57ccf964777470_22221439',
  'variables' => 
  array (
    'detail' => 0,
    'lang' => 0,
    'category' => 0,
    'category_Price' => 0,
    'category_Mac' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ccf964777470_22221439')) {function content_57ccf964777470_22221439($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include 'G:\\workspace\\Backend_dqv2\\core\\Smarty\\libs\\plugins\\function.html_options.php';
?><?php  $_config = new Smarty_Internal_Config($_SESSION['lang_file'], $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars(null, 'local'); ?>
<div class="content-wrapper">
	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title">
				<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?php if ($_GET['task']=='add') {?><?php echo $_smarty_tpl->getConfigVariable('add');?>
<?php } else { ?><?php echo $_smarty_tpl->getConfigVariable('edit');?>
<?php }?> <?php echo $_smarty_tpl->getConfigVariable('news');?>
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
				<form class="form-horizontal form-admin" method="post" action="index.php?mod=<?php echo $_GET['mod'];?>
&amod=<?php echo $_GET['amod'];?>
&atask=<?php echo $_GET['atask'];?>
&task=<?php echo $_GET['task'];?>
<?php if ($_GET['id']) {?>&id=<?php echo $_GET['id'];?>
<?php }?><?php if ($_GET['ticket_type_id']) {?>&ticket_type_id=<?php echo $_GET['ticket_type_id'];?>
 <?php }?>" role="form" enctype="multipart/form-data">
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
							<label for="title" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('name');?>
</label>
							 <div class="col-lg-10">
								<input type="text" class="form-control" id="title" name="Name" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['Name'];?>
" required />
							</div>
						</div>
					</div>	

					<div class="col-md-12">
						<div class="form-group">
							<label for="sub_title" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('subtitle');?>
</label>
							<div class="col-lg-10">
								<input type="text" class="form-control" id="sub_title" name="Sub_title" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['Sub_title'];?>
" />
							</div>
						</div>
					</div>	
	
					<div class="col-md-12">
					  	<div class="form-group">
							<label for="title" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('keyword');?>
</label>
							 <div class="col-lg-10">
								<input class="form-control" id="txtLead" name="Keyword" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['Keyword'];?>
" />
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('Description');?>
</label>
							 <div class="col-sm-10">
								<textarea class="form-control" id="txtLead" name="Description" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['Description'];?>
" /><?php echo $_smarty_tpl->tpl_vars['detail']->value['Description'];?>
</textarea>
							</div>
						</div>
					</div>


					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('lead');?>
</label>
							 <div class="col-sm-10">
								<textarea class="form-control" id="txtLead" name="Summarise" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['Summarise'];?>
" /><?php echo $_smarty_tpl->tpl_vars['detail']->value['Summarise'];?>
</textarea>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="avatar" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('avatar');?>
</label>
							 <div class="col-lg-5" class="" style="position: relative;">
								<div class="row" id="avatar-thumbnail">
								  <div class="col-xs-6 col-md-6">
									<a href="#" class="thumbnail thumb-1">
				                        <img  data-src="holder.js/210x180?text=Chọn ảnh đại diện" src="<?php echo $_smarty_tpl->tpl_vars['detail']->value['Photo'];?>
" data-position="1">
				                        <input type="hidden" name="Photo" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['Photo'];?>
" />
				                        <button type="button" class="close"><span aria-hidden="true">×</span></button>
									</a>
								  </div>
								</div>
							</div> 
						</div>
					</div>

					<div class="col-md-12">
	 					<div class="form-group">
	 						<label for="inputProvince" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('categories');?>
</label>
							 <div class="col-lg-5">
								<select class="form-control" name="CatID" id="cate_id" required>
									<option value="-1">----- <?php echo $_smarty_tpl->getConfigVariable('select');?>
 <?php echo $_smarty_tpl->getConfigVariable('categories');?>
 -----</option>
									<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['category']->value,'selected'=>$_smarty_tpl->tpl_vars['detail']->value['CatID']),$_smarty_tpl);?>

								</select>
							</div>
	 					</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="inputProvince" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('categories_price');?>
</label>
							 <div class="col-lg-5">
								<select class="form-control" name="PriceID" id="cate_id" required>
									<option value="-1">----- <?php echo $_smarty_tpl->getConfigVariable('select');?>
 <?php echo $_smarty_tpl->getConfigVariable('categories_price');?>
 -----</option>
									<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['category_Price']->value,'selected'=>$_smarty_tpl->tpl_vars['detail']->value['PriceID']),$_smarty_tpl);?>

								</select>
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="form-group">
							<label for="inputProvince" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('trademark');?>
</label>
							 <div class="col-lg-5">
								<select class="form-control" name="MacID" id="cate_id" required>
									<option value="-1">----- <?php echo $_smarty_tpl->getConfigVariable('select');?>
 <?php echo $_smarty_tpl->getConfigVariable('categories_mac');?>
 -----</option>
									<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['category_Mac']->value,'selected'=>$_smarty_tpl->tpl_vars['detail']->value['MacID']),$_smarty_tpl);?>

								</select>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('tagcloud');?>
</label>
							<div class="col-sm-10">
								<input type='text' class="form-control" name="tagcloud" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['tagcloud'];?>
" />
								<span class="help-block"><?php echo $_smarty_tpl->getConfigVariable('tagcloud_help');?>
</span>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('product_price_pay');?>
</label>
							 <div class="col-sm-5">
								<input type="text" class="form-control" id="title" name="Price" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['Price'];?>
" required />
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('product_price_old');?>
</label>
							 <div class="col-sm-5">
								<input type="text" class="form-control" id="title" name="Old_Price" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['Old_Price'];?>
" required />
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('product_code');?>
</label>	
							 <div class="col-sm-5">
								<input type="text" class="form-control" id="title" name="Code" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['Code'];?>
" required />
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('product_price_sale');?>
</label>
							 <div class="col-sm-5">
								<input type="text" class="form-control" id="title" name="giagoc" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['giagoc'];?>
" required />
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('number');?>
</label>
							<div class="col-sm-5">
								<input type="text" class="form-control" id="Soluong" name="Soluong" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['Soluong'];?>
" required />
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-sm-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('content');?>
</label>
							 <div class="col-sm-9">
								<textarea class="form-control" id="article" name="Content" value="" /><?php echo $_smarty_tpl->tpl_vars['detail']->value['Content'];?>
</textarea>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<div class="checkbox">
									<label>
										<input type="checkbox" value="1" name="IsHot" <?php if ($_smarty_tpl->tpl_vars['detail']->value['IsHot']) {?>checked<?php }?>> SP hot nhất
									</label>
								</div>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="IsNew" value="1" <?php if ($_smarty_tpl->tpl_vars['detail']->value['IsNew']) {?>checked<?php }?>> SP mới nhất
									</label>
						  		</div>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="inputCongtac" class="col-sm-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('status');?>
</label>
							<div class="col-sm-3">
								<select name="Status" id="inputCongtac" class="form-control">
									<option value="1"  <?php if ($_smarty_tpl->tpl_vars['detail']->value['Status']=='1') {?>selected<?php }?>><?php echo $_smarty_tpl->getConfigVariable('public_all');?>
</option>
									<option value="0" <?php if ($_smarty_tpl->tpl_vars['detail']->value['Status']=='0') {?>selected<?php }?>><?php echo $_smarty_tpl->getConfigVariable('unpublic_all');?>
</option>
								</select>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
							  <button type="submit" class="btn btn-primary"><?php echo $_smarty_tpl->getConfigVariable('submit');?>
</button>
							  <button type="button" class="btn btn-default back" ><?php echo $_smarty_tpl->getConfigVariable('back');?>
</button>
							</div>
						</div>
					</div>

				</form>
			</div>
		</div>
	</div>
</div>

<?php }} ?>
