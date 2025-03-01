document.addEventListener('DOMContentLoaded', function() {
    const ufSelect = document.getElementById('uf');
    const cidadeSelect = document.getElementById('cidade_origem');
    
    if (!ufSelect || !cidadeSelect) return;
    
    ufSelect.addEventListener('change', function() {
        const ufId = this.value;
        
        // Limpar e desabilitar o select de cidades
        cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
        cidadeSelect.disabled = true;
        
        if (ufId === '') {
            cidadeSelect.innerHTML = '<option value="">Selecione uma UF primeiro</option>';
            return;
        }
        
        // Fazer requisição AJAX para buscar as cidades
        fetch(`/Hotel/admin/get-cidades-by-uf/${ufId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na resposta do servidor');
                }
                return response.json();
            })
            .then(data => {
                cidadeSelect.innerHTML = '<option value="">Selecione uma cidade</option>';
                
                if (data && data.length > 0) {
                    data.forEach(cidade => {
                        const option = document.createElement('option');
                        option.value = cidade.id;
                        option.textContent = cidade.nome;
                        cidadeSelect.appendChild(option);
                    });
                } else {
                    cidadeSelect.innerHTML = '<option value="">Nenhuma cidade encontrada</option>';
                }
                
                cidadeSelect.disabled = false;
            })
            .catch(error => {
                console.error('Erro ao carregar cidades:', error);
                cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
            });
    });
});