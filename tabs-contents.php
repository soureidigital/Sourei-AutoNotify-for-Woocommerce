<?php
// Define as configurações da guia de WhatsApp
function get_whatsapp_settings() {
    $settings = array(
        'section_title' => array(
            'name'     => __( 'WhatsApp Settings', 'woocommerce' ),
            'type'     => 'title',
            'desc'     => '',
            'id'       => 'whatsapp_section_title'
        ),
        'enable_plugin' => array(
            'name' => __( 'Enable WhatsApp Notifications', 'woocommerce' ),
            'type' => 'checkbox',
            'desc' => __( 'Enable or disable WhatsApp notifications', 'woocommerce' ),
            'id'   => 'whatsapp_enable_plugin',
            'default' => 'yes'
        ),
        'api_key' => array(
            'name' => __( 'API Key', 'woocommerce' ),
            'type' => 'text',
            'desc' => __( 'Enter your WhatsApp API Key', 'woocommerce' ),
            'id'   => 'whatsapp_api_key'
        ),
        'secret_key' => array(
            'name' => __( 'Secret Key', 'woocommerce' ),
            'type' => 'text',
            'desc' => __( 'Enter your WhatsApp Secret Key', 'woocommerce' ),
            'id'   => 'whatsapp_secret_key'
        ),
        'admin_phone_numbers' => array(
            'name' => __( 'Admin Phone Numbers', 'woocommerce' ),
            'type' => 'text',
            'desc' => __( 'Enter the phone numbers of the admins, separated by commas', 'woocommerce' ),
            'id'   => 'whatsapp_admin_phone_numbers'
        ),
                'section_end' => array(
            'type' => 'sectionend',
            'id' => 'whatsapp_section_end'
        )
    );
            return $settings;
}

