<?php
/*
Plugin Name: Login Logout
Plugin URI: http://web-profile.com.ua/wordpress/plugins/login-logout/
Description: Show login or logout link. Show register or site-admin link.
Version: 1.7.0
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
		//$title = apply_filters('widget_title', empty($instance['title']) ? __('Login-logout') : $instance['title'], $instance, $this->id_base);
		$title = apply_filters('widget_title', $instance['title']);
		$login_text = empty($instance['login_text']) ? __('Log in') : $instance['login_text'];
		$logout_text = empty($instance['logout_text']) ? __('Log out') : $instance['logout_text'];
		$register_link = $instance['register_link'] ? '1' : '0';
		$register_text = empty($instance['register_text']) ? __('Register') : $instance['register_text'];
		$admin_link = $instance['admin_link'] ? '1' : '0';
		$admin_text = empty($instance['admin_text']) ? __('Site Admin') : $instance['admin_text'];
		$login_redirect_to = $instance['login_redirect_to'];
		$logout_redirect_to = $instance['logout_redirect_to'];
		
		echo $before_widget;
		if ( $title ){
			echo $before_title . $title . $after_title;
		}
		$redirect_to_self = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		//$redirect_to = $_SERVER['PATH_INFO'];
		if( empty( $login_redirect_to ) ){
			$login_redirect_to = $redirect_to_self;
		}
		if( empty( $logout_redirect_to ) ){
			$logout_redirect_to = $redirect_to_self;
		}
		
?>
		<ul>
			<li><?php
			//wp_loginout( $redirect_to_self );
			if ( ! is_user_logged_in() ){
				echo '<a href="'.esc_url( wp_login_url( $login_redirect_to ) ).'">'.$login_text.'</a>';
			}else{
				echo '<a href="'.esc_url( wp_logout_url( $logout_redirect_to ) ).'">'.$logout_text.'</a>';
			}
			?></li>
			<?php
			//wp_register();
			if( $register_link ){
				if ( ! is_user_logged_in() ) {
					if ( get_option('users_can_register') ){
						echo '<li><a href="'.site_url('wp-login.php?action=register', 'login').'">'.$register_text.'</a></li>';
					}
				}
			}
			if( $admin_link ){
				if ( is_user_logged_in() ) {
					echo '<li><a href="'.admin_url().'">'.$admin_text.'</a></li>';
				}
			}
			?>
		</ul>
<?php
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args( (array) $new_instance, array( 'title' => '', 'register_link' => 0, 'admin_link' => 0, 'login_redirect_to' => '', 'logout_redirect_to' => '') );
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['login_text'] = strip_tags($new_instance['login_text']);
		$instance['logout_text'] = strip_tags($new_instance['logout_text']);
		$instance['register_link'] = $new_instance['register_link'] ? 1 : 0;
		$instance['register_text'] = strip_tags($new_instance['register_text']);
		$instance['admin_link'] = $new_instance['admin_link'] ? 1 : 0;
		$instance['admin_text'] = strip_tags($new_instance['admin_text']);
		$instance['login_redirect_to'] = strip_tags($new_instance['login_redirect_to']);
		$instance['logout_redirect_to'] = strip_tags($new_instance['logout_redirect_to']);
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'register_link' => 0, 'admin_link' => 0 ) );
		$title = strip_tags($instance['title']);
		$login_text = strip_tags($instance['login_text']);
		$logout_text = strip_tags($instance['logout_text']);
		$register_link = $instance['register_link'] ? 'checked="checked"' : '';
		$register_text = strip_tags($instance['register_text']);
		$admin_link = $instance['admin_link'] ? 'checked="checked"' : '';
		$admin_text = strip_tags($instance['admin_text']);
		$login_redirect_to = strip_tags($instance['login_redirect_to']);
		$logout_redirect_to = strip_tags($instance['logout_redirect_to']);
		
?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('login_text'); ?>"><?php _e('Login text:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('login_text'); ?>" name="<?php echo $this->get_field_name('login_text'); ?>" type="text" value="<?php echo esc_attr($login_text); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('logout_text'); ?>"><?php _e('Logout text:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('logout_text'); ?>" name="<?php echo $this->get_field_name('logout_text'); ?>" type="text" value="<?php echo esc_attr($logout_text); ?>" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php echo $register_link; ?> id="<?php echo $this->get_field_id('register_link'); ?>" name="<?php echo $this->get_field_name('register_link'); ?>" /> <label for="<?php echo $this->get_field_id('register_link'); ?>"><?php _e('Show register link (if user is logged out and if users can register)'); ?>;</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('register_text'); ?>"><?php _e('Register text:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('register_text'); ?>" name="<?php echo $this->get_field_name('register_text'); ?>" type="text" value="<?php echo esc_attr($register_text); ?>" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php echo $admin_link; ?> id="<?php echo $this->get_field_id('admin_link'); ?>" name="<?php echo $this->get_field_name('admin_link'); ?>" /> <label for="<?php echo $this->get_field_id('admin_link'); ?>"><?php _e('Show admin link (if user is logged in)'); ?>;</label>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('admin_text'); ?>"><?php _e('Admin text:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('admin_text'); ?>" name="<?php echo $this->get_field_name('admin_text'); ?>" type="text" value="<?php echo esc_attr($admin_text); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('login_redirect_to'); ?>"><?php _e('Redirect to this page after login:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('login_redirect_to'); ?>" name="<?php echo $this->get_field_name('login_redirect_to'); ?>" type="text" value="<?php echo esc_attr($login_redirect_to); ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('logout_redirect_to'); ?>"><?php _e('Redirect to this page after logout:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('logout_redirect_to'); ?>" name="<?php echo $this->get_field_name('logout_redirect_to'); ?>" type="text" value="<?php echo esc_attr($logout_redirect_to); ?>" />
			</p>
			
<?php
	}
}


/*function login_logout_widget_init() {
	register_widget('WP_Widget_Login_Logout');
	do_action('widgets_init');
}
add_action('init', 'login_logout_widget_init', 1);*/


add_action('widgets_init', create_function('', 'return register_widget("WP_Widget_Login_Logout");'));
