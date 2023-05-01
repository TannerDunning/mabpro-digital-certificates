<?php

/**
 * Auto-completes orders that have a total of $0.
 */

add_action( 'woocommerce_order_status_changed', function( $order_id, $old_status, $new_status ) {

    // Get the order.
    $order = wc_get_order( $order_id );

    // Only complete orders that have a total of $0 and are in the "processing" status.
    if (
        $order->get_total() == 0
        && $order->is_paid()
        && $order->get_status() == 'processing'
    ) {

        // Update the order status to "completed".
        $order->update_status( 'completed' );
    }
});

?>
