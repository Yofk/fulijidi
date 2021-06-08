<?php
function createNewTicket($uid,$type,$number,$note,$content,$image,$email){
	global $wpdb;
	$result = $wpdb->query("insert into ".$wpdb->prefix . "tickets(user_id,type,number,email,create_time,note) values($uid,$type,'".$number."','".$email."','".date("Y-m-d H:i:s")."','".$note."')");
	if($result){ 
		$result2 = $wpdb->query("insert into ".$wpdb->prefix . "ticket_item(user_id,ticket_id,type,image,create_time,note) values($uid,".getTicketIdByNum($number).",1,'".$image."','".date("Y-m-d H:i:s")."','".$content."')");
		if($result2){
			return true;
		}
	}
	return false;
}

function createNewReply($uid,$type,$number,$content,$image){
	global $wpdb;
	$result = $wpdb->query("insert into ".$wpdb->prefix . "ticket_item(user_id,ticket_id,type,image,create_time,note) values($uid,".getTicketIdByNum($number).",$type,'".$image."','".date("Y-m-d H:i:s")."','".$content."')");
	if($result){
		$wpdb->query("update ".$wpdb->prefix . "tickets set status=0 where number='".$number."'");
		return true;
	}
	return false;
}

function createNewReplyByAdmin($uid,$type,$number,$content,$image){
	global $wpdb;
	$result = $wpdb->query("insert into ".$wpdb->prefix . "ticket_item(user_id,ticket_id,type,image,create_time,note) values($uid,".getTicketIdByNum($number).",$type,'".$image."','".date("Y-m-d H:i:s")."','".$content."')");
	if($result){
		$wpdb->query("update ".$wpdb->prefix . "tickets set status=1 where number='".$number."'");
		return true;
	}
	return false;
}

function getTicketIdByNum($number){
	global $wpdb;
	$result = $wpdb->get_var("select id from ".$wpdb->prefix . "tickets where number='".$number."'");
	if($result > 0) return $result;
	return 0;
}

function getTicketByNum($number){
	global $wpdb;
	$result = $wpdb->get_row("select * from ".$wpdb->prefix . "tickets where number='".$number."'");
	return $result;
}

function checkTicketByNum($number){
	global $wpdb;
	$result = $wpdb->get_row("select id from ".$wpdb->prefix . "tickets where number='".$number."'");
	return $result;
}

function checkTicketIsMine($number,$uid){
	global $wpdb;
	if(current_user_can('administrator')){
		return true;
	}else{
		$result = $wpdb->get_row("select id from ".$wpdb->prefix . "tickets where number='".$number."' and user_id=".$uid);
		if($result) return true;
		else return false;
	}
}

function checkTicketIsClosed($number){
	global $wpdb;
	$result = $wpdb->get_row("select id from ".$wpdb->prefix . "tickets where number='".$number."' and status=3");
	if($result) return true;
	else return false;
}

function checkTicketCreateIsFast($uid){
	global $wpdb;
	$result = $wpdb->get_row("select id from ".$wpdb->prefix . "tickets where status < 2 and user_id=".$uid);
	if($result) return '1';
	else return '0';
}

function getTicketTypeNameByNum($number){
	global $wpdb;
	$result = $wpdb->get_var("select type from ".$wpdb->prefix . "tickets where number='".$number."'");
	if($result == '1') return '售前咨询';
	elseif($result == '2') return '售后服务';
	else return '其他';
}

function getTicketStatusNameByNum($number){
	global $wpdb;
	$result = $wpdb->get_var("select status from ".$wpdb->prefix . "tickets where number='".$number."'");
	if($result == '0') return '<font color="#EAB563">等待回复</font>';
	elseif($result == '1') return '<font color="red">已回复</font>';
	elseif($result == '2') return '<font color="#3FCF51">已完成</font>';
	elseif($result == '3') return '已关闭';
	else return '其他';
}

function createTicketNum(){
	$value = '1';
	for($i=0;$i<10;$i++){
		$value .= rand(0,9);
	}
	return $value;
}

function updateTicketSolved($number){
	global $wpdb;
	$result = $wpdb->query("update ".$wpdb->prefix . "tickets set status=2 where number='".$number."'");
	if($result) return true;
	return false;
}

function updateTicketClosed($number){
	global $wpdb;
	$result = $wpdb->query("update ".$wpdb->prefix . "tickets set status=3 where number='".$number."'");
	if($result) return true;
	return false;
}

