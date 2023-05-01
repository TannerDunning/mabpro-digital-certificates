<?php

function add_cert_balance_field( $order_id ) {
    $order = wc_get_order( $order_id ); // Get the order object
    $items = $order->get_items(); // Get the items in the order
    foreach ( $items as $item ) {
        $product_id = $item->get_product_id(); // Get the product ID
        // Check if the product is a token product
        if ( $product_id == 34491 ) { // Replace 34491 with the ID of your token product
            $user_id = $order->get_user_id(); // Get the user ID
            $cert_balance = get_user_meta( $user_id, 'cert_balance', true ); // Get the current balance
            if ( ! $cert_balance ) {
                $cert_balance = 0;
            }
            $quantity = $item->get_quantity(); // Get the quantity of tokens purchased
            $cert_balance += $quantity; // Add the quantity of tokens to the balance
            update_user_meta( $user_id, 'cert_balance', $cert_balance ); // Update the balance field in the user profile
        }
    }
}
add_action( 'woocommerce_payment_complete', 'add_cert_balance_field' );
