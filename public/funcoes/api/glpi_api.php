<?php
$login = $_COOKIE['login_glpi'];
$password = $_COOKIE['senha_glpi'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Se o tipo de requisição for Post vai executar.

    if ($_POST['tipo'] === 'login') { // Vai verificar o loguin.

        // Iniciar a sessão para obter o token de autenticação
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://172.10.20.53/glpi/apirest.php/initSession');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($login . ':' . $password),
                'App-Token: RdyPASfcVCEi7qygh1E72LOlYzVr0aQIwflCcZf6'
            )
        );

        $response = curl_exec($ch);
        curl_close($ch);


        if ($response[2] !== "E") {

            $retorno = ['erro' => False];

            header('Content-Type: application/json'); // Definimos o cabeçalho e devolvemos os dados ao cliente.
            echo json_encode($retorno);

            die();

        } else {

            // Lidar com erros na chamada curl
            $retorno = ['erro' => True, 'mensagem' => 'Login ou senha incorretos!'];

            header('Content-Type: application/json'); // Definimos o cabeçalho e devolvemos os dados ao cliente.
            echo json_encode($retorno);

            die();

        }

    }
}

if ($_POST['tipo'] === 'chamado') { // Vai registrar o chamado.

    $pontos = "";

    if ($_POST['hora_entrada'] != ''){
        $pontos = $pontos." Entrada: ".$_POST['hora_entrada'];
    }
    if ($_POST['hora_ida_lanche'] != ''){
        $pontos = $pontos." Saída para lanche: ".$_POST['hora_ida_lanche'];
    }
    if ($_POST['hora_volta_lanche'] != ''){
        $pontos = $pontos." Volta do lanche: ".$_POST['hora_volta_lanche'];
    }
    if ($_POST['hora_saida'] != ''){
        $pontos = $pontos." Saída: ".$_POST['hora_saida'];
    }

    $nome_chamado = $_POST['matricula'] . " + " . $_POST['nome'] . " + " . $_POST['data'];

    $descricao_chamado = "
        Favor realizar a correção na folha ponto do colaborador(a) abaixo:

        Matrícula Plansul do Colaborador: " . $_POST['matricula'] . "

        Nome do Colaborador: " . $_POST['nome'] . "

        Ação: " . $_POST['acao'] . "

        Data correção: " . $_POST['data'] . "

        Horário: " . $pontos . "

        Horário do colaborador: " . $_POST['escala'] . "

        Justificativa: " . $_POST['justificativa'];

    //echo json_encode($pontos);

    // Iniciar a sessão para obter o token de autenticação
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://172.10.20.53/glpi/apirest.php/initSession');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($login . ':' . $password),
            'App-Token: RdyPASfcVCEi7qygh1E72LOlYzVr0aQIwflCcZf6'
        )
    );
    $response = curl_exec($ch);
    curl_close($ch);

    //echo ($response . PHP_EOL);

    // Verificar se a chamada foi bem-sucedida e obter o token de autenticação
    if ($response !== false) {
        $data = json_decode($response, true);
        $sessionToken = $data['session_token'];
        //echo ($sessionToken . PHP_EOL);

        // Usar o token de autenticação para criar um ticket
        $ticketData = array(
            'input' => array(
                'name' => $nome_chamado,
                'content' => $descricao_chamado,
                'priority' => '3',
                'impact' => '3',
                'urgency' => '3',
                'type' => '1',
                'itilcategories_id' => '1'
            )
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://172.10.20.53/glpi/apirest.php/Ticket/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Session-Token: ' . $sessionToken,
                'App-Token: RdyPASfcVCEi7qygh1E72LOlYzVr0aQIwflCcZf6'
            )
        );
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ticketData));
        $response = curl_exec($ch);
        curl_close($ch);

        // Verificar se a chamada foi bem-sucedida
        if ($response !== false) {
            // Processar a resposta da criação do ticket
            $ticketResponse = json_decode($response, true);
            echo $response;
        } else {
            // Lidar com erros na chamada curl
            echo 'Erro ao criar o ticket: ' . curl_error($ch);
        }
    } else {
        // Lidar com erros na chamada curl
        echo 'Erro ao iniciar a sessão: ' . curl_error($ch);
    }
}

?>