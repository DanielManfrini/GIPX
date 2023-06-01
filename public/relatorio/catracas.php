<?php

$data = date('Y-m-d');

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <title>RELATÓRIO CATRACAS</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Estilos -->
    <link rel="stylesheet" type="text/css" href="../../css/header-style.css">
    <link rel="stylesheet" type="text/css" href="../../css/relatorio/catracas-style.css">
    <link rel="icon" href="../img/icon.ico">

    <!-- Biblioteca jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Biblioteca jQuery CSS-->
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Biblioteca DataTables -->
    <script type="text/javascript" src="../../bibli/dataTables/datatables.min.js"></script>
    <script src="../../bibli/dataTables/Buttons-2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="../../bibli/dataTables/Buttons-2.3.6/js/buttons.html5.min.js"></script>
    <script src="../../bibli/dataTables/RowGroup-1.3.1/js/dataTables.rowGroup.min.js"></script>
    <script src="../../bibli/dataTables/Buttons-2.3.6/js/buttons.print.min.js"></script>
    <script src="../../bibli/dataTables/pdfmake-0.2.7/pdfmake.min.js"></script>
    <script src="../../bibli/dataTables/pdfmake-0.2.7/vfs_fonts.js"></script>

    <!-- CSS Datatables -->
    <link rel="stylesheet" href="../../bibli/dataTables/Buttons-2.3.6/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="../../bibli/dataTables/datatables.min.css" />

    <!-- Sweet Alert -->
    <link type="text/css" href="../../bibli/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Sweet Alerts 2 -->
    <script src="../../bibli/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="../../bibli/sweetalert/dist/sweetalert.min.js"></script>

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
                <label for="campo_matricula">Matrícula:</label>
                <input type="text" id="campo_matricula">
                <label for="campo_nome">Nome:</label>
                <input type="text" id="campo_nome" style="width: 250px;">
                <label for="data_inicial">Data inicial:</label>
                <input type="date" name="data_inicial" id="data_inicial" value="<?php echo $data ?>">
                <label for="data_final">Data final:</label>
                <input type="date" name="data_final" id="data_final" value="<?php echo $data ?>">
            </form>
            <table id="tabela_catracas">
                <thead>
                    <tr class="header_tabela">
                        <td><span style="font-weight:bold;">Semana</span></td>
                        <td><span style="font-weight:bold;">Dia</span></td>
                        <td><span style="font-weight:bold;">Hora</span></td>
                        <td><span style="font-weight:bold;">Tipo</span></td>
                        <td><span style="font-weight:bold;">Matrícula</span></td>
                        <td><span style="font-weight:bold;">Nome</span></td>
                        <td class="fim_tabela"><span style="font-weight:bold;">Catraca</span></td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            tabela_catracas = $('#tabela_catracas').DataTable({
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json"
                },
                order: [[1, 'asc'], [2, 'asc']], // Define a ordenação padrão na segunda coluna (índice 1) em ordem ascendente
                columns: [
                    { data: 'semana', visible: false },
                    { data: 'data', width: '70px' },
                    { data: 'hora', width: '70px' },
                    { data: 'tipo', width: '100px' },
                    { data: 'matricula', width: '100px' },
                    { data: 'nome', width: '500px' },
                    { data: 'catraca', width: '50px' },
                ],
                rowGroup: {
                    dataSrc: 'semana',
                    render: function (group) {
                        var data = this.api().column('data:name').data()[0];
                        return group + ' - ' + data;
                    }
                },
                buttons: [
                    {
                        extend: 'csv', // Adiciona o botão de exportação CSV
                        text: 'Exportar CSV', // Texto do botão
                        className: 'btn-export-csv',// Classe personalizada para o botão CSV
                        filename: function () {
                            // Obtém o valor da primeira linha da coluna "nome"
                            var primeiroNome = tabela_catracas.column(4).data()[0];

                            var data_inicial = $('#data_inicial').val()
                            var data_final = $('#data_final').val()

                            // Remove caracteres inválidos para o nome do arquivo
                            primeiroNome = primeiroNome.replace(/[\\/:*?"<>|]/g, '');

                            // Formata o nome do arquivo.
                            var nome_arquivo = 'Relatório ' + primeiroNome + ' de ' + data_inicial + ' à ' + data_final

                            if (data_inicial == data_final) {

                                var nome_arquivo = 'Relatório ' + primeiroNome + ' de ' + data_inicial

                            }

                            return nome_arquivo;
                        },
                        customize: function (csv) {
                            // Formata o CSV em UTF-8
                            csv = "\uFEFF" + csv;

                            // Altera o separador para ";"
                            csv = csv.replace(/,/g, ";");

                            // Remove as aspas das strings
                            csv = csv.replace(/"/g, "");

                            return csv;
                        }
                    },
                    {
                        extend: 'print', // Adiciona o botão de impressão
                        text: 'Impressão', // Texto do botão
                        className: 'btn-imprimir', // Classe CSS para estilização personalizada
                        // Resto das opções personalizadas...
                    }
                ],
                dom: 'lBfrtip', // Especifica a posição dos botões
            });
        });

        // vamos fazzer os capos limparem um ao outro para evitar conflitos
        $('#campo_matricula').on('click', function () { $('#campo_nome').val('') });
        $('#campo_nome').on('click', function () { $('#campo_matricula').val('') });

        $('#campo_matricula').on('keyup', busca_dados);
        $('#campo_nome').on('keyup', busca_dados);
        $('#data_inicial').on('change', busca_dados);
        $('#data_final').on('change', busca_dados);

        function busca_dados(nome) {

            var matricula = $('#campo_matricula').val(); // Pegamos o valor do lacre
            var nome = $('#campo_nome').val();
            var inicio = $('#data_inicial').val();
            var fim = $('#data_final').val();

            console.log('Dados a enviar:', matricula, nome, inicio, fim)

            $.ajax({
                url: '../funcoes/relatorio/busca_catraca.php', // Vamos mandar um POST para retornar os dados
                method: 'POST',
                data: {
                    tipo: 'select',
                    matricula: matricula,
                    nome: nome,
                    inicio: inicio,
                    fim: fim
                },
                success: function (response) {

                    console.log(response)

                    // Limpar os dados existentes na tabela
                    tabela_catracas.clear();

                    // Adicionar os novos dados à tabela
                    tabela_catracas.rows.add(response).draw();

                    //vamos adicionar o nome e a matricula aos campos
                    //var primeiramatricula = tabela_catracas.column(3).data()[0]
                    //var primeiroNome = tabela_catracas.column(4).data()[0]

                    //$('#campo_matricula').val(primeiramatricula)
                    //$('#campo_nome').val(primeiroNome)

                },
                error: function () {
                }
            });
        };
        $("#campo_nome").on('keyup', function () {

            var nome = $('#campo_nome').val()

            $("#campo_nome").autocomplete({
                source: function (request, response) {
                    $.ajax({
                        url: "../funcoes/relatorio/busca_catraca.php",
                        method: 'POST',
                        data: {
                            tipo: 'nomes',
                            nome: nome
                        },
                        success: function (data) {
                            console.log(data)
                            var nomes = []; // Array para armazenar os nomes

                            // Itera sobre os dados recebidos e extrai os nomes
                            for (var i = 0; i < data.length; i++) {
                                nomes.push(data[i].Nome);
                            }

                            response(nomes); // Retorna os nomes para exibição no autocompletar
                        }
                    });
                },
                select: function (event, ui) {
                    var nome = ui.item.value; // Obtém o valor da opção selecionada

                    $('#campo_nome').val(nome)

                    // Executa sua função com o nome selecionado
                    busca_dados();
                }
            });
        });
    </script>
    <div class="footer">
        <h6>Criado e mantido por Daniel Lopes Manfrini: 2023</h6>
    </div>
</body>

</html>