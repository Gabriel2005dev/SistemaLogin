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