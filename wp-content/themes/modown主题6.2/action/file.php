<?php
require( dirname(__FILE__).'/../../../../wp-load.php' );
if(is_uploaded_file($_FILES['fileFile']['tmp_name']) && is_user_logged_in() && _MBT('tougao_upload')){
    $error = 1;$msg = '上传失败';$url = '';
    $vname = $_FILES['fileFile']['name'];
    $uploaded_type = $_FILES[ 'fileFile' ][ 'type' ];
    $arrType=array('.zip','.rar','.7z');
    $vtype = strtolower(strrchr($vname,'.'));
    if ($vname != "") {
        if (!in_array($vtype,$arrType)) {
            $msg = "附件格式只支持.zip .rar .7z";
        }else{
            if(_MBT('tougao_upload_media')){
                $wordpress_upload_dir = wp_upload_dir();
                $profilepicture = $_FILES['fileFile'];
                //$new_file_path = $wordpress_upload_dir['path'] . '/' . $profilepicture['name'];
                //$new_file_mime = mime_content_type( $profilepicture['tmp_name'] );
                $fname = md5(date("YmdHis").mt_rand(100,999)).strrchr($vname,'.');
                $new_file_path = $wordpress_upload_dir['path'] . '/' . $fname;
                 
                if( move_uploaded_file( $profilepicture['tmp_name'], $new_file_path ) ) {
                    $upload_id = wp_insert_attachment( array(
                        'guid'           => $new_file_path, 
                        'post_mime_type' => $uploaded_type,
                        'post_title'     => preg_replace( '/\.[^.]+$/', '', $profilepicture['name'] ),
                        'post_content'   => '',
                        'post_status'    => 'inherit'
                    ), $new_file_path );
                    require_once( ABSPATH . 'wp-admin/includes/image.php' );
                    wp_update_attachment_metadata( $upload_id, wp_generate_attachment_metadata( $upload_id, $new_file_path ) );
                    $url = $wordpress_upload_dir['url'] . '/' .$fname;
                    $error = 0;
                }
            }else{
                $filename = md5(date("YmdHis").mt_rand(100,999)).strrchr($vname,'.');
                //上传路径
                $upfile = '../../../../wp-content/uploads/modown/';
                if(!file_exists($upfile)){  mkdir($upfile,0777,true);} 
                $file_path = '../../../../wp-content/uploads/modown/'. $filename;
                if(move_uploaded_file($_FILES['fileFile']['tmp_name'], $file_path)){
                    $url = home_url().'/wp-content/uploads/modown/'. $filename;
                    $error = 0;
                }
            }

        }
    }
    $arr=array(
        "error"=>$error, 
        "msg"=>$msg,
        "url" => $url
    ); 
    $jarr=json_encode($arr); 
    echo $jarr;
}