<?php
/**
 * 公司布局“说明”模块
 */
?>
<div class="section-box">
	<?php
		$args = array(
			'post_type' => 'page', 
			'p' => zm_get_option('explain_p'),
		);
		query_posts($args);
	?>
	<?php while (have_posts()) : the_post(); ?>
	<div class="group-title wow fadeInUp" data-wow-delay="0.5s">
		<h3><a href="<?php the_permalink(); ?>" target="_blank" rel="bookmark"><?php echo zm_get_option('group_explain_t'); ?></a></h3>
		<div class="clear"></div>
	</div>

		<div class="group-explain">
			<div class="group-explain-main single-content wow fadeInUp" data-wow-delay="0.5s">
				<?php global $more; $more = 0; the_content( '' ); ?>
			</div>
			<div class="clear"></div>
		</div>
	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>