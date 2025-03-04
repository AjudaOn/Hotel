document.addEventListener('DOMContentLoaded', function() {
    const ufSelect = document.getElementById('uf');
    const cidadeSelect = document.getElementById('cidade_origem');
    
    // Pegar os dados da cidade já selecionada
    const cidadeId = document.getElementById('municipio_id_salvo')?.value;
    const cidadeNome = document.getElementById('municipio_id_salvo')?.dataset.nome;
    
    console.log("Cidade salva:", cidadeId, cidadeNome);
    
    // Função para substituir diretamente o texto "Selecione uma cidade"
    function corrigirTextoSelecione() {
        // Verificar se o select tem apenas uma opção com "Selecione uma cidade"
        if (cidadeSelect.options.length === 1 && 
            cidadeSelect.options[0].text === "Tirar3 Selecione uma cidade" && 
            cidadeId && cidadeNome) {
            
            // Substituir diretamente o texto e valor
            cidadeSelect.options[0].text = cidadeNome;
            cidadeSelect.options[0].value = cidadeId;
        }
        
        // Verificar todas as opções e substituir qualquer uma com "Selecione uma cidade"
        for (let i = 0; i < cidadeSelect.options.length; i++) {
            if (cidadeSelect.options[i].text === "Tirar3 Selecione uma cidade" && cidadeId && cidadeNome) {
                cidadeSelect.options[i].text = cidadeNome;
                cidadeSelect.options[i].value = cidadeId;
            }
        }
    }
    
    // Executar a correção imediatamente
    corrigirTextoSelecione();
    
    // Executar novamente após um pequeno atraso para garantir
    setTimeout(corrigirTextoSelecione, 100);
    
    if (ufSelect && cidadeSelect) {
        // Remover qualquer opção "Selecione uma cidade" que possa existir
        for (let i = 0; i < cidadeSelect.options.length; i++) {
            if (cidadeSelect.options[i].text === "Tirar3 Selecione uma cidade") {
                cidadeSelect.remove(i);
                break;
            }
        }
        
        // Forçar a cidade inicial se tivermos os dados
        if (cidadeId && cidadeNome) {
            // Verificar se já existe uma opção com o mesmo valor
            let cidadeJaExiste = false;
            for (let i = 0; i < cidadeSelect.options.length; i++) {
                if (cidadeSelect.options[i].value === cidadeId) {
                    cidadeSelect.options[i].selected = true;
                    cidadeJaExiste = true;
                    break;
                }
            }
            
            // Se não existir, criar a opção
            if (!cidadeJaExiste) {
                // Manter apenas a opção da cidade atual
                cidadeSelect.innerHTML = '';
                const option = document.createElement('option');
                option.value = cidadeId;
                option.textContent = cidadeNome;
                option.selected = true;
                cidadeSelect.appendChild(option);
            }
        }
        
        ufSelect.addEventListener('change', function() {
            const ufId = this.value;
            
            if (ufId) {
                cidadeSelect.disabled = true;
                cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
                
                const url = `/Hotel/app/controllers/reserva/atualizar_buscar_cidades.php?uf_id=${ufId}`;
                
                const xhr = new XMLHttpRequest();
                xhr.open('GET', url, true);
                
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        try {
                            const data = JSON.parse(xhr.responseText);
                            cidadeSelect.innerHTML = '';
                            
                            // Manter a cidade atual como primeira opção se for a mesma UF
                            if (cidadeId && cidadeNome && ufId === document.getElementById('uf_id_salvo').value) {
                                const cidadeAtual = document.createElement('option');
                                cidadeAtual.value = cidadeId;
                                cidadeAtual.textContent = cidadeNome;
                                cidadeAtual.selected = true;
                                cidadeSelect.appendChild(cidadeAtual);
                            }
                            
                            // Adicionar as outras cidades
                            if (data && data.length > 0) {
                                data.forEach(function(cidade) {
                                    // Verificar se a cidade já existe no select
                                    let cidadeJaExiste = false;
                                    for (let i = 0; i < cidadeSelect.options.length; i++) {
                                        if (cidadeSelect.options[i].value === cidade.id_municipio) {
                                            cidadeJaExiste = true;
                                            break;
                                        }
                                    }
                                    
                                    if (!cidadeJaExiste) {
                                        const option = document.createElement('option');
                                        option.value = cidade.id_municipio;
                                        option.textContent = cidade.nome || cidade.id_municipio_nome;
                                        cidadeSelect.appendChild(option);
                                    }
                                });
                            } else if (cidadeSelect.options.length === 0) {
                                // Só adicionar esta opção se não houver nenhuma outra
                                const option = document.createElement('option');
                                option.value = "";
                                option.textContent = "Nenhuma cidade encontrada";
                                cidadeSelect.appendChild(option);
                            }
                            
                            cidadeSelect.disabled = false;
                        } catch (e) {
                            console.error('Erro ao processar dados:', e);
                            cidadeSelect.disabled = false;
                        }
                    }
                };
                
                xhr.send();
            } else {
                cidadeSelect.innerHTML = '<option value="">Selecione uma UF primeiro</option>';
                cidadeSelect.disabled = true;
            }
        });
    }
    
    // Observar mudanças no select de cidades
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                // Quando o conteúdo do select mudar, corrigir o texto
                corrigirTextoSelecione();
            }
        });
    });
    
    // Configurar o observador
    observer.observe(cidadeSelect, { childList: true });
});