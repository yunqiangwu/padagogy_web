<style>
    .single-padagogy-img img{
        width: 100% !important;
    }
</style>
<div class="single-goods wow fadeInUp" data-wow-delay="0.3s">
	<?php 
		$loop = new WP_Query( array( 'post_type' => 'padagogy', 'orderby' => 'rand', 'posts_per_page' => 4 ) );
		while ( $loop->have_posts() ) : $loop->the_post();
	?>

	<div class="tl4 tm4">
		<div class="single-goods-main">
			<figure class="single-goods-img single-padagogy-img">
				<?php 
					if (zm_get_option('lazy_s')) { padagogy_thumbnail_h(); } else { padagogy_thumbnail(); }
				?>
			</figure>
			<div class="clear"></div>
		</div>
	</div>

	<?php endwhile; ?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>