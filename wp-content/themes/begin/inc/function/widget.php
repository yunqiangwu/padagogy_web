<?php
// 最新文章
class new_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'new_cat',
			'description' => __( '最新文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('new_cat', '主题&nbsp;&nbsp;最新文章', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

<div class="new_cat">
	<ul>
		<?php $q = 'showposts='.$instance['numposts']; if (!empty($instance['cat'])) $q .= '&cat='.$instance['cat']; query_posts($q); while (have_posts()) : the_post(); ?>
		<li>
			<span class="thumbnail">
				<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
			</span>
			<span class="new-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
			<span class="date"><?php the_time('m/d') ?></span>
			<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="fa fa-eye"></i> ','</span>' ); } ?>
		</li>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '最新文章',
		'numposts' => 5,
		'cat' => 0)); ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
<?php }
}

add_action( 'widgets_init', create_function( '', 'register_widget( "new_cat" );' ) );

// 分类最新文章
class post_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'post_cat',
			'description' => __( '以列表形式调用一个分类的最新文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('post_cat', '主题&nbsp;&nbsp;分类文章', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

<div class="post_cat">
	<ul>
		<?php $q = 'showposts='.$instance['numposts']; if (!empty($instance['cat'])) $q .= '&cat='.$instance['cat']; query_posts($q); while (have_posts()) : the_post(); ?>
			<?php the_title( sprintf( '<li class="cat-title"><i class="fa fa-angle-right"></i><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></li>' ); ?>
		<?php endwhile; ?>
		<?php wp_reset_query(); ?>
	</ul>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类文章',
		'numposts' => 5,
		'cat' => 0)); ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
<?php }
}

add_action( 'widgets_init', create_function( '', 'register_widget( "post_cat" );' ) );

// 分类文章（图片）
class img_cat extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'img_cat',
			'description' => __( '以图片形式调用一个分类的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('img_cat', '主题&nbsp;&nbsp;分类图片', $widget_ops);
	}

	function widget( $args, $instance ) {
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

<div class="picture img_cat">
	<?php $q = 'showposts='.$instance['numposts']; if (!empty($instance['cat'])) $q .= '&cat='.$instance['cat']; query_posts($q); while (have_posts()) : the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<span class="img-title"><a href="<?php the_permalink() ?>" rel="bookmark"><?php echo wp_trim_words( get_the_title(), 26 ); ?></a></span>
				<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
}

function update( $new_instance, $old_instance ) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
	$instance['numposts'] = $new_instance['numposts'];
	$instance['cat'] = $new_instance['cat'];
	return $instance;
}

function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '分类图片',
		'numposts' => 4,
		'cat' => 0)); ?> 

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('numposts'); ?>">显示篇数：</label> 
			<input id="<?php echo $this->get_field_id('numposts'); ?>" name="<?php echo $this->get_field_name('numposts'); ?>" type="text" value="<?php echo $instance['numposts']; ?>" size="3" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 全部分类, 'hide_empty'=>0, 'hierarchical'=>1, 'selected'=>$instance['cat'])); ?></label>
		</p>
<?php }
}

add_action( 'widgets_init', create_function( '', 'register_widget( "img_cat" );' ) );

