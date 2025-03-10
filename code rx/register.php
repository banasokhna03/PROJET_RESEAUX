<?php
session_start();

// Connexion à la base de données
$host = "localhost";
$user = "root";
$password = "";
$dbname = "entreprise";

// Créer la connexion
$conn = new mysqli($host, $user, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}


// Définir un message d'erreur vide au début
$error_message = "";

// Traitement du formulaire lorsqu'il est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Récupération des données du formulaire
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "L'email fourni n'est pas valide.";
    } else {
        // Ici tu pourrais ajouter du code pour enregistrer l'utilisateur dans la base de données

        // Envoi de l'email de confirmation
        $to = $email;
        $subject = "Confirmation d'inscription";
        $message = "Bonjour $nom,\n\nMerci de vous être inscrit. Voici votre confirmation.";
        $headers = "From: noreply@tondomaine.com";

        // Envoi de l'email et vérification si l'envoi est réussi
        if (mail($to, $subject, $message, $headers)) {
            echo "<p style='color:green;'>E-mail de confirmation envoyé avec succès.</p>";
        } else {
            $error_message = "Erreur lors de l'envoi de l'e-mail.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <h2>Inscription</h2>

    <?php
    // Affichage des erreurs s'il y en a
    if (!empty($error_message)) {
        echo "<p style='color:red;'>$error_message</p>";
    }
    ?>

    <form method="POST">
        <input type="hidden" name="register">
        <input type="text" name="nom" placeholder="Nom" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <button type="submit">S'inscrire</button>
    </form>
    <a href="login.php">Déjà inscrit ? Connectez-vous</a>
</body>
</html>