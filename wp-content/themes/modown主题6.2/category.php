<?php get_header();$cat_ID = get_query_var('cat');
$vip_see = get_term_meta($cat_ID,'vip_see',true);

if($vip_see && wp_is_erphpdown_active() && !getUsreMemberType()){
    echo '<div class="only-erphpdown-vip"><div class="container"><a href="'.get_permalink(MBThemes_page("template/vip.php")).'"><i class="icon icon-crown-s"></i></a><br><p>此板块的内容仅限VIP浏览</p></div></div>';
}else{

    $cat_class = 'grids'; 
    $style = get_term_meta($cat_ID,'style',true);
    if($style == 'list') $cat_class = 'lists';
    elseif($style == 'grid') $cat_class = 'grids';
    elseif($style == 'grid-audio') $cat_class = 'grids';
    else{
        $style = _MBT('list_style');if($style == 'list') $cat_class = 'lists';
    }
    $banner_archive_img = '';
    $banner_img = get_term_meta($cat_ID,'banner_img',true);
    if($banner_img){
        $banner_archive_img = $banner_img;
    }else{
        if(_MBT('banner_archive_img')){
            $banner_archive_img = _MBT('banner_archive_img');
        }
    }
?>
<div class="banner-archive" <?php if($banner_archive_img){?> style="background-image: url(<?php echo $banner_archive_img;?>);" <?php }?>>
	<div class="container">
		<h1 class="archive-title"><?php single_cat_title() ?></h1>
		<p class="archive-desc"><?php echo category_description();?></p>
	</div>
</div>
<div class="main">
    <?php do_action("modown_main");?>
	<div class="container clearfix">
        <?php if($style == 'list') echo '<div class="content-wrap"><div class="content">';?>
		<?php MBThemes_ad('ad_list_header');?>
		<?php
        $filter_s = get_term_meta($cat_ID,'filter_s',true);
        if((_MBT('filter') && $filter_s != '1') || $filter_s == '2'){?>
		<div class="filters">
			<?php 
            if(_MBT('filter_cat')){
            	$category = get_term_by('id',$cat_ID,'category');
                if(_MBT('filter_cat_brother')){
                    $cat_father_id = 0;
                    if($category->parent != '0'){
                        $cat_father_id = $category->parent;
                    }
                    if($cat_father_id){
                        $cat_father = get_term_by('id',$cat_father_id,'category');
                        $cat_brothers = get_categories("parent=".$cat_father->term_id."&hide_empty=0&depth=1"); 
            ?>
            <div class="filter-item">
                <span>分类</span>
                <div class="filter">
                    <?php 
                        echo '<a href="'.get_category_link($cat_father_id).'">全部</a>';
                        foreach ($cat_brothers as $term) {
                            if($cat_ID == $term->term_id) $class="active";else $class = ''; 
                            echo '<a href="'.get_category_link($term->term_id).'" class="'.$class.'">' . $term->name . '</a>';
                        }
                    ?>
                </div>
            </div>
            <?php
                    }
                }
            	$cat_childs = get_categories("parent=".$category->term_id."&hide_empty=0&depth=1");  
          		if($cat_childs){
          	?>
          	<div class="filter-item">
                <span><?php echo _MBT('filter_cats_title2')?_MBT('filter_cats_title2'):'二级分类';?></span>
                <div class="filter">
                    <?php 
                        if(!isset($_GET['c2']) || $_GET['c2'] == '') $class2="active";else $class2 = ''; 
                        echo '<a href="'.add_query_arg(array("paged"=>1,"c2"=>'',"c3"=>'','c4'=>'',"t"=>''),MBThemes_selfURL()).'" rel="nofollow" class="'.$class2.'">全部</a>';

                        foreach ($cat_childs as $term) {
                            if($_GET['c2'] == $term->term_id) $class="active";else $class = ''; 
                            echo '<a href="'.add_query_arg(array("c2"=>$term->term_id,"c3"=>'','c4'=>'',"t"=>'',"paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class.'">' . $term->name . '</a>';
                        }
                        
                    ?>
                </div>
            </div>
          	<?php
          		}
                if(isset($_GET['c2']) && $_GET['c2']){
	            	$category = get_term_by('id',$_GET['c2'],'category');
	            	$cat_childs = get_categories("parent=".$category->term_id."&hide_empty=0&depth=1");  
	          		if($cat_childs){
	          	?>
	          	<div class="filter-item">
	                <span><?php echo _MBT('filter_cats_title3')?_MBT('filter_cats_title3'):'三级分类';?></span>
	                <div class="filter">
	                    <?php 
	                        if($_GET['c3'] == '' || !isset($_GET['c3'])) $class3="active";else $class3 = ''; 
	                        echo '<a href="'.add_query_arg(array("paged"=>1,"c3"=>'','c4'=>'',"t"=>''),MBThemes_selfURL()).'" rel="nofollow" class="'.$class3.'">全部</a>';

	                        foreach ($cat_childs as $term) {
	                            if($_GET['c3'] == $term->term_id) $class="active";else $class = ''; 
	                            echo '<a href="'.add_query_arg(array("c3"=>$term->term_id,'c4'=>'',"t"=>'',"paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class.'">' . $term->name . '</a>';
	                        }
	                        
	                    ?>
	                </div>
	            </div>
	        <?php
	          		}
	          	}
                if(isset($_GET['c3']) && $_GET['c3']){
                    $category = get_term_by('id',$_GET['c3'],'category');
                    $cat_childs = get_categories("parent=".$category->term_id."&hide_empty=0&depth=1");  
                    if($cat_childs){
                ?>
                <div class="filter-item">
                    <span><?php echo _MBT('filter_cats_title5')?_MBT('filter_cats_title5'):'四级分类';?></span>
                    <div class="filter">
                        <?php 
                            if($_GET['c4'] == '' || !isset($_GET['c4'])) $class4="active";else $class4 = ''; 
                            echo '<a href="'.add_query_arg(array("paged"=>1,"c4"=>'',"t"=>''),MBThemes_selfURL()).'" rel="nofollow" class="'.$class4.'">全部</a>';

                            foreach ($cat_childs as $term) {
                                if($_GET['c4'] == $term->term_id) $class="active";else $class = ''; 
                                echo '<a href="'.add_query_arg(array("c4"=>$term->term_id,"t"=>'',"paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class.'">' . $term->name . '</a>';
                            }
                            
                        ?>
                    </div>
                </div>
            <?php
                    }
                }
            ?>
            <?php }?>
            <?php 
            $taxonomys_s = get_term_meta($cat_ID,'taxonomys_s',true);
            if((_MBT('filter_taxonomy') && $taxonomys_s != '1') || $taxonomys_s == '2'){?>
            <?php 
                $post_texonomys = _MBT('post_taxonomy');
                $taxonomys = get_term_meta($cat_ID,'taxonomys',true);
                if($taxonomys) $post_texonomys = $taxonomys;

                if(isset($_GET['c2']) && $_GET['c2']){
                	$taxonomys = get_term_meta($_GET['c2'],'taxonomys',true);
                	if($taxonomys) $post_texonomys = $taxonomys;
                }

                if(isset($_GET['c3']) && $_GET['c3']){
                	$taxonomys = get_term_meta($_GET['c3'],'taxonomys',true);
                	if($taxonomys) $post_texonomys = $taxonomys;
                }

                if(isset($_GET['c4']) && $_GET['c4']){
                	$taxonomys = get_term_meta($_GET['c4'],'taxonomys',true);
                	if($taxonomys) $post_texonomys = $taxonomys;
                }

                if($post_texonomys){
                    $post_texonomys = explode('|', $post_texonomys);
                    foreach ($post_texonomys as $post_texonomy) { 
                        $post_texonomy = explode(',', $post_texonomy);
            ?>
            <div class="filter-item">
                <span><?php echo $post_texonomy[0];?></span>
                <div class="filter">
                    <?php 
                        if(!isset($_GET[$post_texonomy[2]]) || $_GET[$post_texonomy[2]] == '') $class2="active";else $class2 = ''; 
                        echo '<a href="'.add_query_arg(array("paged"=>1,$post_texonomy[2]=>''),MBThemes_selfURL()).'" rel="nofollow" class="'.$class2.'">全部</a>';
                        if(count($post_texonomy) == '4'){
                            $taxonomy = get_terms( array(
                                'taxonomy' => $post_texonomy[1],
                                'hide_empty' => false,
                                'include' => explode('-', $post_texonomy[3])
                            ) );
                        }else{
                            $taxonomy = get_terms( array(
                                'taxonomy' => $post_texonomy[1],
                                'hide_empty' => false,
                            ) );
                        }
                        if($taxonomy){
                            foreach ( $taxonomy as $term ) {
                                if(isset($_GET[$post_texonomy[2]]) && $_GET[$post_texonomy[2]] == $term->term_id) $class="active";else $class = ''; 
                                echo '<a href="'.add_query_arg(array($post_texonomy[2]=>$term->term_id,"paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class.'">' . $term->name . '</a>';
                            }
                        }
                    ?>
                </div>
            </div>
            <?php      
                    }
                }
            ?>
            <?php }?>
            <?php
            $tags_s = get_term_meta($cat_ID,'tags_s',true);
            if((_MBT('filter_tag') && $tags_s != '1') || $tags_s == '2'){?>
            <div class="filter-item">
                <span><?php echo _MBT('filter_cats_title4')?_MBT('filter_cats_title4'):'标签';?></span>
                <div class="filter">
                    <?php 
                        $tags = '';
                        if($_GET['t'] == '' || !isset($_GET['t'])) $class2="active";else $class2 = '';
                        echo '<a href="'.add_query_arg(array("paged"=>1,"t"=>""),MBThemes_selfURL()).'" rel="nofollow" class="'.$class2.'">全部</a>';

                        if(isset($_GET['c4']) && $_GET['c4']){
                            $tags4 = get_term_meta($_GET['c4'],'tags',true);
                            if($tags4) $tags = $tags4;
                            elseif(isset($_GET['c3']) && $_GET['c3']){
                                $tags3 = get_term_meta($_GET['c3'],'tags',true);
                                if($tags3) $tags = $tags3;
                                elseif(isset($_GET['c2']) && $_GET['c2']){
                                    $tags2 = get_term_meta($_GET['c2'],'tags',true);
                                    if($tags2) $tags = $tags2;
                                    else{
                                        $tags = get_term_meta($cat_ID,'tags',true);
                                    }
                                }
                            }
                        }elseif(isset($_GET['c3']) && $_GET['c3']){
                        	$tags3 = get_term_meta($_GET['c3'],'tags',true);
                        	if($tags3) $tags = $tags3;
                        	elseif(isset($_GET['c2']) && $_GET['c2']){
	                        	$tags2 = get_term_meta($_GET['c2'],'tags',true);
	                        	if($tags2) $tags = $tags2;
	                        	else{
		                        	$tags = get_term_meta($cat_ID,'tags',true);
		                        }
	                        }
                        }elseif(isset($_GET['c2']) && $_GET['c2']){
                        	$tags2 = get_term_meta($_GET['c2'],'tags',true);
                        	if($tags2) $tags = $tags2;
                        	else{
	                        	$tags = get_term_meta($cat_ID,'tags',true);
	                        }
                        }else{
                        	$tags = get_term_meta($cat_ID,'tags',true);
                        }

                        $filter_tag_ids = _MBT('filter_tags');
                        if($tags) $filter_tag_ids = $tags;

                        if(_MBT('filter_tag_auto')){
                            if(isset($_GET['c4']) && $_GET['c4']){
                                $filter_tag_ids = MBThemes_related_tags($_GET['c4']);
                            }elseif(isset($_GET['c3']) && $_GET['c3']){
                                $filter_tag_ids = MBThemes_related_tags($_GET['c3']);
                            }elseif(isset($_GET['c2']) && $_GET['c2']){
                                $filter_tag_ids = MBThemes_related_tags($_GET['c2']);
                            }else{
                                $filter_tag_ids = MBThemes_related_tags($cat_ID);
                            }
                        }

                        if($filter_tag_ids){
                            $filter_tag_ids_array = explode(',', $filter_tag_ids);
                            foreach ($filter_tag_ids_array as $tag_id) {
                                $term = get_term_by('id',$tag_id,'post_tag');
                                if($_GET['t'] == $term->term_id) $class="active";else $class = ''; 
                                echo '<a href="'.add_query_arg(array("t"=>$term->term_id,"paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class.'">' . $term->name . '</a>';
                            }
                        }
                    ?>
                </div>
            </div>
            <?php }?>
            <?php 
            $price_s = get_term_meta($cat_ID,'price_s',true);
            if((_MBT('filter_price') && $price_s != '1') || $price_s == '2'){?>
            <div class="filter-item">
                <span>价格</span>
                <div class="filter">
                    <?php 
                    	$class3 = '';$class4='';$class5='';$class6='';$class7='';$class8='';
                        if($_GET['v'] == '' || !isset($_GET['v'])){ 
                            $class3="active";
                        }elseif($_GET['v'] == 'fee'){
                            $class4 = 'active';
                        }elseif($_GET['v'] == 'free'){
                            $class5 = 'active';
                        }elseif($_GET['v'] == 'vip'){
                            $class6 = 'active';
                        }elseif($_GET['v'] == 'nvip'){
                            $class7 = 'active';
                        }elseif($_GET['v'] == 'svip'){
                            $class8 = 'active';
                        }elseif($_GET['v'] == 'vipf'){
                            $class9 = 'active';
                        }
                        echo '<a href="'.add_query_arg(array("paged"=>1,"v"=>""),MBThemes_selfURL()).'" rel="nofollow" class="'.$class3.'">全部</a>';
                        echo '<a href="'.add_query_arg(array("v"=>"free","paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class5.'">免费</a>';
                        echo '<a href="'.add_query_arg(array("v"=>"fee","paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class4.'">收费</a>';
                        if(!_MBT('vip_hidden')){
                            echo '<a href="'.add_query_arg(array("v"=>"vip","paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class6.'">VIP免费</a>';
                            echo '<a href="'.add_query_arg(array("v"=>"vipf","paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class9.'">VIP优惠</a>';
                            if(get_option('ciphp_year_price')) echo '<a href="'.add_query_arg(array("v"=>"nvip","paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class7.'">年费VIP免费</a>';
                            if(get_option('ciphp_life_price')) echo '<a href="'.add_query_arg(array("v"=>"svip","paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class8.'">终身VIP免费</a>';
                        }  
                    ?>
                </div>
            </div>
            <?php }?>
            <?php 
            $order_s = get_term_meta($cat_ID,'order_s',true);
            if((_MBT('filter_order') && $order_s != '1') || $order_s == '2'){?>
            <div class="filter-item filter-item-order">
                <span>排序</span>
                <div class="filter">
                    <?php 
                        $class3 = '';$class4='';$class5='';$class6='';$class7='';$class8='';
                        if($_GET['o'] == '' || !isset($_GET['o'])){ 
                            $class3="active";
                        }elseif($_GET['o'] == 'download'){
                            $class4 = 'active';
                        }elseif($_GET['o'] == 'view'){
                            $class5 = 'active';
                        }elseif($_GET['o'] == 'comment'){
                            $class6 = 'active';
                        }elseif($_GET['o'] == 'update'){
                            $class7 = 'active';
                        }elseif($_GET['o'] == 'recommend'){
                            $class8 = 'active';
                        }
                        echo '<a href="'.add_query_arg(array("paged"=>1,"o"=>''),MBThemes_selfURL()).'" rel="nofollow" class="'.$class3.'">最新</a>';
                        echo '<a href="'.add_query_arg(array("o"=>"recommend","paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class8.'">推荐</a>';
                        echo '<a href="'.add_query_arg(array("o"=>"download","paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class4.'">下载</a>';
                        echo '<a href="'.add_query_arg(array("o"=>"view","paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class5.'">浏览</a>';
                        echo '<a href="'.add_query_arg(array("o"=>"comment","paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class6.'">评论</a>';
                        echo '<a href="'.add_query_arg(array("o"=>"update","paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class7.'">更新</a>';
                    ?>
                </div>
            </div>
            <?php }?>
        </div>
		<?php }?>
		<div id="posts" class="posts <?php echo $cat_class;?> <?php if(_MBT('waterfall') && $style != 'list') echo 'waterfall';?> clearfix">
			<?php 
				if((_MBT('filter') && $filter_s != '1') || $filter_s == '2'){
					$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
					if(isset($_GET['c4']) && $_GET['c4']){
                        $args = array(
                            'cat' => $_GET['c4'],
                            'paged' => $paged
                        );
                    }elseif(isset($_GET['c3']) && $_GET['c3']){
						$args = array(
	                        'cat' => $_GET['c3'],
	                        'paged' => $paged
	                    );
					}elseif(isset($_GET['c2']) && $_GET['c2']){
						$args = array(
	                        'cat' => $_GET['c2'],
	                        'paged' => $paged
	                    );
					}else{
	                    $args = array(
	                        'cat' => $cat_ID,
	                        'paged' => $paged
	                    );
	                }

                    $args['meta_query'] = array();

                    if(isset($_GET['t']) && $_GET['t']){
                        $args['tag_id'] = $_GET['t'];
                    }

                    if(isset($_GET['v']) && $_GET['v']){
                        if($_GET['v'] == 'fee'){
                            $args['meta_query'] = array(
                                'relation' => 'AND',
                                array(
                                    'relation' => 'OR',
                                    array('key' => 'down_price', 'compare' => '>','value' => '0'),
                                    array('key' => 'down_urls', 'compare' => 'EXISTS')
                                )
                            );
                        }elseif($_GET['v'] == 'free'){
                            $args['meta_query'] = array(
                                'relation' => 'AND',
                                array('key' => 'member_down', 'value' => array(4,8,9), 'compare' => 'NOT IN'),
                                array(
                                    'relation' => 'AND',
                                    array(
                                        'relation' => 'OR',
                                        array('key' => 'down_price', 'value' => ''),
                                        array('key' => 'down_price', 'value' => '0')
                                    ),
                                    array(
                                        'relation' => 'OR',
                                        array('key' => 'down_urls', 'compare' => 'NOT EXISTS'),
                                        array('key' => 'down_urls', 'value' => '')
                                    )
                                )
                            );
                        }elseif($_GET['v'] == 'vip'){
                            $args['meta_query'] = array(array('key' => 'member_down', 'value' => array(4,3), 'compare' => 'IN'));
                        }elseif($_GET['v'] == 'nvip'){
                            $args['meta_query'] = array(array('key' => 'member_down', 'value' => array(4,3,8,6), 'compare' => 'IN'));
                        }elseif($_GET['v'] == 'svip'){
                            $args['meta_query'] = array(array('key' => 'member_down', 'value' => array(4,3,8,9,6,7), 'compare' => 'IN'));
                        }elseif($_GET['v'] == 'vipf'){
                            $args['meta_query'] = array(array('key' => 'member_down', 'value' => array(2,5), 'compare' => 'IN'));
                        }
                    }

                    if(isset($_GET['o']) && $_GET['o']){
                    	if($_GET['o'] == 'comment'){
                    		$args['orderby'] = 'comment_count';
                    	}elseif($_GET['o'] == 'update'){
                            $args['orderby'] = 'modified';
                        }elseif($_GET['o'] == 'recommend'){
                            array_push($args['meta_query'], array('key' => 'down_recommend', 'value' => '1'));
                        }else{
	                        if($_GET['o'] == 'download'){
	                        	$args['meta_key'] = 'down_times';
	                        }elseif($_GET['o'] == 'view'){
	                            $args['meta_key'] = 'views';
	                        }

	                        $args['orderby'] = 'meta_value_num';
	                    }
                    }

                    if(isset($post_texonomys) && is_array($post_texonomys)){
                        $args['tax_query'] = array();
                        foreach ($post_texonomys as $post_texonomy) {
                            $post_texonomy = explode(',', $post_texonomy);
                            if(isset($_GET[$post_texonomy[2]]) && $_GET[$post_texonomy[2]]){
                                array_push($args['tax_query'], array('taxonomy' => $post_texonomy[1],'field' => 'term_id','terms' => $_GET[$post_texonomy[2]]) );
                            }
                        }
                    }
                    query_posts($args);
				}
                $ccc = 'content';if($style == 'list') $ccc = 'content-list';elseif($style == 'grid-audio') $ccc = 'content-audio';
				while ( have_posts() ) : the_post(); 
				get_template_part( $ccc, get_post_format() );
				endwhile; 
				if(!((_MBT('filter') && $filter_s != '1') || $filter_s == '2')) wp_reset_query(); 
			?>
		</div>
		<?php MBThemes_paging();?>
		<?php MBThemes_ad('ad_list_footer');?>
        <?php if($style == 'list') {echo '</div></div>';get_sidebar();}?>
	</div>
</div>
<?php } get_footer();?>