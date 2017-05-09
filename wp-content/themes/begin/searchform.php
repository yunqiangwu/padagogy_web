<div class="searchbar">
	<form method="get" id="searchform" action="<?php echo esc_url( home_url() ); ?>/">
		<span>
			<input type="text" value="<?php the_search_query(); ?>" name="s" id="s" placeholder="<?php _e( '输入搜索内容', 'begin' ); ?>" required />
			<button type="submit" id="searchsubmit"><?php _e( '搜索', 'begin' ); ?></button>
		</span>

		<?php if (zm_get_option('search_cat')) { ?>
		<p>
			<label for="all"><input type="radio" name="cat" id="all" value="all" checked ><?php _e( '全站', 'begin' ); ?></label>
			<label for="cat1"><input type="radio" name="cat" id="cat1" value="765" >WordPress</label>
			<label for="cat2"><input type="radio" name="cat" id="cat2" value="5" >WordPress主题</label>
			<label for="cat3"><input type="radio" name="cat" id="cat3" value="277" >网文资讯</label>
			<!-- 修改value=中的数字为分类ID -->
		</p>
		<?php } ?>
	</form>
</div>