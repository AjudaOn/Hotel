// ... código existente ...

// Capturar o valor da observação do formulário
$observacao = isset($_POST['observacao']) ? $_POST['observacao'] : '';

// ... código existente ...

// Na parte onde insere ou atualiza o hóspede
$sql = "INSERT INTO hospedes (reserva_id, nome, cpf, email, telefone, idade, municipio_id, uf_id, 
        graduacao_id, status_hospede_id, tipo_hospede_id, sexo_id, pet_hospede, 
        necessidades_especiais, obs_hospede) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issssiiiiiiiiis", $reserva_id, $nome_hospede, $cpf, $email, $telefone, 
                 $idade, $municipio_id, $uf_id, $graduacao_id, $status_hospede_id, 
                 $tipo_hospede_id, $sexo_id, $pet_hospede, $necessidades_especiais, $observacao);

// ... código existente ...