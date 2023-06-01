<?php

include('../../conexoes/conexao.php');

function select_mysql($sql, $conn)
{

    $resultado = $conn->query($sql);

    return $resultado;
}
function update_mysql($sql, $conn)
{

    if ($conn->query($sql)) {
        $result = 'sucesso';
    } else {
        $result = 'erro: ' . mysqli_error($conn);
    }

    return $result;
}

if ($_POST['tipo'] == 'gps') {


    $baia = $_POST['baia'];
    $setor = $_POST['setor'];
    $ramal = $_POST['ramal'];
    $serial = $_POST['serial'];
    $serie = $_POST['serie'];
    $qdu_serial = $_POST['qdu'];
    $funcionario = $_COOKIE['login'];

    // vamos verificar primeiramente se os dados estão divergentes.
    $old_data = "SELECT B.id, B.baia, C.serial, C.hostname, D.qdu_serial, E.ramal, F.ramal as ramal_dg, G.serie, H.setor
                FROM archerx.acs_uni as A
                LEFT JOIN archerx.baia as B
                ON A.id_baia = B.id 
                LEFT JOIN archerx.hosts as C
                ON A.id_hostname = C.id
                LEFT JOIN archerx.qdu as D
                ON A.id_qdu = D.id
                LEFT JOIN archerx.ramal_baia as F
                ON A.id_ramal_baia = F.id
                LEFT JOIN archerx.monitores as G
                ON A.id_monitores = G.id
                LEFT JOIN archerx.setor as H
                ON A.id_setor = H.id
                LEFT JOIN archerx.ramal as E
                ON A.id_ramal = E.id where B.baia = '$baia'";

    $result = mysqli_fetch_assoc(select_mysql($old_data, $conn));

    $retorno = ['erro' => True, 'msg' => "não houve nada"];

    // SETOR
    if ($setor != $result['setor']) {
        if ($setor == "VAZIO") {

            $query_update_setor = "UPDATE archerx.acs_uni SET id_setor = NULL WHERE id_baia = '" . $result['id'] . "'";
            if (update_mysql($query_update_setor, $conn) == 'sucesso') {
                $retorno = ['erro' => False];
            } else {
                $retorno = ['erro' => True];
            }

        } else {
            $query_update_setor = "UPDATE archerx.acs_uni 
                                    SET id_setor = (SELECT id FROM setor WHERE setor LIKE '$setor') 
                                    WHERE id_baia = '" . $result['id'] . "'";
            if (update_mysql($query_update_setor, $conn) == 'sucesso') {
                $retorno = ['erro' => False];
            } else {
                $retorno = ['erro' => True, 'msg' => "Erro ao cadastrar o setor!"];
            }
        }
    }

    // RAMAL
    if ($ramal != $result['ramal']) {
        // PRIMEIRO VERIFICA SE HOUVE ALTERAÇÃO
        // DEPOIS VERIFICA SE O VALOR É NULO 
        if ($ramal != "") {
            // vamos verificar se o ramal existe no banco 
            $query_select_ramal = "SELECT id FROM archerx.ramal_baia WHERE ramal='$ramal'";
            $novo_ramal = mysqli_fetch_assoc(select_mysql($query_select_ramal, $conn));

            if ($novo_ramal == "") {
                // ccomo não existe, vamos realizar o insert antes do update.
                $query_insert_ramal = "INSERT INTO archerx.ramal_baia (ramal,tipo,servidor) VALUES ('$ramal','geral',0)";
                if (update_mysql($query_insert_ramal, $conn) == 'sucesso') {

                    $query_update_ramal = "UPDATE archerx.acs_uni SET id_ramal_baia = (SELECT id FROM ramal_baia WHERE ramal ='$ramal') WHERE id_baia = '" . $result['id'] . "'";
                    if (update_mysql($query_update_ramal, $conn) == 'sucesso') {
                        $retorno = ['erro' => False];
                    } else {
                        $retorno = ['erro' => True, 'msg' => "Erro ao cadastrar o ramal!"];
                    }
                }
            } else {
                // Como existe vamos verificar se já não está asociado antes do update.
                $query_count_ramal = "SELECT count(*) FROM archerx.acs_uni WHERE id_ramal_baia = (SELECT id FROM ramal_baia WHERE ramal = '" . $ramal . "') and id_baia NOT IN (SELECT id FROM archerx.baia WHERE baia = '" . $baia . "')";
                $count_ramal = mysqli_fetch_row(select_mysql($query_count_ramal, $conn));
                if ($count_ramal[0] >= 1) {

                    $data[] = ['erro' => True, 'msg' => "Ramal cadastrado em outra baia!"];

                } else {
                    $query_update_ramal = "UPDATE archerx.acs_uni SET id_ramal_baia = (SELECT id FROM ramal_baia WHERE ramal = '" . $ramal . "') WHERE id_baia = '" . $result['id'] . "'";
                    if (update_mysql($query_update_ramal, $conn) == 'sucesso') {

                        $retorno = ['erro' => False];

                    }
                }
            }
        } else {
            // VAI SETAR NULO
            $query_update_ramal = "UPDATE archerx.acs_uni SET id_ramal_baia = NULL WHERE id_baia = '" . $result['id'] . "'";
            if (update_mysql($query_update_ramal, $conn) == 'sucesso') {

                $retorno = ['erro' => False];

            }
        }
    }

    // SERIAL E HOSTNAME
    if ($serial != $result['serial']) {
        // PRIMEIRO VERIFICA SE HOUVE ALTERAÇÃO
        // DEPOIS VERIFICA SE O VALOR É NULO 
        if ($serial != "") {
            // SE NÃO FOR NULO VAI BUSCAR SE O SERIAL EXISTE
            $query_select_serial = "SELECT id FROM archerx.hosts WHERE serial = '" . $serial . "'";
            $novo_serial = mysqli_fetch_assoc(select_mysql($query_select_serial, $conn));
            if ($novo_serial == "") {
                $retorno = ['erro' => True, 'msg' => "Serial não localizado!"];
            } else {
                // VAI VERIFICAR SE O VALOR NÂO ESTÁ DUPLICADO
                $query_count_serial = "SELECT count(*) FROM archerx.acs_uni WHERE id_hostname = '" . $novo_serial['id'] . "' ";
                $count_serial = mysqli_fetch_row(select_mysql($query_count_serial, $conn));
                if ($count_serial[0] >= 1) {

                    $retorno = ['erro' => True, 'msg' => "Serial cadastrado em outra baia!"];

                } else {
                    // SE PASSAR NOS TESTES ATUALIZA
                    $query_update_serial = "UPDATE archerx.acs_uni SET id_hostname = '" . $novo_serial['id'] . "' WHERE id_baia = '" . $result['id'] . "'";
                    if (update_mysql($query_update_serial, $conn) == 'sucesso') {

                        $retorno = ['erro' => False];

                    }
                }
            }
        } else {
            // VAI SETAR NULO
            $query_update_serial = "UPDATE archerx.acs_uni SET id_hostname = NULL WHERE id_baia = '" . $result['id'] . "'";
            if (update_mysql($query_update_serial, $conn) == 'sucesso') {

                $retorno = ['erro' => False];

            }
        }
    }

    // MONITOR
    if ($serie != $result['serie']) {
        // PRIMEIRO VERIFICA SE HOUVE ALTERAÇÃO
        // DEPOIS VERIFICA SE O VALOR É NULO 
        if ($serie != "") {
            $query_select_monitor = "SELECT id FROM archerx.monitores WHERE serie= '" . $serie . "'";
            $novo_monitor = mysqli_fetch_assoc(select_mysql($query_select_monitor, $conn));
            if ($novo_monitor == "") {

                $retorno = ['erro' => True, 'msg' => "Monitor não localizado!"];

            } else {
                $query_count_monitor = "SELECT count(*) FROM archerx.acs_uni WHERE id_monitores = '" . $novo_monitor['id'] . "'";
                $count_monitor = mysqli_fetch_row(select_mysql($query_count_monitor, $conn));
                if ($count_monitor[0] >= 1) {

                    $retorno = ['erro' => True, 'msg' => "Monitor associado em outra baia!"];

                } else {
                    $query_update_monitor = "UPDATE archerx.acs_uni SET id_monitores = (SELECT id FROM monitores WHERE serie = '" . $serie . "') WHERE id_baia = '" . $result['id'] . "'";
                    if (update_mysql($query_update_monitor, $conn) == 'sucesso') {

                        $retorno = ['erro' => False];

                    }
                }
            }
        } else {
            // VAI SETAR NULO
            $query_update_monitor = "UPDATE archerx.acs_uni SET id_monitores = NULL WHERE id_baia = '" . $result['id'] . "'";
            if (update_mysql($query_update_monitor, $conn) == 'sucesso') {

                $retorno = ['erro' => False];

            }
        }
    }

    // CABO QD
    if ($qdu_serial != $result['qdu_serial']) {
        // PRIMEIRO VERIFICA SE HOUVE ALTERAÇÃO
        // DEPOIS VERIFICA SE O VALOR É NULO 
        if ($qdu_serial != "") {
            $query_select_qd = "SELECT id FROM archerx.qdu WHERE qdu_serial = '" . $qdu_serial . "'";
            $novo_qd = mysqli_fetch_assoc(select_mysql($query_select_qd, $conn));
            if ($novo_qd == "") {

                $retorno = ['erro' => True, 'msg' => "Cabo QD não localizado!"];

            } else {
                $query_count_qd = "SELECT count(*) FROM archerx.acs_uni WHERE id_qdu = '" . $novo_qd['id'] . "'";
                $count_qd = mysqli_fetch_row(select_mysql($query_count_qd, $conn));
                if ($count_qd[0] >= 1) {

                    $retorno = ['erro' => True, 'msg' => "Cabo QD associado em outra baia!"];

                } else {
                    $query_update_qd = "UPDATE archerx.acs_uni SET id_qdu = (SELECT id FROM qdu WHERE qdu_serial ='" . $qdu_serial . "') WHERE id_baia ='" . $result['id'] . "'";
                    if (update_mysql($query_update_qd, $conn) == 'sucesso') {

                        $retorno = ['erro' => False];

                    }
                }
            }
        } else {
            // VAI SETAR NULO
            $query_update_qd = "UPDATE archerx.acs_uni SET id_qdu = NULL WHERE id_baia = '" . $result['id'] . "'";
            if (update_mysql($query_update_qd, $conn) == 'sucesso') {

                $retorno = ['erro' => False];

            }
        }
    }

    $query_update_funcionario = "UPDATE archerx.acs_uni SET Id_funcionario = $funcionario, data=NOW() WHERE id_baia = '" . $result['id'] . "'";
    update_mysql($query_update_funcionario, $conn);

    // VAMOS REGISTRAR O HISTÓRICO

    $query_update_historico = "INSERT INTO `archerx`.`historico_gps` (`id_baia`,`id_ramal`,`id_ramal_baia`,`id_hostname`,`id_setor`,`id_monitores`,`id_qdu`,`Id_funcionario`,`data`) SELECT `acs_uni`.`id_baia`, `acs_uni`.`id_ramal`, `acs_uni`.`id_ramal_baia`, `acs_uni`.`id_hostname`, `acs_uni`.`id_setor`, `acs_uni`.`id_monitores`, `acs_uni`.`id_qdu`, `acs_uni`.`Id_funcionario`, `acs_uni`.`data` FROM `archerx`.`acs_uni` WHERE id_baia =" . $result['id'];
    update_mysql($query_update_historico, $conn);

    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($retorno);
    die();

}

