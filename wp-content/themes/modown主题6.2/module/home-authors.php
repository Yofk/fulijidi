<?php $home_author_ids = _MBT('home_author_ids');if($home_author_ids){?>
<div class="home-authors">
	<div class="container">
		<h2><span><?php echo _MBT('home_author_title')?_MBT('home_author_title'):'推荐作者';?></span></h2>
		<div class="home-authors-items clearfix">
			<?php 
				$home_author_ids_arr = explode(',', $home_author_ids);
				if($home_author_ids_arr){
					foreach ($home_author_ids_arr as $home_author_id) {
						$auser = get_user_by('id',$home_author_id);
						if($auser){
							echo '<div class="home-author">';
							echo '<a href="'.get_author_posts_url($home_author_id).'">'.get_avatar($home_author_id,100).'</a>';
							echo '<span class="name">'.$auser->display_name.'</span>';
							echo '</div>';
						}
					}
				}
			?>
		</div>
	</div>
</div>
<?php }?>