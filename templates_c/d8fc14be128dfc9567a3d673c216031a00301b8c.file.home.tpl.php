<?php /* Smarty version Smarty-3.1.19, created on 2016-09-05 06:48:36
         compiled from "module\layout\templates\home.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1879557ccf924c76a44-04587087%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd8fc14be128dfc9567a3d673c216031a00301b8c' => 
    array (
      0 => 'module\\layout\\templates\\home.tpl',
      1 => 1472714428,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1879557ccf924c76a44-04587087',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57ccf924cb1b91_64709376',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ccf924cb1b91_64709376')) {function content_57ccf924cb1b91_64709376($_smarty_tpl) {?><?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; echo smarty_php_tag(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	loadModule("header");
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_php_tag(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<section id="content">
	<?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; echo smarty_php_tag(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

		loadModule("slideshow");
	<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_php_tag(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

	<?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; echo smarty_php_tag(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

		loadModule("product","home");
		loadModule("product","category_product");
		loadModule("tintuc","tinmoi");
	<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_php_tag(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

</section>
<?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; echo smarty_php_tag(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	loadModule("footer");
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_php_tag(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>

<?php }} ?>
