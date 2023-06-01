<?php

include('../conexoes/conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') { // Se o tipo de requisição for Get vai executar.

    if ($_GET['tipo'] === 'select') { // Vai buscar os dados dos usuários.

        $sql = "SELECT 
                idusuarios,
                IFNULL(login_plansul,'') AS login_plansul, 
                IFNULL(login_caixa,'') AS login_caixa,
                IFNULL(nome,'') AS nome,
                IFNULL(nivel_acesso,'') AS nivel_acesso
            FROM archerx.usuarios_novo
            ORDER BY nome"; // Usando If null para evitar undefined na tabela.

        $result = $conn->query($sql);

        $data = array(); // Declaramos uma array e com um laço inserimos os dados.

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        header('Content-Type: application/json'); // Definimos o cabeçalho e devolvemos os dados ao cliente.
        echo json_encode($data);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Se o tipo de requisição for Post vai executar.

    if ($_POST['tipo'] === 'cadastrar') { // Vai cadastrar o usuário.

        $nome = $_POST['nome']; // Coletamos as variaveis do POST
        $login_plansul = $_POST['login_plansul'];
        $login_caixa = $_POST['login_caixa'];
        $senha = $_POST['senha'];
        $nivel = $_POST['nivel'];

        $sql = "INSERT INTO archerx.usuarios_novo 
                    (login_plansul,login_caixa,senha,nome,nivel_acesso) 
                VALUES 
                    ('" . $login_plansul . "','" . $login_caixa . "','" . $senha . "','" . $nome . "','" . $nivel . "')"; // SQL de inserção.


        try { // Trycatch para trataiva de erro.

            $statement = $conn->prepare($sql);
            $statement->execute();

            $retorno = ['erro' => False];

            $conn->close();

            header('Content-Type: application/json'); // Definimos o cabeçalho e devolvemos os dados ao cliente.
            echo json_encode($retorno);

            die();
        } catch (Exception $e) {

            $retorno = ['erro' => True, 'mensagem' => $e->getMessage()];

            header('Content-Type: application/json'); // Definimos o cabeçalho e devolvemos os dados ao cliente.
            echo json_encode($retorno);

            die();
        }
    }

    if ($_POST['tipo'] === 'atualizar') { // Vai atualizar o usuário.

        $nome = $_POST['nome']; // Coletamos as variaveis do POST
        $login_plansul = $_POST['login_plansul'];
        $login_caixa = $_POST['login_caixa'];
        $senha = $_POST['senha'];
        $nivel = $_POST['nivel'];
        $id = $_POST['id'];

        $sql = "UPDATE archerx.usuarios_novo 
                SET
                    login_plansul = '" . $login_plansul . "',
                    login_caixa = '" . $login_caixa . "',
                    senha = '" . $senha . "',
                    nome = '" . $nome . "',
                    nivel_acesso = '" . $nivel . "'                 
                WHERE idusuarios = '" . $id . "'"; // SQL de update.


        try { // Trycatch para trataiva de erro.

            $statement = $conn->prepare($sql);
            $statement->execute();

            $retorno = ['erro' => False];

            $conn->close();

            header('Content-Type: application/json'); // Definimos o cabeçalho e devolvemos os dados ao cliente.
            echo json_encode($retorno);

            die();
        } catch (Exception $e) {

            $retorno = ['erro' => True, 'mensagem' => $e->getMessage()];

            header('Content-Type: application/json'); // Definimos o cabeçalho e devolvemos os dados ao cliente.
            echo json_encode($retorno);
            
            die();
        }

    }

    if ($_POST['tipo'] === 'excluir') { // Vai excluir o usuário.

        $nome = $_POST['nome']; // Coletamos as variaveis do POST
        $login_plansul = $_POST['login_plansul'];
        $login_caixa = $_POST['login_caixa'];
        $senha = $_POST['senha'];
        $nivel = $_POST['nivel'];
        $id = $_POST['id'];

        $sql = "DELETE FROM archerx.usuarios_novo              
                WHERE idusuarios = '" . $id . "'"; // SQL de exclusao.


        try { // Trycatch para trataiva de erro.

            $statement = $conn->prepare($sql);
            $statement->execute();

            $retorno = ['erro' => False];

            $conn->close();

            header('Content-Type: application/json'); // Definimos o cabeçalho e devolvemos os dados ao cliente.
            echo json_encode($retorno);

            die();
        } catch (Exception $e) {

            $retorno = ['erro' => True, 'mensagem' => $e->getMessage()];

            header('Content-Type: application/json'); // Definimos o cabeçalho e devolvemos os dados ao cliente.
            echo json_encode($retorno);

            die();
        }
    }
}

?>