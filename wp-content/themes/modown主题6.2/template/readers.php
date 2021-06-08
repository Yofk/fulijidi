<?php 
/*
	template name: 评论读者墙
	description: template for mobantu.com modown theme 
*/
get_header();?>
<div class="banner-archive" <?php if(_MBT('banner_archive_img')){?> style="background-image: url(<?php echo _MBT('banner_archive_img');?>);" <?php }?>>
	<div class="container">
		<h1 class="archive-title"><?php the_title();?></h1>
	</div>
</div>
<div class="main">
	<?php do_action("modown_main");?>
	<div class="container">
		<div class="content-wrap">
	    	<div class="content readers clearfix">
				<?php
					global $wpdb;
	                $query="SELECT COUNT(comment_ID) AS cnt, comment_author, user_id, comment_author_email FROM (SELECT * FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->posts.ID=$wpdb->comments.comment_post_ID) WHERE comment_approved='1' AND comment_type='') AS tempcmt GROUP BY comment_author ORDER BY cnt DESC LIMIT 100";
	                $wall = $wpdb->get_results($query);
	                $maxNum = $wall[0]->cnt;
	                foreach ($wall as $comment)
	                {
	                    $tmp = "<li>
	                        <a title='".$comment->comment_author." (".$comment->cnt.")'>".get_avatar($comment->user_id, $comment->comment_author_email, 60)."
	                            <span>".$comment->comment_author."<br></span>
	                        </a>
	                    </li>";
	                    $output .= $tmp;
	                 }
	                $output = "<ul>".$output."</ul>";
	                echo $output ;
	            ?>
	    	</div>
	    </div>
	</div>
</div>
<?php get_footer();?>