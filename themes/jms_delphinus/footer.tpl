{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{if $page_name!='index'}	
				</section>
				{if !$hide_right_column && !empty($HOOK_RIGHT_COLUMN) && $page_name=='product'}						
				<!-- right -->
				<aside class="col-sm-4 col-md-3 col-lg-3 content-aside">
					<div class="content-aside-inner">
					{$HOOK_RIGHT_COLUMN}
					</div>
				</aside>
				{/if}	
			</div>		
			</section>	
		{/if}
			{if isset($HOOK_FOOTER) && !$content_only}{$HOOK_FOOTER}{/if}
			</div></div>
		</div><!-- #page -->
		<div class="back-to-top" id="back-to-top">
			<span class="lnr lnr-chevron-up"></span>
		</div>
		{include file="$tpl_dir./global.tpl"}
	</body>
</html>