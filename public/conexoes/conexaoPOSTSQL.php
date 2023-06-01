<?php 

    try {
        $postgres = new PDO("pgsql:host=;dbname=", "", "");
    } catch (PDOException $e) {
        echo "Erro na conexão: " . $e->getMessage();
        print phpinfo();
    }

?>