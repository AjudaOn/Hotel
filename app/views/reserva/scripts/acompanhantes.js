document.addEventListener('DOMContentLoaded', function() {
    const qtdeHospedesSelect = document.getElementById('qtde_hospedes');
    const acompanhantesCard = document.getElementById('acompanhantes_card');
    const acompanhantesContainer = document.getElementById('acompanhantes_container');
    const acompanhantesTitle = document.getElementById('acompanhantes_title');
    
    // Função para atualizar os campos de acompanhantes
    function atualizarAcompanhantes() {
        const qtdeHospedes = parseInt(qtdeHospedesSelect.value);
        const qtdeAcompanhantes = qtdeHospedes - 1;
        
        // Mostrar ou esconder o card de acompanhantes
        if (qtdeAcompanhantes > 0) {
            acompanhantesCard.style.display = 'block';
            
            // Atualizar o título do card
            if (qtdeAcompanhantes === 1) {
                acompanhantesTitle.textContent = 'INFORMAÇÃO DO ACOMPANHANTE';
            } else {
                acompanhantesTitle.textContent = 'INFORMAÇÕES DOS ACOMPANHANTES';
            }
            
            // Limpar o container de acompanhantes
            acompanhantesContainer.innerHTML = '';
            
            // Adicionar campos para cada acompanhante
            for (let i = 0; i < qtdeAcompanhantes; i++) {
                const acompanhanteHtml = `
                    <div class="row mb-3 border-bottom pb-3">
                        <div class="col-12 mb-2">
                            <h6>Acompanhante ${i + 1}</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nome Completo:</label>
                            <input type="text" class="form-control" name="nome_acompanhante_${i}" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Vínculo Familiar:</label>
                            <select class="form-select" name="vinculo_familiar_${i}" required>
                                <option value="">---------</option>
                                <option value="Cônjuge">Cônjuge</option>
                                <option value="Filho(a)">Filho(a)</option>
                                <option value="Pai/Mãe">Pai/Mãe</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>
                        <div class="col-md-1 mb-3">
                            <label class="form-label">Idade:</label>
                            <input type="number" class="form-control" name="idade_acompanhante_${i}" required>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Sexo:</label>
                            <select class="form-select" name="sexo_acompanhante_${i}" required>
                                <option value="">---------</option>
                                <option value="M">Masculino</option>
                                <option value="F">Feminino</option>
                            </select>
                        </div>
                    </div>
                `;
                acompanhantesContainer.innerHTML += acompanhanteHtml;
            }
        } else {
            acompanhantesCard.style.display = 'none';
        }
    }
    
    // Executar na inicialização
    atualizarAcompanhantes();
    
    // Adicionar evento de mudança
    qtdeHospedesSelect.addEventListener('change', atualizarAcompanhantes);
});