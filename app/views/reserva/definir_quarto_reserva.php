<?php
// Verificação de sessão já deve estar sendo feita no header.php
// Remover a verificação duplicada aqui

// Conexão direta com o banco de dados
$db = new mysqli("localhost", "root", "", "hotel");

// Verificar conexão
if ($db->connect_error) {
    die("Conexão falhou: " . $db->connect_error);
}

// Verificar se o ID da reserva foi fornecido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: /Hotel/admin/reserva/listar');
    exit;
}

$reserva_id = $_GET['id'];

// Buscar dados da reserva
$query_reserva = "SELECT r.*, h.nome, DATE_FORMAT(r.data_entrada, '%d/%m/%Y') as data_entrada_formatada, 
                 DATE_FORMAT(r.data_saida, '%d/%m/%Y') as data_saida_formatada 
                 FROM reservas r 
                 JOIN hospedes h ON r.id = h.reserva_id 
                 WHERE r.id = ?";

$stmt = $db->prepare($query_reserva);
$stmt->bind_param("i", $reserva_id);
$stmt->execute();
$result_reserva = $stmt->get_result();

if ($result_reserva->num_rows === 0) {
    header('Location: /Hotel/admin/reserva/listar');
    exit;
}

$reserva = $result_reserva->fetch_assoc();

// Buscar quartos disponíveis
$query_quartos = "SELECT q.id, q.numero, q.descricao 
                 FROM quartos q 
                 WHERE q.status = 'disponivel' 
                 ORDER BY q.numero";
$result_quartos = $db->query($query_quartos);
$quartos = [];
if ($result_quartos && $result_quartos->num_rows > 0) {
    while ($row = $result_quartos->fetch_assoc()) {
        $quartos[] = $row;
    }
}

// Incluir o cabeçalho
include_once $_SERVER['DOCUMENT_ROOT'] . '/Hotel/app/views/includes/header.php';
?>

<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Definir Quarto para Reserva</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Informações da Reserva</h5>
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Hóspede:</strong> <?= htmlspecialchars($reserva['nome']) ?></p>
                    <p><strong>Período:</strong> <?= $reserva['data_entrada_formatada'] ?> a <?= $reserva['data_saida_formatada'] ?></p>
                    <p><strong>Quantidade de Pessoas:</strong> <?= $reserva['qtd_pessoas'] ?></p>
                </div>
            </div>

            <form id="definirQuartoForm" method="post" action="/Hotel/app/controllers/reserva/definir_quarto.php">
                <input type="hidden" name="reserva_id" value="<?= $reserva_id ?>">
                
                <div class="mb-3">
                    <label for="quarto" class="form-label">Selecione o Quarto</label>
                    <select class="form-select" id="quarto" name="quarto_id" required>
                        <option value="">Selecione um quarto...</option>
                        <?php foreach ($quartos as $quarto): ?>
                            <option value="<?= $quarto['id'] ?>">
                                Quarto <?= $quarto['numero'] ?> - <?= $quarto['descricao'] ?>
                            </option>
                        <?php endforeach; ?>
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

<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/Hotel/app/views/includes/footer.php'; ?>