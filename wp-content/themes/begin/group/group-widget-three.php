<?php if (zm_get_option('group_widget_three')) { ?>
<div class="g-row">
	<div class="g-col">
		<div id="group-widget-three" class="group-widget wow fadeInUp" data-wow-delay="0.5s">
			<?php if ( ! dynamic_sidebar( 'group-three' ) ) : ?>
				<aside class="add-widgets">
					<a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“公司三栏小工具”添加小工具</a>
					<div class="clear"></div>
				</aside>
			<?php endif; ?>
			<div class="clear"></div>
		</div>
	</div>
</div>
<?php } ?>