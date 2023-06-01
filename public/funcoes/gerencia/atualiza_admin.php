<?php

include('../../conexoes/conexao.php');

function select_mysql($sql,$conn){

    $resultado = $conn->query($sql);
    
    return $resultado;
}
function update_mysql($sql,$conn){
    
    if ($conn->query($sql)){
        $result='sucesso';
    }else{
        $result='erro: '. mysqli_error($conn);
    }

    return $result;
}

// Receber dados
$dados = filter_input_array(INPUT_GET);

if(isset($dados['dados_atualizar'])){
    $dados = explode(',', $dados['dados_atualizar']);

    // separar as varáveis

    $id = $dados[0];
    $nome = $dados[1];
    $login = $dados[2];
    $senha = $dados[3];
    $nivel = $dados[4];

    $sql = "UPDATE archerx.usuarios
            SET
                login = '".$login."',
                senha = '".$senha."',
                nome = '".$nome."',
                admin = '".$nivel."'

            WHERE idusuarios = '".$id."'";
    
    $statement = $conn->prepare($sql);
    if($statement->execute()){
        $retorno = ['erro' => False];
    }else{
        $retorno = ['erro' => True];
    };

    $conn->close();
    echo json_encode($retorno);
    die();
}

if(isset($dados['dados_exclusao'])){
    $dados = explode(',', $dados['dados_exclusao']);

    // separar as varáveis

    $id = $dados[0];

    $sql = "DELETE FROM archerx.usuarios WHERE idusuarios = '".$id."'";
    
    $statement = $conn->prepare($sql);
    if($statement->execute()){
        $retorno = ['erro' => False];
    }else{
        $retorno = ['erro' => True];
    };

    $conn->close();
    echo json_encode($retorno);
    die();
}

if(isset($dados['dados_cadastro'])){
    $dados = explode(',', $dados['dados_cadastro']);

    // separar as varáveis

    $nome = $dados[0];
    $login = $dados[1];
    $senha = $dados[2];
    $nivel = $dados[3];

    $sql = "INSERT INTO archerx.usuarios (login,senha,nome,admin) VALUES ('".$login."','".$senha."','".$nome."',".$nivel.")";
    
    $statement = $conn->prepare($sql);
    if($statement->execute()){
        $retorno = ['erro' => False];
    }else{
        $retorno = ['erro' => True];
    };

    $conn->close();
    echo json_encode($retorno);
    die();
}
