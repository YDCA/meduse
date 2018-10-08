<?php /* Smarty version Smarty-3.1.19, created on 2018-10-08 22:44:20
         compiled from "/Applications/MAMP/htdocs/meduse/admin/themes/default/template/controllers/logs/employee_field.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11803720025bbbc1a4851163-80745196%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '125bfd6a4e9e917e50ce3136ac1fada133723129' => 
    array (
      0 => '/Applications/MAMP/htdocs/meduse/admin/themes/default/template/controllers/logs/employee_field.tpl',
      1 => 1538050690,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11803720025bbbc1a4851163-80745196',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'employee_image' => 0,
    'employee_name' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5bbbc1a4859455_79111361',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bbbc1a4859455_79111361')) {function content_5bbbc1a4859455_79111361($_smarty_tpl) {?>
<span class="employee_avatar_small">
	<img class="imgm img-thumbnail" alt="" src="<?php echo $_smarty_tpl->tpl_vars['employee_image']->value;?>
" width="32" height="32" />
</span>
<?php echo $_smarty_tpl->tpl_vars['employee_name']->value;?>

<?php }} ?>
