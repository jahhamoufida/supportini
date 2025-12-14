<?php
class Evenement {

    private ?int $id_evenement = null;
    private string $nom_evenement;
    private string $date_evenement;
    private int $nombre_places;
    private int $nombre_inscrits;
    private ?string $image;
    private ?int $id_categorie;   // ✅ ÉTAIT MANQUANT !
    private ?string $description;

    public function __construct($nom, $date, $places, $inscrits, $image, $description, $id_categorie = null)
    {
        $this->nom_evenement = $nom;
        $this->date_evenement = $date;
        $this->nombre_places = $places;
        $this->nombre_inscrits = $inscrits;
        $this->image = $image;
        $this->description = $description;
        $this->id_categorie = $id_categorie;   // ✅ IMPORTANT
    }

    // ---- GETTERS ----
    public function getIdEvenement(): ?int { return $this->id_evenement; }
    public function getNomEvenement(): string { return $this->nom_evenement; }
    public function getDateEvenement(): string { return $this->date_evenement; }
    public function getNombrePlaces(): int { return $this->nombre_places; }
    public function getNombreInscrits(): int { return $this->nombre_inscrits; }
    public function getImage(): ?string { return $this->image; }
    public function getIdCategorie(): ?int { return $this->id_categorie; }
    public function getDescription(): ?string { return $this->description; }

    // ---- SETTERS ----
    public function setIdEvenement(int $id): void { $this->id_evenement = $id; }
    public function setNomEvenement(string $n): void { $this->nom_evenement = $n; }
    public function setDateEvenement(string $d): void { $this->date_evenement = $d; }
    public function setNombrePlaces(int $p): void { $this->nombre_places = $p; }
    public function setNombreInscrits(int $i): void { $this->nombre_inscrits = $i; }
    public function setImage(?string $img): void { $this->image = $img; }
    public function setIdCategorie(?int $id): void { $this->id_categorie = $id; }
    public function setDescription(?string $desc): void { $this->description = $desc; }
}
?>
