<?php
session_start();

// PONTO DE ENTRADA: tela de login
// Todo o fluxo começa aqui

require_once __DIR__ . '/controllers/AuthController.php';

$controller = new AuthController();
$controller->login();
