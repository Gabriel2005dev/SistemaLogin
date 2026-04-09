<?php

require_once '../app/controllers/AuthController.php';

session_start();

$acao = $_GET['acao'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $controller = new AuthController();

    if ($acao == 'login') {

        if ($controller->login()) {
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Email ou senha inválidos!";
        }

    } elseif ($acao == 'cadastrar') {

        if ($controller->cadastrar()) {
            echo "Cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar!";
        }

    }

} else {
    require_once '../app/view/login.php';
}