<?php 
	if(function_exists('getPlayVipAdPv')){
		$vod_id = get_post_meta(get_the_ID(),'vod_id',true);
		$vod_ids = get_post_meta(get_the_ID(),'vod_ids',true);
		if($vod_ids){
			$video_index = 1;
			if(isset($_GET['vindex']) && $_GET['vindex']>0){
				$video_index = $_GET['vindex'];
			}
			$vod_id = $vod_ids['src'][$video_index-1];
		}
		if($vod_id){
			echo '<div class="single-video">'.do_shortcode('[erphpvideo]').'</div>';
		}
	}else{
		$videos = get_post_meta(get_the_ID(),'videos',true);
		$video = get_post_meta(get_the_ID(),'video',true);
		if($video || $videos){
			$player = _MBT('post_video_player')?_MBT('post_video_player'):'ckplayer';
			echo '<script src="'.get_bloginfo('template_url').'/module/'.$player.'/'.$player.'.min.js"></script>';
			if($player == 'dplayer'){
				echo '<script src="'.get_bloginfo('template_url').'/module/'.$player.'/hls.min.js"></script>';
			}
			$video_type = get_post_meta(get_the_ID(),'video_type',true);
			if(wp_is_erphpdown_active()){
				$video_erphpdown = get_post_meta(get_the_ID(),'video_erphpdown',true);
				$memberDown=get_post_meta(get_the_ID(), 'member_down',true);
				$days=get_post_meta(get_the_ID(), 'down_days', true);
				$price=get_post_meta(get_the_ID(), 'down_price', true);
				$start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
				$userType=getUsreMemberType();
				$video_menu_html = '';
				$video_price_text = '此视频观看价格为';

				$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
				$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
				$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

				if($videos){
					$video_index = 1;
					if(isset($_GET['vindex']) && $_GET['vindex']>0){
						$video_index = $_GET['vindex'];
					}
					$video = $videos['src'][$video_index-1];

					$cnt = count($videos['src']);
					if($cnt > 1){
						$video_menu_html .= '<div class="videos-menu">';
		                for($i=0; $i<$cnt;$i++){
		                	$alt = $videos['alt'][$i];
		                	$time = $videos['time'][$i];
		                	$class = ""; if($video_index == $i+1) $class="active";
		                	$video_menu_html .= '<div class="item"><a href="'.add_query_arg("vindex",$i+1,get_permalink()).'" rel="nofollow" class="'.$class.'">· '.$alt.'</a><span>'.$time.'</span></div>';
		                }
		                $video_menu_html .= '</div>';
		                $video_menu_html .= '<a href="javascript:;" class="vmenu-trigger"><i class="icon icon-arrow-double-left"></i></a>';
		            }
		            $video_price_text = '全套视频观看价格合计为';
				}

				if($video){
					if($video_erphpdown){
						$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
						$wppay = new EPD(get_the_ID(), $user_id);
						$erphp_url_front_vip = add_query_arg('action','vip',get_permalink(MBThemes_page("template/user.php")));

						if($start_down2){
							if($wppay->isWppayPaid() || !$price || ($memberDown == 3 && $userType)){
								if($video_type){
			    					echo '<div class="single-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>'.$video_menu_html.'</div>';
			    				}else{
				    				$nonce = wp_create_nonce(rand(10,1000));
				    				echo '<div class="single-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-video="'.trim($video).'"></div>'.$video_menu_html.'</div>';
								}
							}else{
								$video_content = '';
								if($memberDown == 3){
									$video_content .= $video_price_text.'<span class="erphpdown-price">'.$price.'</span>元<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">立即支付</a>&nbsp;&nbsp;<b>或</b>&nbsp;&nbsp;升级'.$erphp_vip_name.'后免费<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
								}else{
									$video_content .= $video_price_text.'<span class="erphpdown-price">'.$price.'</span>元<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">立即支付</a>';	
								}

								echo '<div class="single-video"><div class="'.$player.'-video '.$player.'-erphpdown-video"><div class="playicon"><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">'.$video_content.'</div></div>'.$video_menu_html.'</div>';
							}
						}else{
							if(is_user_logged_in()){
								$user_info=wp_get_current_user();
								$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and ice_user_id=".$user_info->ID." order by ice_time desc");
								if($days > 0){
									$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
									$nowDate = date('Y-m-d H:i:s');
									if(strtotime($nowDate) > strtotime($lastDownDate)){
										$down_info = null;
									}
								}
								if( (($memberDown==3 || $memberDown==4) && $userType) || $wppay->isWppayPaid() || $down_info || (($memberDown==6 || $memberDown==8) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14) && $userType == 10) ){

									if(!$wppay->isWppayPaid() && !$down_info){
										$erphp_life_times    = get_option('erphp_life_times');
										$erphp_year_times    = get_option('erphp_year_times');
										$erphp_quarter_times = get_option('erphp_quarter_times');
										$erphp_month_times  = get_option('erphp_month_times');
										$erphp_day_times  = get_option('erphp_day_times');
										if(checkDownHas($user_info->ID,get_the_ID())){
											if($video_type){
						    					echo '<div class="single-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>'.$video_menu_html.'</div>';
						    				}else{
							    				$nonce = wp_create_nonce(rand(10,1000));
							    				echo '<div class="single-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-video="'.trim($video).'"></div>'.$video_menu_html.'</div>';
											}
										}else{
											if($userType == 6 && $erphp_day_times > 0){
												if( checkSeeLog($user_info->ID,get_the_ID(),$erphp_day_times,erphpGetIP()) ){
													echo '<div class="single-video"><div class="'.$player.'-video '.$player.'-erphpdown-video"><div class="playicon"><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费观看此视频！<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-post="'.get_the_ID().'">立即观看</a>（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}else{
													echo '<div class="single-video"><div class="'.$player.'-video '.$player.'-erphpdown-video"><div class="playicon"><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权观看此视频，请明天再来！（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}
											}elseif($userType == 7 && $erphp_month_times > 0){
												if( checkSeeLog($user_info->ID,get_the_ID(),$erphp_month_times,erphpGetIP()) ){
													echo '<div class="single-video"><div class="'.$player.'-video '.$player.'-erphpdown-video"><div class="playicon"><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费观看此视频！<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-post="'.get_the_ID().'">立即观看</a>（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}else{
													echo '<div class="single-video"><div class="'.$player.'-video '.$player.'-erphpdown-video"><div class="playicon"><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权观看此视频，请明天再来！（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}
											}elseif($userType == 8 && $erphp_quarter_times > 0){
												if( checkSeeLog($user_info->ID,get_the_ID(),$erphp_quarter_times,erphpGetIP()) ){
													echo '<div class="single-video"><div class="'.$player.'-video '.$player.'-erphpdown-video"><div class="playicon"><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费观看此视频！<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-post="'.get_the_ID().'">立即观看</a>（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}else{
													echo '<div class="single-video"><div class="'.$player.'-video '.$player.'-erphpdown-video"><div class="playicon"><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权观看此视频，请明天再来！（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}
											}elseif($userType == 9 && $erphp_year_times > 0){
												if( checkSeeLog($user_info->ID,get_the_ID(),$erphp_year_times,erphpGetIP()) ){
													echo '<div class="single-video"><div class="'.$player.'-video '.$player.'-erphpdown-video"><div class="playicon"><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费观看此视频！<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-post="'.get_the_ID().'">立即观看</a>（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}else{
													echo '<div class="single-video"><div class="'.$player.'-video '.$player.'-erphpdown-video"><div class="playicon"><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权观看此视频，请明天再来！（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}
											}elseif($userType == 10 && $erphp_life_times > 0){
												if( checkSeeLog($user_info->ID,get_the_ID(),$erphp_life_times,erphpGetIP()) ){
													echo '<div class="single-video"><div class="'.$player.'-video '.$player.'-erphpdown-video"><div class="playicon"><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您可免费观看此视频！<a href="javascript:;" class="erphpdown-vip erphpdown-see-btn" data-post="'.get_the_ID().'">立即观看</a>（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}else{
													echo '<div class="single-video"><div class="'.$player.'-video '.$player.'-erphpdown-video"><div class="playicon"><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">您暂时无权观看此视频，请明天再来！（今日已观看'.getSeeCount($user_info->ID).'个，还可观看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</div></div>'.$video_menu_html.'</div>';
												}
											}else{
												if($video_type){
							    					echo '<div class="single-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>'.$video_menu_html.'</div>';
							    				}else{
								    				$nonce = wp_create_nonce(rand(10,1000));
								    				echo '<div class="single-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-video="'.trim($video).'"></div>'.$video_menu_html.'</div>';
												}
											}
										}
									}else{
					    				if($video_type){
					    					echo '<div class="single-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>'.$video_menu_html.'</div>';
					    				}else{
						    				$nonce = wp_create_nonce(rand(10,1000));
						    				echo '<div class="single-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-video="'.trim($video).'"></div>'.$video_menu_html.'</div>';
										}
									}
								}else{
									$video_content = '';
									if($price){
										if($memberDown != 4 && $memberDown != 8 && $memberDown != 9)
											$video_content.=$video_price_text.'<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');
									}else{
										if($memberDown != 4 && $memberDown != 8 && $memberDown != 9){
											if($video_type){
						    					echo '<div class="single-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>'.$video_menu_html.'</div>';
						    				}else{
							    				$nonce = wp_create_nonce(rand(10,1000));
							    				echo '<div class="single-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-video="'.trim($video).'"></div>'.$video_menu_html.'</div>';
											}
										}	
									}
									
									if($price || $memberDown == 4 || $memberDown == 8 || $memberDown == 9){
										if($memberDown > 1)
										{
											$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
											if($userType){
												$vipText = '';
												if(($memberDown == 13 || $memberDown == 14) && $userType < 10){
													$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
												}
											}
											if($memberDown==3 && $down_info==null){
												$video_content.='（'.$erphp_vip_name.'免费'.$vipText.'）';
											}elseif ($memberDown==2 && $down_info==null){
												$video_content.='（'.$erphp_vip_name.' 5折'.$vipText.'）';
											}elseif ($memberDown==5 && $down_info==null){
												$video_content.='（'.$erphp_vip_name.' 8折'.$vipText.'）';
											}elseif ($memberDown==13 && $down_info==null){
												$video_content.='（'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费'.$vipText.'）';
											}elseif ($memberDown==14 && $down_info==null){
												$video_content.='（'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费'.$vipText.'）';
											}elseif ($memberDown==6 && $down_info==null){
												if($userType < 9){
													$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
												}
												$video_content.='（'.$erphp_year_name.'免费'.$vipText.'）';
											}elseif ($memberDown==7 && $down_info==null){
												if($userType < 10){
													$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
												}
												$video_content.='（'.$erphp_life_name.'免费'.$vipText.'）';
											}elseif ($memberDown==4){
												if($userType){
													
												}
											}
										}

										if($memberDown==4 && $userType==FALSE){
											$video_content.='此视频仅限'.$erphp_vip_name.'观看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
										}elseif($memberDown==8 && $userType<9)
										{
											$video_content.='此视频仅限'.$erphp_year_name.'观看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
										}elseif($memberDown==9 && $userType<10)
										{
											$video_content.='此视频仅限'.$erphp_life_name.'观看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
										}
										else 
										{
											
											if($userType && $memberDown > 1)
											{
												if ($memberDown==2 && $down_info==null)
												{
													$video_content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank" >立即购买</a>';
												}
												elseif ($memberDown==5 && $down_info==null)
												{
													$video_content.='<a class="erphpdown-iframe erphpdown-buy"  href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank" >立即购买</a>';
												}
												elseif ($memberDown==6 && $down_info==null)
												{
													if($userType < 9){
														$video_content.='<a class="erphpdown-iframe erphpdown-buy"  href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank" >立即购买</a>';
													}
												}
												elseif (($memberDown==7 || $memberDown==13 || $memberDown==14) && $down_info==null)
												{
													if($userType < 10){
														$video_content.='<a class="erphpdown-iframe erphpdown-buy"  href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank" >立即购买</a>';
													}
												}
												elseif ($memberDown==10 && $down_info==null)
												{
													$video_content.='<a class="erphpdown-iframe erphpdown-buy"  href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank" >立即购买</a>';
												}
												elseif ($memberDown==11 && $down_info==null)
												{
													if($userType < 9){
														$video_content.='（'.$erphp_year_name.' 5折<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip" target="_blank">升级'.$erphp_year_name.'</a>）<a class="erphpdown-iframe erphpdown-buy"  href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank" >立即购买</a>';
													}else{
														$video_content.='（'.$erphp_year_name.' 5折）<a class="erphpdown-iframe erphpdown-buy"  href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank" >立即购买</a>';
													}
												}
												elseif ($memberDown==12 && $down_info==null)
												{
													if($userType < 9){
														$video_content.='（'.$erphp_year_name.' 8折<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip" target="_blank">升级'.$erphp_year_name.'</a>）<a class="erphpdown-iframe erphpdown-buy"  href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank" >立即购买</a>';
													}else{
														$video_content.='（'.$erphp_year_name.' 8折）<a class="erphpdown-iframe erphpdown-buy"  href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank" >立即购买</a>';
													}
												}
											}
											else 
											{
												if($memberDown==10){
													$video_content.='（仅限'.$erphp_vip_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip" target="_blank">升级'.$erphp_vip_name.'</a>';
												}elseif($memberDown==11){
													$video_content.='（仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip" target="_blank">升级'.$erphp_vip_name.'</a>';
												}elseif($memberDown==12){
													$video_content.='（仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip" target="_blank">升级'.$erphp_vip_name.'</a>';
												}else{
													$video_content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
												}
											}
										}
										echo '<div class="single-video"><div class="'.$player.'-video '.$player.'-erphpdown-video"><div class="playicon"><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">'.$video_content.'</div></div>'.$video_menu_html.'</div>';
									}
								}
							}elseif($wppay->isWppayPaid()){
								if($video_type){
			    					echo '<div class="single-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>'.$video_menu_html.'</div>';
			    				}else{
				    				$nonce = wp_create_nonce(rand(10,1000));
				    				echo '<div class="single-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-video="'.trim($video).'"></div>'.$video_menu_html.'</div>';
								}
							}else{
								$video_content = '';
								if($memberDown == 4 || $memberDown == 8 || $memberDown == 9){
									$video_content.='此视频仅限'.$erphp_vip_name.'观看，请先<a href="javascript:;" class="erphp-login-must signin-loader">登录</a>';
								}elseif($memberDown == 10 || $memberDown == 11 || $memberDown == 12){
									$video_content.=$video_price_text.'<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay').'（仅限'.$erphp_vip_name.'购买），请先<a href="javascript:;" class="erphp-login-must signin-loader">登录</a>';
								}else{
									if($price){
										$video_content.=$video_price_text.'<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');
										if($memberDown > 1){
											$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
											if($userType){
												$vipText = '';
												if(($memberDown == 13 || $memberDown == 14) && $userType < 10){
													$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
												}
											}
											if($memberDown==3 && $down_info==null){
												$video_content.='（'.$erphp_vip_name.'免费'.$vipText.'）';
											}elseif ($memberDown==2 && $down_info==null){
												$video_content.='（'.$erphp_vip_name.' 5折'.$vipText.'）';
											}elseif ($memberDown==5 && $down_info==null){
												$video_content.='（'.$erphp_vip_name.' 8折'.$vipText.'）';
											}elseif ($memberDown==13 && $down_info==null){
												$video_content.='（'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费'.$vipText.'）';
											}elseif ($memberDown==14 && $down_info==null){
												$video_content.='（'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费'.$vipText.'）';
											}elseif ($memberDown==6 && $down_info==null){
												if($userType < 9){
													$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
												}
												$video_content.='（'.$erphp_year_name.'免费'.$vipText.'）';
											}elseif ($memberDown==7 && $down_info==null){
												if($userType < 10){
													$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
												}
												$video_content.='（'.$erphp_life_name.'免费'.$vipText.'）';
											}elseif ($memberDown==4){
												if($userType){
													
												}
											}
										}

										$video_content.='，请先<a href="javascript:;" class="erphp-login-must signin-loader">登录</a>';
									}else{
										$video_content.='此视频可免费观看，请先<a href="javascript:;" class="erphp-login-must signin-loader">登录</a>';
									}
								}
								echo '<div class="single-video"><div class="'.$player.'-video '.$player.'-erphpdown-video"><div class="playicon"><i class="icon icon-play"></i></div><div class="erphpdown erphpdown-see erphpdown-content-vip" id="erphpdown" style="display:block">'.$video_content.'</div></div>'.$video_menu_html.'</div>';
							}
						}
					}else{
		            	if($video_type){
	    					echo '<div class="single-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>'.$video_menu_html.'</div>';
	    				}else{
		    				$nonce = wp_create_nonce(rand(10,1000));
		    				echo '<div class="single-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-video="'.trim($video).'"></div>'.$video_menu_html.'</div>';
						} 
					}
				}
			}else{
				if($videos){
					$video_index = 1;
					if(isset($_GET['vindex']) && $_GET['vindex']>0){
						$video_index = $_GET['vindex'];
					}
					$video = $videos['src'][$video_index-1];
					echo '<div class="single-video">';
					if($video_type){
						echo '<iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe>';
					}else{
	    				$nonce = wp_create_nonce(rand(10,1000));
	    				echo '<div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-video="'.trim($video).'"></div>';
					}
					$cnt = count($videos['src']);
					if($cnt > 1){
						echo '<div class="videos-menu">';
	                    for($i=0; $i<$cnt;$i++){
	                    	$alt = $videos['alt'][$i];
	                    	$time = $videos['time'][$i];
	                    	$class = ""; if($video_index == $i+1) $class="active";
	                    	echo '<div class="item"><a href="'.add_query_arg("vindex",$i+1,get_permalink()).'" rel="nofollow" class="'.$class.'">· '.$alt.'</a><span>'.$time.'</span></div>';
	                    }
	                    echo '</div>';
	                    echo '<a href="javascript:;" class="vmenu-trigger"><i class="icon icon-arrow-double-left"></i></a>';
	                }
	                echo '</div>';
				}elseif($video){
					if($video_type){
						echo '<div class="single-video"><iframe src="'.$video.'" class="'.$player.'-video" allowfullscreen="true"></iframe></div>';
					}else{
	    				$nonce = wp_create_nonce(rand(10,1000));
	    				echo '<div class="single-video"><div id="'.$player.'-video-'.$nonce.'" class="'.$player.'-video '.$player.'-video-real" data-nonce="'.$nonce.'" data-video="'.trim($video).'"></div></div>';
					}
				}
			}
		}
	}
?>