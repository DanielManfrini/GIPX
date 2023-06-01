<?php

$data = date('Y-m-d');

?>

<!-- 
  A página Ponto.php é uma página com o foco de melhorar o acesso a informação de ponto para os supervisores.
  e tem como objetivo evitar o uso do aplicativo ponto secullum.

  A página usa os banco de dados [GerenciadorAcessos].[dbo].[Funcionarios_folha_ponto_novo] 
  que é populada por um script de insert buscando os dados 
  em uma view do banco de dados criado por Felipe Zapelli [PontoSecullumUnif].[dbo].[vw_informacao_centralizada].
-->

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <title>FOLHA PONTO</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Estilos -->
  <link rel="stylesheet" type="text/css" href="/archerx/css/header-style.css">
  <link rel="stylesheet" type="text/css" href="/archerx/css/relatorio/ponto-style.css">
  <link rel="icon" href="/archerx/public/img/icon.ico">

  <!-- Biblioteca jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Biblioteca jQuery UI-->
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <!-- Biblioteca jQuery CSS-->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

  <!-- Biblioteca DataTables -->
  <script type="text/javascript" src="/archerx/bibli/dataTables/datatables.min.js"></script>
  <script src="/archerx/bibli/dataTables/Buttons-2.3.6/js/dataTables.buttons.min.js"></script>
  <script src="/archerx/bibli/dataTables/Buttons-2.3.6/js/buttons.html5.min.js"></script>
  <script src="/archerx/bibli/dataTables/RowGroup-1.3.1/js/dataTables.rowGroup.min.js"></script>
  <script src="/archerx/bibli/dataTables/Buttons-2.3.6/js/buttons.print.min.js"></script>
  <script src="/archerx/bibli/dataTables/pdfmake-0.2.7/pdfmake.min.js"></script>
  <script src="/archerx/bibli/dataTables/pdfmake-0.2.7/vfs_fonts.js"></script>

  <!-- CSS Datatables -->
  <link rel="stylesheet" href="/archerx/bibli/dataTables/Buttons-2.3.6/css/buttons.dataTables.min.css">
  <link rel="stylesheet" type="text/css" href="/archerx/bibli/dataTables/datatables.min.css" />

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
      <table id="tabela_ponto">
        <thead>
          <tr class="header_tabela">
            <td><span style="font-weight:bold;">Semana</span></td>
            <td><span style="font-weight:bold;">Dia</span></td>
            <td><span style="font-weight:bold;">Feriado</span></td>
            <td><span style="font-weight:bold;">Batidas</span></td>
            <td><span style="font-weight:bold;">Login Intelix</span></td>
            <td><span style="font-weight:bold;">Logoff Intelix</span></td>
            <td><span style="font-weight:bold;">Entrada</span></td>
            <td><span style="font-weight:bold;">Saida lanche</span></td>
            <td><span style="font-weight:bold;">Volta lanche</span></td>
            <td><span style="font-weight:bold;">Saída</span></td>
            <td><span style="font-weight:bold;">Horas trabalhadas</span></td>
            <td><span style="font-weight:bold;">Horas lanche</span></td>
            <td class="fim_tabela"><span style="font-weight:bold;">BH do dia</span></td>
            <td><span style="font-weight:bold;">Matícula Caixa</span></td>
            <td><span style="font-weight:bold;">Matricula Plansul</span></td>
            <td><span style="font-weight:bold;">Abaixo_de</span></td>
            <td><span style="font-weight:bold;">Nome</span></td>
            <td><span style="font-weight:bold;">Situação</span></td>
            <td><span style="font-weight:bold;">Escala</span></td>
            <td><span style="font-weight:bold;">Observações</span></td>
            <td><span style="font-weight:bold;">Minutos de expediente considerado</span></td>
          </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
          <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Total:</th>
            <th></th>
            <th></th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>

    <!-- modal abertura de chamado -->
    <div class="modal_ponto" id="modal_ponto" title="Justificar ponto!">
      <p>
      <form>
        <label for="ponto">Ação:</label>
        <select name="ponto" id="ponto">
          <option value="Inserir entrada">Inserir Entrada</option>
          <option value="Inserir ida para pausa">Inserir ida para pausa</option>
          <option value="Inserir volta da pausa">Inserir volta da pausa</option>
          <option value="Inserir Saída">Inserir saída</option>
          <option value="Inserir duas ou mais batidas">Inserir duas ou mais batidas</option>
        </select>
        <p class="um">
          <label for="hora">Hora:</label>
          <input type="time" name="hora" id="hora">
        </p>
        <p class="mais_de_um">
          <label for="hora_entrada">Entrada:</label>
          <input type="time" name="hora_entrada" id="hora_entrada">
          <label for="hora_ida_lanche">Ida lanche:</label>
          <input type="time" name="hora_ida_lanche" id="hora_ida_lanche">
          <label for="hora_volta_lanche">Volta lanche:</label>
          <input type="time" name="hora_volta_lanche" id="hora_volta_lanche">
          <label for="hora_saida">Saída:</label>
          <input type="time" name="hora_saida" id="hora_saida">
        </p>
        <p>
          <input type="text" placeholder="Justificativa!">
        </p>
        <input type="hidden" name="nome_modal" id="nome_modal">
        <input type="hidden" name="matricula_modal" id="matricula_modal">
        <input type="hidden" name="escala_modal" id="escala_modal">
        <input type="hidden" name="data_modal" id="data_modal">
      </form>
      </p>
    </div>

    <!-- Modal loguin glpi -->
    <div class="modal_glpi" id="modal_glpi" title="Login!">
      <h4>Para continuar, logue com as credenciais do GLPI!</h4>
      <p>
        <label for="login_glpi">Login:</label>
        <input type="text" name="login_glpi" id="login_glpi">
        <label for="senha_glpi">Senha:</label>
        <input type="password" name="senha_glpi" id="senha_glpi">
      </p>
    </div>

  </div>
  <script>
    // Montagem da tabela.
    $(document).ready(function () {

      // Configuração do modal CORREÇÃO DE PONTO!
      $("#modal_ponto").dialog({
        autoOpen: false, // Não abrir automaticamente ao carregar a página
        modal: true, // Modal com sobreposição
        width: 500, // Definir a largura do modal para 500 pixels
        buttons: {
          "Enviar": function () {

            var ponto = $('#ponto').val();
            var hora = $('#hora').val()

            $(this).dialog("close"); // Fechar o modal após salvar
          },
          "Cancelar": function () {
            $(this).dialog("close"); // Fechar o modal sem salvar
          }
        }
      });

      // Configuração do modal login glpi!
      $("#modal_glpi").dialog({
        autoOpen: false, // Não abrir automaticamente ao carregar a página
        modal: true, // Modal com sobreposição
        width: 400, // Definir a largura do modal para 400 pixels
        buttons: {
          "Enviar": function () {

            var login_glpi = $('#login_glpi').val();
            var senha_glpi = $('#senha_glpi').val();

            setCookie("login_glpi", login_glpi, 1);
            setCookie("senha_glpi", senha_glpi, 1);

            $.ajax({
              url: '/archerx/public/funcoes/api/glpi_api.php', // Função para criar.
              method: 'POST',
              data: {
                tipo: 'login',
              },
              success: async function (response) {
                if (response['erro'] == false) {
                  await swal({
                    title: "Sucesso!",
                    text: "Login realizado!",
                    icon: "success",
                    button: false,
                    timer: 1500
                  });

                  $("#modal_ponto").dialog("open");

                  $(this).dialog("close"); // Fechar o modal após salvar

                } else {
                  await swal({
                    title: "Erro!",
                    text: "Usuário ou senha incorretos!",
                    icon: "error",
                    button: false,
                    timer: 1500
                  });
                  apagarCookie('login_glpi');
                  apagarCookie('senha_glpi');
                }
              },
            });
          },
          "Cancelar": function () {
            $(this).dialog("close"); // Fechar o modal sem salvar
          }
        }
      });

      tabela_ponto = $('#tabela_ponto').DataTable({
        language: {
          url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/pt_br.json"
        },
        paging: false,
        order: [
          [1, 'asc']
        ], // Define a ordenação padrão na segunda coluna (índice 1) em ordem ascendente
        columns: [
          /* coluna 0 */
          {
            data: 'DiaSemana',
            width: '150px'
          },
          /* coluna 1 */
          {
            data: 'data',
            width: '50px'
          },
          /* coluna 2 */
          {
            data: 'feriado',
            width: '100px'
          },
          /* coluna 3 */
          {
            data: 'Qtd_Data',
            width: '100px'
          },
          /* coluna 4 */
          {
            data: 'Login_Intelix',
            width: '50px'
          },
          /* coluna 5 */
          {
            data: 'Logoff_Intelix',
            width: '50px'
          },
          /* coluna 6 */
          {
            data: 'entrada1',
            width: '50px'
          },
          /* coluna 7 */
          {
            data: 'saida1',
            width: '50px'
          },
          /* coluna 8 */
          {
            data: 'entrada2',
            width: '50px'
          },
          /* coluna 9 */
          {
            data: 'saida2',
            width: '50px'
          },
          /* coluna 10 */
          {
            data: 'Minutos_Expediente',
            width: '50px'
          },
          /* coluna 11 */
          {
            data: 'Minutos_Almoco',
            width: '50px'
          },
          /* coluna 12 */
          {
            data: 'Banco_Horas_Dia',
            width: '50px'
          },
          /* coluna 13 */
          {
            data: 'Mat_P',
            visible: false
          },
          /* coluna 14 */
          {
            data: 'Mat_Dac',
            className: 'coluna_matricula',
            visible: false
          },
          /* coluna 15 */
          {
            data: 'Nome',
            className: 'coluna_nome',
            visible: false
          },
          /* coluna 16 */
          {
            data: 'De_Situacao',
            visible: false
          },
          /* coluna 17 */
          {
            data: 'obs',
            visible: false
          },
          /* coluna 18 */
          {
            data: 'DescricaoEscala',
            className: 'coluna_escala',
            visible: false
          },
          /* coluna 29 */
          {
            data: 'Minutos_Expediente_Considerado',
            visible: false
          },
          /* coluna 20 */
          {
            data: 'Nome_Gestor1',
            visible: false
          }
        ],
        // Criar um cabeçalho personalizado com ordenação de linhas
        rowGroup: {
          dataSrc: function (row) {
            // Concatena os valores das colunas de agrupamento
            //console.log(row)
            var Mat_P = row['Mat_P'];
            var Mat_Dac = row['Mat_Dac'];
            var Nome = row['Nome'];
            var De_Situacao = row['De_Situacao'];
            var escala = row['DescricaoEscala'];
            var gestor = row['Nome_Gestor1'];

            // Concatena os valores
            return "Mat Caixa: " + Mat_P + "  |  Mat Plansul: " + Mat_Dac + "  |  Nome: " + Nome + "  |  Escala: " + escala + "  |  Supervisor: " + gestor + "  |  Situação: " + De_Situacao

          },
        },

        // Vamos adicionar os dados calculados das horas ao footer.
        footerCallback: function (tfoot, data, start, end, display) {
          var api = this.api();

          // Cálculo da soma das horas e minutos
          var totalHoras = 0;
          var totalMinutos = 0;
          var totalHoras_lanche = 0;
          var totalMinutos_lanche = 0;
          var totalHoras_dia = 0;
          var totalMinutos_dia = 0;


          // Horas trabalhadas.
          for (var i = 0; i < display.length; i++) {
            var rowData = api.row(display[i]).data();
            if (rowData['Minutos_Expediente'] !== null) {
              var tempo = rowData['Minutos_Expediente'].split(':');
              //console.log(rowData['Minutos_Expediente'])

              var horas = parseInt(tempo[0], 10);
              var minutos = parseInt(tempo[1], 10);

              console.log(" Banco de horas: " + horas + ":" + minutos)

              if (rowData['Minutos_Expediente'].startsWith('-')) {
                console.log("Negativo");
                totalHoras -= Math.abs(horas); // Subtrai horas negativas do total de horas
                totalMinutos -= Math.abs(minutos); // Subtrai minutos negativos do total de minutos
              } else {
                console.log("Positivo");
                totalHoras += horas;
                totalMinutos += minutos;
              }
            }
          }

          // Ajusta os minutos negativos para as horas
          if (totalMinutos < 0) {
            var minutosExcedentes = Math.ceil(Math.abs(totalMinutos) / 60); // Calcula os minutos excedentes
            totalHoras -= minutosExcedentes; // Subtrai os minutos excedentes das horas
            totalMinutos += minutosExcedentes * 60; // Adiciona os minutos excedentes corrigidos
          }


          // Realiza a soma dos minutos excedentes para as horas
          totalHoras += Math.floor(totalMinutos / 60);
          totalMinutos = totalMinutos % 60;

          // Formata a string com o resultado final
          var totalFormatado = (totalHoras) + ':' + ('0' + totalMinutos).slice(-2);

          console.log(totalFormatado)

          // Insere o resultado no tfoot
          $(api.column(10).footer()).html(totalFormatado);


          // Horas horas lanche.
          for (var i = 0; i < display.length; i++) {
            var rowData_lanche = api.row(display[i]).data();
            if (rowData_lanche['Minutos_Almoco'] !== null) {
              var tempo_lanche = rowData_lanche['Minutos_Almoco'].split(':');
              //console.log(rowData_lanche['Minutos_Almoco'])

              var horas_lanche = parseInt(tempo_lanche[0], 10);
              var minutos_lanche = parseInt(tempo_lanche[1], 10);

              console.log(" Banco de horas: " + horas_lanche + ":" + minutos_lanche)

              if (rowData_lanche['Minutos_Almoco'].startsWith('-')) {
                console.log("Negativo");
                totalHoras_lanche -= Math.abs(horas_lanche); // Subtrai horas negativas do total de horas
                totalMinutos_lanche -= Math.abs(minutos_lanche); // Subtrai minutos negativos do total de minutos
              } else {
                console.log("Positivo");
                totalHoras_lanche += horas_lanche;
                totalMinutos_lanche += minutos_lanche;
              }
            }
          }

          // Ajusta os minutos negativos para as horas
          if (totalMinutos_lanche < 0) {
            var minutosExcedentes = Math.ceil(Math.abs(totalMinutos_lanche) / 60); // Calcula os minutos excedentes
            totalHoras_lanche -= minutosExcedentes; // Subtrai os minutos excedentes das horas
            totalMinutos_lanche += minutosExcedentes * 60; // Adiciona os minutos excedentes corrigidos
          }

          // Realiza a soma dos minutos excedentes para as horas
          totalHoras_lanche += Math.floor(totalMinutos_lanche / 60);
          totalMinutos_lanche = totalMinutos_lanche % 60;

          // Formata a string com o resultado final
          var totalFormatado_lanche = (totalHoras_lanche) + ':' + ('0' + totalMinutos_lanche).slice(-2);

          console.log(totalFormatado_lanche)

          // Insere o resultado no tfoot
          $(api.column(11).footer()).html(totalFormatado_lanche);

          // Banco de horas.
          for (var i = 0; i < display.length; i++) {
            var rowData_dia = api.row(display[i]).data();
            if (rowData_dia['Banco_Horas_Dia'] !== null) {
              var tempo_dia = rowData_dia['Banco_Horas_Dia'].split(':');
              //console.log(rowData_dia['Banco_Horas_Dia'])

              var horas_dia = parseInt(tempo_dia[0], 10);
              var minutos_dia = parseInt(tempo_dia[1], 10);

              console.log(" Banco de horas: " + horas_dia + ":" + minutos_dia)

              if (rowData_dia['Banco_Horas_Dia'].startsWith('-')) {
                console.log("Negativo");
                totalHoras_dia -= Math.abs(horas_dia); // Subtrai horas negativas do total de horas
                totalMinutos_dia -= Math.abs(minutos_dia); // Subtrai minutos negativos do total de minutos
              } else {
                console.log("Positivo");
                totalHoras_dia += horas_dia;
                totalMinutos_dia += minutos_dia;
              }
            }
          }

          // Ajusta os minutos negativos para as horas
          if (totalMinutos_dia < 0) {
            var minutosExcedentes = Math.ceil(Math.abs(totalMinutos_dia) / 60); // Calcula os minutos excedentes
            totalHoras_dia -= minutosExcedentes; // Subtrai os minutos excedentes das horas
            totalMinutos_dia += minutosExcedentes * 60; // Adiciona os minutos excedentes corrigidos
          }


          // Realiza a soma dos minutos excedentes para as horas
          totalHoras_dia += Math.floor(totalMinutos_dia / 60);
          totalMinutos_dia = totalMinutos_dia % 60;

          // Formata a string com o resultado final
          var totalFormatado_dia = (totalHoras_dia) + ':' + ('0' + totalMinutos_dia).slice(-2);

          console.log(totalFormatado_dia)

          // Insere o resultado no tfoot
          $(api.column(12).footer()).html(totalFormatado_dia);

        },

        // Vamos adicionar os botões
        buttons: [{
          extend: 'csv', // Adiciona o botão de exportação CSV
          text: 'Exportar CSV', // Texto do botão
          className: 'btn-export-csv', // Classe personalizada para o botão CSV
          filename: function () {
            // Obtém o valor da primeira linha da coluna "nome"
            var primeiroNome = tabela_ponto.column(15).data()[0];

            var data_inicial = $('#data_inicial').val()
            var data_final = $('#data_final').val()

            // Remove caracteres inválidos para o nome do arquivo
            primeiroNome = primeiroNome.replace(/[\\/:*?"<>|]/g, '');

            // Formata o nome do arquivo.
            var nome_arquivo = 'Pontos ' + primeiroNome + ' de ' + data_inicial + ' à ' + data_final

            if (data_inicial == data_final) {

              var nome_arquivo = 'Pontos ' + primeiroNome + ' de ' + data_inicial

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
          exportOptions: {
            columns: ':visible'
          },
          customize: function (win) {
            // Obtém o documento de impressão
            var doc = $(win.document);

            var Mat_P = tabela_ponto.column(13).data()[0];
            var Mat_Dac = tabela_ponto.column(14).data()[0];
            var nome = tabela_ponto.column(15).data()[0];
            var De_Situacao = tabela_ponto.column(16).data()[0];
            var escala = tabela_ponto.column(18).data()[0];
            var gestor = tabela_ponto.column(20).data()[0];
            // Obter o conteúdo do footer em uma variável
            var horastrabalhadas = tabela_ponto.column(10).footer().innerHTML;
            var horaspausa = tabela_ponto.column(11).footer().innerHTML;
            var bancohoras = tabela_ponto.column(12).footer().innerHTML;


            // Adiciona informações adicionais antes do cabeçalho da tabela
            doc.find('head').append('<title>Dados</title>');
            doc.find('body').prepend("<div class='corpo' ><h5><p> Matrícula Caixa: " + Mat_P + "   Matrícula Plansul: " + Mat_Dac + "</p><p class='meio_topo' >Nome: " + nome + "</p><p class='meio_fundo' >Escala: " + escala + " Supervisor: " + gestor + " Situação: " + De_Situacao + "</p><p>Horas Trabalhadas: " + horastrabalhadas + " Horas Lanche: " + horaspausa + " Banco de horas: " + bancohoras + "</p></h5></div>");

            // Estiliza as informações adicionais conforme necessário
            //doc.find('h1').css('text-align', 'center');
            doc.find('div').css({
              'border-bottom': '1px solid #000',
              'border-top': '1px solid #000'
            });
            doc.find('.corpo').css({
              'border': '1px solid #000',
              'padding': '15px 15px',
              'margin-bottom': '15px'
            });
            doc.find('p').css({
              'border-bottom': '1px solid #000',
              'padding-bottom': '5px',
              'padding-right': '15px'
            });
            doc.find('.meio_topo').css({
              'margin': '10px 0'
            });
            doc.find('.meio_fundo').css({
              'margin-bottom': '10px'
            });
          }
        }
        ],
        dom: 'lBfrtip', // Especifica a posição dos botões 
      });
    });

    // vamos fazzer os capos limparem um ao outro para evitar conflitos
    $('#campo_matricula').on('click', function () {
      $('#campo_nome').val('')
    });
    $('#campo_nome').on('click', function () {
      $('#campo_matricula').val('')
    });

    $('#campo_matricula').on('keyup', busca_dados);
    $('#campo_nome').on('keyup', busca_dados);
    $('#data_inicial').on('change', busca_dados);
    $('#data_final').on('change', busca_dados);

    // Função para buscar os dados.
    function busca_dados(nome) {

      var matricula = $('#campo_matricula').val(); // Pegamos o valor do lacre
      var nome = $('#campo_nome').val();
      var inicio = $('#data_inicial').val();
      var fim = $('#data_final').val();

      if (matricula !== "" || nome !== "") {

        console.log('Dados a enviar:', matricula, nome, inicio, fim)

        $.ajax({
          url: '../funcoes/relatorio/buscar_dados_ponto.php', // Vamos mandar um POST para retornar os dados
          method: 'POST',
          data: {
            tipo: 'select',
            matricula: matricula,
            nome: nome,
            inicio: inicio,
            fim: fim
          },
          success: function (response) {

            //console.log(response)

            // Limpar os dados existentes na tabela
            tabela_ponto.clear();

            // Adicionar os novos dados à tabela
            tabela_ponto.rows.add(response).draw();

            //vamos adicionar o nome e a matricula aos campos
            //var primeiramatricula = tabela_ponto.column(3).data()[0]
            //var primeiroNome = tabela_ponto.column(4).data()[0]

            //$('#campo_matricula').val(primeiramatricula)
            //$('#campo_nome').val(primeiroNome)

          },
          error: function () { }
        });
      }
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


    // Abrir o modal ao clicar na célula
    $("#tabela_ponto tbody").on('click', 'tr', function () {

      var cookie_glpi = checkCookieExists('login_glpi')

      if (cookie_glpi) {

        // Coletar os valores da linha do datatables.
        var table = $("#tabela_ponto").DataTable()

        var linha = table.row($(this)).data();

        console.log('Dados da linha: ', linha);

        // Coletar os valores individualmente.
        var matricula = linha.Mat_Dac;
        var nome = linha.Nome;
        var escala = linha.DescricaoEscala;

        console.log("Valores a enviar ao modal: ", matricula, nome, escala);

        // Enviando os dados ao modal.
        $('#nome_modal').val(nome);
        $('#matricula_modal').val(matricula);
        $('#escala_modal').val(escala);

        $("#modal_ponto").dialog("open");

      } else {

        $("#modal_glpi").dialog("open");

      }

    });

    $('#ponto').on('change', function () {

      var selecionado = $(this).val();

      if (selecionado === 'Inserir duas ou mais batidas') {

        $('.mais_de_um').css('display', 'grid');
        $('.um').css('display', 'none');

      } else {

        $('.um').css('display', 'block');
        $('.mais_de_um').css('display', 'none');

      };

    });

    // função para verificar biscoitos
    function checkCookieExists(cookieName) {
      var cookies = document.cookie.split(";");

      for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim();

        // Verifica se o nome do cookie corresponde ao cookieName
        if (cookie.indexOf(cookieName + "=") === 0) {
          return true; // O cookie existe
        }
      }

      return false; // O cookie não existe
    }

    function setCookie(nome, valor, diasExpiracao, caminho) {
      var dataExpiracao = new Date();
      dataExpiracao.setDate(dataExpiracao.getDate() + diasExpiracao);

      var cookie = encodeURIComponent(nome) + "=" + encodeURIComponent(valor);
      cookie += "; expires=" + dataExpiracao.toUTCString();
      cookie += "; path=" + (caminho || "/");

      document.cookie = cookie;
    };

    // Função para apagar um cookie específico
    function apagarCookie(nomeCookie) {
      // Obtém todos os cookies do site
      var cookies = document.cookie.split(";");

      // Percorre os cookies e os remove um por um
      for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i];
        var eqPos = cookie.indexOf("=");
        var nome = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;

        // Verifica se o nome do cookie corresponde ao nome desejado
        if (nome.trim() === nomeCookie.trim()) {
          // Remove o cookie do array
          cookies.splice(i, 1);
          break; // Interrompe o loop após remover o cookie
        }
      }

      // Atualiza os cookies no documento
      document.cookie = cookies.join("; ");
    }
  </script>
  <div class="footer">
    <h6>Criado e mantido por Daniel Lopes Manfrini: 2023</h6>
  </div>
</body>

</html>