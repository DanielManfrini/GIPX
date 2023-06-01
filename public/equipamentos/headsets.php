<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <title>HEADSETS</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Sweet Alert -->
    <link type="text/css" href="../../bibli/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Sweet Alerts 2 -->
    <script src="../../bibli/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../../bibli/sweetalert/dist/sweetalert.min.js"></script>

    <!-- ESTILOS -->
    <link rel="stylesheet" type="text/css" href="/archerx/css/header-style.css">
    <link rel="stylesheet" type="text/css" href="/archerx/css/equipamentos/headsets-style.css">
    <link rel="icon" href="/archerx/public/img/icon.ico">

    <!-- BIBLIOTECAS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php

    if (!isset($_COOKIE['login'])) {

        // Armazena o caminho atual na sessão
        session_start();
        $_SESSION['caminho_atual'] = $_SERVER['REQUEST_URI'];

        echo "<script> 
swal({
    title: 'Erro!',
    text: 'Você não está logado.',
    icon: 'error',
    button: false,
    timer: 1500
  }).then(function() {
    window.location.href = '/archerx/public/login.php';
  });
</script>";
        exit;
    }
    include('../includes/navbar.php');

    ?>
    <div class="container">
        <div class="container_interno">
            <form action="#">
                <h3>Headset</h3>
                <p>
                    <label for="lacre">Lacre:</label>
                    <input type="text" name="lacre" id="lacre">
                    <button>Buscar</button>
                </p>
            </form action="#">
            <form>
                <h3>Dados</h3>
                <div class="container_informacoes">
                    <label for="posse">Em posse de:</label>
                    <input type="text" name="posse" id="posse">
                    <label for="marca">Marca:</label>
                    <select name="marca" id="marca">
                        <option value=""></option>
                        <option value="PLANTRONICS">PLANTRONICS</option>
                        <option value="INTELBRAS">INTELBRAS</option>
                    </select>
                    <label for="fornecedor">Fornecedor:</label>
                    <select name="fornecedor" id="fornecedor">
                        <option value=""></option>
                        <option value="LYRA">LYRA</option>
                        <option value="TELE7">TELE7</option>
                        <option value="SEM">SEM</option>
                    </select>
                    <label for="situacao">Situação</label>
                    <input type="text" name="situacao" id="situacao">
                </div>
                <p class="buttons">
                    <button>Cadastrar</button>
                    <button>Atualizar</button>
                    <button>Excluir</button>
                </p>
            </form>
            <form action="#">
                <h3>Manutenção</h3>
                <div class="container_informacoes">
                    <label for="defeito">Defeito:</label>
                    <select name="defeito" id="defeito">
                        <option value=""></option>
                        <option value="cabo">CABO</option>
                        <option value="conector">CONECTOR</option>
                        <option value="quebrado">QUEBRADO</option>
                        <option value="SEM SOM"></option>
                    </select>
                </div>
                <p class="buttons">
                    <button>Registrar</button>
                    <button>Remover</button>
                </P>
            </form>
            <form action="#">
                <h3>Garantia</h3>
                <div class="container_informacoes">
                    <label for="estado_garantia">Garantia:</label>
                    <input type="text" name="estado_garantia" id="estado_garantia" state="readonly">
                    <label for="data_inicial">Data inicial:</label>
                    <input type="date" name="data_inicial" id="data_inicial">
                    <label for="data_final">Data final</label>
                    <input type="date" name="data_final" id="data_final">
                </div>
                <p class="buttons">
                    <button>Registrar</button>
                    <button>Remover</button>
                </p>
            </form>
        </div>
    </div>
    <div class="footer">
        <h6>Criado e mantido por Daniel Lopes Manfrini: 2023</h6>
    </div>
</body>
<script>
    $('#lacre').on('keyup', function() { //Função para buscar as informaçoes do Headset
        var lacre = $(this).val(); // Pegamos o valor do lacre
        $.ajax({
            url: '/archerx/public/funcoes/headsets/equipamento_busca_head.php', // Vamos mandar um POST para retornar os dados
            method: 'POST',
            data: {
                lacre: lacre
            },
            success: function(response) {
                // Manipule a resposta do servidor aqui e preencha os valores dos elementos do formulário
                console.log(response)

                // preencher o valor do nome
                $('#posse').val(response[0].nome);

                if (response[0].Estoque == '1') {
                    $('#situacao').val('ESTOQUE')
                } else if (response[0].Inativo == '1') {
                    $('#situacao').val('INATIVO')
                } else if (response[0].Manutencao == '1') {
                    $('#situacao').val('MANUTENÇÃO')
                } else if (response[0].Treinamento == '1') {
                    $('#situacao').val('TREINAMENTO')
                } else {
                    $('#situacao').val('EM USO')
                }

                document.getElementById('marca').value = response[0].Marca
                document.getElementById('fornecedor').value = response[0].Vendedor

                if (response[0].Defeito != null) {
                    document.getElementById('defeito').value = response[0].Defeito
                }

                if (response[0].Fim != null) {
                    var dataAtual = new Date();
                    var dataComparacao = new Date(response[0].Fim);
                    console.log(dataAtual, dataComparacao)

                    if (dataComparacao.getTime() > dataAtual.getTime()) {
                        $('#estado_garantia').val('SIM')
                        document.getElementById('data_inicial').value = response[0].Inicio
                        document.getElementById('data_final').value = response[0].Fim
                    } else {
                        $('#estado_garantia').val('NÃO')
                    }
                } else {
                    $('#estado_garantia').val('NÃO')
                }


            },
            error: function() {
                // Trate os erros na requisição Ajax aqui
                console.error('Não encontrado')
            }
        });
    })
</script>

</html>