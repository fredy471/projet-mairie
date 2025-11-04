<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
ob_start();

require '../bdd.php';
require '../../../vendor/autoload.php';



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(empty(($_SESSION['role'])) && $_SESSION['role']!='agent'){

   header('location:../logout.php');
   exit;
}else{
    if(isset($_GET['id']) && is_numeric($_GET['id'])){

        $id= $_GET['id'] ? : '';
        $id_demande=$_GET['id_demande'] ? :'';

        if($id==0){
            $stat='refuse';
            $refuse='UPDATE rendez_vous SET rend_statut=0 WHERE id_demande= :id_demande';
            $stt=$conn->prepare($refuse);
            $stt->execute(['id_demande'=>$id_demande]);
        }elseif($id==1){
            $stat='acepte';
            $acepte='UPDATE rendez_vous SET rend_statut=1 WHERE id_demande= :id_demande';
            $stt=$conn->prepare($acepte);
            $stt->execute(['id_demande'=>$id_demande]);
        }else{
          echo 'error 404';  
        }

         // Récupération de l'email du citoyen
         $sql = "SELECT email, code_demande, nom, types FROM demandes d JOIN users u ON d.id_citoyen= u.id_user WHERE id_demande= :id_demande";
         $stmtEmail = $conn->prepare($sql);
         $stmtEmail->bindParam(':id_demande', $id_demande, PDO::PARAM_INT);
         $stmtEmail->execute();
         $citoyen = $stmtEmail->fetch(PDO::FETCH_ASSOC);

         //recuperation des donnees du rendez-vous

         $took='SELECT * FROM rendez_vous WHERE id_demande= :id_demande';
         $stmt=$conn->prepare($took);
         $stmt->execute(['id_demande'=>$id_demande]);
         $table=$stmt->fetch(PDO::FETCH_ASSOC);


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
            
            if($stat=='acepte'){

            
            $mail->Subject = 'Votre rendez-vous a été validé ✅';
            $mail->Body = "<p>Bonjour <b>{$citoyen['nom']}</b>,</p>
                               <p>Le rendez-vous de la demande : (Code de demande: {$code_demande}) concernant  {$citoyen['types']}a été validé.Vous pouvez passer a la mairie  le {$table['date']} à {$table['heure']} pour le retrait de votre document.</p>
                               <p>Merci de votre confiance.</p>
                               <p><b>La Mairie</b></p>";
            $mail->AltBody = "Bonjour {$citoyen['nom']}, votre rendez-vous a été validée par la mairie.";
            }else{

                $mail->Subject = 'Votre rendez-vous a été rejeté ✅';
                $mail->Body = "<p>Bonjour <b>{$citoyen['nom']}</b>,</p>
                                   <p>Le rendez-vous de la demande : (Code de demande: {$code_demande}) concernant  {$citoyen['types']}a été refusé pour des raisons administratives.Vueillez vous connecter afin de prendre un autre rendez-vous.</p>
                                   <p>Merci de votre confiance.</p>
                                   <p><b>La Mairie</b></p>";
                $mail->AltBody = "Bonjour {$citoyen['nom']}, votre rendez-vous a ete rejete.";
            }

            $mail->send();
        } catch (Exception $e) {
            $error[] = "❌ Mailerror : {$mail->ErrorInfo}";
        }


    header('location:rendez_vous.php');
    exit;

    }
}

?>