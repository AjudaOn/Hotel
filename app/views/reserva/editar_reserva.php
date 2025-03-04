<div class="container-fluid p-0">
    <h1 class="h3 mb-3">EDITAR RESERVA</h1>

    <!-- Debug para verificar os dados da query -->
    <?php 
    // Incluir o controller de acompanhantes
    include_once ROOT_PATH . '/app/controllers/reserva/editar_acompanhantes.php';
    
    // Buscar os sexos do banco
    $query_sexos = "SELECT id, nm_sexo FROM sexo ORDER BY nm_sexo";
    $result_sexos = $db->query($query_sexos);
    $sexos = [];
    if ($result_sexos && $result_sexos->num_rows > 0) {
        while ($row = $result_sexos->fetch_assoc()) {
            $sexos[] = $row;
        }
    }
    
    // Buscar os sexos para acompanhantes (mesma consulta, mas variável diferente)
    $sexos_acomp = $sexos;
    
    // Buscar os tipos de hóspede - Corrigindo o nome da coluna
    $query_tipos = "SELECT id, nm_tipo AS descricao FROM tipo_hospede ORDER BY nm_tipo";
    $result_tipos = $db->query($query_tipos);
    $tiposHospede = [];
    if ($result_tipos && $result_tipos->num_rows > 0) {
        while ($row = $result_tipos->fetch_assoc()) {
            $tiposHospede[] = $row;
        }
    }
    
    // Buscar os vínculos familiares do banco
    $query_vinculos = "SELECT id, nm_vinculo FROM vinculo_familiar ORDER BY nm_vinculo";
    $result_vinculos = $db->query($query_vinculos);
    $vinculos = [];
    if ($result_vinculos && $result_vinculos->num_rows > 0) {
        while ($row = $result_vinculos->fetch_assoc()) {
            $vinculos[] = $row;
        }
    }
    // Buscar o nome do município se tiver municipio_id
    if (isset($reserva['municipio_id']) && !empty($reserva['municipio_id'])) {
        // Buscar o nome do município se tiver municipio_id
        if (isset($reserva['municipio_id']) && !empty($reserva['municipio_id'])) {
            // Usar id_municipio na cláusula WHERE conforme mostrado na imagem
            $query_municipio = "SELECT id_municipio_nome FROM municipios WHERE id_municipio = " . $reserva['municipio_id'];
            $result_municipio = $db->query($query_municipio);
            if ($result_municipio && $result_municipio->num_rows > 0) {
                $municipio = $result_municipio->fetch_assoc();
                $reserva['municipio_nome'] = $municipio['id_municipio_nome'];
            }
        }
    }
    
    // Usar os acompanhantes do novo controller
    if (isset($_SESSION['acompanhantes_query']) && is_array($_SESSION['acompanhantes_query']) && count($_SESSION['acompanhantes_query']) > 0) {
        $acompanhantes = $_SESSION['acompanhantes_query'];
    }
    ?>

    <?php if (isset($reserva)): ?>
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <h5>Debug - Dados da Reserva:</h5>
                <pre><?php print_r($reserva); ?></pre>
                
                <!-- Adicionar informação específica do município -->
                <?php if (isset($reserva['municipio_id']) && !empty($reserva['municipio_id'])): ?>
                <h5 class="mt-4">Debug - Informação do Município:</h5>
                <pre>
