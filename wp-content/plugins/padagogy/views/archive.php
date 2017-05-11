<?php
/*
Template Name: Padagogy分类
*/
?>
<?php //wp_enqueue_style( 'padagogy', padagogy()->url(  'css/padagogy.css'), array(), VERSION );
//wp_enqueue_script( 'padagogy', padagogy()->url(  'js/padagogy.js'), array('jquery'), VERSION );
//?>
<?php get_header(); ?>
    <style type="text/css">
        .tao-cat {
            position: absolute;
            background: #ff4400;
            margin: 1px 6px;
            padding: 5px 15px;
            z-index: 2;
            border-radius: 2px 0 0 0;
        }
        .tao-cat a {
            font-size: 16px;
            font-size: 1.6rem;
            color: #fff;
        }
        .tao-cat a:hover {
            color: #fff;
        }
        .tao-cat .fa-bookmark-o {
            font-size: 18px;
            font-size: 1.8rem;
            margin: 0 5px 0 0;
        }

        .menu-padagogy-container {
            border-bottom: 1px solid gray;
            padding: 1rem 2rem;
            margin: .5rem;
        }
        .menu-padagogy-container > .padagogy-menu >li{
            margin-bottom:.5rem;
        }

        .menu-padagogy-container > .padagogy-menu >li> a {
            background:#f40;
            box-shadow: rgba(0, 0, 0, 0.46) 4px 2px 3px 2px;
            color: white;
            font-weight: bold;
            padding: 2px 20px;
            border-radius: 3%;
        }
        .menu-padagogy-container > .padagogy-menu >li> a:hover, .menu-padagogy-container  .current-menu-item a {
            color:white;
        }

        .menu-padagogy-container a+ul ,.menu-padagogy-container a+ul>li  {
            display:inline-block;
            margin-left:1rem;
            padding: 0 .4rem;
            transition: all .1s ease-in-out;
            /* transition: all .3s ease-in-out; */
        }

        .menu-padagogy-container a+ul>li:hover,.menu-padagogy-container a+ul>li>a:hover , .menu-padagogy-container  .current-menu-item{
            background:#1ba1e2;
            color:white;
            box-shadow: rgba(0, 0, 0, 0.18) 4px 2px 3px 2px;
            color: white;
            border-radius: 3%;
            transition: all .1s ease-in-out;
        }

        .menu-padagogy-container  .current-menu-item{
            background: #14708f!important;
        }
        .br-wrapper,.br-wrapper a.br-selected:after{
            display: inline-block;
            color: #ffbe00 !important;
        }
        .br-wrapper{
            transform: scale(1.5);
            margin: 10px 20px;
        }

    </style>
    <link rel="stylesheet" href="<?php echo Padagogy::url('lib/jquery-bar-rating/dist/themes/fontawesome-stars.css') ?>">
    <link rel="stylesheet"<?php echo Padagogy::url('lib/jquery-bar-rating/dist/themes/css-stars.css') ?>">
    <script src="<?php echo Padagogy::url('lib/jquery-bar-rating/dist/jquery.barrating.min.js') ?>"></script>

    <script>
        function p_query() {
            var slugs = $.map($('#menu-padagogy .menu-item-type-taxonomy.current-menu-item>a'),function (el) {
                return $(el).data('slug');
            }).join('+');
            console.log(slugs);
            location.href = '<?php echo home_url().'/?app_classification=';?>'+slugs ;
        }
        $(function () {
            $('.menu-item>a[href="#"]').attr('href','javascript:void(0);');
            var padagogy_menu_timeid = null;
            $('#menu-padagogy .menu-item-type-taxonomy>a').click(function () {
                var _this = $(this);
                _this.parent().toggleClass('current-menu-item');
                clearTimeout(padagogy_menu_timeid);
                padagogy_menu_timeid = setTimeout(function () {
                    p_query();
                },1000);
            });


        });

    </script>
    <style>
    .padagogy-meta{

    }

        .file_size_wrap,.dl_count_wrap{
            width: 50%;
            float: left;
            font-size: 12px;
            text-align: center;
        }

      .tao-box  h2 {
            text-align: center;
            font-size: 2rem;
          margin-bottom:14px;
        }
      .tao-box{
          padding: 10px 5px;
      }

    .tao-box img{
        /*float: left;*/
        background: #fff;
        max-width: 100%;
        width: 100%!important;
        height: auto;
        border: 1px solid #ddd;
        border-radius: 2px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
    }

    </style>

    <section id="tao" class="content-area">
        <main id="main" class="site-main" role="main">
            <?php
            wp_nav_menu( array(
                'theme_location'	=> 'padagogy-menu',
                'menu_class'		=> 'padagogy-menu',
//                'before'          => '#before#',//显示在导航a标签之前
//                'after'           => '#after#',//显示在导航a标签之后
//                'link_before'     => '<span data-sulg="%2$s">',//显示在导航链接名之后
//                'link_after'      => '</span>',//显示在导航链接名之前
                'fallback_cb'		=> function () {
                    echo '<ul class="padagogy-menu"><li><a href="'.home_url().'/wp-admin/nav-menus.php">设置菜单</a></li></ul>';
                }
            ) );
            ?>

            <?php
