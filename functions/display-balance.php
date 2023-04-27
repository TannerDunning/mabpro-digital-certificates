<?php
function display_instructor_cert_balance() {
    $user_id = get_current_user_id();
    $balance = get_user_meta( $user_id, 'cert_balance', true );
    
    // Check for empty balance and set it to 0 if necessary
    if ( empty( $balance ) ) {
        $balance = 0;
    }

    return $balance;
}
add_shortcode( 'instructor_cert_balance', 'display_instructor_cert_balance' );
