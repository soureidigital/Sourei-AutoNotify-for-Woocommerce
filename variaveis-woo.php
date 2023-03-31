<?php
 // Variáveis
    $order = wc_get_order( $order_id );
    $order_number = $order->get_order_number();
    $payment_url = $order->get_checkout_payment_url();
    $billing_first_name = get_post_meta( $order_id, '_billing_first_name', true );
    $billing_last_name = get_post_meta( $order_id, '_billing_last_name', true );
    $billing_email = get_post_meta( $order_id, '_billing_email', true );
    $billing_phone = get_post_meta( $order_id, '_billing_phone', true );
    $order_total = number_format(get_post_meta( $order_id, '_order_total', true ), 2, ",", ".");
    $billing_address_1 = get_post_meta( $order_id, '_billing_address_1', true );
    $billing_number = get_post_meta( $order_id, '_billing_number', true );
    $billing_address_2 = get_post_meta( $order_id, '_billing_address_2', true );
    $billing_city = get_post_meta( $order_id, '_billing_city', true );
    $billing_state = get_post_meta( $order_id, '_billing_state', true );
    $billing_postcode = get_post_meta( $order_id, '_billing_postcode', true );
    $billing_country = get_post_meta( $order_id, '_billing_country', true );
    $info_pedido = $order->get_view_order_url();
    $home_site = home_url();
    