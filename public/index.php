<?php

require_once '../app/controllers/AuthController.php';

session_start();

// pega ação (login ou cadastrar)
$acao = $_GET['acao'] ?? '';

// verifica se veio requisição POST (formulário enviado)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $controller = new AuthController();

    // 🔐 LOGIN
    if ($acao == 'login') {

        $resultado = $controller->login();

        if ($resultado) {
            echo "Login realizado com sucesso!";
            // você pode redirecionar depois
            // header("Location: dashboard.php");
        } else {
            echo "Email ou senha inválidos!";
        }

    // 📝 CADASTRO
    } elseif ($acao == 'cadastrar') {

        $resultado = $controller->cadastrar();

        if ($resultado) {
            echo "Cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar!";
        }

    } else {
        echo "Ação inválida!";
    }

} else {

    // GET → carrega a tela
    // você pode trocar depois por login.php ou separar as views
    require_once '../app/views/cadastro.php';
}