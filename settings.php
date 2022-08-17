<?php

	/**
	 * Add settings section
	 * @param array $sections The current sections
	 */
	function gmt_edd_custom_settings_section ( $sections ) {
		$sections['gmt_edd_custom'] = __( 'GMT Customizations', 'gmt_edd' );
		return $sections;
	}
	add_filter( 'edd_settings_sections_extensions', 'gmt_edd_custom_settings_section' );


	/**
	 * Add settings
	 * @param  array $settings The existing settings
	 */
	function gmt_edd_custom_settings( $settings ) {

		$custom_settings = array(

			// TEST
			// array(
			// 	'id' => 'gmt_edd_custom_test',
			// 	'desc' => print_r( $payment, true),
			// 	'type' => 'descriptive_text',
			// ),

			// Heading
			array(
				'id'    => 'gmt_edd_custom_settings_cart',
				'name'  => '<strong>' . __( 'Checkout Settings', 'gmt_edd' ) . '</strong>',
				'desc'  => __( 'Settings for the checkout page', 'gmt_edd' ),
				'type'  => 'header',
			),

			// Credit Card Label
			array(
				'id'      => 'gmt_edd_custom_credit_card_label',
				'name'    => __( 'Credit Card Label', 'gmt_edd' ),
				'desc'    => __( 'Label for the credit card field', 'gmt_edd' ),
				'type'    => 'text',
				'std'     => __( 'Credit Card (via Stripe)', 'gmt_edd' ),
			),

			// GDPR Message
			array(
				'id'      => 'gmt_edd_custom_gdpr_message',
				'name'    => __( 'GDPR Message', 'gmt_edd' ),
				'desc'    => __( 'GDPR message to display after the checkout button', 'gmt_edd' ),
				'type'    => 'textarea',
				'std'     => '',
			),

			// // Registration
			// array(
			// 	'id' => 'gmt_edd_register_header',
			// 	'name' => '<strong>' . __( 'Auto Register', 'gmt_edd' ) . '</strong>',
			// 	'type' => 'header',
			// ),
			// array(
			// 	'id' => 'gmt_edd_register_disable_user_email',
			// 	'name' => __( 'Disable User Email', 'edd-auto-register' ),
			// 	'desc' => __( 'Disables the email sent to the user that contains login details', 'gmt_edd' ),
			// 	'type' => 'checkbox',
			// ),
			// array(
			// 	'id' => 'gmt_edd_register_disable_admin_email',
			// 	'name' => __( 'Disable Admin Notification', 'edd-auto-register' ),
			// 	'desc' => __( 'Disables the new user registration email sent to the admin', 'gmt_edd' ),
			// 	'type' => 'checkbox',
			// ),

		);

		if ( version_compare( EDD_VERSION, 2.5, '>=' ) ) {
			$custom_settings = array( 'gmt_edd_custom' => $custom_settings );
		}

		return array_merge( $settings, $custom_settings );

	}
	add_filter( 'edd_settings_extensions', 'gmt_edd_custom_settings', 999, 1 );