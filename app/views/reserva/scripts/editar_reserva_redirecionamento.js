/**
 * Script para gerenciar redirecionamentos após submissão do formulário de edição de reserva
 */
document.addEventListener('DOMContentLoaded', function() {
    const reservaForm = document.getElementById('reservaForm');
    if (reservaForm) {
        reservaForm.addEventListener('submit', function(e) {
            // Permitir que o formulário seja enviado normalmente
            // Mas garantir que após o salvamento, o redirecionamento seja feito corretamente
            localStorage.setItem('redirectAfterSave', '/Hotel/app/views/admin/tela_admin.php');
        });
    }
});