<?php /* Smarty version Smarty-3.1.19, created on 2018-10-08 22:44:18
         compiled from "/Applications/MAMP/htdocs/meduse/admin/themes/default/template/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:406224525bbbc1a270f006-56001758%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7d817dfe9654740af7dd3c93ef2aa1925e70ed65' => 
    array (
      0 => '/Applications/MAMP/htdocs/meduse/admin/themes/default/template/content.tpl',
      1 => 1538050690,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '406224525bbbc1a270f006-56001758',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5bbbc1a279a215_21626071',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bbbc1a279a215_21626071')) {function content_5bbbc1a279a215_21626071($_smarty_tpl) {?>
<div id="ajax_confirmation" class="alert alert-success hide"></div>

<div id="ajaxBox" style="display:none"></div>


<div class="row">
	<div class="col-lg-12">
		<?php if (isset($_smarty_tpl->tpl_vars['content']->value)) {?>
			<?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		<?php }?>
	</div>
</div>
<?php }} ?>
