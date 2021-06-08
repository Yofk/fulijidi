<?php 
/*
	template name: 文章存档
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
	    	<div class="content archives clearfix">
				<?php
                $previous_year = $year = 0;
                $previous_month = $month = 0;
                $ul_open = false;
                 
                $myposts = get_posts('numberposts=-1&orderby=post_date&order=DESC');
                
                foreach($myposts as $post) :
                    setup_postdata($post);
                 
                    $year = mysql2date('Y', $post->post_date);
                    $month = mysql2date('n', $post->post_date);
                    $day = mysql2date('j', $post->post_date);
                    
                    if($year != $previous_year || $month != $previous_month) :
                        if($ul_open == true) : 
                            echo '</ul></div>';
                        endif;
                 
                        echo '<div class="item"><h3>'; echo the_time('F Y'); echo '</h3>';
                        echo '<ul class="archives-list">';
                        $ul_open = true;
                 
                    endif;
                 
                    $previous_year = $year; $previous_month = $month;
                ?>
		      <li>
		        <time>
		          <?php the_time('j'); ?>
		          日</time>
		        <a href="<?php the_permalink(); ?>">
		        <?php the_title(); ?>
		        </a> <span class="muted">
		        <?php comments_number('', '1评论', '%评论'); ?>
		        </span> </li>
		      <?php endforeach; ?>
		      </ul>
	    	</div>
	    </div>
	</div>
</div>
<?php get_footer();?>