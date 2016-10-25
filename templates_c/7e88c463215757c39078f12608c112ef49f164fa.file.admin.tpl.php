<?php /* Smarty version Smarty-3.1.19, created on 2016-10-25 03:51:03
         compiled from "module\layout\templates\admin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1332357ccf957d10ac6-62517963%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7e88c463215757c39078f12608c112ef49f164fa' => 
    array (
      0 => 'module\\layout\\templates\\admin.tpl',
      1 => 1477359904,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1332357ccf957d10ac6-62517963',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_57ccf957d49d00_89044793',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_57ccf957d49d00_89044793')) {function content_57ccf957d49d00_89044793($_smarty_tpl) {?><?php $_smarty_tpl->smarty->_tag_stack[] = array('php', array()); $_block_repeat=true; echo smarty_php_tag(array(), null, $_smarty_tpl, $_block_repeat);while ($_block_repeat) { ob_start();?>

	loadModule('admin');
<?php $_block_content = ob_get_clean(); $_block_repeat=false; echo smarty_php_tag(array(), $_block_content, $_smarty_tpl, $_block_repeat); } array_pop($_smarty_tpl->smarty->_tag_stack);?>
<?php }} ?>
