<?php
/**
 * Plugin Name: Sourei AutoNotify for Woocommerce
 * Plugin URI: https://www.sourei.com.br/
 * Description: Notificate users from abandoned cart, order placed...
 * Version: 1.0
 * Author: Sourei Technologies, Henrique Reis
 * Author URI: https://www.sourei.com.br/
 */

// Adiciona um item de menu para as configurações do WhatsApp
add_action( 'admin_menu', 'add_whatsapp_menu_item', 50 );
function add_whatsapp_menu_item() {
    add_submenu_page( 'woocommerce', __( 'WhatsApp', 'woocommerce' ), __( 'WhatsApp', 'woocommerce' ), 'manage_options', 'whatsapp-settings', 'display_whatsapp_settings_page' );
}

// Carrega o conteúdo das tabs
require_once 'tabs-contents.php';

// Carrega o layout da página de configurações do WhatsApp
require_once 'tabs-layout.php';

// Atualiza as configurações do plugin
add_action( 'admin_post_update_whatsapp_settings', 'update_whatsapp_settings' );
function update_whatsapp_settings() {
    if (isset($_POST['submit'])) {
        $settings = get_whatsapp_settings();
        foreach ($settings as $setting) {
            update_option($setting['id'], $_POST[$setting['id']]);
        }
        update_option('whatsapp_enable_plugin', isset($_POST['whatsapp_enable_plugin']) ? 'yes' : 'no');
    }
    wp_redirect(admin_url('admin.php?page=whatsapp-settings&updated=true'));
    exit;
}

add_action('woocommerce_order_status_changed', 'whatsapp_order_updated', 10, 3);
function whatsapp_order_updated($order_id, $old_status, $new_status) {
    include "variaveis-woo.php";
    $order = wc_get_order($order_id);
    $phone = $order->get_billing_phone();
    if (!$phone) {
        $phone = $order->get_shipping_phone();
    }
    if (!$phone) {
        $customer_id = $order->get_customer_id();
        $customer = new WC_Customer($customer_id);
        $phone = $customer->get_billing_phone();
    }
    
    $status_message_ids = array(
        'on-hold' => 1,
        'pending' => 2,
        'processing' => 3,
        'completed' => 4,
        'cancelled' => 5,
        'failed' => 6,
        'refunded' => 7
    );
    $message_id = isset($status_message_ids[$new_status]) ? $status_message_ids[$new_status] : null;
    if ($message_id) {
        $templates = json_decode(file_get_contents(dirname(__FILE__) . '/template.json'), true);
        $message = '';
        $is_active = false;
        foreach ($templates as $template) {
            if ($template['id'] == $message_id) {
                $message = $template['content'];
                $is_active = $template['status'] === 'Ativo';
                break;
            }
        }
        if ($is_active && $message) {
            $message = str_replace(
        array(
            '{order}',
            '{order_number}',
            '{url_pagamento}',
            '{billing_first_name}',
            '{billing_last_name}',
            '{billing_email}',
            '{billing_phone}',
            '{order_total}',
            '{billing_address_1}',
            '{billing_number}',
            '{billing_address_2}',
            '{billing_city}',
            '{billing_state}',
            '{billing_postcode}',
            '{billing_country}',
            '{info_pedido}',
            '{url_site}'
        ),
        array(
            $order,
            $order_number,
            $payment_url,
            $billing_first_name,
            $billing_last_name,
            $billing_email,
            $billing_phone,
            $order_total,
            $billing_address_1,
            $billing_number,
            $billing_address_2,
            $billing_city,
            $billing_state,
            $billing_postcode,
            $billing_country,
            $info_pedido,
            $home_site
        ),
    $message
    );
            send_whatsapp_message(get_option('whatsapp_api_key'), get_option('whatsapp_secret_key'), $phone, $message);
        }
    }
}

function send_whatsapp_message($api_key, $secret_key, $phone, $message) {
    $data = array(
        'api' => get_option('whatsapp_api_key'),
        'secret' => get_option('whatsapp_secret_key'),
        'phone' => $phone,
        'message' => $message
    );
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.sourei.com.br');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
    $error_message = curl_error($ch);
    // Logar ou exibir o erro
    } else {
    $response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // Verificar o codigo de resposta e lidar com ele adequadamente
    }
    curl_close($ch);
}