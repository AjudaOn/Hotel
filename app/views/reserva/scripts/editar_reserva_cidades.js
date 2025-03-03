document.addEventListener('DOMContentLoaded', function() {
    // Elementos do DOM
    const ufSelect = document.getElementById('uf');
    const cidadeSelect = document.getElementById('cidade_origem');
    const ufIdSalvo = document.getElementById('uf_id_salvo');
    const municipioIdSalvo = document.getElementById('municipio_id_salvo');

    // Função para carregar cidades baseada na UF selecionada
    function carregarCidades(ufId) {
        // Desabilitar o select de cidades enquanto carrega
        cidadeSelect.disabled = true;
        cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
        
        if (!ufId) {
            cidadeSelect.innerHTML = '<option value="">Selecione uma UF primeiro</option>';
            return;
        }
        
        // Fazer requisição AJAX para buscar as cidades
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `/Hotel/app/controllers/reserva/buscar_cidades.php?uf_id=${ufId}`, true);
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    
                    // Limpar o select
                    cidadeSelect.innerHTML = '';
                    
                    // Adicionar opção padrão
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Selecione uma cidade';
                    cidadeSelect.appendChild(defaultOption);
                    
                    // Adicionar as cidades
                    if (data && data.length > 0) {
                        data.forEach(cidade => {
                            const option = document.createElement('option');
                            option.value = cidade.id;
                            option.textContent = cidade.nome;
                            cidadeSelect.appendChild(option);
                        });
                        
                        // Habilitar o select
                        cidadeSelect.disabled = false;
                        
                        // Se tiver município salvo, selecionar
                        if (municipioIdSalvo && municipioIdSalvo.value) {
                            cidadeSelect.value = municipioIdSalvo.value;
                        }
                    } else {
                        cidadeSelect.innerHTML = '<option value="">Nenhuma cidade encontrada</option>';
                    }
                } catch (e) {
                    cidadeSelect.innerHTML = '<option value="">Erro ao processar dados</option>';
                }
            } else {
                cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
            }
        };
        
        xhr.onerror = function() {
            cidadeSelect.innerHTML = '<option value="">Erro de conexão</option>';
        };
        
        xhr.send();
    }

    // Evento de mudança na UF
    ufSelect.addEventListener('change', function() {
        carregarCidades(this.value);
    });

    // Inicialização - se tiver UF salva
    if (ufIdSalvo && ufIdSalvo.value) {
        // Definir o valor da UF
        ufSelect.value = ufIdSalvo.value;
        
        // Carregar as cidades para a UF selecionada
        carregarCidades(ufIdSalvo.value);
    }
});