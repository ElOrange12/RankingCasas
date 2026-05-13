<?php
session_start();
require_once '../inc/bd.php';

// Doble validación de seguridad (Solo Admins)
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../exito.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $id = $_POST['id'] ?? 0;
    $sala_id = $_SESSION['sala_id'] ?? null;

    try {
        if ($accion === 'borrar_usuario' && $id != $_SESSION['user_id']) {
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
            $stmt->execute([$id]);
        } 
        elseif ($accion === 'borrar_casa') {
            $stmt = $pdo->prepare("DELETE FROM casas WHERE id_casa = ?");
            $stmt->execute([$id]);
        }
        elseif ($accion === 'borrar_actividad') {
            $stmt = $pdo->prepare("DELETE FROM actividades WHERE id_actividad = ?");
            $stmt->execute([$id]);
        }
        // 🔥 BOTÓN NUCLEAR: BORRAR SALA COMPLETA 🔥
        elseif ($accion === 'reset_plan' && $sala_id) {
            // Borrar lista_compra (no tiene id_sala todavía)
            $pdo->exec("DELETE FROM lista_compra");
            
            // Borrar la sala — el CASCADE elimina automáticamente:
            // casas, actividades, transporte, votos_fechas, asistentes, usuarios_salas
            $pdo->prepare("DELETE FROM salas WHERE id_sala = ?")->execute([$sala_id]);
            
            // Limpiar la sesión de sala
            unset($_SESSION['sala_id']);
            unset($_SESSION['sala_nombre']);
            
            // Llevar al menú de salas
            header("Location: ../salas.php");
            exit();
        }

        // Si es cualquier otra acción de borrado normal, vuelve al panel admin
        header("Location: ../admin.php?msg=borrado_ok");
        exit();

    } catch (PDOException $e) {
        die("Error en la base de datos: " . $e->getMessage());
    }
} else {
    header("Location: ../admin.php");
    exit();
}
?>
