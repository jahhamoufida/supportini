<?php
require_once __DIR__ . '/../../controller/EvenementC.php';
require_once __DIR__ . '/../../controller/ReservationC.php';

$evc = new EvenementC();
$resC = new ReservationC();

// ---- Vérification ID ----
$id = $_GET['id'] ?? null;
if (!$id) {
    die("ID manquant.");
}

// ---- Récupération événement ----
$ev = $evc->getEvenementById($id);
if (!$ev) {
    die("Événement introuvable.");
}

// ---- Variables messages ----
$success = "";
$error = "";

// ---- Traitement formulaire ----
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $email = trim($_POST["email"]);
    $nb = (int)$_POST["nb_places"];

    if (empty($nom) || empty($prenom) || empty($email)) {
        $error = "Veuillez remplir tous les champs.";
    } else {
        $r = new Reservation($id, $nom, $prenom, $email, $nb);
        $result = $resC->ajouterReservation($r);

        if ($result === "OK") {

    // 🔥 Envoi email
    require_once __DIR__ . '/../../controller/Mailer.php';
    $mailResult = Mailer::envoyerConfirmation($email, $nom, $ev['nom_evenement']);

    if ($mailResult === "OK") {
        $success = "Votre réservation est enregistrée 🎉 Un email de confirmation vous a été envoyé.";
    } else {
        $success = "Réservation OK, mais erreur email : " . $mailResult;
    }

} else {
    $error = $result;
}

    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver : <?= htmlspecialchars($ev['nom_evenement']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #121212;
            color: white;
            margin: 0;
            padding: 0;

            /* CENTRAGE FULL PAGE */
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        h2 {
            margin-top: 40px;
            font-size: 32px;
        }

        h2 i {
            color: #d32f2f;
        }

        /* CONTAINER DU FORMULAIRE */
        .form-container {
            width: 90%;
            max-width: 900px; /* GRAND ET BEAU */
            background: #1e1e1e;
            padding: 40px;
            margin-top: 25px;
            border-radius: 16px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.5);
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            font-size: 15px;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            background: #2c2c2c;
            border: 1px solid #444;
            border-radius: 8px;
            color: white;
        }

        button {
            margin-top: 25px;
            width: 100%;
            padding: 15px;
            background: #d32f2f;
            border: none;
            color: white;
            font-size: 18px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #a72828;
        }

        .alert-success,
        .alert-error {
            width: 90%;
            max-width: 900px;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            font-size: 16px;
        }

        .alert-success {
            background: #2e7d32;
        }

        .alert-error {
            background: #b71c1c;
        }

        .back-link {
            margin-top: 20px;
            display: inline-block;
            color: #ff6659;
            text-decoration: none;
            font-size: 16px;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>

</head>
<body>

<h2><i class="fa-solid fa-ticket"></i> Réserver : <?= htmlspecialchars($ev['nom_evenement']) ?></h2>

<?php if ($success): ?>
    <div class="alert-success"><?= $success ?></div>

    <a class="back-link" href="evenement_detail.php?id=<?= $id ?>">
        <i class="fa-solid fa-arrow-left"></i> Retour à l'événement
    </a>

<?php else: ?>

    <?php if ($error): ?>
        <div class="alert-error"><?= $error ?></div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST">

            <label>Nom :</label>
            <input type="text" name="nom" required>

            <label>Prénom :</label>
            <input type="text" name="prenom" required>

            <label>Email :</label>
            <input type="email" name="email" required>

            <label>Nombre de places :</label>
            <input type="number" name="nb_places"
                   min="1"
                   max="<?= $ev['nombre_places'] - $ev['nombre_inscrits'] ?>"
                   required>

            <button type="submit">Confirmer la réservation</button>

        </form>
    </div>

<?php endif; ?>

</body>
</html>
