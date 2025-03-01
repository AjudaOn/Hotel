<div class="container-fluid p-0">
    <h1 class="h3 mb-3">REGISTRO DE RESERVA</h1>
    
    <!-- Ajustando a action do formulário para usar o caminho correto -->
    <form id="reservaForm" action="/Hotel/app/controllers/reserva/salvar_reserva.php" method="post">
        <!-- Campo oculto para a etapa do protocolo -->
        <input type="hidden" name="etapa_id" value="1">
        <input type="hidden" name="redirect_to" value="/Hotel/app/views/admin/tela_admin.php">
        
        <!-- Remover as mensagens duplicadas -->
        <?php if (isset($mensagem) && !empty($mensagem) && !strpos($_SERVER['REQUEST_URI'], 'sucesso')): ?>
            <div class="alert alert-<?php echo $tipoMensagem; ?> alert-dismissible fade show" role="alert">
                <?php echo $mensagem; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <!-- INFORMAÇÕES DA PRÉ-RESERVA -->
        <div class="card mb-4 shadow-sm">            
            <div class="card-body">
            <h5 class="card-title mb-0">INFORMAÇÕES DA PRÉ-RESERVA</h5>
                <div class="row">
                    
                    <div class="col-md-12 mb-3"></div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Entrada:</label>
                        <input type="date" class="form-control" name="data_entrada" id="data_entrada" required 
                               value="<?php echo isset($formData['data_entrada']) ? $formData['data_entrada'] : ''; ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Saída:</label>
                        <input type="date" class="form-control" name="data_saida" id="data_saida" required
                               value="<?php echo isset($formData['data_saida']) ? $formData['data_saida'] : ''; ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Diárias:</label>
                        <input type="number" class="form-control" name="qtd_diarias" id="qtd_diarias" readonly
                               value="<?php echo isset($formData['qtd_diarias']) ? $formData['qtd_diarias'] : ''; ?>">
                    </div>
                    <!-- Continue adicionando o atributo value para todos os campos do formulário -->
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Qtde de Hóspedes:</label>
                        <select class="form-select" name="qtde_hospedes" id="qtde_hospedes" required>
                            <option value="1"  selected>1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                    <!-- In the Motivo da Viagem select field -->
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Motivo da Viagem:</label>
                        <select class="form-select" name="motivo_viagem">
                            <option value="">---------</option>
                            <?php if (isset($motivos) && is_array($motivos)): ?>
                                <?php foreach ($motivos as $motivo): ?>
                                    <option value="<?php echo $motivo['id']; ?>"><?php echo $motivo['descricao']; ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="Trabalho">Trabalho</option>
                                <option value="Lazer">Lazer</option>
                                <option value="Saúde">Saúde</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- DADOS DO HÓSPEDE PRINCIPAL -->
        <div class="card mb-4 shadow-sm">
            
            <div class="card-body">
                <h5 class="card-title mb-0">DADOS DO HÓSPEDE PRINCIPAL</h5><br>
                <div class="row"></div>                                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nome Completo:</label>
                        <input type="text" class="form-control" name="nome_hospede" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">E-mail:</label>
                        <input type="email" class="form-control" name="email">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">CPF:</label>
                        <input type="text" class="form-control" name="cpf" id="cpf" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Telefone:</label>
                        <input type="text" class="form-control" name="telefone">
                    </div>
                </div>
                <div class="row">
                    <!-- No campo Status -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status:</label>
                        <select class="form-select" name="status">
                            <option value="">---------</option>
                            <?php if (isset($statusHospede) && is_array($statusHospede)): ?>
                                <?php foreach ($statusHospede as $status): ?>
                                    <option value="<?php echo $status['id']; ?>"><?php echo $status['descricao']; ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo">Inativo</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Graduação:</label>
                        <select class="form-select" name="graduacao">
                            <option value="">---------</option>
                            <?php if (isset($graduacoes) && is_array($graduacoes)): ?>
                                <?php foreach ($graduacoes as $graduacao): ?>
                                    <option value="<?php echo $graduacao['id']; ?>"><?php echo $graduacao['descricao']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Tipo:</label>
                        <select class="form-select" name="tipo">
                            <option value="">---------</option>
                            <?php if (isset($tiposHospede) && is_array($tiposHospede)): ?>
                                <?php foreach ($tiposHospede as $tipo): ?>
                                    <option value="<?php echo $tipo['id']; ?>"><?php echo $tipo['descricao']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Sexo:</label>
                        <select class="form-select" name="sexo">
                            <option value="">---------</option>
                            <?php if (isset($sexos) && is_array($sexos)): ?>
                                <?php foreach ($sexos as $sexo): ?>
                                    <option value="<?php echo $sexo['id']; ?>"><?php echo $sexo['descricao']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- INFORMAÇÃO DO ACOMPANHANTE - Será mostrado/escondido via JavaScript -->
                     



        <div class="card mb-4 shadow-sm" id="acompanhantes_card" style="display: none;">
            
                
            
            <div class="card-body">
                <h5 class="card-title mb-0" id="acompanhantes_title">INFORMAÇÃO DO ACOMPANHANTE</h5>
                <div class="row"><br></div>
                <div id="acompanhantes_container">
                    <!-- Campos de acompanhantes serão gerados dinamicamente via JavaScript -->
                </div>
            </div>
        </div>

        <!-- INFORMAÇÕES DO PET -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title text-success mb-0">INFORMAÇÕES DO PET</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">PET:</label>
                        <select class="form-select" name="tem_pet" id="tem_pet">
                            <option value="Não" selected>Não</option>
                            <option value="Sim">Sim</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="qtde_pet_field">
                        <label class="form-label">Qtde PET:</label>
                        <!-- Adicione este bloco de estilo no cabeçalho da página ou no seu arquivo CSS -->
                        <style>
                            /* Remove as setinhas de incremento/decremento dos inputs do tipo number */
                            .no-spinner::-webkit-inner-spin-button, 
                            .no-spinner::-webkit-outer-spin-button { 
                                -webkit-appearance: none; 
                                margin: 0; 
                            }
                            
                            /* Para Firefox */
                            .no-spinner {
                                -moz-appearance: textfield;
                            }
                        </style>
                        <input type="number" class="form-control no-spinner" name="qtde_pet" id="qtde_pet" min="0" value="0">
                    </div>
                </div>
            </div>
        </div>

        <!-- OUTRAS INFORMAÇÕES -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-light">
                <h5 class="card-title text-success mb-0">OUTRAS INFORMAÇÕES</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Remover o formulário aninhado que estava aqui -->
                    <!-- Na seção de UF -->
                    <div class="col-md-2 mb-3">
                        <label class="form-label">UF:</label>
                        <select class="form-select" name="uf" id="uf">
                            <option value="">----</option>
                            <?php if (isset($ufs) && is_array($ufs)): ?>
                                <?php foreach ($ufs as $uf): ?>
                                    <option value="<?php echo $uf['id']; ?>"><?php echo $uf['sigla']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cidade de Origem:</label>
                        <select class="form-select" name="cidade_origem" id="cidade_origem" disabled>
                            <option value="">Selecione uma UF primeiro</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Necessidades Especiais?</label>
                        <select class="form-select" name="necessidades_especiais">
                            <option value="">---------</option>
                            <option value="Sim">Sim</option>
                            <option value="Não">Não</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label">Observação:</label>
                        <textarea class="form-control" name="observacao" rows="3"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end mb-4">
            <a href="/Hotel/app/views/admin/tela_admin.php" class="btn btn-secondary me-2">Cancelar</a>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </div>
    </form>
