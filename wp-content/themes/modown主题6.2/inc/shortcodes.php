<?php
add_shortcode('moad','MBThemes_shortcode_ad');
function MBThemes_shortcode_ad($atts){
  $atts = shortcode_atts( array(
    'bgcolor' => '',
    'img' => '',
    'fullwidth' => 0,
    'mb' => '',
    'link' => '',
  ), $atts, 'moad' );
  $style = '';
  if($atts['bgcolor']){
    $style .= 'background-color:'.$atts['bgcolor'].';';
  }
  if($atts['mb'] != ''){
    $style .= 'margin-bottom:'.$atts['mb'].'px;';
  }
  if($atts['fullwidth']){
    $html = '<div class="moad" style="'.$style.'"><a href="'.$atts['link'].'" target="_blank"><img src="'.$atts['img'].'"></a></div>';
  }else{
    $html = '<div class="moad" style="'.$style.'"><div class="container"><a href="'.$atts['link'].'" target="_blank"><img src="'.$atts['img'].'"></a></div></div>';
  }
  return $html;
}

add_shortcode('mocat','MBThemes_shortcode_cat');
function MBThemes_shortcode_cat($atts){
  global $post_target;
	$atts = shortcode_atts( array(
    'id' => '',
    'ids' => '',
    'num' => 8,
    'title' => '',
    'desc' => '',
    'more' => 1,
    'new' => 0,
    'recommend' => 0,
    'sticky' => 0,
    'orderby' => 'date',
    'text' => '查看更多',
    'link' => '',
    'child' => 0,
    'child-num' => 5,
    'water' => 0,
    'cols' => 3,
    'style' => ''
  ), $atts, 'mocat' );
  $title = $atts['title']?$atts['title']:get_cat_name($atts['id']);
  $css = '';
  $cat_class = 'grids';
  $style = _MBT('list_style');
  if($style == 'list') $cat_class = 'lists';
  if($atts['id']) {
    $category = get_term( $atts['id'], 'category' );
    $moid = ' id="mocat-'.$atts['id'].'"';
    $style = get_term_meta($atts['id'],'style',true);
    if($atts['style']) $style = $atts['style'];
    if($style == 'list') $cat_class = 'lists';
    elseif($style == 'grid') $cat_class = 'grids';
    elseif($style == 'grid-audio') $cat_class = 'grids';
    if(!$atts['water']){
      $timthumb_height = get_term_meta($atts['id'],'timthumb_height',true);
      if($timthumb_height){
          $css = '<style>#mocat-'.$atts['id'].' .grids .grid .img{height: '.$timthumb_height.'px;}
            @media (max-width: 1230px){
              #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285)).'px;}
            }
            @media (max-width: 1024px){
              #mocat-'.$atts['id'].' .grids .grid .img{height: '.$timthumb_height.'px;}
            }
            @media (max-width: 925px){
              #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285)).'px;}
            }
            @media (max-width: 768px){
              #mocat-'.$atts['id'].' .grids .grid .img{height: '.$timthumb_height.'px;}
            }
            @media (max-width: 620px){
              #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"232.5":(232.5*$timthumb_height/285)).'px;}
            }
            @media (max-width: 480px){
              #mocat-'.$atts['id'].' .grids .grid .img{height: '.(($timthumb_height=="285")?"180":(180*$timthumb_height/285)).'px;}
            }
            </style>';
      }
    }
  }else {
    $category = '';
    $moid = ' id="mocat-rand-'.rand(1000,9999).'"';
  }
  $html = '<div class="mocat'.($atts['water']?' water':'').'"'.$moid.'>'.$css.'<div class="container">';

  if(!$category && !$atts['title']){

  }else{
    $html .= '<h2><span>'.$title;
    if($atts['new']){
    	$html .= '<i>NEW</i>';
    }
    $html .= '</span></h2>';
  }

  if($atts['desc']){
    $html .= '<p class="desc">'.$atts['desc'].'</p>';
  }else{
    if($category){
      if($category->description) $html .= '<p class="desc">'.$category->description.'</p>';
    }
  }

  if($atts['child'] && $atts['id']){
  	$category = get_term_by('id',$atts['id'],'category');
  	$cat_childs = get_categories("parent=".$category->term_id."&hide_empty=1&depth=1");  
		if($cat_childs){
			$html .= '<ul class="child"><li><a href="javascript:;" class="active" data-c="'.$atts['id'].'" data-c2="0" data-num="'.$atts['num'].'">全部</a></li>';
			$i = 1;
			foreach ($cat_childs as $term) {
				if($i > $atts['child-num']) $html .= '';
				else $html .= '<li><a href="javascript:;" data-c="'.$atts['id'].'" data-c2="'.$term->term_id.'" data-num="'.$atts['num'].'">'.$term->name.'</a></li>';
				$i ++;
			}
			$html .= '</ul>';
		}
  }elseif(!$category && $atts['ids']){
    $html .= '<ul class="child"><li><a href="javascript:;" class="active" data-c="" data-c2="0" data-link="'.$atts['link'].'" data-num="'.$atts['num'].'">全部</a></li>';
    $cat_ids = explode(',', $atts['ids']);
    foreach ($cat_ids as $term_id) {
      $html .= '<li><a href="javascript:;" data-c="'.$term_id.'" data-c2="0" data-link="'.get_category_link($term_id).'" data-num="'.$atts['num'].'">'.get_term( $term_id, 'category' )->name.'</a></li>';
    }
    $html .= '</ul>';
  }

  if($atts['cols'] == 2){
    $cat_class .= ' cols-two';
  }

  if($atts['water']){
    $cat_class .= ' waterfall';
  }

  $html .= '<div class="'.$cat_class.' clearfix">';
  if($atts['id']){ 
    $args = array(
  		'cat'              => $atts['id'],
  		'showposts'        => $atts['num'],
      	'order'            => 'DESC'
  	);
  }else{
    $args = array(
      'showposts'        => $atts['num'],
      'category__not_in' => explode(',', _MBT('home_cats_exclude')),
      'order'            => 'DESC'
    );
  }

  if(!$atts['sticky']){
    $args['ignore_sticky_posts'] = 1;
  }

  if($atts['orderby'] == 'views'){
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = 'views';
  }if($atts['orderby'] == 'downs'){
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = 'down_times';
  }elseif($atts['orderby'] == 'comment'){
    $args['orderby'] = 'comment_count';
  }elseif($atts['orderby'] == 'rand'){
    $args['orderby'] = 'rand';
  }else{
    $args['orderby'] = $atts['orderby'];
  }
  if($atts['recommend']){
    $args['meta_query'] = array(array('key'=>'down_recommend','value'=>'1'));
  }

  $lz = (_MBT('lazyload') && !$atts['water'])?1:0;
  
	query_posts($args);
	while (have_posts()) : the_post();
    if($style == 'grid-audio'){
      $audio = get_post_meta(get_the_ID(),'audio',true);
      $audio_time = get_post_meta(get_the_ID(),'audio_time',true);
      $html .= '<div class="post grid audio" data-audio="'.$audio.'" data-id="'.get_the_ID().'">
          <i class="audio-play"></i>
          <div class="info">
              <a class="title" target="_blank" href="'.get_permalink().'">'.get_the_title().'</a>
              <a target="_blank" href="'.get_permalink().'" class="down"><i class="icon icon-download"></i> 下载</a>
          </div>
          <audio preload="none" id="audio-'.get_the_ID().'" data-time="'.($audio_time?$audio_time:'0').'">
              <source src="'.$audio.'" type="audio/mpeg">
          </audio>
          <span class="star-time">00:00</span>
          <div class="time-bar">
              <span class="progressBar"></span>
              <i class="move-color"></i>
              <p class="timetip"></p>
          </div>
          <span class="end-time">'.mbt_sec_to_time($audio_time).'</span>
      </div>';
    }else{
      $ts = get_post_meta(get_the_ID(),'down_special',true);
  		$tj = get_post_meta(get_the_ID(),'down_recommend',true);
      $sign = get_post_meta(get_the_ID(),'sign',true);
      $sign = $sign?'<span class="post-sign">'.$sign.'</span>':'';
      $tsstyle = '';
  		if($tj) $tj = ' grid-tj'; else $tj = '';
      if(_MBT('post_author')) $zz = ' grid-zz'; else $zz = '';
      if($ts && $cat_class != 'lists'){ 
        $ts = ' grid-ts'; 
        $tsstyle = ' style="background-image:url('.MBThemes_thumbnail_full().')"';
      }else $ts = '';
  		$start_down=get_post_meta(get_the_ID(), 'start_down', true);
      $start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
      $start_see=get_post_meta(get_the_ID(), 'start_see', true);
      $start_see2=get_post_meta(get_the_ID(), 'start_see2', true);
  	  $price=MBThemes_erphpdown_price(get_the_ID());
  	  $memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
      $downtimes = get_post_meta(get_the_ID(),'down_times',true);
      //$video = get_post_meta(get_the_ID(),'video',true);
      //$video_type = get_post_meta(get_the_ID(),'video_type',true);
      if($video) $vd = ' grid-vd';else $vd='';
      $cat = MBThemes_youngest_category();
      $noimg = '';
      if(_MBT('post_list_img') && !MBThemes_thumbnail_has()){
        $noimg = ' noimg';
      }
  		$html .= '<div class="post grid'.$tj.$ts.$vd.$zz.$noimg.'"'.($video?' data-video="'.$video.'"':'').' data-id="'.get_the_ID().'"'.$tsstyle.'>
  		  <div class="img"><a href="'.get_permalink().'" title="'.get_the_title().'" target="'.$post_target.'" rel="bookmark">
  		    <img'.($lz?' src="'.get_bloginfo("template_directory").'/static/img/thumbnail.png"':'').' '.($lz?'data-src':'src').'="'.MBThemes_thumbnail().'" class="thumb" alt="'.get_the_title().'">';
        if($video){
          if($video_type){
            $html .= '<div class="grid-video"><iframe id="video-'.get_the_ID().'" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="allowfullscreen" src=""></iframe></div><span class="video-icon"><i class="icon icon-play"></i></span>'; 
          }else{
            $html .= '<div class="grid-video"><video id="video-'.get_the_ID().'" autoplay="autoplay" muted="true" preload="none" poster="'.MBThemes_thumbnail().'" src=""></video></div><span class="video-icon"><i class="icon icon-play"></i></span>'; 
          }
        }
  		  $html .='</a></div>';
        if(_MBT('post_cat')) $html .= '<a href="'.get_category_link($cat->term_id ).'" class="cat">'.$cat->cat_name.'</a>';
  		  $html .= '<h3 itemprop="name headline"><a itemprop="url" rel="bookmark" href="'.get_permalink().'" title="'.get_the_title().'" target="'.$post_target.'">'.$sign.get_the_title().'</a></h3>';
        $html .= '<p class="excerpt">'.MBThemes_get_excerpt(80).'</p>';
        if(!_MBT('post_metas')){
  		  $html .= '<div class="grid-meta">';
  		    if(_MBT('post_date')) $html .= '<span class="time"><i class="icon icon-time"></i> '.MBThemes_timeago( get_the_time('Y-m-d G:i:s') ).'</span>';
  		    if(_MBT('post_views')) $html .= '<span class="views"><i class="icon icon-eye"></i> '.MBThemes_views(false).'</span>';
              if(_MBT('post_comments')) $html .= '<span class="comments"><i class="icon icon-comment"></i> '.get_comments_number('0', '1', '%').'</span>';
  		    if(($start_down || $start_down2 || $start_see || $start_see2) && wp_is_erphpdown_active()){
  		        if(_MBT('post_downloads')) $html .= '<span class="downs"><i class="icon icon-download"></i> '.($downtimes?$downtimes:'0').'</span>';
              if(!_MBT('post_price')){
                  $html .= '<span class="price">';
                  $down_tuan = '';
                  if(function_exists('erphpdown_tuan_install')){
                    $down_tuan=get_post_meta(get_the_ID(), 'down_tuan', true);
                  }
                  if($down_tuan) $html .= '<span class="vip-tag tuan-tag"><i>拼团</i></span>';
                  elseif($memberDown == '4' || $memberDown == '8' || $memberDown == '9') $html .= '<span class="vip-tag"><i>VIP</i></span>';
                  elseif($price) $html .= '<span class="fee"><i class="icon icon-ticket"></i> '.$price.'</span>';
                  else $html .= '<span class="vip-tag free-tag"><i>免费</i></span>';
                  $html .= '</span>';
              }
  		  	}
  		$html .= '</div>';}
      if(_MBT('post_author')){
        $html .= '<div class="grid-author">
          <a target="_blank" href="'.get_author_posts_url(get_the_author_meta( 'ID' )).'"  class="avatar-link">'.get_avatar(get_the_author_meta( 'ID' )).'<span class="author-name">'.get_the_author().'</span></a>
          <span class="time">'.MBThemes_timeago( get_the_time('Y-m-d G:i:s') ).'</span>
        </div>';
      }
  		$html .= '</div>';
    }
	endwhile; wp_reset_query(); 
	$html .= '</div>';
	if($atts['more']) $html .= '<div class="more"><a href="'.($atts['link']?$atts['link']:get_category_link($atts['id'])).'" target="_blank">'.$atts['text'].'</a></div>';
    $html .= '</div></div>';
    return $html;
}

