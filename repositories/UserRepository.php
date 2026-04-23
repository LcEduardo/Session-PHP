<?php

require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../models/User.php';

// REPOSITORY: isola todo acesso ao banco de dados
// O Controller nunca fala diretamente com o banco — passa sempre por aqui

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Busca um usuário pelo e-mail (usado no login)
    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);

        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new User($row['id'], $row['name'], $row['email'], $row['password']);
    }
}
