<ul id="scroll">
	<li class="log log-no"><a class="log-button" title="<?php _e( '文章目录', 'begin' ); ?>"><i class="fa fa-bars"></i></a><div class="log-prompt"><div class="log-arrow"><?php _e( '文章目录', 'begin' ); ?></div></div></li>
	<li><a class="scroll-h" title="<?php _e( '返回顶部', 'begin' ); ?>"><i class="fa fa-angle-up"></i></a></li>
	<?php if(is_single() || is_page()) { ?><li><a class="scroll-c" title="<?php _e( '评论', 'begin' ); ?>"><i class="fa fa-comment-o"></i></a></li><?php } ?>
	<li><a class="scroll-b" title="<?php _e( '转到底部', 'begin' ); ?>"><i class="fa fa-angle-down"></i></a></li>
	<?php if (zm_get_option('gb2')) { ?><li class="gb2-site"><a id="gb2big5"><span>繁</span></a></li><?php } ?>
	<?php if (zm_get_option('qq_online')) { ?><?php get_template_part( 'inc/qqonline' ); ?><?php } ?>
	<?php if (zm_get_option('qr_img')) { ?>
		<li class="qr-site"><a href="javascript:void(0)" class="qr" title="<?php _e( '本页二维码', 'begin' ); ?>"><i class="fa fa-qrcode"></i><span class="qr-img"><span id="output"><img class="alignnone" src="<?php echo zm_get_option('qr_icon'); ?>" alt="icon"/></span><span class="arrow arrow-z"><i class="fa fa-caret-right"></i></span><span class="arrow arrow-y"><i class="fa fa-caret-right"></i></span></span></a></li>
		<script type="text/javascript">$(document).ready(function(){if(!+[1,]){present="table";} else {present="canvas";}$('#output').qrcode({render:present,text:window.location.href,width:"150",height:"150"});});</script>
	<?php } ?>
</ul>