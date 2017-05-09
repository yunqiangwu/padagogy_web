<?php
/**
 * 全部子分类
 */

get_header(); ?>

<style type="text/css">
.child-post {
	position: relative;
	background: #fff;
	margin: 0 0 10px 0;
	padding: 0 20px 20px 20px;
	border: 1px solid #ddd;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
    border-radius: 2px;
}
.archive-title {
	position: absolute;
    background: #c40000;
	top: 20px;
	left: 20px;
	font-size: 16px;
	font-size: 1.6rem;
	color: #fff;
	line-height: 35px;
	padding: 0 20px;
	filter: alpha(opacity=70);
	background: none repeat scroll 0 0 rgba(216, 0, 0, 0.7);
}
.archive-l {
	position: absolute;
	background: #c40000;
	top: 20px;
	left: -1px;
	width: 5px;
	height: 35px;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
}
.child-inf {
	float: right;
	color: #bbbbbb;
}
.child-title {
	background: #f8f8f8;
	line-height: 40px;
	margin: 0 -20px;
	padding: 0 0px;
	border-bottom: 1px solid #ddd;
}
.child-title .fa-bars {
	float: left;
	font-size: 16px;
	width: 40px;
    height: 40px;
	line-height: 40px;
	text-align: center;
	margin-right: 10px;
	padding: 1px 0;
	border-right: 1px solid #ddd;
}
.child-list {
	margin-top: 10px;
}
.child-list li {
	line-height: 230%;
	white-space: nowrap;
	word-wrap: normal;
	text-overflow: ellipsis;
	overflow: hidden;
}
.child-list .fa-angle-right {
	color: #999;
	margin: 0 5px 0 0;
}
.child-title .icon-m {
	font-size: 16px;
	color: #999;
	vertical-align: middle;
}
.child-img img {
	max-width: 100%;
 	width: auto\9;
	height: auto;
	display: block;
	margin: 0 0 10px 0;
}
@media screen and (max-width: 440px) {
	.archive-title {
		margin: 0 -20px 10px -20px;
	}
	.child-inf {
		display: none;
	}
}
</style>

<section id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp'); ?> data-wow-delay="0.3s">
			<div class="archive-l"></div>
			<h1 class="archive-title"><?php printf( '%s', single_cat_title( '', false ) ); ?></h1>
 			<div class="child-img">
				<img alt="<?php printf( '%s', single_cat_title( '', false ) ); ?>" src="http://ww3.sinaimg.cn/large/703be3b1jw1etrprt3qioj20u00acwiw.jpg"><!-- 替换图片链接 -->
			</div>
			<?php if ( category_description() ) :  ?>
			<div class="archive-meta"><?php echo category_description(); ?></div>
			<?php endif; ?>
		</article>

		<?php if (zm_get_option('ad_a')) { ?>
			<?php if ( wp_is_mobile() ) { ?>
				 <?php if ( zm_get_option('ad_a_c_m') ) { ?><div class="ad-m ad-site"><?php echo stripslashes( zm_get_option('ad_a_c_m') ); ?></div><?php } ?>
			<?php } else { ?>
				 <?php if ( zm_get_option('ad_a_c') ) { ?><div class="ad-pc ad-site"><?php echo stripslashes( zm_get_option('ad_a_c') ); ?></div><?php } ?>
			<?php } ?>
		<?php } ?>

		<?php
		    global $cat;
		    $cats = get_categories(array(
		        'child_of' => $cat,
		        'parent' => $cat,
		        'hide_empty' => 0
		    ));
		    foreach($cats as $the_cat){
		        $posts = get_posts(array(
		            'category' => $the_cat->cat_ID,
		            'numberposts' => 18,
		        ));
		        if(!empty($posts)){
		            echo '
		            <div class="child-post wow fadeInUp" data-wow-delay="0.3s">
		                <div class="child-title"><h2><a href="'.get_category_link($the_cat).'"><i class="fa fa-bars"></i>'.$the_cat->name.'</a></h2></div>
		                <ul class="child-list">';
		                    foreach($posts as $post){
		                        echo '<li class="child-inf">'.mysql2date('m月d日', $post->post_date).'</li><li>
		                        <i class="fa fa-angle-right"></i><a href="'.get_permalink($post->ID).'">'.$post->post_title.'</a></li>';
		                    }
		                echo '</ul>
		            </div>';
		        }
		    }

		?>
	</main><!-- .site-main -->
</section><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>