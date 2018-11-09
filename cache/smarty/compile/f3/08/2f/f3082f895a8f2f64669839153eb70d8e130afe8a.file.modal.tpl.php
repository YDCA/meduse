<?php /* Smarty version Smarty-3.1.19, created on 2018-11-09 17:47:22
         compiled from "/Applications/MAMP/htdocs/meduse/admin726baf167/themes/default/template/helpers/modules_list/modal.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7594828155be5ba1a7f9ce6-23985257%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f3082f895a8f2f64669839153eb70d8e130afe8a' => 
    array (
      0 => '/Applications/MAMP/htdocs/meduse/admin726baf167/themes/default/template/helpers/modules_list/modal.tpl',
      1 => 1541782018,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7594828155be5ba1a7f9ce6-23985257',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5be5ba1a7ffb32_11417394',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5be5ba1a7ffb32_11417394')) {function content_5be5ba1a7ffb32_11417394($_smarty_tpl) {?><div class="modal fade" id="modules_list_container">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title"><?php echo smartyTranslate(array('s'=>'Recommended Modules and Services'),$_smarty_tpl);?>
</h3>
			</div>
			<div class="modal-body">
				<div id="modules_list_container_tab_modal" style="display:none;"></div>
				<div id="modules_list_loader"><i class="icon-refresh icon-spin"></i></div>
			</div>
		</div>
	</div>
</div>
<?php }} ?>
