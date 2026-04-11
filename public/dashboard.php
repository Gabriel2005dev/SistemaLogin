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
    <h2>Bem-vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></h2>

<a href="logout.php">Sair</a>

<?php if (($_GET['sucesso'] ?? '') === 'usuario_excluido'): ?>
    <p style="color: green;">Usuário excluído com sucesso.</p>

<?php elseif (($_GET['sucesso'] ?? '') === 'usuario_editado'): ?>
    <p style="color: green;">Usuário atualizado com sucesso.</p>
<?php endif; ?>



<?php if (($_GET['erro'] ?? '') === 'nao_pode_auto_excluir'): ?>
    <p style="color: red;">Você não pode excluir o próprio usuário logado.</p>

<?php elseif (($_GET['erro'] ?? '') === 'id_invalido'): ?>
    <p style="color: red;">ID inválido para exclusão.</p>

<?php elseif (($_GET['erro'] ?? '') === 'id_invalido_edicao'): ?>
    <p style="color: red;">ID inválido para edição.</p>
<?php endif; ?>

<?php if (!empty($usuarioEmEdicao)): ?>
    <h3>Editar usuário #<?= (int) $usuarioEmEdicao['id'] ?></h3>
    <p><strong>Campos editáveis:</strong> Nome, E-mail, Telefone e Data de nascimento.</p>
    <p><strong>Campos bloqueados:</strong> ID e CPF (dados sensíveis para rastreabilidade).</p>

    <?php if (!empty($errosEdicao)): ?>
        <div style="color: red;">
            <p><strong>Não foi possível salvar:</strong></p>
            <ul>
                <?php foreach ($errosEdicao as $erro): ?>
                    <li><?= htmlspecialchars($erro) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="dashboard.php" style="margin-bottom: 20px;">
        <input type="hidden" name="acao" value="editar">
        <input type="hidden" name="id" value="<?= (int) $usuarioEmEdicao['id'] ?>">

        <div>
            <label for="nome">Nome:</label>
            <input id="nome" type="text" name="nome" minlength="3" required
                value="<?= htmlspecialchars($_POST['nome'] ?? $usuarioEmEdicao['nome']) ?>">
        </div>

        <div>
            <label for="email">E-mail:</label>
            <input id="email" type="email" name="email" required
                value="<?= htmlspecialchars($_POST['email'] ?? $usuarioEmEdicao['email']) ?>">
        </div>

        <div>
            <label for="telefone">Telefone:</label>
            <input id="telefone" type="tel" name="telefone" pattern="\d{10,11}" required
                value="<?= htmlspecialchars($_POST['telefone'] ?? $usuarioEmEdicao['telefone']) ?>">
        </div>

        <div>
            <label for="data_nascimento">Data de nascimento:</label>
            <input id="data_nascimento" type="date" name="data_nascimento" required
                value="<?= htmlspecialchars($_POST['data_nascimento'] ?? $usuarioEmEdicao['data_nascimento']) ?>">
        </div>

        <div>
            <label for="cpf">CPF (somente leitura):</label>
            <input id="cpf" type="text" value="<?= htmlspecialchars($usuarioEmEdicao['cpf']) ?>" readonly>
        </div>

        <button type="submit">Salvar alterações</button>
        <a href="dashboard.php">Cancelar</a>
    </form>
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
            <td><?= (int) $user['id'] ?></td>
            <td><?= htmlspecialchars($user['nome']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['telefone']) ?></td>
            <td><?= htmlspecialchars($user['cpf']) ?></td>
            <td><?= htmlspecialchars($user['data_nascimento']) ?></td>
        <td>
            <a href="dashboard.php?editar=<?= (int) $user['id'] ?>" title="Editar usuário" style="margin-right: 8px;">
                    <i class="bi bi-pencil-square" aria-hidden="true"></i>
                    <span class="sr-only">Editar</span>
                </a>
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
          
        </td>
           
        </tr>
    <?php endforeach; ?>
</table>

    
</body>
</html>






