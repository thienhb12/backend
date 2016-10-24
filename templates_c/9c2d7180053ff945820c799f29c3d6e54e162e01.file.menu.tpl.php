<?php /* Smarty version Smarty-3.1.19, created on 2016-09-05 06:49:32
         compiled from "module\admin\templates\menu.tpl" */ ?>
<?php /*%%SmartyHeaderCode:620957ccf95cedcd63-77243758%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9c2d7180053ff945820c799f29c3d6e54e162e01' => 
    array (
      0 => 'module\\admin\\templates\\menu.tpl',
      1 => 1472280673,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '620957ccf95cedcd63-77243758',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'menuItems' => 0,
    'subitem' => 0,
    'item' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57ccf95d020ed4_18052991',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ccf95d020ed4_18052991')) {function content_57ccf95d020ed4_18052991($_smarty_tpl) {?><div class="sidebar sidebar-main">
	<div class="sidebar-content">

		<!-- Main navigation -->
		<div class="sidebar-category sidebar-category-visible">
			<div class="category-content no-padding">
				<ul class="navigation navigation-main navigation-accordion">
					<!-- Main -->
					<li class="navigation-header"><span>Main</span> <i class="icon-menu" title="" data-original-title="Main pages"></i></li>
					<li><a href="index.html"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
					<?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['menuItems']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
						<li class="<?php echo $_smarty_tpl->tpl_vars['subitem']->value['selected'];?>
">
							<a href="?mod=admin&<?php echo $_smarty_tpl->tpl_vars['item']->value['link'];?>
" class="has-ul"><i class="icon-stack2"></i> <span><?php echo $_smarty_tpl->tpl_vars['item']->value['name'];?>
</span></a>
							<?php if (count($_smarty_tpl->tpl_vars['item']->value['submenu'])>0) {?>
								<ul >
									<?php  $_smarty_tpl->tpl_vars['subitem'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['subitem']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['item']->value['submenu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['subitem']->key => $_smarty_tpl->tpl_vars['subitem']->value) {
$_smarty_tpl->tpl_vars['subitem']->_loop = true;
?>
										<?php if (substr($_smarty_tpl->tpl_vars['subitem']->value['link'],0,7)!='http://') {?>
											<li class="<?php echo $_smarty_tpl->tpl_vars['subitem']->value['selected'];?>
"><a href="?mod=admin&<?php echo $_smarty_tpl->tpl_vars['subitem']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['subitem']->value['name'];?>
</a></li>
										<?php } else { ?>
											<li class="<?php echo $_smarty_tpl->tpl_vars['subitem']->value['selected'];?>
"><a href="<?php echo $_smarty_tpl->tpl_vars['subitem']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['subitem']->value['name'];?>
</a></li>
										<?php }?>
									<?php } ?>
								</ul>
							<?php }?>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<!-- /main navigation -->

	</div>
</div><?php }} ?>
