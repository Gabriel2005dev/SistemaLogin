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

    }

    public function listarUsuarios() {

        $usuario = new Usuario();

        return $usuario->listar();
    }
}