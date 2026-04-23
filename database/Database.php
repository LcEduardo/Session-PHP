<?php

// DATABASE: gerencia a conexão com o SQLite
// Usa o padrão Singleton para garantir uma única instância de conexão

class Database
{
    private static ?PDO $instance = null;

    // Caminho do arquivo SQLite (será criado automaticamente se não existir)
    private static string $dbPath = __DIR__ . '/app.db';

    private function __construct() {}

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new PDO('sqlite:' . self::$dbPath);
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            self::createTables(self::$instance);
        }

        return self::$instance;
    }

    private static function createTables(PDO $pdo): void
    {
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id       INTEGER PRIMARY KEY AUTOINCREMENT,
                name     TEXT    NOT NULL,
                email    TEXT    NOT NULL UNIQUE,
                password TEXT    NOT NULL
            )
        ");

        // Seed: insere um usuário de teste se a tabela estiver vazia
        $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        if ($count == 0) {
            $hash = password_hash('senha123', PASSWORD_BCRYPT);
            $pdo->exec("INSERT INTO users (name, email, password) VALUES ('Usuário Teste', 'teste@email.com', '$hash')");
        }
    }
}