add_shortcode('motag','MBThemes_shortcode_tag');
function MBThemes_shortcode_tag($atts){
  global $post_target;
	$atts = shortcode_atts( array(
    'id' => '',
    'num' => 8,
    'title' => '',
    'desc' => '',
    'more' => 1,
    'new' => 0,
    'recommend' => 0,
    'orderby' => 'date',
    'text' => '查看更多',
    'link' => '',
    'water' => 0,
    'cols' => 3,
    'style' => ''
  ), $atts, 'motag' );
  $title = $atts['title']?$atts['title']:get_tag($atts['id'])->name;
  $css = '';
  $cat_class = 'grids';
  $style = _MBT('list_style');
  if($style == 'list') $cat_class = 'lists';
  if($atts['id']) {
    $category = get_term( $atts['id'], 'post_tag' );
    $moid = ' id="mocat-'.$atts['id'].'"';
    $style = get_term_meta($atts['id'],'style',true);
    if($atts['style']) $style = $atts['style'];
    if($style == 'list') $cat_class = 'lists';
    elseif($style == 'grid') $cat_class = 'grids';
  }else {
    $category = '';
    $moid = ' id="mocat-rand-'.rand(1000,9999).'"';
  }
  $html = '<div class="mocat'.($atts['water']?' water':'').'"'.$moid.'>'.$css.'<div class="container">';

  if(!$category && !$atts['title']){

  }else{
    $html .= '<h2><span>'.$title;
    if($atts['new']){
    	$html .= '<i>NEW</i>';
    }
    $html .= '</span></h2>';
  }

  if($atts['desc']){
    $html .= '<p class="desc">'.$atts['desc'].'</p>';
  }else{
    if($category){
      if($category->description) $html .= '<p class="desc">'.$category->description.'</p>';
    }
  }

  if($atts['cols'] == 2){
    $cat_class .= ' cols-two';
  }

  if($atts['water']){
    $cat_class .= ' waterfall';
  }

  $html .= '<div class="'.$cat_class.' clearfix">';
  if($atts['id']){ 
    $args = array(
  		'tag_id'              => $atts['id'],
  		'showposts'        => $atts['num'],
        'order'            => 'DESC',
  		'ignore_sticky_posts' => 1
  	);
  }else{
    $args = array(
      'showposts'        => $atts['num'],
      'category__not_in' => explode(',', _MBT('home_cats_exclude')),
      'order'            => 'DESC'
    );
  }
  if($atts['orderby'] == 'views'){
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = 'views';
  }if($atts['orderby'] == 'downs'){
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = 'down_times';
  }elseif($atts['orderby'] == 'comment'){
    $args['orderby'] = 'comment_count';
  }elseif($atts['orderby'] == 'rand'){
    $args['orderby'] = 'rand';
  }else{
    $args['orderby'] = $atts['orderby'];
  }
  if($atts['recommend']){
    $args['meta_query'] = array(array('key'=>'down_recommend','value'=>'1'));
  }

  $lz = (_MBT('lazyload') && !$atts['water'])?1:0;
  
	query_posts($args);
	while (have_posts()) : the_post();
    $ts = get_post_meta(get_the_ID(),'down_special',true);
		$tj = get_post_meta(get_the_ID(),'down_recommend',true);
    $sign = get_post_meta(get_the_ID(),'sign',true);
    $sign = $sign?'<span class="post-sign">'.$sign.'</span>':'';
    $tsstyle = '';
		if($tj) $tj = ' grid-tj'; else $tj = '';
    if(_MBT('post_author')) $zz = ' grid-zz'; else $zz = '';
    if($ts && $cat_class != 'lists'){ 
      $ts = ' grid-ts'; 
      $tsstyle = ' style="background-image:url('.MBThemes_thumbnail_full().')"';
    }else $ts = '';
		$start_down=get_post_meta(get_the_ID(), 'start_down', true);
    $start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
    $start_see=get_post_meta(get_the_ID(), 'start_see', true);
    $start_see2=get_post_meta(get_the_ID(), 'start_see2', true);
	  $price=MBThemes_erphpdown_price(get_the_ID());
	  $memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
    $downtimes = get_post_meta(get_the_ID(),'down_times',true);
    //$video = get_post_meta(get_the_ID(),'video',true);
    //$video_type = get_post_meta(get_the_ID(),'video_type',true);
    if($video) $vd = ' grid-vd';else $vd='';
    $cat = MBThemes_youngest_category();
    $noimg = '';
    if(_MBT('post_list_img') && !MBThemes_thumbnail_has()){
      $noimg = ' noimg';
    }
		$html .= '<div class="post grid'.$tj.$ts.$zz.$noimg.'"'.($video?' data-video="'.$video.'"':'').' data-id="'.get_the_ID().'"'.$tsstyle.'>
		  <div class="img"><a href="'.get_permalink().'" title="'.get_the_title().'" target="'.$post_target.'" rel="bookmark">
		    <img'.($lz?' src="'.get_bloginfo("template_directory").'/static/img/thumbnail.png"':'').' '.($lz?'data-src':'src').'="'.MBThemes_thumbnail().'" class="thumb" alt="'.get_the_title().'">';
      if($video){
        if($video_type){
          $html .= '<div class="grid-video"><iframe id="video-'.get_the_ID().'" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="allowfullscreen" src=""></iframe></div><span class="video-icon"><i class="icon icon-play"></i></span>'; 
        }else{
          $html .= '<div class="grid-video"><video id="video-'.get_the_ID().'" autoplay="autoplay" muted="true" preload="none" poster="'.MBThemes_thumbnail().'" src=""></video></div><span class="video-icon"><i class="icon icon-play"></i></span>'; 
        }
      }
		  $html .='</a></div>';
      if(_MBT('post_cat')) $html .= '<a href="'.get_category_link($cat->term_id ).'" class="cat">'.$cat->cat_name.'</a>';
		  $html .= '<h3 itemprop="name headline"><a itemprop="url" rel="bookmark" href="'.get_permalink().'" title="'.get_the_title().'" target="'.$post_target.'">'.$sign.get_the_title().'</a></h3>';
      $html .= '<p class="excerpt">'.MBThemes_get_excerpt(80).'</p>';
      if(!_MBT('post_metas')){
		  $html .= '<div class="grid-meta">';
		    if(_MBT('post_date')) $html .= '<span class="time"><i class="icon icon-time"></i> '.MBThemes_timeago( get_the_time('Y-m-d G:i:s') ).'</span>';
		    if(_MBT('post_views')) $html .= '<span class="views"><i class="icon icon-eye"></i> '.MBThemes_views(false).'</span>';
            if(_MBT('post_comments')) $html .= '<span class="comments"><i class="icon icon-comment"></i> '.get_comments_number('0', '1', '%').'</span>';
		    if(($start_down || $start_down2 || $start_see || $start_see2) && wp_is_erphpdown_active()){
		        if(_MBT('post_downloads')) $html .= '<span class="downs"><i class="icon icon-download"></i> '.($downtimes?$downtimes:'0').'</span>';
            if(!_MBT('post_price')){
                $html .= '<span class="price">';
                $down_tuan = '';
                if(function_exists('erphpdown_tuan_install')){
                  $down_tuan=get_post_meta(get_the_ID(), 'down_tuan', true);
                }
                if($down_tuan) echo '<span class="vip-tag tuan-tag"><i>拼团</i></span>';
                elseif($memberDown == '4' || $memberDown == '8' || $memberDown == '9') $html .= '<span class="vip-tag"><i>VIP</i></span>';
                elseif($price) $html .= '<span class="fee"><i class="icon icon-ticket"></i> '.$price.'</span>';
                else $html .= '<span class="vip-tag free-tag"><i>免费</i></span>';
                $html .= '</span>';
            }
		  	}
		$html .= '</div>';}
    if(_MBT('post_author')){
      $html .= '<div class="grid-author">
        <a target="_blank" href="'.get_author_posts_url(get_the_author_meta( 'ID' )).'"  class="avatar-link">'.get_avatar(get_the_author_meta( 'ID' )).'<span class="author-name">'.get_the_author().'</span></a>
        <span class="time">'.MBThemes_timeago( get_the_time('Y-m-d G:i:s') ).'</span>
      </div>';
    }
		$html .= '</div>';

	endwhile; wp_reset_query(); 
	$html .= '</div>';
	if($atts['more']) $html .= '<div class="more"><a href="'.($atts['link']?$atts['link']:get_tag_link($atts['id'])).'" target="_blank">'.$atts['text'].'</a></div>';
    $html .= '</div></div>';
    return $html;
}