</div>

<!-- Include the scripts -->
<!-- No final do arquivo, junto com os outros scripts -->
<script src="/Hotel/app/views/reserva/scripts/reserva_acompanhantes.js"></script>
<script src="/Hotel/app/views/reserva/scripts/reserva_pet.js"></script>
<script src="/Hotel/app/views/reserva/scripts/reserva_cidades.js"></script>
<script src="/Hotel/app/views/reserva/scripts/reserva_diarias.js"></script>
// ... no final do arquivo, antes do fechamento do </body>

<script>
    // Função para formatar CPF enquanto digita
    document.getElementById('cpf').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não for dígito
        
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        
        // Aplica a máscara conforme vai digitando
        if (value.length > 9) {
            value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{2}).*/, '$1.$2.$3-$4');
        } else if (value.length > 6) {
            value = value.replace(/^(\d{3})(\d{3})(\d{3}).*/, '$1.$2.$3');
        } else if (value.length > 3) {
            value = value.replace(/^(\d{3})(\d{3}).*/, '$1.$2');
        }
        
        e.target.value = value;
    });
    
    // Função para formatar telefone de forma flexível
    document.querySelector('input[name="telefone"]').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        
        // Formato flexível para números brasileiros e paraguaios
        if (value.length > 0) {
            // Se começar com +, adiciona o código do país
            if (e.target.value.startsWith('+')) {
                if (value.length <= 2) {
                    e.target.value = '+' + value;
                } else if (value.length <= 5) {
                    e.target.value = '+' + value.substring(0, 2) + ' ' + value.substring(2);
                } else if (value.length <= 10) {
                    e.target.value = '+' + value.substring(0, 2) + ' ' + value.substring(2, 5) + '-' + value.substring(5);
                } else {
                    e.target.value = '+' + value.substring(0, 2) + ' ' + value.substring(2, 5) + '-' + value.substring(5, 9) + '-' + value.substring(9);
                }
            } 
            // Formato brasileiro padrão
            else if (value.length <= 11) {
                if (value.length <= 2) {
                    // Apenas DDD
                } else if (value.length <= 6) {
                    e.target.value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
                } else if (value.length <= 10) {
                    e.target.value = '(' + value.substring(0, 2) + ') ' + value.substring(2, 6) + '-' + value.substring(6);
                } else {
                    e.target.value = '(' + value.substring(0, 2) + ') ' + value.substring(2, 7) + '-' + value.substring(7);
                }
            } 
            // Para números mais longos (internacionais)
            else {
                // Mantém como está para números muito longos
            }
        }
    });
</script>
<!-- Adicionar script para garantir o redirecionamento correto -->
<script>
document.getElementById('reservaForm').addEventListener('submit', function(e) {
    // Permitir que o formulário seja enviado normalmente
    // Mas garantir que após o salvamento, o redirecionamento seja feito corretamente
    localStorage.setItem('redirectAfterSave', '/Hotel/app/views/admin/tela_admin.php');
});
</script>