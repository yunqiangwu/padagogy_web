<div id="slideshow">
<script type="text/javascript">
	$(document).ready(function(){
		$("#slider-home").responsiveSlides({
			auto: true,
			pager: true,
			nav: true,
			speed: 800,
			timeout: 5000,
			pause: true,
			maxwidth: 2000,
			namespace: "callbacks"
		});
	});
</script>
	<ul class="rslides" id="slider-home">
		<?php
			$args = array(
				'posts_per_page' => zm_get_option('group_slider_n'),
				'post_type' => 'page', 
				'meta_key' => 'guide_img',
				'ignore_sticky_posts' => 1
			);
			query_posts($args);
		?>
		<?php while (have_posts()) : the_post(); ?>
		<?php $image = get_post_meta($post->ID, 'guide_img', true); ?>
		<?php $group_slider_url = get_post_meta($post->ID, 'group_slider_url', true); ?>
			<li>
				<?php if (zm_get_option('group_slider_url')) { ?>
					<a href="<?php if ( get_post_meta($post->ID, 'group_slider_url', true) ) { ?><?php echo $group_slider_url; ?><?php } else { ?><?php the_permalink() ?><?php } ?>" rel="bookmark"><img src="<?php echo $image; ?>" alt="<?php the_title(); ?>" /></a>
				<?php } else { ?>
					<img src="<?php echo $image; ?>" />
				<?php } ?>


				<?php if (zm_get_option('group_slider_t')) { ?>
					<span class="group-slider-main wow fadeInUp" data-wow-delay="0.5s">
						<span class="group-slider-title">
						<?php if (zm_get_option('group_slider_url')) { ?>
							<a href="<?php if ( get_post_meta($post->ID, 'group_slider_url', true) ) { ?><?php echo $group_slider_url; ?><?php } else { ?><?php the_permalink() ?><?php } ?>" rel="bookmark"><?php the_title(); ?></a>
						<?php } else { ?>
							<?php the_title(); ?>
						<?php } ?>
						</span>
						<span class="group-slider-content">
							<?php the_content( '' ); ?>
						</span>
					</span>
				<?php } ?>
			</li>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>