//            $taxonomy = 'app_classification';
//                $args = array(
//                    'showposts' => 12,
//                    'posttype' => 'padagogy',
////                    'tax_query' => array( array( 'taxonomy' => $taxonomy, 'terms' => $catid,  'include_children' => false ) )
//                );
//                $query = new WP_Query($args);
                if( have_posts() ) { ?>
                    <?php while (have_posts()) : the_post();?>
                        <div class="taocat">
                            <article id="post-<?php the_ID(); ?>" <?php post_class('tao'); ?>>
                                <div class="tao-box wow fadeInUp" data-wow-delay="0.3s">
                                    <?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
                                        <figure class="tao-img">
                                            <?php padagogy_thumbnail(); ?>
                                        </figure>

                                        <div class="brief">

                                            <div class="padagogy-meta">
                                                <div class="file_size_wrap">

                                                    <span>文件大小：</span>
                                                    <span> <?php $file_size = get_post_meta($post->ID, 'file_size', true);{ echo $file_size; }?></span>
                                                </div>
                                                <div class="dl_count_wrap">
                                                    <span>下载次数： </span>
                                                    <span> <?php $dl_count = get_post_meta($post->ID, 'dl_count', true);{ echo strval($dl_count)?$dl_count:''. rand(2,99) .'万'; }?></span>
                                                </div>
                                                <div style="clear: both"></div>
                                            </div>


                                            <div>

                                                <?php $app_score = get_post_meta($post->ID, 'app_score', true);
                                                $app_score2 = preg_match('/(\d+)/',$app_score,$r)?$r[1]:$app_score;
                                                $ratings = 0;
                                                if(preg_match('/^\d+$/',$app_score2)){
                                                    $ratings =  intval($app_score2)/20;
                                                }
                                                ?>
                                                <span>网友评价：</span>
                                                <div class="br-wrapper br-theme-fontawesome-stars">
                                                    <div class="br-widget br-readonly">
                                                        <?php
                                                            for ($i=0;$i<=5;$i++){
                                                                if($i<=$ratings){
                                                                    echo "<a href=\"#\" data-rating-value=\"1\" data-rating-text=\"$i\" class=\"br-selected br-current\"></a>";
                                                                }else{
                                                                    echo "<a href=\"#\" data-rating-value=\"2\" data-rating-text=\"$i\"></a>";
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>

<!--                                                <select id="ratings-padagogy_--><?php //echo $post->ID ?><!--" data-vvv="--><?php //echo $ratings ?><!--" style="display: none;" name="rating" autocomplete="off">                             <option value="1">1</option>-->
<!--                                                    <option value="2">2</option>-->
<!--                                                    <option value="3">3</option>-->
<!--                                                    <option value="4">4</option>-->
<!--                                                    <option value="5">5</option>-->
<!--                                                </select>-->
<!--                                                --><?php //echo $app_score; ?>
                                                <script>
//<                                                    $('#ratings-padagogy_--><?php ////echo $post->ID ?>////').barrating({
//                                                       theme: 'fontawesome-stars',
//                                                       readonly: true,
//                                                    }).barrating('set', Math.floor(<?php ////echo $ratings ?>////));
                                             </script>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                </div>

                                <div class="clear"></div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                <?php } wp_reset_query(); ?>
        </main>
        <div class="clear"></div>
        <?php begin_pagenav(); ?>
    </section>

<?php get_footer(); ?>