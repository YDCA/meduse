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
{if !isset($content_only) || !$content_only}
    {if isset($optimize_id, $optimize_class_name) && $optimize_id && $optimize_class_name}
        <!-- Google Optimize Page Hiding-->
        <style>.{$optimize_class_name|escape:"html":"UTF-8"} {ldelim}opacity: 0 !important{rdelim} </style>
        <script data-keepinline>
            {literal}
            (function(a,s,y,n,c,h,i,d,e){
                s.className+=' '+y;h.start=1*new Date;
                h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
                (a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);
                h.timeout=c;
            })
            {/literal}
            (window,document.documentElement,'{$optimize_class_name|escape:"html":"UTF-8"}','dataLayer',{$optimize_time_out|intval},{ldelim}'{$optimize_id|escape:"html":"UTF-8"}':true{rdelim});
        </script>
    {/if}
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src='https://www.googletagmanager.com/gtag/js?id={$analytics_id|escape:"html":"UTF-8"}' data-keepinline></script>
    <script data-keepinline>
        {literal}
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        {/literal}
    </script>
{/if}
{if isset($content_only) && $content_only}
    <script type="text/javascript">
        // Initialize all user events when DOM ready
        document.addEventListener('DOMContentLoaded', initQuickViewEvents, false);

        function initQuickViewEvents(event) {
            window.top.rcAnalyticsEvents.eventProductView(event);

            productAttributesNode = document.querySelector('#attributes');
            if (productAttributesNode) {
                productAttributesNode.addEventListener('click', window.top.rcAnalyticsEvents.eventProductView, false);
            }

            document.body.addEventListener('click', window.top.rcAnalyticsEvents.eventAddCartProductView, false);

            if (window.top.rcAnalyticsEvents.trackingFeatures.goals.socialAction) {
                document.body.addEventListener('click', window.top.rcAnalyticsEvents.eventSocialShareProductView, false);
            }
            if (window.top.rcAnalyticsEvents.trackingFeatures.goals.wishList) {
                document.body.addEventListener('click', window.top.rcAnalyticsEvents.eventWishListProductView, false);
            }
        }
    </script>
{/if}