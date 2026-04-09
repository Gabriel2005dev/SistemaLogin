<?php

require_once __DIR__ . '/../../config/Database.php';

class Usuario {

    private $pdo;

    public function __construct() {
        $db = new Database();
        $this->pdo = $db->conectar();
    }

    public function cadastrar($dados) {

        $sql = "INSERT INTO usuarios 
        (nome, email, telefone, cpf, data_nascimento, senha) 
        VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            $dados['nome'],
            $dados['email'],
            $dados['telefone'],
            $dados['cpf'],
            $dados['data_nascimento'],
            $dados['senha']
        ]);
    }

    public function buscarPorEmail($email) {

        $sql = "SELECT * FROM usuarios WHERE email = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listar() {

        $sql = "SELECT id, nome, email, telefone, cpf, data_nascimento FROM usuarios";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}