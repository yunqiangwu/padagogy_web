<?php
/*
Template Name: Padagogy
*/
?>
<?php get_header(); ?>
<style>
    .tag-groups-cloud-tabs .ui-widget-header .ui-state-default{
        border:none;
    }
</style>
    <!-- 主页工具 -->
    <div id="cms-widget-one" class="wow fadeInUp" data-wow-delay="0.5s">
        <?php if ( ! dynamic_sidebar( 'cms-index' ) ) : ?>
            <aside class="add-widgets">
                <a href="<?php echo admin_url(); ?>widgets.php" target="_blank">为“主页工具”添加小工具</a>
            </aside>
        <?php endif; ?>
        <div class="clear"></div>
    </div>
    <div id="primary" class="content-area primary">

        <main id="main" class="site-main" role="main">


            <!-- 最新文章 -->
            <?php if (zm_get_option('news')) { ?>
                <?php
                if (!zm_get_option('news_model') || (zm_get_option("news_model") == 'news_normal')) {
                    // 标准模式
                    require get_template_directory() . '/cms/cms-news.php';
                }
                if (zm_get_option('news_model') == 'news_grid') {
                    // 图文模式
                    require get_template_directory() . '/cms/cms-news-grid.php';
                }
                if (zm_get_option('news_model') == 'news_list') {
                    // 标题列表模式
                    require get_template_directory() . '/cms/cms-news-list.php';
                }
                ?>
            <?php } ?>

            <aside id="php_text-5" class="widget php_text wow fadeInUp" data-wow-delay="0.3s" style="margin-top: 15px;height: 42px;">
                <h3 class="widget-title" style="line-height: 40px;"><i class="fa fa-bars"></i>按标签浏览</h3>
                <div class="textwidget widget-text"></div>
                <div class="clear"></div>
            </aside>
            <?php
            if ( function_exists( 'tag_groups_cloud' ) ) echo tag_groups_cloud( array( 'amount' => 10 ) );
            ?>

        </main><!-- .site-main -->
    </div><!-- .content-area -->
    <!-- 侧边小工具 -->
<?php //get_sidebar('cms'); ?>

    <!-- 底部分类 -->
<?php if (zm_get_option('cat_big')) { ?>
    <div class="line-big">
        <?php require get_template_directory() . '/cms/cms-cat-big.php'; ?>
    </div>
<?php } ?>

    <!-- 页脚 -->
<?php get_footer(); ?>