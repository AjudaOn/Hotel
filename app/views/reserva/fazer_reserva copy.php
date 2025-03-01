<div class="row">
    <div class="col-12">
        <form id="reservaForm" action="/Trae/reserva/salvar" method="post">
            <!-- INFORMAÇÕES DA RESERVA -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">INFORMAÇÕES DA RESERVA</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Entrada:</label>
                            <input type="date" class="form-control" name="entrada" id="entrada" 
                                   min="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Saída:</label>
                            <input type="date" class="form-control" name="saida" id="saida" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Qtde de Hóspedes:</label>
                            <select class="form-select" name="qtde_hospedes" id="qtde_hospedes" required>
                                <?php for($i=1; $i<=6; $i++): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Motivo da Viagem:</label>
                            <!-- No select de motivo da viagem -->
                            <select class="form-select" name="motivo_viagem" required>
                                <option value="">---------</option>
                                <?php if(isset($data['motivos']) && is_array($data['motivos'])): ?>
                                    <?php foreach($data['motivos'] as $motivo): ?>
                                        <option value="<?= $motivo['id'] ?>"><?= $motivo['nm_motivo'] ?></option>
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
                            <input type="text" class="form-control" name="nome" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail:</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label class="form-label">CPF:</label>
                            <input type="text" class="form-control" name="cpf" id="cpf" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone:</label>
                            <input type="text" class="form-control" name="telefone" id="telefone" required>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <label class="form-label">Status:</label>
                            <!-- No select de status -->
                            <select class="form-select" name="status" required>
                                <option value="">---------</option>
                                <?php if(isset($data['status']) && is_array($data['status'])): ?>
                                    <?php foreach($data['status'] as $status): ?>
                                        <option value="<?= $status['id'] ?>"><?= $status['nm_status'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Graduação:</label>
                            <select class="form-select" name="graduacao" required>
                                <option value="">---------</option>
                                <?php if(isset($data['graduacoes']) && is_array($data['graduacoes'])): ?>
                                    <?php foreach($data['graduacoes'] as $graduacao): ?>
                                        <option value="<?= $graduacao['id'] ?>"><?= $graduacao['nm_graduacao'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo:</label>
                            <select class="form-select" name="tipo" required>
                                <option value="">---------</option>
                                <?php if(isset($data['tipos']) && is_array($data['tipos'])): ?>
                                    <?php foreach($data['tipos'] as $tipo): ?>
                                        <option value="<?= $tipo['id'] ?>"><?= $tipo['nm_tipo'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sexo:</label>
                            <select class="form-select" name="sexo" required>
                                <option value="">---------</option>
                                <?php if(isset($data['sexos']) && is_array($data['sexos'])): ?>
                                    <?php foreach($data['sexos'] as $sexo): ?>
                                        <option value="<?= $sexo['id'] ?>"><?= $sexo['nm_sexo'] ?></option>
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
                        <div class="col-md-4">
                            <label class="form-label">UF:</label>
                            <select class="form-select" name="uf" id="uf" required>
                                <option value="">--</option>
                                <?php if(isset($data['ufs']) && is_array($data['ufs'])): ?>
                                    <?php foreach($data['ufs'] as $uf): ?>
                                        <option value="<?= $uf['id'] ?>"><?= $uf['sigla_uf'] ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Cidade de Origem:</label>
                            <select class="form-select" name="cidade_origem" id="cidade_origem" required disabled>
                                <option value="">Selecione primeiro a UF</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Necessidades Especiais:</label>
                            <select class="form-select" name="necessidades_especiais">
                                <option value="1">Sim</option>
                                <option value="0" selected>Não</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pet:</label>
                            <select class="form-select" name="tem_pet" id="tem_pet">
                                <option value="1">Sim</option>
                                <option value="0" selected>Não</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="qtde_pet_container" style="display:none;">
                            <label class="form-label">Qtde Pet:</label>
                            <input type="number" class="form-control" name="qtde_pet" id="qtde_pet" min="1">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="row">
                <div class="col-12 text-end">
                    <button type="button" class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- No início do arquivo, após as tags head, adicione os scripts necessários -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<!-- E no final do arquivo, ajuste o script -->
<script>
    $(document).ready(function() {
        // Máscaras para os campos
        $('#cpf').mask('000.000.000-00');
        $('#telefone').mask('(00) 00000-0000');

        // Controle de acompanhantes
        $('#qtde_hospedes').on('change', function() {
            const qtde = parseInt($(this).val());
            const container = $('#acompanhantes_container');
            const fields = $('#acompanhantes_fields');

            if (qtde > 1) {
                container.show();
                fields.empty();

                // Atualiza título
                $('#titulo_acompanhantes').text(qtde > 2 ?
                    'INFORMAÇÕES DOS ACOMPANHANTES' : 'INFORMAÇÃO DO ACOMPANHANTE');

                // Adiciona campos para cada acompanhante
                for (let i = 1; i < qtde; i++) {
                    fields.append(createAcompanhanteFields(i));
                }
            } else {
                container.hide();
                fields.empty();
            }
        });

        // Função para criar campos de acompanhante
        function createAcompanhanteFields(index) {
            return `
            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label">Nome Completo:</label>
                    <input type="text" class="form-control" name="acomp_nome_${index}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Vínculo Familiar:</label>
                    <select class="form-select" name="acomp_vinculo_${index}" required>
                        <option value="">---------</option>
                        <?php if(isset($data['vinculos']) && is_array($data['vinculos'])): ?>
                            <?php foreach($data['vinculos'] as $vinculo): ?>
                                <option value="<?= $vinculo['id'] ?>"><?= $vinculo['nm_vinculo'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Idade:</label>
                    <input type="number" class="form-control" name="acomp_idade_${index}" min="0" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Sexo:</label>
                    <select class="form-select" name="acomp_sexo_${index}" required>
                        <option value="">--</option>
                        <?php if(isset($data['sexos']) && is_array($data['sexos'])): ?>
                            <?php foreach($data['sexos'] as $sexo): ?>
                                <option value="<?= $sexo['id'] ?>"><?= $sexo['nm_sexo'] ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>`;
        }

        // Controle do campo Pet
        $('#tem_pet').change(function() {
            if ($(this).val() === '1') {
                $('#qtde_pet_container').show();
                $('#qtde_pet').prop('required', true);
            } else {
                $('#qtde_pet_container').hide();
                $('#qtde_pet').prop('required', false);
                $('#qtde_pet').val('0');
            }
        });

        // Controle das datas
        var hoje = new Date();
        hoje.setHours(0, 0, 0, 0);
        var dataMinima = hoje.toISOString().split('T')[0];

        // Permite selecionar a partir do dia atual
        $('#entrada').attr('min', dataMinima);
        
        $('#entrada').on('change', function() {
            var dataEntrada = $(this).val();
            $('#saida').attr('min', dataEntrada);
            
            if ($('#saida').val() && $('#saida').val() < dataEntrada) {
                $('#saida').val('');
            }
        });

        // Controle de UF e Cidade
        $('#uf').change(function() {
            const ufId = $(this).val();
            const cidadeSelect = $('#cidade_origem');
        
            if (ufId) {
                $.ajax({
                    url: '/Trae/reserva/getMunicipios',
                    method: 'POST',
                    data: { uf_id: ufId },
                    success: function(response) {
                        const municipios = JSON.parse(response);
                        cidadeSelect.empty().prop('disabled', false);
                        cidadeSelect.append('<option value="">Selecione a cidade</option>');
                        
                        municipios.forEach(function(municipio) {
                            cidadeSelect.append(`<option value="${municipio.id_municipio}">${municipio.id_municipio_nome}</option>`);
                        });
                    },
                    error: function() {
                        alert('Erro ao carregar municípios');
                        cidadeSelect.empty()
                            .prop('disabled', true)
                            .append('<option value="">Erro ao carregar cidades</option>');
                    }
                });
            } else {
                cidadeSelect.empty()
                    .prop('disabled', true)
                    .append('<option value="">Selecione primeiro a UF</option>');
            }
        });
    });
</script>