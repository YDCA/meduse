{*
 * @package Jms Vertical Megamenu
 * @version 1.0
 * @Copyright (C) 2009 - 2015 Joommasters.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @Website: http://www.joommasters.com
*}
<div class="menu-mobile-vermegamenu">
	<a class="title" id="mobile-jmsvermegamenu" href="#">
		<span class="fa fa-bars"></span>
		{l s='Category' mod='jmsvermegamenu'}
	</a>
	<div id="mobile-vermegamenu">
	{$vermenu_html|escape:''}
	</div>
</div>