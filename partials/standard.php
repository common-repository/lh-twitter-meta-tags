<meta name="twitter:card" content="summary" />
<meta name="twitter:title" content="<?php single_post_title(''); ?>" />
<meta name="twitter:description" content="<?php if (!empty(get_the_content())){ echo self::truncate_string(get_the_content(), "140");  } elseif (!empty(get_the_excerpt())){  echo self::truncate_string(get_the_excerpt(), "140"); } ?>" />
<meta name="twitter:url" content="<?php the_permalink(); ?>" />
<?php

if (get_option(self::return_site_opt_name())){

?>
<meta name="twitter:site" content="<?php echo get_option(self::return_site_opt_name());  ?>" />
<?php
}

if (get_the_ID() && wp_get_attachment_thumb_url(get_post_thumbnail_id(get_the_ID()))){


?>
<meta name="twitter:image" content="<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'lh_twitter_meta_tags-thumbnail'); echo $image[0]; ?>" />
<?php

}