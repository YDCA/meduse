<script type="text/javascript">
jQuery(function ($) {
    "use strict";
	var categoriesCarousel = $(".categories-carousel2");		
	var rtl = false;
	if ($("body").hasClass("rtl")) rtl = true;				
	categoriesCarousel.owlCarousel({
		responsiveClass:true,
		responsive:{			
			1199:{
				items:4
			},
			991:{
				items:3
			},
			768:{
				items:2
			},
			318:{
				items:1
			}
		},
		rtl: rtl,
		margin:30,
	    nav: true,
	    dots: false,
		autoplay: false,
		loop: true,
	    rewindNav: false,
	    navigationText: ["", ""],
	    slideBy: false,
	    slideSpeed: 200	
	});
});
</script>
<div class="home_categories2">
    {if isset($categories) AND $categories}
            <div class="categories-carousel2">
            {foreach from=$categories item=category }
                {assign var='categoryLink' value=$link->getcategoryLink($category.id_category, $category.link_rewrite)}
					<div class="categories-wrapper">
						<div class="categoy-image">
							<a href="{$categoryLink}">
								<img src="{$img_cat_dir}{$category.id_category}_thumb.jpg" alt="{$category.name}" title="{$category.name}" class="img-responsive"/>
							</a>
						</div>
						<div class="category-info">
							<a class="cat-name" href="{$categoryLink}">{$category.name}</a>
						</div>
					</div>
            {/foreach}
            </div>
    {else}
        <p>{l s='No categories' mod='jmspagebuilder'}</p>
  {/if}
</div>