add_shortcode('mocats','MBThemes_shortcode_cats');
function MBThemes_shortcode_cats($atts, $content=null){
  global $post_target;
  $atts = shortcode_atts( array(
    'cols' => 3,
    'title' => '',
    'text' => '查看更多',
    'link' => '',
    'more' => 0
  ), $atts, 'mocats' );
  $cat_class = '';
  if($atts['cols'] == 2){
    $cat_class = ' cols-two';
  }

  $title = '';
  if($atts['title']){
    $title .= '<h2><span>'.$atts['title'];
    if($atts['new']){
      $title .= '<i>NEW</i>';
    }
    $title .= '</span></h2>';
  }

  $html = '<div class="mocat mocats'.$cat_class.'"><div class="container">'.$title.'<div class="molis clearfix">'.do_shortcode($content).'</div>';
  if($atts['more']) $html .= '<div class="more"><a href="'.$atts['link'].'" target="_blank">'.$atts['text'].'</a></div>';
  $html .='</div></div>';
  return $html;
}

add_shortcode('moli','MBThemes_shortcode_li');
function MBThemes_shortcode_li($atts){
  global $post_target;
  $atts = shortcode_atts( array(
    'id' => '',
    'recommend' => 0,
    'orderby' => 'date',
    'title' => '',
    'desc' => '',
    'link' => '',
    'num' => 8
  ), $atts, 'moli' );

  $title = $atts['title']?$atts['title']:get_cat_name($atts['id']);
  $banner_archive_img = '';$bg = '';
  if($atts['id']) {
    $category = get_term( $atts['id'], 'category' );
    $args = array(
      'cat'              => $atts['id'],
      'showposts'        => $atts['num'],
      'order'            => 'DESC',
      'ignore_sticky_posts' => 1
    );
    $desc = $atts['desc']?$atts['desc']:$category->description;
    if($desc) $desc = '<p class="des">'.$desc.'</p>';
    $banner_img = get_term_meta($atts['id'],'banner_img',true);
    if($banner_img){
        $banner_archive_img = $banner_img;
    }else{
        if(_MBT('banner_archive_img')){
            $banner_archive_img = _MBT('banner_archive_img');
        }
    }
  }else{
    if(_MBT('banner_archive_img')){
        $banner_archive_img = _MBT('banner_archive_img');
    }
    $desc = $atts['desc'];
    if($desc) $desc = '<p class="des">'.$desc.'</p>';
    $args = array(
      'showposts'        => $atts['num'],
      'order'            => 'DESC',
      'ignore_sticky_posts' => 1
    );
  }

  if($atts['orderby'] == 'views'){
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = 'views';
  }if($atts['orderby'] == 'downs'){
    $args['orderby'] = 'meta_value_num';
    $args['meta_key'] = 'down_times';
  }elseif($atts['orderby'] == 'comment'){
    $args['orderby'] = 'comment_count';
  }elseif($atts['orderby'] == 'rand'){
    $args['orderby'] = 'rand';
  }else{
    $args['orderby'] = $atts['orderby'];
  }
  if($atts['recommend']){
    $args['meta_query'] = array(array('key'=>'down_recommend','value'=>'1'));
  }

  if($banner_archive_img) $bg = 'style="background: url('.$banner_archive_img.');"';

  $html = '<div class="moli"><div class="moli-header"'.$bg.'><h3>'.$title.'</h3>'.$desc.'<a href="'.($atts['link']?$atts['link']:get_category_link($atts['id'])).'" target="_blank"></a></div><ul>';
  query_posts($args);
  $i = 1;
  while (have_posts()) : the_post();
  $html .= '<li><i>'.$i.'</i><a href="'.get_permalink().'" target="'.$post_target.'">'.get_the_title().'</a><span><i class="icon icon-eye"></i> '.MBThemes_views(false).'</span></li>';
  $i ++;
  endwhile; wp_reset_query();
  $html .= '</ul></div>';
  return $html;
}

