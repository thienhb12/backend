<?php /* Smarty version Smarty-3.1.19, created on 2016-09-06 03:55:46
         compiled from "module\tintuc\templates\backend\tintuc.backend.tpl" */ ?>
<?php /*%%SmartyHeaderCode:984257ccf96d6f0511-18418713%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '33c880514a90aee0a3733a9bc73b599d90d3f81e' => 
    array (
      0 => 'module\\tintuc\\templates\\backend\\tintuc.backend.tpl',
      1 => 1473126440,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '984257ccf96d6f0511-18418713',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57ccf96d7b97c5_70606862',
  'variables' => 
  array (
    'detail' => 0,
    'lang' => 0,
    'list_related' => 0,
    'attr' => 0,
    'related' => 0,
    'category' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ccf96d7b97c5_70606862')) {function content_57ccf96d7b97c5_70606862($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include 'G:\\workspace\\Backend_dqv2\\core\\Smarty\\libs\\plugins\\function.html_options.php';
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
							<label for="sub_title" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('subtitle');?>
</label>
							 <div class="col-lg-10">
								<input type="text" class="form-control" id="sub_title" name="sub_title" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['sub_title'];?>
" />
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('title');?>
</label>
							 <div class="col-lg-10">
								<input type="text" class="form-control" id="title" name="title" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['title'];?>
" required />
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
							<label for="title" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('content');?>
</label>
							 <div class="col-lg-10">
								<textarea class="form-control" id="article" name="content" value="" /><?php echo $_smarty_tpl->tpl_vars['detail']->value['content'];?>
</textarea>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							 <label for="title" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('tagcloud');?>
</label>
							 <div class="col-lg-10">
								<input type='text' class="form-control" name="tagcloud" value="<?php echo $_smarty_tpl->tpl_vars['detail']->value['tagcloud'];?>
" />
								<span class="help-block"><?php echo $_smarty_tpl->getConfigVariable('tagcloud_help');?>
</span>
							</div>
						</div>
					</div>

					<div class="col-sm-offset-2 col-sm-10">
                  		<button type="button" class="btn btn-default attribute" ><?php echo $_smarty_tpl->getConfigVariable('related');?>
</button>
                      	<ul id="related_link" style="width: 400px; margin: 10px 0 0; " class="list-group">
                            <?php  $_smarty_tpl->tpl_vars['related'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['related']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['list_related']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['related']->key => $_smarty_tpl->tpl_vars['related']->value) {
$_smarty_tpl->tpl_vars['related']->_loop = true;
?>
                                    <li id="link_<?php echo $_smarty_tpl->tpl_vars['attr']->value['id'];?>
" class="list-group-item">
                                        <span class="badge" onclick="deleteRelatedLink(this)"><?php echo $_smarty_tpl->getConfigVariable('delete');?>
</span>
                                        <?php echo $_smarty_tpl->tpl_vars['related']->value['title'];?>

                                        <input type="hidden" name="related_id[]" class="clear"  value="<?php echo $_smarty_tpl->tpl_vars['related']->value['id'];?>
" />
                                    </li>
                            <?php } ?>
                      	</ul>
	                </div>
	                
					<div class="col-md-12">
						<div class="form-group">
							 <div class="col-sm-offset-2 col-sm-10">
							  <div class="checkbox">
								<label>
								  <input type="checkbox" name="is_hot" <?php if ($_smarty_tpl->tpl_vars['detail']->value['is_hot']) {?>checked<?php }?>> <?php echo $_smarty_tpl->getConfigVariable('hotnews');?>

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
									  <input type="checkbox" name="is_home" <?php if ($_smarty_tpl->tpl_vars['detail']->value['is_home']) {?>checked<?php }?>> Tin mới
									</label>
								 </div>
							</div>
						</div>
					</div>

					<div class="col-md-12">
						<div class="form-group">
							<label for="inputProvince" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('categories');?>
</label>
							 <div class="col-lg-6">
								<select class="form-control" name="cate_id" id="cate_id" required>
									<option value="-1">----- <?php echo $_smarty_tpl->getConfigVariable('select');?>
 <?php echo $_smarty_tpl->getConfigVariable('categories');?>
 -----</option>
									<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['category']->value,'selected'=>$_smarty_tpl->tpl_vars['detail']->value['cate_id']),$_smarty_tpl);?>

								</select>
							</div>
						</div>
					</div>
					
					<div class="col-md-12">
						<div class="form-group">
							<label for="title" class="col-lg-2 control-label"><?php echo $_smarty_tpl->getConfigVariable('status');?>
</label>
							<div class="col-lg-6">
								<select name="status" class="form-control">
									<option value="1"  <?php if ($_smarty_tpl->tpl_vars['detail']->value['status']=='1') {?>selected<?php }?>><?php echo $_smarty_tpl->getConfigVariable('public_all');?>
</option>
									<option value="0" <?php if ($_smarty_tpl->tpl_vars['detail']->value['status']=='0') {?>selected<?php }?>><?php echo $_smarty_tpl->getConfigVariable('unpublic_all');?>
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

<!-- Modal -->
<div class="modal fade" id="related" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $_smarty_tpl->getConfigVariable('selectAttribute');?>
</h4>
      </div>
      <div class="modal-body">
		<form class="navbar-form navbar-right" role="search">
		  <div class="form-group">
			<input type="text" class="form-control search" placeholder="<?php echo $_smarty_tpl->getConfigVariable('search');?>
">
		  </div>
		  <button type="submit" class="btn btn-default btn-search"><?php echo $_smarty_tpl->getConfigVariable('search');?>
</button>
		</form>
		<div class="clearfix"></div>
		<ul class="list-group" id="list-step"></ul>
		 <div id="pager"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $_smarty_tpl->getConfigVariable('close');?>
</button>
      </div>
    </div>
  </div>
</div><?php }} ?>