// 近期留言
class recent_comments extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'recent_comments',
			'description' => __( '带头像的近期留言' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('recent_comments', '主题&nbsp;&nbsp;近期留言', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<div id="message" class="message-widget">
	<ul>
		<?php
		$show_comments = $number;
		$my_email = get_bloginfo ('admin_email');
		$i = 1;
		$comments = get_comments('number=200&status=approve&type=comment');
		foreach ($comments as $my_comment) {
			if ($my_comment->comment_author_email != $my_email) {
				?>
				<li>
					<a href="<?php echo get_permalink($my_comment->comment_post_ID); ?>#anchor-comment-<?php echo $my_comment->comment_ID; ?>" title="<?php echo get_the_title($my_comment->comment_post_ID); ?>" rel="external nofollow">
						<?php echo get_avatar($my_comment->comment_author_email,64, '', $my_comment->comment_author); ?>
						<span class="comment_author"><strong><?php echo $my_comment->comment_author; ?></strong></span>
						<?php echo convert_smilies($my_comment->comment_content); ?>
					</a>
				</li>
				<?php
				if ($i == $show_comments) break;
				$i++;
			}
		}
		?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '近期评论';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "recent_comments" );' ) );

// 热门文章
class hot_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_post',
			'description' => __( '调用点击最多的文章，必须安装 wp-postviews 插件,并有统计数据' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_post', '主题&nbsp;&nbsp;热门文章', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>

<div id="hot_post_widget">
	<ul>
	    <?php if (function_exists('get_most_viewed')): ?> 
	    <?php get_timespan_most_viewed('post',$number,$days, true, true); ?>
	    <?php endif; ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['days'] = strip_tags($new_instance['days']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '热门文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<p><label for="<?php echo $this->get_field_id('days'); ?>">时间限定（天）：</label>
	<input id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="text" value="<?php echo $days; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "hot_post" );' ) );

// 热评文章
class hot_comment extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_comment',
			'description' => __( '调用评论最多的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_comment', '主题&nbsp;&nbsp;热评文章', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>

<div id="hot_comment_widget">
	<ul>
		<?php hot_comment_viewed($number, $days); ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['days'] = strip_tags($new_instance['days']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '热评文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<p><label for="<?php echo $this->get_field_id('days'); ?>">时间限定（天）：</label>
	<input id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="text" value="<?php echo $days; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "hot_comment" );' ) );

// 随机文章
class random_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'random_post',
			'description' => __( '显示随机文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('random_post', '主题&nbsp;&nbsp;随机文章', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<div id="random_post_widget">
	<ul>
		<?php
		$cat = get_the_category();
		foreach($cat as $key=>$category){
		    $catid = $category->term_id;
		}
		$args = array( 'orderby' => 'rand', 'showposts' => $number, 'ignore_sticky_posts' => 10,'cat' => $catid );
		$query_posts = new WP_Query();
		$query_posts->query($args);
		while ($query_posts->have_posts()) : $query_posts->the_post();
		?>
		<li><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></li>
		<?php endwhile;?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '随机文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "random_post" );' ) );

// 标签云
class cx_tag_cloud extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'cx_tag_cloud',
			'description' => __( '可实现3D特效' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('cx_tag_cloud', '主题&nbsp;&nbsp;热门标签', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 20;
?>
<?php if (zm_get_option("3dtag")) { ?>
	<div id="tag_cloud_widget">
<?php } else { ?>
	<div class="tagcloud">
<?php } ?>
	<?php wp_tag_cloud( array ('taxonomy' => array('post_tag','app_classification'), 'smallest' => '14', 'largest' => 14, 'unit' => 'px', 'order' => 'RAND', 'number' => $number ) ); ?>
	<div class="clear"></div>
	<?php if (zm_get_option("3dtag")) : ?><?php wp_enqueue_script( '3dtag.min', get_template_directory_uri() . '/js/3dtag.js', array(), '', false ); ?><?php endif; ?>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '热门标签';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '20'));
		$number = strip_tags($instance['number']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "cx_tag_cloud" );' ) );

// 相关文章
class related_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'related_post',
			'description' => __( '显示相关文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('related_post', '主题&nbsp;&nbsp;相关文章', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<div id="related_post_widget">
	<ul>
		<?php
			$post_num = $number;
			global $post;
			$tmp_post = $post;
			$tags = ''; $i = 0;
			if ( get_the_tags( $post->ID ) ) {
			foreach ( get_the_tags( $post->ID ) as $tag ) $tags .= $tag->slug . ',';
			$tags = strtr(rtrim($tags, ','), ' ', '-');
			$myposts = get_posts('numberposts='.$post_num.'&tag='.$tags.'&exclude='.$post->ID);
			foreach($myposts as $post) {
			setup_postdata($post);
		?>
		<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
		<?php
			$i += 1;
			}
			}
			if ( $i < $post_num ) {
			$post = $tmp_post; setup_postdata($post);
			$cats = ''; $post_num -= $i;
			foreach ( get_the_category( $post->ID ) as $cat ) $cats .= $cat->cat_ID . ',';
			$cats = strtr(rtrim($cats, ','), ' ', '-');
			$myposts = get_posts('numberposts='.$post_num.'&category='.$cats.'&exclude='.$post->ID);
			foreach($myposts as $post) {
			setup_postdata($post);
		?>
		<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
		<?php
		}
		}
		$post = $tmp_post; setup_postdata($post);
		?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '相关文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "related_post" );' ) );

// 本站推荐
class hot_commend extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_commend',
			'description' => __( '调用添加自定义栏目“hot”的文章（有缩略图）' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_commend', '主题&nbsp;&nbsp;本站推荐', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<div id="hot" class="hot_commend">
	<ul>
		<?php query_posts( array ( 'meta_key' => 'hot', 'showposts' => $number, 'ignore_sticky_posts' => 1 ) ); while ( have_posts() ) : the_post(); ?>
			<li>
				<span class="thumbnail">
					<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
				</span>
				<span class="hot-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<?php if( function_exists( 'the_views' ) ) { the_views( true, '<span class="views"><i class="fa fa-eye"></i> ','</span>' ); } ?>
				<?php if (function_exists('zm_link')) { zm_link(); } ?><i class="fa fa-thumbs-o-up">&nbsp;<?php zm_get_current_count(); ?></i>
			</li>
		<?php endwhile;?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '本站推荐';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "hot_commend" );' ) );

// 推荐文章
class hot_posts extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'hot_posts',
			'description' => __( '调用添加自定义栏目“posts”的文章（无缩略图）' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('hot_posts', '主题&nbsp;&nbsp;推荐文章', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<div id="hot_article" class="hot_article">
	<ul>
		<?php $i = 1; query_posts( array ( 'meta_key' => 'posts', 'showposts' => $number, 'ignore_sticky_posts' => 1 ) ); while ( have_posts() ) : the_post(); ?>
			<li>
				<?php if($i < 4) { ?>
					<span class="hotp-title"><span class='li-number'><?php echo($i++); ?></span><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<?php } else { ?>
					<span class="hotp-title"><span class='li-numbers'><?php echo($i++); ?></span><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></span>
				<?php } ?>
			</li>
		<?php endwhile;?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '推荐文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "hot_posts" );' ) );

// 大家喜欢
class like_most extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'like_most',
			'description' => __( '调用点击喜欢最多的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('like_most', '主题&nbsp;&nbsp;大家喜欢', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>

<div id="like" class="like_most">
	<ul>
		<?php if (function_exists('get_most_viewed')): ?> 
		<?php get_like_most('post',$number,$days, true, true); ?>
		<?php endif; ?>
		<?php wp_reset_query(); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['days'] = strip_tags($new_instance['days']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '大家喜欢';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<p><label for="<?php echo $this->get_field_id('days'); ?>">时间限定（天）：</label>
	<input id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="text" value="<?php echo $days; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "like_most" );' ) );

// 读者墙
class readers extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'readers',
			'description' => __( '最活跃的读者' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('readers', '主题&nbsp;&nbsp;读者墙', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 6;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 90;
?>

<div id="readers_widget" class="readers">
	<?php
		global $wpdb;
		  $counts = wp_cache_get( 'mostactive' );

		  if ( false === $counts ) {
		    $counts = $wpdb->get_results("SELECT COUNT(comment_author) AS cnt, comment_author, comment_author_url, comment_author_email
		        FROM {$wpdb->prefix}comments
		        WHERE comment_date > date_sub( NOW(), INTERVAL $days DAY )
		            AND comment_approved = '1'
		            AND comment_author_email != 'example@example.com'
		            AND comment_author_email != ''
		            AND comment_author_url != ''
		            AND comment_type = ''
		            AND user_id = '0'
		        GROUP BY comment_author_email
		        ORDER BY cnt DESC
		        LIMIT $number");
		  }

		  $mostactive = '';

		  if ( $counts ) {
		    wp_cache_set( 'mostactive', $counts );

		    foreach ($counts as $count) {
		      $c_url = $count->comment_author_url;
		      $mostactive .= '<div class="readers-avatar"><span>' . '<a href="'. get_template_directory_uri()."/inc/go.php?url=".$c_url . '" title="' . $count->comment_author .'  '. $count->cnt . ' 个脚印" target="_blank" rel="external nofollow">' . get_avatar($count->comment_author_email, 48, '', $count->comment_author . ' 发表 ' . $count->cnt . ' 条评论') . '</a></span></div>';
		    }
		  echo $mostactive;
		  }
	?>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['days'] = strip_tags($new_instance['days']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '读者墙';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '6'));
		$instance = wp_parse_args((array) $instance, array('days' => '90'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<p><label for="<?php echo $this->get_field_id('days'); ?>">时间限定（天）：</label>
	<input id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="text" value="<?php echo $days; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "readers" );' ) );

// 关注我们
class feed extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'feed',
			'description' => __( 'RSS、微信、微博' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('feed', '主题&nbsp;&nbsp;关注我们', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
?>

<div id="feed_widget">
	<div class="feed-rss">
		<ul>
			<li class="weixin">
				<span class="tipso_style" id="tip-w-j" data-tipso='<span class="weixin-qr"><img src="<?php echo $instance['weixin']; ?>" alt="weixin"/></span>'><a title="微信"><i class="fa fa-weixin"></i></a></span>
			</li>
			<li class="feed"><a title="订阅" href="<?php echo esc_url( home_url('/') ); ?>feed/" target="_blank" rel="external nofollow"><i class="fa fa-rss"></i></a></li>
			<li class="tsina"><a title="" href="<?php echo $instance['tsinaurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['tsina']; ?>"></i></a></li>
			<li class="tqq"><a title="" href="<?php echo $instance['tqqurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['tqq']; ?>"></i></a></li>
		</ul>
	</div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['weixin'] = $new_instance['weixin'];
			$instance['tsina'] = $new_instance['tsina'];
			$instance['tsinaurl'] = $new_instance['tsinaurl'];
			$instance['tqq'] = $new_instance['tqq'];
			$instance['tqqurl'] = $new_instance['tqqurl'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '关注我们';
		}
		global $wpdb;
		$weixin = $instance['weixin'];
		$instance = wp_parse_args((array) $instance, array('tsina' => 'fa fa-weibo'));
		$tsina = $instance['tsina'];
		$instance = wp_parse_args((array) $instance, array('tsinaurl' => '输入链接地址'));
		$tsinaurl = $instance['tsinaurl'];
		$instance = wp_parse_args((array) $instance, array('tqq' => 'fa fa-tencent-weibo'));
		$tqq = $instance['tqq'];
		$instance = wp_parse_args((array) $instance, array('tqqurl' => '输入链接地址'));
		$tqqurl = $instance['tqqurl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('weixin'); ?>">微信二维码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'weixin' ); ?>" name="<?php echo $this->get_field_name( 'weixin' ); ?>" type="text" value="<?php echo $weixin; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsina' ); ?>" name="<?php echo $this->get_field_name( 'tsina' ); ?>" type="text" value="<?php echo $tsina; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsinaurl' ); ?>" name="<?php echo $this->get_field_name( 'tsinaurl' ); ?>" type="text" value="<?php echo $tsinaurl; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tqq'); ?>">腾讯微博图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqq' ); ?>" name="<?php echo $this->get_field_name( 'tqq' ); ?>" type="text" value="<?php echo $tqq; ?>" />
	</p>
	</p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">腾讯微博地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqqurl' ); ?>" name="<?php echo $this->get_field_name( 'tqqurl' ); ?>" type="text" value="<?php echo $tqqurl; ?>" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "feed" );' ) );

// 关于本站
class about extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'about',
			'description' => __( '本站信息、RSS、微信、微博、QQ' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('about', '主题&nbsp;&nbsp;关于本站', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
?>

<div id="feed_widget">
	<div class="feed-about">
		<div class="about-main">
			<div class="about-img">
				<img src="<?php echo $instance['about_img']; ?>" alt="QR Code"/>
			</div>
			<div class="about-name"><?php echo $instance['about_name']; ?></div>
			<div class="about-the"><?php echo $instance['about_the']; ?></div>
		</div>
		<div class="clear"></div>
		<ul>
			<li class="weixin">
				<span class="tipso_style" id="tip-w" data-tipso='<span class="weixin-qr"><img src="<?php echo $instance['weixin']; ?>" alt=" weixin"/></span>'><a title="微信"><i class="fa fa-weixin"></i></a></span>
			</li>
			<li class="tqq"><a target=blank rel="external nofollow" href=http://wpa.qq.com/msgrd?V=3&uin=<?php echo $instance['tqqurl']; ?>&Site=QQ&Menu=yes title="QQ在线"><i class="<?php echo $instance['tqq']; ?>"></i></a></li>
			<li class="tsina"><a title="" href="<?php echo $instance['tsinaurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['tsina']; ?>"></i></a></li>
			<li class="feed"><a title="" href="<?php echo $instance['rssurl']; ?>" target="_blank" rel="external nofollow"><i class="<?php echo $instance['rss']; ?>"></i></a></li>
		</ul>
		<div class="about-inf">
			<span class="about-pn"><?php _e( '文章', 'begin' ); ?> <?php $count_posts = wp_count_posts(); echo $published_posts = $count_posts->publish;?></span>
			<span class="about-cn"><?php _e( '留言', 'begin' ); ?> <?php global $wpdb;	echo $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments");?></span>
		</div>
	</div>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['about_img'] = $new_instance['about_img'];
			$instance['about_name'] = $new_instance['about_name'];
			$instance['about_the'] = $new_instance['about_the'];
			$instance['weixin'] = $new_instance['weixin'];
			$instance['tsina'] = $new_instance['tsina'];
			$instance['tsinaurl'] = $new_instance['tsinaurl'];
			$instance['rss'] = $new_instance['rss'];
			$instance['rssurl'] = $new_instance['rssurl'];
			$instance['tqq'] = $new_instance['tqq'];
			$instance['tqqurl'] = $new_instance['tqqurl'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '关于本站';
		}
		global $wpdb;
		$weixin = $instance['weixin'];
		$about_img = $instance['about_img'];
		$about_name = $instance['about_name'];
		$about_the = $instance['about_the'];
		$instance = wp_parse_args((array) $instance, array('tsina' => 'fa fa-weibo'));
		$tsina = $instance['tsina'];
		$instance = wp_parse_args((array) $instance, array('tsinaurl' => '输入链接地址'));
		$tsinaurl = $instance['tsinaurl'];
		$instance = wp_parse_args((array) $instance, array('rss' => 'fa fa-rss'));
		$rss = $instance['rss'];
		$instance = wp_parse_args((array) $instance, array('rssurl' => 'http://域名/feed/'));
		$rssurl = $instance['rssurl'];
		$instance = wp_parse_args((array) $instance, array('tqq' => 'fa fa-qq'));
		$tqq = $instance['tqq'];
		$instance = wp_parse_args((array) $instance, array('tqqurl' => '88888'));
		$tqqurl = $instance['tqqurl'];
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('about_img'); ?>">头像：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'about_img' ); ?>" name="<?php echo $this->get_field_name( 'about_img' ); ?>" type="text" value="<?php echo $about_img; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('about_name'); ?>">昵称：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'about_name' ); ?>" name="<?php echo $this->get_field_name( 'about_name' ); ?>" type="text" value="<?php echo $about_name; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('about_the'); ?>">说明：</label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('about_the'); ?>" name="<?php echo $this->get_field_name('about_the'); ?>"><?php echo $about_the; ?></textarea></p>
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('weixin'); ?>">微信二维码：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'weixin' ); ?>" name="<?php echo $this->get_field_name( 'weixin' ); ?>" type="text" value="<?php echo $weixin; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('tqq'); ?>">QQ图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqq' ); ?>" name="<?php echo $this->get_field_name( 'tqq' ); ?>" type="text" value="<?php echo $tqq; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">QQ号：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tqqurl' ); ?>" name="<?php echo $this->get_field_name( 'tqqurl' ); ?>" type="text" value="<?php echo $tqqurl; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsina' ); ?>" name="<?php echo $this->get_field_name( 'tsina' ); ?>" type="text" value="<?php echo $tsina; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('tsina'); ?>">新浪微博地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'tsinaurl' ); ?>" name="<?php echo $this->get_field_name( 'tsinaurl' ); ?>" type="text" value="<?php echo $tsinaurl; ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_id('rss'); ?>">订阅图标：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'rss' ); ?>" name="<?php echo $this->get_field_name( 'rss' ); ?>" type="text" value="<?php echo $rss; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('rss'); ?>">订阅地址：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'rssurl' ); ?>" name="<?php echo $this->get_field_name( 'rssurl' ); ?>" type="text" value="<?php echo $rssurl; ?>" />
	</p>

	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "about" );' ) );

// 广告位
class advert extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'advert',
			'description' => __( '用于侧边添加广告代码' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('advert', '主题&nbsp;&nbsp;广告位', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;

		$text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
?>

<?php if ( ! wp_is_mobile() ) { ?>
<div id="advert_widget">
	<?php echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text; ?>
</div>
<?php } ?>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			if ( current_user_can('unfiltered_html') )
				$instance['text'] =  $new_instance['text'];
			else
				$instance['text'] = stripslashes( wp_filter_post_kses( addslashes($new_instance['text']) ) ); // wp_filter_post_kses() expects slashed
			$instance['filter'] = ! empty( $new_instance['filter'] );
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '广告位';
		}
		$text = esc_textarea($instance['text']);
		global $wpdb;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'text' ); ?>">内容：</label>
		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea></p>
		<p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
		<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />


<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "advert" );' ) );

// 图片
class img_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'img_widget',
			'description' => __( '调用最新图片文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('img_widget', '主题&nbsp;&nbsp;最新图片', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

<div class="picture img_widget">
	<?php
	    $args = array(
	        'post_type' => 'picture',
	        'showposts' => $number, 
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'gallery',
	                'terms' => $instance['cat']
	                ),
	            )
	        );
 		?>
	<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php if (zm_get_option('lazy_s')) { zm_thumbnail_h(); } else { zm_thumbnail(); } ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['cat'] = $new_instance['cat'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新图片';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '4'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 选择分类, 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'gallery', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "img_widget" );' ) );


// 视频
class video_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'video_widget',
			'description' => __( '调用最新视频文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('video_widget', '主题&nbsp;&nbsp;最新视频', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

<div class="picture video_widget">
	<?php
	    $args = array(
	        'post_type' => 'video',
	        'showposts' => $number, 
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'videos',
	                'terms' => $instance['cat']
	                ),
	            )
	        );
 		?>
	<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php if (zm_get_option('lazy_s')) { videor_thumbnail_h(); } else { videor_thumbnail(); } ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['cat'] = $new_instance['cat'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新视频';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '4'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 选择分类, 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'videos', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "video_widget" );' ) );

// 淘客
class tao_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'tao_widget',
			'description' => __( '调用最新商品' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('tao_widget', '主题&nbsp;&nbsp;最新商品', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

<div class="picture tao_widget">
	<?php
	    $args = array(
	        'post_type' => 'tao',
	        'showposts' => $number, 
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'taobao',
	                'terms' => $instance['cat']
	                ),
	            )
	        );
 		?>
	<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php if (zm_get_option('lazy_s')) { tao_thumbnail_h(); } else { tao_thumbnail(); } ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['cat'] = $new_instance['cat'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新商品';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '4'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 选择分类, 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'taobao', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "tao_widget" );' ) );

// 多功能小工具
class php_text extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'php_text',
			'description' => __( '支持PHP、JavaScript、短代码等' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('php_text', '主题&nbsp;&nbsp;增强文本', $widget_ops);
	}

	function widget( $args, $instance ) {

        if (!isset($args['widget_id'])) {
          $args['widget_id'] = null;
        }

        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance);
        $titleUrl = empty($instance['titleUrl']) ? '' : $instance['titleUrl'];
        $cssClass = empty($instance['cssClass']) ? '' : $instance['cssClass'];
        $text = apply_filters('widget_enhanced_text', $instance['text'], $instance);
        $hideTitle = !empty($instance['hideTitle']) ? true : false;
        $hideEmpty = !empty($instance['hideEmpty']) ? true : false;
        $newWindow = !empty($instance['newWindow']) ? true : false;
        $filterText = !empty($instance['filter']) ? true : false;
        $bare = !empty($instance['bare']) ? true : false;

        if ( $cssClass ) {
            if( strpos($before_widget, 'class') === false ) {
                $before_widget = str_replace('>', 'class="'. $cssClass . '"', $before_widget);
            } else {
                $before_widget = str_replace('class="', 'class="'. $cssClass . ' ', $before_widget);
            }
        }

        // 通过PHP解析文本
        ob_start();
        eval('?>' . $text);
        $text = ob_get_contents();
        ob_end_clean();

        // 通过do_shortcode运行文本
        $text = do_shortcode($text);

        if (!empty($text) || !$hideEmpty) {
            echo $bare ? '' : $before_widget;

            if ($newWindow) $newWindow = "target='_blank'";

            if(!$hideTitle && $title) {
                if($titleUrl) $title = "<a href='$titleUrl' $newWindow>$title</a>";
                echo $bare ? $title : $before_title . $title . $after_title;
            }

            echo $bare ? '' : '<div class="textwidget widget-text">';

            // 重复的内容
            echo $filterText ? wpautop($text) : $text;

            echo $bare ? '' : '</div>' . $after_widget;
        }
    }

    /**
     * 更新内容
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        if ( current_user_can('unfiltered_html') )
            $instance['text'] =  $new_instance['text'];
        else
		$instance['text'] = wp_filter_post_kses($new_instance['text']);
        $instance['titleUrl'] = strip_tags($new_instance['titleUrl']);
        $instance['cssClass'] = strip_tags($new_instance['cssClass']);
        $instance['hideTitle'] = isset($new_instance['hideTitle']);
        $instance['hideEmpty'] = isset($new_instance['hideEmpty']);
        $instance['newWindow'] = isset($new_instance['newWindow']);
        $instance['filter'] = isset($new_instance['filter']);
        $instance['bare'] = isset($new_instance['bare']);

        return $instance;
    }

    /**
     * 管理面板
     */
    function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array(
            'title' => '',
            'titleUrl' => '',
            'cssClass' => '',
            'text' => ''
        ));
        $title = $instance['title'];
        $titleUrl = $instance['titleUrl'];
        $cssClass = $instance['cssClass'];
        $text = format_to_edit($instance['text']);
