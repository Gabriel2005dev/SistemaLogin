<?php

require_once __DIR__.'/../models/Usuario.php';

class AuthController {

    public function cadastrar() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nome = $_POST['nome'];
            $email = $_POST['email'];   
            $senha = $_POST['senha']; 
            $telefone = $_POST['telefone']; 
            $nascimento = $_POST['data_de_nascimento'];
            
            $usuario = new Usuario();
            $usuario->cadastrar($nome, $email, $senha, $telefone, $nascimento);

            echo"Usuário cadastrado com sucesso!";
        }
    }
}
?>