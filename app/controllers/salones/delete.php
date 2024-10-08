<?php

include('../../../app/config.php');

$classroom_id = $_POST['classroom_id'];

/* Verificar si el sal�n est� asociado a alg�n grupo */
$sql_asociaciones = "SELECT * FROM `groups` WHERE estado = '1' AND classroom_id = :classroom_id";
$query_asociaciones = $pdo->prepare($sql_asociaciones);
$query_asociaciones->bindParam(':classroom_id', $classroom_id);
$query_asociaciones->execute();
$asociaciones = $query_asociaciones->fetchAll(PDO::FETCH_ASSOC);
$contador = count($asociaciones);

if ($contador > 0) {
    session_start();
    $_SESSION['mensaje'] = "Este sal�n est� asociado a grupos, no se puede eliminar.";
    $_SESSION['icono'] = "error";
    header('Location:' . APP_URL . "/admin/salones");
    exit;
} else {
    $sentencia = $pdo->prepare("DELETE FROM `classrooms` WHERE classroom_id = :classroom_id");
    $sentencia->bindParam(':classroom_id', $classroom_id);

    try {
        if ($sentencia->execute()) {
            session_start();
            $_SESSION['mensaje'] = "Se ha eliminado el sal�n correctamente.";
            $_SESSION['icono'] = "success";
            header('Location:' . APP_URL . "/admin/salones");
            exit;
        } else {
            session_start();
            $_SESSION['mensaje'] = "No se ha podido eliminar el sal�n, comun�quese con el �rea de IT.";
            $_SESSION['icono'] = "error";
            header('Location:' . APP_URL . "/admin/salones");
            exit;
        }
    } catch (Exception $e) {
        session_start();
        $_SESSION['mensaje'] = "Error al eliminar el sal�n: " . $e->getMessage();
        $_SESSION['icono'] = "error";
        header('Location:' . APP_URL . "/admin/salones");
        exit;
    }
}
