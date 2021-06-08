<?php
require( dirname(__FILE__) . '/../../../../wp-load.php' );
date_default_timezone_set('Asia/Shanghai');
if ( is_user_logged_in() && _MBT('ticket')) { 
	global $current_user; 
	$uid = $current_user->ID;

	if($_POST['action']=='ticket.new'){
		$item = trim(htmlspecialchars($wpdb->escape(trim($_POST['item'])), ENT_QUOTES));
        $content = trim(htmlspecialchars($wpdb->escape(trim($_POST['content'])), ENT_QUOTES));
        $email = trim(htmlspecialchars($wpdb->escape(trim($_POST['email'])), ENT_QUOTES));
        $image = $_POST['pic'];
        if(!empty($image)) $image = implode(',',$image);
        $number = createTicketNum();
        $error = 0;$msg = '';
        
       /* if(checkTicketCreateIsFast($uid) == 1){
            $error = 1;
            $msg = '您尚有工单未完成，可直接回复';
        }else{*/
            if($item && $content){
                createNewTicket($uid,$item,$number,mb_strimwidth( MBThemes_strip_tags( $content ), 0, "50","..."),$content,$wpdb->escape($image),$email);
                $ticket_id = $number;
                $to = get_option('admin_email');
                $subject = 'Hi，[' . get_option('blogname') . '] 有新工单啦！';
                $message = '' . "\r\n" . '    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse"><tbody><tr><td><table width="600" cellpadding="0" cellspacing="0" border="0" align="center" style="border-collapse:collapse"><tbody><tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr><td width="73" align="left" valign="top" style="border-top:1px solid #d9d9d9;border-left:1px solid #d9d9d9;border-radius:5px 0 0 0"></td><td valign="top" style="border-top:1px solid #d9d9d9"><div style="font-size:14px;line-height:10px"><br><br><br><br></div><div style="font-size:18px;line-height:18px;color:#444;font-family:Microsoft Yahei">Hi, 管理员<br><br><br></div><div style="font-size:14px;line-height:22px;color:#444;font-weight:bold;font-family:Microsoft Yahei">刚才有人提交了新工单！</div><div style="font-size:14px;line-height:10px"><br><br></div><div style="font-size:14px;line-height:22px;color:#5DB408;font-weight:bold;font-family:Microsoft Yahei">工单ID：</div><div style="font-size:14px;line-height:10px"><br></div><div style="font-size:14px;line-height:22px;color:#666;font-family:Microsoft Yahei">&nbsp; &nbsp;&nbsp; &nbsp; ' . $ticket_id . '</div><div style="font-size:14px;line-height:10px"><br><br><br><br></div><div style="text-align:center"><a href="' .get_permalink(MBThemes_page("template/user.php")). '?action=ticket&id='. $ticket_id . '" target="_blank" style="text-decoration:none;color:#fff;display:inline-block;line-height:44px;font-size:18px;background-color:#ff5f33;border-radius:3px;font-family:Microsoft Yahei">&nbsp; &nbsp;&nbsp; &nbsp;查看工单&nbsp; &nbsp;&nbsp; &nbsp;</a><br><br></div></td><td width="65" align="left" valign="top" style="border-top:1px solid #d9d9d9;border-right:1px solid #d9d9d9;border-radius:0 5px 0 0"></td></tr><tr><td style="border-left:1px solid #d9d9d9">&nbsp;</td><td align="left" valign="top" style="color:#999"><div style="font-size:8px;line-height:14px"><br><br></div><div style="min-height:1px;font-size:1px;line-height:1px;background-color:#e0e0e0">&nbsp;</div><div style="font-size:12px;line-height:20px;width:425px;font-family:Microsoft Yahei"><br>此邮件由系统自动发出，请勿回复！</div></td><td style="border-right:1px solid #d9d9d9">&nbsp;</td></tr><tr><td colspan="3" style="border-bottom:1px solid #d9d9d9;border-right:1px solid #d9d9d9;border-left:1px solid #d9d9d9;border-radius:0 0 5px 5px"><div style="min-height:42px;font-size:42px;line-height:42px">&nbsp;</div></td></tr></tbody></table></td></tr><tr><td><div style="min-height:42px;font-size:42px;line-height:42px">&nbsp;</div></td></tr></tbody></table></td></tr></tbody></table>';
                $headers = 'Content-Type: text/html; charset=' . get_option('blog_charset') . "\n";
                wp_mail($to, $subject, $message, $headers);
                
            }else{
                $error = 1;
                $msg = '系统错误，请稍后重试';
            }
        //}
        
        $arr=array(
            "error"=>$error, 
            "msg"=>$msg,
            "id"=>$ticket_id
        ); 
        $jarr=json_encode($arr); 
        echo $jarr;
	}elseif($_POST['action']=='ticket.reply'){
        $number = trim(htmlspecialchars($wpdb->escape(trim($_POST['id'])), ENT_QUOTES));
        $content = trim(htmlspecialchars($wpdb->escape(trim($_POST['content'])), ENT_QUOTES));
        $image = $_POST['pic'];
        if($image) $image = implode(',',$image);
        $error = 0;$msg = '';$admin = 0;
        if($number && $content && checkTicketByNum($number) > 0 && checkTicketIsMine($number,$uid)){
            if(checkTicketIsClosed($number)){
                $error = 1;
                $msg = '抱歉，工单已关闭';
            }else{
                if(current_user_can('administrator')){
                    $result = createNewReplyByAdmin($uid,2,$number,$content,$wpdb->escape($image));
                    $ticket_id = $number;
                    $to = getTicketByNum($number)->email;
                    $subject = 'Hi，[' . get_option('blogname') . '] 您的工单有新回复啦！';
                    $message = '' . "\r\n" . '    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse"><tbody><tr><td><table width="600" cellpadding="0" cellspacing="0" border="0" align="center" style="border-collapse:collapse"><tbody><tr><td><table width="100%" cellpadding="0" cellspacing="0" border="0"><tbody><tr><td width="73" align="left" valign="top" style="border-top:1px solid #d9d9d9;border-left:1px solid #d9d9d9;border-radius:5px 0 0 0"></td><td valign="top" style="border-top:1px solid #d9d9d9"><div style="font-size:14px;line-height:10px"><br><br><br><br></div><div style="font-size:18px;line-height:18px;color:#444;font-family:Microsoft Yahei">Hi, 用户<br><br><br></div><div style="font-size:14px;line-height:22px;color:#444;font-weight:bold;font-family:Microsoft Yahei">您的工单有新回复啦！</div><div style="font-size:14px;line-height:10px"><br><br></div><div style="font-size:14px;line-height:22px;color:#5DB408;font-weight:bold;font-family:Microsoft Yahei">工单ID：</div><div style="font-size:14px;line-height:10px"><br></div><div style="font-size:14px;line-height:22px;color:#666;font-family:Microsoft Yahei">&nbsp; &nbsp;&nbsp; &nbsp; ' . $ticket_id . '</div><div style="font-size:14px;line-height:10px"><br><br><br><br></div><div style="text-align:center"><a href="' .get_permalink(MBThemes_page("template/user.php")). '?action=ticket&id='. $ticket_id . '" target="_blank" style="text-decoration:none;color:#fff;display:inline-block;line-height:44px;font-size:18px;background-color:#ff5f33;border-radius:3px;font-family:Microsoft Yahei">&nbsp; &nbsp;&nbsp; &nbsp;查看工单&nbsp; &nbsp;&nbsp; &nbsp;</a><br><br></div></td><td width="65" align="left" valign="top" style="border-top:1px solid #d9d9d9;border-right:1px solid #d9d9d9;border-radius:0 5px 0 0"></td></tr><tr><td style="border-left:1px solid #d9d9d9">&nbsp;</td><td align="left" valign="top" style="color:#999"><div style="font-size:8px;line-height:14px"><br><br></div><div style="min-height:1px;font-size:1px;line-height:1px;background-color:#e0e0e0">&nbsp;</div><div style="font-size:12px;line-height:20px;width:425px;font-family:Microsoft Yahei"><br>此邮件由系统自动发出，请勿回复！</div></td><td style="border-right:1px solid #d9d9d9">&nbsp;</td></tr><tr><td colspan="3" style="border-bottom:1px solid #d9d9d9;border-right:1px solid #d9d9d9;border-left:1px solid #d9d9d9;border-radius:0 0 5px 5px"><div style="min-height:42px;font-size:42px;line-height:42px">&nbsp;</div></td></tr></tbody></table></td></tr><tr><td><div style="min-height:42px;font-size:42px;line-height:42px">&nbsp;</div></td></tr></tbody></table></td></tr></tbody></table>';
                    $headers = 'Content-Type: text/html; charset=' . get_option('blog_charset') . "\n";
                    wp_mail($to, $subject, $message, $headers);
                    $admin = 1;
                }else{
                    $result = createNewReply($uid,1,$number,$content,$wpdb->escape($image));
                }
                if($result){
                    $msg = '回复成功';
                }else{
                    $error = 1;
                    $msg = '回复失败，请稍后重试';
                }
            }
        }else{
            $error = 1;
            $msg = '回复异常';
        }
        
        $arr=array(
            "error"=>$error, 
            "msg"=>$msg,
            "admin"=>$admin,
            "pic"=>explode(',',$image),
            "avatar"=>get_avatar($uid,50),
            "content"=>$content
        ); 
        $jarr=json_encode($arr); 
        echo $jarr;
    }elseif($_POST['action']=='ticket.close'){
        $error = 0;$msg = '';
        if($_POST['id'] && checkTicketByNum($_POST['id']) > 0 && current_user_can('administrator')){
            if(updateTicketClosed($wpdb->escape(trim($_POST['id'])))){
                $msg = '提交成功';
            }else{
                $error = 1;
                $msg = '提交失败，请稍后重试';
            }
        }else{
            $error = 1;
                $msg = '工单异常';
        }
        
        $arr=array(
            "error"=>$error, 
            "msg"=>$msg
        ); 
        $jarr=json_encode($arr); 
        echo $jarr;
    }elseif($_POST['action']=='ticket.solved'){
        $error = 0;$msg = '';
        if($_POST['id'] && checkTicketByNum($_POST['id']) > 0 && checkTicketIsMine($_POST['id'],$uid)){
            if(updateTicketSolved($wpdb->escape(trim($_POST['id'])))){
                $msg = '提交成功';
            }else{
                $error = 1;
                $msg = '提交失败，请稍后重试';
            }
        }else{
            $error = 1;
                $msg = '工单异常';
        }
        
        $arr=array(
            "error"=>$error, 
            "msg"=>$msg
        ); 
        $jarr=json_encode($arr); 
        echo $jarr;
    }elseif($_POST['action']=='ticket.upload'){
        $error = 0;$msg = '';
        if(_MBT('ticket_img')){
            if(is_uploaded_file($_FILES['file']['tmp_name']) && is_user_logged_in()){
                $vname = $_FILES['file']['name'];
                $arrType=array('image/jpg','image/png','image/jpeg','image/gif');
                $uploaded_ext  = substr( $vname, strrpos( $vname, '.' ) + 1);
                $uploaded_type = $_FILES[ 'file' ][ 'type' ];
                $uploaded_tmp  = $_FILES[ 'file' ][ 'tmp_name' ];
                $uploaded_size  = $_FILES[ 'file' ][ 'size' ];

                if ($vname != "") {
                    if ($uploaded_size > 1024*1024) {
                        $msg = "上传的图片不能大于1M";
                        $error = 1;
                    }else{
                        if (in_array($uploaded_type,$arrType) && (strtolower( $uploaded_ext ) == 'jpg' || strtolower( $uploaded_ext ) == 'jpeg' || strtolower( $uploaded_ext ) == 'png' || strtolower( $uploaded_ext ) == 'gif')) {

                            //上传路径
                            $upfile = '../../../../wp-content/uploads/ticket/'.date("ym").'/';
                            if(!file_exists($upfile)){  mkdir($upfile,0777,true);} 

                            $filename = md5(date("His").mt_rand(100,999)).strrchr($vname,'.');

                            $file_path = '../../../../wp-content/uploads/ticket/'.date("ym").'/'. $filename;

                            if( $uploaded_type == 'image/jpeg' ) {
                                $img = imagecreatefromjpeg( $uploaded_tmp );
                                imagejpeg( $img, $file_path, 100);
                            }elseif( $uploaded_type == 'image/gif' ) {
                                $img = imagecreatefromgif( $uploaded_tmp );
                                imagegif( $img, $file_path);
                            }else {
                                $img = imagecreatefrompng( $uploaded_tmp );
                                imagepng( $img, $file_path);
                            }
                            imagedestroy( $img );

                            $image = home_url().'/wp-content/uploads/ticket/'.date("ym").'/'. $filename;

                        }else{
                            $msg = "上传的图片格式应是：jpeg、jpg、png、gif";
                            $error = 1;
                        }
                    }
                }

            }
            
            $arr=array(
                "error"=>$error, 
                "msg"=>$msg,
                "img"=>$image
            ); 
            $jarr=json_encode($arr); 
            echo $jarr;
        }
    }
}