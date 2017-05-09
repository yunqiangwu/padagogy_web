<?php


// 视频
class P_PadagogyWidget extends WP_Widget {
    public function __construct() {
        $widget_ops = array(
            'classname' => 'P_PadagogyWidget',
            'description' => __( 'Padagogy分类' ),
            'customize_selective_refresh' => true,
        );
        parent::__construct('padagogy', 'Padagogy分类', $widget_ops);
    }

    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        $title = apply_filters( 'widget_title', $instance['title'] );
        echo $before_widget;
        if ( ! empty( $title ) )
            echo $before_title . $title . $after_title;
        $number = strip_tags($instance['number']) ? absint( $instance['number'] ) : 4;
        ?>

        <div class="picture">
            <?php
            $args = array(
                'post_type' => 'padagogy',
                'showposts' => $number,
//                'tax_query' => array(
//                    array(
//                        'taxonomy' => 'videos',
//                        'terms' => $instance['cat']
//                    ),
//                )
            );
            ?>
            <?php $my_query = new WP_Query($args); while ($my_query->have_posts()) : $my_query->the_post(); ?>
                <span class="img-box">
                    <span class="img-x1">
                        <span class="insets">
                            <?php if (zm_get_option('lazy_s')) { img_thumbnail_h(); } else { img_thumbnail(); } ?>
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
            $title = '最＋APP';
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
                <?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => 选择分类, 'hide_empty'=>0, 'hierarchical'=>1,	'taxonomy' => 'padagogy', 'selected'=>$instance['cat'])); ?></label>
        </p>
        <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
    <?php }
}

