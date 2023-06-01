<?php

function conect_gerenciador()
{

    $conn = new PDO("sqlsrv:Database=;server=", "", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($conn == false)
        die();

    return $conn;

}
function conect_topaceso()
{

    $conn = new PDO("sqlsrv:Database=;server=", "", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($conn == false)
        die();

    return $conn;

}

?>