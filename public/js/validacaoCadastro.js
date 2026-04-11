     const form = document.getElementById('form-cadastro');
        const errosJs = document.getElementById('erros-js');

        form.addEventListener('submit', function (event) {
            const telefone = form.telefone.value.replace(/\D/g, '');
            const cpf = form.cpf.value.replace(/\D/g, '');
            const senha = form.senha.value;

            const erros = [];

            if (telefone.length < 10 || telefone.length > 11) {
                erros.push('Telefone deve ter 10 ou 11 dígitos.');
            }

            if (cpf.length !== 11) {
                erros.push('CPF deve ter 11 dígitos.');
            }

            if (senha.length < 8) {
                erros.push('Senha deve ter no mínimo 8 caracteres.');
            }

            if (erros.length > 0) {
                event.preventDefault();
                errosJs.textContent = erros.join(' ');
            }
        });