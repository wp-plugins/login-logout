<?php
/*
Plugin Name: Login Logout
Plugin URI: http://web-profile.com.ua/wordpress/plugins/login-logout/
Description: Show login or logout link. Show register or site-admin link.
Version: 1.2.0
Author: webvitaly
Author Email: webvitaly(at)gmail.com
Author URI: http://web-profile.com.ua/

Future features:
- http-s;
- add translation;
*/

class WP_Widget_Login_Logout extends WP_Widget {

	function WP_Widget_Login_Logout() {
		$widget_ops = array('classname' => 'widget_login_logout', 'description' => __( 'Login-logout widget' ) );
		$this->WP_Widget('login_logout', __('Login-logout'), $widget_ops);
	}
	
	function widget( $args, $instance ) {
		extract($args);
		$register_link = $instance['register_link'] ? '1' : '0';
		$admin_link = $instance['admin_link'] ? '1' : '0';
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Login-logout') : $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
			$return_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			//$return_link = $_SERVER['PATH_INFO'];
?>
			<ul>
				<li><?php wp_loginout( $return_link ); ?></li>
				<?php
				//wp_register();
				if( $register_link ){
					if ( ! is_user_logged_in() ) {
						if ( get_option('users_can_register') ){
							echo '<li>' . '<a href="' . site_url('wp-login.php?action=register', 'login') . '">' . __('Register') . '</a>' . '</li>';
						}
					}
					
				}
				if( $admin_link ){
					if ( is_user_logged_in() ) {
						echo '<li>' . '<a href="' . admin_url() . '">' . __('Site Admin') . '</a>' . '</li>';
					}
				}
				?>
			</ul>
<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'register_link' => 0, 'admin_link' => 0) );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['register_link'] = $new_instance['register_link'] ? 1 : 0;
		$instance['admin_link'] = $new_instance['admin_link'] ? 1 : 0;
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'register_link' => 0, 'admin_link' => 0 ) );
		$title = strip_tags($instance['title']);
		$register_link = $instance['register_link'] ? 'checked="checked"' : '';
		$admin_link = $instance['admin_link'] ? 'checked="checked"' : '';
		
?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php echo $register_link; ?> id="<?php echo $this->get_field_id('register_link'); ?>" name="<?php echo $this->get_field_name('register_link'); ?>" /> <label for="<?php echo $this->get_field_id('register_link'); ?>"><?php _e('Show register link (if user is logged out and if users can register)'); ?>;</label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php echo $admin_link; ?> id="<?php echo $this->get_field_id('admin_link'); ?>" name="<?php echo $this->get_field_name('admin_link'); ?>" /> <label for="<?php echo $this->get_field_id('admin_link'); ?>"><?php _e('Show admin link (if user is logged in)'); ?>;</label>
			</p>
			
<?php
	}
}


function login_logout_widget_init() {
	register_widget('WP_Widget_Login_Logout');
	do_action('widgets_init');
}
add_action('init', 'login_logout_widget_init', 1);

//add_action('widgets_init', create_function('', 'return register_widget("WP_Widget_Login_Logout");'));

