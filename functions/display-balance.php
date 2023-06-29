<?php
function display_instructor_cert_balance() {
    error_log('display_instructor_cert_balance function started'); // Log the start of the function
    $user_id = get_current_user_id();
    $balance = get_user_meta( $user_id, 'cert_balance', true );
    
    // Check for empty balance and set it to 0 if necessary
    if ( empty( $balance ) ) {
        // If there is no cert_balance meta field, return the default text
        return '<div class="cert_balance_message">You currently have no certificate tokens. <a href="https://testmabpro.wpengine.com/product/certificate-tokens/">Purchase tokens here</a>.</div>';
    }

    // If there is a cert_balance meta field, return the balance
    return '<div class="cert_balance_value">Your current certificate balance is: ' . $balance . '</div>';
}
add_shortcode( 'instructor_cert_balance', 'display_instructor_cert_balance' );
?>
