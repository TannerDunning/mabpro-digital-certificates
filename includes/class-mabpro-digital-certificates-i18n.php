<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://sites.google.com/mabpro.com/custom-cert-plugin/
 * @since      1.0.0
 *
 * @package    Mabpro_Digital_Certificates
 * @subpackage Mabpro_Digital_Certificates/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Mabpro_Digital_Certificates
 * @subpackage Mabpro_Digital_Certificates/includes
 * @author     Tanner <Tanner@mabpro.com>
 */
class Mabpro_Digital_Certificates_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'mabpro-digital-certificates',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
