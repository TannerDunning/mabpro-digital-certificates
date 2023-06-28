<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://sites.google.com/mabpro.com/custom-cert-plugin/
 * @since             1.0.0
 * @package           Mabpro_Digital_Certificates
 *
 * @wordpress-plugin
 * Plugin Name:       Send MABPRO Certificates
 * Plugin URI:        https://sites.google.com/mabpro.com/custom-cert-plugin/
 * Description:       This plugin allows instructors to send personalized digital certificates to their students' emails. Instructors can purchase a set number of certificates through a WooCommerce product and then use a form to enter the students' information and send the certificates. The plugin includes a form builder, PDF generation, and email sending functionality.
 * Version:           1.0.0
 * Author:            Tanner
 * Author URI:        https://sites.google.com/mabpro.com/custom-cert-plugin/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mabpro-digital-certificates
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'MABPRO_DIGITAL_CERTIFICATES_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mabpro-digital-certificates-activator.php
 */
function activate_mabpro_digital_certificates() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mabpro-digital-certificates-activator.php';
	Mabpro_Digital_Certificates_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mabpro-digital-certificates-deactivator.php
 */
function deactivate_mabpro_digital_certificates() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mabpro-digital-certificates-deactivator.php';
	Mabpro_Digital_Certificates_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mabpro_digital_certificates' );
register_deactivation_hook( __FILE__, 'deactivate_mabpro_digital_certificates' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
add_action( 'init', 'mabpro_digital_certificates_init' );

require_once plugin_dir_path(__FILE__) . 'includes/process-certificate-form.php';
require_once plugin_dir_path(__FILE__) . 'functions/mabpro-certificates-post-type.php';
require_once plugin_dir_path(__FILE__) . 'functions/auto-complete-orders.php';
require_once plugin_dir_path( __FILE__ ) . 'functions/add-balance-field.php';
require_once plugin_dir_path( __FILE__ ) . 'functions/display-balance.php';

function mabpro_digital_certificates_init() {
    if ( is_page( 'send-certificates' ) ) { // Updated page URL
        // Enqueue js for adding students
        wp_enqueue_script('add-students', plugin_dir_url(__FILE__).'../public/js/add-students.js', array('jquery'), '', true);
    }
}

add_action( 'wp', 'mabpro_digital_certificates_init' );

// Register the shortcode
require_once( plugin_dir_path( __FILE__ ) .  'functions/form-shortcode.php' );
add_shortcode( 'certificate_form', 'certificate_form_shortcode' );
