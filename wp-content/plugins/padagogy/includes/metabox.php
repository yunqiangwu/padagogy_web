<?php
/**
 * Created by PhpStorm.
 * User: wayne
 * Date: 2017/5/23
 * Time: 23:46
 */


function mymetabox_get_meta( $value ) {
    global $post;

    $field = get_post_meta( $post->ID, $value, true );
    if ( ! empty( $field ) ) {
        return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
    } else {
        return false;
    }
}

function mymetabox_add_meta_box() {
    add_meta_box(
        'mymetabox-mymetabox',
        __( 'mymetabox', 'mymetabox' ),
        'mymetabox_html',
        'post',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'mymetabox_add_meta_box' );

function mymetabox_html( $post) {
    wp_nonce_field( '_mymetabox_nonce', 'mymetabox_nonce' ); ?>

    <p>test customize meta box</p>

    <p>
        <label for="mymetabox_aa"><?php _e( 'aa', 'mymetabox' ); ?></label><br>
        <input type="text" name="mymetabox_aa" id="mymetabox_aa" value="<?php echo mymetabox_get_meta( 'mymetabox_aa' ); ?>">
    </p>    <p>
        <label for="mymetabox_bb"><?php _e( 'bb', 'mymetabox' ); ?></label><br>
        <textarea name="mymetabox_bb" id="mymetabox_bb" ><?php echo mymetabox_get_meta( 'mymetabox_bb' ); ?></textarea>

    </p>    <p>
        <label for="mymetabox_cc"><?php _e( 'cc', 'mymetabox' ); ?></label><br>
        <select name="mymetabox_cc" id="mymetabox_cc">
            <option <?php echo (mymetabox_get_meta( 'mymetabox_cc' ) === 'cc-1' ) ? 'selected' : '' ?>>cc-1</option>
            <option <?php echo (mymetabox_get_meta( 'mymetabox_cc' ) === 'cc-2' ) ? 'selected' : '' ?>>cc-2</option>
            <option <?php echo (mymetabox_get_meta( 'mymetabox_cc' ) === 'cc-3' ) ? 'selected' : '' ?>>cc-3</option>
        </select>
    </p>    <p>

        <input type="checkbox" name="mymetabox_dd" id="mymetabox_dd" value="dd" <?php echo ( mymetabox_get_meta( 'mymetabox_dd' ) === 'dd' ) ? 'checked' : ''; ?>>
        <label for="mymetabox_dd"><?php _e( 'dd', 'mymetabox' ); ?></label> </p>    <p>

    <input type="radio" name="mymetabox_ee" id="mymetabox_ee_0" value="ee-1" <?php echo ( mymetabox_get_meta( 'mymetabox_ee' ) === 'ee-1' ) ? 'checked' : ''; ?>>
    <label for="mymetabox_ee_0">ee-1</label><br>

    <input type="radio" name="mymetabox_ee" id="mymetabox_ee_1" value="ee-2" <?php echo ( mymetabox_get_meta( 'mymetabox_ee' ) === 'ee-2' ) ? 'checked' : ''; ?>>
    <label for="mymetabox_ee_1">ee-2</label><br>
    </p><?php
}

function mymetabox_save( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! isset( $_POST['mymetabox_nonce'] ) || ! wp_verify_nonce( $_POST['mymetabox_nonce'], '_mymetabox_nonce' ) ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( isset( $_POST['mymetabox_aa'] ) )
        update_post_meta( $post_id, 'mymetabox_aa', esc_attr( $_POST['mymetabox_aa'] ) );
    if ( isset( $_POST['mymetabox_bb'] ) )
        update_post_meta( $post_id, 'mymetabox_bb', esc_attr( $_POST['mymetabox_bb'] ) );
    if ( isset( $_POST['mymetabox_cc'] ) )
        update_post_meta( $post_id, 'mymetabox_cc', esc_attr( $_POST['mymetabox_cc'] ) );
    if ( isset( $_POST['mymetabox_dd'] ) )
        update_post_meta( $post_id, 'mymetabox_dd', esc_attr( $_POST['mymetabox_dd'] ) );
    else
        update_post_meta( $post_id, 'mymetabox_dd', null );
    if ( isset( $_POST['mymetabox_ee'] ) )
        update_post_meta( $post_id, 'mymetabox_ee', esc_attr( $_POST['mymetabox_ee'] ) );
}
add_action( 'save_post', 'mymetabox_save' );

/*
    Usage: mymetabox_get_meta( 'mymetabox_aa' )
    Usage: mymetabox_get_meta( 'mymetabox_bb' )
    Usage: mymetabox_get_meta( 'mymetabox_cc' )
    Usage: mymetabox_get_meta( 'mymetabox_dd' )
    Usage: mymetabox_get_meta( 'mymetabox_ee' )
*/