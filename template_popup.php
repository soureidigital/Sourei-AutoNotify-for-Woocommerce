<div class="edit-template-popup" id="edit-template-popup" style="display: none;">
    <form class="edit-template-form">
        <h2>Mensagem: #id - Tipo da Mensagem</h2>
        <label for="template-content">Mensagem</label>
        <textarea name="template-content" id="template-content" class="full-width"></textarea>
        <hr>
        <h4>Variáveis Disponíveis</h4>
        <p>
{order} - Número do pedido<br>
{order_number} - Número do pedido<br>
{url_pagamento} - URL para pagamento do pedido<br>
{billing_first_name} - Primeiro nome do cliente<br>
{billing_last_name} - Último nome do cliente<br>
{billing_email} - E-mail do cliente<br>
{billing_phone} - Telefone do cliente<br>
{order_total} - Valor total do pedido<br>
{billing_address_1} - Endereço do cliente (linha 1)<br>
{billing_number} - Número do endereço do cliente<br>
{billing_address_2} - Endereço do cliente (linha 2)<br>
{billing_city} - Cidade do cliente<br>
{billing_state} - Estado do cliente<br>
{billing_postcode} - CEP do cliente<br>
{billing_country} - País do cliente<br>
{info_pedido} - Informações adicionais do pedido<br>
{url_site} - URL do site<br>
</p>
        <hr>
        <label for="template-status">Status</label>
        <select name="template-status" id="template-status" class="full-width">
            <option value="Ativo"><?php _e( 'Ativo', 'woocommerce' ); ?></option>
            <option value="Inativo"><?php _e( 'Inativo', 'woocommerce' ); ?></option>
        </select>
        <p>* Se desativado, esta mensagem não será enviada</p>
        <hr>
        <button type="submit" class="button save-template"><?php _e( 'Save', 'woocommerce' ); ?></button>
        <button type="button" class="button cancel-template"><?php _e( 'Cancel', 'woocommerce' ); ?></button>
    </form>
</div>
<div class="edit-template-popup-overlay" id="edit-template-popup-overlay" style="display: none;"></div>