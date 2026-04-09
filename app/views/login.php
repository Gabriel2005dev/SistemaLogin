<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div class="container-login">
        <h1>Entrar</h1>

        <?php if (!empty($_GET['erro'])): ?>
            <p style="color: red;">Email ou senha inválidos.</p>
        <?php endif; ?>

        <?php if (!empty($_GET['sucesso'])): ?>
            <p style="color: green;">Cadastro realizado com sucesso! Faça seu login.</p>
        <?php endif; ?>

        <form method="POST" action="index.php?acao=login">
            <label>Email:</label>
            <input type="email" name="email" placeholder="exemplo@gmail.com" required>

            <label>Senha:</label>
            <input type="password" name="senha" placeholder="Digite sua senha" required>

            <div class="action-btns-login">
                <button type="submit">Entrar</button>
            </div>
        </form>

        <p>Não tem conta? <a href="index.php?view=cadastro">Cadastre-se</a></p>
    </div>
</body>
</html>