Município ID: <?php echo $reserva['municipio_id']; ?>
Município Nome: <?php echo isset($reserva['municipio_nome']) ? $reserva['municipio_nome'] : 'Nome não encontrado'; ?>
                </pre>
                <?php endif; ?>
    
                <?php if (isset($acompanhantes) && is_array($acompanhantes) && count($acompanhantes) > 0): ?>
                    <h5 class="mt-4">Debug - Dados dos Acompanhantes:</h5>
                    <pre><?php print_r($acompanhantes); ?></pre>
                <?php elseif (isset($_SESSION['acompanhantes']) && is_array($_SESSION['acompanhantes']) && count($_SESSION['acompanhantes']) > 0): ?>
                    <h5 class="mt-4">Debug - Dados dos Acompanhantes (da Sessão):</h5>
                    <pre><?php print_r($_SESSION['acompanhantes']); ?></pre>
                <?php else: ?>
                    <div class="alert alert-info mt-3">
                        Nenhum acompanhante encontrado para esta reserva.
                    </div>
                <?php endif; ?>
    
                <!-- Novo debug para os acompanhantes do novo controller -->
                <h5 class="mt-4">Debug - Dados dos Acompanhantes (Novo Controller):</h5>
                <?php if (isset($_SESSION['acompanhantes_query']) && is_array($_SESSION['acompanhantes_query']) && count($_SESSION['acompanhantes_query']) > 0): ?>
                    <pre><?php print_r($_SESSION['acompanhantes_query']); ?></pre>
                <?php else: ?>
                    <div class="alert alert-warning mt-3">
                        <?php echo isset($_SESSION['erro_acompanhantes']) ? $_SESSION['erro_acompanhantes'] : 'Nenhum acompanhante encontrado no novo controller.'; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            Nenhum dado de reserva encontrado na variável $reserva
        </div>
    <?php endif; ?>

    <!-- Ajustando a action do formulário para usar o caminho correto -->
    <form id="reservaForm" action="/Hotel/app/controllers/reserva/atualizar_reserva.php" method="post">
        <!-- Campo oculto para o ID da reserva -->
        <input type="hidden" name="reserva_id" value="<?= $reserva_id ?>">
        <input type="hidden" name="redirect_to" value="/Hotel/admin/reserva/listar">
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
                            value="<?php echo isset($reserva['data_entrada']) ? $reserva['data_entrada'] : ''; ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Saída:</label>
                        <input type="date" class="form-control" name="data_saida" id="data_saida" required
                            value="<?php echo isset($reserva['data_saida']) ? $reserva['data_saida'] : ''; ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Diárias:</label>
                        <input type="number" class="form-control" name="qtd_diarias" id="qtd_diarias" readonly
                            value="<?php echo isset($reserva['qtd_diarias']) ? $reserva['qtd_diarias'] : ''; ?>">
                    </div>
                    <!-- Continue adicionando o atributo value para todos os campos do formulário -->
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Qtde de Hóspedes:</label>
                        <select class="form-select" name="qtd_pessoas" id="qtde_hospedes" required>
                            <?php for ($i = 1; $i <= 6; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo (isset($reserva['qtd_pessoas']) && $reserva['qtd_pessoas'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <!-- In the Motivo da Viagem select field -->
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Motivo da Viagem:</label>
                        <select class="form-select" name="motivo_viagem_id">
                            <option value="">---------</option>
                            <?php if (isset($motivos) && is_array($motivos)): ?>
                                <?php foreach ($motivos as $motivo): ?>
                                    <option value="<?php echo $motivo['id']; ?>" <?php echo (isset($reserva['motivo_viagem_id']) && $reserva['motivo_viagem_id'] == $motivo['id']) ? 'selected' : ''; ?>><?php echo $motivo['descricao']; ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="1" <?php echo (isset($reserva['motivo_viagem_id']) && $reserva['motivo_viagem_id'] == 1) ? 'selected' : ''; ?>>Trabalho</option>
                                <option value="2" <?php echo (isset($reserva['motivo_viagem_id']) && $reserva['motivo_viagem_id'] == 2) ? 'selected' : ''; ?>>Lazer</option>
                                <option value="3" <?php echo (isset($reserva['motivo_viagem_id']) && $reserva['motivo_viagem_id'] == 3) ? 'selected' : ''; ?>>Saúde</option>
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
                        <input type="text" class="form-control" name="nome_hospede" value="<?php echo isset($reserva['nome_hospede']) ? htmlspecialchars($reserva['nome_hospede']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">E-mail:</label>
                        <input type="email" class="form-control" name="email" value="<?php echo isset($reserva['email']) ? htmlspecialchars($reserva['email']) : ''; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">CPF:</label>
                        <input type="text" class="form-control" name="cpf" id="cpf" value="<?php echo isset($reserva['cpf']) ? htmlspecialchars($reserva['cpf']) : ''; ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Telefone:</label>
                        <input type="text" class="form-control" name="telefone" value="<?php echo isset($reserva['telefone']) ? htmlspecialchars($reserva['telefone']) : ''; ?>">
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
                                    <option value="<?php echo $status['id']; ?>" <?php echo (isset($reserva['status_hospede_id']) && $reserva['status_hospede_id'] == $status['id']) ? 'selected' : ''; ?>><?php echo $status['descricao']; ?></option>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <option value="Ativo" <?php echo (isset($reserva['status']) && $reserva['status'] == 'Ativo') ? 'selected' : ''; ?>>Ativo</option>
                                <option value="Inativo" <?php echo (isset($reserva['status']) && $reserva['status'] == 'Inativo') ? 'selected' : ''; ?>>Inativo</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Graduação:</label>
                        <select class="form-select" name="graduacao_id">
                            <option value="">---------</option>
                            <?php if (isset($graduacoes) && is_array($graduacoes)): ?>
                                <?php foreach ($graduacoes as $graduacao): ?>
                                    <option value="<?php echo $graduacao['id']; ?>" <?php echo (isset($reserva['graduacao_id']) && $reserva['graduacao_id'] == $graduacao['id']) ? 'selected' : ''; ?>><?php echo $graduacao['descricao']; ?></option>
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
                                    <option value="<?php echo $tipo['id']; ?>" 
                                        <?php echo (isset($reserva['tipo_hospede_id']) && $reserva['tipo_hospede_id'] == $tipo['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($tipo['descricao']); ?>
                                    </option>
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
                                    <option value="<?php echo $sexo['id']; ?>"
                                        <?php echo (isset($reserva['sexo_id']) && $reserva['sexo_id'] == $sexo['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($sexo['nm_sexo']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- INFORMAÇÃO DO ACOMPANHANTE - Será mostrado/escondido via JavaScript -->
        <div class="card mb-4 shadow-sm" id="acompanhantes_card"
            <?php echo (isset($reserva['qtd_pessoas']) && $reserva['qtd_pessoas'] <= 1) ? 'style="display: none;"' : ''; ?>>
            <div class="card-body">
                <h5 class="card-title mb-0" id="acompanhantes_title">
                    <?php echo (isset($reserva['qtd_pessoas']) && $reserva['qtd_pessoas'] > 2) ? 'INFORMAÇÕES DOS ACOMPANHANTES' : 'INFORMAÇÃO DO ACOMPANHANTE'; ?>
                </h5>
                <div class="row"><br></div>
                <div id="acompanhantes_container">
                    <?php
                    // Garantir que $acompanhantes esteja definido
                    if (!isset($acompanhantes) || !is_array($acompanhantes)) {
                        $acompanhantes = isset($_SESSION['acompanhantes_query']) ? $_SESSION['acompanhantes_query'] : [];
                    }
                    // Se ainda não tiver acompanhantes mas a reserva indicar que deveria ter
                    if (empty($acompanhantes) && isset($reserva['qtd_pessoas']) && $reserva['qtd_pessoas'] > 1) {
                        $numAcompanhantes = $reserva['qtd_pessoas'] - 1;
                        for ($i = 0; $i < $numAcompanhantes; $i++) {
                            $acompanhantes[] = [
                                'acompanhante_id' => null,
                                'nm_acomp' => '',
                                'idade_acomp' => '',
                                'sexo_id' => null,
                                'vinculo_familiar_id' => null,
                                'sexo_descricao' => '',
                                'vinculo_descricao' => ''
                            ];
                        }
                    }
                    if (!empty($acompanhantes)):
                        foreach ($acompanhantes as $index => $acompanhante):
                    ?>
                            <div class="row acompanhante-row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Nome do Acompanhante <?php echo $index + 1; ?>:</label>
                                    <input type="hidden" name="acompanhante_id_<?php echo $index; ?>" 
                                        value="<?php echo isset($acompanhante['acompanhante_id']) ? $acompanhante['acompanhante_id'] : ''; ?>">
                                    <input type="text" class="form-control" name="nome_acompanhante_<?php echo $index; ?>"
                                        value="<?php echo isset($acompanhante['nm_acomp']) ? htmlspecialchars($acompanhante['nm_acomp']) : ''; ?>" required>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">Idade:</label>
                                    <input type="number" class="form-control" name="idade_acompanhante_<?php echo $index; ?>"
                                        value="<?php echo isset($acompanhante['idade_acomp']) ? htmlspecialchars($acompanhante['idade_acomp']) : ''; ?>" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Sexo:</label>
                                    <select class="form-select" name="sexo_acompanhante_<?php echo $index; ?>" required>
                                        <option value="">Selecione</option>
                                        <?php 
                                        // Debug do valor do sexo
                                        echo "<!-- Sexo ID: " . (isset($acompanhante['acompanhante_sexo_id']) ? $acompanhante['acompanhante_sexo_id'] : 'não definido') . " -->";
                                        
                                        if (isset($sexos_acomp) && is_array($sexos_acomp)): 
                                            foreach ($sexos_acomp as $sexo): 
                                        ?>
                                                <option value="<?php echo $sexo['id']; ?>"
                                                    <?php echo (isset($acompanhante['acompanhante_sexo_id']) && $acompanhante['acompanhante_sexo_id'] == $sexo['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($sexo['nm_sexo']); ?>
                                                </option>
                                        <?php 
                                            endforeach;
                                        endif; 
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Vínculo Familiar:</label>
                                    <select class="form-select" name="vinculo_familiar_<?php echo $index; ?>" required>
                                        <option value="">Selecione</option>
                                        <?php if (isset($vinculos) && is_array($vinculos)): foreach ($vinculos as $vinculo): ?>
                                                <option value="<?php echo $vinculo['id']; ?>"
                                                    <?php echo (isset($acompanhante['vinculo_familiar_id']) && $acompanhante['vinculo_familiar_id'] == $vinculo['id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($vinculo['nm_vinculo']); ?>
                                                </option>
                                        <?php endforeach;
                                        endif; ?>
                                    </select>
                                </div>
                            </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
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
                            <option value="Não" <?php echo (!isset($reserva['pet_hospede']) || $reserva['pet_hospede'] == 0) ? 'selected' : ''; ?>>Não</option>
                            <option value="Sim" <?php echo (isset($reserva['pet_hospede']) && $reserva['pet_hospede'] > 0) ? 'selected' : ''; ?>>Sim</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3" id="qtde_pet_field" <?php echo (!isset($reserva['pet_hospede']) || $reserva['pet_hospede'] == 0) ? 'style="display: none;"' : ''; ?>>
                        <label class="form-label">Qtde PET:</label>
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
                        <input type="number" class="form-control no-spinner" name="qtde_pet" id="qtde_pet" min="0" 
                               value="<?php echo isset($reserva['pet_hospede']) ? intval($reserva['pet_hospede']) : '0'; ?>">
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
                            <option value="">-------</option>
                            <?php if (isset($ufs) && is_array($ufs)): ?>
                                <?php foreach ($ufs as $uf): ?>
                                    <option value="<?php echo $uf['id']; ?>" 
                                        <?php echo (isset($reserva['uf_id']) && $reserva['uf_id'] == $uf['id']) ? 'selected' : ''; ?>>
                                        <?php echo $uf['sigla']; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Cidade de Origem:</label>
                        <select class="form-select" name="cidade_origem" id="cidade_origem">
                            <?php if (isset($reserva['municipio_nome'])): ?>
                                <option value="<?php echo $reserva['municipio_id']; ?>"><?php echo $reserva['municipio_nome']; ?></option>
                            <?php else: ?>
                                <option value="">Selecione uma UF primeiro</option>
                            <?php endif; ?>
                            <!-- As cidades serão carregadas via JavaScript -->
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Necessidades Especiais?</label>
                        <select class="form-select" name="necessidades_especiais">
                            <option value="">---------</option>
                            <option value="Sim" <?php echo (isset($reserva['necessidades_especiais']) && ($reserva['necessidades_especiais'] == 'Não' || $reserva['necessidades_especiais'] == '0')) ? 'selected' : ''; ?>>Não</option>
                            <option value="Sim" <?php echo (isset($reserva['necessidades_especiais']) && ($reserva['necessidades_especiais'] == 'Sim' || $reserva['necessidades_especiais'] == '1')) ? 'selected' : ''; ?>>Sim</option>
                        </select>
                    </div>
                    <!-- Adicionar estes campos ocultos para armazenar os valores do banco -->
                    <input type="hidden" id="uf_id_salvo" value="<?php echo isset($reserva['uf_id']) ? $reserva['uf_id'] : ''; ?>">
                    <input type="hidden" id="municipio_id_salvo" 
                           value="<?php echo isset($reserva['municipio_id']) ? $reserva['municipio_id'] : ''; ?>"
                           data-nome="<?php echo isset($reserva['municipio_nome']) ? $reserva['municipio_nome'] : ''; ?>">
                    <!-- Remover a duplicação do campo Necessidades Especiais -->
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label">Observação:</label>
                        <textarea class="form-control" name="observacao" rows="3"><?php echo isset($reserva['observacao']) ? htmlspecialchars($reserva['observacao']) : ''; ?></textarea>
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
<script src="/Hotel/app/views/reserva/scripts/editar_reserva_acompanhantes.js"></script>
<script src="/Hotel/app/views/reserva/scripts/reserva_pet.js"></script>
<script src="/Hotel/app/views/reserva/scripts/reserva_cidades.js"></script>
<script src="/Hotel/app/views/reserva/scripts/reserva_diarias.js"></script>
<script src="/Hotel/app/views/reserva/scripts/editar_reserva_formatacao.js"></script>
<script src="/Hotel/app/views/reserva/scripts/editar_reserva_redirecionamento.js"></script>
<!-- Remover a linha duplicada abaixo -->
<script>
    $(document).ready(function() {
        // Função para carregar cidades com base na UF selecionada
        function carregarCidades(ufId, cidadeSelecionada = null) {
            $.ajax({
                url: '/Hotel/admin/get-cidades-by-uf/' + ufId,
                type: 'GET',
                dataType: 'json',
                success: function(cidades) {
                    var selectCidade = $('#cidade_origem');
                    var cidadeEncontrada = false;
                    
                    // Guardar a opção atual se for a cidade salva
                    var cidadeSalva = null;
                    if (selectCidade.find('option:first').val() == cidadeSelecionada) {
                        cidadeSalva = selectCidade.find('option:first').clone();
                    }
                    
                    // Limpar o select mas NÃO adicionar a opção padrão "Selecione uma cidade"
                    selectCidade.empty();
                    
                    // Se temos uma cidade salva, adicioná-la primeiro e selecioná-la
                    if (cidadeSalva) {
                        selectCidade.append(cidadeSalva);
                        cidadeEncontrada = true;
                    }
                    
                    // Adicionar as cidades da lista
                    $.each(cidades, function(index, cidade) {
                        var selected = '';
                        if (cidadeSelecionada && cidadeSelecionada == cidade.id) {
                            selected = 'selected';
                            cidadeEncontrada = true;
                        }
                        selectCidade.append('<option value="' + cidade.id + '" ' + selected + '>' + cidade.nome + '</option>');
                    });
                    
                    // Se não encontrou a cidade na lista e não temos uma cidade salva,
                    // mas temos o ID e nome da cidade no campo oculto
                    if (!cidadeEncontrada && cidadeSelecionada) {
                        var municipioNome = $('#municipio_id_salvo').data('nome');
                        if (municipioNome) {
                            selectCidade.prepend('<option value="' + cidadeSelecionada + '" selected>' + municipioNome + '</option>');
                        }
                    }
                }
            });
        }

        // Quando a UF for alterada, carrega as cidades
        $('#uf').change(function() {
            var ufId = $(this).val();
            if (ufId) {
                carregarCidades(ufId);
            } else {
                $('#cidade_origem').empty();
                // Se não tiver UF selecionada, mostrar a mensagem
                $('#cidade_origem').append('<option value="">Selecione uma UF primeiro</option>');
            }
        });

        // Ao carregar a página, se já tiver UF selecionada, carrega as cidades
        var ufInicial = $('#uf').val();
        var cidadeInicial = $('#municipio_id_salvo').val();
        
        if (ufInicial && cidadeInicial) {
            carregarCidades(ufInicial, cidadeInicial);
        }
    });
</script>
<!-- <script src="/Hotel/app/views/reserva/scripts/editar_reserva_cidades.js"></script> -->
</body>
<?php
// Definir o caminho raiz se não estiver definido
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/Hotel');
}
// Resto do código...