<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$error = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);

echo json_encode(['error' => $error]);