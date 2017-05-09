<?php
// 小工具
if (function_exists('register_sidebar')){
	register_sidebar( array(
		'name'          => '博客布局侧边栏',
		'id'            => 'sidebar-h',
		'description'   => '显示在首页博客布局侧边栏',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><i class="fa fa-bars"></i>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '正文侧边栏',
		'id'            => 'sidebar-s',
		'description'   => '显示在文章正文及页面侧边栏',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><i class="fa fa-bars"></i>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '分类归档侧边栏',
		'id'            => 'sidebar-a',
		'description'   => '显示在文章归档页、搜索、404页侧边栏 ',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><i class="fa fa-bars"></i>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '杂志布局侧边栏',
		'id'            => 'cms-s',
		'description'   => '只显示在杂志布局侧边栏',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><i class="fa fa-bars"></i>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '杂志单栏小工具',
		'id'            => 'cms-one',
		'description'   => '显示在首页CMS杂志布局',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><i class="fa fa-bars"></i>',
		'after_title'   => '</h3>',
	) );




    register_sidebar( array(
        'name'          => '主页工具',
        'id'            => 'cms-index',
        'description'   => '显示在首页主页工具',
        'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
        'after_widget'  => '<div class="clear"></div></aside>',
        'before_title'  => '<h3 class="widget-title"><i class="fa fa-bars"></i>',
        'after_title'   => '</h3>',
    ) );

	register_sidebar( array(
		'name'          => '杂志两栏小工具',
		'id'            => 'cms-two',
		'description'   => '显示在首页CMS杂志布局',
		'before_widget' => '<div class="xl2"><aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside></div>',
		'before_title'  => '<h3 class="widget-title"><i class="fa fa-bars"></i>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '正文底部小工具',
		'id'            => 'sidebar-e',
		'description'   => '显示在正文底部',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="s-icon"></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '公司主页“一栏”小工具',
		'id'            => 'group-one',
		'description'   => '显示在公司主页布局',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '公司主页“两栏”小工具',
		'id'            => 'group-two',
		'description'   => '显示在公司主页布局',
		'before_widget' => '<div class="xl2"><aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside></div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '公司主页“三栏”小工具',
		'id'            =>  'group-three',
		'description'   => '显示在公司主页布局',
		'before_widget' => '<div class="xl3"><aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside></div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '页脚小工具',
		'id'            => 'sidebar-f',
		'description'   => '显示在页脚',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><span class="s-icon"></span>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '菜单页面',
		'id'            => 'all-cat',
		'description'   => '只适用于菜单页面模板，添加自定义菜单小工具',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '网址侧边栏',
		'id'            => 'favorites',
		'description'   => '只适用于网址分类法页面 ',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title"><i class="fa fa-bars"></i>',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => '网址单栏小工具',
		'id'            => 'favorites-one',
		'description'   => '显示网址页面',
		'before_widget' => '<aside id="%1$s" class="widget %2$s wow fadeInUp" data-wow-delay="0.3s">',
		'after_widget'  => '<div class="clear"></div></aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}

register_nav_menus(
	array(
		'primary' => '主要菜单',
		'header' => '顶部菜单',
		'mobile' => '移动端菜单'
	)
);

add_theme_support( 'custom-background' );
add_theme_support( 'post-formats', array(
	'aside', 'image', 'video', 'quote', 'link'
) );
require get_template_directory() . '/inc/function/lib.php';
if (zm_get_option('languages_en')) {
add_action('after_setup_theme', 'begin_theme_setup');
function begin_theme_setup(){
	load_theme_textdomain( 'begin', get_template_directory() . '/languages' );
}
}
if ( ! zm_get_option('wp_title') ) {
// 默认title
	add_theme_support( 'title-tag' );

/** 兼容模式
function theme_slug_render_title() {
    echo '<title>' . wp_title( '|', false, 'right' ) . "</title>\n";
}
add_action( 'wp_head', 'theme_slug_render_title' );
 */
}
if (zm_get_option('woo')) {
	add_theme_support( 'woocommerce' );
}
add_editor_style( '/css/editor-style.css' );
add_theme_support( 'automatic-feed-links' );
show_admin_bar(false);
function default_menu() {
	echo '<ul class="default-menu"><li><a href="'.home_url().'/wp-admin/nav-menus.php">设置菜单</a></li></ul>';
}
define( 'version', '2017.03.20' );

function zmingcx_scripts() {
	$my_theme = wp_get_theme();
	$theme_version = $my_theme->get( 'Version' );
	wp_enqueue_style( 'begin-style', get_stylesheet_uri(), array(), esc_attr( $theme_version ) );
	wp_enqueue_style( 'fonts', get_template_directory_uri() . '/css/fonts.css', array(), version );
	if (zm_get_option('highlight')) {
		if ( is_singular() ) {
			wp_enqueue_style( 'highlight', get_template_directory_uri() . '/css/highlight.css', array(), version );
		}
	}
		if (zm_get_option('woo')) {
			wp_enqueue_style( 'woo', get_template_directory_uri() . '/css/woo.css', array(), version );
		}
		if (zm_get_option('edd')) {
			wp_enqueue_style( 'edd', get_template_directory_uri() . '/css/edd.css', array(), version );
		}
		if (zm_get_option('dw')) {
			wp_enqueue_style( 'dw', get_template_directory_uri() . '/css/dw.css', array(), version );
		}
		if ( !is_admin() ) {
			wp_deregister_script( 'jquery' );
			wp_register_script( 'jquery', get_template_directory_uri() . '/js/jquery.min.js', array(), '1.10.1', false );
			wp_enqueue_script( 'jquery' );
		}
		wp_enqueue_script( 'slides', get_template_directory_uri() . '/js/slides.js', array(), version, false );
		if (zm_get_option('qr_img')) {
			wp_enqueue_script( 'jquery.qrcode.min', get_template_directory_uri() . '/js/jquery.qrcode.min.js', array(), version, false );
		}
		if (zm_get_option('wow_no')) {
			wp_enqueue_script( 'wow', get_template_directory_uri() . '/js/wow.js', array(), '0.1.9', false );
		}
		if (zm_get_option('sidebar_sticky')) {
			wp_enqueue_script( 'sticky', get_template_directory_uri() . '/js/sticky.js', array(), '1.6.0', false );
		}
		wp_enqueue_script( 'jquery-ias', get_template_directory_uri() . '/js/jquery-ias.js', array(), '2.2.1', false );
		wp_enqueue_script( 'lazyload', get_template_directory_uri() . '/js/jquery.lazyload.js', array(), version, false );
		wp_enqueue_script( 'tipso', get_template_directory_uri() . '/js/tipso.js', array(), '1.0.1', false );
		wp_enqueue_script( 'script', get_template_directory_uri() . '/js/script.js', array(), version, false );
		wp_register_script( 'flexisel', get_template_directory_uri() . '/js/flexisel.js', array(), version, false );
		wp_enqueue_script( 'flexisel' );
	if ( is_singular() ) {
		wp_localize_script( 'script', 'wpl_ajax_url', admin_url() . "admin-ajax.php");
		wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/js/fancybox.js', array(), version, false);
		if (zm_get_option('qt')) {
			wp_enqueue_script( 'comments-ajax-qt', get_template_directory_uri() . '/js/comments-ajax-qt.js', array(), version, false);
		} else {
        	wp_enqueue_script( 'comments-ajax', get_template_directory_uri() . '/js/comments-ajax.js', array(), version, false);
		}
	}
	// 加载回复js
	// if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
	// 	wp_enqueue_script( 'comment-reply' );
	// }
}
add_action( 'wp_enqueue_scripts', 'zmingcx_scripts' );

// 页脚JS
function footerscript() {
	wp_register_script( 'superfish', get_template_directory_uri() . "/js/superfish.js", array(), version, true );
	if (zm_get_option('gb2')) {
		wp_register_script( 'gb2big5', get_template_directory_uri() . "/js/gb2big5.js", array(), version, true );
	}
	if (zm_get_option('group_carousel')) {
		wp_register_script( 'carousel', get_template_directory_uri() . "/js/carousel.min.js", array(), version, true );
	}
	if ( !is_admin() ) {
		wp_enqueue_script( 'superfish' );
		wp_enqueue_script( 'gb2big5' );
		wp_enqueue_script( 'carousel' );
	}
}
add_action( 'wp_enqueue_scripts', 'footerscript' );

// 全部结束