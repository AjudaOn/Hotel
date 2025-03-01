document.addEventListener('DOMContentLoaded', function() {
    const ufSelect = document.getElementById('uf');
    const municipioSelect = document.getElementById('municipio');

    console.log('Script location.js carregado');

    // Função para mostrar erro
    function showError(message) {
        console.error('Erro:', message);
        alert('Erro: ' + message);
    }

    // Carrega as UFs quando a página carrega
    console.log('Iniciando busca de UFs');
    fetch('/Codando/public/api/ufs')
        .then(response => {
            console.log('Resposta UFs:', response);
            if (!response.ok) {
                throw new Error('Erro ao carregar UFs');
            }
            return response.json();
        })
        .then(data => {
            console.log('UFs recebidas:', data);
            ufSelect.innerHTML = '<option value="">Selecione o estado</option>';
            data.forEach(uf => {
                const option = document.createElement('option');
                option.value = uf.id;
                option.textContent = uf.sigla_uf;
                ufSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Erro ao carregar UFs:', error);
            showError(error.message);
        });

    // Carrega os municípios quando uma UF é selecionada
    ufSelect.addEventListener('change', function() {
        const ufId = this.value;
        console.log('UF selecionada:', ufId);
        
        municipioSelect.innerHTML = '<option value="">Selecione o município</option>';
        
        if (ufId) {
            console.log('Buscando municípios para UF:', ufId);
            fetch(`/Codando/public/api/municipios/${ufId}`)
                .then(response => {
                    console.log('Resposta municípios:', response);
                    if (!response.ok) {
                        throw new Error('Erro ao carregar municípios');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Municípios recebidos:', data);
                    data.forEach(municipio => {
                        const option = document.createElement('option');
                        option.value = municipio.id_municipio;
                        option.textContent = municipio.id_municipio_nome;
                        municipioSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Erro ao carregar municípios:', error);
                    showError(error.message);
                });
        }
    });
}); 