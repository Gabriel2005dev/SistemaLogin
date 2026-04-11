<?php

require_once '../app/controllers/AuthController.php';

session_start();

$acao = $_GET['acao'] ?? '';
$view = $_GET['view'] ?? 'login';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $controller = new AuthController();

    if ($acao == 'login') {
        $resultado = $controller->login();

        if ($resultado) {
            header("Location: dashboard.php");
            exit;
        } else {
            header("Location: index.php?erro=1");
            exit;
        }

    } elseif ($acao == 'cadastrar') {
        $resultado = $controller->cadastrar();

        if ($resultado) {
            header("Location: index.php?sucesso=1");
            exit;
        }

        header("Location: index.php?view=cadastro&erro=1");
        exit;

    } elseif ($acao == 'deletar') {
        $id = $_GET['id'];
        $controller->deletar($id);

    } else {
        echo "Ação inválida!";
    }

} else {
    if ($view === 'cadastro') {
        require_once '../app/views/cadastro.php';
    } else {
        require_once '../app/views/login.php';
    }
}