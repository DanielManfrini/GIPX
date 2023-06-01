<?php

include('../conexoes/conexao_mssql.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') { // Se o tipo de requisição for Get vai executar.

    if ($_GET['tipo'] == "Select_funcionario") {

        $matricula = $_GET['matricula'];
        //$matricula= '125104';

        $query = "WITH CTE_gerenciador AS (
                    SELECT
                        GerenciadorAcessos.dbo.Funcionarios.Matricula,
                        GerenciadorAcessos.dbo.Funcionarios.Nome,
                        GerenciadorAcessos.dbo.Departamentos.Departamento,
                        GerenciadorAcessos.dbo.Funcionarios.Cartao,
                        GerenciadorAcessos.dbo.Funcionarios.Pis,
                        GerenciadorAcessos.dbo.Funcionarios.Situacao,
                        GerenciadorAcessos.dbo.Funcionarios.DataDemissao,
                        GerenciadorAcessos.dbo.Funcionarios.RecebeuHead,
                        GerenciadorAcessos.dbo.Funcionarios.HeadDevolvido,
                        H1.Lacre as Head_novo,
                        H2.Lacre as Head_antigo,
                        H3.Lacre as head_emprestimo
                    
                    FROM GerenciadorAcessos.dbo.Funcionarios
                    
                    LEFT JOIN GerenciadorAcessos.dbo.Departamentos ON Departamentos.id = GerenciadorAcessos.dbo.Funcionarios.Id_departamento
                    LEFT JOIN GerenciadorAcessos.dbo.Headsets H1 ON H1.Id = GerenciadorAcessos.dbo.Funcionarios.Id_headset
                    LEFT JOIN GerenciadorAcessos.dbo.Headsets H2 ON H2.id = GerenciadorAcessos.dbo.Funcionarios.id_headset_antigo
                    LEFT JOIN GerenciadorAcessos.dbo.Headsets H3 ON H3.id = GerenciadorAcessos.dbo.Funcionarios.Id_head_emprestimo
                    
                    WHERE GerenciadorAcessos.dbo.Funcionarios.Matricula = " . $matricula . "
                    
                    ),
                    
                    CTE_catracas AS (
                    SELECT 
                        TopAcesso.dbo.Funcionarios.Matricula,
                        TopAcesso.dbo.Funcionarios.bloqueado,
                        CONVERT(varchar(10), TopAcesso.dbo.Funcionarios.InicioBloqueio,103) AS InicioBloqueio,
                        CONVERT(varchar(10),TopAcesso.dbo.Funcionarios.FimBloqueio,103) AS FimBloqueio,
                        TopAcesso.dbo.CartoesProvisorios.NumeroCartao,
                        CONVERT(varchar(10),TopAcesso.dbo.CartoesProvisorios.Inicio,103) AS Inicio,
                        CONVERT(varchar(10),TopAcesso.dbo.CartoesProvisorios.Fim,103) AS Fim
                    
                    FROM TopAcesso.dbo.Funcionarios
                    LEFT JOIN TopAcesso.dbo.CartoesProvisorios ON TopAcesso.dbo.CartoesProvisorios.COD_PESSOA = TopAcesso.dbo.Funcionarios.COD_PESSOA
                    
                    WHERE TopAcesso.dbo.Funcionarios.Matricula = " . $matricula . "
                    
                    )
                    
                    SELECT 
                            CTE_gerenciador.Matricula,
                            CTE_gerenciador.Nome,
                            CTE_gerenciador.Departamento,
                            CTE_gerenciador.Cartao,
                            CTE_gerenciador.Pis,
                            CTE_gerenciador.Situacao,
                            CTE_gerenciador.DataDemissao,
                            CTE_gerenciador.RecebeuHead,
                            CTE_gerenciador.HeadDevolvido,
                            CTE_gerenciador.Head_novo,
                            CTE_gerenciador.Head_antigo,
                            CTE_gerenciador.head_emprestimo,
                            CTE_catracas.bloqueado,
                            CTE_catracas.InicioBloqueio,
                            CTE_catracas.FimBloqueio,
                            CTE_catracas.NumeroCartao,
                            CTE_catracas.Inicio,
                            CTE_catracas.Fim
                    
                    FROM CTE_gerenciador
                    
                    LEFT JOIN CTE_catracas ON CTE_catracas.Matricula = CTE_gerenciador.Matricula
                    
                    WHERE CTE_gerenciador.Matricula = " . $matricula;

        try {

            $conn = conect_gerenciador();
            $statement = $conn->prepare($query);
            $statement->execute();
            $conn = null;

            $resultado = $statement->fetchAll(PDO::FETCH_ASSOC);
            $resultado = $resultado[0];

            //print_r($resultado);

            if ($resultado != "") {


                //vamos veririficar a situação do funcionario
                if ($resultado['Situacao'] == '1') {

                    $resultado['Situacao'] = 'DEMITIDO';

                } else {

                    $resultado['Situacao'] = 'ATIVO';

                }

                // Verificar a situação do HEadset
                if ($resultado['RecebeuHead'] = 1 and $resultado['HeadDevolvido'] = 0 and $resultado['head_emprestimo'] = '') {

                    $situacao_head = "EM USO";
                    $head = $resultado['Head_novo'];

                } elseif ($resultado['RecebeuHead'] = 1 and $resultado['HeadDevolvido'] = 1 and $resultado['head_emprestimo'] = '') {

                    $situacao_head = "DEVOLVIDO";
                    $head = $resultado['Head_antigo'];

                } elseif ($resultado['head_emprestimo'] = 1) {

                    $situacao_head = "EMPRÉSTIMO";
                    $head = $resultado['head_emprestimo'];

                } else {

                    $situacao_head = "NÃO RECEBEU";
                    $head = '';

                }
                ;

                // verificar a situação do bloqueio
                if ($resultado['bloqueado'] == 1) {

                    $situcao_bloqueado = 'BLOQUEADO';

                } else {

                    $situcao_bloqueado = 'LIBERADO';

                }


                // Formatar a array
                $resultado_fomratado = [
                    'matricula' => $resultado['Matricula'],
                    'nome' => $resultado['Nome'],
                    'departamento' => $resultado['Departamento'],
                    'cartao' => $resultado['Cartao'],
                    'pis' => $resultado['Pis'],
                    'situacao_funcionario' => $resultado['Situacao'],
                    'data_demissao' => $resultado['DataDemissao'],
                    'situacao_head' => $situacao_head,
                    'head' => $head,
                    'bloqueio' => $situcao_bloqueado,
                    'inicio_bloqueio' => $resultado['InicioBloqueio'],
                    'fim_bloqueio' => $resultado['FimBloqueio'],
                    'provisorio' => $resultado['NumeroCartao'],
                    'inicio_provisorio' => $resultado['Inicio'],
                    'fim_provisorio' => $resultado['Fim'],
                ];

                //print_r($resultado_fomratado);

                header('Content-Type: application/json'); // Definimos o cabeçalho e devolvemos os dados ao cliente.
                echo json_encode($resultado_fomratado);
            }

        } catch (PDOException $e) {

            echo "Erro na conexão: " . $e->getMessage();

        }


    }

}

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Se o tipo de requisição for Post vai executar.

}

?>