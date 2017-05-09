<div class="searchbar">
	<form method="get" id="baiduform" action="<?php echo get_permalink( zm_get_option('baidu_url') ); ?>" target="_blank">
		<span>
			<input type="hidden" value="1" name="entry">
			<input class="swap_value" placeholder="<?php _e( '输入百度站内搜索关键词', 'begin' ); ?>" name="q">
			<button type="submit" id="searchbaidu"><?php _e( '百度', 'begin' ); ?></button>
		</span>
	</form>
</div>