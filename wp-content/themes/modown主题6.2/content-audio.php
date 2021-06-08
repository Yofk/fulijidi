<?php $audio = get_post_meta(get_the_ID(),'audio',true);$audio_time = get_post_meta(get_the_ID(),'audio_time',true);?>
<div class="post grid audio" data-audio="<?php echo $audio;?>" data-id="<?php the_ID();?>">
    <i class="audio-play"></i>
    <div class="info">
        <a class="title" target="_blank" href="<?php the_permalink();?>"><?php the_title();?></a>
        <a target="_blank" href="<?php the_permalink();?>" class="down"><i class="icon icon-download"></i> 下载</a>
    </div>
    <audio preload="none" id="audio-<?php the_ID();?>" data-time="<?php echo $audio_time?$audio_time:'0';?>">
        <source src="<?php echo $audio;?>" type="audio/mpeg">
    </audio>
    <span class="star-time">00:00</span>
    <div class="time-bar">
        <span class="progressBar"></span>
        <i class="move-color"></i>
        <p class="timetip"></p>
    </div>
    <span class="end-time"><?php echo mbt_sec_to_time($audio_time);?></span>
</div>