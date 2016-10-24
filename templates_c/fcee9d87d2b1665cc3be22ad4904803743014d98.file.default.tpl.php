<?php /* Smarty version Smarty-3.1.19, created on 2016-09-05 10:00:22
         compiled from "module\layout\templates\default.tpl" */ ?>
<?php /*%%SmartyHeaderCode:332957cd2616bbab35-95166792%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fcee9d87d2b1665cc3be22ad4904803743014d98' => 
    array (
      0 => 'module\\layout\\templates\\default.tpl',
      1 => 1472626846,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '332957cd2616bbab35-95166792',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57cd2616bf7045_10756528',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57cd2616bf7045_10756528')) {function content_57cd2616bf7045_10756528($_smarty_tpl) {?><?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; echo smarty_php_tag(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	loadModule("header");
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_php_tag(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>


<!-- <?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; echo smarty_php_tag(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	loadModule($_GET['mod'],$_GET['task']);
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_php_tag(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>
 -->

<?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; echo smarty_php_tag(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	loadModule("footer");
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_php_tag(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }} ?>
