<?php /* Smarty version Smarty-3.1.19, created on 2018-10-08 22:44:24
         compiled from "/Applications/MAMP/htdocs/meduse/themes/default-bootstrap/modules/cheque/views/templates/hook/infos.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16831083985bbbc1a81412f1-55576768%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8aeec4fb68e9d8e0de458815be2f0ed74002e35d' => 
    array (
      0 => '/Applications/MAMP/htdocs/meduse/themes/default-bootstrap/modules/cheque/views/templates/hook/infos.tpl',
      1 => 1538050692,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16831083985bbbc1a81412f1-55576768',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.19',
  'unifunc' => 'content_5bbbc1a8152059_71042111',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5bbbc1a8152059_71042111')) {function content_5bbbc1a8152059_71042111($_smarty_tpl) {?>

<div class="alert alert-info">
<img src="../modules/cheque/cheque.jpg" style="float:left; margin-right:15px;" width="86" height="49">
<p><strong><?php echo smartyTranslate(array('s'=>"This module allows you to accept payments by check.",'mod'=>'cheque'),$_smarty_tpl);?>
</strong></p>
<p><?php echo smartyTranslate(array('s'=>"If the client chooses this payment method, the order status will change to 'Waiting for payment.'",'mod'=>'cheque'),$_smarty_tpl);?>
</p>
<p><?php echo smartyTranslate(array('s'=>"You will need to manually confirm the order as soon as you receive a check.",'mod'=>'cheque'),$_smarty_tpl);?>
</p>
</div>
<?php }} ?>
