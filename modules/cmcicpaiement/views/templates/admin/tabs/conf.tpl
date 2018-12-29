{*
* 2007-2015 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*}

{* selected done *}
<h3><i class="icon-cogs"></i> {l s='Configuration' mod='cmcicpaiement'} <small>{$module_display|escape:'htmlall':'UTF-8'}</small></h3>
<div id="wizard" class="swMain">
	{$html|escape}

	{if $error == 0 && $step_name neq ''}
		<div class="module_confirmation conf confirm alert alert-success">
			<button type="button" class="close" data-dismiss="alert">×</button>
			{$step_name|escape:'htmlall':'UTF-8'} {l s='has been updated.' mod='cmcicpaiement'}
		</div>
	{elseif $error < 0}
		<div class="module_confirmation conf confirm alert alert-danger">
			<button type="button" class="close" data-dismiss="alert">×</button>
			{$step_name|escape:'htmlall':'UTF-8'} {l s='cannot be updated.' mod='cmcicpaiement'}
			<p>{l s='Please verify the details that you have entered.' mod='cmcicpaiement'}</p>
		</div>
	{/if}

	<div class="clearfix"></div>

	<div class="panel-group" id="accordion">
<!-- Info configutation -->
		<div class="alert alert-info">
			{l s="Assurez-vous du bon fonctionnement de votre module de paiement : laissez notre équipe d'experts s'occuper de son installation et de sa configuration !" mod='cmcicpaiement'}<br>
			<a target="_blank" href="https://addons.prestashop.com/fr/support/186-installation-et-configuration-de-module-de-paiement-par-prestashop.html{$tracking_url_install|escape:'urlencode'}"> {l s="Découvrir >" mod='cmcicpaiement'}</a>
		</div>
		{* Setup your bank informations *}
		<div class="panel">
			<div class="panel-heading">
				{if $ps_version == 0}<h3>{/if}
					1 - {l s='Setup your bank informations' mod='cmcicpaiement'}
				{if $ps_version == 0}</h3>{/if}
			</div>
			<form id="form_step_one" name="form_step_one" action="{$form_uri|escape:'htmlall':'UTF-8'}" method="post">
				<div id="collapse1">
					<div class="clearfix">
						<div class="form-group clear">
							<label for="form-field-1" class="col-sm-4 control-label">
								<h4>{l s='CYPHER KEY' mod='cmcicpaiement'}</h4>
							</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" value="{if isset($key) & !empty($key)}{$key|escape:'htmlall':'UTF-8'}{/if}" id="CMCIC_KEY" name="CMCIC_KEY" placeholder="{l s='CYPHER KEY' mod='cmcicpaiement'}" maxlength="40"/>
								<p class="help-block">
									{l s='The security key is represented by 40 hexadecimal characters. Document provided by your bank.' mod='cmcicpaiement'}
								</p>
							</div>
						</div>
						<div class="form-group clear">
							<label for="form-field-1" class="col-sm-4 control-label">
								<h4>{l s='TPE' mod='cmcicpaiement'}</h4>
							</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" value="{if isset($tpe) & !empty($tpe)}{$tpe|escape:'htmlall':'UTF-8'}{/if}" id="CMCIC_TPE" name="CMCIC_TPE" placeholder="{l s='TPE' mod='cmcicpaiement'}" maxlength="7"/>
								<p class="help-block">
									{l s='Number of virtual site number (7 figures) Exemple : 1234567.' mod='cmcicpaiement'}
								</p>
							</div>
						</div>
						<div class="form-group clear">
							<label for="form-field-1" class="col-sm-4 control-label">
								<h4>{l s='Company Code' mod='cmcicpaiement'}</h4>
							</label>
							<div class="col-sm-6">
								<input type="text" class="form-control" value="{if isset($company_code) & !empty($company_code)}{$company_code|escape:'htmlall':'UTF-8'}{/if}" id="CMCIC_COMPANY_CODE" name="CMCIC_COMPANY_CODE" placeholder="{l s='Company Code' mod='cmcicpaiement'}" />
								<p class="help-block">
									{l s='Allows you to use the same POS for different sites related to the same activity. It is provided by CM-CIC. Exemple : yourSite1.' mod='cmcicpaiement'}
								</p>
							</div>
						</div>

						<div class="form-group clear">
							<label for="form-field-1" class="col-sm-4 control-label">
								<h4>{l s='Environment' mod='cmcicpaiement'}</h4>
							</label>
							<div class="col-sm-6">

								<span id="cmcic_server" class="switch prestashop-switch input-group col-sm-12 col-md-8 col-lg-8">
									<input type="radio" name="CMCIC_ENVIRONMENT" id="cmcic_yes" {if $environment == 1}checked="checked"{/if} value="1" />
									<label for="cmcic_yes" class="radioCheck">
										<i class="color_success"></i> {l s=' PRODUCTION ' mod='cmcicpaiement'}
									</label>
									<input type="radio" class="switch_off" name="CMCIC_ENVIRONMENT" id="cmcic_no"{if empty($environment) || $environment|intval === 0}checked="checked"{/if} value="0" />
									<label for="cmcic_no" class="radioCheck">
										<i class="color_success"></i> {l s=' TEST ' mod='cmcicpaiement'}
									</label>
									<a class="slide-button btn"></a>
								</span>

								<p class="help-block">
									{l s='This option defines in which environment your account is configured' mod='cmcicpaiement'}
								</p>
							</div>
						</div>

                        <div class="form-group clear">
                            <label for="form-field-1" class="col-sm-4 control-label">
                                <h4>{l s='Payment server' mod='cmcicpaiement'}</h4>
                            </label>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="CMCIC_SERVER" id="CMCIC_SERVER0" value="0" {if $server|intval == 0}checked="checked"{/if} />
                                        {l s='Credit Mutuel' mod='cmcicpaiement'}
                                        <p class="help-block">
                                            {l s='Pour les banques et fédérations du Crédit Mutuel' mod='cmcicpaiement'}
                                        </p>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="CMCIC_SERVER" id="CMCIC_SERVER1" value="1" {if $server|intval == 1}checked="checked"{/if} />
                                        {l s='CIC' mod='cmcicpaiement'}
                                    <p class="help-block">
                                        {l s='Pour les banques du Groupe CIC' mod='cmcicpaiement'}
                                    </p>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="CMCIC_SERVER" id="CMCIC_SERVER2" value="2" {if $server|intval == 2}checked="checked"{/if} />
                                        {l s='OBC' mod='cmcicpaiement'}
                                    <p class="help-block">
                                        {l s='Pour la banque OBC' mod='cmcicpaiement'}
                                    </p>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="CMCIC_SERVER" id="CMCIC_SERVER3" value="3" {if $server|intval == 3}checked="checked"{/if} />
                                        {l s='MONETICO' mod='cmcicpaiement'}
                                    <p class="help-block">
                                        {l s='Pour le nouveau serveur Monetico' mod='cmcicpaiement'}
                                    </p>
                                    </label>
                                </div>
                            </div>
                        </div>

						<div class="form-group clear">
							<label for="form-field-1" class="col-sm-4 control-label">
								<h4>{l s='Have you suscribe to the "Express Payment" option ?' mod='cmcicpaiement'}</h4>
							</label>
							<div class="col-sm-6">

								<span id="cmcic_express" class="switch prestashop-switch input-group col-sm-12 col-md-8 col-lg-8">
									<input type="radio" name="CMCIC_EXPRESS" id="cmcic_express_yes" {if $express_option == 1}checked="checked"{/if} value="1" />
									<label for="cmcic_express_yes" class="radioCheck">
										<i class="color_success"></i> {l s=' ACTIVATED ' mod='cmcicpaiement'}
									</label>
									<input type="radio" class="switch_off" name="CMCIC_EXPRESS" id="cmcic_express_no"{if empty($express_option) || $express_option|intval === 0}checked="checked"{/if} value="0" />
									<label for="cmcic_express_no" class="radioCheck">
										<i class="color_success"></i> {l s=' DESACTIVATED ' mod='cmcicpaiement'}
									</label>
									<a class="slide-button btn"></a>
								</span>

								<p class="help-block">
									{l s='Please check with your bank before activate this option' mod='cmcicpaiement'}
								</p>
							</div>
						</div>

						<div class="clearfix"></div>
					</div>
				</div>
			<div class="panel-footer">
				<div class="btn-group pull-right">
					<button name="submitBankInformations" type="submit" class="btn btn-default" value="1"><i class="process-icon-save"></i> {l s='Save' mod='cmcicpaiement'}</button>
				</div>
			</div>
			</form>
		</div>

		{* Setup your URL *}
		<div class="panel">
			<div class="panel-heading">
				{if $ps_version == 0}<h3>{/if}
					2 - {l s='Check your URL' mod='cmcicpaiement'}
					{if $ps_version == 0}</h3>{/if}
			</div>
			<div id="collapse1">
				<div class="alert alert-warning fade in" role="alert">
					<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
					<strong> Warning ! </strong> {l s='You have to send the URL validation to Center Com at centrecom@e-i.com before using this module' mod='cmcicpaiement'}
				</div>
				<div class="clearfix">
					<div class="form-group clear">
						<label for="form-field-1" class="col-sm-4 control-label">
							<h4>{l s='URL OK' mod='cmcicpaiement'}</h4>
						</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" value="{if isset($url_ok) & !empty($url_ok)}{$url_ok|escape:'htmlall':'UTF-8'}{/if}" id="CMCIC_URLOK" name="CMCIC_URLOK" placeholder="{l s='URL OK' mod='cmcicpaiement'} " readonly="readonly" />
							<p class="help-block">
								{l s='The buyer’s browser is redirected to the return URL when order has been confirmated.' mod='cmcicpaiement'}
							</p>
						</div>
					</div>
					<div class="form-group clear">
						<label for="form-field-1" class="col-sm-4 control-label">
							<h4>{l s='URL KO' mod='cmcicpaiement'}</h4>
						</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" value="{if isset($url_ko) & !empty($url_ko)}{$url_ko|escape:'htmlall':'UTF-8'}{/if}" id="CMCIC_URLKO" name="CMCIC_URLKO" placeholder="{l s='URL KO' mod='cmcicpaiement'}" readonly="readonly" />
							<p class="help-block">
								{l s='The buyer’s browser is redirected to the return URL when order payment has been refused.' mod='cmcicpaiement'}
							</p>
						</div>
					</div>

					<div class="form-group clear">
						<label for="form-field-1" class="col-sm-4 control-label">
							<h4>{l s='Validation URL' mod='cmcicpaiement'}</h4>
							</br>
						</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" value="{if isset($url_validation) & !empty($url_validation)}{$url_validation|escape:'htmlall':'UTF-8'}{/if}" id="CMCIC_URLVALIDATION" name="CMCIC_URLVALIDATION" placeholder="{l s='URL Validation' mod='cmcicpaiement'}" readonly="readonly" />
							<p class="help-block">
								{l s='The buyer’s browser is redirected to the validation URL to verify his cart and his informations.' mod='cmcicpaiement'}
							</p>
						</div>
					</div>

					<div class="clearfix"></div>
				</div>
			</div>
		</div>

		{* Setup options *}
		<div class="panel">
			<div class="panel-heading">
				{if $ps_version == 0}<h3>{/if}
					3 - {l s='Setup options' mod='cmcicpaiement'}
				{if $ps_version == 0}</h3>{/if}
			</div>

			<form id="form_step_three" name="form_step_three" action="{$form_uri|escape:'htmlall':'UTF-8'}" method="post">
				<div id="collapse4">
					<div class="table-responsive clearfix">
						<div class="row">

							<div class="col-sm-4 col-md-4 col-lg-4">
								<div class="well">
									<h4>{l s='Payment error behavior' mod='cmcicpaiement'}</h4>
									<div class="radio">
										<label>
											<input type="radio" name="CMCIC_ERROR_BEHAVIOR" id="CMCIC_ERROR_BEHAVIOR1" value="1" {if $behavior|intval == 1}checked="checked"{/if} />
											{l s='Save order as a payment error' mod='cmcicpaiement'}
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="CMCIC_ERROR_BEHAVIOR" id="CMCIC_ERROR_BEHAVIOR2" value="2" {if $behavior|intval == 2}checked="checked"{/if} />
											{l s='Send me an e-mail' mod='cmcicpaiement'}
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="CMCIC_ERROR_BEHAVIOR" id="CMCIC_ERROR_BEHAVIOR4" value="3" {if $behavior|intval == 3}checked="checked"{/if} />
											{l s='Send me an e-mail and saving the order' mod='cmcicpaiement'}
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" name="CMCIC_ERROR_BEHAVIOR" id="CMCIC_ERROR_BEHAVIOR3" value="0" {if $behavior|intval == 0}checked="checked"{/if} />
											{l s='Do nothing' mod='cmcicpaiement'}
										</label>
									</div>
								</div>
							</div>

							<div class="col-sm-4 col-md-4 col-lg-4">
								<div class="well">
									<h4>{l s='Notification e-mail' mod='cmcicpaiement'}</h4>
									<div class="form-group clear">
										<label for="form-field-1" class="col-sm-4 control-label">
											{l s='Your mail address(es)' mod='cmcicpaiement'}
										</label>
										<div class="col-sm-12">
											<input type="text" class="form-control" value="{if isset($notification) & !empty($notification)}{$notification|escape:'htmlall':'UTF-8'}{/if}" id="CMCIC_EMAIL_NOTIFICATION" name="CMCIC_EMAIL_NOTIFICATION" placeholder="{l s='Your mail address' mod='cmcicpaiement'}" />
											<p class="help-block">
												{l s='Enter your email addresses to receive an notification. You can add several email address (ex : address1@gmail.com,address2@gmail.com)' mod='cmcicpaiement'}
											</p>
											<div class="clear">&nbsp;</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-sm-4 col-md-4 col-lg-4">
								<div class="well">
									<h4>{l s='Setup display logo' mod='cmcicpaiement'}</h4>
									<div class="form-group clear">
										<label for="form-field-1" class="col-sm-4 control-label">
											{l s='Home' mod='cmcicpaiement'}
										</label>
										<div class="col-sm-8">
											<span id="cmcic_logo_home" class="switch prestashop-switch input-group col-sm-12 col-md-12 col-lg-12">
												<input type="radio" name="CMCIC_LOGO_HOME" id="cmcic_logo_home_yes" {if $logo_home == 1}checked="checked"{/if} value="1" />
												<label for="cmcic_logo_home_yes" class="radioCheck">
													<i class="color_success"></i> {l s=' YES ' mod='cmcicpaiement'}
												</label>
												<input type="radio" class="switch_off" name="CMCIC_LOGO_HOME" id="cmcic_logo_home_no"{if empty($logo_home) || $logo_home|intval === 0}checked="checked"{/if} value="0" />
												<label for="cmcic_logo_home_no" class="radioCheck">
													<i class="color_success"></i> {l s=' NO ' mod='cmcicpaiement'}
												</label>
												<a class="slide-button btn"></a>
											</span>
										</div>
									</div>
									<div class="clear">&nbsp;</div>

									<div class="form-group clear">
										<label for="form-field-1" class="col-sm-4 control-label">
											{l s='Right column' mod='cmcicpaiement'}
										</label>
										<div class="col-sm-8">
											<span id="cmcic_logo_right_column" class="switch prestashop-switch input-group col-sm-12 col-md-12 col-lg-12">
												<input type="radio" name="CMCIC_LOGO_RIGHT_COLUMN" id="cmcic_logo_right_column_yes" {if $logo_right_column == 1}checked="checked"{/if} value="1" />
												<label for="cmcic_logo_right_column_yes" class="radioCheck">
													<i class="color_success"></i> {l s=' YES ' mod='cmcicpaiement'}
												</label>
												<input type="radio" class="switch_off" name="CMCIC_LOGO_RIGHT_COLUMN" id="cmcic_logo_right_column_no"{if empty($logo_right_column) || $logo_right_column|intval === 0}checked="checked"{/if} value="0" />
												<label for="cmcic_logo_right_column_no" class="radioCheck">
													<i class="color_success"></i> {l s=' NO ' mod='cmcicpaiement'}
												</label>
												<a class="slide-button btn"></a>
											</span>
										</div>
									</div>
									<div class="clear">&nbsp;</div>

									<div class="form-group clear">
										<label for="form-field-1" class="col-sm-4 control-label">
											{l s='Left Column' mod='cmcicpaiement'}
										</label>
										<div class="col-sm-8">
											<span id="cmcic_logo_left_column" class="switch prestashop-switch input-group col-sm-12 col-md-12 col-lg-12">
												<input type="radio" name="CMCIC_LOGO_LEFT_COLUMN" id="cmcic_logo_left_column_yes" {if $logo_left_column == 1}checked="checked"{/if} value="1" />
												<label for="cmcic_logo_left_column_yes" class="radioCheck">
													<i class="color_success"></i> {l s=' YES ' mod='cmcicpaiement'}
												</label>
												<input type="radio" class="switch_off" name="CMCIC_LOGO_LEFT_COLUMN" id="cmcic_logo_left_column_no"{if empty($logo_left_column) || $logo_left_column|intval === 0}checked="checked"{/if} value="0" />
												<label for="cmcic_logo_left_column_no" class="radioCheck">
													<i class="color_success"></i> {l s=' NO ' mod='cmcicpaiement'}
												</label>
												<a class="slide-button btn"></a>
											</span>
										</div>
									</div>
									<div class="clear">&nbsp;</div>
								</div>
							</div>

							<div class="clearfix"></div>

						</div>
					</div>
				</div>
				<div class="panel-footer">
					<div class="btn-group pull-right">
						<button name="submitCMCICOptions" type="submit" class="btn btn-default" value="1"><i class="process-icon-save"></i> {l s='Save' mod='cmcicpaiement'}</button>
					</div>
				</div>
			</form>
		</div>

	</div>
</div>