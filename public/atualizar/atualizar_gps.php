<!DOCTYPE html>
<html lang="pt-BR">

<head>

  <title>ATUALIZAR GPS</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Estilo -->
  <link rel="stylesheet" type="text/css" href="/archerx/css/header-style.css">
  <link rel="stylesheet" type="text/css" href="/archerx/css/atualizar/atualizar_gps-style.css">
  <link rel="icon" href="/archerx/public/img/icon.ico">

  <!-- Biblioteca jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

  <!-- Biblioteca DataTables -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" />
  <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>-->

  <!-- Sweet Alert -->
  <link type="text/css" href="/archerx/bibli/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

  <!-- Sweet Alerts 2 -->
  <script src="/archerx/bibli/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script src="/archerx/bibli/sweetalert/dist/sweetalert.min.js"></script>

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
      <table id="tabela_gps">
        <thead>
          <tr class="header_tabela">
            <th>BAIA</th>
            <th>SETOR</th>
            <th>RAMAL</th>
            <th>HOSTNAME</th>
            <th>SERIAL</th>
            <th>MONITOR</th>
            <th>QDU</th>
            <th>AÇÃO</th> <!-- Cabeçalho adicionado -->
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <script>
        $(document).ready(function () {
          tabelaGPS = $('#tabela_gps').DataTable({
            "language": {
              "url": "//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json",
            },
            columns: [
              {
                data: 'baia',
              },
              {
                data: 'setor',
                render: function (data, type, row, meta) {
                  return '<select name="setor" class="select-setor" ><option selected="' + data + '">' + data + '</option>; <option value="VAZIO">VAZIO</option>; <option value="OPERAÇÃO">OPERAÇÃO</option>; <option value="SUPERVISÃO">SUPERVISÃO</option>; <option value="WYNTECH">WYNTECH</option>; <option value="SUPORTE DE TI">SUPORTE DE TI</option>; <option value="SESMT">SESMT</option>; <option value="RECURSOS HUMANOS">RH</option>; <option value="TREINAMENTO">TREINAMENTO</option>; <option value="GERÊNCIA">GERÊNCIA</option>; <option value="CÉLULA BABY">CÉLULA BABY</option>; <option value="DESENVOLVIMENTO">DESENVOLVIMENTO</option>; <option value="COORDENAÇÃO">COORDENAÇÃO</option>; <option value="TRÁFEGO">TRÁFEGO</option>; <option value="MONITORIA">MONITORIA</option>; <option value="RETAGUARDA">RETAGUARDA</option>; <option value="DATA CENTER">DATA CENTER</option>; <option value="SALA CAIXA">SALA CAIXA</option></select';
                }
              },
              {
                data: 'ramal',
                render: function (data, type, row, meta) {
                  return '<input type="text" class="input-ramal" value="' + data + '" style="width: 100px;">';
                }
              },
              {
                data: 'hostname',
                render: function (data, type, row, meta) {
                  return '<input type="text" class="input-hostname" value="' + data + '" style="width: 100px;">';
                }
              },
              {
                data: 'serial',
                render: function (data, type, row, meta) {
                  return '<input type="text" class="input-serial" value="' + data + '" style="width: 100px;">';
                }
              },
              {
                data: 'serie',
                render: function (data, type, row, meta) {
                  return '<input type="text" class="input-serie" value="' + data + '" style="width: 100px;">';
                }
              },
              {
                data: 'qdu_serial',
                render: function (data, type, row, meta) {
                  return '<input type="text" class="input-qdu" value="' + data + '" style="width: 100px;">';
                }
              },
              {
                targets: -1, // Última coluna
                orderable: false, // Não permite ordenação
                className: 'dt-body-center', // Centraliza o conteúdo da coluna
                render: function (data, type, row, meta) {
                  return '<button class="btn-update" data-id="' + row.id + '">Atualizar</button>';
                }
              }
            ],
            ajax: {
              url: '/archerx/public/funcoes/relatorio/obter_dados_gps.php',
              dataSrc: ''
            },
            rowId: 'baia'
          });

          // Evento de clique no botão de atualização
          $('#tabela_gps tbody').on('click', '.btn-update', function () {
            var id = $(this).data('id');
            // as variaveis abaixo são apenas para recolher a baia pois ela não está em um input, apenas na célula
            var tr = $(this).closest('tr');
            var row = tabelaGPS.row(tr);
            var data = row.data();
            var baia = data.baia;
            console.log(baia)
            // Coletar o resto das variáveis
            var ramal = $(this).closest('tr').find('.input-ramal').val();
            var setor = $(this).closest('tr').find('.select-setor').val();
            var hostname = $(this).closest('tr').find('.input-hostname').val();
            var serial = $(this).closest('tr').find('.input-serial').val();
            var serie = $(this).closest('tr').find('.input-serie').val();
            var qdu = $(this).closest('tr').find('.input-qdu').val();
            // Realizar a ação de atualização para o ID correspondente
            // ...
            console.log("Dados a enviar:", baia, ramal, setor, hostname, serial, serie, qdu)
            $.ajax({
              url: '/archerx/public/funcoes/atualizar/atualiza_js.php',
              method: 'POST',
              data: {
                tipo: 'gps',
                baia: baia,
                ramal: ramal,
                setor: setor,
                hostname: hostname,
                serial: serial,
                serie: serie,
                qdu: qdu
              },
              success: async function (response) {
                console.log(response)
                console.log(response['erro'])
                console.log(response['msg'])
                if (response['erro'] == false) {
                  await swal({
                    title: "Sucesso!",
                    text: "Baia atualizada!",
                    icon: "success",
                    button: false,
                    timer: 1500
                  });
                  tabelaGPS.ajax.reload(null, false);
                } else {
                  await swal({
                    title: "Erro!",
                    text: response['msg'],
                    icon: "error",
                    button: false,
                    timer: 1500
                  });
                  tabelaGPS.ajax.reload(null, false);
                }

              },
              error: async function (response) {
                // Tratar erros na requisição AJAX
                console.log(response)
                await swal({
                  title: "Erro!",
                  text: response['msg'],
                  icon: "error",
                  button: false,
                  timer: 1500
                });
                tabelaGPS.ajax.reload(null, false);
              }
            });
          });
        });

      </script>
    </div>
    <div class="footer">
      <h6>Criado e mantido por Daniel Lopes Manfrini: 2023</h6>
    </div>
  </div>
</body>


</html>