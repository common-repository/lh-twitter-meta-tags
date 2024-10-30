<meta name="twitter:card" content="gallery" />
<meta name="twitter:title" content="<?php single_post_title(''); ?>" />
<meta name="twitter:description" content="<?php echo self::text_content($the_post_object); ?>" />
<meta name="twitter:url" content="<?php the_permalink(); ?>" />
<?php

if (get_option(self::return_site_opt_name())){

?>
<meta name="twitter:site" content="<?php echo get_option(self::return_site_opt_name());  ?>" />
<?php
}


$post_thumbnail_id = get_post_thumbnail_id( $the_post_object->ID );

if (wp_get_attachment_thumb_url($post_thumbnail_id)){

?>
<meta name="twitter:image0" content="<?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($the_post_object->ID), 'lh_twitter_meta_tags-thumbnail'); echo $image[0]; ?>" />
<?php

}

$images = get_children("post_type=attachment&post_mime_type=image&numberposts=3&post_parent=".$the_post_object->ID."&exclude=".$post_thumbnail_id);

$iterator = 1;


foreach($images as $image) {


$image_tag = wp_get_attachment_image_src($image->ID, 'lh_twitter_meta_tags-thumbnail' );

echo "<meta name=\"twitter:image".$iterator."\" content=\"".$image_tag[0]."\" />\n";

$iterator++;


}