function MBThemes_shortcode_img($atts, $content=null, $code="") {
    $return = '<img class="alignnone" src="';
    $return .= htmlspecialchars($content);
    $return .= '" alt="" />';
    return $return;
}
add_shortcode('img' , 'MBThemes_shortcode_img' );

function MBThemes_shortcode_code($atts, $content=null, $code="") {
    $content = htmlspecialchars($content);
    $return = '<div class="code-highlight"><pre><code class="hljs">';
    $return .= ltrim($content, '\n');
    $return .= '</code></pre></div>';
    return $return;
}
add_shortcode('code' , 'MBThemes_shortcode_code' );

add_shortcode("ckplayer","MBThemes_ckplayer_shortcode");
function MBThemes_ckplayer_shortcode( $atts, $content=null ){
    $nonce = wp_create_nonce(rand(10,1000));
    return '<script src="'.get_bloginfo('template_url').'/module/ckplayer/ckplayer.min.js"></script><div id="ckplayer-video-'.$nonce.'" class="ckplayer-video ckplayer-video-real" style="margin-bottom:30px;" data-nonce="'.$nonce.'" data-video="'.trim($content).'"></div>';
}

add_shortcode("dplayer","MBThemes_dplayer_shortcode");
function MBThemes_dplayer_shortcode( $atts, $content=null ){
    $nonce = wp_create_nonce(rand(10,1000));
    return '<script src="'.get_bloginfo('template_url').'/module/dplayer/dplayer.min.js"></script><script src="'.get_bloginfo('template_url').'/module/dplayer/hls.min.js"></script><div id="dplayer-video-'.$nonce.'" class="dplayer-video dplayer-video-real" style="margin-bottom:30px;" data-nonce="'.$nonce.'" data-video="'.trim($content).'"></div>';
}

