<?php
session_start();

// Connexion à la base de données
$host = "localhost";
$user = "root";
$password = "";
$dbname = "entreprise";

$conn = new mysqli($host, $user, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Gestion de la connexion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérification de l'utilisateur
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nom'];
            echo "<script>alert('Connexion réussie !'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Mot de passe incorrect');</script>";
        }
    } else {
        echo "<script>alert('Email non trouvé');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h2>Connexion</h2>
    <form method="POST">
        <input type="hidden" name="login">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <button type="submit">Se connecter</button>
    </form>
    <a href="register.php">Pas encore inscrit ? S'inscrire</a>
</body>
</html>