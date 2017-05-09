<div class="group-tab-site wow fadeInUp" data-wow-delay="0.5s">
	<div id="group-tab" class="group-tab-product">
	    <h2 class="group-tab-hd">
		    <span class="group-tab-hd-con"><a href="javascript:"><?php echo zm_get_option('anli_t'); ?></a></span>
		    <span class="group-tab-hd-con"><a href="javascript:"><?php echo zm_get_option('cp_t'); ?></a></span>
	    </h2>

		<div class="group-tab-bd group-dom-display">

			<div class="group-tab-bd-con group-current">
				<?php query_posts( array( 'showposts' => zm_get_option('group_tab_n'), 'cat' => zm_get_option('anli_id'), 'post__not_in' => $do_not_duplicate ) ); while (have_posts()) : the_post(); ?>

				<div class="xl4 xm4">
					<div id="post-<?php the_ID(); ?>" class="picture wow fadeInUp">
						<figure class="picture-h-img">
							<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
						</figure>
						<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<div class="group-tab-meta">
							<div class="group-date"><?php time_ago( $time_type ='post' ); ?></div>
							<?php if( function_exists( 'the_views' ) ) { the_views( true, '<div class="group-views"><i class="fa fa-eye"></i> ','</div>' ); } ?>
							<div class="clear"></div>
						</div>
					</div>
				</div>

				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
				<div class="clear"></div>
			</div>

			<div class="group-tab-bd-con">
				<?php query_posts( array( 'showposts' => zm_get_option('group_tab_n'), 'cat' => zm_get_option('cp_id'), 'post__not_in' => $do_not_duplicate ) ); while (have_posts()) : the_post(); ?>

				<div class="xl4 xm4">
					<div id="post-<?php the_ID(); ?>" class="picture">
						<figure class="picture-h-img">
							<?php zm_thumbnail(); ?>
						</figure>
						<?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
						<div class="group-tab-meta">
							<div class="group-date"><?php time_ago( $time_type ='post' ); ?></div>
							<?php if( function_exists( 'the_views' ) ) { the_views( true, '<div class="group-views"><i class="fa fa-eye"></i> ','</div>' ); } ?>
							<div class="clear"></div>
						</div>
					</div>
				</div>

				<?php endwhile; ?>
				<?php wp_reset_query(); ?>
				<div class="clear"></div>
			</div>

		</div>
	</div>
</div>