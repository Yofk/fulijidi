<?php 
global $wpdb;
$wpdb->erphpcard = $wpdb->prefix.'erphpdown_card';

function erphpdown_card_install(){
	global $wpdb;
	$table_name = $wpdb->prefix.'erphpdown_card';
	$sql = "CREATE TABLE ".$table_name." (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			card varchar(100),
			password varchar(100),
			uid int(10) DEFAULT '0',
			usetime datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			status int(3) DEFAULT '0' NOT NULL,
			price double(10,2) NOT NULL,
			UNIQUE KEY id (id)
			);";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}

/*
add_action('admin_menu', 'erphpdown_card_menu');
function erphpdown_card_menu() {
	if (function_exists('add_menu_page')) {
		add_menu_page('erphpdown充值卡', '充值卡', 'administrator', 'erphpdown-add-on-card/card-list.php', '','');
	}
	if (function_exists('add_submenu_page')) {
		add_submenu_page('erphpdown-add-on-card/card-list.php', '所有充值卡','所有充值卡', 'administrator', 'erphpdown-add-on-card/card-list.php');
		add_submenu_page('erphpdown-add-on-card/card-list.php', '添加充值卡','添加充值卡', 'administrator', 'erphpdown-add-on-card/card-add.php');
    }
}
*/

function erphpcard_admin_pagenavi($total_count, $number_per_page=15){

	$current_page = isset($_GET['paged'])?$_GET['paged']:1;

	if(isset($_GET['paged'])){
		unset($_GET['paged']);
	}

	$base_url = add_query_arg($_GET,admin_url('admin.php'));

	$total_pages	= ceil($total_count/$number_per_page);

	$first_page_url	= $base_url.'&amp;paged=1';
	$last_page_url	= $base_url.'&amp;paged='.$total_pages;
	
	if($current_page > 1 && $current_page < $total_pages){
		$prev_page		= $current_page-1;
		$prev_page_url	= $base_url.'&amp;paged='.$prev_page;

		$next_page		= $current_page+1;
		$next_page_url	= $base_url.'&amp;paged='.$next_page;
	}elseif($current_page == 1){
		$prev_page_url	= '#';
		$first_page_url	= '#';
		if($total_pages > 1){
			$next_page		= $current_page+1;
			$next_page_url	= $base_url.'&amp;paged='.$next_page;
		}else{
			$next_page_url	= '#';
		}
	}elseif($current_page == $total_pages){
		$prev_page		= $current_page-1;
		$prev_page_url	= $base_url.'&amp;paged='.$prev_page;
		$next_page_url	= '#';
		$last_page_url	= '#';
	}
	?>
	<div class="tablenav bottom">
		<div class="tablenav-pages">
			<span class="displaying-num">每页 <?php echo $number_per_page;?>，共 <?php echo $total_count;?>个项目 </span>
			<span class="pagination-links">
				<a class="first-page <?php if($current_page==1) echo 'disabled'; ?>" title="前往第一页" href="<?php echo $first_page_url;?>">«</a>
				<a class="prev-page <?php if($current_page==1) echo 'disabled'; ?>" title="前往上一页" href="<?php echo $prev_page_url;?>">‹</a>
				<span class="paging-input">第 <?php echo $current_page;?> 页，共 <span class="total-pages"><?php echo $total_pages; ?></span> 页</span>
				<a class="next-page <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往下一页" href="<?php echo $next_page_url;?>">›</a>
				<a class="last-page <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往最后一页" href="<?php echo $last_page_url;?>">»</a>
			</span>
		</div>
		<br class="clear">
	</div>
	<?php
}

function isErphpCardUsed($id){
	global $wpdb;
	$result = $wpdb->get_row("select * from $wpdb->erphpcard where id = '".$id."'");
	if(!$result->status) return '否';
	else return '是 [使用者：'.get_the_author_meta( 'user_login', $result->uid ).'，时间：'.$result->usetime.']';
}

function checkDoCardResult($card,$password){
	global $wpdb;
	$result = $wpdb->get_row("select * from $wpdb->erphpcard where card = '".$wpdb->escape($card)."'");
	if($result->status == '0'){
		if($result->password == $password)
		{
			$user_info=wp_get_current_user();
			$ss = $wpdb->query("update $wpdb->erphpcard set status=1,uid='".$user_info->ID."',usetime='".date("Y-m-d H:i:s")."' where card='".$card."'");
			if($ss){
				$alipay_no = date("ymd").mt_rand(10, 99).mt_rand(10,99);
				$sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_time,ice_success,ice_note,ice_success_time,ice_alipay)
			VALUES ('".$result->price*get_option(ice_proportion_alipay)."','$alipay_no','".$user_info->ID."','".date("Y-m-d H:i:s")."',1,'6','".date("Y-m-d H:i:s")."','')";
				$a=$wpdb->query($sql);
				if($a){
					addUserMoney($user_info->ID, $result->price*get_option(ice_proportion_alipay));
					return '1';
				}else{
					return '4';
				}
			}else{
				return '4';
			}
		}else
		{
			return '2';
		}
	}elseif($result->status == '1'){
		return '0';  //已被使用过
	}
	else{
		return '5';
	}
}

?>
