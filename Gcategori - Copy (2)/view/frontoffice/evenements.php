<?php
require_once __DIR__ . '/../../controller/EvenementC.php';
$ec = new EvenementC();

$name = $_GET['name'] ?? null;
$date_from = $_GET['date_from'] ?? null;
$date_to = $_GET['date_to'] ?? null;
$availability = $_GET['availability'] ?? null;

if ($name || $date_from || $date_to || $availability) {
    $liste = $ec->filterEvenements($name, $date_from, $date_to, $availability);
} else {
    $liste = $ec->listEvenements();
}

// chemin public
$upload_web_dir = '/events/uploads/';
$base_url = '/events';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Événements | Supportini</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-red: #d32f2f;
            --dark-red: #b71c1c;
            --light-red: #ff6659;
            --dark-bg: #121212;
            --card-bg: #1e1e1e;
            --text-light: #f5f5f5;
            --text-muted: #aaaaaa;
            --border-color: #333333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-light);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Header */
        .main-header {
            background: linear-gradient(135deg, var(--dark-red), var(--primary-red));
            padding: 25px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            margin-bottom: 30px;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .site-title {
            font-size: 28px;
            font-weight: 700;
            color: white;
        }

        .site-title span {
            color: #ffccbc;
        }

        .nav-links {
            display: flex;
            gap: 20px;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 40px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .page-title {
            font-size: 36px;
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 10px;
        }

        .page-title i {
            color: var(--primary-red);
            margin-right: 15px;
        }

        .page-subtitle {
            color: var(--text-muted);
            font-size: 18px;
        }

        /* Search and Filters */
        .search-container {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            margin-bottom: 40px;
        }

        .search-title {
            font-size: 22px;
            margin-bottom: 25px;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .search-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            margin-bottom: 10px;
            font-weight: 500;
            color: var(--text-light);
            font-size: 14px;
        }

        .form-control {
            padding: 14px 16px;
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-light);
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-red);
            box-shadow: 0 0 0 2px rgba(211, 47, 47, 0.2);
        }

        .form-actions {
            display: flex;
            gap: 15px;
            align-items: flex-end;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--primary-red);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--dark-red);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.4);
        }

        .btn-outline {
            background-color: transparent;
            border: 2px solid var(--border-color);
            color: var(--text-light);
        }

        .btn-outline:hover {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: var(--primary-red);
            transform: translateY(-2px);
        }

        /* Stats Section */
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: linear-gradient(135deg, var(--card-bg), #252525);
            border-radius: 10px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border-left: 4px solid var(--primary-red);
        }

        .stat-icon {
            font-size: 32px;
            margin-bottom: 15px;
            color: var(--primary-red);
        }

        .stat-value {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--text-light);
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
        }

        /* Events Grid */
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }

        .event-card {
            background-color: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .event-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.35);
            border-color: var(--primary-red);
        }

        .event-image-container {
            position: relative;
            overflow: hidden;
            height: 220px;
        }

        .event-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .event-card:hover .event-image {
            transform: scale(1.05);
        }

        .event-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }

        .badge-available {
            background-color: #4caf50;
        }

        .badge-full {
            background-color: var(--primary-red);
        }

        .event-content {
            padding: 25px;
        }

        .event-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--text-light);
            line-height: 1.3;
        }

        .event-meta {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .event-meta-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .event-meta-item i {
            color: var(--primary-red);
            width: 16px;
            font-size: 16px;
        }

        .event-places {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 6px;
        }

        .places-info {
            font-size: 14px;
            color: var(--text-muted);
        }

        .places-remaining {
            font-weight: 600;
            font-size: 16px;
            color: var(--text-light);
        }

        .highlight {
            color: var(--primary-red);
            font-weight: 700;
        }

        .event-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-details {
            background-color: transparent;
            border: 2px solid var(--primary-red);
            color: var(--primary-red);
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-details:hover {
            background-color: var(--primary-red);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            color: var(--border-color);
        }

        .empty-state h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: var(--text-light);
        }

        .empty-state p {
            font-size: 16px;
            margin-bottom: 25px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Footer */
        .main-footer {
            background-color: var(--card-bg);
            padding: 30px 0;
            margin-top: 60px;
            border-top: 1px solid var(--border-color);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
        }

        .footer-text {
            color: var(--text-muted);
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .page-title {
                font-size: 28px;
            }

            .search-form {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .events-grid {
                grid-template-columns: 1fr;
            }

            .event-actions {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }

            .btn-details {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .main-header {
                padding: 20px 0;
            }

            .logo-section {
                flex-direction: column;
                gap: 10px;
            }

            .site-title {
                font-size: 24px;
            }

            .nav-links {
                flex-direction: column;
                width: 100%;
            }

            .nav-link {
                text-align: center;
            }

            .page-title {
                font-size: 24px;
            }

            .search-container {
                padding: 20px;
            }

            .stats-section {
                grid-template-columns: 1fr;
            }
        }
        /* petit style d'appoint pour le contrôle vocal */
#start, #stop {
    padding: 10px 14px;
    font-size: 14px;
}
#output {
    font-size: 14px;
    opacity: 0.9;
}

    </style>
</head>

<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-content">
            <div class="logo-section">
                <img src="http://localhost/Gcategori/uploads/logoN.png" class="logo" alt="Supportini Logo">
                <h1 class="site-title">SUPPORTINI<span>.TN</span></h1>
            </div>
            
            <nav class="nav-links">
                <a href="#" class="nav-link">
                    <i class="fa-solid fa-house"></i> Accueil
                </a>
                <a href="http://localhost/Gcategori/view/frontoffice/evenements.php" class="nav-link active">
                    <i class="fa-solid fa-calendar-days"></i> Événements
                </a>
                <a href="http://localhost/Gcategori/view/frontoffice/statistique.php" class="nav-link">
                     <i class="fa-solid fa-chart-column"></i> Statistiques
                </a>

                <a href="http://localhost/Gcategori/view/frontoffice/categories.php" class="nav-link active">
                     <i class="fa-solid fa-layer-group"></i> Catégories
                </a>

                
                <a href="/logout.php" class="nav-link">
                    <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <div class="page-header">
            <h1 class="page-title"><i class="fa-solid fa-calendar-days"></i> Liste des Événements</h1>
            <p class="page-subtitle">Découvrez tous nos événements et réservez vos places</p>
        </div>

        <!-- Stats Section -->
        <?php
        $totalEvents = count($liste);
        $totalPlaces = 0;
        $totalInscrits = 0;
        $availableEvents = 0;
        
        foreach ($liste as $ev) {
            $totalPlaces += $ev['nombre_places'];
            $totalInscrits += $ev['nombre_inscrits'];
            if ($ev['nombre_inscrits'] < $ev['nombre_places']) {
                $availableEvents++;
            }
        }
        ?>
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-film"></i>
                </div>
                <div class="stat-value"><?= $totalEvents ?></div>
                <div class="stat-label">Événements total</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <div class="stat-value"><?= $totalPlaces ?></div>
                <div class="stat-label">Places totales</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="stat-value"><?= $totalInscrits ?></div>
                <div class="stat-label">Inscrits total</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fa-solid fa-check-circle"></i>
                </div>
                <div class="stat-value"><?= $availableEvents ?></div>
                <div class="stat-label">Événements disponibles</div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="search-container">
            <h2 class="search-title"><i class="fa-solid fa-filter"></i> Filtrer les événements</h2>
            <!-- ====== CONTROLES VOCAUX ====== -->
            <div style="display:flex;gap:10px;align-items:center;margin-bottom:18px;">
            <button id="start" class="btn btn-primary" type="button"><i class="fa-solid fa-microphone"></i> Démarrer</button>
            <button id="stop" class="btn btn-outline" type="button" disabled><i class="fa-solid fa-square"></i> Arrêter</button>
            <div id="output" style="color:var(--text-muted);margin-left:10px;">🎧 En attente de commande vocale...</div>
        </div>
<!-- ============================================ -->

            <form method="GET" class="search-form">
                <div class="form-group">
                    <label class="form-label" for="name">Nom de l'événement</label>
                    <input type="text" id="name" name="name" class="form-control" 
                           placeholder="Rechercher par nom..." value="<?= htmlspecialchars($name ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="date_from">Date de début</label>
                    <input type="date" id="date_from" name="date_from" class="form-control" 
                           value="<?= htmlspecialchars($date_from ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="date_to">Date de fin</label>
                    <input type="date" id="date_to" name="date_to" class="form-control" 
                           value="<?= htmlspecialchars($date_to ?? '') ?>">
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="availability">Disponibilité</label>
                    <select id="availability" name="availability" class="form-control">
                        <option value="">Tous les événements</option>
                        <option value="available" <?= ($availability=='available')?'selected':'' ?>>Disponibles</option>
                        <option value="full" <?= ($availability=='full')?'selected':'' ?>>Complets</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass"></i> Appliquer les filtres
                    </button>
                    <a href="evenements.php" class="btn btn-outline">
                        <i class="fa-solid fa-rotate-left"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        <!-- Events Grid -->
        <div class="events-grid">
            <?php if (count($liste) == 0): ?>
                <div class="empty-state">
                    <i class="fa-regular fa-calendar-xmark"></i>
                    <h3>Aucun événement trouvé</h3>
                    <p>Aucun événement ne correspond à vos critères de recherche. Essayez de modifier vos filtres ou réinitialisez la recherche.</p>
                    <a href="evenements.php" class="btn btn-primary">
                        <i class="fa-solid fa-rotate-left"></i> Voir tous les événements
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($liste as $ev): ?>
                    <div class="event-card">
                        <div class="event-image-container">
                            <?php if (!empty($ev['image'])): 
    // build URL and escape filename safely
    $img_filename = $ev['image'];
    $img_url = 'http://localhost/Gcategori/uploads/' . rawurlencode($img_filename);
?>
    <img src="<?= $img_url ?>"
         class="event-image"
         alt="<?= htmlspecialchars($ev['nom_evenement']) ?>"
         onerror="this.src='https://via.placeholder.com/400x220/1e1e1e/666666?text=Image+non+disponible'">
<?php else: ?>
    <img src="https://via.placeholder.com/400x220/1e1e1e/666666?text=Pas+d%27image"
         class="event-image"
         alt="Aucune image">
<?php endif; ?>

                            
                            <?php if ($ev['nombre_inscrits'] < $ev['nombre_places']): ?>
                                <span class="event-badge badge-available">
                                    <i class="fa-solid fa-check"></i> Disponible
                                </span>
                            <?php else: ?>
                                <span class="event-badge badge-full">
                                    <i class="fa-solid fa-xmark"></i> Complet
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="event-content">
                            <h3 class="event-title"><?= htmlspecialchars($ev['nom_evenement']) ?></h3>
                            
                            <div class="event-meta">
                                <div class="event-meta-item">
                                    <i class="fa-regular fa-calendar"></i>
                                    <span><?= date('d/m/Y', strtotime($ev['date_evenement'])) ?></span>
                                </div>
                                <div class="event-meta-item">
                                    <i class="fa-regular fa-user"></i>
                                    <span><?= (int)$ev['nombre_inscrits'] ?> inscrits</span>
                                </div>
                                <div class="event-meta-item">
                                    <i class="fa-solid fa-chair"></i>
                                    <span><?= (int)$ev['nombre_places'] ?> places totales</span>
                                </div>
                            </div>
                            
                            <div class="event-places">
                                <span class="places-info">Places restantes :</span>
                                <span class="places-remaining highlight"><?= (int)$ev['nombre_places'] - (int)$ev['nombre_inscrits'] ?></span>
                            </div>
                            
                            <div class="event-actions">
                                <a class="btn-details" 
                                   href="http://localhost/Gcategori/view/frontoffice/evenement_detail.php?id=<?= $ev['id_evenement'] ?>">
                                    <i class="fa-regular fa-eye"></i> Voir détails
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-content">
            <p class="footer-text">&copy; 2024 Supportini.tn - Tous droits réservés</p>
        </div>
    </footer>

    <script>
        // Gestion des erreurs d'images
        document.addEventListener('DOMContentLoaded', function() {
            const images = document.querySelectorAll('.event-image');
            images.forEach(img => {
                img.addEventListener('error', function() {
                    this.src = 'https://via.placeholder.com/400x220/1e1e1e/666666?text=Image+non+disponible';
                });
            });
        });
    </script>
    <script>
/* ========== Voice control adapted for evenements.php ========== */
/* Feature detection + mic permission */
if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
    alert("Votre navigateur ne supporte pas la reconnaissance vocale.");
} else {
    navigator.mediaDevices.getUserMedia({ audio: true })
      .then(stream => stream.getTracks().forEach(track => track.stop()))
      .catch(err => {
        console.warn("Micro non accessible:", err);
        // ne pas alerter trop fort pour UX, mais avertir si nécessaire
      });
}

/* SpeechRecognition instantiation */
const SpeechRec = window.SpeechRecognition || window.webkitSpeechRecognition;
if (!SpeechRec) {
    // degrade gracefully : cache les boutons ou informe l'utilisateur
    document.getElementById('output').textContent = "SpeechRecognition non supporté par votre navigateur.";
} else {
    const recog = new SpeechRec();
    recog.lang = "fr-FR";
    recog.continuous = false;
    recog.interimResults = false;

    // DOM
    const btnStart = document.getElementById("start");
    const btnStop  = document.getElementById("stop");
    const outputEl = document.getElementById("output");
    let youtubeWindow = null;

    // Start / Stop handlers
    btnStart.addEventListener('click', () => {
        try {
            recog.start();
            btnStart.disabled = true;
            btnStop.disabled  = false;
            outputEl.textContent = "🎧 Écoute en cours...";
        } catch (err) {
            console.error(err);
            outputEl.textContent = "Erreur démarrage vocal : " + err.message;
        }
    });
    btnStop.addEventListener('click', () => {
        recog.stop();
    });

    recog.onresult = e => {
        const txt = e.results[0][0].transcript.trim();
        outputEl.textContent = `Vous avez dit : « ${txt} »`;
        speak(`Vous avez dit : ${txt}`);
        handleCommand(txt.toLowerCase());
        btnStart.disabled = false;
        btnStop.disabled  = true;
    };
    recog.onerror = e => {
        outputEl.textContent = "❌ Erreur : " + (e.error || "inconnue");
        btnStart.disabled = false;
        btnStop.disabled  = true;
    };
    recog.onend = () => {
        btnStart.disabled = false;
        btnStop.disabled  = true;
    };

    function speak(text) {
        if (!window.speechSynthesis) return;
        const utter = new SpeechSynthesisUtterance(text);
        utter.lang = "fr-FR";
        window.speechSynthesis.speak(utter);
    }

    /* ---- Commandes ----
       - salut / bonjour
       - météo (utilise API déjà dans ton code)
       - ouvrir / fermer YouTube
       - rafraîchir, scroll up/down
       - rechercher <terme> -> rempli le champ 'search-input' et soumet le form
       - voir détails <nom événement> -> ouvre le bouton 'Voir détails' du event-card correspondant (recherche par titre)
    */
    function findEventCardByTitle(prefix) {
        // retourne le premier .event-card dont le titre commence par prefix
        const cards = document.querySelectorAll('.event-card');
        for (const card of cards) {
            const titleEl = card.querySelector('.event-title');
            if (!titleEl) continue;
            const title = titleEl.textContent.trim().toLowerCase();
            if (title.startsWith(prefix) || title.includes(prefix)) return card;
        }
        return null;
    }

    const commands = [
        { triggers: ["bonjour", "salut"], action: () => speak("Bonjour à vous !") },
        { triggers: ["comment ça va", "ça va"], action: () => speak("Je vais bien, merci ! Et vous ?") },
        { triggers: ["ouvrir youtube", "ouvre youtube"], action: () => {
            if (!youtubeWindow || youtubeWindow.closed) {
              youtubeWindow = window.open("https://www.youtube.com", "youtubeWindow");
              speak("YouTube est ouvert.");
            } else speak("YouTube est déjà ouvert.");
          }
        },
        { triggers: ["fermer youtube", "quitter youtube", "ferme youtube"], action: () => {
            if (youtubeWindow && !youtubeWindow.closed) {
              youtubeWindow.close();
              speak("YouTube est fermé.");
            } else speak("Aucune fenêtre YouTube à fermer.");
          }
        },
        { triggers: ["aller à l'accueil", "accueil", "retour à l'accueil"], action: () => { window.location.href = "/"; speak("Retour à l'accueil."); } },
        { triggers: ["actualise la page", "rafraîchir la page", "refresh"], action: () => { location.reload(); speak("Page rafraîchie."); } },
        { triggers: ["scrolle vers le bas", "descend", "bas"], action: () => { window.scrollBy(0, window.innerHeight); speak("Je descends la page."); } },
        { triggers: ["scrolle vers le haut", "remonte", "haut"], action: () => { window.scrollBy(0, -window.innerHeight); speak("Je remonte la page."); } },
        { triggers: ["donne la météo", "quelle est la météo", "donne moi la météo", "météo", "meteo"], action: () => {
            // réutilise ton endpoint openweathermap (attention à la clé côté client - idéalement côté serveur)
            const city = "Tunis";
            const apiKey = "cef20fce78232192d31686727cd1eb8c"; // déjà présent dans ton code : garder si ok pour toi
            fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric&lang=fr`)
              .then(res => res.json())
              .then(data => {
                if (data && data.weather && data.weather.length) {
                  const desc = data.weather[0].description;
                  const temp = data.main.temp;
                  speak(`À ${city}, il fait ${temp} degrés avec ${desc}.`);
                } else speak("Je n'ai pas pu obtenir la météo.");
              })
              .catch(() => speak("Erreur lors de la récupération de la météo."));
          }
        },
        { triggers: ["rechercher", "cherche", "cherche la", "cherche le"], action: (txt) => {
            // extrait le mot après le trigger
            let mot = txt.replace(/^(rechercher|cherche|cherche la|cherche le)\s*/, "").trim();
            if (!mot) { speak("Que voulez-vous rechercher ?"); return; }
            const input = document.getElementById("search-input");
            const form  = document.getElementById("search-form");
            if (input && form) {
                input.value = mot;
                form.submit();
                speak(`Recherche de ${mot}`);
            } else speak("Champ de recherche introuvable.");
          }
        },
        {
  triggers: ["categorie", "catégorie", "categories", "catégories", "ouvrir categorie", "ouvrir catégorie"],
  action: () => {
      window.location.href = "categories.php";
      speak("Ouverture de la page des catégories.");
  }
},

       { 
  triggers: ["chercher sur google","cherche sur google","rechercher sur google","cherche google","rechercher google"], 
  action: (txt) => {
      const term = txt.replace(/^(chercher sur google|cherche sur google|rechercher sur google|cherche google|rechercher google)\s*/i, "").trim();
      if (!term) { 
          speak("Quel est le terme à chercher sur Google ?"); 
          return; 
      }
      const url = `https://www.google.com/search?q=${encodeURIComponent(term)}`;
      window.open(url, "_blank");
      speak(`Recherche ${term} sur Google.`);
  }
},

{ 
  triggers: ["statistique", "statistiques"], 
  action: () => {
      window.location.href = "statistique.php";
      speak("Ouverture de la page Statistiques.");
  }
},

        // Voir détails d'un événement : "voir détails <nom>" ou "ouvrir <nom>"
        { triggers: ["voir détails", "voir detail", "ouvrir evenement", "ouvrir l'événement", "ouvrir"], action: (txt) => {
            // extrait la partie après le trigger
            const foundTrigger = ["voir détails", "voir detail", "ouvrir evenement", "ouvrir l'événement", "ouvrir"]
                .find(t => txt.startsWith(t));
            let target = txt.replace(foundTrigger, "").trim();
            if (!target) {
                speak("Quel événement voulez-vous voir ?");
                return;
            }
            target = target.toLowerCase();
            const card = findEventCardByTitle(target);
            if (card) {
                const detailsLink = card.querySelector('.btn-details') || card.querySelector('a');
                if (detailsLink) {
                    // ouvre la page de détail dans la même fenêtre
                    window.location.href = detailsLink.href;
                    speak(`Ouverture de ${target}`);
                } else speak("Lien de détail introuvable pour cet événement.");
            } else {
                speak("Événement non trouvé sur la page. Essayez avec un autre nom ou modifiez les filtres.");
            }
          }
        }
    ];

    // fonction de routage principale
    function handleCommand(txt) {
        for (const cmd of commands) {
            if (cmd.triggers.some(t => txt.startsWith(t))) {
                cmd.action(txt);
                return;
            }
        }
        // fallback : si commence par un mot libre, essayer la recherche
        if (txt && txt.length > 2) {
            // remplir la recherche par défaut
            const input = document.getElementById("search-input");
            const form  = document.getElementById("search-form");
            if (input && form) {
                input.value = txt;
                form.submit();
                speak(`Recherche de ${txt}`);
                return;
            }
        }
        speak("Commande non reconnue.");
    }
}
</script>

</body>
</html>