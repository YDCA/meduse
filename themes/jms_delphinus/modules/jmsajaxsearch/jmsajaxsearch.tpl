{*
 * @package Jms Ajax Search
 * @version 1.1
 * @Copyright (C) 2009 - 2015 Joommasters.
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @Website: http://www.joommasters.com
*}

<div id="jms_ajax_search" class="jms_ajax_search">
	<a href="#"  class="btn-xs search-icon" title="Search">
		<span class="fa fa-search"></span>
	</a>
	<div class="search-box dropdown-menu">	
		<form method="get" action="{$link->getPageLink('search')|escape:'html':'UTF-8'}" id="searchbox">
		<input type="hidden" name="controller" value="search" />
		<input type="hidden" name="orderby" value="position" />
		<input type="hidden" name="orderway" value="desc" />
		<input type="text" id="ajax_search" name="search_query" placeholder="{l s='Search and hit enter...' mod='jmsajaxsearch'}" class="form-control" />		
		</form>
		<a href="#"  class="fa fa-search">		
		</a>
		<a id="close-search" href="#"  class="fa fa-close">		
		</a>
		<div id="search_result">
		</div>
	</div>	
	<div id="cover-background"> </div> 
</div>