<?php
/**
 * 企业布局“关于”模块
 */
?>
<div class="section-box">
	<div class="group-title wow fadeInUp" data-wow-delay="0.5s">
		<h2><?php echo zm_get_option('group_contact_t'); ?></h2>
		<div class="clear"></div>
	</div>
	<?php
		$args = array(
			'post_type' => 'page', 
			'p' => zm_get_option('contact_p'),
		);
		query_posts($args);
	?>
	<?php while (have_posts()) : the_post(); ?>
		<div class="group-contact">
			<div class="group-contact-main single-content wow fadeInUp" data-wow-delay="0.5s">
				<?php global $more; $more = 0; the_content( '' ); ?>
			</div>
			<div class="clear"></div>
			<div class="group-contact-more wow fadeInUp" data-wow-delay="0.5s">
				<span class="group-more">
					<?php if ( zm_get_option('group_more_url') == '' ) { ?>
						<a href="<?php the_permalink(); ?>" target="_blank" rel="bookmark"><i class="fa fa-sticky-note-o"></i><?php echo zm_get_option('group_more_z'); ?></a>
					<?php } else { ?>
						<a href="<?php echo zm_get_option('group_more_url'); ?>" rel="bookmark" target="_blank"><i class="fa fa-sticky-note-o"></i> <?php echo zm_get_option('group_more_z'); ?></a>
					<?php } ?>
				</span>
				<span class="group-phone"><a href="<?php echo  zm_get_option('group_contact_url'); ?>" rel="bookmark" target="_blank"><i class="fa fa-phone"></i> <?php echo zm_get_option('group_contact_z'); ?></a></span>
				<div class="clear"></div>
			</div>
		</div>
	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>