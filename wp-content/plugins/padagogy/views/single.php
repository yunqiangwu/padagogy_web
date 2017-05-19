<?php get_header(); ?>

    <style type="text/css">
        .tao-goods {
            border: 1px solid #fff;
        }
        .tao-img {
            float: left;
            width: 261px;
            height: 261px;
            margin: 0 30px 30px 0;
            overflow: hidden;
            transition-duration: .3s;
        }
        .tao-img a img {
            width: 261px;
            height: 261px;
            -webkit-transition: -webkit-transform .3s linear;
            -moz-transition: -moz-transform .3s linear;
            -o-transition: -o-transform .3s linear;
            transition: transform .3s linear
        }
        .tao-img:hover a img {
            transition: All 0.7s ease;
            -webkit-transform: scale(1.1);
            -moz-transform: scale(1.1);
            -ms-transform: scale(1.1);
            -o-transform: scale(1.1);
        }
        .brief {
            float: left;
            width: 50%;
            margin: 0;
            padding: 0 10px 10px 10px;
        }
        .product-m {
            font-size: 15px;
            display: block;
            margin: 0 0 15px 0;
        }
        .pricex {
            font-size: 16px;
            color: #ff4400;
            display: block;
        }

        .tao-goods ul li {
            font-size: 14px;
            font-weight: normal;
            list-style:none;
            border: none;
            line-height: 180%;
            margin: 0;
            box-shadow: none;
        }
        .taourl a {
            float: left;
            background: #ff4400;
            color: #fff !important;
            line-height: 35px;
            margin: 40px 20px 0 0;
            padding: 0 15px;
            border: 1px solid #ff4400;
            border-radius: 2px;
        }
        .taourl a:hover {
            background: #7ab951;
            border: 1px solid #7ab951;
        }
        .discount a {
            float: left;
            background: #fff;
            color: #444 !important;
            line-height: 35px;
            margin: 40px 20px 0 0;
            padding: 0 15px;
            border: 1px solid #ddd;
            border-radius: 2px;
        }
        .discount a:hover {
            color: #fff !important;
            background: #7ab951;
            border: 1px solid #7ab951;
        }

        @media screen and (max-width: 640px) {
            .brief {
                width: 100%;
            }
            .tao-img {
                float: inherit;
                margin: 0 auto 0;
            }
        }
    </style>

    <link rel="stylesheet" href="<?php echo Padagogy::url('lib/jquery-bar-rating/dist/themes/fontawesome-stars.css') ?>">
    <link rel="stylesheet"<?php echo Padagogy::url('lib/jquery-bar-rating/dist/themes/css-stars.css') ?>">
    <script src="<?php echo Padagogy::url('lib/jquery-bar-rating/dist/jquery.barrating.min.js') ?>"></script>

    <div id="primary" class="content-area">
        <main id="main" class="site-main single-tao" role="main">

            <?php while ( have_posts() ) : the_post(); ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('wow fadeInUp tao'); ?> data-wow-delay="0.3s">

                    <header class="entry-header">
                        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    </header><!-- .entry-header -->

                    <div class="entry-content">
                        <div class="single-content">
                            <div class="tao-goods">
                                <figure class="tao-img">
                                    <?php padagogy_thumbnail(); ?>
                                </figure>

                                <div class="brief">

                                    <div>
                                        <div>
                                            <span>文件大小：</span>
                                            <span> <?php $file_size = get_post_meta($post->ID, 'file_size', true);{ echo $file_size; }?></span>
                                        </div>
                                        <div>
                                            <span>下载次数： </span>
                                            <span> <?php $dl_count = get_post_meta($post->ID, 'dl_count', true);{ echo strval($dl_count)?$dl_count:''. 14 .'万'; }?></span>
                                        </div>
                                    </div>

                                    <div class="single-tag">
                                        <span>领域分类：</span>
                                        <ul class="wow fadeInUp" data-wow-delay="0.3s" style="    width: 100%;
    display: inline-block;
    margin: -30px 0 0 65px;">
                                            <?php echo get_the_term_list($post->ID,  'app_classification', '<li style="width:80px;display: inline-block;;transform: scale(.8);">', '</li><li style="display: inline-block;width:80px;transform: scale(.8);">', '</li>' ); ?>
                                        </ul>
                                    </div>

                                    <div>

                                        <style>
                                            .br-wrapper,.br-wrapper a.br-selected:after{
                                                display: inline-block;
                                                color: #ffbe00 !important;
                                            }
                                            .br-wrapper{
                                                transform: scale(1.5);
                                                margin: 10px 20px;
                                            }
                                        </style>
                                        <?php $app_score = get_post_meta($post->ID, 'app_score', true);
                                            $app_score2 = preg_match('/(\d+)/',$app_score,$r)?$r[1]:$app_score;
                                            $ratings = 0;
                                            if(preg_match('/^\d+$/',$app_score2)){
                                                $ratings =  intval($app_score2)/20;
                                            }

                                        ?>
                                        <script>
                                            $(function() {
                                                $('#example-css').barrating({
                                                    theme: 'fontawesome-stars',
                                                    readonly: true,
                                                }).barrating('set', Math.floor(<?php echo $ratings ?>));
                                            });
                                        </script>
                                        <span>网友评价：</span>
                                        <select id="example-css" style="display: none;" name="rating" autocomplete="off">
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                         </select>
                                        <?php echo $app_score; ?>

                                    </div>

                                    <div class="clear"></div>
                                </div>
                            </div>



                            <div class="clear"></div>

                            <?php the_content(); ?>
                            <?php if ( get_post_meta($post->ID, 'no_sidebar', true) ) : ?><style>	#primary {width: 100%;}#sidebar,.r-hide {display: none;}</style><?php endif; ?>
                            <div class="clear"></div>
                            <?php wp_link_pages(array('before' => '<div class="page-links">', 'after' => '', 'next_or_number' => 'next', 'previouspagelink' => '<span><i class="fa fa-angle-left"></i></span>', 'nextpagelink' => "")); ?>
                            <?php wp_link_pages(array('before' => '', 'after' => '', 'next_or_number' => 'number', 'link_before' =>'<span>', 'link_after'=>'</span>')); ?>
                            <?php wp_link_pages(array('before' => '', 'after' => '</div>', 'next_or_number' => 'next', 'previouspagelink' => '', 'nextpagelink' => '<span><i class="fa fa-angle-right"></i></span> ')); ?>
                        </div>

                        <?php if (zm_get_option('zm_like')) { ?>
                            <?php get_template_part( 'inc/social' ); ?>
                        <?php } else { ?>
                            <div id="social"></div>
                        <?php } ?>

                        <footer class="single-footer">
                            <ul class="single-meta">
                                <?php edit_post_link('编辑', '<li class="edit-link">', '</li>' ); ?>
                                <?php if ( post_password_required() ) { ?>
                                    <li class="comment"><a href="#comments">密码保护</a></li>
                                <?php } else { ?>
                                    <li class="comment"><?php comments_popup_link( '<i class="fa fa-comment-o"></i> 发表评论', '<i class="fa fa-comment-o"></i> 1 ', '<i class="fa fa-comment-o"></i> %' ); ?></li>
                                <?php } ?>
                                <?php if( function_exists( 'the_views' ) ) { the_views(true, '<li class="views"><i class="fa fa-eye"></i> ','</li>');  } ?>
                            </ul>
                            <ul id="fontsize">A+</ul>
