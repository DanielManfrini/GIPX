<?php
// Importadores
include('../../conexoes/conexao.php');

if (isset($_POST['action']) && $_POST['action'] === 'gps') {
    // Criar arquivo CSV temporário
    $csv_file = tempnam(sys_get_temp_dir(), "csv");

    // Abrir ponteiro para escrita
    $file_pointer = fopen($csv_file, 'w');

    // Escrever cabeçalho do CSV
    $header_row = array("BAIA","SETOR","RAMAL","HOSTNAME","SERIE","MONITOR","CABO QD");
    fputs($file_pointer, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
    fputcsv($file_pointer, $header_row, ";");

    // Escrever dados do CSV
    $sql = "SELECT B.baia, C.setor, D.ramal, E.hostname, E.serial, F.serie, G.qdu_serial  
            FROM archerx.acs_uni as A
            LEFT JOIN archerx.baia as B
            ON A.id_baia = B.id
            LEFT JOIN archerx.setor as C
            ON A.id_setor = C.id
            LEFT JOIN archerx.ramal_baia as D
            ON A.id_ramal_baia = D.id 
            LEFT JOIN archerx.hosts as E
            ON A.id_hostname = E.id
            LEFT JOIN archerx.monitores as F
            ON A.id_monitores = F.id
            LEFT JOIN archerx.qdu as G
            ON A.id_qdu = G.id";

    $result = $conn->query($sql);

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($file_pointer, $row, ";");
        
    }

    // Fechar ponteiro do arquivo
    fclose($file_pointer);

    // Enviar arquivo CSV para o navegador
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="GPS.csv"');
    header('Pragma: no-cache');
    readfile($csv_file);

    // Deletar arquivo CSV temporário
    unlink($csv_file);
}

if (isset($_POST['action']) && $_POST['action'] === 'rede'){
    // Criar arquivo CSV temporário
    $csv_file = tempnam(sys_get_temp_dir(), "csv");

    // Abrir ponteiro para escrita
    $file_pointer = fopen($csv_file, 'w');

    // Escrever cabeçalho do CSV
    $header_row = array("BAIA","PATCH PANEL","SWITCH HOST","SWICTH IP","SWITCH PORTA");
    fputs($file_pointer, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));
    fputcsv($file_pointer, $header_row, ";");

    // Escrever dados do CSV
    $sql = "SELECT  baia.baia,
                    pach_panel,
                    switch_host,
                    switch_ip,
                    switch_porta
            FROM archerx.mapa_rede_caixa
            INNER JOIN archerx.baia ON baia.id = archerx.mapa_rede_caixa.id_baia;";

    $result = $conn->query($sql);

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($file_pointer, $row, ";");
        
    }

    // Fechar ponteiro do arquivo
    fclose($file_pointer);

    // Enviar arquivo CSV para o navegador
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="REDE.csv"');
    header('Pragma: no-cache');
    readfile($csv_file);

    // Deletar arquivo CSV temporário
    unlink($csv_file);
}
?>