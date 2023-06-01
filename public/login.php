<?php
// Página para realizar o login

// Define o tempo de expiração dos cookies para o passado
$cookies = $_COOKIE;
foreach ($cookies as $name => $value) {
  setcookie($name, '', time() - 3600, '/');
  unset($_COOKIE[$name]);
}

session_start();
if (isset($_SESSION['caminho_atual'])) {

  // Adiciona o valor da sessão a variável
  $caminhoAtual = $_SESSION['caminho_atual'];
  unset($_COOKIE['caminho_atual']); // Limpa o valor da sessão

} else {

  // o caminho será a home
  $caminhoAtual = "home/home.php";

}
;

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <title>LOGIN</title>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Sweet Alert -->
  <link type="text/css" href="/archerx/bibli/sweetalert2/dist/sweetalert2.min.css" rel="stylesheet">

  <!-- Sweet Alerts 2 -->
  <script src="/archerx/bibli/sweetalert2/dist/sweetalert2.all.min.js"></script>
  <script src="/archerx/bibli/sweetalert/dist/sweetalert.min.js"></script>

  <!-- Estilo -->
  <link rel="stylesheet" type="text/css" href="/archerx/css/login-style.css">
  <link rel="icon" href="img/icon.ico">
</head>

<body>
  <form method="POST" action="login.php" id="entrar">
    <img class="logo" src="img/logo transparente.png">
    <h3>SIPX CURITIBA</h3>
    <h5>Sistema Integrado Plansul Xaxim</h5>
    <input type="text" name="login" id="login" placeholder="Login">
    <input type="password" name="pass" id="pass" placeholder="Senha">
    <input type="submit" name="acao" value="Enviar">
  </form>

  <body>

    <?php

    if (isset($_POST['acao'])) {
      include('conexoes/conexao_mysql_PDO.php');
      $login = $_POST["login"];
      $senha = $_POST["pass"];
      $site = "localhost:8080";

      $query = "SELECT idusuarios,login_plansul,login_caixa,nome,nivel_acesso FROM archerx.usuarios_novo WHERE login_plansul = :login AND senha = :senha OR login_caixa = :login AND senha = :senha ";
      $statement = $conn->prepare($query);
      $statement->bindParam(':login', $login);
      $statement->bindParam(':senha', $senha);
      $statement->execute();
      $result = $statement->fetchAll(PDO::FETCH_ASSOC);

      $linha = $result[0];
      $id = $linha['idusuarios'];
      $login_plansul = $linha['login_plansul'];
      $login_caixa = $linha['login_caixa'];
      $nome = $linha['nome'];
      $nivel_acesso = $linha['nivel_acesso'];


      // Verifica o retorno, se voltar sem dados reporta senha incorreta.
      if (count($result) <= 0) {

        echo "<script> swal({
                              title: 'Erro!',
                              text: 'Usuario ou senha incorretos.',
                              icon: 'error',
                              button: false,
                              timer: 1500
                            }).then(function() {
                              window.location.href = 'login.php';
                            });
              </script>";
        die();

      } else {
        echo '<script>cosole.log('.$result.')</script>';
        // Definimos o caminho para guardar os kookies
        $cookiePath = "/";
        // Definimos quanto tempo levará para o kookie expirar
        $cookieExpire = time() + (60 * 60 * 07); //1 hora -> seconds*minutes*hours
        setcookie("login", 'sim', $cookieExpire, $cookiePath);
        setcookie("id", $id, $cookieExpire, $cookiePath);
        setcookie("login_plansul", $login_plansul, $cookieExpire, $cookiePath);
        setcookie("login_caixa", $login_caixa, $cookieExpire, $cookiePath);
        setcookie("nome", $nome, $cookieExpire, $cookiePath);

        // Verificamos o nivel de acesso
        switch ($nivel_acesso) {
          case 1:
            setcookie("ti", $nivel_acesso, $cookieExpire, $cookiePath);
          case 2:
            setcookie("rh", $nivel_acesso, $cookieExpire, $cookiePath);
          case 3:
            setcookie("supervisor", $nivel_acesso, $cookieExpire, $cookiePath);
          case 4:
            setcookie("coordenador", $nivel_acesso, $cookieExpire, $cookiePath);
          case 5:
            setcookie("gerente", $nivel_acesso, $cookieExpire, $cookiePath);
        }

        echo "<script> swal({
                              title: 'Sucesso!',
                              text: 'Login efetuado com sucesso!',
                              icon: 'success',
                              button: false,
                              timer: 1500
                            }).then(function() {
                              window.location.href = '" . $caminhoAtual . "';
                            });
                </script>";

      }
    }
    ?>
  </body>

</html>