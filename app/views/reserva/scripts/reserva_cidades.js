document.addEventListener('DOMContentLoaded', function() {
    const ufSelect = document.getElementById('uf');
    const cidadeSelect = document.getElementById('cidade_origem');
    
    if (ufSelect && cidadeSelect) {
        cidadeSelect.disabled = true;
        
        ufSelect.addEventListener('change', function() {
            const ufId = this.value;
            console.log('UF selecionada:', ufId);
            
            if (ufId) {
                cidadeSelect.disabled = true;
                cidadeSelect.innerHTML = '<option value="">Carregando cidades...</option>';
                
                const url = `/Hotel/app/controllers/reserva/buscar_cidades.php?uf_id=${ufId}`;
                console.log('URL da requisição:', url);
                
                const xhr = new XMLHttpRequest();
                xhr.open('GET', url, true);
                
                xhr.onload = function() {
                    console.log('Status da resposta:', xhr.status);
                    console.log('Resposta do servidor:', xhr.responseText);
                    
                    if (xhr.status === 200) {
                        try {
                            const data = JSON.parse(xhr.responseText);
                            console.log('Dados processados:', data);
                            
                            cidadeSelect.innerHTML = '<option value="">Selecione uma cidade</option>';
                            
                            if (data && data.length > 0) {
                                data.forEach(function(cidade) {
                                    const option = document.createElement('option');
                                    option.value = cidade.id;
                                    option.textContent = cidade.nome;
                                    cidadeSelect.appendChild(option);
                                });
                                cidadeSelect.disabled = false;
                            } else {
                                cidadeSelect.innerHTML = '<option value="">Nenhuma cidade encontrada</option>';
                                cidadeSelect.disabled = false;
                            }
                        } catch (e) {
                            console.error('Erro ao processar JSON:', e);
                            console.error('Resposta recebida:', xhr.responseText);
                            cidadeSelect.innerHTML = '<option value="">Erro ao processar dados</option>';
                            cidadeSelect.disabled = false;
                        }
                    } else {
                        console.error('Erro na requisição:', xhr.status);
                        cidadeSelect.innerHTML = '<option value="">Erro ao carregar cidades</option>';
                        cidadeSelect.disabled = false;
                    }
                };
                
                xhr.onerror = function(e) {
                    console.error('Erro de conexão:', e);
                    cidadeSelect.innerHTML = '<option value="">Erro de conexão</option>';
                    cidadeSelect.disabled = false;
                };
                
                xhr.send();
            } else {
                cidadeSelect.innerHTML = '<option value="">Selecione uma UF primeiro</option>';
                cidadeSelect.disabled = true;
            }
        });
        
        if (ufSelect.value) {
            const event = new Event('change');
            ufSelect.dispatchEvent(event);
        }
    }
});