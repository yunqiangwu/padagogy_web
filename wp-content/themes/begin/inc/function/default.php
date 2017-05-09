<?php
// Ajax加载
function ajax_scroll_js() {
if ( !is_singular() && !is_paged() ) { ?>
<script type="text/javascript">var ias=$.ias({container:"#main",item:"article",pagination:"#nav-below",next:"#nav-below .nav-previous a",});ias.extension(new IASTriggerExtension({text:'<i class="fa fa-chevron-circle-down"></i>更多',offset:<?php echo zm_get_option('scroll_n');?>,}));ias.extension(new IASSpinnerExtension());ias.extension(new IASNoneLeftExtension({text:'已是最后',}));ias.on('rendered',function(items){$("img").lazyload({effect: "fadeIn",failure_limit: 70});})</script>
<?php }
}

function ajax_c_scroll_js() {
if ( is_single() ) { ?>
<script type="text/javascript">var ias=$.ias({container:"#comments",item:".comment-list",pagination:".scroll-links",next:".scroll-links .nav-previous a",});ias.extension(new IASTriggerExtension({text:'<i class="fa fa-chevron-circle-down"></i>更多',offset: 0,}));ias.extension(new IASSpinnerExtension());ias.extension(new IASNoneLeftExtension({text:'已是最后',}));ias.on('rendered',function(items){$("img").lazyload({effect: "fadeIn",failure_limit: 10});});</script>
<?php }
}
// 选择颜色
function choose_color(){
	custom_color();
}
function custom_color(){
	if (zm_get_option("custom_color")) {
		$color = substr(zm_get_option("custom_color"), 1);
	}
	if ($color) {
		$styles .= "
a:hover,.top-menu a:hover,.show-more span,.cat-box .icon-cat,.single-content a,.single-content a:visited,#site-nav .down-menu > .current-menu-item > a,.entry-meta a,#site-nav .down-menu > .current-menu-item > a:hover,#site-nav .down-menu > li > a:hover,#site-nav .down-menu > li.sfHover > a, .cat-title .fa-bars,.widget-title .fa-bars,.at, .at a,#user-profile a:hover,#comments .fa-exclamation-circle, #comments .fa-check-square, #comments .fa-spinner, #comments .fa-pencil-square-o {color: #" . $color . ";}
.sf-arrows > li > .sf-with-ul:focus:after,.sf-arrows > li:hover > .sf-with-ul:after,.sf-arrows > .sfHover > .sf-with-ul:after{border-top-color: #" . $color . ";}
.thumbnail .cat,.format-cat,.format-img-cat {background: #" . $color . ";opacity: 0.8;}
#login h1 a,.format-aside .post-format a,#searchform button,.li-icon-1,.li-icon-2,.li-icon-3,.new-icon, .title-l,.buttons a, .li-number, .post-format, .searchbar button {background: #" . $color . ";}
.entry-more a, .qqonline a, #login input[type='submit'], .log-zd {background: #" . $color . ";}
.entry-more a {	right: -1px;}
.entry-more a:hover {color: #fff;background: #595959;}
.entry-direct a:hover, #respond input[type='text']:focus, #respond textarea:focus {border: 1px solid #" . $color . ";}
#down a,.page-links span,.reply a:hover,.widget_categories a:hover,.widget_links a:hover,#respond #submit:hover,.callbacks_tabs .callbacks_here a,#gallery .callbacks_here a,#fontsize:hover,.single-meta li a:hover,.meta-nav:hover,.nav-single i:hover, .widget_categories a:hover, .widget_links a:hover, .tagcloud a:hover, #sidebar .widget_nav_menu a:hover, .gr-cat-title a, .group-tab-hd .group-current, .img-tab-hd .img-current {background: #" . $color . ";border: 1px solid #" . $color . ";}
.comment-tool a, .link-all a:hover, .link-f a:hover, .ias-trigger-next a:hover, .type-cat a:hover, .type-cat a:hover, .child-cat a:hover {background: #" . $color . ";border: 1px solid #" . $color . ";}
#site-nav .down-menu > .current-menu-item > a, #site-nav .down-menu > .current-menu-item > a:hover,.deanm .deanm-main a {background: #" . $color . ";}
.entry-header h1 {border-left: 5px solid #" . $color . ";border-right: 5px solid #" . $color . ";}
.slider-caption, .grid,icon-zan, .grid-cat, .entry-title-img, .header-sub h1 {background: #" . $color . ";opacity: 0.9;}
@media screen and (min-width: 900px) {#scroll li a:hover, .nav-search {background: #" . $color . ";border: 1px solid #" . $color . ";}.custom-more a, .cat-more a,.author-m a {background: #" . $color . ";}}
@media screen and (max-width: 900px) {#navigation-toggle:hover,.nav-search:hover,.mobile-login a:hover,.nav-mobile:hover, {color: #" . $color . ";}}
@media screen and (min-width: 550px) {.pagination span.current, .pagination a:hover {background: #" . $color . ";border: 1px solid #" . $color . ";}}
@media screen and (max-width: 550px) {.pagination .prev,.pagination .next {background: #" . $color . ";}}
.single-content h3, .single-content .directory {border-left: 5px solid #" . $color . ";}
.page-links  a:hover span {background: #a3a3a3;border: 1px solid #a3a3a3;}
.single-content a:hover {color: #555;}
.format-aside .post-format a:hover,.cat-more a:hover,.custom-more a:hover {color: #fff;}";
	}
	if ($styles) {
		echo "<style>" . $styles . "</style>";
	}
}

// 定制CSS
function modify_style(){
	custom_css();
}
function custom_css(){
	if (zm_get_option("custom_css")) {
		$css = substr(zm_get_option("custom_css"), 0);
		echo "<style>" . $css . "</style>";
	}
}

// 自定义宽度
function zm_width(){
	custom_width();
}
function custom_width(){
	if (zm_get_option("custom_width")) {
		$width = substr(zm_get_option("custom_width"), 0);
		echo "<style>#content, .header-sub, .top-nav, #top-menu, #mobile-nav, #main-search, #search-main, .breadcrumb, .footer-widget, .links-box {width: " . $width . "px;}@media screen and (max-width: " . $width . "px) {#content, .breadcrumb, .footer-widget, .links-box {width: 98%;}#top-menu{width: 98%;}.top-nav {width: 98%;}#main-search, #search-main, #mobile-nav {width: 98%;}.breadcrumb {width: 98%;}}</style>";
	}
}

// 文章归档更新
function clear_archives() {
	update_option('cx_archives_list', '');
}

// 邀请码
if (zm_get_option('invitation_code')) {
	if ( ! is_admin() ) {
		require get_template_directory() . '/inc/invitation/front-end.php';
	} else {
		require get_template_directory() . '/inc/invitation/back-end.php';
	}
}

// 后台添加文章ID
function ssid_column($cols) {
	$cols['ssid'] = 'ID';
	return $cols;
}

function ssid_value($column_name, $id) {
	if ($column_name == 'ssid')
		echo $id;
}

function ssid_return_value($value, $column_name, $id) {
	if ($column_name == 'ssid')
		$value = $id;
	return $value;
}

function ssid_css() {
?>
<style type="text/css">
	#ssid { width: 50px; 
</style>
<?php	
}

function ssid_add() {
	add_action('admin_head', 'ssid_css');

	add_filter('manage_posts_columns', 'ssid_column');
	add_action('manage_posts_custom_column', 'ssid_value', 10, 2);

	add_filter('manage_pages_columns', 'ssid_column');
	add_action('manage_pages_custom_column', 'ssid_value', 10, 2);

	add_filter('manage_media_columns', 'ssid_column');
	add_action('manage_media_custom_column', 'ssid_value', 10, 2);

	add_filter('manage_link-manager_columns', 'ssid_column');
	add_action('manage_link_custom_column', 'ssid_value', 10, 2);

	add_action('manage_edit-link-categories_columns', 'ssid_column');
	add_filter('manage_link_categories_custom_column', 'ssid_return_value', 10, 3);

	foreach ( get_taxonomies() as $taxonomy ) {
		add_action("manage_edit-${taxonomy}_columns", 'ssid_column');
		add_filter("manage_${taxonomy}_custom_column", 'ssid_return_value', 10, 3);
	}

	add_action('manage_users_columns', 'ssid_column');
	add_filter('manage_users_custom_column', 'ssid_return_value', 10, 3);

	add_action('manage_edit-comments_columns', 'ssid_column');
	add_action('manage_comments_custom_column', 'ssid_value', 10, 2);
}

// 异步加载JS
function async_script( $tag, $handle, $src ) {
	$begin_method = zm_get_option('async_defer');
	$begin_exclusions = zm_get_option('exclu_js');
	$array_exclusions = !empty( $begin_exclusions ) ? explode( ',', $begin_exclusions ) : array();
	if ( false !== $begin_enabled && false === is_admin() ) {
		if ( !empty( $array_exclusions ) ) {
			foreach ( $array_exclusions as $exclusion ) {
				$exclusion = trim( $exclusion );
				if ( $exclusion != '' ) {
					if ( false !== strpos( strtolower( $src ), strtolower( $exclusion ) ) ) {
						return $tag;
					}
				}
			}
		}
		$tag = str_replace( 'src=', $begin_method . "='" . $begin_method . "' src=", $tag );
		return $tag;
	}
	return $tag;
}