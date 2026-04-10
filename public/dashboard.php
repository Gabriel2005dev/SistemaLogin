<?php

require_once __DIR__. '/../app/controllers/AuthController.php';

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}

$controller = new AuthController();


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

$usuarios = $controller->listarUsuarios();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <h2>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h2>

<a href="logout.php">Sair</a>

<?php if (($_GET['sucesso'] ?? '') === 'usuario_excluido'): ?>
    <p style="color: green;">Usuário excluído com sucesso.</p>
<?php endif; ?>

<?php if (($_GET['erro'] ?? '') === 'nao_pode_auto_excluir'): ?>
    <p style="color: red;">Você não pode excluir o próprio usuário logado.</p>
<?php elseif (($_GET['erro'] ?? '') === 'id_invalido'): ?>
    <p style="color: red;">ID inválido para exclusão.</p>
<?php endif; ?>



<h3>Lista de Usuários</h3>

<table border="1">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Telefone</th>
        <th>CPF</th>
        <th>Nascimento</th>
        <th>Ações</th>
    </tr>

    <?php foreach ($usuarios as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['nome'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['telefone'] ?></td>
            <td><?= $user['cpf'] ?></td>
            <td><?= $user['data_nascimento'] ?></td>
            <td>
            <form method="POST" action="dashboard.php" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');" style="display:inline;">
                <input type="hidden" name="acao" value="deletar">
                <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">
                <button
                    type="submit"
                    title="Excluir usuário"
                    aria-label="Excluir usuário"
                    style="background: transparent; border: none; color: #dc3545; cursor: pointer;"
                >
                    <i class="bi bi-trash-fill" aria-hidden="true"></i>
                </button>
            </form>
        </td>
           
        </tr>
    <?php endforeach; ?>
</table>

    
</body>
</html>






