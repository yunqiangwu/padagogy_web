<?php $display_categories =  explode(',',zm_get_option('group_cat_a_id') ); foreach ($display_categories as $category) { ?>
<?php query_posts( array( 'showposts' => 1, 'cat' => $category, 'post__not_in' => $do_not_cat ) ); ?>

<div class="gr2">
	<div class="gr-cat-box wow fadeInUp" data-wow-delay="0.5s">
		<h3 class="gr-cat-title"><a href="<?php echo get_category_link($category);?>" title="<?php echo strip_tags(category_description($category)); ?>"><?php single_cat_title(); ?></a></h3>
		<div class="gr-cat-more"><a href="<?php echo get_category_link($category);?>" title="<?php echo strip_tags(category_description($category)); ?>"><?php _e( '更多', 'begin' ); ?> <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></a></div>
		<div class="clear"></div>
		<div class="gr-cat-site">
			<?php if (zm_get_option('group_cat_a_top')) { ?>
				
				<?php query_posts( array ( 'category__in' => array(get_query_var('cat')), 'meta_key' => 'cat_top', 'showposts' => 1, 'ignore_sticky_posts' => 1 ) ); while ( have_posts() ) : the_post(); $do_not_cat[] = $post->ID;?>
					<div id="post-<?php the_ID(); ?>" class="gr-img-t wow fadeInUp" data-wow-delay="0.5s">
						<figure class="gr-thumbnail"><?php if (zm_get_option('lazy_s')) { zm_long_thumbnail_h(); } else { zm_long_thumbnail(); } ?></figure>
						<?php the_title( sprintf( '<h2 class="gr-title-img"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<div class="clear"></div>
					</div>
				<?php endwhile; ?>

				<div class="clear"></div>
				<div class="gr-cat-img">
					<?php query_posts( array( 'showposts' => zm_get_option('group_cat_a_n'), 'cat' => $category, 'offset' => 0, 'post__not_in' => $do_not_cat ) ); ?>
					<?php while ( have_posts() ) : the_post(); ?>
					<div class="cat-gr2">
						<div id="post-<?php the_ID(); ?>" class="gr-img wow fadeInUp" data-wow-delay="0.5s">
							<figure class="gr-a-thumbnail picture-h-img"><?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?></figure>
							<?php the_title( sprintf( '<div class="gr-img-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></div>' ); ?>
							<div class="clear"></div>
						</div>
					</div>
					<?php endwhile; ?>
					<?php wp_reset_query(); ?>
				</div>


			<?php } else { ?>

				<?php while ( have_posts() ) : the_post(); ?>
					<div id="post-<?php the_ID(); ?>" class="gr-img-t wow fadeInUp" data-wow-delay="0.5s">
						<figure class="gr-thumbnail"><?php if (zm_get_option('lazy_s')) { zm_long_thumbnail_h(); } else { zm_long_thumbnail(); } ?></figure>
						<?php the_title( sprintf( '<h2 class="gr-title-img"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<div class="clear"></div>
					</div>
				<?php endwhile; ?>

				<div class="clear"></div>
				<div class="gr-cat-img">
					<?php query_posts( array( 'showposts' => zm_get_option('group_cat_a_n'), 'cat' => $category, 'offset' => 1, 'post__not_in' => $do_not_cat ) ); ?>
					<?php while ( have_posts() ) : the_post(); ?>
					<div id="post-<?php the_ID(); ?>" class="cat-gr2 wow fadeInUp" data-wow-delay="0.5s">
						<div class="gr-img">
							<figure class="gr-a-thumbnail picture-h-img"><?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?></figure>
							<?php the_title( sprintf( '<div class="gr-img-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></div>' ); ?>
							<div class="clear"></div>
						</div>
					</div>
					<?php endwhile; ?>
					<?php wp_reset_query(); ?>
				</div>

			<?php } ?>
		</div>
	</div>

</div>
<?php } ?>
<div class="clear"></div>