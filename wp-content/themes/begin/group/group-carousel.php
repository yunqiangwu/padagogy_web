<div class="wow fadeInUp" data-wow-delay="0.5s">
	<div class="group-title">
		<h3><?php echo zm_get_option('group_carousel_t'); ?></h3>
		<span><?php echo zm_get_option('carousel_des'); ?></span>
		<div class="clear"></div>
	</div>

	<div id="owl" class="owl-carousel">
		<?php 
			if (zm_get_option('group_gallery')) {
				$loop = new WP_Query(array('tax_query' => array( array('taxonomy' => 'gallery', 'field' => 'id', 'terms' => explode(',',zm_get_option('group_gallery_id') ))), 'posts_per_page' => zm_get_option('carousel_n')) );
			} else {
				$loop = new WP_Query( array( 'cat' => zm_get_option('group_carousel_id'), 'posts_per_page' => zm_get_option('carousel_n'), 'post__not_in' => get_option( 'sticky_posts'), 'post__not_in' => $do_not_duplicate ) );
			}
			while ( $loop->have_posts() ) : $loop->the_post();
		?>
		<div id="post-<?php the_ID(); ?>" class="itemd wow fadeInUp" data-wow-delay="0.5s">
			<?php zm_thumbnail(); ?>
			<?php the_title( sprintf( '<h2 class="carousel-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<div class="group-tab-meta">
				<div class="group-date"><?php time_ago( $time_type ='post' ); ?></div>
				<?php if( function_exists( 'the_views' ) ) { the_views( true, '<div class="group-views"><i class="fa fa-eye"></i> ','</div>' ); } ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</div>
	<div class="clear"></div>

</div>

<?php $imgurl=zm_get_option('group_carousel_img');echo'<style type="text/css">.gtg{background: center top url('.$imgurl.')}</style>'?>