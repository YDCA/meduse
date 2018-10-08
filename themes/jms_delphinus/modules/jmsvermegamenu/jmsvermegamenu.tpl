{*
 * @package Jms Drop Megamenu
 * @version 1.0
 * @Copyright (C) 2009 - 2015 Joommasters.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @Website: http://www.joommasters.com
*}

{$vermenu_html|escape:''}
<script type="text/javascript">
	jQuery(document).ready(function($) {
    	jQuery('.jms-vermegamenu').jmsVerMegaMenu({    			
    		event: '{$JMSVMM_MOUSEEVENT}',
    		duration: '{$JMSVMM_DURATION}'
    	});		
	});	
</script>
<style type="text/css">
.jms-vermegamenu .dropdown-menu {    
	transition:all {$JMSVMM_DURATION}ms;
}
</style>