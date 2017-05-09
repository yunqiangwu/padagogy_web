<?php
/**
 * 分类图片布局，使用标准缩略图尺寸
 */

get_header(); ?>

	<?php if ( !is_paged() ) :child_cat();	get_template_part( 'template/cat-top' ); endif; ?>
	<section id="picture" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php $posts = query_posts($query_string . '&orderby=date&showposts=16');?>
			<?php while ( have_posts() ) : the_post(); ?>
			<article class="picture wow fadeInUp" data-wow-delay="0.3s">
				<div class="picture-box">
					<figure class="picture-img">
						<?php if (zm_get_option('hide_box')) { ?>
							<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-box"></div></a>
							<a rel="bookmark" href="<?php echo esc_url( get_permalink() ); ?>"><div class="hide-excerpt"><?php if (has_excerpt('')){ echo wp_trim_words( get_the_excerpt(), 62, '...' ); } else { echo wp_trim_words( get_the_content(), 72, '...' ); } ?></div></a>
						<?php } ?>
						<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
						<?php if (function_exists('zm_link')) { zm_link(); } ?><span class="grid"><span class="fa fa-thumbs-o-up">&nbsp;<?php zm_get_current_count(); ?></span></span>
					</figure>
					<?php the_title( sprintf( '<h2 class="grid-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					<span class="grid-inf">
						<span class="date"><i class="fa fa-clock-o"></i> <?php the_time( 'm/d' ); ?> </span>
						<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="fa fa-eye"></i> ','</span>' ); } ?>
		 			</span>
		 			<div class="clear"></div>
				</div>
			</article>
			<?php endwhile;?>
		</main><!-- .site-main -->
		<?php begin_pagenav(); ?>
	</section><!-- .content-area -->
<?php get_footer(); ?>