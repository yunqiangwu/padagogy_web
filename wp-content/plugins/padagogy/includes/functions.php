<?php
/**
 * Created by PhpStorm.
 * User: wayne
 * Date: 2017/5/8
 * Time: 19:53
 */
//require_once( 'metabox.php');

// Padagogy缩略图
function padagogy_thumbnail() {
    global $post;
    if ( get_post_meta($post->ID, 'app_icon', true) ) {
        $image = get_post_meta($post->ID, 'app_icon', true);
        echo '<a href="'.esc_url( get_permalink() ).'"><img src=';
        echo $image;
        echo ' alt="'.$post->post_title .'" /></a>';
    } else {
        if ( has_post_thumbnail() ) {
            echo '<a href="'.get_permalink().'">';
            the_post_thumbnail('tao', array('alt' => get_the_title()));
            echo '</a>';
        } else {
            $content = $post->post_content;
            preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
            $n = count($strResult[1]);
            if($n > 0){
                echo '<a href="'.get_permalink().'"><img src="'.get_template_directory_uri().'/timthumb.php?src='.$strResult[1][0].'&w='.zm_get_option('img_t_w').'&h='.zm_get_option('img_t_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a>';
            }
        }
    }
}



function padagogy_thumbnail_h() {
    global $post;
    if ( get_post_meta($post->ID, 'app_icon', true) ) {
        $image = get_post_meta($post->ID, 'app_icon', true);
        echo '<span class="load"><a href="'.esc_url( get_permalink() ).'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original=';
        echo $image;
        echo ' alt="'.$post->post_title .'" /></a></span>';
    } else {
        if ( has_post_thumbnail() ) {
            echo '<a href="'.get_permalink().'">';
            the_post_thumbnail('tao', array('alt' => get_the_title()));
            echo '</a>';
        } else {
            $content = $post->post_content;
            preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
            $n = count($strResult[1]);
            if($n > 0){
                echo '<span class="load"><a href="'.get_permalink().'"><img src="' . get_template_directory_uri() . '/img/loading.png" data-original="'.get_template_directory_uri().'/timthumb.php?src='.$strResult[1][0].'&w='.zm_get_option('img_t_w').'&h='.zm_get_option('img_t_h').'&a='.zm_get_option('crop_top').'&zc=1" alt="'.$post->post_title .'" /></a></span>';
            }
        }
    }
}


function get_padagogy_search_form($isecho = true){
    if($isecho){
        require Padagogy::dir("views/searchform.php");
        return;
    }
    ob_start();
    require( Padagogy::dir("views/searchform.php") );
    return ob_get_clean();
}


function mypadagogytheme_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ( 'div' == $args['style'] ) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    // 楼层
    global $commentcount;
    if(!$commentcount) {
        if ( get_query_var('cpage') > 0 )
            $page = get_query_var('cpage')-1;
        else $page = get_query_var('cpage');
        $cpp=get_option('comments_per_page');
        $commentcount = $cpp * $page;
    }
    ?>

    <li class="comments-anchor"><ul id="anchor-comment-<?php comment_ID() ?>"></ul></li>
    <<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? 'wow fadeInUp' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
    <?php if ( 'div' != $args['style'] ) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
    <?php endif; ?>
    <div class="comment-author vcard">
        <?php if (zm_get_option('lazy_c')) { ?>
            <?php echo '<img class="avatar" src="' . get_template_directory_uri() . '/img/load-avatar.gif" alt="avatar" data-original="' . preg_replace(array('/^.+(src=)(\"|\')/i', '/(\"|\')\sclass=(\"|\').+$/i'), array('', ''), get_avatar( $comment, '64','', get_comment_author())) . '" />'; ?>
        <?php } else { ?>
            <?php echo get_avatar( $comment, 64, '', get_comment_author() ); ?>
        <?php } ?>
        <!--<?php printf( __( '<cite class="fn">%s</cite> <span class="says">:</span>' ), get_comment_author_link() ); ?>-->
        <strong>
            <?php if (zm_get_option('link_to')) { ?>
                <?php commentauthor(); ?>
            <?php } else { ?>
                <?php comment_author_link(); ?>
            <?php } ?>
        </strong>
        <?php get_author_admin($comment->comment_author_email, $comment->user_id); ?>
        <?php if (zm_get_option('vip')) { ?><?php get_author_class($comment->comment_author_email, $comment->user_id); ?><?php if(user_can($comment->user_id, 1)); ?><?php } ?>
        <span class="comment-meta commentmetadata">
			<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>"></a><br />
			<span class="comment-aux">
				<span class="reply"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => '' . sprintf(__( '回复', 'begin' )) . '', 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></span>
                <?php printf('%1$s %2$s', get_comment_date(),  get_comment_time() ); ?>
                <?php
                if ( current_user_can('level_10') ) {
                    $url = home_url();
                    echo '<a id="delete-'. $comment->comment_ID .'" href="' . wp_nonce_url("$url/wp-admin/comment.php?action=deletecomment&amp;p=" . $comment->comment_post_ID . '&amp;c=' . $comment->comment_ID, 'delete-comment_' . $comment->comment_ID) . '" >&nbsp;' . sprintf(__( '删除', 'begin' )) . '</a>';
                }
                ?>
                <?php edit_comment_link( '' . sprintf(__( '编辑', 'begin' )) . '' , '&nbsp;', '' ); ?>
                <?php if (zm_get_option('comment_floor')) { ?>
                    <span class="floor">
						<?php
                        if(!$parent_id = $comment->comment_parent){
                            switch ($commentcount){
                                case 0 :echo "&nbsp;" . sprintf(__( '沙发', 'begin' )) . "";++$commentcount;break;
                                case 1 :echo "&nbsp;" . sprintf(__( '板凳', 'begin' )) . "";++$commentcount;break;
                                case 2 :echo "&nbsp;" . sprintf(__( '地板', 'begin' )) . "";++$commentcount;break;
                                default:printf('&nbsp;%1$s' . sprintf(__( '楼', 'begin' )) . '', ++$commentcount);
                            }
                        }
                        ?>
                        <?php if( $depth > 1){printf('&nbsp;%1$s' . sprintf(__( '层', 'begin' )) . '', $depth-1);} ?>
					</span>
                <?php } ?>
			</span>
		</span>
    </div>
<!--    rating-->
    <div>评分: &nbsp;&nbsp; <div class="br-wrapper br-theme-fontawesome-stars">
        <div class="br-widget br-readonly">
            <?php
            $rating = get_comment_meta( get_comment_ID(), 'rating', true);
            for ($i=0;$i<=5;$i++){
                if($i<=$rating){
                    echo "<a href=\"#\" data-rating-value=\"1\" data-rating-text=\"$i\" class=\"br-selected br-current\"></a>";
                }else{
                    echo "<a href=\"#\" data-rating-value=\"2\" data-rating-text=\"$i\"></a>";
                }
            }
            ?>
        </div>
    </div>
    <?php comment_text(); ?>
    <?php if ( $comment->comment_approved == '0' ) : ?>
        <div class="comment-awaiting-moderation"><?php _e( '您的评论正在等待审核！', 'begin' ); ?></div>
    <?php endif; ?>
    <?php if ( 'div' != $args['style'] ) : ?>
        </div>
    <?php endif; ?>
    <?php
}
