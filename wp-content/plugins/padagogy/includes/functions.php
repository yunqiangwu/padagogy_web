<?php
/**
 * Created by PhpStorm.
 * User: wayne
 * Date: 2017/5/8
 * Time: 19:53
 */


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

