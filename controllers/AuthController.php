<?php


require_once __DIR__ . '/../repositories/UserRepository.php';

// CONTROLLER: orquestra o fluxo entre a View e o Repository
// Recebe os dados do formulário, valida, e decide o que exibir/redirecionar

class AuthController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function login(): void
    {
        // Se o formulário ainda não foi enviado, apenas exibe a tela de login
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once __DIR__ . '/../views/login.php';
            return;
        }

        $email    = trim($_POST['email']    ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $error = 'Preencha e-mail e senha.';
            require_once __DIR__ . '/../views/login.php';
            return;
        }

        $user = $this->userRepository->findByEmail($email);

        // Verifica se o usuário existe e se a senha confere
        if ($user === null || !password_verify($password, $user->getPassword())) {
            $error = 'E-mail ou senha inválidos.';
            require_once __DIR__ . '/../views/login.php';
            return;
        }

        // Se o hash não usa o algoritmo mais atual (PASSWORD_ARGON2ID), regera e persiste
        if (password_needs_rehash($user->getPassword(), PASSWORD_ARGON2ID)) {
            $newHash = password_hash($password, PASSWORD_ARGON2ID);
            $this->userRepository->updatePassword($user->getId(), $newHash);
        }

        // como em index.php eu iniciei a sessão e chamei esse método ele já usou nessa requisição
        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_name'] = $user->getName();

        header('Location: dashboard.php');
        exit;
    }

    public function dashboard(): void
    {
        // session_start(); // Preciso ver uma forma de startar a sessão uma vez no código
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }

        $userName = $_SESSION['user_name'] ?? 'Visitante';

        require_once __DIR__ . '/../views/dashboard.php';
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();

        header('Location: index.php');
        exit;
    }
}
