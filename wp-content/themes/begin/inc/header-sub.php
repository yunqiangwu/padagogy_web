<?php if ( is_archive() ) : ?>
	<?php if ( !is_paged() ) : ?>
		<?php if (zm_get_option('child_cat')) { ?>
			<?php child_cat(); ?>
		<?php } ?>
		<?php get_template_part( 'template/cat-top' ); ?>
	<?php endif; ?>

	<?php if (zm_get_option('cat_des')) { ?>
		<?php if ( !is_paged() && category_description()){ ?>
			<div class="header-sub">
				<?php 
					$imgurl=zm_get_option('cat_des_img');
					echo'<ul class="cat-des wow fadeInUp" data-wow-delay="0.3s" style="background: url('.$imgurl.');background-size: cover;">';
				?>
				<li class="des-title"><?php single_cat_title(); ?></li>
				<li><?php echo category_description(); ?></li>
				</ul>
			</div>
		<?php } ?>
	<?php } ?>
<?php endif; ?>