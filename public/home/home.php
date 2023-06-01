<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <title>HOME</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Estilos -->
  <link rel="stylesheet" type="text/css" href="/archerx/css/header-style.css">
  <link rel="stylesheet" href="/archerx/css/home-style.css">
  <link rel="icon" href="/archerx/public/img/icon.ico">

  <!-- jquery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
  ;

  include('../includes/navbar.php');

  ?>
  <div class="inicio">
    <h3>SEJA BEM VINDO!</h3>
    <br>
    <h5> O SIPX é tem como objetivo auxilar na gerência de pessoas e ativos.
      <br>
      <p>Você tem acesso as seguintes ferramentas:</p>
      <br>
    </h5>
    <ul>
      <?php
      ?>
      <li>
        <h4>RELATÓRIOS:</h4>
        <br>
        <ul>
          <h5>
            <li>Folha ponto.</li>
            <li>Catracas.</li>
            <?php
            if (isset($_COOKIE['ti']) or isset($_COOKIE['gerente']) or isset($_COOKIE['coordenador'])) {
              echo ('
                    <li>GPS.</li>
                    <li>Rede.</li>
                  ');
            }
            ?>
          </h5>
        </ul>
      </li>
      <?php
      if (isset($_COOKIE['ti']) or isset($_COOKIE['gerente']) or isset($_COOKIE['coordenador'])) {
        echo ('<br>
              <li>
                <h4>DASHBOARDS:</h4>
                <br>
                <ul>
                  <h5>
                    <li>Chamados.</li>
                    <li>Headsets.</li>
                  </h5>
                </ul>
              </li>
            <br>'
        );
      }
      ;
      if (isset($_COOKIE['ti'])) {
        echo ('
          <li>
            <h4>TI:</h4>
            <br>
            <ul>
              <h5>
                <li>Gerência de funcionarios.</li>
                <li>Controle de GPS e REDE.</li>
                <li>Gerência de ativos.</li>
              </h5>
            </ul>
          </li>
        ');
      }
      ?>
      <br>
      <li>
        <h4>USUÁRIO:</h4>
        <ul>
          <br>
          <li>
            <h5>
              Você pode acessar a área de usuario ao clicar no seu nome na barra de navegação.
            </h5>
          </li>
        </ul>
      </li>
    </ul>
  </div>
  <div class="footer">
    <h6>Criado e mantido por Daniel Lopes Manfrini: 2023</h6>
  </div>
</body>

</html>