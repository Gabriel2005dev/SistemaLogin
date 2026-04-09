<?php

require_once __DIR__. '/../app/controllers/AuthController.php';

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$controller = new AuthController();
$usuarios = $controller->listarUsuarios();

?>

<h2>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h2>

<a href="logout.php">Sair</a>

<h3>Lista de Usuários</h3>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>CPF</th>
        <th>Nascimento</th>
    </tr>

    <?php foreach ($usuarios as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['nome'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['telefone'] ?></td>
            <td><?= $user['cpf'] ?></td>
            <td><?= $user['data_nascimento'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>