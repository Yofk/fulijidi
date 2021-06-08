<?php get_header();$style = _MBT('list_style');$cat_class = 'grids'; if($style == 'list') $cat_class = 'lists';?>
<div class="banner-archive" <?php if(_MBT('banner_archive_img')){?> style="background-image: url(<?php echo _MBT('banner_archive_img');?>);" <?php }?>>
	<div class="container">
		<h1 class="archive-title" style="display: none;">搜索：<?php echo wp_specialchars($s, 1);?></h1>
		<div class="search-form" style="margin-top:10px;">
            <form method="get" class="site-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" >
                <input class="search-input" name="s" type="text" placeholder="搜索一下" value="<?php echo wp_specialchars($s, 1);?>">
                <button class="search-btn" type="submit" style="border:1px solid #fff;border-left:none"><i class="icon icon-search"></i></button>
            </form>
        </div>
	</div>
</div>
<div class="main">
    <?php do_action("modown_main");?>
	<div class="container clearfix">
        <?php if($style == 'list') echo '<div class="content-wrap"><div class="content">';?>
		<?php MBThemes_ad('ad_list_header');?>
		<?php if(_MBT('filter_search')){?>
		<div class="filters">
            <?php if(_MBT('filter_cat')){?>
			<div class="filter-item">
                <span><?php echo _MBT('filter_cats_title1')?_MBT('filter_cats_title1'):'分类';?></span>
                <div class="filter">
                    <?php 
                        if(!isset($_GET['cat']) || !$_GET['cat']) $class2="active";else $class2 = ''; 
                        echo '<a href="'.add_query_arg(array("paged"=>1,"cat"=>''),MBThemes_selfURL()).'" rel="nofollow" class="'.$class2.'">全部</a>';
                        $filter_cat_ids = _MBT('banner_cats');
                        if($filter_cat_ids){
                            $filter_cat_ids_array = explode(',', $filter_cat_ids);
                            foreach ($filter_cat_ids_array as $cat_id) {
                                $term = get_term_by('id',$cat_id,'category');
                                if($_GET['cat'] == $term->term_id) $class="active";else $class = ''; 
                                echo '<a href="'.add_query_arg(array("cat"=>$term->term_id,"paged"=>1),MBThemes_selfURL()).'" rel="nofollow" class="'.$class.'">' . $term->name . '</a>';
                            }
                        }
                    ?>
                </div>
            </div>
            <?php }?>
            <?php if(_MBT('filter_taxonomy')){?>
            <?php 
                if(isset($_GET['cat']) && $_GET['cat']){
                $cat_ID = $_GET['cat'];
                $post_texonomys = get_term_meta($cat_ID,'taxonomys',true);
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
                }
            ?>
            <?php }?>
            <?php if(_MBT('filter_tag')){?>
            <div class="filter-item">
                <span><?php echo _MBT('filter_cats_title4')?_MBT('filter_cats_title4'):'标签';?></span>
                <div class="filter">
                    <?php 
                        $tags = '';
                        if($_GET['t'] == '' || !isset($_GET['t'])) $class2="active";else $class2 = ''; 
                        echo '<a href="'.add_query_arg(array("paged"=>1,"t"=>''),MBThemes_selfURL()).'" rel="nofollow" class="'.$class2.'">全部</a>';
                        if(isset($_GET['cat']) && $_GET['cat']){
                            $tags2 = get_term_meta($_GET['cat'],'tags',true);
                            if($tags2) $tags = $tags2;
                        }

                        $filter_tag_ids = _MBT('filter_tags');
                        if($tags) $filter_tag_ids = $tags;

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
            <?php if(_MBT('filter_price')){?>
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
            <?php if(_MBT('filter_order')){?>
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
                $args = array();
				if(_MBT('filter_search')){
                    $args['meta_query'] = array();
					
					if(isset($_GET['cat']) && $_GET['cat']){
                        $args['cat'] = $_GET['cat'];
                    }

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
                            }
                            elseif($_GET['o'] == 'view'){
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

				}
                if(!isset($_GET['post_type'])){
                    $args['post_type'] = 'post';
                }
                $arms = array_merge($args, $wp_query->query);
                query_posts($arms);
                $ccc = 'content';if($style == 'list') $ccc = 'content-list';
				while ( have_posts() ) : the_post(); 
				get_template_part( $ccc, get_post_format() );
				endwhile; //wp_reset_query(); 
			?>
		</div>
		<?php MBThemes_paging();?>
		<?php MBThemes_ad('ad_list_footer');?>
        <?php if($style == 'list') {echo '</div></div>';get_sidebar();}?>
	</div>
</div>
<?php get_footer();?>