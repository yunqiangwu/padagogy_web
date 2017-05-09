<?php 
	$allsearch = new WP_Query("s=$s&showposts=-1");
	$key = esc_html($s, 1);
	$count = $allsearch->post_count;
	echo $key; _e(' 找到 ', 'begin' );
	echo $count . ' '; _e(' 个与之相关的文章', 'begin' );
	wp_reset_query();
?>