add_shortcode("login","MBThemes_login_shortcode");
function MBThemes_login_shortcode( $atts, $content=null ){
    if(is_user_logged_in()){
      return '<p>'.do_shortcode($content).'</p>';
    }else{
      return '<div class="modown-login">此内容 <a href="javascript:;" class="signin-loader">登录</a> 后可见！</div>';
    }
}

add_shortcode("reply","MBThemes_reply_shortcode");
function MBThemes_reply_shortcode( $atts, $content=null ){
  global $wpdb;

  extract(shortcode_atts(array("notice" => '<div class="modown-reply">此内容 <a href="javascript:scrollTo(\'#respond\',-120);">评论</a> 本文后<span>刷新页面</span>可见！</div>'), $atts));   
  $email = null;   
  $user_ID = (int) wp_get_current_user()->ID;
  $post_id = get_the_ID();

  if ($user_ID > 0) {   
      $email = get_userdata($user_ID)->user_email;   
      $admin_email = get_option('admin_email');  
      if ($email == $admin_email) {   
        return '<p>'.do_shortcode($content).'</p>';
      }else{
        $query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `user_id`='{$user_ID}' LIMIT 1";
        if ($wpdb->get_results($query)) {   
          return '<p>'.do_shortcode($content).'</p>';
        }else{
          return $notice;  
        }
      } 
  } else if (isset($_COOKIE['comment_author_email_' . COOKIEHASH])) {   
      $email = str_replace('%40', '@', $_COOKIE['comment_author_email_' . COOKIEHASH]);   
  } else {   
      return $notice;   
  }  

  if (empty($email)) {   
      return $notice;   
  }  
     
  $query = "SELECT `comment_ID` FROM {$wpdb->comments} WHERE `comment_post_ID`={$post_id} and `comment_approved`='1' and `comment_author_email`='{$email}' LIMIT 1";   
  if ($wpdb->get_results($query)) {   
      return '<p>'.do_shortcode($content).'</p>';
  }

}

