<?php /* Smarty version Smarty-3.1.19, created on 2016-10-25 05:17:38
         compiled from "G:\workspace\Backend_dqv2\core\datagrid\templates\datagrid.html" */ ?>
<?php /*%%SmartyHeaderCode:251657ccf95f917ab7-84509068%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6287a442299e40d16e7819ae6e50e5edc28c17c4' => 
    array (
      0 => 'G:\\workspace\\Backend_dqv2\\core\\datagrid\\templates\\datagrid.html',
      1 => 1477359860,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '251657ccf95f917ab7-84509068',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57ccf95fcc0e92_17744267',
  'variables' => 
  array (
    'menu' => 0,
    'path' => 0,
    'root_path' => 0,
    'sort_by' => 0,
    'sort_value' => 0,
    'arr_filter' => 0,
    'row' => 0,
    'arr_action' => 0,
    'has_action' => 0,
    'has_action_add' => 0,
    'submit_url' => 0,
    'arr_cols' => 0,
    'arr_value' => 0,
    'index_start' => 0,
    'item' => 0,
    'pkey' => 0,
    'ref' => 0,
    'cor' => 0,
    'key' => 0,
    'case' => 0,
    'action_width' => 0,
    'number_record' => 0,
    'per_page' => 0,
    'page' => 0,
    'number_page' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ccf95fcc0e92_17744267')) {function content_57ccf95fcc0e92_17744267($_smarty_tpl) {?><?php if (!is_callable('smarty_function_html_options')) include 'G:\\workspace\\Backend_dqv2\\core\\Smarty\\libs\\plugins\\function.html_options.php';
?><?php  $_config = new Smarty_Internal_Config($_SESSION['lang_file'], $_smarty_tpl->smarty, $_smarty_tpl);$_config->loadConfigVars(null, 'local'); ?>
<?php echo $_smarty_tpl->tpl_vars['menu']->value;?>

<script type="text/javascript" src="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
/datagrid.js"></script>
<div class="content-wrapper">
	<div class="page-header page-header-default">
		<div class="page-header-content">
			<div class="page-title">
				<h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"><?php echo $_smarty_tpl->tpl_vars['root_path']->value;?>
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
			<form name="for_datagrid" action="" method="get">
				<input type="hidden" name="mod" value="<?php echo $_GET['mod'];?>
">
				<input type="hidden" name="amod" value="<?php echo $_GET['amod'];?>
">
				<input type="hidden" name="atask" value="<?php echo $_GET['atask'];?>
">
				<input type="hidden" name="sys" value="<?php echo $_GET['sys'];?>
" />
				<input type="hidden" name="task" value="<?php echo $_GET['task'];?>
" />
				<input type="hidden" name="sort_by" value="<?php echo $_smarty_tpl->tpl_vars['sort_by']->value;?>
">
				<input type="hidden" name="sort_value" value="<?php echo $_smarty_tpl->tpl_vars['sort_value']->value;?>
">
				<input type="hidden" name="id" value="<?php echo $_GET['id'];?>
">
				<div id="DataTables_Table_3_wrapper" class="dataTables_wrapper no-footer">
					<div class="datatable-header">
						<div id="DataTables_Table_3_filter" class="dataTables_filter">
							<?php if ($_smarty_tpl->tpl_vars['arr_filter']->value!='') {?>
								<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['arr_filter']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['filter']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['filter']['index']++;
?>
									
									<?php if ($_smarty_tpl->tpl_vars['row']->value['type']=='date') {?>
									<label>
										<input type="text" class="form-control"  name="<?php if ($_smarty_tpl->tpl_vars['row']->value['name']!='') {?><?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['row']->value['field'];?>
<?php }?>" id="<?php if ($_smarty_tpl->tpl_vars['row']->value['name']!='') {?><?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['row']->value['field'];?>
<?php }?>" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['selected'];?>
" <?php if ($_smarty_tpl->tpl_vars['row']->value['style']!='') {?> style="<?php echo $_smarty_tpl->tpl_vars['row']->value['style'];?>
"<?php }?>  placeholder="<?php echo $_smarty_tpl->tpl_vars['row']->value['display'];?>
" />
										<img src="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
/calendar/images/lich.gif" id="img_<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['filter']['index'];?>
" onclick="displayCalendar(document.getElementById(<?php if ($_smarty_tpl->tpl_vars['row']->value['name']!='') {?>'<?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
'<?php } else { ?>'<?php echo $_smarty_tpl->tpl_vars['row']->value['field'];?>
'<?php }?>),'yyyy-mm-dd',this)" />
									</label>
									<?php } elseif ($_smarty_tpl->tpl_vars['row']->value['type']=='text') {?>
										<label>
											<span>Filter:</span> 
											<input class="form-control"  type="text" name="<?php if ($_smarty_tpl->tpl_vars['row']->value['name']!='') {?><?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['row']->value['field'];?>
<?php }?>" value="<?php echo $_smarty_tpl->tpl_vars['row']->value['selected'];?>
" placeholder="<?php echo $_smarty_tpl->tpl_vars['row']->value['display'];?>
" style="<?php echo $_smarty_tpl->tpl_vars['row']->value['style'];?>
" />
										</label>
									<?php } else { ?>
										<label>
											<select class="form-control" name="<?php if ($_smarty_tpl->tpl_vars['row']->value['name']!='') {?><?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['row']->value['field'];?>
<?php }?>" id="<?php if ($_smarty_tpl->tpl_vars['row']->value['name']!='') {?><?php echo $_smarty_tpl->tpl_vars['row']->value['name'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['row']->value['field'];?>
<?php }?>" <?php if ($_smarty_tpl->tpl_vars['row']->value['style']!='') {?> style="<?php echo $_smarty_tpl->tpl_vars['row']->value['style'];?>
"<?php }?>  onchange="<?php echo $_smarty_tpl->tpl_vars['row']->value['onchange'];?>
" >
												<?php if ($_smarty_tpl->tpl_vars['row']->value['option_string']=='') {?>
													<option value="">---<?php echo $_smarty_tpl->getConfigVariable('all');?>
 <?php echo $_smarty_tpl->tpl_vars['row']->value['display'];?>
 ---</option>
													<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->tpl_vars['row']->value['options'],'selected'=>$_smarty_tpl->tpl_vars['row']->value['selected']),$_smarty_tpl);?>

												<?php } else { ?>
													<?php echo $_smarty_tpl->tpl_vars['row']->value['option_string'];?>

												<?php }?>
											</select>
										</label>
									<?php }?>
									
								<?php } ?>
									<label>
										<input type="submit" name="bt_fillter" value="<?php echo $_smarty_tpl->getConfigVariable('filter');?>
" style="width:30px"  class="btn btn-default "/>
									</label>
							<?php }?>
						</div>
					<!-- 	<div id="DataTables_Table_3_filter" class="dataTables_filter">
						<label>
							<span>Filter:</span> 
							<input type="search" class="" name="filter_title" value="<?php echo $_smarty_tpl->getConfigVariable('filter');?>
" placeholder="Type to filter..." aria-controls="DataTables_Table_3">
						</label>
					</div> -->
						<div class="dataTables_length" id="DataTables_Table_2_length">
							<?php if ($_smarty_tpl->tpl_vars['arr_action']->value&&$_smarty_tpl->tpl_vars['has_action']->value&&$_smarty_tpl->tpl_vars['has_action_add']->value) {?>
								<a  href="javascript:<?php if ($_smarty_tpl->tpl_vars['arr_action']->value[0]['action']!='') {?><?php echo $_smarty_tpl->tpl_vars['arr_action']->value[0]['action'];?>
<?php } else { ?>redirect_url('<?php echo $_smarty_tpl->tpl_vars['submit_url']->value;?>
&task=add');<?php }?>" class="btn btn-success"><i class="icon-plus2 position-right"></i> <?php echo $_smarty_tpl->getConfigVariable('addnew');?>
 </a>
							<?php }?>
						</div>
					</div>

					<div class="datatable-scroll">
						<table class="table datatable-show-all dataTable no-footer" id="DataTables_Table_0">
							<thead>
								<tr role="row">
									<th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="First Name: activate to sort column descending">STT
									</th>
									<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['arr_cols']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->_loop = true;
?>
										<?php if ($_smarty_tpl->tpl_vars['row']->value['visible']==''||$_smarty_tpl->tpl_vars['row']->value['visible']!='hidden') {?>
											<th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Last Name: activate to sort column ascending"><?php echo $_smarty_tpl->tpl_vars['row']->value['display'];?>

												<?php if ($_smarty_tpl->tpl_vars['row']->value['sortable']) {?>
												<i class="icon-arrow-up12" onclick="for_datagrid.sort_by.value='<?php echo $_smarty_tpl->tpl_vars['row']->value['field'];?>
'; for_datagrid.sort_value.value='asc'; for_datagrid.submit();" style="cursor:pointer"></i>
												
												<?php }?>
												
												<?php if ($_smarty_tpl->tpl_vars['row']->value['sortable']) {?>
												<i class="icon-arrow-down12 " onclick="for_datagrid.sort_by.value='<?php echo $_smarty_tpl->tpl_vars['row']->value['field'];?>
'; for_datagrid.sort_value.value='desc'; for_datagrid.submit();" style="cursor:pointer"></i>
											
												<?php }?>
												<?php if ($_smarty_tpl->tpl_vars['row']->value['datatype']=='order'&&$_smarty_tpl->tpl_vars['arr_value']->value) {?><img src="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
/images/save.png" style="cursor:pointer" title="Lưu thứ tự" border="0" onclick="save_order();"/><?php }?>
												<?php if ($_smarty_tpl->tpl_vars['row']->value['datatype']=='quantity'&&$_smarty_tpl->tpl_vars['arr_value']->value) {?><img src="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
/images/save.png" style="cursor:pointer" title="Lưu số lượng" border="0" onclick="save_quantity();"/><?php }?>
											</th>
										<?php }?>
									<?php } ?>
									<th class="text-center sorting_disabled" rowspan="1" colspan="1" aria-label="Actions" style="width: 100px;">Actions</th>
								</tr>
							</thead>
							<tbody>	
								<?php  $_smarty_tpl->tpl_vars['row'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['row']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['arr_value']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['row']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['row']->iteration=0;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['value']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['row']->key => $_smarty_tpl->tpl_vars['row']->value) {
$_smarty_tpl->tpl_vars['row']->_loop = true;
 $_smarty_tpl->tpl_vars['row']->iteration++;
 $_smarty_tpl->tpl_vars['row']->last = $_smarty_tpl->tpl_vars['row']->iteration === $_smarty_tpl->tpl_vars['row']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['value']['index']++;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['value']['last'] = $_smarty_tpl->tpl_vars['row']->last;
?>
									<tr role="row" class="odd">
										<td class="sorting_1"><?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['value']['index']+$_smarty_tpl->tpl_vars['index_start']->value;?>
</td>
										<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['arr_cols']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['item']->key;
?>
											<?php if ($_smarty_tpl->tpl_vars['item']->value['visible']==''||$_smarty_tpl->tpl_vars['item']->value['visible']!='hidden') {?>
												<td>
													<?php if ($_smarty_tpl->tpl_vars['item']->value['link']!='') {?> <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['link'];?>
&<?php echo $_smarty_tpl->tpl_vars['item']->value['field'];?>
=<?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']];?>
&id=<?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['pkey']->value];?>
"><?php }?>
													<?php if ($_smarty_tpl->tpl_vars['item']->value['value_cores']!='') {?>
														<?php  $_smarty_tpl->tpl_vars['cor'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cor']->_loop = false;
 $_smarty_tpl->tpl_vars['ref'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['item']->value['value_cores']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cor']->key => $_smarty_tpl->tpl_vars['cor']->value) {
$_smarty_tpl->tpl_vars['cor']->_loop = true;
 $_smarty_tpl->tpl_vars['ref']->value = $_smarty_tpl->tpl_vars['cor']->key;
?>
															<?php if ($_smarty_tpl->tpl_vars['ref']->value==$_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']]) {?>
																<?php echo $_smarty_tpl->tpl_vars['cor']->value;?>

															<?php }?>
														<?php } ?>
													<?php } else { ?>
														<?php if ($_smarty_tpl->tpl_vars['item']->value['datatype']=='img') {?>
															<?php if ($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']]!='') {?>
															<a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['img_path'];?>
<?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']];?>
" onclick="return hs.expand(this)">
																<img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['img_path'];?>
<?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']];?>
" width="40" border="0" title="<?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['tooltip']];?>
"/>
															</a>
															<?php } else { ?>
															&nbsp;
															<?php }?>
														<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['datatype']=='boolean') {?>
															<?php if ($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']]=='1'||$_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']]=='t'||$_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']]=='active') {?>
																<img src="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
/images/active.gif" border="0">
															<?php } else { ?>
																<img src="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
/images/deactive.gif" border="0">
															<?php }?>
														<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['datatype']=='publish') {?>
															<?php if ($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']]=='1'||$_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']]=='t'||$_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']]=='active') {?>
																<span class="label label-success"><?php echo $_smarty_tpl->getConfigVariable('publish_item');?>
</span>
															<?php } else { ?>
																<span class="label label-danger"><?php echo $_smarty_tpl->getConfigVariable('unpublish_item');?>
</span>
															<?php }?>
														<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['datatype']=='value_set') {?>
															<?php  $_smarty_tpl->tpl_vars['case'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['case']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['item']->value['case']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['case']->key => $_smarty_tpl->tpl_vars['case']->value) {
$_smarty_tpl->tpl_vars['case']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['case']->key;
?>
																<?php if ($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']]==$_smarty_tpl->tpl_vars['key']->value) {?>
																	<?php echo $_smarty_tpl->tpl_vars['case']->value;?>

																<?php }?>
															<?php } ?>
														<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['datatype']=='number') {?>
															<?php echo number_format($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']],0,",",".");?>

														<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['datatype']=='float') {?>
															<?php echo number_format($_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']],3,".",",");?>

														<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['datatype']=='order') {?>
															<input type="text" name="<?php echo $_smarty_tpl->tpl_vars['item']->value['field'];?>
[<?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['pkey']->value];?>
]" value="<?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']];?>
" class="text" />
														<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['datatype']=='quantity') {?>
															<input type="text" name="<?php echo $_smarty_tpl->tpl_vars['item']->value['field'];?>
[<?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['pkey']->value];?>
]" value="<?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']];?>
" class="text" />
														<?php } elseif ($_smarty_tpl->tpl_vars['item']->value['editable']==true) {?>
															 <input style="text-align:center" size="1" type="text" name="<?php echo $_smarty_tpl->tpl_vars['item']->value['field'];?>
[<?php echo $_smarty_tpl->tpl_vars['row']->value['id'];?>
]" value="<?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']];?>
">
														<?php } else { ?>
															<?php echo (($tmp = @$_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']])===null||$tmp==='' ? '&nbsp;' : $tmp);?>

														<?php }?>
													<?php }?>
													<?php if ($_smarty_tpl->tpl_vars['item']->value['link']!='') {?></a><?php }?>

												</td>
											<?php }?>
										<?php } ?>
										<?php if ($_smarty_tpl->tpl_vars['has_action']->value) {?>
											<td class="text-center">
												<ul class="icons-list">
													<li class="dropdown">
														<a href="#" class="dropdown-toggle" data-toggle="dropdown">
															<i class="icon-menu9"></i>
														</a>
														
														<ul class="dropdown-menu dropdown-menu-right">
															<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['arr_action']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
																<?php if ($_smarty_tpl->tpl_vars['item']->value['task']!='add'&&is_array($_smarty_tpl->tpl_vars['item']->value)) {?>
																	<?php if (($_smarty_tpl->tpl_vars['item']->value['display']=='')||($_smarty_tpl->tpl_vars['item']->value['operation']=='equal'&&$_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']]==$_smarty_tpl->tpl_vars['item']->value['value'])||($_smarty_tpl->tpl_vars['item']->value['operation']=='notequal'&&$_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field']]!=$_smarty_tpl->tpl_vars['item']->value['value'])) {?>
																		<li>
																			<a href="#" onclick="
																			<?php if ($_smarty_tpl->tpl_vars['item']->value['confirm']||$_smarty_tpl->tpl_vars['item']->value['action']) {?>javascript:
																				<?php if ($_smarty_tpl->tpl_vars['item']->value['action']!='') {?> <?php echo $_smarty_tpl->tpl_vars['item']->value['action'];?>
('<?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['pkey']->value];?>
')
																				<?php } else { ?>
																					<?php if ($_smarty_tpl->tpl_vars['item']->value['field_cascade']!=''&&$_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field_cascade']]!=''&&$_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['item']->value['field_cascade']]!=0) {?>
																						confirm_redirect('<?php echo $_smarty_tpl->tpl_vars['item']->value['confirm_cascade'];?>
', '<?php echo $_smarty_tpl->tpl_vars['submit_url']->value;?>
&task=<?php echo $_smarty_tpl->tpl_vars['item']->value['task'];?>
&id=<?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['pkey']->value];?>
')
																					<?php } else { ?>
																						 confirm_redirect('<?php echo $_smarty_tpl->tpl_vars['item']->value['confirm'];?>
', '<?php echo $_smarty_tpl->tpl_vars['submit_url']->value;?>
&task=<?php echo $_smarty_tpl->tpl_vars['item']->value['task'];?>
&id=<?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['pkey']->value];?>
')
																					<?php }?>
																				<?php }?>
																			<?php } else { ?> javascript:redirect_url('<?php echo $_smarty_tpl->tpl_vars['submit_url']->value;?>
&task=<?php echo $_smarty_tpl->tpl_vars['item']->value['task'];?>
&id=<?php echo $_smarty_tpl->tpl_vars['row']->value[$_smarty_tpl->tpl_vars['pkey']->value];?>
') <?php }?>" title="<?php echo $_smarty_tpl->tpl_vars['item']->value['tooltip'];?>
">
																				<?php if ($_smarty_tpl->tpl_vars['item']->value['icon']) {?><img src="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
/images/<?php echo $_smarty_tpl->tpl_vars['item']->value['icon'];?>
" border="0" style="cursor:pointer;" /><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
<?php } else { ?><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
<?php }?>
																			</a>
																		</li>
																	<?php }?>
																<?php }?>
															<?php } ?>
														</ul>
													</li>
												</ul>
											</td>
										<?php } else { ?>
											<td class="td_action <?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['value']['last']) {?> td_border<?php } else { ?> td_border_left<?php }?>" style="width:<?php echo $_smarty_tpl->tpl_vars['action_width']->value;?>
px;"></td>
										<?php }?>
									</tr>
								<?php } ?>
							</tbody>		
						</table>
					</div>

					<div class="datatable-footer">
					<div class="dataTables_info" id="DataTables_Table_2_info" role="status" aria-live="polite">
						<?php echo $_smarty_tpl->getConfigVariable('total_record');?>
 <?php echo $_smarty_tpl->tpl_vars['number_record']->value;?>

					</div>
					<div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_2_paginate">
						<?php echo $_smarty_tpl->getConfigVariable('number_record');?>
/<?php echo $_smarty_tpl->getConfigVariable('page');?>
: <input  name="per_page" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['per_page']->value)===null||$tmp==='' ? 10 : $tmp);?>
" style="width: 40px; text-align:center;" onKeyPress="if(event.keyCode==13) for_datagrid.submit();">&nbsp;&nbsp;
						<?php if ($_smarty_tpl->tpl_vars['page']->value>1) {?>
						<span onClick="for_datagrid.page.value=1; for_datagrid.submit();" style="cursor: pointer; padding-top:3px;">
							<i class="icon-first"></i>
						</span>
						<span onClick="for_datagrid.page.value=<?php echo $_smarty_tpl->tpl_vars['page']->value-1;?>
; for_datagrid.submit();" style="cursor: pointer">
							<i class="icon-arrow-left7"></i>
						</span>
						<?php } else { ?>
						<span>
							<i class="icon-first"  style="color: #CCC"></i>
							<i class="icon-arrow-left7"   style="color: #CCC"></i>
						</span>
						<?php }?><img src="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
/images/grid-split.gif" border="0" />
						<?php echo $_smarty_tpl->getConfigVariable('page');?>
: <input name="page" value="<?php echo (($tmp = @$_smarty_tpl->tpl_vars['page']->value)===null||$tmp==='' ? 1 : $tmp);?>
" style="width: 20px; text-align:center;" onKeyPress="if(event.keyCode==13) for_datagrid.submit();" />
						<?php echo $_smarty_tpl->getConfigVariable('ofpage');?>
 <strong><?php echo $_smarty_tpl->tpl_vars['number_page']->value;?>
</strong>&nbsp;&nbsp;<img src="<?php echo $_smarty_tpl->tpl_vars['path']->value;?>
/images/grid-split.gif" border="0" />
						<?php if ($_smarty_tpl->tpl_vars['page']->value<$_smarty_tpl->tpl_vars['number_page']->value) {?>
						<span onClick="for_datagrid.page.value=<?php echo $_smarty_tpl->tpl_vars['page']->value+1;?>
; for_datagrid.submit();" style="cursor: pointer;">
							<i class="icon-arrow-right7"></i>
						</span>
						<span onClick="for_datagrid.page.value=<?php echo $_smarty_tpl->tpl_vars['number_page']->value;?>
; for_datagrid.submit();" style="cursor: pointer;">
							<i class="icon-last" ></i>
						</span>
						<?php } else { ?>
						<span>
							<i class="icon-arrow-right7" style="color: #CCC"></i>
							<i class="icon-last" style="color: #CCC"></i>
						</span>
						<?php }?>&nbsp;
						</div>
					</div>
				</div>
			</form>

		</div>		
	</div>
</div><?php }} ?>
