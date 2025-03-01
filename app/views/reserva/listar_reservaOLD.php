<div class="container-fluid p-4">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Lista de Reservas</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hóspede</th>
                        <th>CPF</th>
                        <th>Entrada</th>
                        <th>Saída</th>
                        <th>Diárias</th>
                        <th>Motivo</th>
                        <th>Etapa</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($data['reservas'] as $reserva): ?>
                        <tr>
                            <td><?= $reserva['id'] ?></td>
                            <td><?= $reserva['nome_hospede'] ?></td>
                            <td><?= $reserva['cpf'] ?></td>
                            <td><?= date('d/m/Y', strtotime($reserva['data_entrada'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($reserva['data_saida'])) ?></td>
                            <td><?= $reserva['qtd_diarias'] ?></td>
                            <td><?= $reserva['nm_motivo'] ?></td>
                            <td><?= $reserva['nome_etapa'] ?? 'N/A' ?></td>
                            <td><?= $reserva['status_etapa'] ?? 'N/A' ?></td>
                            <td>
                                <a href="/Trae/reserva/editar/<?= $reserva['id'] ?>" class="btn btn-sm btn-primary">Editar</a>
                                <a href="/Trae/reserva/visualizar/<?= $reserva['id'] ?>" class="btn btn-sm btn-info">Ver</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>