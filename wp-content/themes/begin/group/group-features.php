<?php
/**
 * 企业布局“本站简介”模块
 */
?>

<div class="group-features">
	<div class="group-title wow fadeInUp" data-wow-delay="0.3s">
		<?php if ( zm_get_option('features_url') == '' ) { ?>
		<?php } else { ?>
			<div class="img-more"><a href="<?php echo zm_get_option('features_url'); ?>"><?php _e( '更多', 'begin' ); ?> <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></a></div>
		<?php } ?>
		<h3><?php echo zm_get_option('features_t'); ?></h3>
		<div><?php echo zm_get_option('features_des'); ?></div>
		<div class="clear"></div>
	</div>
	<div class="section-box">
		<?php query_posts('showposts='.zm_get_option('features_n').'&cat='.zm_get_option('features_id')); while (have_posts()) : the_post(); ?>
		<div class="g4">
			<div class="box-4 wow fadeInUp" data-wow-delay="0.3s">
				<figure class="section-thumbnail">
					<?php if (zm_get_option('lazy_s')) { tao_thumbnail_h(); } else { tao_thumbnail(); } ?>
					<a href="<?php the_permalink(); ?>" rel="bookmark"><div class="group-img-b"><div class="group-img-m"></div></div></a>
				</figure>
				<?php the_title( sprintf( '<h3 class="g4-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
			</div>
		</div>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
		<div class="clear"></div>
	</div>
</div>