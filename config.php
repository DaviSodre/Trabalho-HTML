<?php

    $dbHost = 'localhost:3307'; //Eu mudei a porta então vai dar erro de só por "localhost"
    $dbUsername = 'davi';
    $dbPassword = '10121314';
    $dbName = 'cadastro-html';

    $conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    if($conexao->connect_errno)
    {
        echo "Erro";
    }
    else
    {
        echo "Conexão efetuada com sucesso";
    }

?>