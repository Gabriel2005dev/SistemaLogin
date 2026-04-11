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

    public function existePorEmailOuCpf($email, $cpf) {
        $sql = "SELECT id FROM usuarios WHERE email = ? OR cpf = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email, $cpf]);

        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function login($email) {
        $sql = "SELECT id, nome, email, senha FROM usuarios WHERE email = ? LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);

        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    public function listar() {

        $sql = "SELECT id, nome, email, telefone, cpf, data_nascimento FROM usuarios";

        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deletarPorId($id){
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([$id]);
    }


}