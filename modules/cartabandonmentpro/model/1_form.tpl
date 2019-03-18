{*
* 2007-2017 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div id="color_1_1_1" style="background-color: #2A374E;"><center>
    <input id="color_picker_1_1_1" class="color" name="color_picker_1_1_1" value="2A374E" />
    <table  cellpadding="0" cellspacing="0" border="0">
        <tbody>
            <tr>
                <td class="w640"  width="640" height="10"></td>
            </tr>

            <tr>
                <td align="center" class="w640"  width="640" height="20"></td>
            </tr>
            <tr>
                <td class="w640"  width="640" height="10"></td>
            </tr>


            <!-- entete -->
            <tr class="pagetoplogo">
                <td id="color_1_2_1" bgcolor="#F2F0F0">
                    <table cellpadding="0" cellspacing="0" border="0">
                        <tbody>
                            <tr><td colspan="3"><input id="color_picker_1_2_1" class="color" name="color_picker_1_2_1" value="F2F0F0" /></td></tr>
                            <tr>
                                <td class="w30"  width="30"></td>
                                <td  class="w580"  width="580" valign="middle" align="center">
                                    <div class="pagetoplogo-content">
                                        <center>
                                            <a href="{$url}" target="_blank">
                                                <img  style="text-decoration: none; display: block; color:#476688; font-size:30px;max-height:200px;" src="http://{$logo}" alt="%SHOP_NAME%"/>
                                            </a>
                                        </center>
                                    </div>
                                </td>
                                <td class="w30"  width="30"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <!-- separateur horizontal -->
            <tr>
                <td  class="w640"  width="640" height="1" bgcolor="#d7d6d6"></td>
            </tr>

             <!-- contenu -->
            <tr class="content">
                <td id="color_1_3_1" class="w640" class="w640"  width="640" bgcolor="#ffffff">
                    <input id="color_picker_1_3_1" class="color" name="color_picker_1_3_1" value="ffffff" />
                    <table class="w640"  width="640" cellpadding="0" cellspacing="0" border="0">
                        <tbody>
                            <tr>
                                <td  class="w30"  width="30"></td>
                                <td  class="w580"  width="580">
                                    <!-- une zone de contenu -->
                                    <table class="w580"  width="580" cellpadding="0" cellspacing="0" border="0">
                                        <tbody>
                                            <tr>
                                                <td class="w580"  width="580">
                                                    <textarea name="center_1_1_1" id="tpl1_center_1_1"></textarea>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!-- fin zone -->
                                </td>
                                <td class="w30" class="w30"  width="30"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <!--  separateur horizontal de 15px de  haut-->
            <tr>
                <td class="w640"  width="640" height="1" bgcolor="#ffffff"></td>
            </tr>

            <!-- pied de page -->
            <tr class="pagebottom">
                <td id="color_1_4_1" class="w640"  width="640" bgcolor="#c7c7c7">
                    <table class="w640"  width="640" cellpadding="0" cellspacing="0" border="0" >
                        <tbody>
                            <tr><td colspan=3><input id="color_picker_1_4_1" class="color" name="color_picker_1_4_1" value="c7c7c7" /></td></tr>
                            <tr>
                                <td colspan="5" height="10"></td>
                            </tr>
                            <tr>
                                <td class="w30"  width="30"></td>
                                <td class="w580"  width="580" valign="top">
                                    <textarea name="center_1_2_1" id="tpl1_center_2_1">
                                    <table>
                                        <tr>
                                            <td colspan="8"><font size="2"><b>100% {l s='satisfaction guarantee: ' mod='cartabandonmentpro'}</b></font></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <img src="{$url}modules/cartabandonmentpro/img/picto/Livraison.png">
                                            </td>
                                            <td>
                                                <b>{l s='Fast delivery' mod='cartabandonmentpro'}</b>
                                            </td>
                                            <td>
                                                <img src="{$url}modules/cartabandonmentpro/img/picto/Secure_Paiement.png">
                                            </td>
                                            <td>
                                                <b>{l s='Secure paiement' mod='cartabandonmentpro'}</b>
                                            </td>
                                            <td>
                                                <img src="{$url}modules/cartabandonmentpro/img/picto/Satisfied.png">
                                            </td>
                                            <td>
                                                <b>{l s='Satisfied or refunded' mod='cartabandonmentpro'}</b>
                                            </td>
                                            <td>
                                                <img src="{$url}modules/cartabandonmentpro/img/picto/SAV.png">
                                            </td>
                                            <td>
                                                <b>{l s='After-sales service' mod='cartabandonmentpro'}</b>
                                            </td>
                                        </tr>
                                    </table>
                                    </textarea>
                                </td>

                                <td class="w30"  width="30"></td>
                            </tr>
                            <tr>
                                <td colspan="5" height="10"></td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="w640"  width="640" height="60"></td>
            </tr>
        </tbody>
    </table>
    </center>
</div>