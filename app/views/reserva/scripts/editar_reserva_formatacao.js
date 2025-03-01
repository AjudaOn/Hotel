/**
 * Script para formatação de campos na edição de reserva (CPF, telefone, etc.)
 */
document.addEventListener('DOMContentLoaded', function() {
    // Função para formatar CPF enquanto digita
    const cpfInput = document.getElementById('cpf');
    if (cpfInput) {
        cpfInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não for dígito
            
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            
            // Aplica a máscara conforme vai digitando
            if (value.length > 9) {
                value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
            } else if (value.length > 6) {
                value = value.replace(/^(\d{3})(\d{3})(\d{3}).*/, '$1.$2.$3');
            } else if (value.length > 3) {
                value = value.replace(/^(\d{3})(\d{3}).*/, '$1.$2');
            }
            
            e.target.value = value;
        });
    }
    
    // Função para formatar telefone de forma flexível
    const telefoneInput = document.querySelector('input[name="telefone"]');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            
            // Formato flexível para números brasileiros e paraguaios
            if (value.length > 0) {
                // Se começar com +, adiciona o código do país
                if (e.target.value.startsWith('+')) {
                    if (value.length <= 2) {
                        e.target.value = '+' + value;
                    } else if (value.length <= 5) {
                        e.target.value = '+' + value.substring(0, 2) + ' ' + value.substring(2);
                    } else if (value.length <= 10) {
                        e.target.value = '+' + value.substring(0, 2) + ' ' + value.substring(2, 5) + '-' + value.substring(5);
                    } else {
                        e.target.value = '+' + value.substring(0, 2) + ' ' + value.substring(2, 5) + '-' + value.substring(5, 9) + '-' + value.substring(9);
                    }
                } 
                // Formato brasileiro padrão
                else if (value.length <= 11) {
                    if (value.length <= 2) {
                        // Apenas DDD
                    } else if (value.length <= 6) {
                        e.target.value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
                    } else if (value.length <= 10) {
                        e.target.value = '(' + value.substring(0, 2) + ') ' + value.substring(2, 6) + '-' + value.substring(6);
                    } else {
                        e.target.value = '(' + value.substring(0, 2) + ') ' + value.substring(2, 7) + '-' + value.substring(7);
                    }
                } 
                // Para números mais longos (internacionais)
                else {
                    // Mantém como está para números muito longos
                }
            }
        });
    }
});