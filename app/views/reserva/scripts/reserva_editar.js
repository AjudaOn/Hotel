/**
 * Script para gerenciar a edição de reservas
 */
document.addEventListener('DOMContentLoaded', function() {
    // 1. Verificar quantidade de hóspedes e mostrar bloco de acompanhantes se necessário
    const qtdeHospedes = document.getElementById('qtde_hospedes');
    const acompanhantesCard = document.getElementById('acompanhantes_card');
    
    if (qtdeHospedes && parseInt(qtdeHospedes.value) > 1) {
        acompanhantesCard.style.display = 'block';
        // Atualizar título baseado na quantidade
        const acompanhantesTitle = document.getElementById('acompanhantes_title');
        if (acompanhantesTitle) {
            acompanhantesTitle.textContent = parseInt(qtdeHospedes.value) > 2 ? 
                'INFORMAÇÕES DOS ACOMPANHANTES' : 'INFORMAÇÃO DO ACOMPANHANTE';
        }
    }
    
    // 2. Verificar se tem pet e mostrar campo de quantidade
    const temPet = document.getElementById('tem_pet');
    const qtdePetField = document.getElementById('qtde_pet_field');
    
    if (temPet && temPet.value === 'Sim') {
        qtdePetField.style.display = 'block';
    }
    
    // 3. Carregar cidades da UF selecionada
    const ufSelect = document.getElementById('uf');
    const cidadeSelect = document.getElementById('cidade_origem');
    
    if (ufSelect && ufSelect.value) {
        // Habilitar o select de cidades
        if (cidadeSelect) {
            cidadeSelect.disabled = false;
        }
        
        // Carregar cidades da UF selecionada
        carregarCidadesPorUf(ufSelect.value);
    }
    
    // Adicionar event listeners
    if (qtdeHospedes) {
        qtdeHospedes.addEventListener('change', function() {
            gerenciarAcompanhantes(this.value);
        });
    }
    
    if (temPet) {
        temPet.addEventListener('change', function() {
            gerenciarCamposPet(this.value);
        });
    }
    
    if (ufSelect) {
        ufSelect.addEventListener('change', function() {
            carregarCidadesPorUf(this.value);
        });
    }
});

/**
 * Função para gerenciar a exibição do bloco de acompanhantes
 */
function gerenciarAcompanhantes(quantidade) {
    const acompanhantesCard = document.getElementById('acompanhantes_card');
    const acompanhantesTitle = document.getElementById('acompanhantes_title');
    const acompanhantesContainer = document.getElementById('acompanhantes_container');
    
    if (!acompanhantesCard || !acompanhantesContainer) return;
    
    quantidade = parseInt(quantidade);
    
    if (quantidade > 1) {
        // Mostrar o card de acompanhantes
        acompanhantesCard.style.display = 'block';
        
        // Atualizar o título
        if (acompanhantesTitle) {
            acompanhantesTitle.textContent = quantidade > 2 ? 
                'INFORMAÇÕES DOS ACOMPANHANTES' : 'INFORMAÇÃO DO ACOMPANHANTE';
        }
        
        // Gerar campos para acompanhantes
        gerarCamposAcompanhantes(quantidade - 1);
    } else {
        // Esconder o card de acompanhantes
        acompanhantesCard.style.display = 'none';
    }
}

/**
 * Função para gerenciar a exibição dos campos de pet
 */
function gerenciarCamposPet(temPet) {
    const qtdePetField = document.getElementById('qtde_pet_field');
    
    if (!qtdePetField) return;
    
    if (temPet === 'Sim') {
        qtdePetField.style.display = 'block';
    } else {
        qtdePetField.style.display = 'none';
        // Resetar o valor
        const qtdePet = document.getElementById('qtde_pet');
        if (qtdePet) {
            qtdePet.value = '0';
        }
    }
}

/**
 * Função para carregar cidades por UF
 */
function carregarCidadesPorUf(ufId) {
    const cidadeSelect = document.getElementById('cidade_origem');
    const municipioSalvo = document.getElementById('municipio_id_salvo');
    
    if (!cidadeSelect) return;
    
    // Habilitar o select de cidades
    cidadeSelect.disabled = false;
    
    // Limpar opções atuais
    cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
    
    // Fazer requisição AJAX para buscar cidades
    fetch(`/Hotel/admin/get-cidades-by-uf/${ufId}`)
        .then(response => response.json())
        .then(data => {
            cidadeSelect.innerHTML = '<option value="">Selecione uma cidade</option>';
            
            data.forEach(cidade => {
                const option = document.createElement('option');
                option.value = cidade.id;
                option.textContent = cidade.nome;
                
                // Selecionar a cidade salva
                if (municipioSalvo && municipioSalvo.value == cidade.id) {
                    option.selected = true;
                }
                
                cidadeSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Erro ao carregar cidades:', error);
            cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
        });
}

/**
 * Função para gerar campos de acompanhantes
 */
function gerarCamposAcompanhantes(quantidade) {
    const container = document.getElementById('acompanhantes_container');
    
    if (!container) return;
    
    // Limpar container
    container.innerHTML = '';
    
    // Gerar campos para cada acompanhante
    for (let i = 1; i <= quantidade; i++) {
        const acompanhanteHtml = `
            <div class="acompanhante-section mb-4">
                <h6 class="mb-3">Acompanhante ${i}</h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nome Completo:</label>
                        <input type="text" class="form-control" name="acompanhante_nome_${i}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">CPF:</label>
                        <input type="text" class="form-control cpf-mask" name="acompanhante_cpf_${i}" required>
                    </div>
                </div>
            </div>
        `;
        
        container.innerHTML += acompanhanteHtml;
    }
}