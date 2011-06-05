<?php
/*
Plugin Name: Login Logout
Plugin URI: http://web-profile.com.ua/wordpress/plugins/login-logout/
Description: Show login or logout link. Show register or site-admin link.
Version: 1.1.0
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
		$this->WP_Widget('meta', __('Login-logout'), $widget_ops);
	}
	
	function widget( $args, $instance ) {
		extract($args);
		$register_admin = $instance['register_admin'] ? '1' : '0';
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Login Logout') : $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
			$return_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			//$return_link = $_SERVER['PATH_INFO'];
?>
			<ul>
				<li><?php wp_loginout( $return_link ); ?></li>
				<?php
				if( $register_admin ){
					wp_register();
				}
				?>
			</ul>
<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'register_admin' => 0) );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['register_admin'] = $new_instance['register_admin'] ? 1 : 0;
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'register_admin' => 0 ) );
		$title = strip_tags($instance['title']);
		$register_admin = $instance['register_admin'] ? 'checked="checked"' : '';
?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php echo $register_admin; ?> id="<?php echo $this->get_field_id('register_admin'); ?>" name="<?php echo $this->get_field_name('register_admin'); ?>" /> <label for="<?php echo $this->get_field_id('register_admin'); ?>"><?php _e('Show register or site-admin link'); ?>;</label>
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

