<?php

include('../conexoes/conexao_mssql.php');

function update($sql)
{

 try{
    
    $conn = conect_gerenciador();
    $statement = $conn->prepare($sql);
    $statement->execute();

 } catch(PDOException $e) {

    // Captura a exceção e exibe a mensagem de erro
    echo "Erro: " . $e->getMessage() . PHP_EOL;
    echo "Código do erro: " . $e->getCode() . PHP_EOL;
    // Extrai a mensagem de erro do PDOStatement e exibe
    $errorInfo = $statement->errorInfo();
    echo "Mensagem de erro do MSSQL: " . $errorInfo[2] . PHP_EOL;

    $retorno = ['erro' => True, 'dados' => $errorInfo];
    echo json_encode($retorno);
 };

    stop($conn);
    return $retorno;

}

$dados = filter_input_array(INPUT_GET);

$dados = explode(',', $dados['dados_head']);

// separar as varáveis

$matricula = $dados[0];
$head_novo = $dados[1];
$chamado = $dados[2];
$motivo = $dados[3];
$desconto = $dados[4];
$head_atual = $dados[5];
$login = $_COOKIE['login'];




if ($motivo == "troca"){
    
    $motivo = 2;
    if ($desconto == ""){
        $desconto = "NULL";
    }
    if ($head_atual == ""){
        $head_atual = "NULL";
    }

    # Setar a posse do head  novo
    $sql_head = "UPDATE Headsets SET UltPosse=EmPosse,EmPosse=$matricula,Estoque=0,Manutencao=0,Inativo=0 WHERE Lacre=$head_novo";
    Update($sql_head);
    #$sql_troca = "INSERT INTO Headsets_trocas (Id_motivo,Id_headset_antigo,Id_headset_novo,Id_funcionario,id_tecnico,Chamado) VALUES ($motivo, (SELECT Id FROM Headsets WHERE lacre=$head_atual), (SELECT id FROM Headsets WHERE Lacre=$head_novo), $matricula, $login, $chamado)";
    #Update($sql_troca);
    $sql_funcionario = "UPDATE Funcionarios SET RecebeuHead = 1,HeadDevolvido = 0,Id_headset_antigo = Id_headset,Id_headset = (SELECT id FROM Headsets WHERE Lacre = $head_novo) WHERE matricula = $matricula";
    Update($sql_funcionario);

    $retorno = ['erro' => False, 'dados' => "UPDATE Realizado com sucesso"];  
    echo json_encode($retorno);

};
if ($motivo == "integracao"){
    
};
if ($motivo == "emprestimo"){
    
};
if ($motivo == "entrega"){
    
};

?>