<?php
require_once __DIR__ . '/../auth/config.php';
require_once __DIR__ . '/../model/Reservation.php';

class ReservationC {

    public function ajouterReservation(Reservation $r)
    {
        $db = config::getConnexion();

        // 1) Vérifier places restantes
        $checkSQL = "SELECT nombre_places, nombre_inscrits FROM evenement WHERE id_evenement = ?";
        $req = $db->prepare($checkSQL);
        $req->execute([$r->getIdEvenement()]);
        $ev = $req->fetch();

        if (!$ev) {
            return "Événement introuvable";
        }

        $places_restantes = $ev['nombre_places'] - $ev['nombre_inscrits'];

        if ($r->getNbPlaces() > $places_restantes) {
            return "Impossible : Places restantes = $places_restantes";
        }

        // 2) Insérer réservation
        $sql = "INSERT INTO reservation (id_evenement, nom, prenom, email, nb_places)
                VALUES (:id_ev, :nom, :prenom, :email, :nb_places)";
        $query = $db->prepare($sql);

        $query->execute([
            ':id_ev' => $r->getIdEvenement(),
            ':nom'   => $r->getNom(),
            ':prenom'=> $r->getPrenom(),
            ':email' => $r->getEmail(),
            ':nb_places' => $r->getNbPlaces(),
        ]);

        // 3) Mise à jour des inscrits
        $updateSQL = "UPDATE evenement
                      SET nombre_inscrits = nombre_inscrits + ?
                      WHERE id_evenement = ?";
        $up = $db->prepare($updateSQL);
        $up->execute([$r->getNbPlaces(), $r->getIdEvenement()]);

        return "OK";
    }
}
