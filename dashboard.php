<?php

// PONTO DE ENTRADA: área interna (protegida)

require_once __DIR__ . '/controllers/AuthController.php';

$controller = new AuthController();
$controller->dashboard();
