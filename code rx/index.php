<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}?>
<a href="logout.php">Deconnexion</a>
<?php // Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "entreprise"; // Remplace par le nom de ta base

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("echec de la connexion : " . $conn->connect_error);
}


// Ajouter un employé
if (isset($_POST['add_employee'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    
    $sql = "INSERT INTO employees (nom, prenom, email) VALUES ('$nom', '$prenom', '$email')";
    $conn->query($sql);
    header("Location: index.php");
    exit();
}

// Ajouter un client
if (isset($_POST['add_client'])) {
    $nom = $_POST['nom_client'];
    $prenom = $_POST['prenom_client'];
    $email = $_POST['email_client'];
    
    $sql = "INSERT INTO clients (nom, prenom, email) VALUES ('$nom', '$prenom', '$email')";
    $conn->query($sql);
    header("Location: index.php");
    exit();
}


if (isset($_POST['add_document']) && isset($_FILES['document'])) {
    $titre_document = $_POST['titre_document'];
    $document = $_FILES['document'];

    // Vérifier si le fichier est bien téléchargé
    if ($document['error'] == 0) {
       // Définir le chemin de destination
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($document["name"]);

        // Vérifier l'extension du fichier, taille, etc.
        if (move_uploaded_file($document["tmp_name"], $target_file)) {
            echo "Le fichier a été téléchargé avec succès.";
        } else {
            echo "Désolé, une erreur est survenue lors du téléchargement du fichier.";
        }
    } else {
        echo "Une erreur est survenue avec le fichier.";
 
   }

}
if (isset($_GET['delete_employee'])) {
    $conn->query("DELETE FROM employees WHERE id=" . $_GET['delete_employee']);
    exit();
}
if (isset($_GET['delete_client'])) {
    $conn->query("DELETE FROM clients WHERE id=" . $_GET['delete_client']);
    exit();
}
if (isset($_GET['delete_document'])) {
    $conn->query("DELETE FROM documents WHERE id=" . $_GET['delete_document']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Employés, Clients et Documents</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="container mt-4">

    <h2>Gestion des Employés</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#employeeModal">Ajouter un Employé</button>
    <table class="table table-striped">
        <thead>
            <tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php $result = $conn->query("SELECT * FROM employees");
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['nom']}</td><td>{$row['prenom']}</td><td>{$row['email']}</td>
                      <td><button class='btn btn-danger' onclick='deleteEmployee({$row['id']})'>Supprimer</button></td></tr>";
            } ?>
        </tbody>
    </table>

     <h2>Gestion des Clients</h2>
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#clientModal">Ajouter un Client</button>
    <table class="table table-striped">
        <thead>
            <tr><th>Nom</th><th>Prénom</th><th>Email</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php $result = $conn->query("SELECT * FROM clients");
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['nom']}</td><td>{$row['prenom']}</td><td>{$row['email']}</td>
                      <td><button class='btn btn-danger' onclick='deleteClient({$row['id']})'>Supprimer</button></td></tr>";
            } ?>
        </tbody>
    </table>

    <h2>Gestion des Documents</h2>
    <button class="btn btn-info mb-3" data-bs-toggle="modal" data-bs-target="#documentModal">Ajouter un Document</button>
    <table class="table table-striped">
        <thead>
            <tr><th>Titre</th><th>Chemin</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php $result = $conn->query("SELECT * FROM documents");
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['titre']}</td><td><a href='{$row['chemin']}' target='_blank'>Voir le document</a></td>
                      <td><button class='btn btn-danger' onclick='deleteDocument({$row['id']})'>Supprimer</button></td></tr>";
            } ?>
        </tbody>
    </table>

    <!-- Modals -->
    <div class="modal fade" id="employeeModal" tabindex="-1">
        <div class="modal-dialog"><div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Ajouter un Employé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="index.php" >
                    <input type="hidden" name="add_employee">
                    <label>Nom</label><input type="text" name="nom" class="form-control" required>
                    <label>Prénom</label><input type="text" name="prenom" class="form-control" required>
                    <label>Email</label><input type="email" name="email" class="form-control" required>
                    <button type="submit" class="btn btn-primary mt-3">Enregistrer</button>
                </form>
            </div>
        </div></div>
    </div>
<!-- Modal Ajouter Client -->
<div class="modal fade" id="clientModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="index.php">
                    <input type="hidden" name="add_client">
                    <input type="text" name="nom_client" placeholder="Nom" class="form-control mb-2" required>
                    <input type="text" name="prenom_client" placeholder="Prénom" class="form-control mb-2" required>
                    <input type="email" name="email_client" placeholder="Email" class="form-control mb-2" required>
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajouter Document -->
<div class="modal fade" id="documentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
          <form method="POST" enctype="multipart/form-data" action="index.php">
                    <input type="hidden" name="add_document">
                    <input type="text" name="titre_document" placeholder="Titre" class="form-control mb-2" required>
                    <input type="file" name="document" class="form-control mb-2" required>
                    <button type="submit" class="btn btn-info">Enregistrer</button>
                </form>
            </div>
        </div>
    </div>
</div>
    <script>
    function deleteEmployee(id) { $.get(index.php?delete_employee=${id}, function() { location.reload(); }); }
    function deleteClient(id) { $.get(index.php?delete_client=${id}, function() { location.reload(); }); }
    function deleteDocument(id) { $.get(index.php?delete_document=${id}, function() { location.reload(); }); }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>