add_shortcode('gallery_modown','MBThemes_shortcode_gallery_modown');
function MBThemes_shortcode_gallery_modown($atts){
  $atts = shortcode_atts( array(
    'urls' => '',
    'vip' => '0',
    'preview' => '0',
    'hide' => '0',
    'columns' => '4',
    'crop' => '0'
  ), $atts, 'gallery_modown' );

  $gallery_column = $atts['columns'];
  $gallery_preview = $atts['preview'];
  $gallery_hide = $atts['hide'];
  $gallery_vip = $atts['vip'];
  $gallery_urls = $atts['urls'];
  $gallery_arr = explode(',', $gallery_urls);

  $count = count($gallery_arr);

  $output = '';
  $erphp_url_front_vip = add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php")));

  if(!is_user_logged_in()){
    if($gallery_vip && wp_is_erphpdown_active()){
      if($gallery_preview != ''){
        $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> 非VIP用户仅限浏览".$gallery_preview."张，共".$count."张<a href='javascript:;' class='signin-loader'>登录</a></span></div>";
      }
    }else{
      if($gallery_preview != ''){
        $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> 游客仅限浏览".$gallery_preview."张，共".$count."张<a href='javascript:;' class='signin-loader'>登录</a></span></div>";
      }
    }
  }else{
    if(wp_is_erphpdown_active()){
      $userType=getUsreMemberType();
      if($gallery_vip && !$userType){
        if($gallery_preview != ''){
          $output .= "<div class='gallery-login'><span><i class='icon icon-notice'></i> 非VIP用户仅限浏览".$gallery_preview."张，共".$count."张<a href='".$erphp_url_front_vip."' target='_blank'>升级VIP</a></span></div>";
        }
      }
    }
  }

  $output .= "<div id='gallery-1' class='gallery gallery-column-{$gallery_column} clearfix'>";

  $i = 0;
  foreach ( $gallery_arr as $attachment ) {
    if ($atts['crop']) {
      $thumb = get_bloginfo('template_directory').'/timthumb.php?src='.$attachment.'&w=200&h=200&zc=1&q=100';
    }else{
      $thumb = $attachment;
    }

      $i++;
      if(($gallery_preview != '' && $i <= $gallery_preview) || $gallery_preview == '' || (!$gallery_vip && is_user_logged_in()) || ($gallery_vip && $userType)){
        $image_output = '<a href="'.$attachment.'"><img src="'.$thumb.'"></a>';
        $output .= "<div class='gallery-item gallery-fancy-item'>";
      }else{
        if($gallery_hide){
            break;
        }else{
          $image_output = '<span class="img"><img src="'.$thumb.'"></span>';
            $output .= "<div class='gallery-item gallery-blur-item'>";
        }
    }
    $output .= "$image_output";
      $output .= "</div>";
  }
  $output .= "</div>\n";
  return $output;
}

add_action( 'admin_init', 'modown_tinymce_button' );
function modown_tinymce_button() {
     if ( current_user_can( 'edit_posts' ) && current_user_can( 'edit_pages' ) ) {
          add_filter( 'mce_buttons', 'modown_register_tinymce_button' );
          add_filter( 'mce_external_plugins', 'modown_add_tinymce_button' );
     }
}

function modown_register_tinymce_button( $buttons ) {
     array_push( $buttons, "button_player");
     array_push( $buttons, "button_gallery");
     array_push( $buttons, "button_erphpdown");
     array_push( $buttons, "button_reply");
     array_push( $buttons, "button_login");
     array_push( $buttons, "button_catag");
     return $buttons;
}

function modown_add_tinymce_button( $plugin_array ) {
     $plugin_array['mobantu_button_script'] = get_bloginfo('template_directory') . "/static/js/editor.js";
     return $plugin_array;
}