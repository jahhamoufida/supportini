<?php

// 💡 Charger les fichiers PHPMailer correctement
require_once __DIR__ . '/../phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../phpmailer/src/SMTP.php';
require_once __DIR__ . '/../phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer {

    public static function envoyerConfirmation($emailClient, $nomClient, $evenement) {

        // 💡 ICI était ton erreur : classe introuvable car mauvais require
        $mail = new PHPMailer(true);

        try {
            // SMTP CONFIG
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = "jahhamoufida64@gmail.com"; // 🔴 Mets ton email Gmail
            $mail->Password = "uvlrwxiptxhrdgrz";     // 🔴 Mot de passe application Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            

            // EXPÉDITEUR
            $mail->setFrom("tonEmail@gmail.com", "Réservations - Gcategori");

            // DESTINATAIRE
            $mail->addAddress($emailClient);

            // CONTENU
            $mail->isHTML(true);
            $mail->Subject = "Confirmation de votre réservation";
            $mail->Body = "
                Bonjour <strong>$nomClient</strong>,<br><br>
                Votre réservation pour l'événement <strong>$evenement</strong> a été confirmée 🎉.<br><br>
                Merci pour votre confiance.<br><br>
                Cordialement,<br>
                <strong>L'équipe Gcategori</strong>
            ";

            $mail->send();
            return "OK";

        } catch (Exception $e) {
            return "Erreur lors de l'envoi du mail : " . $mail->ErrorInfo;
        }
    }
}
