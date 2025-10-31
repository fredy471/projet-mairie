<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../bdd.php';
require '../../../vendor/autoload.php';



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error[] = '';

if (isset($_SESSION['id']) && $_SESSION['role'] == 'agent') {
    $id_agent = $_SESSION['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {

        $action = $_POST['action'];
        $id_demande = htmlspecialchars(trim($_POST['id_demande'] ?? ''));
        $commentaire = htmlspecialchars(trim($_POST['commentaire'] ?? ''));

        if ($action === 'valider') {
            $statut = 'valide';
        } elseif ($action === 'refuser') {
            $statut = 'rejete';
        } else {
            $statut = 'en attente';
        }

        $req = 'UPDATE demandes SET  id_agent=:id_agent, commentaire=:commentaire, statut=:statut WHERE id_demande=:id_demande';
        $stmt = $conn->prepare($req);
        $stmt->bindParam(':statut', $statut, PDO::PARAM_STR);
        $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
        $stmt->bindParam(':id_agent', $id_agent, PDO::PARAM_INT);
        $stmt->bindParam(':id_demande', $id_demande, PDO::PARAM_INT);

        if ($stmt->execute()) {
            if ($statut === 'valide') {
                // Récupération de l'email du citoyen
                $sql = "SELECT email, code_demande, nom FROM demandes d JOIN users u ON d.id_citoyen= u.id_user WHERE id_demande= :id_demande";
                $stmtEmail = $conn->prepare($sql);
                $stmtEmail->bindParam(':id_demande', $id_demande, PDO::PARAM_INT);
                $stmtEmail->execute();
                $citoyen = $stmtEmail->fetch(PDO::FETCH_ASSOC);

                if ($citoyen) {
                    $mail = new PHPMailer(true);

                    try {
                        // 🔧 Configuration SMTP
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'fredyaurel16@gmail.com'; // ton adresse Gmail
                        $mail->Password = 'rxov wsse dzsn jess'; // mot de passe d’application Gmail
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // 👤 Expéditeur et destinataire
                        $mail->setFrom('fredyaurel16@gmail.com', 'Mairie');
                        $mail->addAddress($citoyen['email'], $citoyen['nom']);

                        // 💬 Contenu du message
                        $mail->isHTML(true);
                        $mail->CharSet = 'UTF-8'; // <- ici tu mets le charset
                        $mail->Subject = 'Votre demande a été validée ✅';
                        $mail->Body = "<p>Bonjour <b>{$citoyen['nom']}</b>,</p>
                                           <p>Votre demande (Code de demande: {$code_demande}) a été validée par la mairie.Vous pouvez maintenant vous connectez pour prendre un rendez-vous</p>
                                           <p>Merci de votre confiance.</p>
                                           <p><b>La Mairie</b></p>";
                        $mail->AltBody = "Bonjour {$citoyen['nom']}, votre demande a été validée par la mairie.";

                        $mail->send();
                    } catch (Exception $e) {
                        $error[] = "❌ Mailerror : {$mail->ErrorInfo}";
                    }
                }
            } else {
                $error[] = 'donnees invalides pour le traitement';
            }

            // ✅ Redirection hors du if
            header('location:demande_attente.php?success=1');
            exit;
        } else {
            $error[] = 'citoyen null';
        }
    } else {
        echo "❌ Erreur SQL : ";
        print_r($stmt->errorInfo());
    }
} else {
    header('location:../login.php');
    exit;
}
