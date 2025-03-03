document.addEventListener('DOMContentLoaded', function() {
    const ufSelect = document.getElementById('uf');
    const cidadeSelect = document.getElementById('cidade_origem');
    
    // Função para carregar cidades
    function carregarCidades(ufId) {
        if (!ufId) return;
        
        cidadeSelect.disabled = true;
        cidadeSelect.innerHTML = '<option value="">Carregando...</option>';

        // Usar XMLHttpRequest síncrono para garantir que as cidades sejam carregadas antes de continuar
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `/Hotel/app/controllers/reserva/buscar_cidades.php?uf_id=${ufId}&nocache=${new Date().getTime()}`, false); // false para requisição síncrona
        
        try {
            xhr.send();
            
            if (xhr.status === 200) {
                const cidades = JSON.parse(xhr.responseText);
                cidadeSelect.innerHTML = '';
                
                const municipioIdSalvo = document.getElementById('municipio_id_salvo')?.value;
                let cidadeEncontrada = false;
                
                cidades.forEach(cidade => {
                    const option = document.createElement('option');
                    option.value = cidade.id_municipio;
                    option.textContent = cidade.id_municipio_nome;
                    
                    if (municipioIdSalvo && cidade.id_municipio == municipioIdSalvo) {
                        option.selected = true;
                        cidadeEncontrada = true;
                    }
                    
                    cidadeSelect.appendChild(option);
                });
                
                cidadeSelect.disabled = false;
                
                // Se não encontrou a cidade salva, selecionar a primeira
                if (!cidadeEncontrada && cidades.length > 0) {
                    cidadeSelect.selectedIndex = 0;
                }
            } else {
                console.error('Erro ao carregar cidades:', xhr.status);
                cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
                cidadeSelect.disabled = false;
            }
        } catch (error) {
            console.error('Erro ao processar cidades:', error);
            cidadeSelect.innerHTML = '<option value="">Erro ao processar cidades</option>';
            cidadeSelect.disabled = false;
        }
    }

    // Carregar cidades quando a UF mudar
    ufSelect.addEventListener('change', function() {
        carregarCidades(this.value);
    });

    // Executar imediatamente - não esperar por eventos
    console.log("Inicializando carregamento de cidades");
    
    // Verificar se há uma UF salva e carregar suas cidades
    const ufIdSalvo = document.getElementById('uf_id_salvo')?.value;
    
    if (ufIdSalvo) {
        console.log("UF salva encontrada:", ufIdSalvo);
        // Definir a UF no select
        ufSelect.value = ufIdSalvo;
        // Carregar as cidades imediatamente
        carregarCidades(ufIdSalvo);
    } else if (ufSelect.value) {
        console.log("Usando UF do select:", ufSelect.value);
        // Carregar as cidades com o valor atual do select
        carregarCidades(ufSelect.value);
    }
    
    // Adicionar um evento para quando a janela terminar de carregar
    window.addEventListener('load', function() {
        console.log("Janela carregada, verificando UF novamente");
        if (ufSelect.value) {
            carregarCidades(ufSelect.value);
        }
    });
});