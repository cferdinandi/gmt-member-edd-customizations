<?php


	//
	// Stripe Label
	//

	/**
	 * Add stripe language to credit card field
	 */
	function gmt_edd_custom_add_via_stripe ( $gateways ) {
		if (array_key_exists( 'stripe', $gateways )) {
			if (array_key_exists( 'checkout_label', $gateways['stripe'] ))
			$gateways['stripe']['checkout_label'] = edd_get_option( 'gmt_edd_custom_credit_card_label' );
		}
		return $gateways;
	}
	add_filter( 'edd_payment_gateways', 'gmt_edd_custom_add_via_stripe', 20 );



	//
	// Name Fields
	//

	/**
	 * Unset first and last name as required fields in checkout
	 * @param  Array $required_fields Required fields
	 */
	function gmt_edd_custom_purchase_form_remove_required_fields( $required_fields ) {
		unset( $required_fields['edd_first'] );
		unset( $required_fields['edd_last'] );
		// unset( $required_fields['edd_user_login'] );
		return $required_fields;
	}
	add_filter( 'edd_purchase_form_required_fields', 'gmt_edd_custom_purchase_form_remove_required_fields' );


	/**
	 * Remove default name fields from checkout
	 */
	function gmt_edd_custom_remove_names() {
		remove_action( 'edd_purchase_form_after_user_info', 'edd_user_info_fields' );
		remove_action( 'edd_register_fields_before', 'edd_user_info_fields' );
		remove_action( 'edd_purchase_form_register_fields', 'edd_get_register_fields' );
		remove_action( 'edd_purchase_form_login_fields', 'edd_get_login_fields' );
	}
	add_action( 'init', 'gmt_edd_custom_remove_names' );


	/**
	 * Remove name fields from checkout form
	 */
	function gmt_edd_custom_user_info_fields() {
		if( is_user_logged_in() ) :
			$user_data = get_userdata( get_current_user_id() );
		endif;
		?>
		<fieldset id="edd_checkout_user_info">
			<?php do_action( 'edd_purchase_form_before_email' ); ?>
			<p id="edd-email-wrap">
				<label class="edd-label" for="edd-email"><strong><?php _e('Email Address', 'edd'); ?></strong></label>
				<input class="edd-input required" type="email" name="edd_email" placeholder="<?php _e('Email address', 'edd'); ?>" id="edd-email" value="<?php echo is_user_logged_in() ? $user_data->user_email : ''; ?>"/>
			</p>
			<?php do_action( 'edd_purchase_form_after_email' ); ?>
			<?php do_action( 'edd_purchase_form_user_info' ); ?>
		</fieldset>
		<?php
	}
	// add_action( 'edd_purchase_form_after_user_info', 'gmt_edd_custom_user_info_fields' );
	// add_action( 'edd_register_fields_before', 'gmt_edd_custom_user_info_fields' );



	/**
	 * Remove username from registration form
	 */
	function gmt_edd_custom_user_registration_fields () {
		$show_register_form = edd_get_option( 'show_register_form', 'none' );

		if ( is_user_logged_in() ) :
			$user_data = get_userdata( get_current_user_id() );
		endif;

		ob_start(); ?>
		<fieldset id="edd_register_fields">

			<?php if ( 'both' === $show_register_form ) { ?>
				<p id="edd-login-account-wrap">
					<?php esc_html_e( 'Already have an account?', 'easy-digital-downloads' ); ?> <a href="<?php echo esc_url( add_query_arg( 'login', 1 ) ); ?>" class="edd_checkout_register_login" data-action="checkout_login" data-nonce="<?php echo esc_attr( wp_create_nonce( 'edd_checkout_login' ) ); ?>"><?php esc_html_e( 'Log in', 'easy-digital-downloads' ); ?></a>
				</p>
			<?php } ?>

			<?php do_action( 'edd_register_fields_before' ); ?>

			<fieldset id="edd_register_account_fields">
				<legend><?php _e( 'Create an Account', 'easy-digital-downloads' ); if( !edd_no_guest_checkout() ) { echo ' ' . __( '(optional)', 'easy-digital-downloads' ); } ?></legend>

				<?php do_action( 'edd_purchase_form_before_email' ); ?>
				<p id="edd-email-wrap">
					<label class="edd-label" for="edd-email"><strong><?php _e('Email Address', 'edd'); ?></strong></label>
					<input class="edd-input required" type="email" name="edd_email" placeholder="<?php _e('Email address', 'edd'); ?>" id="edd-email" value="<?php echo is_user_logged_in() ? $user_data->user_email : ''; ?>"/>
				</p>
				<?php do_action( 'edd_purchase_form_after_email' ); ?>

				<?php do_action( 'edd_register_account_fields_before' ); ?>
				<p id="edd-user-pass-wrap">
					<label for="edd_user_pass">
						<?php _e( 'Password', 'easy-digital-downloads' ); ?> <span class="text-normal text-small">(<em>must be at least 8 characters long</em>)</span>
						<?php if ( edd_no_guest_checkout() ) : ?>
						<span class="edd-required-indicator">*</span>
						<?php endif; ?>
					</label>
					<span class="edd-description"><?php _e( 'The password used to access your account.', 'easy-digital-downloads' ); ?></span>
					<input name="edd_user_pass" id="edd_user_pass" class="<?php if(edd_no_guest_checkout()) { echo sanitize_html_class( 'required ' ); } ?>edd-input" placeholder="<?php _e( 'Password', 'easy-digital-downloads' ); ?>" type="password"/>
				</p>
				<p id="edd-user-pass-confirm-wrap" class="edd_register_password">
					<label for="edd_user_pass_confirm">
						<?php _e( 'Password again', 'easy-digital-downloads' ); ?>
						<?php if ( edd_no_guest_checkout() ) : ?>
						<span class="edd-required-indicator">*</span>
						<?php endif; ?>
					</label>
					<span class="edd-description"><?php _e( 'Confirm your password.', 'easy-digital-downloads' ); ?></span>
					<input name="edd_user_pass_confirm" id="edd_user_pass_confirm" class="<?php if ( edd_no_guest_checkout() ) { echo sanitize_html_class( 'required ' ); } ?>edd-input" placeholder="<?php _e( 'Confirm password', 'easy-digital-downloads' ); ?>" type="password"/>
				</p>
				<?php do_action( 'edd_register_account_fields_after' ); ?>
			</fieldset>

			<?php do_action('edd_register_fields_after'); ?>

			<input type="hidden" name="edd-purchase-var" value="needs-to-register"/>

			<?php do_action( 'edd_purchase_form_user_info' ); ?>
			<?php do_action( 'edd_purchase_form_user_register_fields' ); ?>

		</fieldset>
		<?php
		echo ob_get_clean();
	}
	add_action( 'edd_purchase_form_register_fields', 'gmt_edd_custom_user_registration_fields' );


	/**
	 * Add legend to checkout login
	 */
	function gmt_edd_custom_get_login_fields() {
		$color = edd_get_option( 'checkout_color', 'gray' );

		$color = 'inherit' === $color
			? ''
			: $color;

		$style = edd_get_option( 'button_style', 'button' );

		$show_register_form = edd_get_option( 'show_register_form', 'none' );

		ob_start(); ?>
			<fieldset id="edd_login_fields">
				<?php if ( 'both' === $show_register_form ) : ?>
					<p id="edd-new-account-wrap">
						<?php _e( 'Need to create an account?', 'easy-digital-downloads' ); ?>
						<a href="<?php echo esc_url( remove_query_arg( 'login' ) ); ?>" class="edd_checkout_register_login" data-action="checkout_register"  data-nonce="<?php echo wp_create_nonce( 'edd_checkout_register' ); ?>">
							<?php _e( 'Register', 'easy-digital-downloads' ); if ( ! edd_no_guest_checkout() ) { echo esc_html( ' ' . __( 'or checkout as a guest', 'easy-digital-downloads' ) ); } ?>
						</a>
					</p>
				<?php endif; ?>

				<?php do_action( 'edd_checkout_login_fields_before' ); ?>

				<p id="edd-user-login-wrap">
					<legend><?php _e( 'Sign In', 'easy-digital-downloads' ); if( !edd_no_guest_checkout() ) { echo ' ' . __( '(optional)', 'easy-digital-downloads' ); } ?></legend>

					<label class="edd-label" for="edd_user_login">
						<?php _e( 'Email', 'easy-digital-downloads' ); ?>
						<?php if ( edd_no_guest_checkout() ) : ?>
						<span class="edd-required-indicator">*</span>
						<?php endif; ?>
					</label>
					<input class="<?php if(edd_no_guest_checkout()) { echo sanitize_html_class( 'required ' ); } ?>edd-input" type="text" name="edd_user_login" id="edd_user_login" value="" placeholder="<?php _e( 'Your username or email address', 'easy-digital-downloads' ); ?>"/>
				</p>
				<p id="edd-user-pass-wrap" class="edd_login_password">
					<label class="edd-label" for="edd_user_pass">
						<?php _e( 'Password', 'easy-digital-downloads' ); ?>
						<?php if ( edd_no_guest_checkout() ) : ?>
						<span class="edd-required-indicator">*</span>
						<?php endif; ?>
					</label>
					<input class="<?php if ( edd_no_guest_checkout() ) { echo sanitize_html_class( 'required '); } ?>edd-input" type="password" name="edd_user_pass" id="edd_user_pass" placeholder="<?php _e( 'Your password', 'easy-digital-downloads' ); ?>"/>
					<?php if ( edd_no_guest_checkout() ) : ?>
						<input type="hidden" name="edd-purchase-var" value="needs-to-login"/>
					<?php endif; ?>
				</p>
				<p id="edd-user-login-submit">
					<input type="submit" class="edd-submit <?php echo sanitize_html_class( $color ); ?> <?php echo sanitize_html_class( $style ); ?>" name="edd_login_submit" value="<?php _e( 'Log in', 'easy-digital-downloads' ); ?>"/>
					<?php wp_nonce_field( 'edd-login-form', 'edd_login_nonce', false, true ); ?>
				</p>

				<?php do_action( 'edd_checkout_login_fields_after' ); ?>
			</fieldset><!--end #edd_login_fields-->
		<?php
		echo ob_get_clean();
	}
	add_action( 'edd_purchase_form_login_fields', 'gmt_edd_custom_get_login_fields' );


	/**
	 * Use the user's email address as their username for new accounts
	 * @return [type] [description]
	 */
	function gmt_edd_custom_set_checkout_email_as_username () {

		// Bail if there's no post
		if ( empty( $_POST ) ) {
			return false;
		}

		// Set username to email
		$_POST['edd_user_login'] = $_POST['edd_email'];

	}
	add_action( 'edd_pre_process_purchase', 'gmt_edd_custom_set_checkout_email_as_username' );



	//
	// GDPR Message
	//

	/**
	 * GDPR Message
	 */
	function gmt_edd_custom_add_gdpr_message() {
		echo stripslashes( edd_get_option('gmt_edd_custom_gdpr_message') );
	}
	add_action( 'edd_purchase_form_after_submit', 'gmt_edd_custom_add_gdpr_message' );



	//
	// Enforce strong password
	//

	function gmt_edd_enforce_strong_password ($valid_data) {
		if (empty($valid_data['new_user_data'])) return;
		if (empty($valid_data['new_user_data']['user_pass'])) return;
		if (strlen($valid_data['new_user_data']['user_pass']) > 7) return;
		edd_set_error( 'password_length', __( 'Please use a password that\'s at least 8 characters long.', 'gmt_member_edd' ) );
	}
	add_action( 'edd_checkout_error_checks', 'gmt_edd_enforce_strong_password', 20 );


	function gmt_edd_show_min_password_length () {
		echo '<div class="margin-bottom-small"><em>Password must be at least 8 characters long.</em></div>';
	}
	// add_action('edd_register_account_fields_after', 'gmt_edd_show_min_password_length', 10);
	// add_action('edd_register_account_fields_before', 'gmt_edd_show_min_password_length', 10);



	//
	// Disable Things
	//

	/**
	 * Disable EDD verification emails
	 */
	function gmt_edd_custom_disable_verification_email() {
		remove_action( 'edd_send_verification_email', 'edd_process_user_verification_request' );
	}
	add_action('init', 'gmt_edd_custom_disable_verification_email');


	/**
	 * Remove default credit card validator
	 */
	function gmt_edd_custom_remove_credit_card_validator() {
		wp_dequeue_script( 'creditCardValidator' );
	}
	add_action( 'wp_enqueue_scripts', 'gmt_edd_custom_remove_credit_card_validator' );