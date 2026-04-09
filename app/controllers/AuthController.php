<?php

require_once __DIR__.'/../models/Usuario.php';

class AuthController {

    public function cadastrar() {

        $dados = $_POST;

        $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);

        $usuario = new Usuario();

        return $usuario->cadastrar($dados);
    }

    public function login(){

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if (empty($email) || empty($senha)) {
            return false;
        }

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->buscarPorEmail($email);

        if ($usuario && password_verify($senha, $usuario['senha'])) {

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];

            return true;
        }

        return false;
    }

    public function listarUsuarios() {

        $usuario = new Usuario();

        return $usuario->listar();
    }
}