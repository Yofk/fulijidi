<?php 
if ( !defined('ABSPATH') ) {exit;}
if(!is_user_logged_in())
{
	wp_die('请登录系统');
}
global $wpdb;
if($_POST){
	if($_POST['delid']){
		$sql = "delete from $wpdb->erphpcard where id=".$wpdb->escape($_POST['delid']);
		$result=$wpdb->query($sql);
		if(!$result)
		{
			echo "<div id=message><div class='error settings-error'><p>添加失败！</p></div></div>";
		}else{echo "<div id=message><div class='updated settings-error'><p>添加成功！</p></div></div>";}
	}
}
$totals = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->erphpcard");
$perpage = 30;
$pages = ceil($totals / $perpage);
$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
$offset = $perpage*($page-1);
$results = $wpdb->get_results("select * from $wpdb->erphpcard order by id desc limit $offset,$perpage");

?>
<div class="wrap">
	<h2>充值卡列表</h2>
	<table class="wp-list-table widefat fixed posts">
            <thead>
            <tr>
				<th width="8%">序号</th>
				<th width="22%">卡号</th>
				<th width="15%">密码</th>
                <th width="15%">面值(元)</th>
				<th width="35%">使用</th>
				<th>删除</th>
            </tr>
            </thead>
            <tbody>
			<?php 
				if($results){
					foreach($results as $result){
						echo '<tr>';
						echo '<td>'.$result->id.'</td>';
						echo '<td>'.$result->card.'</td>';
						echo '<td>'.$result->password.'</td>';
						echo '<td>'.$result->price.'</td>';
						echo '<td>'.isErphpCardUsed($result->id).'</td>';
						echo "<td>";
						echo "<form method=post ><input type=hidden id=delid name=delid value=".$result->id."><input type=submit value=删除 class=button onclick=\"return confirm('确认删除?');\"></form>";
						echo "</td>";
						echo '</tr>';
					}
				}
			?>
			</tbody>
	</table>
<?php erphpcard_admin_pagenavi($totals,$perpage);?>
</div>