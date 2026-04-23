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

        // ✅ LOGIN VÁLIDO
        // >>> IMPLEMENTE A SESSÃO AQUI <<<
        // Sugestão:
        //   session_start();
        //   $_SESSION['user_id']   = $user->getId();
        //   $_SESSION['user_name'] = $user->getName();

        header('Location: dashboard.php');
        exit;
    }

    public function dashboard(): void
    {
        // >>> IMPLEMENTE A VERIFICAÇÃO DE SESSÃO AQUI <<<
        // Antes de renderizar o dashboard, confirme que o usuário está logado.
        // Sugestão:
        //   session_start();
        //   if (!isset($_SESSION['user_id'])) {
        //       header('Location: index.php');
        //       exit;
        //   }

        // Por ora, passa um nome genérico para a view (substitua pelo dado da sessão depois)
        $userName = 'Visitante';

        require_once __DIR__ . '/../views/dashboard.php';
    }

    public function logout(): void
    {
        // >>> IMPLEMENTE O LOGOUT AQUI <<<
        // Sugestão:
        //   session_start();
        //   session_destroy();

        header('Location: index.php');
        exit;
    }
}
