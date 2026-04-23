# learningSession

Mini projeto de aprendizado em PHP para praticar o conceito de **sessões** com um sistema simples de login.

## Como rodar

```bash
php -S localhost:8000
```

Acesse `http://localhost:8000` e use:

- **E-mail:** `teste@email.com`
- **Senha:** `senha123`

---

## Sessão: com vs. sem

### Sem sessão (estado inicial do projeto)

O HTTP é **stateless** — cada requisição é independente, o servidor não lembra de você.

Quando o login é válido, o controller apenas redireciona:

```php
header('Location: dashboard.php');
exit;
```

O que acontece:
1. Você envia e-mail + senha → servidor valida → redireciona para `dashboard.php`
2. O navegador faz uma **nova requisição** para `dashboard.php`
3. O servidor não sabe quem você é — a requisição chegou "zerada"
4. Qualquer um que digitar `http://localhost:8000/dashboard.php` diretamente acessa o dashboard **sem precisar fazer login**

### Com sessão

`$_SESSION` é um mecanismo que o PHP usa para "lembrar" o usuário entre requisições, guardando dados no **servidor** e enviando apenas um cookie com um ID para o navegador.

O fluxo vira:

```
Login válido
  → session_start()
  → $_SESSION['user_id'] = 3         ← salvo no servidor
  → cookie PHPSESSID=abc123 → navegador   ← ID da sessão

Próxima requisição (dashboard.php)
  → navegador envia cookie PHPSESSID=abc123
  → session_start() recupera os dados
  → $_SESSION['user_id'] existe? → deixa entrar
  → $_SESSION['user_id'] não existe? → redireciona para login
```

### Comparação direta

| Situação | Sem sessão | Com sessão |
|---|---|---|
| Acessar `/dashboard.php` direto | Entra sem login | Redireciona para login |
| Saber o nome do usuário logado | Impossível | `$_SESSION['user_name']` |
| Logout funcional | Só redireciona | Destrói os dados do servidor |
| Segurança | Nenhuma | Básica (suficiente para aprendizado) |

> Sem sessão, o login é uma **porta giratória** — você passa, mas ela não lembra que você já entrou. Com sessão, o servidor carrega um **crachá seu** enquanto você navega pelo sistema.

---

## O que implementar

Os três pontos marcados com `>>> IMPLEMENTE` no `controllers/AuthController.php`:

| Método | O que fazer |
|---|---|
| `login()` | `session_start()` + salvar dados do usuário em `$_SESSION` |
| `dashboard()` | Verificar se `$_SESSION['user_id']` existe, senão redirecionar |
| `logout()` | `session_start()` + `session_destroy()` |

---

## Arquitetura

```
index.php / dashboard.php / logout.php   ← pontos de entrada
        |
   AuthController                        ← orquestra o fluxo
        |
   UserRepository                        ← acesso ao banco
        |
   Database (SQLite)                     ← conexão PDO Singleton
        |
   User (Model)                          ← entidade de dados
```
