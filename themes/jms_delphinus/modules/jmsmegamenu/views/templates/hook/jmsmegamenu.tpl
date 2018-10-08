{*
 * @package Jms Drop Megamenu
 * @version 1.0
 * @Copyright (C) 2009 - 2015 Joommasters.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @Website: http://www.joommasters.com
*}
<a id="open-button" class="open-button hidden-lg hidden-md" href="#"><span class="fa fa-bars"></span></a>
{$menu_html|escape:''}
<script type="text/javascript">
	jQuery(document).ready(function($) {
    	jQuery('.jms-megamenu').jmsMegaMenu({    			
    		event: '{$JMSMM_MOUSEEVENT}',
    		duration: {$JMSMM_DURATION}
    	});		
	});	
</script>