function modown_ticket_new_html(){
	global $wpdb, $current_user;
	$uid = $current_user->ID;
	if(isset($_GET['id']) && $_GET['id']){
		$number = $wpdb->escape(trim($_GET['id']));
		if(checkTicketByNum($number) > 0 && checkTicketIsMine($number,$uid)){
		echo '<div class="ticket-item">
                <h3>工单：'.$number.'</h3>
                <div class="ticket-item-info">
                    '.get_user_by('id',getTicketByNum($number)->user_id)->user_login.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.getTicketTypeNameByNum($number).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.getTicketByNum($number)->create_time.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.getTicketStatusNameByNum($number).'
                    <input type="hidden" id="ticket-id" value="'.$number.'">
                </div>
                <div class="ticket-item-content">';
                $ticket_items = $wpdb->get_results("select * from ".$wpdb->prefix . "ticket_item where ticket_id=".getTicketIdByNum($number));
                if($ticket_items){
                    foreach($ticket_items as $item){  
                        echo    '<dl '.($item->type == '2' ? 'class="ticket-item-reply"' : '').'>
                                <dt><img src="'.MBThemes_get_avatar($item->user_id).'" class="avatar"></dt>
                                <dd>
                                '.str_replace("\n","<br>",$item->note);
                        $imgs = explode(',',$item->image);
                        $length = count($imgs);
                        if($item->image){
                            echo            '<div class="thumbs">';
                                    for($i=0;$i < $length;$i++){
                                        echo '<img class="ticket-thumb" src="'.$imgs[$i].'">';
                                    }
                                    
                            echo            '</div>';
                        }   
                        echo        '<time>'.$item->create_time.'</time>
                                </dd>
                            </dl>';
                    }
                }
                echo '</div>';
                if(getTicketByNum($number)->status < 2){
                    echo '<div class="ticket-item-status">
                        如果您的问题已得到解决，请点击：<a href="javascript:;" class="btn btn-danger ticket-solved">已解决</a>';
                    if(current_user_can('administrator')) echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:;" class="btn btn-primary ticket-close">关闭工单</a>';
                    echo '</div>
                    <form class="ticket-reply">
                        <h4>继续回复：</h4>
                        <p style="margin-bottom:20px;"><textarea name="content" id="content" class="form-control" id="" cols="30" rows="6" placeholder="请输入内容..." style="height: inherit;"></textarea><span style="font-size: 13px;">图片可上传到 <a href="https://sm.ms/" target="_blank" style="text-decoration:underline;">图床</a> 后在内容里贴上链接</span></p>';
                        if(_MBT('ticket_img')){
	                        echo '<div class="pics">
	                            <div class="pic pic-no ticket-upload">
	                                <i class="dripicons dripicons-plus"></i>
	                            </div>
	                            <div class="pic pic-no ticket-upload">
	                                <i class="dripicons dripicons-plus"></i>
	                            </div>
	                            <div class="pic pic-no ticket-upload">
	                                <i class="dripicons dripicons-plus"></i>
	                            </div>
	                        </div>
	                        <input class="ticket-file" type="file" style="display:none" accept="image/gif, image/jpeg, image/png">';
	                    }
                        echo '<div class="hdl">
                            <input type="button" class="btn btn-primary ticket-reply-submit" name="submit" value="提交回复">
                            <input type="hidden" name="id" value="'.$number.'">
                            <p class="text-muted">我们将尽快答复您，请耐心等待！</p>
                        </div>
                    </form><script type="text/javascript" src="'.get_bloginfo('template_url').'/static/js/ticket.js"></script>';
                }
                
            echo '</div>';
        }

	}else{
?>
<form class="ticket-new">
    <h3>提交新工单：</h3>
    <ul class="user-meta">
        <li>
            <label class="tit">工单类别：</label>
            <span class="radio-inline"><input name="item" value="1" type="radio">售前咨询</span>
            <span class="radio-inline"><input name="item" value="2" type="radio">售后服务</span>
            <span class="radio-inline"><input name="item" value="3" type="radio" checked>其他</span>
        </li>
        <li>
            <label class="tit">工单内容：</label>
            <textarea name="content" class="form-control" id="" cols="30" rows="8" placeholder="请输入内容..." style="max-width: 100%;padding: 6px 12px;height: inherit;"></textarea>
            <p style="font-size: 13px;">图片可上传到 <a href="https://sm.ms/" target="_blank" style="text-decoration:underline;">图床</a> 后在内容里贴上链接</p>
        </li>
        <li>
            <label class="tit">联系邮箱：</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="请输入邮箱..." style="width:30%" value="<?php echo $current_user->user_email;?>"></textarea>
        </li>
        <?php if(_MBT('ticket_img')){?>
        <li>
            <label class="tit">添加图片：</label>
            <div class="pics">
                <div class="pic pic-no ticket-upload">
                    <i class="dripicons dripicons-plus"></i>
                </div>
                <div class="pic pic-no ticket-upload">
                    <i class="dripicons dripicons-plus"></i>
                </div>
                <div class="pic pic-no ticket-upload">
                    <i class="dripicons dripicons-plus"></i>
                </div>
                <input class="ticket-file hide" type="file" style="display: none;" accept="image/gif, image/jpeg, image/png">
            </div>
        </li>
    	<?php }?>
        <li>
            <input type="button" class="btn btn-primary ticket-new-submit" name="submit" value="提交工单" style="float: right;">
            <br><br>
            <p style="float: right;margin-top: -25px;font-size:12px;">我们将尽快答复您，请耐心等待！</p>
        </li>
    </ul>
</form>
<script type="text/javascript" src="<?php bloginfo('template_url');?>/static/js/ticket.js"></script>
<?php
	}
}

