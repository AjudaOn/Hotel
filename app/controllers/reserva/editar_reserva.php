<?php
session_start();
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/Hotel');
require_once ROOT_PATH . '/app/config/database.php';

$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

$query = "SELECT 
    a.id AS acompanhante_id,
    a.nm_acomp,
    a.idade_acomp,
    a.sexo_id AS acompanhante_sexo_id,
    a.vinculo_familiar_id,
    s.nm_sexo AS acompanhante_sexo_descricao,
    v.nm_vinculo AS vinculo_descricao
FROM 
    acompanhantes a
LEFT JOIN 
    sexo s ON a.sexo_id = s.id
LEFT JOIN 
    vinculo_familiar v ON a.vinculo_familiar_id = v.id
WHERE 
    a.reserva_id = 10";

$result = $db->query($query);

echo "<h4>Resultado da Query:</h4>";
echo "<pre>";
while ($row = $result->fetch_assoc()) {
    print_r($row);
}
echo "</pre>";

$db->close();