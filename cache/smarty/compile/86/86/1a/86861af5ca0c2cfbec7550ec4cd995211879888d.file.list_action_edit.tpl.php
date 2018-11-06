<?php /* Smarty version Smarty-3.1.19, created on 2018-10-18 14:59:32
         compiled from "/Applications/MAMP/htdocs/meduse/admin726baf167/themes/default/template/helpers/list/list_action_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:5025404935bc883b4a90367-18440651%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '86861af5ca0c2cfbec7550ec4cd995211879888d' => 
    array (
      0 => '/Applications/MAMP/htdocs/meduse/admin726baf167/themes/default/template/helpers/list/list_action_edit.tpl',
      1 => 1538050690,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '5025404935bc883b4a90367-18440651',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'href' => 0,
    'action' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5bc883b4aa5204_26793253',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bc883b4aa5204_26793253')) {function content_5bc883b4aa5204_26793253($_smarty_tpl) {?>
<a href="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['href']->value, ENT_QUOTES, 'UTF-8', true);?>
" title="<?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>
" class="edit">
	<i class="icon-pencil"></i> <?php echo htmlspecialchars($_smarty_tpl->tpl_vars['action']->value, ENT_QUOTES, 'UTF-8', true);?>

</a>
<?php }} ?>
