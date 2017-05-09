<?php
/*
Template Name: 博客页面
*/
?>
<?php get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php if (zm_get_option('slider')) { ?>
				<?php
					if ( !is_paged() ) :
						get_template_part( 'inc/slider' );
					endif;
				?>
			<?php } ?>
			<?php
				$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
				$notcat = explode(',',zm_get_option('not_cat_n'));
				$args = array(
					'category__not_in' => $notcat,
				    'ignore_sticky_posts' => 0, 
					'paged' => $paged
				);
				query_posts( $args );
		 	?>
			<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'template/content', get_post_format() ); ?>

				<?php get_template_part('ad/ads', 'archive'); ?>

			<?php endwhile; ?>

			<?php else : ?>
				<?php get_template_part( 'template/content', 'none' ); ?>
			<?php endif; ?>

		</main><!-- .site-main -->

		<div class="pagenav-clear"><?php begin_pagenav(); ?></div>

	</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php if (zm_get_option('scroll')) { ?>
<script type="text/javascript">var ias=$.ias({container:"#main",item:"article",pagination:"#nav-below",next:"#nav-below .nav-previous a",});ias.extension(new IASTriggerExtension({text:'<i class="fa fa-chevron-circle-down"></i>更多',offset:<?php echo zm_get_option('scroll_n');?>,}));ias.extension(new IASSpinnerExtension());ias.extension(new IASNoneLeftExtension({text:'已是最后',}));ias.on('rendered',function(items){$("img").lazyload({effect: "fadeIn",failure_limit: 10});});ias.on('rendered',function(items){$(".picture-img,.img-box").hover(function(){$(this).find(".hide-box,.hide-excerpt,.img-title").fadeIn(500);},function(){$(this).find(".hide-box,.hide-excerpt,.img-title").hide();})})</script>
<?php } ?>
<script type="text/javascript">
    document.onkeydown = chang_page;function chang_page(e) {
        var e = e || event,
        keycode = e.which || e.keyCode;
        if (keycode == 37) location = '<?php echo get_previous_posts_page_link(); ?>';
        if (keycode == 39) location = '<?php echo get_next_posts_page_link(); ?>';
    }
</script>
<?php get_footer(); ?>