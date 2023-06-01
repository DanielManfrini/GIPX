<!DOCTYPE html>
<html lang="pt-BR">

<head>

  <title>CADASTRO</title>
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

  <!-- Estilos -->
  <link rel="stylesheet" type="text/css" href="/archerx/css/header-style.css">
  <link rel="stylesheet" type="text/css" href="/archerx/css/equipamentos/cadastro-style.css">
  <link rel="icon" href="/archerx/public/img/icon.ico">

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
  <div class="sidebar">
    <a href="../equipamentos/cadastro.php?tipos=ET%2FSÉRIE">ESTAÇÃO DE TRABALHO</a>
    <a href="../equipamentos/cadastro.php?tipos=QDU">CABO QD</a>
    <a href="../equipamentos/cadastro.php?tipos=MONITOR">MONITOR</a>
  </div>
  <div class="container">
    <?php
    switch ($_GET['tipos']) {
      case 'ET/SÉRIE':
        $classe_et = "ativa";
        $campo = '<form action="../funcoes/inserter.php" method="POST">
                                        <label for="et">ESTAÇÃO DE TRABALHO:</label>
                                        <input type="entry" class="host" id="et" name="et" />
                                        <p><label for="serie">NÚMERO DE SÉRIE:</label>
                                        <input type="entry" class="serie" id="serie" name="serie" /></p>
                                        <p><input type="submit" value="Cadastrar" /></p> 
                                      </form>';
        break;
      case 'MONITOR':
        $classe_monitor = "ativa";
        $campo = '<form action="../funcoes/inserter.php" method="POST">
                                        <label for="monitor">MONITOR:</label>
                                        <input type="entry" class="monitor" id="monitor" name="monitor" />
                                        <p><input type="submit" value="Cadastrar" /></p> 
                                      </form>';
        break;
      case 'QDU':
        $classe_qdu = "ativa";
        $campo = '<form action="../funcoes/inserter.php" method="POST">
                                        <label for="qd">CABO QD:</label>
                                        <input type="entry" class="qd" id="qd" name="qd" />
                                        <p><input type="submit" value="Cadastrar" /></p> 
                                      </form>';
        break;
    }
    print $campo
    ?>
  </div>
  <div class="footer">
    <h6>Criado e mantido por Daniel Lopes Manfrini: 2023</h6>
  </div>
</body>

</html>