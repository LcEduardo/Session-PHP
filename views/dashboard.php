<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body { font-family: sans-serif; background: #f0f2f5; margin: 0; }
        header { background: #4f46e5; color: #fff; padding: 1rem 2rem; display: flex; justify-content: space-between; align-items: center; }
        main   { padding: 2rem; }
        a.logout { color: #fff; text-decoration: none; background: rgba(255,255,255,.2); padding: .4rem .8rem; border-radius: 4px; }
        a.logout:hover { background: rgba(255,255,255,.35); }
    </style>
</head>
<body>
    <header>
        <!-- >>> Após implementar a sessão, exiba o nome real: $_SESSION['user_name'] -->
        <span>Olá, <?= htmlspecialchars($userName) ?>!</span>
        <a class="logout" href="logout.php">Sair</a>
    </header>

    <main>
        <h1>Bem-vindo ao Dashboard</h1>
        <p>Você está autenticado. Explore o sistema!</p>
    </main>
</body>
</html>