<!--                            <div class="single-cat-tag">-->
<!--                                <div class="single-cat">分类：--><?php //echo get_the_term_list( $post->ID,  'taobao', '' ); ?><!--</div>-->
<!--                            </div>-->
                        </footer><!-- .entry-footer -->

                        <div class="clear"></div>
                    </div><!-- .entry-content -->

                </article><!-- #post -->

<!--                --><?php //if (zm_get_option('copyright')) { ?>
<!--                    --><?php //get_template_part( 'inc/copyright' ); ?>
<!--                --><?php //} ?>

<!--                --><?php //if (zm_get_option('related_img')) { ?>
<!--                    --><?php //include( 'template/single-padagogy.php' ); ?>
<!--                --><?php //} ?>

<!--                --><?php //get_template_part('ad/ads', 'comments'); ?>

                <nav class="nav-single wow fadeInUp" data-wow-delay="0.3s">
<!--                    --><?php
//                    if (get_previous_post()) { previous_post_link( '%link','<span class="meta-nav"><span class="post-nav"><i class="fa fa-angle-left"></i> ' . sprintf(__( '上一篇', 'begin' )) . '</span><br/>%title</span>' ); } else { echo "<span class='meta-nav'><span class='post-nav'>" . sprintf(__( '没有了', 'begin' )) . "<br/></span>" . sprintf(__( '已是最后文章', 'begin' )) . "</span>"; }
//                    if (get_next_post()) { next_post_link( '%link', '<span class="meta-nav"><span class="post-nav">' . sprintf(__( '下一篇', 'begin' )) . ' <i class="fa fa-angle-right"></i></span><br/>%title</span>' ); } else { echo "<span class='meta-nav'><span class='post-nav'>" . sprintf(__( '没有了', 'begin' )) . "<br/></span>" . sprintf(__( '已是最新文章', 'begin' )) . "</span>"; }
//                    ?>
                    <div class="clear"></div>
                </nav>

                <?php
                the_post_navigation( array(
                    'next_text' => '<span class="meta-nav-l" aria-hidden="true"><i class="fa fa-angle-right"></i></span>',
                    'prev_text' => '<span class="meta-nav-r" aria-hidden="true"><i class="fa fa-angle-left"></i></span>',
                ) );
                ?>

                <?php if ( comments_open() || get_comments_number() ) : ?>

                    <?php comments_template( '', true ); ?>
                <?php endif; ?>

            <?php endwhile; ?>

        </main><!-- .site-main -->
    </div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>