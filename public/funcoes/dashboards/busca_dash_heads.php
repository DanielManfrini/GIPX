<?php
// pegar o dia
if (!isset($_POST['data_inicial'])){
    $data_inicial = date('Y-m-d');
}else{
    $data_inicial = $_POST['data_inicial'];
}
if (!isset($_POST['data_final'])){
    $data_final = date('Y-m-d');
}else{
    $data_final = $_POST['data_final'];
}
  

include('/archerx/public/conexoes/conexao_mssql.php');

$conexao_gerenciador = conect_gerenciador();

try{ // ATIVOS

    $sql_ativos = "SELECT COUNT(*) as ativos FROM HEADSETS WHERE Estoque=0 AND Inativo = 0 AND Manutencao = 0 AND EmPosse IS NOT NULL";   
    $statement = $conexao_gerenciador->prepare($sql_ativos);
    $statement->execute();

    $resultado_ativos = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    echo "Erro na conexão: " . $e->getMessage();

}

try{ // ESTOQUE

    $sql_estoque = "SELECT COUNT(*) as estoque FROM HEADSETS WHERE Estoque=1 AND Inativo = 0 AND Manutencao = 0 AND Emprestado = 0 AND Treinamento = 0 AND Id_marca != 4";   
    $statement = $conexao_gerenciador->prepare($sql_estoque);
    $statement->execute();

    $resultado_estoque = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    echo "Erro na conexão: " . $e->getMessage();

}

try{ // MANUTENCAO
    $sql_manutençao = "SELECT COUNT(*) as manutencao FROM HEADSETS WHERE Manutencao=1 AND Inativo=0 ";
    $statement = $conexao_gerenciador->prepare($sql_manutençao);
    $statement->execute();

    $resultado_manutencao = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    echo "Erro na conexão: " . $e->getMessage();

}

try{ // TROCAS
    $sql_trocas = "SELECT COUNT(*) as trocas FROM Trocasheadsets WHERE Id_motivo = 2 AND Data BETWEEN '$data_inicial' AND '$data_final'";
    $statement = $conexao_gerenciador->prepare($sql_trocas);
    $statement->execute();

    $resultado_trocas = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    echo "Erro na conexão: " . $e->getMessage();

}

try{// ENTREGAS 
    $sql_entregas = "SELECT COUNT(*) as entregas FROM Trocasheadsets WHERE Id_motivo = 1 AND Id_headset_novo NOT IN (SELECT Id_headset FROM Treinamento) AND Data BETWEEN '$data_inicial' AND '$data_final'";
    $statement = $conexao_gerenciador->prepare($sql_entregas);
    $statement->execute();

    $resultado_entregas = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    echo "Erro na conexão: " . $e->getMessage();

}

try{ // INTEGRACAO 
    $sql_treinamento = "SELECT COUNT(*) as treinamento FROM Treinamento WHERE EmUso = 1 AND Data BETWEEN '$data_inicial' AND '$data_final'";
    $statement = $conexao_gerenciador->prepare($sql_treinamento);
    $statement->execute();

    $resultado_treinamento = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    echo "Erro na conexão: " . $e->getMessage();

}

try{ // EMPRÉSTIMO
    $sql_emprestimo = "SELECT COUNT(*) as emprestimo FROM HEADSETS WHERE Emprestado = 1";
    $statement = $conexao_gerenciador->prepare($sql_emprestimo);
    $statement->execute();

    $resultado_emprestimo = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    echo "Erro na conexão: " . $e->getMessage();

}

try{ // DEVOLVIDOS 
    $sql_devolvidos = "SELECT COUNT(*) as devolvidos FROM Funcionarios WHERE HeadDevolvido = 1 AND DataDemissao BETWEEN '$data_inicial' AND '$data_final'";
    $statement = $conexao_gerenciador->prepare($sql_devolvidos);
    $statement->execute();

    $resultado_devolvidos = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    echo "Erro na conexão: " . $e->getMessage();

}

try{ // DEMISSAO
    $sql_nao_devolvidos = "SELECT COUNT(*) as nao_devolvidos FROM Funcionarios WHERE Situacao = 1 AND RecebeuHead = 1 AND HeadDevolvido = 0 AND DescontoDevolucao = 0  AND DataDemissao BETWEEN '$data_inicial' AND '$data_final'";
    $statement = $conexao_gerenciador->prepare($sql_nao_devolvidos);
    $statement->execute();

    $resultado_nao_devolvidos = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    echo "Erro na conexão: " . $e->getMessage();

}

try{// DESCONTOS POR NAO DEVOLVER
    $sql_descontos_devolucao = "SELECT COUNT(*) AS nao_devolvidos FROM Funcionarios WHERE Situacao = 1 AND RecebeuHead = 1 AND HeadDevolvido = 0 AND DescontoDevolucao = 1 AND DataDemissao BETWEEN '$data_inicial' AND '$data_final'";
    $statement = $conexao_gerenciador->prepare($sql_descontos_devolucao);
    $statement->execute();

    $resultado_descontos_devolucao = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    echo "Erro na conexão: " . $e->getMessage();

}

try{// DESCONTOS
    $sql_descontos = "SELECT COUNT(*) AS descontos FROM Descontos WHERE Data BETWEEN '$data_inicial' AND '$data_final'";
    $statement = $conexao_gerenciador->prepare($sql_descontos);
    $statement->execute();

    $resultado_descontos = $statement->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    echo "Erro na conexão: " . $e->getMessage();

}

$conexao_gerenciador = null;

$dados_estoque_manutencao = array(
    'MANUTENCAO' => $resultado_manutencao[0]['manutencao'],
    'ESTOQUE' => $resultado_estoque[0]['estoque'],
    'ATIVOS' => $resultado_ativos[0]['ativos']
);

$dados_movimentacoes = array(
    'TROCAS' => $resultado_trocas[0]['trocas'],
    'ENTREGAS' => $resultado_entregas[0]['entregas'],
    'INTEGRACAO' => $resultado_treinamento[0]['treinamento'],
    'EMPRESTIMO' => $resultado_emprestimo[0]['emprestimo'] 
);

$dados_resto = array(
    'DEVOLVIDOS' => $resultado_devolvidos[0]['devolvidos'],
    'NAO_DEVOLVIDOS' => $resultado_nao_devolvidos[0]['nao_devolvidos'],
    'DESCONTOS_DEVOLUCAO' => $resultado_descontos_devolucao[0]['nao_devolvidos'],
    'DESCONTOS' => $resultado_emprestimo[0]['emprestimo'] 
);

$dados = array(
    'estoque' => $dados_estoque_manutencao, 
    'movimentacoes' => $dados_movimentacoes,
    'resto' => $dados_resto
);

//echo json_encode($dados);

?>
