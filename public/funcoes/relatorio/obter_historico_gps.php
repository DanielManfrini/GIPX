<?php include('../../conexoes/conexao.php');


$baia = $_GET['id'];

// Query SQL com JOIN para selecionar os dados necessários
$sql = "SELECT 
            baia.baia, 
            setor.setor,
            ramal_baia.ramal as ramal_dg, 
            hosts.hostname, 
            hosts.serial, 
            monitores.serie,
            qdu.qdu_serial,
            usuarios.nome, 
            historico_gps.data
        FROM historico_gps 
            LEFT JOIN baia ON historico_gps.id_baia = baia.id
            LEFT JOIN ramal_baia  ON historico_gps.id_ramal_baia = ramal_baia.id
            LEFT JOIN hosts ON historico_gps.id_hostname = hosts.id
            LEFT JOIN monitores ON historico_gps.id_monitores = monitores.id
            LEFT JOIN usuarios ON historico_gps.Id_funcionario = usuarios.login
            LEFT JOIN setor ON historico_gps.id_setor = setor.id
            LEFT JOIN qdu ON historico_gps.id_qdu = qdu.id
        WHERE baia.baia = '$baia'";

// Executando a query e armazenando o resultado na variável $result
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
