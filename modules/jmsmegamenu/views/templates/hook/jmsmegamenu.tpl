{*
 * @package Jms Drop Megamenu
 * @version 1.0
 * @Copyright (C) 2009 - 2015 Joommasters.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @Website: http://www.joommasters.com
*}

{$menu_html|escape:'htmlall':'UTF-8'}
<script type="text/javascript">
	jQuery(document).ready(function($) {
    	jQuery('.jms-megamenu').jmsMegaMenu({    			
    		event: '{$JMSMM_MOUSEEVENT|escape:'htmlall':'UTF-8'}',
    		duration: {$JMSMM_DURATION|escape:'htmlall':'UTF-8'}
    	});		
	});	
</script>
<style type="text/css">
.jms-megamenu .dropdown-menu {    
	transition:all {$JMSMM_DURATION|escape:'htmlall':'UTF-8'}ms;
}
</style>