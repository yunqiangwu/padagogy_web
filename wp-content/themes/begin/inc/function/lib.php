<?php

/* -- enphp : https://git.oschina.net/mz/mzphp2 */
add_action('wp_head', 'zm_width');
add_shortcode('reply', 'reply_read');
add_shortcode('password', 'secret');
add_shortcode('login', 'login_to_read');
add_shortcode('img', 'gallery');
add_shortcode('slide', 'image');
add_shortcode('file', 'button_a');
add_shortcode('button', 'button_b');
add_shortcode('url', 'button_url');
add_shortcode('fieldset', 'fieldset_label');
add_shortcode('videos', 'my_videos');
add_action('wp_ajax_nopriv_zm_ding', 'begin_ding');
add_action('wp_ajax_zm_ding', 'begin_ding');
add_shortcode('s', 'show_more');
add_shortcode('p', 'section_content');
add_shortcode('ad', 'post_ad');
add_filter('category_description', 'deletehtml');
add_action('init', 'custom_smilies', 5);
if (current_user_can('edit_posts') && current_user_can('edit_pages')) {
	add_action('media_buttons', 'begin_select', 11);
}
add_action('admin_head', 'begin_button');
add_action('save_post', 'clear_archives');

add_filter('user_contactmethods', 'custom_user_contact');
add_filter('esc_html', 'custom_post_format');
add_action('wp_head', 'choose_color');
add_action('wp_head', 'modify_style');
add_action('admin_init', 'ssid_add');

require get_template_directory() . '/inc/function/widget.php';
require get_template_directory() . '/inc/function/comment-template.php';
require get_template_directory() . '/inc/function/my-field.php';
require get_template_directory() . '/inc/function/notify.php';
require get_template_directory() . '/inc/function/meta-boxes.php';
require get_template_directory() . '/inc/options-theme/options-framework.php';
if (is_admin() && $_GET['activated'] == 'true') {
	header('Location: themes.php?page=options-framework');
}
require get_template_directory() . '/inc/function/help.php';
require get_template_directory() . '/inc/function/post-type.php';
require get_template_directory() . '/inc/function/default.php';
require get_template_directory() . '/inc/function/function.php';
require get_template_directory() . '/inc/function/the-thumbnail.php';
require get_template_directory() . '/inc/function/add-lazyload.php';
require get_template_directory() . '/inc/function/order.php';
require get_template_directory() . '/inc/function/lazy-avatars.php';
if (zm_get_option('edd')) {
	require get_template_directory() . '/inc/function/edd.php';
}
if (zm_get_option('smart_ideo')) {
	require get_template_directory() . '/inc/function/smartideo.php';
}
if (zm_get_option('front_tougao')) {
	require get_template_directory() . '/inc/frontpost/frontpost.php';
}
if (zm_get_option('no_category')) {
	require get_template_directory() . '/inc/function/no-category.php';
}
if (zm_get_option('wp_thumbnails')) {
	add_theme_support('post-thumbnails');
	require get_template_directory() . '/inc/function/post-thumbnails.php';
}
if (zm_get_option('qt')) {
	require get_template_directory() . '/inc/function/qaptcha.php';
}
if (zm_get_option('auto_tags')) {
	add_action('save_post', 'auto_add_tags');
}
if (zm_get_option('page_html')) {
	add_action('init', 'html_page_permalink', -1);
}
if (zm_get_option('no_admin')) {
	add_action('admin_init', 'redirect_non_admin_users');
}
if (zm_get_option('save_image')) {
	require get_template_directory() . '/inc/function/save-image.php';
}
if (zm_get_option('search_title')) {
	add_filter('posts_search', 'wpse_11826_search_by_title', 10, 2);
}
if (zm_get_option('async_js')) {
	add_filter('script_loader_tag', 'async_script', 10, 3);
}
if (zm_get_option('scroll')) {
	add_action('wp_footer', 'ajax_scroll_js', 100);
}
if (zm_get_option('comment_scroll')) {
	add_action('wp_footer', 'ajax_c_scroll_js', 100);
}
function begin_seo()
{
	get_template_part('inc/function/seo');
}
function type_breadcrumb()
{
	get_template_part('/inc/function/type-breadcrumb');
}
function setTitle()
{
	$_var_0 = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
	echo $_var_1 = $_var_0->name;
}

if (zm_get_option('check_admin')) {
	if (!is_user_logged_in()) {
		add_filter('preprocess_comment', 'usercheck');
	}
}