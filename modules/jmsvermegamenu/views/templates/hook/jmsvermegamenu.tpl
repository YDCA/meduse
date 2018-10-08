{*
 * @package Jms Drop Megamenu
 * @version 1.0
 * @Copyright (C) 2009 - 2015 Joommasters.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @Website: http://www.joommasters.com
*}

{$vermenu_html|escape:'htmlall':'UTF-8'}
<script type="text/javascript">
	jQuery(document).ready(function($) {
    	jQuery('.jms-vermegamenu').jmsVerMegaMenu({    			
    		event: '{$JMSVMM_MOUSEEVENT|escape:'htmlall':'UTF-8'}',
    		duration: '{$JMSVMM_DURATION|escape:'htmlall':'UTF-8'}'
    	});		
	});	
</script>
<style type="text/css">
.jms-vermegamenu .dropdown-menu {    
	transition:all {$JMSVMM_DURATION|escape:'htmlall':'UTF-8'}ms;
}
</style>