<script>
jQuery(document).ready(function($) {
    $('.edit-template').click(function() {
        var template_id = $(this).data('id');
        var template_content = $('button[data-id="' + template_id + '"]').closest('tr').find('td:eq(2)').html();
        var template_status = $('button[data-id="' + template_id + '"]').closest('tr').find('td:eq(3)').html();
        var template_type = $('button[data-id="' + template_id + '"]').closest('tr').find('td:eq(1)').html();
        $('#edit-template-popup').find('h2').text('Mensagem: #' + template_id + ' - ' + template_type);
        $('#edit-template-popup').find('textarea[name="template-content"]').val(template_content);
        $('#edit-template-popup').find('select[name="template-status"]').val(template_status);
        $('#edit-template-popup').hide().slideDown(500);
        $('#edit-template-popup-overlay').show();
    });

    $('.cancel-template').click(function() {
        $(this).closest('.edit-template-popup').hide();
        $('#edit-template-popup-overlay').hide();
    });

        $('.reset-template').click(function() {
        var template_id = $(this).data('id');
        $.post(ajaxurl, {
            action: 'reset_template_to_default',
            template_id: template_id,
        }, function(response) {
            if (response.success) {
                $('button[data-id="' + template_id + '"]').closest('tr').find('td:eq(2)').html(response.data.content);
                // Remove a linha abaixo para não atualizar o status ao resetar o template
                // $('button[data-id="' + template_id + '"]').closest('tr').find('td:eq(3)').html(response.data.status);
            } else {
                console.error('Erro ao resetar o template para o padrão');
            }
        });
    });

    $('.edit-template-form').submit(function(event) {
        event.preventDefault();
        var template_id = $(this).find('h2').text().split('#')[1].split('-')[0].trim();
        var template_content = $(this).find('textarea[name="template-content"]').val();
        var template_status = $(this).find('select[name="template-status"]').val();

        // Adicione a função AJAX para salvar as edições do template aqui
        $.post(ajaxurl, {
            action: 'save_template_edits',
            template_id: template_id,
            template_content: template_content,
            template_status: template_status
        }, function(response) {
            if (response.success) {
                $('button[data-id="' + template_id + '"]').closest('tr').find('td:eq(2)').html(template_content);
                $('button[data-id="' + template_id + '"]').closest('tr').find('td:eq(3)').html(template_status);
            } else {
                console.error('Erro ao salvar as edições do template');
            }
        });

        $(this).closest('.edit-template-popup').hide();
        $('#edit-template-popup-overlay').hide();
        return false;
    });
});
</script>

<style>
    .edit-template-popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: white;
        padding: 20px;
        border: 1px solid #ccc;
        z-index: 1000;
    }
    .full-width {
        width: 100%;
    }
    .edit-template-popup-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 999;
}
</style>