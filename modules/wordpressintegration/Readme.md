##############################################################################
## HOW-TO GUIDE

1. Install the module
2. Set up your blog URL in the module settings.
3. If you wish to display your blog posts' thumbnails, please add the 
following code at the end of your WordPress theme functions.php file :

	function add_rss_thumbnail() {
		global $post;
		if(has_post_thumbnail($post->ID))
		{
			$thumbnail = get_the_post_thumbnail_url($post->ID);
			echo("<image>{$thumbnail}</image>");
		}
	}
	add_action('rss2_item', 'add_rss_thumbnail');

4. Enjoy :)

For detailed instructions, read the the readme PDF.


##############################################################################
## CHANGELOG

------------------------------------------------------------------------------
Version	: v1.0.2
Date	: 03-22-2018

- Fixed an issue with translations on older Prestashop versions.
------------------------------------------------------------------------------
Version	: v1.0.1
Date	: 19-11-2018

- Added more informations for errors when displaying the feed
- Added cURL support for fetching the feed when allow_url_fopen is not enabled
------------------------------------------------------------------------------
Version	: v1.0.0
Date	: 26-09-2018

- First release
------------------------------------------------------------------------------