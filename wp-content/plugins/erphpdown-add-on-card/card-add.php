<?php 
if ( !defined('ABSPATH') ) {exit;}
if(!is_user_logged_in())
{
	wp_die('请登录系统');
}
global $wpdb;
if($_POST['createerphpcard']){
	$price = $wpdb->escape($_POST['erphpcard_price']);
	$num = $_POST['erphpcard_num'];
	$i=0;$out = '生成的充值卡如下：<br /><div>';
	for($i=0;$i < $num;$i++){
		$card = create_guid();
		$password = wp_create_nonce(rand(10,1000));
		$result = $wpdb->query("insert into $wpdb->erphpcard (card,password,price) values('".$card."','".$password."','".$price."')");
		$out .= $card.' '.$password.'<br />';
	}
	$out .='</div>';
	echo $out;
}

?>
<script type="text/javascript">
	function checkFm()
	{
		if(document.getElementById("erphpcard_num").value=="")
		{
			alert('请输入个数');
			return false;
		}
		if(document.getElementById("erphpcard_price").value=="")
		{
			alert('请输入面值');
			return false;
		}
		if(isNaN(document.getElementById("erphpcard_price").value))
		{
			alert('请输入正确的面值');
			return false;
		}
	}
</script>
<div class="wrap">
<?php if(!empty($text))
{
	echo '<div id="message">'.$text.'</div>';
} ?>
<h2 id="add-new-user"> 添加充值卡</h2>

<div id="ajax-response"></div>

<p>添加充值卡，便于用户充值使用，生成后会输出刚生成的充值卡，直接复制即可（格式支持www.100fk.com自动发卡平台）。</p>
<form action="" method="post" name="createerphpcard" id="createerphpcard" class="validate" onsubmit="return checkFm();">
<input name="action" type="hidden" value="createerphpcard">
<table class="form-table">
	<tbody>
    <tr class="form-field form-required">
		<th scope="row"><label for="erphpcard_name">个数 </label></th>
		<td><input name="erphpcard_num" type="text" id="erphpcard_num" value="1" aria-required="true" ></td>
	</tr>
	<tr class="form-field form-required">
		<th scope="row"><label for="erphpcard_price">面值 <span class="description">(单位：元  必填)</span></label></th>
		<td><input name="erphpcard_price" type="text" id="erphpcard_price" value="" placeholder="0.00"></td>
	</tr>
	</tbody>
</table>


<p class="submit"><input type="submit" name="createerphpcard" id="createerphpcardsub" class="button button-primary" value="添加充值卡"></p>
</form>
</div>
