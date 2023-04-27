<?php
function certificate_form_shortcode( $atts ) {
    error_log( "certificate_form_shortcode() called" );
    ob_start(); // Start output buffering

    // Add the nonce field
    wp_nonce_field( 'mabpro_certificate_form', 'mabpro_certificate_form_nonce' );

    // Set the form action
    echo '<form action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" method="post">';
    echo '<input type="hidden" name="action" value="submit_certificate_form">';

    // Include the certificate-form.html content
    include( plugin_dir_path( __FILE__ ) . '../includes/certificate-form.html' ); // Updated path

    // Close the form
    echo '</form>';

    error_log( "certificate_form_shortcode() returning form" );
    return ob_get_clean(); // End output buffering and return the content
}

