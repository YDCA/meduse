{*
* NOTICE OF LICENSE
*
* This source file is subject to a trade license awared by
* Garamo Online L.T.D.
*
* Any use, reproduction, modification or distribution
* of this source file without the written consent of
* Garamo Online L.T.D It Is prohibited.
*
*  @author    ReactionCode <info@reactioncode.com>
*  @copyright 2015-2018 Garamo Online L.T.D
*  @license   Commercial license
*}
{extends file="helpers/form/form.tpl"}

{block name="defaultForm"}
    <div class="row">
        {block name="tabs"}
            {if isset($vertical_tabs)}
                <div class="vertical-tabs col-lg-2 col-md-3">
                    {foreach $vertical_tabs as $group_name => $group_tabs}
                        <div id="js-{$group_name|escape:'html':'UTF-8'}-tabs" class="list-group js-tabs-list">
                            {foreach $group_tabs as $id_tab => $tab}
                                <a
                                        id="{$id_tab|escape:'html':'UTF-8'}"
                                        class="js-tab-item list-group-item{if !isset($tab.link)} form-tab-item{/if}{if isset($tab.is_reachable) && !$tab.is_reachable} unreachable disabled{else} reachable{/if}{if isset($tab.is_complete) && $tab.is_complete} complete{/if}{if isset($tab.hidden) && $tab.hidden} hidden{/if}{if isset($tab.active) && $tab.active} active{/if}"
                                        {if isset($tab.link)}
                                            href="{$tab.link|escape:'html':'UTF-8'}"
                                        {/if}
                                        {if isset($tab.target)}target="{$tab.target|escape:'html':'UTF-8'}" {if $tab.target ==='_blank'}rel="noopener noreferrer"{/if}{/if}
                                >{if isset($tab.is_completed) && $tab.is_completed}<i class="icon-ok-sign"></i>{/if}{if isset($tab.icon)}<i class="icon-{$tab.icon|escape:'html':'UTF-8'}"></i>{/if}{$tab.name|escape:'html':'UTF-8'}</a>
                            {/foreach}
                        </div>
                    {/foreach}
                </div>
            {/if}
        {/block}

        <form id="{if isset($fields.form.form.id_form)}{$fields.form.form.id_form|escape:'html':'UTF-8'}{else}{if $table == null}configuration_form{else}{$table|escape:'html':'UTF-8'}_form{/if}{if isset($smarty.capture.table_count) && $smarty.capture.table_count}_{$smarty.capture.table_count|intval}{/if}{/if}" class="defaultForm form-horizontal{if isset($vertical_tabs) && $vertical_tabs|count} col-lg-10 col-md-9{/if}{if isset($name_controller) && $name_controller} {$name_controller}{/if}"{if isset($current) && $current} action="{$current|escape:'html':'UTF-8'}{if isset($token) && $token}&amp;token={$token|escape:'html':'UTF-8'}{/if}"{/if} method="post" enctype="multipart/form-data"{if isset($style)} style="{$style}"{/if} novalidate>
            {if $form_id}
                <input type="hidden" name="{$identifier|escape:'html':'UTF-8'}" id="{$identifier|escape:'html':'UTF-8'}{if isset($smarty.capture.identifier_count) && $smarty.capture.identifier_count}_{$smarty.capture.identifier_count|intval}{/if}" value="{$form_id}" />
            {/if}
            {if !empty($submit_action)}
                <input type="hidden" name="{$submit_action|escape:'html':'UTF-8'}" value="1" />
            {/if}

            {foreach $fields as $f => $fieldset}
                {block name="fieldset"}
                    {if isset($vertical_tabs) && isset($vertical_tabs.form[$f])}
                        <div id="js-tab-content-{$f|escape:'html':'UTF-8'}" class="js-tab-content{if isset($vertical_tabs.form[$f].active) && $vertical_tabs.form[$f].active} active{else} hidden{/if}">
                    {/if}
                    {capture name='fieldset_name'}{counter name='fieldset_name'}{/capture}
                    <div class="panel" id="fieldset_{$f|escape:'html':'UTF-8'}{if isset($smarty.capture.identifier_count) && $smarty.capture.identifier_count}_{$smarty.capture.identifier_count|intval}{/if}{if $smarty.capture.fieldset_name > 1}_{($smarty.capture.fieldset_name - 1)|intval}{/if}">
                        {foreach $fieldset.form as $key => $field}
                            {if $key == 'legend'}
                                {block name="legend"}
                                    <div class="panel-heading">
                                        {if isset($field.image) && isset($field.title)}<img src="{$field.image|escape:'html':'UTF-8'}" alt="{$field.title|escape:'html':'UTF-8'}" />{/if}
                                        {if isset($field.icon)}<i class="{$field.icon|escape:'html':'UTF-8'}"></i>{/if}
                                        {$field.title|escape:'html':'UTF-8'}
                                    </div>
                                {/block}
                            {elseif $key == 'description' && $field}
                                <div class="alert alert-info">{$field|escape:'quotes'}</div>
                            {elseif $key == 'warning' && $field}
                                <div class="alert alert-warning">{$field|escape:'quotes'}</div>
                            {elseif $key == 'success' && $field}
                                <div class="alert alert-success">{$field|escape:'quotes'}</div>
                            {elseif $key == 'error' && $field}
                                <div class="alert alert-danger">{$field|escape:'quotes'}</div>
                            {elseif $key == 'input'}
                                <div class="form-wrapper">
                                    {foreach $field as $input}
                                        {block name="input_row"}
                                            <div class="form-group{if isset($input.form_group_class)} {$input.form_group_class|escape:'html':'UTF-8'}{/if}{if $input.type == 'hidden'} hide{/if}"{if $input.name == 'id_state'} id="contains_states"{if !$contains_states} style="display:none;"{/if}{/if}>
                                                {if $input.type == 'hidden'}
                                                    <input type="hidden" name="{$input.name|escape:'html':'UTF-8'}" id="{$input.name|escape:'html':'UTF-8'}" value="{$fields_value[$input.name]|escape:'html':'UTF-8'}" />
                                                {else}
                                                    {block name="label"}
                                                        {if isset($input.label)}
                                                            <label class="control-label col-lg-3{if isset($input.required) && $input.required && $input.type != 'radio'} required{/if}">
                                                                {if isset($input.hint)}
                                                                <span class="label-tooltip" data-toggle="tooltip" data-html="true" data-original-title="
                                                                    {if is_array($input.hint)}
                                                                        {foreach $input.hint as $hint}
                                                                            {if is_array($hint)}
                                                                                {$hint.text|escape:"html":"UTF-8"}
                                                                            {else}
                                                                                {$hint|escape:"html":"UTF-8"}
                                                                            {/if}
                                                                        {/foreach}
                                                                    {else}
                                                                        {$input.hint|escape:"html":"UTF-8"}
                                                                    {/if}">
                                                                {/if}
                                                                    {$input.label|escape:'html':'UTF-8'}
                                                                    {if isset($input.hint)}
                                                                    </span>
                                                                {/if}
                                                            </label>
                                                        {/if}
                                                    {/block}

                                                    {block name="field"}
                                                        <div class="col-lg-{if isset($input.col)}{$input.col|intval}{else}9{/if}{if !isset($input.label)} col-lg-offset-3{/if}">
                                                            {block name="input"}
                                                                {if $input.type == 'text'}
                                                                {if isset($input.lang) && $input.lang}
                                                                {if $languages|count > 1}
                                                                    <div class="form-group">
                                                                        {/if}
                                                                        {foreach $languages as $language}
                                                                            {assign var='value_text' value=$fields_value[$input.name][$language.id_lang]}
                                                                            {if $languages|count > 1}
                                                                                <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
                                                                                <div class="col-lg-9">
                                                                            {/if}
                                                                        {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                                            <div class="input-group{if isset($input.class)} {$input.class|escape:'html':'UTF-8'}{/if}">
                                                                        {/if}
                                                                            {if isset($input.maxchar) && $input.maxchar}
                                                                                <span id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}_{$language.id_lang|intval}{else}{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}{/if}_counter" class="input-group-addon">
                                                                                    <span class="text-count-down">{$input.maxchar|intval}</span>
                                                                                </span>
                                                                            {/if}
                                                                            {if isset($input.prefix)}
                                                                                <span class="input-group-addon">
                                                                                    {$input.prefix|escape:'quotes'}
                                                                                </span>
                                                                            {/if}
                                                                            <input type="text"
                                                                                   id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}_{$language.id_lang|intval}{else}{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}{/if}"
                                                                                   name="{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}"
                                                                                   class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"
                                                                                   value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                                                   onkeyup="if (isArrowKey(event)) return ;updateFriendlyURL();"
                                                                                    {if isset($input.size)} size="{$input.size|intval}"{/if}
                                                                                    {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                                                    {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                                                    {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                                                    {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                                                    {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                                                    {if isset($input.required) && $input.required} required="required" {/if}
                                                                                    {if isset($input.placeholder) && $input.placeholder} placeholder="{$input.placeholder|escape:'html':'UTF-8'}"{/if}
                                                                            />
                                                                            {if isset($input.suffix)}
                                                                                <span class="input-group-addon">{$input.suffix|escape:'quotes'}</span>
                                                                            {/if}
                                                                        {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                                            </div>
                                                                        {/if}
                                                                            {if $languages|count > 1}
                                                                                </div>
                                                                                <div class="col-lg-2">
                                                                                    <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                                                        {$language.iso_code|escape:'html':'UTF-8'}
                                                                                        <i class="icon-caret-down"></i>
                                                                                    </button>
                                                                                    <ul class="dropdown-menu">
                                                                                        {foreach from=$languages item=language}
                                                                                            <li><a href="javascript:hideOtherLanguage({$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'html':'UTF-8'}</a></li>
                                                                                        {/foreach}
                                                                                    </ul>
                                                                                </div>
                                                                                </div>
                                                                            {/if}
                                                                        {/foreach}
                                                                        {if isset($input.maxchar) && $input.maxchar}
                                                                            <script type="text/javascript">
                                                                                $(document).ready(function(){
                                                                                    {foreach from=$languages item=language}
                                                                                    countDown($("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}_{$language.id_lang|intval}{else}{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}{/if}"), $("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}_{$language.id_lang|intval}{else}{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}{/if}_counter"));
                                                                                    {/foreach}
                                                                                });
                                                                            </script>
                                                                        {/if}
                                                                        {if $languages|count > 1}
                                                                    </div>
                                                                {/if}
                                                                {else}
                                                                    {assign var='value_text' value=$fields_value[$input.name]}
                                                                {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                                    <div class="input-group{if isset($input.class)} {$input.class|escape:'html':'UTF-8'}{/if}">
                                                                        {/if}
                                                                        {if isset($input.maxchar) && $input.maxchar}
                                                                            <span id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}_counter" class="input-group-addon"><span class="text-count-down">{$input.maxchar|intval}</span></span>
                                                                        {/if}
                                                                        {if isset($input.prefix)}
                                                                            <span class="input-group-addon">{$input.prefix|escape:'quotes'}</span>
                                                                        {/if}
                                                                        <input type="text"
                                                                               name="{$input.name|escape:'html':'UTF-8'}"
                                                                               id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"
                                                                               value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                                               class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"
                                                                                {if isset($input.size)} size="{$input.size|intval}"{/if}
                                                                                {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                                                {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                                                {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                                                {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                                                {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                                                {if isset($input.required) && $input.required } required="required" {/if}
                                                                                {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder|escape:'html':'UTF-8'}"{/if}
                                                                        />
                                                                        {if isset($input.suffix)}
                                                                            <span class="input-group-addon">{$input.suffix|escape:'quotes'}</span>
                                                                        {/if}
                                                                        {if isset($input.maxchar) || isset($input.prefix) || isset($input.suffix)}
                                                                    </div>
                                                                {/if}
                                                                {if isset($input.maxchar) && $input.maxchar}
                                                                    <script type="text/javascript">
                                                                        $(document).ready(function(){
                                                                            countDown($("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"), $("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}_counter"));
                                                                        });
                                                                    </script>
                                                                {/if}
                                                                {/if}
                                                                {elseif $input.type == 'range'}
                                                                    {assign var='value_range' value=$fields_value[$input.name]}
                                                                    <div class="input-group{if isset($input.class)} {$input.class|escape:'html':'UTF-8'}{/if}">
                                                                        <input type="range"
                                                                               name="{$input.name|escape:'html':'UTF-8'}"
                                                                               id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"
                                                                               class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"
                                                                               value="{$value_range|intval}"
                                                                                {if isset($input.min)} min="{$input.min|intval}"{/if}
                                                                                {if isset($input.max)} max="{$input.max|intval}"{/if}
                                                                                {if isset($input.step)} step="{$input.step|intval}"{/if}
                                                                                {if isset($input.disabled)} disabled{/if}
                                                                        />
                                                                    </div>
                                                                {elseif $input.type == 'inputwithselect' && isset($input.select)}
                                                                    {assign var='value_text' value=$fields_value[$input.name]}
                                                                    {assign var='value_selected' value=''}
                                                                    {assign var='name_selected' value =''}

                                                                    {if isset($fields_value[$input.select.name])}
                                                                        {assign var='value_selected' value=$fields_value[$input.select.name]}
                                                                    {elseif isset($input.select.default_id)}
                                                                        {assign var='value_selected' value=$input.select.default_id}
                                                                    {/if}

                                                                    {if isset($input.select.options.query[$value_selected][$input.select.options.name])}
                                                                        {assign var='name_selected' value=$input.select.options.query[$value_selected][$input.select.options.name]}
                                                                    {/if}
                                                                    <div class="input-group{if isset($input.class)} {$input.class|escape:'html':'UTF-8'}{/if}">

                                                                        {if !isset($input.select.after) || !$input.select.after}
                                                                            <div class="input-group-btn">
                                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    <span class="js-inputwithselect-label">{if $name_selected}{$name_selected|escape:'html':'UTF-8'}{else}{l s='Select' mod='rcpganalytics'}{/if}</span>
                                                                                    <span class="caret"></span>
                                                                                    <input type="hidden"
                                                                                           name="{$input.select.name|escape:'html':'UTF-8'}"
                                                                                           id="{if isset($input.select.id)}{$input.select.id|escape:'html':'UTF-8'}{else}{$input.select.name|escape:'html':'UTF-8'}{/if}"
                                                                                           value="{$value_selected|escape:'html':'UTF-8'}"
                                                                                    >
                                                                                </button>
                                                                                <ul class="dropdown-menu js-inputwithselect-menu">
                                                                                    {foreach $input.select.options.query as $option}
                                                                                        {if $option === "-"}
                                                                                            <li role="separator" class="divider"></li>
                                                                                        {else}
                                                                                            <li class="js-inputwithselect-option"
                                                                                                data-value="{$option[$input.select.options.id]|intval}"
                                                                                                    {if $option[$input.select.options.id] == $value_selected}
                                                                                                        data-selected="true"
                                                                                                    {/if}
                                                                                            >
                                                                                                <a href="#">{$option[$input.select.options.name]|escape:'html':'UTF-8'}</a>
                                                                                            </li>
                                                                                        {/if}
                                                                                    {/foreach}
                                                                                </ul>
                                                                            </div>
                                                                        {/if}
                                                                        {if isset($input.maxchar) && $input.maxchar}
                                                                            <span id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}_counter" class="input-group-addon"><span class="text-count-down">{$input.maxchar|intval}</span></span>
                                                                        {/if}
                                                                        {if isset($input.prefix)}
                                                                            <span class="input-group-addon">{$input.prefix|escape:'quotes'}</span>
                                                                        {/if}
                                                                        <input {if isset($input.input_type) && $input.input_type === 'number'}type="number"{else}type="text"{/if}
                                                                               name="{$input.name|escape:'html':'UTF-8'}"
                                                                               id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"
                                                                               value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                                               class="js-inputwithselect-input form-control{if isset($input.class)} {$input.class|escape:'html':'UTF-8'}{/if}"
                                                                                {if isset($input.step)} step="{$input.step|intval}"{/if}
                                                                                {if isset($input.min)} min="{$input.min|intval}"{/if}
                                                                                {if isset($input.max)} max="{$input.max|intval}"{/if}
                                                                                {if isset($input.size)} size="{$input.size|intval}"{/if}
                                                                                {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                                                {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                                                {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                                                {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                                                {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                                                {if isset($input.required) && $input.required } required="required" {/if}
                                                                                {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder|escape:'html':'UTF-8'}"{/if}
                                                                        />
                                                                        {if isset($input.suffix)}
                                                                            <span class="input-group-addon">{$input.suffix|escape:'quotes'}</span>
                                                                        {/if}
                                                                        {if isset($input.select.after) && $input.select.after}
                                                                            <div class="input-group-btn">
                                                                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                                    <span class="js-inputwithselect-label">{if $name_selected}{$name_selected|escape:'html':'UTF-8'}{else}{l s='Select' mod='rcpganalytics'}{/if}</span>
                                                                                    <span class="caret"></span>
                                                                                    <input type="hidden"
                                                                                           name="{$input.select.name|escape:'html':'UTF-8'}"
                                                                                           id="{if isset($input.select.id)}{$input.select.id|escape:'html':'UTF-8'}{else}{$input.select.name|escape:'html':'UTF-8'}{/if}"
                                                                                           value="{$value_selected|escape:'html':'UTF-8'}"
                                                                                    >
                                                                                </button>
                                                                                <ul class="dropdown-menu js-inputwithselect-menu">
                                                                                    {foreach $input.select.options.query as $option}
                                                                                        {if $option === "-"}
                                                                                            <li role="separator" class="divider"></li>
                                                                                        {else}
                                                                                            <li class="js-inputwithselect-option"
                                                                                                data-value="{$option[$input.select.options.id]|intval}"
                                                                                                    {if $option[$input.select.options.id] == $value_selected}
                                                                                                        data-selected="true"
                                                                                                    {/if}
                                                                                            >
                                                                                                <a href="#">{$option[$input.select.options.name]|escape:'html':'UTF-8'}</a>
                                                                                            </li>
                                                                                        {/if}
                                                                                    {/foreach}
                                                                                </ul>
                                                                            </div>
                                                                        {/if}
                                                                    </div>
                                                                {if isset($input.maxchar) && $input.maxchar}
                                                                    <script type="text/javascript">
                                                                        $(document).ready(function(){
                                                                            countDown($("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"), $("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}_counter"));
                                                                        });
                                                                    </script>
                                                                {/if}
                                                                {elseif $input.type == 'select'}
                                                                {if isset($input.options.query) && !$input.options.query && isset($input.empty_message)}
                                                                    {$input.empty_message|escape:'html':'UTF-8'}
                                                                    {$input.required = false}
                                                                    {$input.desc = null}
                                                                {else}
                                                                    <select name="{$input.name|escape:'html':'utf-8'}"
                                                                            class="{if isset($input.class)}{$input.class|escape:'html':'utf-8'}{/if} fixed-width-xl"
                                                                            id="{if isset($input.id)}{$input.id|escape:'html':'utf-8'}{else}{$input.name|escape:'html':'utf-8'}{/if}"
                                                                            {if isset($input.multiple) && $input.multiple} multiple="multiple"{/if}
                                                                            {if isset($input.size)} size="{$input.size|intval}"{/if}
                                                                            {if isset($input.onchange)} onchange="{$input.onchange|escape:'html':'utf-8'}"{/if}
                                                                            {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                                    >
                                                                        {if isset($input.options.default)}
                                                                            <option value="{$input.options.default.value|escape:'html':'utf-8'}">{$input.options.default.label|escape:'html':'utf-8'}</option>
                                                                        {/if}
                                                                        {if isset($input.options.optiongroup)}
                                                                            {foreach $input.options.optiongroup.query AS $optiongroup}
                                                                                <optgroup label="{$optiongroup[$input.options.optiongroup.label]|escape:'html':'UTF-8'}">
                                                                                    {foreach $optiongroup[$input.options.options.query] as $option}
                                                                                        <option value="{$option[$input.options.options.id]|escape:'html':'UTF-8'}"
                                                                                                {if isset($input.multiple)}
                                                                                                    {foreach $fields_value[$input.name] as $field_value}
                                                                                                        {if $field_value == $option[$input.options.options.id]}selected="selected"{/if}
                                                                                                    {/foreach}
                                                                                                {else}
                                                                                                    {if $fields_value[$input.name] == $option[$input.options.options.id]}selected="selected"{/if}
                                                                                                {/if}
                                                                                        >{$option[$input.options.options.name]|escape:'html':'UTF-8'}</option>
                                                                                    {/foreach}
                                                                                </optgroup>
                                                                            {/foreach}
                                                                        {else}
                                                                            {foreach $input.options.query AS $option}
                                                                                {if is_object($option)}
                                                                                    <option value="{$option->$input.options.id|escape:'html':'UTF-8'}"
                                                                                            {if isset($input.multiple)}
                                                                                                {foreach $fields_value[$input.name] as $field_value}
                                                                                                    {if $field_value == $option->$input.options.id}
                                                                                                        selected="selected"
                                                                                                    {/if}
                                                                                                {/foreach}
                                                                                            {else}
                                                                                                {if $fields_value[$input.name] == $option->$input.options.id}
                                                                                                    selected="selected"
                                                                                                {/if}
                                                                                            {/if}
                                                                                    >{$option->$input.options.name|escape:'html':'UTF-8'}</option>
                                                                                {elseif $option == "-"}
                                                                                    <option value="">-</option>
                                                                                {else}
                                                                                    <option value="{$option[$input.options.id]|escape:'html':'UTF-8'}"
                                                                                            {if isset($input.multiple)}
                                                                                                {foreach $fields_value[$input.name] as $field_value}
                                                                                                    {if $field_value == $option[$input.options.id]}
                                                                                                        selected="selected"
                                                                                                    {/if}
                                                                                                {/foreach}
                                                                                            {else}
                                                                                                {if $fields_value[$input.name] == $option[$input.options.id]}
                                                                                                    selected="selected"
                                                                                                {/if}
                                                                                            {/if}
                                                                                    >{$option[$input.options.name]|escape:'html':'UTF-8'}</option>

                                                                                {/if}
                                                                            {/foreach}
                                                                        {/if}
                                                                    </select>
                                                                {/if}
                                                                {elseif $input.type == 'radio'}
                                                                {foreach $input.values as $value}
                                                                    <div class="radio {if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}">
                                                                        {strip}
                                                                            <label>
                                                                                <input type="radio"	name="{$input.name|escape:'html':'UTF-8'}" id="{$value.id|escape:'html':'UTF-8'}" value="{$value.value|escape:'html':'UTF-8'}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
                                                                                {$value.label|escape:'html':'UTF-8'}
                                                                            </label>
                                                                        {/strip}
                                                                    </div>
                                                                {if isset($value.p) && $value.p}<p class="help-block">{$value.p|escape:'html':'UTF-8'}</p>{/if}
                                                                {/foreach}
                                                                {elseif $input.type == 'switch'}
                                                                    <span class="switch prestashop-switch fixed-width-lg">
                                                                        {foreach $input.values as $value}
                                                                            <input type="radio" name="{$input.name|escape:'html':'UTF-8'}"{if $value.value == 1} id="{$input.name|escape:'html':'UTF-8'}_on"{else} id="{$input.name|escape:'html':'UTF-8'}_off"{/if} value="{$value.value|escape:'html':'UTF-8'}"{if $fields_value[$input.name] == $value.value} checked="checked"{/if}{if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}/>
                                                                            {strip}
                                                                            <label {if $value.value == 1} for="{$input.name|escape:'html':'UTF-8'}_on"{else} for="{$input.name|escape:'html':'UTF-8'}_off"{/if}>
                                                                                    {if $value.value == 1}
                                                                                        {l s='Yes' mod='rcpganalytics'}
                                                                                    {else}
                                                                                        {l s='No' mod='rcpganalytics'}
                                                                                    {/if}
                                                                                </label>
                                                                        {/strip}
                                                                        {/foreach}
                                                                        <a class="slide-button btn"></a>
                                                                    </span>
                                                                {elseif $input.type == 'textbutton'}
                                                                    {assign var='value_text' value=$fields_value[$input.name]}
                                                                    <div class="row">
                                                                        <div class="col-lg-9">
                                                                            {if isset($input.maxchar)}
                                                                            <div class="input-group">
                                                                                    <span id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}_counter" class="input-group-addon">
                                                                                        <span class="text-count-down">{$input.maxchar|intval}</span>
                                                                                    </span>
                                                                                {/if}
                                                                                <input type="text"
                                                                                       name="{$input.name|escape:'html':'UTF-8'}"
                                                                                       id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"
                                                                                       value="{if isset($input.string_format) && $input.string_format}{$value_text|string_format:$input.string_format|escape:'html':'UTF-8'}{else}{$value_text|escape:'html':'UTF-8'}{/if}"
                                                                                       class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}{if $input.type == 'tags'} tagify{/if}"
                                                                                        {if isset($input.size)} size="{$input.size|intval}"{/if}
                                                                                        {if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}
                                                                                        {if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}
                                                                                        {if isset($input.readonly) && $input.readonly} readonly="readonly"{/if}
                                                                                        {if isset($input.disabled) && $input.disabled} disabled="disabled"{/if}
                                                                                        {if isset($input.autocomplete) && !$input.autocomplete} autocomplete="off"{/if}
                                                                                        {if isset($input.placeholder) && $input.placeholder } placeholder="{$input.placeholder|escape:'html':'UTF-8'}"{/if}
                                                                                />
                                                                                {if isset($input.suffix)}{$input.suffix|escape:'html':'UTF-8'}{/if}
                                                                            {if isset($input.maxchar) && $input.maxchar}
                                                                            </div>
                                                                            {/if}
                                                                        </div>
                                                                        <div class="col-lg-2">
                                                                            <button type="button" class="btn btn-default{if isset($input.button.attributes['class'])} {$input.button.attributes['class']|escape:'html':'UTF-8'}{/if}{if isset($input.button.class)} {$input.button.class|escape:'html':'UTF-8'}{/if}"
                                                                            {if isset($input.button.attributes)}
                                                                            {foreach from=$input.button.attributes key=name item=value}
                                                                                {if $name|lower != 'class'}
                                                                                    {$name|escape:'html':'UTF-8'}="{$value|escape:'html':'UTF-8'}"
                                                                                {/if}
                                                                            {/foreach}
                                                                            {/if}>
                                                                            {$input.button.label|escape:'html':'UTF-8'}
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                {if isset($input.maxchar) && $input.maxchar}
                                                                    <script type="text/javascript">
                                                                        $(document).ready(function() {
                                                                            countDown($("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"), $("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}_counter"));
                                                                        });
                                                                    </script>
                                                                {/if}
                                                                {elseif $input.type == 'textarea'}
                                                                {if isset($input.maxchar) && $input.maxchar}<div class="input-group">{/if}
                                                                    {assign var=use_textarea_autosize value=true}
                                                                    {if isset($input.lang) && $input.lang}
                                                                    {foreach $languages as $language}
                                                                    {if $languages|count > 1}
                                                                        <div class="form-group translatable-field lang-{$language.id_lang|intval}"{if $language.id_lang != $defaultFormLanguage} style="display:none;"{/if}>
                                                                        <div class="col-lg-9">
                                                                    {/if}
                                                                        {if isset($input.maxchar) && $input.maxchar}
                                                                            <span id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}_{$language.id_lang|intval}{else}{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}{/if}_counter" class="input-group-addon">
                                                                                        <span class="text-count-down">{$input.maxchar|intval}</span>
                                                                                    </span>
                                                                        {/if}
                                                                        <textarea{if isset($input.readonly) && $input.readonly} readonly="readonly"{/if} name="{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}" id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}_{$language.id_lang|intval}" class="{if isset($input.autoload_rte) && $input.autoload_rte}rte autoload_rte{else}textarea-autosize{/if}{if isset($input.class)} {$input.class|escape:'html':'UTF-8'}{/if}"{if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}{if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}>{$fields_value[$input.name][$language.id_lang]|escape:'html':'UTF-8'}</textarea>
                                                                    {if $languages|count > 1}
                                                                        </div>
                                                                        <div class="col-lg-2">
                                                                            <button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
                                                                                {$language.iso_code|escape:'html':'UTF-8'}
                                                                                <span class="caret"></span>
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                {foreach from=$languages item=language}
                                                                                    <li>
                                                                                        <a href="javascript:hideOtherLanguage({$language.id_lang|intval});" tabindex="-1">{$language.name|escape:'html':'UTF-8'}</a>
                                                                                    </li>
                                                                                {/foreach}
                                                                            </ul>
                                                                        </div>
                                                                        </div>
                                                                    {/if}
                                                                    {/foreach}
                                                                    {if isset($input.maxchar) && $input.maxchar}
                                                                        <script type="text/javascript">
                                                                            $(document).ready(function(){
                                                                                {foreach from=$languages item=language}
                                                                                countDown($("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}_{$language.id_lang|intval}{else}{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}{/if}"), $("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}_{$language.id_lang|intval}{else}{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}{/if}_counter"));
                                                                                {/foreach}
                                                                            });
                                                                        </script>
                                                                    {/if}
                                                                    {else}
                                                                    {if isset($input.maxchar) && $input.maxchar}
                                                                        <span id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}_{$language.id_lang|intval}{else}{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}{/if}_counter" class="input-group-addon">
                                                                                    <span class="text-count-down">{$input.maxchar|intval}</span>
                                                                                </span>
                                                                    {/if}
                                                                        <textarea{if isset($input.readonly) && $input.readonly} readonly="readonly"{/if} name="{$input.name|escape:'html':'UTF-8'}" id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}" {if isset($input.cols)}cols="{$input.cols}"{/if} {if isset($input.rows)}rows="{$input.rows}"{/if} class="{if isset($input.autoload_rte) && $input.autoload_rte}rte autoload_rte{else}textarea-autosize{/if}{if isset($input.class)} {$input.class|escape:'html':'UTF-8'}{/if}"{if isset($input.maxlength) && $input.maxlength} maxlength="{$input.maxlength|intval}"{/if}{if isset($input.maxchar) && $input.maxchar} data-maxchar="{$input.maxchar|intval}"{/if}>{$fields_value[$input.name]|escape:'html':'UTF-8'}</textarea>
                                                                    {if isset($input.maxchar) && $input.maxchar}
                                                                        <script type="text/javascript">
                                                                            $(document).ready(function(){
                                                                                countDown($("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"), $("#{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}_counter"));
                                                                            });
                                                                        </script>
                                                                    {/if}
                                                                    {/if}
                                                                    {if isset($input.maxchar) && $input.maxchar}</div>{/if}
                                                                {elseif $input.type == 'checkbox'}
                                                                {if isset($input.expand)}
                                                                    <a class="btn btn-default show_checkbox{if strtolower($input.expand.default) == 'hide'} hidden{/if}" href="#">
                                                                        <i class="icon-{$input.expand.show.icon|escape:'html':'UTF-8'}"></i>
                                                                        {$input.expand.show.text|escape:'html':'UTF-8'}
                                                                        {if isset($input.expand.print_total) && $input.expand.print_total > 0}
                                                                            <span class="badge">{$input.expand.print_total|intval}</span>
                                                                        {/if}
                                                                    </a>
                                                                    <a class="btn btn-default hide_checkbox{if strtolower($input.expand.default) == 'show'} hidden{/if}" href="#">
                                                                        <i class="icon-{$input.expand.hide.icon|escape:'html':'UTF-8'}"></i>
                                                                        {$input.expand.hide.text|escape:'html':'UTF-8'}
                                                                        {if isset($input.expand.print_total) && $input.expand.print_total > 0}
                                                                            <span class="badge">{$input.expand.print_total|intval}</span>
                                                                        {/if}
                                                                    </a>
                                                                {/if}
                                                                {foreach $input.values.query as $value}
                                                                    {assign var=id_checkbox value=$input.name|cat:'_'|cat:$value[$input.values.id]}
                                                                    <div class="checkbox{if isset($input.expand) && strtolower($input.expand.default) == 'show'} hidden{/if}">
                                                                        {strip}
                                                                            <label for="{$id_checkbox|escape:'html':'UTF-8'}">
                                                                                <input type="checkbox" name="{$id_checkbox|escape:'html':'UTF-8'}" id="{$id_checkbox|escape:'html':'UTF-8'}" class="{if isset($input.class)}{$input.class|escape:'html':'UTF-8'}{/if}"{if isset($value.val)} value="{$value.val|escape:'html':'UTF-8'}"{/if}{if isset($fields_value[$id_checkbox]) && $fields_value[$id_checkbox]} checked="checked"{/if} />
                                                                                {$value[$input.values.name]|escape:'html':'UTF-8'}
                                                                            </label>
                                                                        {/strip}
                                                                    </div>
                                                                {/foreach}
                                                                {elseif $input.type == 'datetime'}
                                                                    <div class="row">
                                                                        <div class="input-group col-lg-4">
                                                                            <input
                                                                                    id="{if isset($input.id)}{$input.id|escape:'html':'UTF-8'}{else}{$input.name|escape:'html':'UTF-8'}{/if}"
                                                                                    type="text"
                                                                                    data-hex="true"{if isset($input.class)} class="{$input.class|escape:'html':'UTF-8'}"{else} class="datetimepicker"{/if}
                                                                                    name="{$input.name|escape:'html':'UTF-8'}"
                                                                                    value="{$fields_value[$input.name]|escape:'html':'UTF-8'}"
                                                                            />
                                                                            <span class="input-group-addon">
                                                                                <i class="icon-calendar-empty"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                {/if}
                                                            {/block}{* end block input *}
                                                            {block name="description"}
                                                                {if isset($input.desc) && !empty($input.desc)}
                                                                    <p class="help-block">
                                                                        {if is_array($input.desc)}
                                                                            {foreach $input.desc as $p}
                                                                                {if is_array($p)}
                                                                                    <span id="{$p.id|escape:'html':'UTF-8'}">{$p.text|escape:'html':'UTF-8'}</span><br />
                                                                                {else}
                                                                                    {$p|escape:'html':'UTF-8'}<br />
                                                                                {/if}
                                                                            {/foreach}
                                                                        {else}
                                                                            {$input.desc|escape:'html':'UTF-8'}
                                                                        {/if}
                                                                    </p>
                                                                {/if}
                                                            {/block}
                                                        </div>
                                                    {/block}{* end block field *}
                                                {/if}
                                            </div>
                                        {/block}{* end block input_row *}
                                    {/foreach}
                                    {hook h='displayAdminForm' fieldset=$f}
                                    {if isset($name_controller)}
                                        {capture name=hookName assign=hookName}display{$name_controller|ucfirst|escape:'html':'UTF-8'}Form{/capture}
                                        {hook h=$hookName fieldset=$f}
                                    {elseif isset($smarty.get.controller)}
                                        {capture name=hookName assign=hookName}display{$smarty.get.controller|ucfirst|htmlentities|escape:'html':'UTF-8'}Form{/capture}
                                        {hook h=$hookName fieldset=$f}
                                    {/if}
                                </div><!-- /.form-wrapper -->
                            {elseif $key == 'desc'}
                                <div class="alert alert-info col-lg-offset-3">
                                    {if is_array($field)}
                                        {foreach $field as $k => $p}
                                            {if is_array($p)}
                                                <span{if isset($p.id)} id="{$p.id|escape:'html':'UTF-8'}"{/if}>{$p.text|escape:'html':'UTF-8'}</span><br />
                                            {else}
                                                {$p|escape:'html':'UTF-8'}
                                                {if isset($field[$k+1])}<br />{/if}
                                            {/if}
                                        {/foreach}
                                    {else}
                                        {$field|escape:"html":"UTF-8"}
                                    {/if}
                                </div>
                            {/if}
                            {block name="other_input"}{/block}
                        {/foreach}
                        {block name="footer"}
                            {capture name='form_submit_btn'}{counter name='form_submit_btn'}{/capture}
                            {if isset($fieldset['form']['submit']) || isset($fieldset['form']['buttons'])}
                                <div class="panel-footer">
                                    {if isset($fieldset['form']['submit']) && !empty($fieldset['form']['submit'])}
                                        <button type="submit" value="1"	id="{if isset($fieldset['form']['submit']['id'])}{$fieldset['form']['submit']['id']|escape:'html':'UTF-8'}{else}{$table|escape:'html':'UTF-8'}_form_submit_btn{/if}{if $smarty.capture.form_submit_btn > 1}_{($smarty.capture.form_submit_btn - 1)|intval}{/if}" name="{if isset($fieldset['form']['submit']['name'])}{$fieldset['form']['submit']['name']}{else}{$submit_action|escape:'html':'UTF-8'}{/if}{if isset($fieldset['form']['submit']['stay']) && $fieldset['form']['submit']['stay']}AndStay{/if}" class="{if isset($fieldset['form']['submit']['class'])}{$fieldset['form']['submit']['class']}{else}btn btn-default pull-right{/if}">
                                            <i class="{if isset($fieldset['form']['submit']['icon'])}{$fieldset['form']['submit']['icon']|escape:'html':'UTF-8'}{else}process-icon-save{/if}"></i> {$fieldset['form']['submit']['title']}
                                        </button>
                                    {/if}
                                    {if isset($show_cancel_button) && $show_cancel_button}
                                        <a href="{$back_url|escape:'html':'UTF-8'}" class="btn btn-default" onclick="window.history.back();">
                                            <i class="process-icon-cancel"></i> {l s='Cancel' mod='rcpganalytics'}
                                        </a>
                                    {/if}
                                    {if isset($fieldset['form']['reset'])}
                                        <button
                                                type="reset"
                                                id="{if isset($fieldset['form']['reset']['id'])}{$fieldset['form']['reset']['id']|escape:'html':'UTF-8'}{else}{$table|escape:'html':'UTF-8'}_form_reset_btn{/if}"
                                                class="{if isset($fieldset['form']['reset']['class'])}{$fieldset['form']['reset']['class']|escape:'html':'UTF-8'}{else}btn btn-default{/if}"
                                        >
                                            {if isset($fieldset['form']['reset']['icon'])}<i class="{$fieldset['form']['reset']['icon']|escape:'html':'UTF-8'}"></i> {/if} {$fieldset['form']['reset']['title']}
                                        </button>
                                    {/if}
                                    {if isset($fieldset['form']['buttons'])}
                                        {foreach from=$fieldset['form']['buttons'] item=btn key=k}
                                            {if isset($btn.href) && trim($btn.href) != ''}
                                                <a href="{$btn.href|escape:'html':'UTF-8'}" {if isset($btn['id'])}id="{$btn['id']|escape:'html':'UTF-8'}"{/if} class="btn btn-default{if isset($btn['class'])} {$btn['class']|escape:'html':'UTF-8'}{/if}" {if isset($btn.js) && $btn.js} onclick="{$btn.js}"{/if}>{if isset($btn['icon'])}<i class="{$btn['icon']|escape:'html':'UTF-8'}" ></i> {/if}{$btn.title|escape:'html':'UTF-8'}</a>
                                            {else}
                                                <button type="{if isset($btn['type'])}{$btn['type']|escape:'html':'UTF-8'}{else}button{/if}" {if isset($btn['id'])}id="{$btn['id']|escape:'html':'UTF-8'}"{/if} class="btn btn-default{if isset($btn['class'])} {$btn['class']|escape:'html':'UTF-8'}{/if}" name="{if isset($btn['name'])}{$btn['name']|escape:'html':'UTF-8'}{else}submitOptions{$table|escape:'html':'UTF-8'}{/if}"{if isset($btn.js) && $btn.js} onclick="{$btn.js|escape:'html':'UTF-8'}"{/if}>{if isset($btn['icon'])}<i class="{$btn['icon']|escape:'html':'UTF-8'}" ></i> {/if}{$btn.title|escape:'html':'UTF-8'}</button>
                                            {/if}
                                        {/foreach}
                                    {/if}
                                </div>
                            {/if}
                        {/block}{* end block footer *}
                    </div>
                    {if isset($vertical_tabs) && isset($vertical_tabs.form[$f])}
                        </div>
                    {/if}
                {/block}{* end block fieldset *}
                {block name="other_fieldsets"}{/block}
            {/foreach}
        </form>
    </div>
{/block}