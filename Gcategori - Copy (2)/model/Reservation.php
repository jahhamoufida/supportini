<?php

class Reservation {
    private ?int $id_reservation = null;
    private int $id_evenement;
    private string $nom;
    private string $prenom;
    private string $email;
    private int $nb_places;

    public function __construct($id_evenement, $nom, $prenom, $email, $nb_places)
    {
        $this->id_evenement = $id_evenement;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->nb_places = $nb_places;
    }

    // Getters
    public function getIdReservation(): ?int { return $this->id_reservation; }
    public function getIdEvenement(): int { return $this->id_evenement; }
    public function getNom(): string { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getEmail(): string { return $this->email; }
    public function getNbPlaces(): int { return $this->nb_places; }

    // Setters
    public function setIdReservation(int $id): void { $this->id_reservation = $id; }
}
