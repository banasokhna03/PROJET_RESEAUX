<?php
session_start();

// Détruire toutes les variables de session
$_SESSION = array();

// Si une session existe, la supprimer
if (session_id() != "" || isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 42000, '/'); // Détruire le cookie de session
    session_destroy(); // Détruire la session
}

// Rediriger vers la page de connexion
header("Location: login.php");
exit;
?>