function modown_ticket_list_html(){
	global $wpdb, $current_user;
	$uid = $current_user->ID;
	if(current_user_can('administrator')){
        $total_trade   = $wpdb->get_var("SELECT COUNT(id) FROM ".$wpdb->prefix . "tickets");
    }else{
        $total_trade   = $wpdb->get_var("SELECT COUNT(id) FROM ".$wpdb->prefix . "tickets WHERE user_id=".$uid);
    }
    $perpage = 15;
    if (!get_query_var('paged')) {
		$paged = 1;
	}else{
		$paged = get_query_var('paged');
	}
    $pagess = ceil($total_trade / $perpage);
    $offset = $perpage*($paged-1);
    if(current_user_can('administrator')){
        $lists = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "tickets order by create_time DESC limit $offset,$perpage");
    }else{
        $lists = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "tickets where user_id=".$uid." order by create_time DESC limit $offset,$perpage");
    }
    
?>
	<table class="table table-striped table-hover user-tickets"><thead><tr><th class=pc>工单ID</th><th>工单内容</th><th class=pc>类别</th><th class=pc>时间</th><th>状态</th></tr></thead><tbody>
		<?php foreach ($lists as $ticket) {?>
		<tr>
			<td class=pc><?php echo $ticket->number;?></td>
			<td><a href="<?php echo add_query_arg(array("action"=>"ticket","id"=>$ticket->number),get_permalink(MBThemes_page("template/user.php")));?>"><?php echo $ticket->note;?></a></td>
			<td class=pc><?php echo getTicketTypeNameByNum($ticket->number);?></td>
			<td class=pc><?php echo $ticket->create_time;?></td>
			<td><?php echo getTicketStatusNameByNum($ticket->number);?></td>
		</tr>
		<?php }?>
	</tbody></table>
	<?php MBThemes_custom_paging($paged,$pagess);?>
<?php
}

add_action('admin_menu', 'modown_ticket_menu');
function modown_ticket_menu() {
	add_management_page('工单列表', '工单列表', 'activate_plugins', 'modown_ticket_list', 'modown_ticket_list','dashicons-twitch');
}

function modown_ticket_list(){
	global $wpdb;
	if($_POST['delid'] > 0){
		$wpdb->query("delete from ".$wpdb->prefix . "tickets where id=".$_POST['delid']);
	}

	$total_trade   = $wpdb->get_var("SELECT COUNT(id) FROM ".$wpdb->prefix . "tickets");
	$ice_perpage = 20;
	$pages = ceil($total_trade / $ice_perpage);
	$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
	$offset = $ice_perpage*($page-1);
?>
<div class="wrap">
    <?php
   		$adds=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix . "tickets order by id DESC limit $offset,$ice_perpage");
    ?>
    <h3>工单列表</h3>

    <table class="widefat striped" style="width:100%;">
        <thead>
        <tr>
			<th width="10%">工单ID</th> 
            <th width="30%">内容</th>
            <th width="10%">用户</th>
            <th width="20%">提交时间</th>
            <th width="10%">状态</th>
            <th width="10%">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if($adds) {
            foreach($adds as $value)
            {
                echo "<tr>\n";
				echo "<td>$value->number</td>";
                echo "<td><a href='".add_query_arg(array("action"=>"ticket","id"=>$value->number),get_permalink(MBThemes_page("template/user.php")))."' target='_blank'>$value->note</td>";
                echo "<td>".get_user_by('id',$value->user_id)->user_login."</td>\n";
				echo "<td>$value->create_time</td>";
				echo "<td>".getTicketStatusNameByNum($value->number)."</td>";
				echo '<td><form method="post"><input type="hidden" name="delid" value="'.$value->id.'"><input type="submit" class="button" value="删除"></form></td>';
                echo "</tr>";
            }
        }
        else
        {
            echo '<tr><td colspan="6" align="center"><strong>没有记录</strong></td></tr>';
        }
        ?>
        </tbody>
    </table>
    <?php echo modown_ticket_admin_pagenavi($total_trade,$ice_perpage);?>		
</div>
<?php
}
	
function modown_ticket_admin_pagenavi($total_count, $number_per_page=15){

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
			<span class="displaying-num">每页 <?php echo $number_per_page;?> 共 <?php echo $total_count;?></span>
			<span class="pagination-links">
				<a class="first-page button <?php if($current_page==1) echo 'disabled'; ?>" title="前往第一页" href="<?php echo $first_page_url;?>">«</a>
				<a class="prev-page button <?php if($current_page==1) echo 'disabled'; ?>" title="前往上一页" href="<?php echo $prev_page_url;?>">‹</a>
				<span class="paging-input">第 <?php echo $current_page;?> 页，共 <span class="total-pages"><?php echo $total_pages; ?></span> 页</span>
				<a class="next-page button <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往下一页" href="<?php echo $next_page_url;?>">›</a>
				<a class="last-page button <?php if($current_page==$total_pages) echo 'disabled'; ?>" title="前往最后一页" href="<?php echo $last_page_url;?>">»</a>
			</span>
		</div>
		<br class="clear">
	</div>
	<?php
}

