document.addEventListener('DOMContentLoaded', function() {
    const qtdeHospedesSelect = document.getElementById('qtde_hospedes');
    const acompanhantesCard = document.getElementById('acompanhantes_card');
    const acompanhantesTitle = document.getElementById('acompanhantes_title');
    const acompanhantesContainer = document.getElementById('acompanhantes_container');
    
    // Função para atualizar os campos de acompanhantes
    function atualizarCamposAcompanhantes() {
        const qtdeHospedes = parseInt(qtdeHospedesSelect.value);
        
        // Limpar campos existentes
        acompanhantesContainer.innerHTML = '';
        
        if (qtdeHospedes > 1) {
            // Mostrar o card de acompanhantes
            acompanhantesCard.style.display = 'block';
            
            // Atualizar o título
            acompanhantesTitle.textContent = qtdeHospedes > 2 ? 'INFORMAÇÕES DOS ACOMPANHANTES' : 'INFORMAÇÃO DO ACOMPANHANTE';
            
            // Adicionar campos para cada acompanhante
            for (let i = 0; i < qtdeHospedes - 1; i++) {
                adicionarCamposAcompanhante(i);
            }
        } else {
            // Esconder o card de acompanhantes
            acompanhantesCard.style.display = 'none';
        }
    }
    
    // Função para adicionar campos de acompanhante
    function adicionarCamposAcompanhante(index) {
        // Buscar dados do banco via AJAX
        fetch('/Hotel/app/controllers/reserva/get_vinculos_sexos.php')
            .then(response => response.json())
            .then(data => {
                // Gerar opções para sexo
                let sexoOptions = '<option value="">Selecione</option>';
                data.sexos.forEach(sexo => {
                    sexoOptions += `<option value="${sexo.id}">${sexo.descricao}</option>`;
                });
                
                // Gerar opções para vínculo familiar
                let vinculoOptions = '<option value="">Selecione</option>';
                data.vinculos.forEach(vinculo => {
                    vinculoOptions += `<option value="${vinculo.id}">${vinculo.nm_vinculo}</option>`;
                });
                
                // Criar HTML para os campos do acompanhante
                let html = `
                    <div class="row acompanhante-row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nome do Acompanhante ${index + 1}:</label>
                            <input type="text" class="form-control" name="nome_acompanhante_${index}" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Idade:</label>
                            <input type="number" class="form-control" name="idade_acompanhante_${index}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Sexo:</label>
                            <select class="form-select" name="sexo_acompanhante_${index}" required>
                                ${sexoOptions}
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Vínculo Familiar:</label>
                            <select class="form-select" name="vinculo_familiar_${index}" required>
                                ${vinculoOptions}
                            </select>
                        </div>
                    </div>
                `;
                
                // Adicionar HTML ao container
                acompanhantesContainer.insertAdjacentHTML('beforeend', html);
            })
            .catch(error => {
                console.error('Erro ao buscar dados:', error);
                // Fallback para opções estáticas em caso de erro
                let html = `
                    <div class="row acompanhante-row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Nome do Acompanhante ${index + 1}:</label>
                            <input type="text" class="form-control" name="nome_acompanhante_${index}" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Idade:</label>
                            <input type="number" class="form-control" name="idade_acompanhante_${index}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Sexo:</label>
                            <select class="form-select" name="sexo_acompanhante_${index}" required>
                                <option value="">Selecione</option>
                                <option value="1">Masculino</option>
                                <option value="2">Feminino</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Vínculo Familiar:</label>
                            <select class="form-select" name="vinculo_familiar_${index}" required>
                                <option value="">Selecione</option>
                                <option value="1">Cônjuge</option>
                                <option value="2">Filho(a)</option>
                                <option value="3">Pai/Mãe</option>
                                <option value="4">Irmão/Irmã</option>
                                <option value="5">Amigo(a)</option>
                            </select>
                        </div>
                    </div>
                `;
                acompanhantesContainer.insertAdjacentHTML('beforeend', html);
            });
    }
    
    // Adicionar evento de mudança ao select de quantidade de hóspedes
    qtdeHospedesSelect.addEventListener('change', atualizarCamposAcompanhantes);
    
    // Inicializar campos de acompanhantes
    atualizarCamposAcompanhantes();
});