<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuários</title>
</head>
<body>
    <div class="Container-cadastro">
        <h1>Cadastre-se</h1>
        <form method="POST" action="index.php?acao=cadastrar">

            <label>Nome Completo:</label>
            <input type="text" name="nome" id="" placeholder="Nome completo">

            <label>Email:</label>
            <input type="email" name="email" id="" placeholder="exemplo@gmail.com">

            <label>Telefone:</label>
            <input type="number" name="telefone" id="" placeholder="(xx) xxxxx-xxxx">

            <label>CPF</label>
            <input type="number" name="cpf" id="" placeholder="xxx.xxx.xxx">

            <Label>Data de Nascimento</Label>
            <input type="date" name="data_nascimento" id="">

            <Label>Senha:</Label>
            <input type="password" name="senha" id="" placeholder="crie sua senha">

            <div class="action-btns-cadastro">
                <button type="submit">Cria conta</button>
            </div>
    
        </form>

    </div>
 
    
</body>
</html>