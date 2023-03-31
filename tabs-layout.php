<?php
function display_whatsapp_settings_page() {
    ?>
    <script>
    jQuery(document).ready(function($) {
      $('.nav-tab-wrapper a').click(function(e) {
        e.preventDefault();
        $('.tab-content').hide();
        $($(this).attr('href')).show();
        $('.nav-tab-active').removeClass('nav-tab-active');
        $(this).addClass('nav-tab-active');
      });
    });
    </script>
    
<div class="wrap">
        <h1><?php _e( 'WhatsApp for Woocommerce by Sourei Technologies', 'woocommerce' ); ?></h1>
        <h2 class="nav-tab-wrapper">
        <a href="#whatsapp-settings" class="nav-tab nav-tab-active">
            <?php _e( 'Settings', 'woocommerce' ); ?>
        </a>
        <a href="#whatsapp-templates" class="nav-tab">
            <?php _e( 'Templates', 'woocommerce' ); ?>
        </a>    
        <a href="#whatsapp-qr-code" class="nav-tab">
            <?php _e( 'QR Code', 'woocommerce' ); ?>
        </a>
        <a href="#whatsapp-relatorio" class="nav-tab">
            <?php _e( 'Relatorio', 'woocommerce' ); ?>
        </a>
        <a href="#whatsapp-envio-manual" class="nav-tab">
            <?php _e( 'Envio Manual', 'woocommerce' ); ?>
        </a>
        <a href="#whatsapp-expediente" class="nav-tab">
            <?php _e( 'Expediente', 'woocommerce' ); ?>
        </a>
    </h2>
    
    <div id="whatsapp-settings" class="tab-content">
        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="update_whatsapp_settings">
            <?php woocommerce_admin_fields( get_whatsapp_settings() ); ?>
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Save changes', 'woocommerce'); ?>">
            </p>
        </form>
    </div>
    
    <div id="whatsapp-templates" class="tab-content" style="display: none;">
    <?php display_whatsapp_templates_tab(); ?>
    </div>
    
    <div id="whatsapp-qr-code" class="tab-content" style="display: none;">
        <?php display_whatsapp_qr_code_tab(); ?>
    </div>
    
    <div id="whatsapp-relatorio" class="tab-content" style="display: none;">
        <?php display_whatsapp_relatory_tab(); ?>
    </div>
    
    <div id="whatsapp-envio-manual" class="tab-content" style="display: none;">
        <?php display_whatsapp_envio_manual_tab(); ?>
    </div>
    
    <div id="whatsapp-expediente" class="tab-content" style="display: none;">
        <?php display_whatsapp_expediente_tab(); ?>
    </div>
    
<?php
}