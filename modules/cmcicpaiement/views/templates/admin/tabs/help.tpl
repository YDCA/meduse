{* Start FAQ block *}
<div class="faq tab-pane" id="help">
    {* Tittle *}
    <div class="faq-header">
        {l s='FAQ' mod='expeditor'}
    </div>
    {* Content *}
    <div class="faq-content">
        <ul class="accordion">
            {* faq category *}
            {foreach from=$apifaq item=categorie}
                <li>
                    <span class="toggleFaq titleFaq">{$categorie->title|escape:'htmlall':'UTF-8'}<i class="fa fa-chevron-right caretRight"></i></span>
                    <ul class=innerFaq>
                        {* faq question *}
                        {foreach from=$categorie->blocks item=QandA}
                            <li>
                                <span href="#" class="toggleFaq questionFaq"><i class="fa fa-caret-right caretLeft"></i>{$QandA->question|escape:'htmlall':'UTF-8'}</span>
                                <div class="innerFaq answerFaq">
                                    {* faq answer *}
                                    <p>{$QandA->answer|escape:'htmlall':'UTF-8'|replace:"\n":"<br />"}</p>
                                </div>
                            </li>
                        {/foreach}
                    </ul>
                </li>
            {/foreach}
        </ul>
    </div>
</div>
{* End FAQ block *}
