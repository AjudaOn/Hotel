document.addEventListener('DOMContentLoaded', function() {
    // Função para carregar dados em um select
    function loadSelectData(url, selectElement) {
        fetch(url)
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url; // Redireciona para o login se necessário
                    return;
                }
                if (!response.ok) {
                    throw new Error('Erro ao carregar dados');
                }
                return response.json();
            })
            .then(data => {
                if (data) { // Verifica se há dados antes de tentar processar
                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.nm_graduacao || item.nm_tipo || item.nm_status || 
                                           item.nm_vinculo || item.nm_motivo;
                        selectElement.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                // Não mostra o alert para evitar múltiplas mensagens
                console.log('Erro ao carregar dados:', error.message);
            });
    }

    // Carrega dados para cada select
    loadSelectData('/Codando/public/api/graduacoes', document.querySelector('select[name="graduacao_id"]'));
    loadSelectData('/Codando/public/api/tipos-hospede', document.querySelector('select[name="tipo_hospede_id"]'));
    loadSelectData('/Codando/public/api/status-hospede', document.querySelector('select[name="status_hospede_id"]'));
    loadSelectData('/Codando/public/api/motivos-viagem', document.querySelector('select[name="motivo_viagem_id"]'));

    // Função para carregar vínculos nos campos de acompanhantes
    function loadVinculosForAcompanhante(index) {
        loadSelectData(
            '/Codando/public/api/vinculos-familiares', 
            document.querySelector(`select[name="acompanhante_vinculo_${index}"]`)
        );
    }

    // Atualiza a função createAcompanhanteFields para carregar os vínculos
    window.createAcompanhanteFields = function(index) {
        const html = `...`; // seu HTML existente
        const container = document.createElement('div');
        container.innerHTML = html;
        loadVinculosForAcompanhante(index);
        return container.innerHTML;
    };
}); 