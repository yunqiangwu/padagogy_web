<div id="at-top">
	<?php query_posts( array ( 'meta_key' => 'cms_top', 'showposts' => zm_get_option('cms_top_n'), 'post__not_in' => get_option( 'sticky_posts') ) ); while ( have_posts() ) : the_post(); ?>
	<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp'); ?> data-wow-delay="0.3s">
		<div class="at-top-main">
			<figure class="small-thumbnail">
				<?php if (zm_get_option('lazy_s')) { zm_long_thumbnail_h(); } else { zm_long_thumbnail(); } ?>
				<?php the_title( sprintf( '<h2 class="cms-top-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</figure>
			<div class="clear"></div>
		</div>
	</article>
	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>