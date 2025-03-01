<div class="row">
    <div class="col-12">
        <!-- Atualizar a action do formulário -->
        <form id="reservaForm" action="/Trae/reserva/atualizar" method="post">
            <!-- Manter o campo hidden com o ID -->
            <input type="hidden" name="id" value="<?= $id ?>">

            <!-- Corrigir os names dos campos para corresponder ao que o controller espera -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">INFORMAÇÕES DA RESERVA</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Entrada:</label>
                            <input type="date" class="form-control" name="data_entrada" id="entrada"
                                value="<?= isset($data['reserva']['data_entrada']) ? $data['reserva']['data_entrada'] : '' ?>"
                                min="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Saída:</label>
                            <input type="date" class="form-control" name="data_saida" id="saida"
                                value="<?= isset($data['reserva']['data_saida']) ? $data['reserva']['data_saida'] : '' ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Qtde de Hóspedes:</label>
                            <select class="form-select" name="qtd_pessoas" id="qtde_hospedes" required>
                                <?php for ($i = 1; $i <= 6; $i++): ?>
                                    <option value="<?= $i ?>" <?= (isset($data['reserva']['qtd_pessoas']) && $data['reserva']['qtd_pessoas'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Motivo da Viagem:</label>
                            <select class="form-select" name="motivo_viagem_id" id="motivo_viagem" required>
                                <option value="">---------</option>
                                <?php if (isset($data['motivos']) && is_array($data['motivos'])): ?>
                                    <?php foreach ($data['motivos'] as $motivo): ?>
                                        <?php
                                        $selected = (isset($data['reserva']['motivo_viagem_id']) &&
                                            (int)$motivo['id'] === (int)$data['reserva']['motivo_viagem_id']) ? 'selected' : '';
                                        ?>
                                        <option value="<?= $motivo['id'] ?>" <?= $selected ?>>
                                            <?= $motivo['nm_motivo'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DADOS DO HÓSPEDE PRINCIPAL -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">DADOS DO HÓSPEDE PRINCIPAL</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Nome Completo:</label>
                            <input type="text" class="form-control" name="nome"
                                value="<?= isset($data['reserva']['nome']) ? htmlspecialchars($data['reserva']['nome']) : '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail:</label>
                            <input type="email" class="form-control" name="email"
                                value="<?= isset($data['reserva']['email']) ? htmlspecialchars($data['reserva']['email']) : '' ?>" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">CPF:</label>
                            <input type="text" class="form-control" name="cpf" id="cpf"
                                value="<?= isset($data['reserva']['cpf']) ? htmlspecialchars($data['reserva']['cpf']) : '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone:</label>
                            <input type="text" class="form-control" name="telefone" id="telefone"
                                value="<?= isset($data['reserva']['telefone']) ? htmlspecialchars($data['reserva']['telefone']) : '' ?>" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label">Status:</label>
                            <select class="form-select" name="status_hospede_id" required>
                                <option value="">---------</option>
                                <?php if (isset($data['status']) && is_array($data['status'])): ?>
                                    <?php foreach ($data['status'] as $status): ?>
                                        <option value="<?= $status['id'] ?>" <?= (isset($data['reserva']['status_hospede_id']) && $status['id'] == $data['reserva']['status_hospede_id']) ? 'selected' : '' ?>>
                                            <?= $status['nm_status'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Graduação:</label>
                            <select class="form-select" name="graduacao_id" required>
                                <option value="">---------</option>
                                <?php if (isset($data['graduacoes']) && is_array($data['graduacoes'])): ?>
                                    <?php foreach ($data['graduacoes'] as $graduacao): ?>
                                        <option value="<?= $graduacao['id'] ?>" <?= (isset($data['reserva']['graduacao_id']) && $graduacao['id'] == $data['reserva']['graduacao_id']) ? 'selected' : '' ?>>
                                            <?= $graduacao['nm_graduacao'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo:</label>
                            <select class="form-select" name="tipo_hospede_id" required>
                                <option value="">---------</option>
                                <?php if (isset($data['tipos']) && is_array($data['tipos'])): ?>
                                    <?php foreach ($data['tipos'] as $tipo): ?>
                                        <option value="<?= $tipo['id'] ?>" <?= (isset($data['reserva']['tipo_hospede_id']) && $tipo['id'] == $data['reserva']['tipo_hospede_id']) ? 'selected' : '' ?>>
                                            <?= $tipo['nm_tipo'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sexo:</label>
                            <select class="form-select" name="sexo_id" required>
                                <option value="">---------</option>
                                <?php if (isset($data['sexos']) && is_array($data['sexos'])): ?>
                                    <?php foreach ($data['sexos'] as $sexo): ?>
                                        <option value="<?= $sexo['id'] ?>" <?= (isset($data['reserva']['sexo_id']) && $sexo['id'] == $data['reserva']['sexo_id']) ? 'selected' : '' ?>>
                                            <?= $sexo['nm_sexo'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- INFORMAÇÕES DO ACOMPANHANTE -->
            <div id="acompanhantes_container" class="card mb-4" style="display:none;">
                <div class="card-header">
                    <h5 class="card-title mb-0" id="titulo_acompanhantes">INFORMAÇÕES DO ACOMPANHANTE</h5>
                </div>
                <div class="card-body">
                    <div id="acompanhantes_fields">
                        <!-- campos dos acompanhantes serão inseridos aqui via JavaScript -->
                    </div>
                </div>
            </div>

            <!-- OUTRAS INFORMAÇÕES -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">OUTRAS INFORMAÇÕES</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label class="form-label">UF:</label>
                            <select class="form-select" name="uf_id" id="uf" required>
                                <option value="">--</option>
                                <?php if (isset($data['ufs']) && is_array($data['ufs'])): ?>
                                    <?php foreach ($data['ufs'] as $uf): ?>
                                        <option value="<?= $uf['id'] ?>" <?= (isset($data['reserva']['uf_id']) && $uf['id'] == $data['reserva']['uf_id']) ? 'selected' : '' ?>>
                                            <?= $uf['sigla_uf'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cidade de Origem:</label>
                            <select class="form-select" name="municipio_id" id="cidade_origem" required>
                                <option value="">Selecione primeiro a UF</option>
                                <?php if (isset($data['municipios']) && is_array($data['municipios'])): ?>
                                    <?php foreach ($data['municipios'] as $municipio): ?>
                                        <option value="<?= $municipio['id_municipio'] ?>" <?= (isset($data['reserva']['municipio_id']) && $municipio['id_municipio'] == $data['reserva']['municipio_id']) ? 'selected' : '' ?>>
                                            <?= $municipio['id_municipio_nome'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Necessidades Especiais:</label>
                            <select class="form-select" name="necessidades_especiais" id="necessidades_especiais" required>
                                <option value="">---------</option>
                                <option value="0" <?php echo ($data['reserva']['necessidades_especiais'] == 0) ? 'selected' : ''; ?>>Não</option>
                                <option value="1" <?php echo ($data['reserva']['necessidades_especiais'] == 1) ? 'selected' : ''; ?>>Sim</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Pet:</label>
                            <select class="form-select" name="tem_pet" id="tem_pet">
                                <option value="1" <?= (isset($data['reserva']['pet_hospede']) && $data['reserva']['pet_hospede'] == 1) ? 'selected' : '' ?>>Sim</option>
                                <option value="0" <?= (isset($data['reserva']['pet_hospede']) && $data['reserva']['pet_hospede'] == 0) ? 'selected' : '' ?>>Não</option>
                            </select>
                        </div>
                        <div class="col-md-2" id="qtde_pet_container" style="display:none;">
                            <label class="form-label">Qtde Pet:</label>
                            <input type="number" class="form-control" name="qtde_pet" id="qtde_pet" min="1"
                                value="<?= isset($data['reserva']['pet_hospede']) ? $data['reserva']['pet_hospede'] : '' ?>">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Botões -->
            <div class="row mb-5">
                <div class="col-12 text-start"> <!-- Mudado de text-end para text-start -->
                    <button type="submit" class="btn btn-primary" onclick="return validateForm()">Salvar</button>
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>






<!-- No início do arquivo, após as tags head, adicione os scripts necessários -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script><!-- No início do arquivo, após as tags head -->


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<!-- No final do arquivo, antes do </body> -->
<script src="/Trae/app/views/reserva/scripts/form-masks.js"></script>
<script src="/Trae/app/views/reserva/scripts/form-submit.js"></script>
<script src="/Trae/app/views/reserva/scripts/form-validation.js"></script>
<script src="/Trae/app/views/reserva/scripts/ajax-submit.js"></script>
<script src="/Trae/app/views/reserva/scripts/acompanhantes-fields.js"></script>
<script src="/Trae/app/views/reserva/scripts/acompanhantes.js"></script>
<script src="/Trae/app/views/reserva/scripts/acompanhantes-data.js"></script>
<script src="/Trae/app/views/reserva/scripts/municipios.js"></script>
<script src="/Trae/app/views/reserva/scripts/pet-control.js"></script>
<?php
require_once ROOT_PATH . '/vendor/autoload.php';
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery e outros scripts essenciais -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
</head>