<?php

add_action('woocommerce_order_status_changed', function ($order_id, $old_status, $new_status) {
    error_log('woocommerce_order_status_changed function started'); // Log the start of the function
    // Get the order.
    $order = wc_get_order($order_id);

    // Only complete orders that have a total of $0 and are in the "processing" status.
    if (
        $order->get_total() == 0
        && $order->is_paid()
        && $order->get_status() == 'processing'
    ) {
        // Update the order status to "completed".
        $order->update_status('completed');
        error_log('woocommerce_order_status_changed function ended'); // Log the end of the function
   // Call the add_cert_balance_field function directly
   add_cert_balance_field($order_id);
    }
}, 10, 3);
?>