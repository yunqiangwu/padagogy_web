<?php get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<!-- 幻灯 -->
			<?php if (zm_get_option('slider')) { ?>
				<?php require get_template_directory() . '/inc/slider.php'; ?>
			<?php } ?>
			<!-- 置顶 -->
			<?php if (zm_get_option('cms_top')) { ?><?php get_template_part( 'template/cms-top' ); ?><?php } ?>
			<!-- 最新文章 -->
			<?php if (zm_get_option('news')) { ?>
				<?php 
				if (!zm_get_option('news_model') || (zm_get_option("news_model") == 'news_normal')) {
					// 标准模式
					require get_template_directory() . '/cms/cms-news.php';
				}
				if (zm_get_option('news_model') == 'news_grid') {
					// 图文模式
					require get_template_directory() . '/cms/cms-news-grid.php';
				}
				if (zm_get_option('news_model') == 'news_list') {
					// 标题列表模式
					require get_template_directory() . '/cms/cms-news-list.php';
				}
				?>
			<?php } ?>
			<!-- 单栏小工具  -->
				<?php get_template_part( '/cms/cms-widget-one' ); ?>
			<!-- 单栏分类5篇 -->
			<?php if (zm_get_option('cat_one_5')) { ?>
				<div class="line-one">
					<?php require get_template_directory() . '/cms/cms-cat-one-5.php'; ?>
				</div>
			<?php } ?>
			<!-- 单栏分类10篇 -->
			<?php if (zm_get_option('cat_one_10')) { ?>
				<div class="line-one">
					<?php require get_template_directory() . '/cms/cms-cat-one-10.php'; ?>
				</div>
			<?php } ?>
			<!-- 图片日志 -->
			<?php if (zm_get_option('picture')) { ?>
				<div class="line-four">
					<?php get_template_part( '/cms/cms-picture' ); ?>
				</div>
			<?php } ?>
			<!-- 两栏小工具 -->
				<?php get_template_part( '/cms/cms-widget-two' ); ?>
			<!-- 视频日志 -->
			<?php if (zm_get_option('video')) { ?>
				<div class="line-four">
					<?php get_template_part( '/cms/cms-video' ); ?>
				</div>
			<?php } ?>
			<!-- 主体两栏分类 -->
			<?php if (zm_get_option('cat_small')) { ?>
				<div class="line-small">
					<?php require get_template_directory() . '/cms/cms-cat-small.php'; ?>
				</div>
			<?php } ?>
			<!-- TAB切换 -->
			<?php if (zm_get_option('tab_h')) { ?>
				<?php get_template_part( '/cms/cms-tab' ); ?>
			<?php } ?>

		</main><!-- .site-main -->
	</div><!-- .content-area -->
<!-- 侧边小工具 -->
<?php get_sidebar('cms'); ?>
<!-- 横向图片滚动 -->
<?php if (zm_get_option('flexisel')) { ?>
<div class="wow fadeInUp" data-wow-delay="0.3s">
	<?php get_template_part( '/cms/cms-scrolling' ); ?>
</div>
<?php } ?>
<!-- 底部分类 -->
<?php if (zm_get_option('cat_big')) { ?>
	<div class="line-big">
		<?php require get_template_directory() . '/cms/cms-cat-big.php'; ?>
	</div>
<?php } ?>
<!-- 淘客 -->
<?php if (zm_get_option('tao_h')) { ?>
	<div class="line-tao">
		<?php get_template_part( '/cms/cms-tao' ); ?>
	</div>
<?php } ?>
<!-- 下载 -->
<?php if (zm_get_option('cms_edd')) { ?>
	<div class="line-tab">
		<?php get_template_part( '/cms/cms-dow-tab' ); ?>
	</div>
<?php } ?>
<!-- 产品 -->
<?php if (zm_get_option('product_h')) { ?>
	<div class="line-tao">
		<?php get_template_part( '/cms/cms-product' ); ?>
	</div>
<?php } ?>
<!-- 无缩略图分类 -->
<?php if (zm_get_option('cat_big_not')) { ?>
	<div class="line-big">
		<?php require get_template_directory() . '/cms/cms-cat-big-n.php'; ?>
	</div>
<?php } ?>
<!-- 页脚 -->
<?php get_footer(); ?>