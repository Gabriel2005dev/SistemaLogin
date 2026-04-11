<?php

require_once __DIR__.'/../models/Usuario.php';

class AuthController {

    public function cadastrar() {
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefone' => preg_replace('/\D+/', '', $_POST['telefone'] ?? ''),
            'cpf' => preg_replace('/\D+/', '', $_POST['cpf'] ?? ''),
            'data_nascimento' => trim($_POST['data_nascimento'] ?? ''),
            'senha' => $_POST['senha'] ?? ''
        ];

        $erros = $this->validarCadastro($dados);

        if (!empty($erros)) {
            $_SESSION['cadastro_erros'] = $erros;
            $_SESSION['cadastro_old'] = [
                'nome' => $dados['nome'],
                'email' => $dados['email'],
                'telefone' => $dados['telefone'],
                'cpf' => $dados['cpf'],
                'data_nascimento' => $dados['data_nascimento']
            ];

            return false;
        }

        $usuario = new Usuario();

        if ($usuario->existePorEmailOuCpf($dados['email'], $dados['cpf'])) {
            $_SESSION['cadastro_erros'] = ['Já existe um usuário com este e-mail ou CPF.'];
            $_SESSION['cadastro_old'] = [
                'nome' => $dados['nome'],
                'email' => $dados['email'],
                'telefone' => $dados['telefone'],
                'cpf' => $dados['cpf'],
                'data_nascimento' => $dados['data_nascimento']
            ];

            return false;
        }

        $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);

        return $usuario->cadastrar($dados);
    }

    private function validarCadastro($dados) {
        $erros = [];

        if ($dados['nome'] === '' || mb_strlen($dados['nome']) < 3) {
            $erros[] = 'Nome é obrigatório e deve ter pelo menos 3 caracteres.';
        }

        if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'E-mail inválido.';
        }

        if (!preg_match('/^\d{10,11}$/', $dados['telefone'])) {
            $erros[] = 'Telefone deve conter 10 ou 11 dígitos.';
        }

        if (!$this->cpfValido($dados['cpf'])) {
            $erros[] = 'CPF inválido.';
        }

        $data = DateTime::createFromFormat('Y-m-d', $dados['data_nascimento']);
        $hoje = new DateTime('today');

        if (!$data || $data->format('Y-m-d') !== $dados['data_nascimento']) {
            $erros[] = 'Data de nascimento inválida.';
        } elseif ($data > $hoje) {
            $erros[] = 'Data de nascimento não pode ser no futuro.';
        }

        if (strlen($dados['senha']) < 8) {
            $erros[] = 'Senha deve ter no mínimo 8 caracteres.';
        }

        return $erros;
    }

    private function cpfValido($cpf) {
        if (!preg_match('/^\d{11}$/', $cpf)) {
            return false;
        }

        if (preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($c = 0; $c < $t; $c++) {
                $soma += $cpf[$c] * (($t + 1) - $c);
            }

            $digito = ((10 * $soma) % 11) % 10;
            if ((int) $cpf[$c] !== $digito) {
                return false;
            }
        }

        return true;
    }

    public function login(){
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            return false;
        }

        $usuario = new Usuario();
        $registro = $usuario->login($email);

        if (!$registro) {
            return false;
        }

        if (!password_verify($senha, $registro['senha'])) {
            return false;
        }

        $_SESSION['usuario_id'] = $registro['id'];
        $_SESSION['usuario_nome'] = $registro['nome'];

        return true;
    }

    public function listarUsuarios() {

        $usuario = new Usuario();

        return $usuario->listar();
    }

    public function deletarUsuario($id) {
        $usuario = new Usuario();

        return $usuario->deletarPorId($id);
    }


 
}