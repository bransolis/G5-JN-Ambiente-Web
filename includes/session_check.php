<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function usuarioAutenticado(): bool
{
    return isset($_SESSION['usuario_id']);
}

function requerirLogin(): void
{
    if (!usuarioAutenticado()) {
        header('Location: index.html?auth=1');
        exit;
    }
}

function usuarioActual(): ?array
{
    if (!usuarioAutenticado()) {
        return null;
    }

    require_once __DIR__ . '/../config/database.php';
    $pdo = conectarDB();

    $stmt = $pdo->prepare(
        'SELECT id, nombre, apellidos, username, email, foto_perfil, puntos, kg_reciclado, nivel_huella, activo, fecha_registro
         FROM usuarios WHERE id = :id'
    );
    $stmt->execute(['id' => $_SESSION['usuario_id']]);
    $usuario = $stmt->fetch();

    return $usuario ?: null;
}
