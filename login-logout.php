<?php
/*
Plugin Name: Login Logout
Plugin URI: http://web-profile.com.ua/wordpress/plugins/login-logout/
Description: Show login or logout link.
Version: 1.0.0
Author: webvitaly
Author Email: webvitaly(at)gmail.com
Author URI: http://web-profile.com.ua/

*/

class WP_Widget_Login_Logout extends WP_Widget {

	function WP_Widget_Login_Logout() {
		$widget_ops = array('classname' => 'widget_login_logout', 'description' => __( 'Login-logout widget' ) );
		$this->WP_Widget('meta', __('Login-logout'), $widget_ops);
	}
	
	function widget( $args, $instance ) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Login Logout') : $instance['title'], $instance, $this->id_base);

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
			$return_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			//$return_link = $_SERVER['PATH_INFO'];
?>
			<ul>
				<li><?php wp_loginout( $return_link ); ?></li>
				<?php wp_register(); ?>
			</ul>
<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = strip_tags($instance['title']);
?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
<?php
	}
}


/*
function login_logout_widget_init() {
	register_widget('WP_Widget_Login_Logout');
	do_action('widgets_init');
}
add_action('init', 'login_logout_widget_init', 1);
*/

add_action('widgets_init', create_function('', 'return register_widget("WP_Widget_Login_Logout");'));
