<?php
/*
Template Name: 用户信息
*/

$wpdb->hide_errors(); nocache_headers();

global $userdata; get_currentuserinfo();

if(!empty($_POST['action'])){
	
	require_once(ABSPATH . 'wp-admin/includes/user.php');
	require_once(ABSPATH . WPINC . '/registration.php');
	
	check_admin_referer('update-profile_' . $user_ID);
	
	$errors = edit_user($user_ID);
	
	if ( is_wp_error( $errors ) ) {
		foreach( $errors->get_error_messages() as $message )
			$errmsg = "$message";
	}

	if($errmsg == '')
	{
		do_action('personal_options_update',$user_ID);
		$d_url = $_POST['dashboard_url'];
		wp_redirect( get_option("siteurl").'?page_id='.$post->ID.'&updated=true' );
	}
	else{
		$errmsg = '<div class="box-red">' . $errmsg . '</div>';
		$errcolor = 'style="background-color:#FFEBE8;border:1px solid #CC0000;"';
	}
}
get_currentuserinfo();
?>
<style type="text/css">
body,
input,
select,
textarea {
	font: 14px "Microsoft YaHei", Helvetica, Arial, Lucida Grande, Tahoma, sans-serif;
	color: #444;
	background: #fff;
}
#user-profile {}
#user-profile input, #user-profile select, #description {
	padding: 5px;
	border: 1px solid #ddd;
}
.profile input {
	background: #2f889a;
	color: #fff;
	width: 120px;
	margin: 40px 0 0 0;
	cursor: pointer;
	border: 1px solid #2f889a !important;
	border-radius: 2px;
	-webkit-appearance: none;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.1);
}
.profile input:hover {
	background: #4c94a2;
	border: 1px solid #4c94a2;
 	transition: all 0.2s ease-in 0s;
}
#user_login {
	background: #f1f1f1;
}
#user-profile td {
	font-size: 14px;
	padding: 5px;
}
#user-profile th {
	float: left;
	font-size: 14px;
	font-weight: normal;
	padding: 15px 0;
}
.profileok, .profileerr {
	float: left;
	color: #2f889a;
	padding: 5px;
	border: 1px solid #2f889a;
}
.profileerr {
	float: left;
	color: #ff0000;
	padding: 5px;
	border: 1px solid #ff0000;
}
#user-profile .avatar {
	width: 64px;
	height: 64px;
}
#simple-local-avatar {
	border: none !important;
}
</style>