if ($_POST['tipo'] == 'rede') {

    $dados = explode(',', $dados['dados_rede']);

    $baia = $_POST['baia'];
    $pach = $_POST['pach_panel'];
    $host = $_POST['switch_host'];
    $ip = $_POST['switch_ip'];
    $porta = $_POST['switch_porta'];


    $sql = "UPDATE archerx.mapa_rede_caixa
            SET
                pach_panel = '" . $pach . "',
                switch_host = '" . $host . "',
                switch_ip = '" . $ip . "',
                switch_porta = '" . $porta . "'

            WHERE id_baia = (SELECT id FROM archerx.baia WHERE baia = '" . $baia . "')";

    $statement = $conn->prepare($sql);
    if ($statement->execute()) {
        $retorno = ['erro' => False];
    } else {
        $retorno = ['erro' => True];
    }
    ;

    $conn->close();
    echo json_encode($retorno);
    die();
}

if ($_POST['tipo'] == 'ip') {

    $ip = $_POST['ip'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $status = $_POST['status'];

    if ($status == "NÃO"){ $status = 0; } elseif($status == "SIM"){ $status = 1; };

    $sql = "UPDATE archerx.mapa_tabela_ip
            SET
                nome = '" . $nome . "',
                descricao = '" . $descricao . "',
                status = " . $status . "

            WHERE Ip = '".$ip."'";

    try {

        $statement = $conn->prepare($sql);
        $statement->execute();
        
        $retorno = ['erro' => False];

        $conn->close();
        echo json_encode($retorno);
        die();
    } catch (Exception $e) {

        $retorno = ['erro' => True, 'mensagem' => $e->getMessage()];
        echo json_encode($retorno);
        die();
    }
}
?>