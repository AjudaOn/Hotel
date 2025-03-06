document.addEventListener('DOMContentLoaded', function() {
    const ufSelect = document.getElementById('uf');
    const cidadeSelect = document.getElementById('cidade_origem');
    
    // Obter a cidade salva do banco de dados
    const cidadeId = document.getElementById('municipio_id_salvo')?.value;
    const cidadeNome = document.getElementById('municipio_id_salvo')?.getAttribute('data-nome');
    
    console.log('Cidade do banco:', cidadeId, cidadeNome);
    
    if (ufSelect && cidadeSelect) {
        // Inicialmente, mostra o nome da cidade do banco
        if (cidadeId && cidadeNome) {
            // Limpar o select e adicionar a cidade do banco como primeira opção
            cidadeSelect.innerHTML = `<option value="${cidadeId}" selected>${cidadeNome}</option>`;
        }
        
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
                            
                            // Limpar o select antes de adicionar novas opções
                            cidadeSelect.innerHTML = '';
                            
                            if (data && data.length > 0) {
                                let cidadeEncontrada = false;
                                
                                // Verificar se a cidade do banco está na lista de cidades da UF selecionada
                                if (cidadeId && cidadeNome) {
                                    for (let i = 0; i < data.length; i++) {
                                        if (data[i].id == cidadeId) {
                                            cidadeEncontrada = true;
                                            break;
                                        }
                                    }
                                    
                                    // Se a cidade não estiver na lista e a UF for a mesma do banco,
                                    // adicionar a cidade do banco como primeira opção
                                    if (!cidadeEncontrada && ufId == document.getElementById('uf_id_salvo').value) {
                                        const option = document.createElement('option');
                                        option.value = cidadeId;
                                        option.textContent = cidadeNome;
                                        option.selected = true;
                                        cidadeSelect.appendChild(option);
                                    }
                                }
                                
                                // Adicionar todas as cidades da lista
                                data.forEach(function(cidade) {
                                    const option = document.createElement('option');
                                    option.value = cidade.id;
                                    option.textContent = cidade.nome;
                                    
                                    // Se for a cidade que veio do banco, seleciona ela
                                    if (cidadeId && cidade.id == cidadeId) {
                                        option.selected = true;
                                    }
                                    
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
        
        // Disparar o evento change para carregar as cidades da UF selecionada
        if (ufSelect.value) {
            const event = new Event('change');
            ufSelect.dispatchEvent(event);
        }
    }
});