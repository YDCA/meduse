<?php /* Smarty version Smarty-3.1.19, created on 2018-10-16 19:36:08
         compiled from "/Applications/MAMP/htdocs/meduse/admin726baf167/themes/default/template/content.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10549101095bc621885721f7-56862523%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2e260013d967d0fe29503f50de95406d88132d6f' => 
    array (
      0 => '/Applications/MAMP/htdocs/meduse/admin726baf167/themes/default/template/content.tpl',
      1 => 1538050690,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10549101095bc621885721f7-56862523',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5bc62188582098_73300875',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bc62188582098_73300875')) {function content_5bc62188582098_73300875($_smarty_tpl) {?>
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
