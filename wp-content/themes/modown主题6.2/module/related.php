<?php
$exclude_id = $post->ID; 
$limit = _MBT('post_related_num')?_MBT('post_related_num'):'6';
$posttags = get_the_tags();$tags = '';
if ( $posttags ) { 
    $tags = ''; foreach ( $posttags as $tag ) $tags .= $tag->term_id . ',';
}
$cats = ''; foreach ( get_the_category() as $cat ) $cats .= $cat->cat_ID . ',';


$cat_class = 'grids';
$style = _MBT('list_style');if($style == 'list') $cat_class = 'lists cols-two';

$default_width = 285;
if(_MBT('list_column') == 'five-mini'){
    $default_width = 228;
}
$related_js = '';
if(_MBT('post_related_in') == 'tag' && $tags){
    $args = array(
        'tag__in'        => explode(',', $tags), 
        'post__not_in'        => explode(',', $exclude_id),
        'ignore_sticky_posts' => 1,
        'posts_per_page'      => $limit
    );
    if(_MBT('timthumb_height')){
        $bili = round(_MBT('timthumb_height')/$default_width,4);
        $related_js = '<script>var relateImgWidth = jQuery(".single-related .grids .grid .img").width();jQuery(".single-related .grids .grid .img").height(relateImgWidth*'.$bili.');</script>';
    }
}elseif(_MBT('post_related_in') == 'all'){

    $cat_ID = get_the_category()[0]->cat_ID;
    $style = 'grid'; $cat_class = 'grids';
    $style = get_term_meta($cat_ID,'style',true);
    if($style == 'list') $cat_class = 'lists cols-two';
    elseif($style == 'grid') $cat_class = 'grids';
    elseif($style == 'grid-audio') $cat_class = 'grids';
    else{
        $style = _MBT('list_style');if($style == 'list') $cat_class = 'lists cols-two';
    }

    if($tags){
        $args = array(
            'category__in'        => explode(',', $cats), 
            'tag__in'        => explode(',', $tags), 
            'post__not_in'        => explode(',', $exclude_id),
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $limit
        );
        if(_MBT('timthumb_height')){
            $bili = round(_MBT('timthumb_height')/$default_width,4);
            $related_js = '<script>var relateImgWidth = jQuery(".single-related .grids .grid .img").width();jQuery(".single-related .grids .grid .img").height(relateImgWidth*'.$bili.');</script>';
        }
    }else{
        $args = array(
            'category__in'        => explode(',', $cats), 
            'post__not_in'        => explode(',', $exclude_id),
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => $limit
        );
        $category = get_the_category();
        $cat_height = get_term_meta($category[0]->term_id,'timthumb_height',true);
        if($cat_height){
            $bili = round($cat_height/$default_width,4);
            $related_js = '<script>var relateImgWidth = jQuery(".single-related .grids .grid .img").width();jQuery(".single-related .grids .grid .img").height(relateImgWidth*'.$bili.');</script>';
        }elseif(_MBT('timthumb_height')){
            $bili = round(_MBT('timthumb_height')/$default_width,4);
            $related_js = '<script>var relateImgWidth = jQuery(".single-related .grids .grid .img").width();jQuery(".single-related .grids .grid .img").height(relateImgWidth*'.$bili.');</script>';
        }
    }
}else{

    $cat_ID = get_the_category()[0]->cat_ID;
    $style = 'grid'; $cat_class = 'grids';
    $style = get_term_meta($cat_ID,'style',true);
    if($style == 'list') $cat_class = 'lists cols-two';
    elseif($style == 'grid') $cat_class = 'grids';
    elseif($style == 'grid-audio') $cat_class = 'grids';
    else{
        $style = _MBT('list_style');if($style == 'list') $cat_class = 'lists cols-two';
    }

    $args = array(
        'category__in'        => explode(',', $cats), 
        'post__not_in'        => explode(',', $exclude_id),
        'ignore_sticky_posts' => 1,
        'posts_per_page'      => $limit
    );
    $category = get_the_category();
    $cat_height = get_term_meta($category[0]->term_id,'timthumb_height',true);
    if($cat_height){
        $bili = round($cat_height/$default_width,4);
        $related_js = '<script>var relateImgWidth = jQuery(".single-related .grids .grid .img").width();jQuery(".single-related .grids .grid .img").height(relateImgWidth*'.$bili.');</script>';
    }elseif(_MBT('timthumb_height')){
        $bili = round(_MBT('timthumb_height')/$default_width,4);
        $related_js = '<script>var relateImgWidth = jQuery(".single-related .grids .grid .img").width();jQuery(".single-related .grids .grid .img").height(relateImgWidth*'.$bili.');</script>';
    }
}
query_posts($args);
if(have_posts()) :
    echo '<div class="single-related"><h3 class="related-title"><i class="icon icon-related"></i> '.(_MBT('post_related_title')?_MBT('post_related_title'):'猜你喜欢').'</h3><div class="'.$cat_class.(_MBT('waterfall')?' waterfall':'').' clearfix">';
    $ccc = 'content';if($style == 'grid-audio') $ccc = 'content-audio';
    while( have_posts() ) :
        the_post();
        get_template_part( $ccc, get_post_format() );
    endwhile;
    echo '</div>'.$related_js.'</div>';
endif;
wp_reset_query();
