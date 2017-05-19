<section class="no-results not-found">
	
	<div class="post">

		<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>

			<p>该分类下目前还没有APP！</p>
			<p><a href="<?php echo get_option('siteurl'); ?>/wp-admin/post-new.php">点击这里发布您的APP</a></p>

		<?php elseif ( is_search() ) : ?>

			<header class="entry-header">
				<h1 class="page-title">没有您要找的APP！</h1>
			</header><!-- .page-header -->

			<p>可以尝试使用下面的搜索功能，查找您喜欢的APP！</p>
			<?php get_padagogy_search_form(); ?>
			<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
		<?php else : ?>

			<p>目前还没有该类APP！可以尝试使用下面的搜索功能，查找您喜欢的APP！</p>
			<?php get_padagogy_search_form(); ?>
			<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />

		<?php endif; ?>

	</div><!-- .page-content -->
</section><!-- .no-results -->