?>

        <style>
            .monospace {
                font-family: Consolas, Lucida Console, monospace;
            }
            .etw-credits {
                font-size: 6.9em;
                background: #F7F7F7;
                border: 1px solid #EBEBEB;
                padding: 4px 6px;
            }
        </style>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">标题：</label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('titleUrl'); ?>">标题链接：</label>
            <input class="widefat" id="<?php echo $this->get_field_id('titleUrl'); ?>" name="<?php echo $this->get_field_name('titleUrl'); ?>" type="text" value="<?php echo $titleUrl; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('cssClass'); ?>">CSS 类:</label>
            <input class="widefat" id="<?php echo $this->get_field_id('cssClass'); ?>" name="<?php echo $this->get_field_name('cssClass'); ?>" type="text" value="<?php echo $cssClass; ?>" />
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('text'); ?>">内容：</label>
            <textarea class="widefat monospace" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
        </p>

        <p>
            <input id="<?php echo $this->get_field_id('hideTitle'); ?>" name="<?php echo $this->get_field_name('hideTitle'); ?>" type="checkbox" <?php checked(isset($instance['hideTitle']) ? $instance['hideTitle'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('hideTitle'); ?>">不显示标题</label>
        </p>

        <p>
            <input id="<?php echo $this->get_field_id('hideEmpty'); ?>" name="<?php echo $this->get_field_name('hideEmpty'); ?>" type="checkbox" <?php checked(isset($instance['hideEmpty']) ? $instance['hideEmpty'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('hideEmpty'); ?>">不显示空的小工具</label>
        </p>

        <p>
            <input type="checkbox" id="<?php echo $this->get_field_id('newWindow'); ?>" name="<?php echo $this->get_field_name('newWindow'); ?>" <?php checked(isset($instance['newWindow']) ? $instance['newWindow'] : 0); ?> />
            <label for="<?php echo $this->get_field_id('newWindow'); ?>">在新窗口打开标题链接</label>
        </p>

        <p>
            <input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>">自动添加段落</label>
        </p>
		<!-- 
        <p>
            <input id="<?php echo $this->get_field_id('bare'); ?>" name="<?php echo $this->get_field_name('bare'); ?>" type="checkbox" <?php checked(isset($instance['bare']) ? $instance['bare'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('bare'); ?>">标题之前不输出after_widget</label>
        </p>
		 -->
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "php_text" );' ) );

// 作者墙
class author_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'author_widget',
			'description' => __( '显示所有作者头像' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('author_widget', '主题&nbsp;&nbsp;作者墙', $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title; 
?>

<?php
	$author_numbers=$instance['author_numbers'];
	if($author_numbers) {} else { $author_numbers=50; }
	$list = $instance['exclude_author'];
	$array = explode(',', $list); 
	$count=count($array);
	for($excludeauthor=0;$excludeauthor<=$count;$excludeauthor++) {
		$exclude.="user_login!='".trim($array[$excludeauthor])."'";
		if($excludeauthor!=$count) {
			$exclude.=" and ";
		}
	}
	$where = "WHERE ".$exclude."";
	global $wpdb;
	$table_prefix.=$wpdb->base_prefix;
	$table_prefix.="users";
	$table_prefix1.=$wpdb->base_prefix;
	$table_prefix1.="posts";

	$get_results="SELECT count(p.post_author) as post1,c.id, c.user_login, c.display_name, c.user_email, c.user_url, c.user_registered FROM {$table_prefix} as c , {$table_prefix1} as p {$where} and p.post_type = 'post' AND p.post_status = 'publish' and c.id=p.post_author GROUP BY p.post_author order by post1 DESC limit {$author_numbers}  ";
	$comment_counts = (array) $wpdb->get_results("{$get_results}", object);
?>
<div class="author_widget_box">
	<?php
		foreach ( $comment_counts as $count ) {
			$user = get_userdata($count->id);
			echo '<ul class="xl9"><li class="author_box">';
			$post_count = get_usernumposts($user->ID);
			$postount = get_avatar( $user->user_email, $size = 0);

				$temp=explode(" ",$user->display_name);
			 	$link = sprintf(
					'<a href="%1$s" title="%2$s" >'.$postount.' <span class="clear"></span>%3$s %4$s %5$s</a>',
					get_author_posts_url( $user->ID, $user->user_login ),
					esc_attr( sprintf( ' %s 发表 %s 篇文章', $user->display_name,get_usernumposts($user->ID) ) ),
					$temp[0],$temp[1],$temp[2]
				);
			echo $link;
			echo "</li></ul>";
		}
	?>
	<div class="clear"></div>
</div>

<?php
	echo $after_widget;
}
function form( $instance ) {
	$instance = wp_parse_args( (array) $instance, array( 
		'title' => '作者墙'
		)); 
		?> 

        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
        <input type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
        </p>
        <?php $video_embed_c = stripslashes(htmlspecialchars($instance['exclude_author'], ENT_QUOTES)); ?>
        <p>
          <label for="<?php echo $this->get_field_id( 'exclude_author' ); ?>">排除的作者：</label>
		<textarea style="height:200px;" class="widefat" id="<?php echo $this->get_field_id( 'exclude_author' ); ?>" name="<?php echo $this->get_field_name( 'exclude_author' ); ?>"><?php echo stripslashes(htmlspecialchars(( $instance['exclude_author'] ), ENT_QUOTES)); ?></textarea>
        </p>
         <p>
        <p>
        <label for="<?php echo $this->get_field_id( 'author_numbers' ); ?>">显示数量：</label>
        <input type="text" id="<?php echo $this->get_field_id('author_numbers'); ?>" name="<?php echo $this->get_field_name('author_numbers'); ?>" value="<?php echo $instance['author_numbers']; ?>" style="width:100%;" />
        </p>
		
		
	<?php
	}
}
add_action( 'widgets_init', create_function( '', 'register_widget( "author_widget" );' ) );

// 即将发布
class timing_post extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'timing_post',
			'description' => __( '即将发表的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('timing_post', '主题&nbsp;&nbsp;即将发布', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<div class="timing_post">
	<ul>
		<?php
		$my_query = new WP_Query( array ( 'post_status' => 'future','order' => 'ASC','showposts' => $number,'ignore_sticky_posts' => '1'));
		if ($my_query->have_posts()) {
			while ($my_query->have_posts()) : $my_query->the_post();
				$do_not_duplicate = $post->ID; ?>
				<li><i class="fa fa-clock-o"> <?php the_time('G:i') ?></i><?php the_title(); ?></li>
			<?php endwhile;
		}
		?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '即将发布';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "timing_post" );' ) );

// 关于作者
class about_author extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'about_author',
			'description' => __( '只显示在正文和作者页面' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('about_author', '主题&nbsp;&nbsp;关于作者', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		if ( is_author() || is_single() ){ 
			$title = apply_filters( 'widget_title', $instance['title'] );
			echo $before_widget;
			if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
     	}
?>

<?php if ( is_author() || is_single() ) { ?>
<?php
	global $wpdb;
	$author_id = get_the_author_meta( 'ID' );
	$comment_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments  WHERE comment_approved='1' AND user_id = '$author_id' AND comment_type not in ('trackback','pingback')" );
?>
<div id="about_author_widget">
	<div class="author-meta">
		<div class="author-avatar"><?php echo get_avatar( get_the_author_meta('user_email'), '100' ); ?><div class="clear"></div></div>
		<h4 class="author-the"><?php the_author(); ?></h4>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<div class="author-th">
		<div class="author-description"><?php the_author_meta( 'user_description' ); ?></div>
		<div class="author-n author-nickname"><span><?php the_author_posts(); ?></span><br /><?php _e( '文章', 'begin' ); ?></div>
		<div class="author-n"><span><?php echo $comment_count;?></span><br /><?php _e( '评论', 'begin' ); ?></div>
		<div class="clear"></div>
		<div class="author-m"><a href="<?php echo esc_url( home_url('/') ); ?>author/<?php the_author_meta( 'first_name' ); ?>"><?php _e( '更多文章', 'begin' ); ?></a></div>
		<div class="clear"></div>
	</div>
</div>
<?php } ?>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '关于作者';
		}
		global $wpdb;
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "about_author" );' ) );

// 最近更新过的文章
class updated_posts extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'updated_posts',
			'description' => __( '调用最近更新过的文章' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('updated_posts', '主题&nbsp;&nbsp;最近更新过的文章', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
		$days = strip_tags($instance['days']) ? absint( $instance['days'] ) : 15;
?>

<div class="post_cat">
	<ul>
		<?php if ( function_exists('recently_updated_posts') ) recently_updated_posts($number,$days); ?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['days'] = strip_tags($new_instance['days']);
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最近更新过的文章';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$instance = wp_parse_args((array) $instance, array('days' => '15'));
		$number = strip_tags($instance['number']);
		$days = strip_tags($instance['days']);
 ?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<p><label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
	<p><label for="<?php echo $this->get_field_id('days'); ?>">排除近期文章（天）：</label>
	<input id="<?php echo $this->get_field_id( 'days' ); ?>" name="<?php echo $this->get_field_name( 'days' ); ?>" type="text" value="<?php echo $days; ?>" size="3" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "updated_posts" );' ) );

// EDD下载
class edd_widget extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'edd_widget',
			'description' => __( '调用最新EDD下载' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('edd_widget', '主题&nbsp;&nbsp;最新下载', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
?>

<div class="picture tao_widget">
	<?php
	    $args = array(
	        'post_type' => 'download',
	        'showposts' => $number, 
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'download_category',
	                'terms' => $instance['cat']
	                ),
	            )
	        );
 		?>
	<?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<span class="img-box">
		<span class="img-x2">
			<span class="insets">
				<?php if (zm_get_option('lazy_s')) { tao_thumbnail_h(); } else { tao_thumbnail(); } ?>
			</span>
		</span>
	</span>
	<?php endwhile;?>
	<?php wp_reset_query(); ?>
	<span class="clear"></span>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['cat'] = $new_instance['cat'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '最新下载';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '4'));
		$number = strip_tags($instance['number']);
?>
	<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('cat'); ?>">选择分类：
		<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 选择分类, 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'download_category', 'selected'=>$instance['cat'])); ?></label>
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "edd_widget" );' ) );


// 用户登录
class login extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'login',
			'description' => __( '用户登录、管理站点及用户中心等链接' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('login', '主题&nbsp;&nbsp;用户登录', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
?>

<div id="login_widget">
	<?php get_currentuserinfo();global $current_user, $user_ID, $user_identity;	if( !$user_ID || '' == $user_ID ) { ?>
		<form action="<?php echo wp_login_url( get_permalink() ); ?>" method="post" class="loginform">
			<p>
				<input class="username" name="log" required="required" type="text" placeholder="名称"/>
			</p>
			<p>
				<input class="password" name="pwd" required="required" type="password" placeholder="密码" />
			</p>
			<p class="login button"> 
				<input type="submit" value="登录" />
			</p>
			<div class="login-widget-reg">
				<input type="hidden" name="redirect_to" value="<?php echo $_SERVER[ 'REQUEST_URI' ]; ?>" />
				<label><input type="checkbox" name="rememberme" class="modlogn_remember" value="yes"  checked="checked">自动登录</label>
				<a href="<?php echo esc_url( home_url('/') ); ?>wp-login.php?action=lostpassword">&nbsp;&nbsp;找回密码</a>
				<?php if ( zm_get_option('reg_url') == '' ) { ?><?php } else { ?><a href="<?php echo stripslashes( zm_get_option('reg_url') ); ?>" target="_blank"> 立即注册</a><?php } ?>
			</div>
			<div class="login-form"><?php do_action('login_form'); ?></div>
		</form>
	<?php } ?>
	<?php global $user_identity,$user_level;get_currentuserinfo();if ($user_identity) { ?>
		<div class="login-user-widget">
			<div class="login-widget-avata">
				<?php global $current_user;	get_currentuserinfo();
					echo get_avatar( $current_user->user_email, 64);
				?>
				<div class="clear"></div>
				您已登录：<?php echo $user_identity; ?>
			</div>
			<div class="login-widget-link">
				<?php if ( zm_get_option('user_url') == '' ) { ?>
				<?php } else { ?>
			  		<a href="<?php echo get_permalink( zm_get_option('user_url') ); ?>" target="_blank">用户中心</a>
			  	<?php } ?>
				<?php if (current_user_can('level_10') ){ ?><?php wp_register('', ''); ?><?php } ?>
				<a href="<?php echo wp_logout_url( home_url() ); ?>" title="">退出登录</a>
				<div class="clear"></div>
			</div>
		</div>
	 <?php } ?>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '用户登录';
		}
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "login" );' ) );

// 留言板
class pages_recent_comments extends WP_Widget {
	public function __construct() {
		$widget_ops = array(
			'classname' => 'pages_recent_comments',
			'description' => __( '调用“留言板”页面留言' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct('pages_recent_comments', '主题&nbsp;&nbsp;留言板', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		$title = apply_filters( 'widget_title', $instance['title'] );
		echo $before_widget;
		if ( ! empty( $title ) )
		echo $before_title . $title . $after_title;
		$number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 5;
?>

<div id="message" class="message-widget">
	<ul>
		<?php
		$show_comments = $number;
		$my_email = get_bloginfo ('admin_email');
		$i = 1;
		$comments = get_comments(
			array(
				'post_id' => $instance["pages_id"]
			)
		);
		foreach ($comments as $my_comment) {
			if ($my_comment->comment_author_email != $my_email) {
				?>
				<li>
					<a href="<?php echo get_permalink($my_comment->comment_post_ID); ?>#anchor-comment-<?php echo $my_comment->comment_ID; ?>" title="<?php echo get_the_title($my_comment->comment_post_ID); ?>" rel="external nofollow">
						<?php echo get_avatar($my_comment->comment_author_email,64, '', $my_comment->comment_author); ?>
						<span class="comment_author"><strong><?php echo $my_comment->comment_author; ?></strong></span>
						<?php echo convert_smilies($my_comment->comment_content); ?>
					</a>
				</li>
				<?php
				if ($i == $show_comments) break;
				$i++;
			}
		}
		?>
	</ul>
</div>

<?php
	echo $after_widget;
	}
	function update( $new_instance, $old_instance ) {
		if (!isset($new_instance['submit'])) {
			return false;
		}
			$instance = $old_instance;
			$instance = array();
			$instance['title'] = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags($new_instance['number']);
			$instance['pages_id'] = $new_instance['pages_id'];
			return $instance;
		}
	function form($instance) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = '留言板';
		}
		global $wpdb;
		$instance = wp_parse_args((array) $instance, array('number' => '5'));
		$number = strip_tags($instance['number']);
?>
	<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">标题：</label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('pages_id'); ?>">选择页面：</label>
		<?php wp_dropdown_pages( array( 'name' => $this->get_field_name("pages_id"), 'selected' => $instance["pages_id"] ) ); ?>
	</p>
	<p>
		<label for="<?php echo $this->get_field_id('number'); ?>">显示数量：</label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" />
	</p>
	<input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
<?php }
}
add_action( 'widgets_init', create_function( '', 'register_widget( "pages_recent_comments" );' ) );