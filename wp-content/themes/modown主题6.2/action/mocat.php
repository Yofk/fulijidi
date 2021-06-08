<?php 
require( dirname(__FILE__) . '/../../../../wp-load.php' ); 
global $post_target;
if(isset($_GET)){ 
	$html = '';
	$cf = $_GET['cf'];
	$o = $_GET['o'];
	$c = $_GET['c'];
	$c2 = $_GET['c2'];
	$num = $_GET['num'];

	$cat_class = 'grids';
	$style = _MBT('list_style');
	if($style == 'list') $cat_class = 'lists';

	if($cf){
		if($c){
			$args = array(
		        'cat' => $c,
		        'showposts' => $num
		    );
		    $style = get_term_meta($c,'style',true);
		    if($style == 'list') $cat_class = 'lists';
	        elseif($style == 'grid') $cat_class = 'grids';
	        elseif($style == 'grid-audio') $cat_class = 'grids';
		}else{
			$args = array(
				'post_type' => 'post',
		        'showposts' => $num,
		        'category__not_in' => explode(',', _MBT('home_cats_exclude'))
		    );
		}

		if($o == 'views'){
		    $args['orderby'] = 'meta_value_num';
		    $args['meta_key'] = 'views';
		}elseif($o == 'downs'){
		    $args['orderby'] = 'meta_value_num';
		    $args['meta_key'] = 'down_times';
		}elseif($o == 'colls'){
		    $args['orderby'] = 'meta_value_num';
		    $args['meta_key'] = 'collects';
		}elseif($o == 'fee'){
			$args['meta_key'] = 'down_price';
            $args['meta_query'] = array('key' => 'down_price', 'compare' => '>','value' => '0');
		}elseif($o == 'free'){
			$args['meta_query'] = array(
                'relation' => 'AND',
                array('key' => 'member_down', 'value' => array(4,8,9), 'compare' => 'NOT IN'),
                array(
        			'relation' => 'OR',
        			array('key' => 'down_price', 'value' => ''),
        			array('key' => 'down_price', 'value' => '0')
                )
    		);
		}
	}else{
		if($c2){
			$args = array(
		        'cat' => $c2,
		        'showposts' => $num
		    );
		    $style = get_term_meta($c2,'style',true);
		    if($style == 'list') $cat_class = 'lists';
	        elseif($style == 'grid') $cat_class = 'grids';
	        elseif($style == 'grid-audio') $cat_class = 'grids';
		}else{
			if($c){
				$args = array(
			        'cat' => $c,
			        'showposts' => $num
			    );
			}else{
				$args = array(
					'post_type' => 'post',
			        'showposts' => $num,
			        'category__not_in' => explode(',', _MBT('home_cats_exclude'))
			    );
			}

		    if($c){
			    $style = get_term_meta($c,'style',true);
			    if($style == 'list') $cat_class = 'lists';
		        elseif($style == 'grid') $cat_class = 'grids';
		        elseif($style == 'grid-audio') $cat_class = 'grids';
		    }
		}
	}


	query_posts($args);
	while ( have_posts() ) : the_post(); 
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
		$html .= '<div class="post grid'.$tj.$ts.$zz.$noimg.'"'.($video?' data-video="'.$video.'"':'').' data-id="'.get_the_ID().'"'.$tsstyle.'>
		  <div class="img"><a href="'.get_permalink().'" title="'.get_the_title().'" target="'.$post_target.'" rel="bookmark">
		    <img src="'.MBThemes_thumbnail().'" class="thumb" alt="'.get_the_title().'">';
		  if($video){
	        if($video_type){
	          $html .= '<div class="grid-video"><iframe id="video-'.get_the_ID().'" scrolling="no" border="0" frameborder="no" framespacing="0" allowfullscreen="allowfullscreen" src=""></iframe></div><span class="video-icon"><i class="icon icon-play"></i></span>'; 
	        }else{
	          $html .= '<div class="grid-video"><video id="video-'.get_the_ID().'" autoplay="autoplay" muted="true" preload="none" poster="'.MBThemes_thumbnail().'" src=""></video></div><span class="video-icon"><i class="icon icon-play"></i></span>'; 
	        }
	      }
		  $html .= '</a></div>';
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
		    	if(!_MBT('post_price') && (is_user_logged_in() || !_MBT('hide_user_all'))){
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
	endwhile;wp_reset_query(); 
	echo $html;
}