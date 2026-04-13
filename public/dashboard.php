<?php

require_once __DIR__. '/../app/controllers/AuthController.php';

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$controller = new AuthController();
$errosEdicao = [];
$usuariosEmEdicao = null;

if (isset($_GET['editar'])) {
    $idEditar = (int) $_GET['editar'];
    if ($idEditar > 0) {
        $usuarioEmEdicao = $controller->buscarUsuarioPorId($idEditar);
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'deletar') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($id > 0) {
        if ($id === (int) $_SESSION['usuario_id']) {
            header("Location: dashboard.php?erro=nao_pode_auto_excluir");
            exit;
        }

        $controller->deletarUsuario($id);
        header("Location: dashboard.php?sucesso=usuario_excluido");
        exit;
    }

    header("Location: dashboard.php?erro=id_invalido");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['acao'] ?? '') === 'editar') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($id <= 0) {
        header("Location: dashboard.php?erro=id_invalido_edicao");
        exit;
    }

    $resultado = $controller->editarUsuario($id, $_POST);

    if ($resultado['sucesso']) {
        if ($id === (int) $_SESSION['usuario_id']) {
            $_SESSION['usuario_nome'] = trim($_POST['nome'] ?? $_SESSION['usuario_nome']);
        }

        header("Location: dashboard.php?sucesso=usuario_editado");
        exit;
    }

    $errosEdicao = $resultado['erros'];
    $usuarioEmEdicao = $controller->buscarUsuarioPorId($id);
}


$usuarios = $controller->listarUsuarios();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/main.css">
</head>

<body>

<div class="container">

    <!-- HEADER -->
    <header class="header">
        <h2>👋 Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h2>
        <a href="logout.php" class="btn-logout">Sair</a>
    </header>

    <!-- ALERTAS -->
    <?php if (($_GET['sucesso'] ?? '') === 'usuario_excluido'): ?>
        <div class="alert success">Usuário excluído com sucesso.</div>
    <?php elseif (($_GET['sucesso'] ?? '') === 'usuario_editado'): ?>
        <div class="alert success">Usuário atualizado com sucesso.</div>
    <?php endif; ?>

    <?php if (($_GET['erro'] ?? '') === 'nao_pode_auto_excluir'): ?>
        <div class="alert error">Você não pode excluir o próprio usuário.</div>
    <?php endif; ?>


    <!-- FORM EDITAR -->
    <?php if (!empty($usuarioEmEdicao)): ?>
        <div class="card">
            <h3>✏️ Editar usuário</h3>

            <form method="POST" action="dashboard.php" class="form">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" name="id" value="<?= (int) $usuarioEmEdicao['id'] ?>">

                <input type="text" name="nome" placeholder="Nome"
                    value="<?= htmlspecialchars($_POST['nome'] ?? $usuarioEmEdicao['nome']) ?>" required>

                <input type="email" name="email" placeholder="Email"
                    value="<?= htmlspecialchars($_POST['email'] ?? $usuarioEmEdicao['email']) ?>" required>

                <input type="tel" name="telefone" placeholder="Telefone"
                    value="<?= htmlspecialchars($_POST['telefone'] ?? $usuarioEmEdicao['telefone']) ?>" required>

                <input type="date" name="data_nascimento"
                    value="<?= htmlspecialchars($_POST['data_nascimento'] ?? $usuarioEmEdicao['data_nascimento']) ?>" required>

                <input type="text" value="<?= htmlspecialchars($usuarioEmEdicao['cpf']) ?>" readonly>

                <div class="form-actions">
                    <button type="submit" class="btn primary">Salvar</button>
                    <a href="dashboard.php" class="btn secondary">Cancelar</a>
                </div>
            </form>
        </div>
    <?php endif; ?>


    <!-- TABELA -->
    <div class="card">
        <div class="header-crud">
            <h3>CRUD</h3>
            <div class="action-crud">
                <input type="text" placeholder="Pesquisar Usuario">
                <button><i class="bi bi-search"></i></button>
            </div>

        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>CPF</th>
                    <th>Nascimento</th>
                    <th>Ações</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($usuarios as $user): ?>
                    <tr>
                        <td><?= (int) $user['id'] ?></td>
                        <td><?= htmlspecialchars($user['nome']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['telefone']) ?></td>
                        <td><?= htmlspecialchars($user['cpf']) ?></td>
                        <td><?= htmlspecialchars($user['data_nascimento']) ?></td>

                        <td class="actions">
                            <a href="dashboard.php?editar=<?= (int) $user['id'] ?>" class="icon edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form method="POST" action="dashboard.php"
                                  onsubmit="return confirm('Deseja excluir?');">

                                <input type="hidden" name="acao" value="deletar">
                                <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">

                                <button type="submit" class="icon delete">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>