<div id="user-profile" role="main">
<?php if(is_user_logged_in()){?>
<form name="profile" action="" method="post" enctype="multipart/form-data">
	<?php wp_nonce_field('update-profile_' . $user_ID) ?>
	<input type="hidden" name="from" value="profile" />
	<input type="hidden" name="action" value="update" />
	<input type="hidden" name="checkuser_id" value="<?php echo $user_ID ?>" />
	<input type="hidden" name="dashboard_url" value="<?php echo get_option("dashboard_url"); ?>" />
	<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>" />
	<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<?php if ( isset($_GET['updated']) ):
		$d_url = $_GET['d'];?>

	<tr>
		<td align="center" colspan="2"><span class="profileok"><?php _e( '个人资料已更新。', 'begin' ); ?></span></td>
	</tr>
	<?php elseif($errmsg!=""): ?>
	<tr>
		<td align="center" colspan="2"><span class="profileerr"><?php _e( '两次输入的密码不一样！', 'begin' ); ?></span></td>
	</tr>
	<?php endif;?>

	<tr>
		<th><?php _e( '登录名 (不可更改)', 'begin' ); ?></th>
		<td><input type="text" name="user_login" id="user_login" disabled="disabled" value="<?php echo esc_attr($userdata->user_login); ?>" style="width: 60%;" /></td>
	</tr>
	<tr>
		<th><?php _e( '名字', 'begin' ); ?></th>
		<td><input type="text" name="first_name" id="first_name" value="<?php echo $userdata->first_name ?>" style="width: 60%;" /></td>
	</tr>
	<tr>
		<th><?php _e( '呢称 (必填)', 'begin' ); ?></th>
		<td><input type="text" name="nickname" id="nickname" value="<?php echo esc_attr($userdata->nickname) ?>" style="width: 60%;" /></td>
	</tr>
	<tr>
		<th><?php _e( '公开显示', 'begin' ); ?></th>
		<td>
			<select name="display_name" id="display_name">
			<?php
				$public_display = array();
				$public_display['display_nickname']  = $userdata->nickname;
				$public_display['display_username']  = $userdata->user_login;

				if ( !empty($profileuser->first_name) )
					$public_display['display_firstname'] = $userdata->first_name;

				if ( !empty($profileuser->last_name) )
					$public_display['display_lastname'] = $userdata->last_name;

				if ( !empty($profileuser->first_name) && !empty($profileuser->last_name) ) {
					$public_display['display_firstlast'] = $userdata->first_name . ' ' . $userdata->last_name;
					$public_display['display_lastfirst'] = $userdata->last_name . ' ' . $userdata->first_name;
				}

				if ( !in_array( $profileuser->display_name, $public_display ) ) // Only add this if it isn't duplicated elsewhere
					$public_display = array( 'display_displayname' => $userdata->display_name ) + $public_display;

				$public_display = array_map( 'trim', $public_display );
				$public_display = array_unique( $public_display );

				foreach ( $public_display as $id => $item ) {
			?>
				<option <?php selected( $profileuser->display_name, $item ); ?>><?php echo $item; ?></option>
			<?php
				}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<th><?php _e( '电子邮件 (必填)', 'begin' ); ?></th>
		<td><input type="text" name="email" class="mid2" id="email" value="<?php echo $userdata->user_email ?>" style="width: 60%;" /></td>
	</tr>
	<tr>
		<th><?php _e( '新密码', 'begin' ); ?></th>
		<td><input type="password" name="pass1" class="mid2" id="pass1" value="" style="width: 60%;" /></td>
	</tr>
	<tr>
		<th><?php _e( '重复新密码', 'begin' ); ?></th>
		<td><input type="password" name="pass2" class="mid2" id="pass2" value="" style="width: 60%;" /></td>
	</tr>
	<tr><th><h4><?php _e( '其它信息', 'begin' ); ?></h4></th></tr>
	<tr>
		<th><?php _e( '站点', 'begin' ); ?></th>
		<td><input type="url" name="url" id="url" value="<?php echo esc_attr( $userdata->user_url ) ?>" style="width: 60%;" /></td>
	</tr>
	<tr>
	<tr>
		<th><?php _e( 'QQ', 'begin' ); ?></th>
		<td><input type="text" name="qq" id="qq" value="<?php echo esc_attr( get_the_author_meta( 'qq', $userdata->ID ) ); ?>" style="width: 60%;" /></td>
	</tr>
	<tr>
		<th><?php _e( '微信', 'begin' ); ?></th>
		<td><input type="text" name="weixin" id="weixin" value="<?php echo esc_attr( get_the_author_meta( 'weixin', $userdata->ID ) ); ?>" style="width: 60%;" /></td>
	</tr>
	<tr>
	<tr>
		<th><?php _e( '新浪微博', 'begin' ); ?></th>
		<td><input type="text" name="weibo" id="weibo" value="<?php echo esc_attr( get_the_author_meta( 'weibo', $userdata->ID ) ); ?>" style="width: 60%;" /></td>
	</tr>
	<tr>
		<th><?php _e( '个人说明', 'begin' ); ?></th>
		<td><textarea name="description" id="description" rows="5" cols="30"  style="width: 60%;" ><?php echo $userdata->description; ?></textarea></td>
	</tr>

	<tr><th><h4><?php _e( '上传头像', 'begin' ); ?></h4></th></tr>

	<tr>
		<td style="width: 50px;" valign="top">
		<?php global $current_user;	get_currentuserinfo();
			echo get_avatar( $current_user->user_email, 64); 
		?>
		</td>
		<td>
		<?php
			$options = get_option('simple_local_avatars_caps');

			if ( empty($options['simple_local_avatars_caps']) || current_user_can('upload_files') ) {
				do_action( 'simple_local_avatar_notices' ); 
				wp_nonce_field( 'simple_local_avatar_nonce', '_simple_local_avatar_nonce', false ); 
		?>
				<input type="file" name="simple-local-avatar" id="simple-local-avatar" /><br />
		<?php
				if ( empty( $profileuser->simple_local_avatar ) )
					echo '<span class="description">' . sprintf(__( '上传本地头像', 'begin' )) . '</span>';
			}
		?>
		</td>
	</tr>

	<tr>
		<td class="profile"><input type="submit" value="<?php _e( '更新个人资料', 'begin' ); ?>" /></td>
	</tr>
	</table>
	<input type="hidden" name="action" value="update" />
</form>

<?php }else{
 wp_redirect( home_url() );
 exit;
}?>
</div>