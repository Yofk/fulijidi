<?php 
/*
	template name: 友情链接
	description: template for mobantu.com modown theme 
*/
get_header();
if(_MBT('friendlink_no')){
    $cats = get_terms( 'link_category', array(
        'hierarchical' => true,
    	'hide_empty' => false,
        'exclude' => _MBT('friendlink_id')
    ) );
}else{
    $cats = get_terms( 'link_category', array(
        'hierarchical' => true,
        'hide_empty' => false
    ) );
}
?>
<style>
.pageside h2{background: #f5f5f5;text-align: center;font-size: 18px;padding:15px 0;}
body.night .pageside h2{background: #212533}
</style>
<div class="main">
    <?php do_action("modown_main");?>
	<div class="container clearfix">
		<div class="content-wrap content-nav">
			<?php if(!empty($cats)){?>
			<div class="pageside">
				<h2>快速导航</h2>
			    <div class="pagemenus pagelinks">
			    	<ul class="pagemenu">
			        	<?php
							if(!empty($cats)){
								foreach ($cats as $cat) {
									$i++;
									echo '<li><a href="javascript:scrollTo(\'#links_'.$cat->term_id.'\',-100);">'.$cat->name.' <i class="icon icon-arrow-right"></i></a></li>';
								}
							}
						?>
			    	</ul>
			    </div>
			</div>
			<?php }else{echo '<style>.content-nav{padding-left:0}</style>';}?>
	    	<div class="content" style="min-height: 300px">
	    		<?php 
	    			if(!empty($cats)){
                        foreach ( $cats as $cat ) {
                            $bookmarks = get_bookmarks( array('category'=>$cat->term_id) );
                            $html = '';
                            foreach ($bookmarks as $bookmark) {
                                if($bookmark->link_image ){
                                    $img = $bookmark->link_image;
                                }
                                $img = $bookmark->link_image ? $bookmark->link_image : get_bloginfo('template_url').'/static/img/loading.gif';
                                $description = $bookmark->link_description ? $bookmark->link_description : '这个网站没有任何描述信息';
                                $html .= '<div class="link-item">
                                        <div class="link-main">
											<a href="'.$bookmark->link_url.'" target="_blank"><img class="link-img" src="'.$img. '"/></a>
                                            <a href="'.$bookmark->link_url.'" target="_blank">
                                                <h2>'.$bookmark->link_name.'</h2>
                                            </a>
										</div>
                                        <p class="link-desc">'.$description.'</p>
                                </div>';
                            }

                            echo '<div class="link-box" id="links_'.$cat->term_id.'">
                                <div class="link-title"><i class="icon icon-circle"></i> '.$cat->name.'</div>
                                <div class="link-list clearfix">
                                    '.$html.'
                                </div>
                            </div>';
                        }
                    }else{
                    	$bookmarks = get_bookmarks();
                        $html = '';
                        foreach ($bookmarks as $bookmark) {
                            if($bookmark->link_image ){
                                $img = $bookmark->link_image;
                            }
                            $img = $bookmark->link_image ? $bookmark->link_image : get_bloginfo('template_url').'/static/img/loading.gif';
                            $description = $bookmark->link_description ? $bookmark->link_description : '这个网站没有任何描述信息';
                            $html .= '<div class="link-item">
                                    <div class="link-main">
										<a href="'.$bookmark->link_url.'" target="_blank"><img class="link-img" src="'.$img. '"/></a>
                                        <a href="'.$bookmark->link_url.'" target="_blank">
                                            <h2>'.$bookmark->link_name.'</h2>
                                        </a>
									</div>
                                    <p class="link-desc">'.$description.'</p>
                            </div>';
                        }

                        echo '<div class="link-box">
                            <div class="link-title"><i class="icon icon-circle"></i> 友情链接</div>
                            <div class="link-list clearfix">
                                '.$html.'
                            </div>
                        </div>';
                    }
	    		?>
	    	</div>
	    </div>
	</div>
</div>
<?php get_footer();?>