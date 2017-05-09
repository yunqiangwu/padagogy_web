<div class="cms-new-box wow fadeInUp" data-wow-delay="0.3s">
	<h3 class="cms-new-title">最新文章</h3>
	<div class="clear"></div>
	<div class="cat-box">
		<div class="cat-site">
			<?php if (zm_get_option('cms_top')) { ?>
				<?php $recent = new WP_Query( array( 'posts_per_page' => zm_get_option('news_n'), 'category__not_in' => explode(',', zm_get_option('not_news_n')), 'post__not_in' => $do_show, 'meta_query' => array( array( 'key' => 'cms_top', 'compare' => 'NOT EXISTS'))));?>
			<?php } else { ?>
				<?php $recent = new WP_Query( array( 'posts_per_page' => zm_get_option('news_n'), 'post__not_in' => $do_show, 'category__not_in' => explode(',',zm_get_option('not_news_n'))) ); ?>
			<?php } ?>
			<?php while($recent->have_posts()) : $recent->the_post(); $do_not_duplicate[] = $post->ID; ?>
			<div id="post-<?php the_ID(); ?>" class="cms-new-list wow fadeInUp" data-wow-delay="0.3s">
				<figure class="thumbnail cms-new-thumbnail">
					<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
				</figure>

				<div class="cms-list-title">
					<h2><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a><?php get_template_part( 'inc/new-cms' ); ?></h2>
					<span class="cms-list-date"><?php the_time('Y/m/d') ?></span><span class="cms-list-cat"><?php zm_category(''); ?></span>
				</div>
			</div>
			<div class="clear"></div>
		<?php endwhile; ?>
		</div>
	</div>
</div>
<div class="wow fadeInUp" data-wow-delay="0.5s"><?php get_template_part('ad/ads', 'cms'); ?></div>