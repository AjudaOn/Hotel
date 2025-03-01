document.addEventListener('DOMContentLoaded', function() {
    const qtdeHospedesSelect = document.getElementById('qtde_hospedes');
    const acompanhantesCard = document.getElementById('acompanhantes_card');
    const acompanhantesContainer = document.getElementById('acompanhantes_container');
    const acompanhantesTitle = document.getElementById('acompanhantes_title');
    // Adicionar evento ao botão Cancelar
    const btnCancelar = document.querySelector('button[type="button"].btn-secondary');
    if (btnCancelar) {
        btnCancelar.addEventListener('click', function() {
            window.location.href = '/Hotel/admin';
        });
    }
    
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
                            <input type="number" class="form-control no-spinner" name="idade_acompanhante_${i}" required>
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
    // Adicione esta função para criar os campos de acompanhantes sem as setinhas
    // Função para adicionar campos de acompanhantes
    function adicionarCamposAcompanhantes() {
        const container = document.getElementById('acompanhantes_container');
        const qtdeHospedes = parseInt(document.getElementById('qtde_hospedes').value);
        
        // Limpar container
        container.innerHTML = '';
        
        // Atualizar título
        const title = document.getElementById('acompanhantes_title');
        title.textContent = qtdeHospedes > 2 ? 'INFORMAÇÕES DOS ACOMPANHANTES' : 'INFORMAÇÃO DO ACOMPANHANTE';
        
        // Mostrar ou esconder o card de acompanhantes
        const card = document.getElementById('acompanhantes_card');
        card.style.display = qtdeHospedes > 1 ? 'block' : 'none';
        
        // Criar campos para cada acompanhante
        for (let i = 0; i < qtdeHospedes - 1; i++) {
            const acompanhanteDiv = document.createElement('div');
            acompanhanteDiv.className = 'mb-4 p-3 border rounded';
            
            const titleH5 = document.createElement('h5');
            titleH5.className = 'mb-3';
            titleH5.textContent = `Acompanhante ${i + 1}`;
            acompanhanteDiv.appendChild(titleH5);
            
            // Nome
            const rowNome = document.createElement('div');
            rowNome.className = 'row mb-3';
            
            const colNome = document.createElement('div');
            colNome.className = 'col-md-12';
            
            const labelNome = document.createElement('label');
            labelNome.className = 'form-label';
            labelNome.textContent = 'Nome Completo:';
            
            const inputNome = document.createElement('input');
            inputNome.type = 'text';
            inputNome.className = 'form-control';
            inputNome.name = `nome_acompanhante_${i}`;
            inputNome.required = true;
            
            colNome.appendChild(labelNome);
            colNome.appendChild(inputNome);
            rowNome.appendChild(colNome);
            acompanhanteDiv.appendChild(rowNome);
            
            // Idade e Sexo
            const rowIdadeSexo = document.createElement('div');
            rowIdadeSexo.className = 'row mb-3';
            
            // Idade - Aplicando a classe no-spinner para remover as setinhas
            const colIdade = document.createElement('div');
            colIdade.className = 'col-md-6';
            
            const labelIdade = document.createElement('label');
            labelIdade.className = 'form-label';
            labelIdade.textContent = 'Idade:';
            
            const inputIdade = document.createElement('input');
            inputIdade.type = 'number';
            inputIdade.className = 'form-control no-spinner'; // Adicionando a classe no-spinner
            inputIdade.name = `idade_acompanhante_${i}`;
            inputIdade.min = '0';
            
            colIdade.appendChild(labelIdade);
            colIdade.appendChild(inputIdade);
            rowIdadeSexo.appendChild(colIdade);
            
            // Sexo
            const colSexo = document.createElement('div');
            colSexo.className = 'col-md-6';
            
            const labelSexo = document.createElement('label');
            labelSexo.className = 'form-label';
            labelSexo.textContent = 'Sexo:';
            
            const selectSexo = document.createElement('select');
            selectSexo.className = 'form-select';
            selectSexo.name = `sexo_acompanhante_${i}`;
            
            const optionM = document.createElement('option');
            optionM.value = 'M';
            optionM.textContent = 'Masculino';
            
            const optionF = document.createElement('option');
            optionF.value = 'F';
            optionF.textContent = 'Feminino';
            
            selectSexo.appendChild(optionM);
            selectSexo.appendChild(optionF);
            
            colSexo.appendChild(labelSexo);
            colSexo.appendChild(selectSexo);
            rowIdadeSexo.appendChild(colSexo);
            
            acompanhanteDiv.appendChild(rowIdadeSexo);
            container.appendChild(acompanhanteDiv);
        }
    }
    
    // Adicionar evento de mudança para o select de quantidade de hóspedes
    document.addEventListener('DOMContentLoaded', function() {
        const qtdeHospedesSelect = document.getElementById('qtde_hospedes');
        if (qtdeHospedesSelect) {
            qtdeHospedesSelect.addEventListener('change', adicionarCamposAcompanhantes);
            // Inicializar
            adicionarCamposAcompanhantes();
        }
    });
    // Executar na inicialização
    atualizarAcompanhantes();
    
    // Adicionar evento de mudança
    qtdeHospedesSelect.addEventListener('change', atualizarAcompanhantes);
});