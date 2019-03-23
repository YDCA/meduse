<!-- BEGIN Wordpress Integration -->
{if !empty($blog_title)}
	<h1 class="h1 text-uppercase page-heading text-center text-sm-center wordpressintegration-blog-title">
		{$blog_title|escape:'htmlall':'UTF-8'}
	</h1>
{/if}
<div class="wordpressintegration-posts">
{foreach from=$blog_posts item=blog_post}
	<div class="wordpressintegration-post-container">
		<div class="wordpressintegration-post">
			{if $display_images}
			<a href="{$blog_post.link|escape:'htmlall':'UTF-8'}" class="wordpressintegration-post-image"
				style="background-image: url('{$blog_post.image|escape:'htmlall':'UTF-8'}')">
			</a>
			{/if}
			
			<h6 class="wordpressintegration-post-title">
				<a href="{$blog_post.link|escape:'htmlall':'UTF-8'}">{$blog_post.title|escape:'htmlall':'UTF-8'}</a>
			</h6>
			
			<div class="wordpressintegration-post-description">
				{$blog_post.description|strip_tags|truncate:$description_max_chars:"..."|escape:'htmlall':'UTF-8'}
				<a href="{$blog_post.link|escape:'htmlall':'UTF-8'}" class="wordpressintegration-post-readmore">
					{l s='Read more' mod='wordpressintegration'} &raquo;
				</a>
			</div>
		</div>
	</div>
{/foreach}
</div>
<div class="wordpressintegration-link-blog">
	<a href="{$blog_url|escape:'htmlall':'UTF-8'}" class="btn btn-primary">{l s='See all articles' mod='wordpressintegration'} &raquo;</a>
</div>
<!-- END Wordpress Integration -->