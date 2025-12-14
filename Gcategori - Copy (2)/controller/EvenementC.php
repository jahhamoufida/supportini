<?php
require_once __DIR__ . '/../auth/config.php';
require_once __DIR__ . '/../model/Evenement.php';

class EvenementC {

    /* -------------------------------------------
        LISTE DES ÉVÉNEMENTS
    --------------------------------------------*/
    public function listEvenements() {
        $db = config::getConnexion();
        $sql = "SELECT * FROM evenement ORDER BY date_evenement ASC";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -------------------------------------------
        GET PAR ID
    --------------------------------------------*/
    public function getEvenement($id) {
        $db = config::getConnexion();
        $stmt = $db->prepare("SELECT * FROM evenement WHERE id_evenement = :id");
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getEvenementById($id) {
        return $this->getEvenement($id);
    }

    /* -------------------------------------------
        AJOUT D'ÉVÉNEMENT
    --------------------------------------------*/
    public function addEvenement(Evenement $e) {
        $db = config::getConnexion();

        $sql = "INSERT INTO evenement 
        (nom_evenement, date_evenement, nombre_places, nombre_inscrits, image, id_categorie, description)
        VALUES 
        (:nom, :date, :places, :inscrits, :image, :idcat, :description)";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            ':nom'         => $e->getNomEvenement(),
            ':date'        => $e->getDateEvenement(),
            ':places'      => $e->getNombrePlaces(),
            ':inscrits'    => $e->getNombreInscrits(),
            ':image'       => $e->getImage(),
            ':idcat'       => $e->getIdCategorie(),
            ':description' => $e->getDescription()
        ]);
    }

    /* -------------------------------------------
        MISE À JOUR D'ÉVÉNEMENT
    --------------------------------------------*/
    public function updateEvenement($id, Evenement $e) {
        $db = config::getConnexion();

        $sql = "UPDATE evenement SET 
                nom_evenement = :nom,
                date_evenement = :date,
                nombre_places = :places,
                nombre_inscrits = :inscrits,
                image = :image,
                id_categorie = :idcat,
                description = :description
                WHERE id_evenement = :id";

        $stmt = $db->prepare($sql);

        $stmt->execute([
            ':id'          => $id,
            ':nom'         => $e->getNomEvenement(),
            ':date'        => $e->getDateEvenement(),
            ':places'      => $e->getNombrePlaces(),
            ':inscrits'    => $e->getNombreInscrits(),
            ':image'       => $e->getImage(),
            ':idcat'       => $e->getIdCategorie(),
            ':description' => $e->getDescription()
        ]);
    }

    /* -------------------------------------------
        SUPPRESSION D'ÉVÉNEMENT
    --------------------------------------------*/
    public function deleteEvenement($id) {

        // Récupérer pour supprimer l'image
        $ev = $this->getEvenement($id);

        if ($ev && !empty($ev['image'])) {
            $path = __DIR__ . '/../uploads/' . $ev['image'];

            if (file_exists($path)) {
                @unlink($path);
            }
        }

        // Supprimer l'événement
        $db = config::getConnexion();
        $stmt = $db->prepare("DELETE FROM evenement WHERE id_evenement = :id");
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    /* -------------------------------------------
        FILTRAGE AVANCÉ
    --------------------------------------------*/
    public function filterEvenements($name = null, $date_from = null, $date_to = null, $availability = null) {
        $db = config::getConnexion();
        $clauses = [];
        $params = [];

        // Filtre nom
        if (!empty($name)) {
            $clauses[] = "nom_evenement LIKE :name";
            $params[':name'] = "%$name%";
        }

        // Date min
        if (!empty($date_from)) {
            $clauses[] = "date_evenement >= :df";
            $params[':df'] = $date_from;
        }

        // Date max
        if (!empty($date_to)) {
            $clauses[] = "date_evenement <= :dt";
            $params[':dt'] = $date_to;
        }

        // Disponibilité
        if ($availability === 'available') {
            $clauses[] = "nombre_inscrits < nombre_places";
        } elseif ($availability === 'full') {
            $clauses[] = "nombre_inscrits >= nombre_places";
        }

        // Assemblage de la requête
        $sql = "SELECT * FROM evenement";

        if (!empty($clauses)) {
            $sql .= " WHERE " . implode(" AND ", $clauses);
        }

        $sql .= " ORDER BY date_evenement ASC";

        // Exécution
        $stmt = $db->prepare($sql);

        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
