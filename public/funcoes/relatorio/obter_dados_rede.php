<?php

include('../../conexoes/conexao.php');

$sql = "SELECT 
            IFNULL(baia.baia,'') AS baia , 
            IFNULL(pach_panel,'') AS pach_panel,
            IFNULL(switch_host,'') AS switch_host,
            IFNULL(switch_ip,'') AS switch_ip,
            IFNULL(switch_porta,'') AS switch_porta
        FROM archerx.mapa_rede_caixa
            INNER JOIN archerx.baia ON baia.id = archerx.mapa_rede_caixa.id_baia";

$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);

?>