add_action('wp_ajax_save_template_edits', 'save_template_edits');
add_action('wp_ajax_reset_template_to_default', 'reset_template_to_default');
function display_whatsapp_templates_tab() {
    $templates = json_decode(file_get_contents(dirname(__FILE__) . '/template.json'), true);
    $default_templates = json_decode(file_get_contents(dirname(__FILE__) . '/template_default.json'), true);
    ?>
<table class="wp-list-table widefat striped">
    <thead>
        <tr>
            <th>ID</th>
            <th><?php _e('Tipo da Mensagem', 'woocommerce'); ?></th>
            <th><?php _e('Conteúdo', 'woocommerce'); ?></th>
            <th><?php _e('Status', 'woocommerce'); ?></th>
            <th><?php _e('Ações', 'woocommerce'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($templates as $template) : ?>
            <tr>
                <td><?php echo esc_html($template['id']); ?></td>
                <td><?php echo esc_html($template['type']); ?></td>
                <td><?php echo esc_html($template['content']); ?></td>
                <td><?php echo esc_html($template['status']); ?></td>
                <td>
                    <button class="edit-template" data-id="<?php echo esc_attr($template['id']); ?>"><?php _e('Editar', 'woocommerce'); ?></button>
                    <button class="reset-template" data-id="<?php echo esc_attr($template['id']); ?>"><?php _e('Restaurar padrão', 'woocommerce'); ?></button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    <?php include 'template_popup.php'; ?>
    <?php include 'template_scripts.php'; ?>
    <?php
}

function save_template_edits() {
    $template_id = $_POST['template_id'];
    $template_content = stripslashes($_POST['template_content']);
    $template_status = $_POST['template_status'];

    $templates_file = dirname(__FILE__) . '/template.json';

    // Verifica se o arquivo template.json existe e é legível
    if (!file_exists($templates_file) || !is_readable($templates_file)) {
        wp_send_json_error('O arquivo template.json não existe ou não pode ser lido.');
        return;
    }

    $templates = json_decode(file_get_contents($templates_file), true);

    // Verifica se o conteúdo de template.json é um array válido
    if (!is_array($templates)) {
        wp_send_json_error('O conteúdo do arquivo template.json é inválido.');
        return;
    }

    $template_found = false;
    foreach ($templates as &$template) {
        if ($template['id'] == $template_id) {
            $template['content'] = $template_content;
            $template['status'] = $template_status;
            $template_found = true;
            break;
        }
    }

    // Verifica se o template foi encontrado
    if (!$template_found) {
        wp_send_json_error('O template não foi encontrado.');
        return;
    }

    // Salva as alterações no arquivo template.json
    if (file_put_contents($templates_file, json_encode($templates)) === false) {
        wp_send_json_error('Erro ao salvar as informações editadas.');
    } else {
        wp_send_json_success();
    }
}

function reset_template_to_default() {
    $template_id = $_POST['template_id'];

    $templates_file = dirname(__FILE__) . '/template.json';
    $default_templates_file = dirname(__FILE__) . '/template_default.json';

    // Verifica se os arquivos existem e são legíveis
    if (!file_exists($templates_file) || !is_readable($templates_file) || !file_exists($default_templates_file) || !is_readable($default_templates_file)) {
        wp_send_json_error('Um dos arquivos (template.json ou template_default.json) não existe ou não pode ser lido.');
        return;
    }

    $templates = json_decode(file_get_contents($templates_file), true);
    $default_templates = json_decode(file_get_contents($default_templates_file), true);

    // Verifica se o conteúdo de ambos os arquivos são arrays válidos
    if (!is_array($templates) || !is_array($default_templates)) {
        wp_send_json_error('O conteúdo de um dos arquivos (template.json ou template_default.json) é inválido.');
        return;
    }

    $template_found = false;
    foreach ($templates as &$template) {
    if ($template['id'] == $template_id) {
        foreach ($default_templates as $default_template) {
            if ($default_template['id'] == $template_id) {
                $template['content'] = $default_template['content'];
                $template_found = true;
                break 2;
            }
        }
    }
}

    // Salva as alterações no arquivo template.json
    if (!$template_found || file_put_contents($templates_file, json_encode($templates)) === false) {
        wp_send_json_error('Erro ao salvar o template restaurado.');
    } else {
        wp_send_json_success(array(
            'content' => $template['content'],
        ));
    }
}





// Exibe o conteúdo API da Sourei em formato de iframe
// Exibe o conteúdo da guia de QR Code nas configurações do WooCommerce
function display_whatsapp_qr_code_tab() {
$api_key = get_option( 'whatsapp_api_key' );
$secret_key = get_option( 'whatsapp_secret_key' );
echo '<iframe src="https://apiw.sourei.com.br/instance/?api=' . $api_key . '&amp;secret=' . $secret_key . '" style="border:none;" width="100%" height="600"></iframe>';
}
// Exibe o conteúdo da guia de Relatorio nas configurações do WooCommerce
function display_whatsapp_relatory_tab() {
$api_key = get_option( 'whatsapp_api_key' );
$secret_key = get_option( 'whatsapp_secret_key' );
echo '<iframe src="https://apiw.sourei.com.br/relatory/?api=' . $api_key . '&amp;secret=' . $secret_key . '" style="border:none;" width="100%" height="600"></iframe>';
}
// Exibe o conteúdo da guia de Envio Manual nas configurações do WooCommerce
function display_whatsapp_envio_manual_tab() {
$api_key = get_option( 'whatsapp_api_key' );
$secret_key = get_option( 'whatsapp_secret_key' );
echo '<iframe src="https://apiw.sourei.com.br/message/?api=' . $api_key . '&amp;secret=' . $secret_key . '" style="border:none;" width="100%" height="600"></iframe>';
}
// Exibe o conteúdo da guia de Expediente nas configurações do WooCommerce
function display_whatsapp_expediente_tab() {
$api_key = get_option( 'whatsapp_api_key' );
$secret_key = get_option( 'whatsapp_secret_key' );
echo '<iframe src="https://apiw.sourei.com.br/WorkSchedule/?api=' . $api_key . '&amp;secret=' . $secret_key . '" style="border:none;" width="100%" height="600"></iframe>';
}