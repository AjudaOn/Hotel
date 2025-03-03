document.addEventListener('DOMContentLoaded', function() {
    // Elementos do DOM
    const ufSelect = document.getElementById('uf');
    const cidadeSelect = document.getElementById('cidade_origem');
    const ufIdSalvo = document.getElementById('uf_id_salvo');
    const municipioIdSalvo = document.getElementById('municipio_id_salvo');

    console.log("Valores iniciais:", {
        uf: ufSelect ? ufSelect.value : "não encontrado",
        ufIdSalvo: ufIdSalvo ? ufIdSalvo.value : "não encontrado",
        municipioIdSalvo: municipioIdSalvo ? municipioIdSalvo.value : "não encontrado"
    });

    // Função para carregar cidades baseada na UF selecionada
    function carregarCidades(ufId) {
        console.log("Carregando cidades para UF:", ufId);
        
        // Desabilitar o select de cidades enquanto carrega
        cidadeSelect.disabled = true;
        cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
        
        if (!ufId) {
            cidadeSelect.innerHTML = '<option value="">Selecione uma UF primeiro</option>';
            cidadeSelect.disabled = false;
            return;
        }
        
        // Fazer requisição AJAX para buscar as cidades - usando XMLHttpRequest síncrono
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `/Hotel/app/controllers/reserva/buscar_cidades.php?uf_id=${ufId}&_=${new Date().getTime()}`, false); // Requisição síncrona com cache buster
        
        try {
            xhr.send();
            
            if (xhr.status === 200) {
                try {
                    const data = JSON.parse(xhr.responseText);
                    console.log("Cidades recebidas:", data);
                    
                    // Limpar o select
                    cidadeSelect.innerHTML = '';
                    
                    // Adicionar opção padrão
                    const defaultOption = document.createElement('option');
                    defaultOption.value = '';
                    defaultOption.textContent = 'Selecione uma cidade';
                    cidadeSelect.appendChild(defaultOption);
                    
                    // Adicionar as cidades
                    if (data && data.length > 0) {
                        let municipioEncontrado = false;
                        
                        data.forEach(cidade => {
                            const option = document.createElement('option');
                            option.value = cidade.id_municipio;
                            option.textContent = cidade.id_municipio_nome;
                            
                            // Se for o município salvo, selecionar
                            if (municipioIdSalvo && municipioIdSalvo.value && 
                                cidade.id_municipio == municipioIdSalvo.value) {
                                option.selected = true;
                                municipioEncontrado = true;
                                console.log("Município encontrado e selecionado:", cidade.id_municipio_nome);
                            }
                            
                            cidadeSelect.appendChild(option);
                        });
                        
                        // Habilitar o select
                        cidadeSelect.disabled = false;
                        
                        if (!municipioEncontrado && municipioIdSalvo && municipioIdSalvo.value) {
                            console.warn("Município ID salvo não encontrado na lista:", municipioIdSalvo.value);
                        }
                    } else {
                        cidadeSelect.innerHTML = '<option value="">Nenhuma cidade encontrada</option>';
                        cidadeSelect.disabled = false;
                    }
                } catch (e) {
                    console.error("Erro ao processar dados:", e);
                    cidadeSelect.innerHTML = '<option value="">Erro ao processar dados</option>';
                    cidadeSelect.disabled = false;
                }
            } else {
                console.error("Erro na requisição:", xhr.status);
                cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
                cidadeSelect.disabled = false;
            }
        } catch (e) {
            console.error("Erro de conexão:", e);
            cidadeSelect.innerHTML = '<option value="">Erro de conexão</option>';
            cidadeSelect.disabled = false;
        }
    }

    // Evento de mudança na UF
    ufSelect.addEventListener('change', function() {
        carregarCidades(this.value);
    });

    // Executar imediatamente após o DOM estar pronto
    // Verificar se temos uma UF salva ou selecionada
    let ufId = null;
    
    if (ufIdSalvo && ufIdSalvo.value) {
        console.log("Usando UF do campo oculto:", ufIdSalvo.value);
        ufId = ufIdSalvo.value;
        // Definir o valor da UF no select
        if (ufSelect) ufSelect.value = ufId;
    } else if (ufSelect && ufSelect.value) {
        console.log("Usando UF do select:", ufSelect.value);
        ufId = ufSelect.value;
    }
    
    // Forçar carregamento imediato das cidades
    if (ufId) {
        console.log("Carregando cidades imediatamente para UF ID:", ufId);
        carregarCidades(ufId);
    } else {
        console.warn("Nenhuma UF encontrada para carregar cidades");
    }

    // Adicionar um evento que é disparado quando o DOM está completamente carregado
    window.addEventListener('load', function() {
        if (ufId) {
            console.log("Recarregando cidades após carregamento completo da página para UF ID:", ufId);
            carregarCidades(ufId);
        }
    });
});