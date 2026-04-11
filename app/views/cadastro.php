<?php
$erros = $_SESSION['cadastro_erros'] ?? [];
$old = $_SESSION['cadastro_old'] ?? [];
unset($_SESSION['cadastro_erros'], $_SESSION['cadastro_old']);
?>
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

        <?php if (!empty($erros)): ?>
            <div style="color: red;">
                <p><strong>Corrija os campos abaixo:</strong></p>
                <ul>
                    <?php foreach ($erros as $erro): ?>
                        <li><?= htmlspecialchars($erro) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?acao=cadastrar" id="form-cadastro" novalidate>

            <label>Nome Completo:</label>
            <input type="text" name="nome" placeholder="Nome completo" minlength="3" required
                value="<?= htmlspecialchars($old['nome'] ?? '') ?>">
            <small style="color: red; display: block;" data-error-for="nome"></small>

            <label>Email:</label>
            <input type="email" name="email" placeholder="exemplo@gmail.com" required
                value="<?= htmlspecialchars($old['email'] ?? '') ?>">
            <small style="color: red; display: block;" data-error-for="email"></small>

            <label>Telefone:</label>
            <input type="tel" name="telefone" placeholder="(xx) xxxxx-xxxx" pattern="\d{10,11}" required
                value="<?= htmlspecialchars($old['telefone'] ?? '') ?>">
            <small style="color: red; display: block;" data-error-for="telefone"></small>

            <label>CPF</label>
            <input type="text" name="cpf" placeholder="00000000000" pattern="\d{11}" maxlength="11" required
                value="<?= htmlspecialchars($old['cpf'] ?? '') ?>">
            <small style="color: red; display: block;" data-error-for="cpf"></small>

            <label>Data de Nascimento</label>
            <input type="date" name="data_nascimento" required
                value="<?= htmlspecialchars($old['data_nascimento'] ?? '') ?>">
            <small style="color: red; display: block;" data-error-for="data_nascimento"></small>

            <label>Senha:</label>
            <input type="password" name="senha" placeholder="crie sua senha" minlength="8" required>
            <small style="color: red; display: block;" data-error-for="senha"></small>

            <p id="erros-js" style="color: red;"></p>

            <div class="action-btns-cadastro">
                <button type="submit">Criar conta</button>
            </div>

        </form>

        <p>Já tem conta? <a href="index.php">Faça login</a></p>

    </div>

    <script>
        const form = document.getElementById('form-cadastro');
        const errosGerais = document.getElementById('erros-js');

        const campos = {
            nome: form.elements.nome,
            email: form.elements.email,
            telefone: form.elements.telefone,
            cpf: form.elements.cpf,
            data_nascimento: form.elements.data_nascimento,
            senha: form.elements.senha,
        };

        const mensagens = {
            nome: 'Nome deve ter pelo menos 3 caracteres.',
            email: 'Digite um e-mail válido.',
            telefone: 'Telefone deve ter 10 ou 11 dígitos.',
            cpf: 'CPF deve ter 11 dígitos numéricos.',
            data_nascimento: 'Data de nascimento inválida.',
            senha: 'Senha deve ter no mínimo 8 caracteres.',
        };

        function setErro(campo, mensagem) {
            const target = document.querySelector(`[data-error-for="${campo}"]`);
            if (target) {
                target.textContent = mensagem || '';
            }
        }

        function validarCampo(campo) {
            const valor = campos[campo].value.trim();

            switch (campo) {
                case 'nome':
                    if (valor.length < 3) {
                        setErro('nome', mensagens.nome);
                        return false;
                    }
                    break;
                case 'email':
                    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(valor)) {
                        setErro('email', mensagens.email);
                        return false;
                    }
                    break;
                case 'telefone': {
                    const telefone = valor.replace(/\D/g, '');
                    if (telefone.length < 10 || telefone.length > 11) {
                        setErro('telefone', mensagens.telefone);
                        return false;
                    }
                    break;
                }
                case 'cpf': {
                    const cpf = valor.replace(/\D/g, '');
                    if (cpf.length !== 11) {
                        setErro('cpf', mensagens.cpf);
                        return false;
                    }
                    break;
                }
                case 'data_nascimento': {
                    if (!valor) {
                        setErro('data_nascimento', mensagens.data_nascimento);
                        return false;
                    }
                    const data = new Date(valor + 'T00:00:00');
                    const hoje = new Date();
                    hoje.setHours(0, 0, 0, 0);
                    if (Number.isNaN(data.getTime()) || data > hoje) {
                        setErro('data_nascimento', 'Data não pode ser no futuro.');
                        return false;
                    }
                    break;
                }
                case 'senha':
                    if (valor.length < 8) {
                        setErro('senha', mensagens.senha);
                        return false;
                    }
                    break;
                default:
                    break;
            }

            setErro(campo, '');
            return true;
        }

        Object.keys(campos).forEach((campo) => {
            campos[campo].addEventListener('input', () => validarCampo(campo));
            campos[campo].addEventListener('blur', () => validarCampo(campo));
        });

        form.addEventListener('submit', function (event) {
            errosGerais.textContent = '';

            const invalido = Object.keys(campos).some((campo) => !validarCampo(campo));

            if (invalido) {
                event.preventDefault();
                errosGerais.textContent = 'Revise os campos destacados antes de continuar.';
            }
        });
    </script>

</body>
</html>