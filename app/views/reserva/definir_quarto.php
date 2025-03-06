<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Definir Quarto para Reserva</h1>

    <!-- Debug information -->
    <div class="alert alert-info">
        Debug: ID = <?= isset($_GET['id']) ? $_GET['id'] : 'não definido' ?>
    </div>
    
    <?php 
    // Obter o ID diretamente da URL
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    
    if ($id) {
        // Conectar ao banco de dados usando a classe Database existente
        require_once ROOT_PATH . '/app/config/database.php';
        $dbInstance = new \App\Config\Database();
        $db = $dbInstance->getConnection();
        
        // Buscar dados da reserva
        $query_reserva = "SELECT r.*, h.nome, DATE_FORMAT(r.data_entrada, '%d/%m/%Y') as data_entrada_formatada, 
                         DATE_FORMAT(r.data_saida, '%d/%m/%Y') as data_saida_formatada 
                         FROM reservas r 
                         JOIN hospedes h ON r.id = h.reserva_id 
                         WHERE r.id = ?";
        
        $stmt = $db->prepare($query_reserva);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result_reserva = $stmt->get_result();
        
        if ($result_reserva->num_rows > 0) {
            $reserva = $result_reserva->fetch_assoc();
            
            // Obter todos os quartos
            $query_todos_quartos = "SELECT id, numero FROM quartos ORDER BY numero";
            $result_todos_quartos = $db->query($query_todos_quartos);
            $todos_quartos = [];
            while ($row = $result_todos_quartos->fetch_assoc()) {
                $todos_quartos[] = $row;
            }
            // Obter datas da reserva
            $data_entrada = new DateTime($reserva['data_entrada']);
            $data_saida = new DateTime($reserva['data_saida']);
            $periodo = [];
            
            // Criar array com todas as datas do período (excluindo o dia de saída)
            $data_atual = clone $data_entrada;
            while ($data_atual < $data_saida) { // Mudança de <= para < para excluir o dia de saída
                $periodo[] = $data_atual->format('d/m/Y');
                $data_atual->modify('+1 day');
            }
            // Buscar reservas que se sobrepõem ao período
            $query_reservas_periodo = "SELECT r.id, r.quartos_id, r.data_entrada, r.data_saida 
                                     FROM reservas r 
                                     WHERE r.quartos_id IS NOT NULL 
                                     AND r.id != {$id}
                                     AND (
                                         (r.data_entrada < '{$reserva['data_saida']}' AND r.data_saida > '{$reserva['data_entrada']}')
                                         OR (r.data_entrada = '{$reserva['data_entrada']}')
                                     )";
            $result_reservas_periodo = $db->query($query_reservas_periodo);
            $quartos_ocupados = [];
            
            while ($row = $result_reservas_periodo->fetch_assoc()) {
                $quartos_ocupados[$row['quartos_id']] = true;
            }
            
            // Buscar quartos disponíveis considerando as datas
            $query_quartos = "SELECT q.id, q.numero, tq.descricao 
                             FROM quartos q 
                             JOIN tipos_quarto tq ON q.tipo_id = tq.id
                             WHERE q.id NOT IN (
                                 SELECT r.quartos_id 
                                 FROM reservas r 
                                 WHERE r.quartos_id IS NOT NULL 
                                 AND r.id != {$id}
                                 AND (
                                     (r.data_entrada <= '{$reserva['data_saida']}' AND r.data_saida >= '{$reserva['data_entrada']}')
                                 )
                             )
                             ORDER BY q.numero";
            $result_quartos = $db->query($query_quartos);
            $quartos = [];
            if ($result_quartos && $result_quartos->num_rows > 0) {
                while ($row = $result_quartos->fetch_assoc()) {
                    $quartos[] = $row;
                }
            }
    ?>
    <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Quadro de distribuição de Quartos</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <?php foreach ($todos_quartos as $quarto): ?>
                                            <th><?= $quarto['numero'] ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($periodo as $data): ?>
                                        <tr>
                                            <td><?= $data ?></td>
                                            <?php foreach ($todos_quartos as $quarto): ?>
                                                <td class="<?= isset($quartos_ocupados[$quarto['id']]) ? 'bg-danger' : 'bg-success' ?>">&nbsp;</td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Dados da reserva</h5>
                    <div class="mb-2">
                        <span class="text-muted">ID da Reserva:</span> <?= $id ?>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted">Hóspede:</span> <?= htmlspecialchars($reserva['nome']) ?>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted">Período:</span> <?= $reserva['data_entrada_formatada'] ?> a <?= $reserva['data_saida_formatada'] ?>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted">Quantidade de Acompanhantes:</span> <?= $reserva['qtd_pessoas'] - 1 ?>
                    </div>
                    
                    <div class="text-muted mt-3" style="color: #6c757d !important;">
                        Precisa ter a ID da reserva e caso tenha acompanhante listar o nome e idade
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Análise da Reserva</h5>
                    
                    <div class="mb-3">
                        <label class="form-label">Possui quartos disponíveis?</label>
                        <select class="form-select" disabled>
                            <option><?= count($quartos) > 0 ? 'Sim' : 'Não' ?></option>
                        </select>
                    </div>
                    
                    <form id="definirQuartoForm" method="post" action="/Hotel/app/controllers/reserva/definir_quarto.php">
                        <input type="hidden" name="reserva_id" value="<?= $id ?>">
                        
                        <div class="mb-3">
                            <label for="quarto" class="form-label">Defina a UH:</label>
                            <select class="form-select" id="quarto" name="quarto_id" required>
                                <option value="">Selecione um quarto...</option>
                                <?php if (count($quartos) > 0): ?>
                                    <?php foreach ($quartos as $quarto): ?>
                                        <option value="<?= $quarto['id'] ?>">
                                            Quarto <?= $quarto['numero'] ?> - <?= $quarto['descricao'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>Nenhum quarto disponível</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="/Hotel/admin/reserva/listar" class="btn btn-secondary">Voltar</a>
                            <button type="submit" class="btn btn-primary">Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php 
        } else {
    ?>
        <div class="alert alert-danger">
            Reserva não encontrada.
        </div>
        <a href="/Hotel/admin/reserva/listar" class="btn btn-secondary">Voltar</a>
    <?php
        }
    } else {
    ?>
        <div class="alert alert-danger">
            ID da reserva não fornecido.
        </div>
        <a href="/Hotel/admin/reserva/listar" class="btn btn-secondary">Voltar</a>
    <?php
    }
    ?>
</div>