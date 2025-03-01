<?php
// Ensure database connection is available
require_once ROOT_PATH . '/app/config/database.php';

$dbInstance = new \App\Config\Database();
$db = $dbInstance->getConnection();

// Query to fetch reservations
$query = "SELECT 
    r.id, 
    h.nome AS nome, 
    h.cpf AS cpf, 
    r.data_entrada, 
    r.data_saida, 
    r.qtd_pessoas AS acompanhantes
FROM 
    reservas r
JOIN 
    hospedes h ON r.id = h.reserva_id
ORDER BY 
    r.id ASC";

// Executar a consulta
$result = $db->query($query);

// Inicializar array para armazenar os resultados
$reservas = [];

// Verificar se a consulta retornou resultados
if ($result) {
    // Obter os resultados como um array associativo
    while ($row = $result->fetch_assoc()) {
        $reservas[] = $row;
    }
}
?>

<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Reservas em Análise</h1>

    <div class="card mb-4 shadow-sm">
        
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>CPF</th>
                        <th>Entrada</th>
                        <th>Saída</th>
                        <th>Acompanhantes</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($reservas) > 0): ?>
                        <?php foreach ($reservas as $reserva): ?>
                            <tr>
                                <td><?= htmlspecialchars($reserva['id']) ?></td>
                                <td><?= htmlspecialchars($reserva['nome']) ?></td>
                                <td><?= htmlspecialchars($reserva['cpf']) ?></td>
                                <td><?= date('d/m/Y', strtotime($reserva['data_entrada'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($reserva['data_saida'])) ?></td>
                                <td><?= htmlspecialchars($reserva['acompanhantes']) ?></td>
                                <td>
                                    <a href="/Hotel/admin/reserva/editar?id=<?= $reserva['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Nenhuma reserva encontrada.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>