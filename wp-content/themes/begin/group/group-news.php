<div class="group-news">
	<div class="group-title wow fadeInUp" data-wow-delay="0.3s">
		<h3><?php echo zm_get_option('group_new_t'); ?></h3>
		<span><?php echo zm_get_option('group_new_des'); ?></span>
		<div class="clear"></div>
	</div>
	<div class="group-news-content">
		<?php $recent = new WP_Query( array( 'posts_per_page' => zm_get_option('group_new_n'), 'category__not_in' => explode(',',zm_get_option('not_group_new'))) ); ?>
		<?php while($recent->have_posts()) : $recent->the_post(); $do_not_cat[] = $post->ID; ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp'); ?> data-wow-delay="0.3s">
			<figure class="thumbnail">
				<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
				<span class="cat"><?php zm_category(); ?></span>
			</figure>
			<header class="entry-header">
				<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			</header>

			<div class="entry-content">
				<div class="archive-content">
					<?php if (has_excerpt('')){
							echo wp_trim_words( get_the_excerpt(), 30, '...' );
						} else {
							$content = get_the_content();
							$content = wp_strip_all_tags(str_replace(array('[',']'),array('<','>'),$content));
							echo wp_trim_words( $content, 40, '...' );
				        }
					?>
				</div>
				<span class="entry-meta">
					<?php begin_entry_meta(); ?>
				</span>
				<div class="clear"></div>
			</div>
		</article>
		<?php endwhile; ?>
	</div>
	<div class="clear"></div>
</div>