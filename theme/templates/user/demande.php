<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../bdd.php';

$error = [];
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'citoyen') {
    header('location:../logout.php');
    exit;
} else {

    //recuperation des donnees de l'utilisateur

    $sql = "SELECT * FROM users WHERE id_user=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    $table = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['send'])) {
            //traitement de donnees
            $types = htmlspecialchars($_POST['types']);
            $commentaire = htmlspecialchars($_POST['commentaire']);

            //gestion du fichier
            $file_name = $_FILES['file']['name'];
            $file_tmp = $_FILES['file']['tmp_name'];
            $file_size = $_FILES['file']['size'];
            $file_error = $_FILES['file']['error'];


            if ($file_size <= 2 * 1024 * 1024) { //2MB
                $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx');

                if ($file_error === 0) {
                    if ($file_size <= 2 * 1024 * 1024) { //2MB
                        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
                        $allowed = array('jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx');

                        if (in_array(strtolower($file_ext), $allowed)) {
                            $new_file_name = uniqid('', true) . "." . $file_ext;
                            $file_destination = '../../assets/images/uploads/' . $new_file_name;

                            if (!is_dir('../../assets/images/uploads/')) {
                                mkdir('../../assets/images/uploads/', 0777, true);
                            }

                            if (move_uploaded_file($file_tmp, $file_destination)) {
                                //insertion dans la base de donnees
                                $code = "DEM-" . date('Ymd') . "-" . strtoupper(substr(uniqid(), -4));
                                $insert_sql = "INSERT INTO demandes(code_demande,id_citoyen,types,piece,commentaire) VALUES(:code,:id_citoyen,:types,:piece,:commentaire)";
                                $stmt = $conn->prepare($insert_sql);
                                $stmt->bindParam(':code', $code, PDO::PARAM_STR);
                                $stmt->bindParam(':id_citoyen', $_SESSION['id'], PDO::PARAM_INT);
                                $stmt->bindParam(':types', $types, PDO::PARAM_STR);
                                $stmt->bindParam(':piece', $new_file_name, PDO::PARAM_STR);
                                $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);

                                if ($stmt->execute()) {
                                    $_SESSION['success_message'] = true;
                                    $_SESSION['success_code'] = $code;
                                    header('Location: demande.php?success=1');
                                    exit;
                                } else {
                                    $error[] = 'Erreur lors de l\'enregistrement';
                                }
                            } else {
                                $error[] = 'Erreur lors du téléchargement du fichier';
                            }
                        } else {
                            $error[] = 'Type de fichier non autorisé';
                        }
                    } else {
                        $error[] = 'Le fichier est trop volumineux (max 2MB)';
                    }
                }
            } else {
                $error[] = 'Le fichier est trop volumineux';
            }
        } else {
            $error[] = 'Vous ne pouvez pas envoyer un formulaire vide';
        }
    }
?>

    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Responsive Admin Dashboard Template">
        <meta name="keywords" content="admin,dashboard">
        <meta name="author" content="stacks">

        <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->


        <!-- Title -->
        <title>e-services | AM</title>

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
        <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">





        <!-- Theme Styles -->
        <link href="../../assets/css/connect.min.css" rel="stylesheet">
        <link href="../../assets/css/admin2.css" rel="stylesheet">
        <link href="../../assets/css/dark_theme.css" rel="stylesheet">
        <link href="../../assets/css/custom.css" rel="stylesheet">
        <style>
            .success-notification {
                position: fixed;
                top: 20px;
                right: 20px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 1.5rem 2rem;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
                z-index: 9999;
                min-width: 380px;
                animation: slideIn 0.5s ease;
            }

            .close-notif {
                position: absolute;
                top: 10px;
                right: 10px;
                width: 30px;
                height: 30px;
                background: rgba(255, 255, 255, 0.2);
                border: none;
                border-radius: 50%;
                color: white;
                font-size: 1.2rem;
                cursor: pointer;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .close-notif:hover {
                background: rgba(255, 255, 255, 0.3);
                transform: rotate(90deg);
            }

            .success-notification .icon-wrapper {
                width: 50px;
                height: 50px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
            }

            .success-notification h5 {
                margin-bottom: 0.75rem;
                font-weight: 600;
                text-align: center;
            }

            .success-notification p {
                margin: 0.25rem 0;
                opacity: 0.9;
                font-size: 0.9rem;
                text-align: center;
            }

            .success-notification .btn-light {
                width: 100%;
                margin-top: 0.5rem;
                font-weight: 600;
            }

            @keyframes slideIn {
                from {
                    opacity: 0;
                    transform: translateX(400px);
                }

                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            .success-notification.hide {
                animation: slideOut 0.5s ease forwards;
            }

            @keyframes slideOut {
                from {
                    opacity: 1;
                    transform: translateX(0);
                }

                to {
                    opacity: 0;
                    transform: translateX(400px);
                }
            }
        </style>



    </head>

    <body>
        <?php if (isset($_SESSION['success_message'])):
            $code_demande = $_SESSION['success_code'];
            unset($_SESSION['success_message']);
            unset($_SESSION['success_code']);
        ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Demande envoyée !',
                    text: 'Code de suivi: <?= $code_demande ?>',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                });
            </script>
        <?php endif; ?>
        <?php require 'header.php' ?>
        <main class="">

            <div class="container" style="margin-top:130px; margin-bottom:100px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center mx-auto my-5">
                            <h4>Bienvenue sur l'espace e-services de l'administration municipale</h4>
                        </div>

                        <div class="col-md-6 mx-auto" style='margin-left:10px; margin-right:10px; border-left:moccasin 1px solid; border-right:moccasin 1px solid;border-left: moccasin 1px solid; border-right: moccasin 1px solid; padding-top:40px; padding-bottom:10px;'>
                            <form action="" class="" method='POST' enctype="multipart/form-data">
                                <div class="text-center">
                                    <h6>Formulaire de demande</h6>
                                    <br>
                                    <small class="text-muted fst-italic" style='border-left:2px blue solid; background-color:antiquewhite; padding:5px;'>
                                        Remplissez soigneusement le formulaire ci-dessous pour soumettre votre demande en ligne.
                                    </small>
                                </div>

                                <?php if (!empty($error)): ?>
                                    <div class="alert alert-danger alert-dismissible fade show mt-3">
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                        <ul class="mb-0">
                                            <?php foreach ($error as $err): ?>
                                                <li><?= htmlspecialchars($err) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>


                                <div class="my-3">
                                    <label for="email" class="form-label">
                                        <i class="fa-solid fa-envelope"></i> Votre email
                                    </label>
                                    <input type="email" class="form-control" id="email" name='email' readonly value='<?= htmlspecialchars($table['email']) ?>'>
                                </div>

                                <div class="my-3">
                                    <label for="types" class="form-label">
                                        <i class="fa-solid fa-envelope"></i> Type de document demandé
                                    </label>
                                    <input type="text" class="form-control" id="types" name='types' required placeholder="Ex: Acte de naissance, acte de mariage, etc.">
                                </div>

                                <div class="my-3">
                                    <label for="file" class="form-label">
                                        <i class="fa-solid fa-envelope"></i> Piece jointe
                                    </label>
                                    <input type="file" class="form-control" id="file" name='file' required>
                                </div>

                                <div class="my-3">
                                    <label for="commentaire" class="form-label">
                                        <i class="fa-solid fa-envelope"></i> Commentaire (Optionnel)
                                    </label>
                                    <textarea type="text" class="form-control" id="commentaire" name='commentaire'></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block" name='send'>
                                    <i class="fa-solid fa-paper-plane"></i> Soumettre la demande
                                </button>
                            </form>
                            <hr>
                            <div class="col-md-12">
                                <small>Vous pouvez egalement suivre vos demandes ici</small>
                            </div>
                            <div class="col-md-12 my-5">
                                <a href="suivi_demande.php" class="btn btn-info btn-block">Suivre mes demandes</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </main>

        <footer class="text-white pt-5 pb-4" style='background-color:#2C3E91;'>
            <div class="container text-md-left">
                <div class="row">

                    <!-- À propos -->
                    <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
                        <h5 class="text-uppercase mb-4 font-weight-bold">Portail Municipal</h5>
                        <p>
                            Simplifiez vos démarches administratives depuis chez vous. Suivi rapide et sécurisé, 24h/24.
                        </p>
                    </div>

                    <!-- Liens rapides -->
                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                        <h5 class="text-uppercase mb-4 font-weight-bold">Liens utiles</h5>
                        <p><a href="#about" class="text-white" style="text-decoration:none;">Nos services</a></p>
                        <p><a href="#infos" class="text-white" style="text-decoration:none;">À propos</a></p>
                        <p><a href="contact.php" class="text-white" style="text-decoration:none;">Contact</a></p>
                        <p><a href="../logout.php" class="text-white" style="text-decoration:none;">Deconnexion</a></p>
                    </div>

                    <!-- Contact -->
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                        <h5 class="text-uppercase mb-4 font-weight-bold">Contact</h5>
                        <p><i class="fa fa-home mr-3"></i> 123 Rue de la Mairie, Ville</p>
                        <p><i class="fa fa-envelope mr-3"></i> contact@mairie.gov</p>
                        <p><i class="fa fa-phone mr-3"></i> +229 22 00 00 00</p>
                    </div>

                </div>

                <hr class="mb-4">

                <!-- Copyright -->
                <div class="row align-items-center">
                    <div class="col-md-7 col-lg-8">
                        <p>© 2025 Portail Municipal. Tous droits réservés.</p>
                    </div>
                    <div class="col-md-5 col-lg-4">
                        <!-- Réseaux sociaux -->
                        <div class="text-center text-md-right">
                            <a href="#" class="text-white me-4"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white me-4"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="text-white me-4"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>




        <!---Javascripts-->
        <script>
            function hideNotification() {
                const notification = document.getElementById('notification');
                notification.classList.add('hide');
                setTimeout(function() {
                    notification.remove();
                }, 500);
            }

            // Masquer automatiquement après 10 secondes
            setTimeout(hideNotification, 10000);
        </script>
        <script src="../../assets/plugins/jquery/jquery-3.4.1.min.js"></script>
        <script src="../../assets/plugins/bootstrap/popper.min.js"></script>
        <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="../../assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="../../assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
        <script src="../../assets/plugins/blockui/jquery.blockUI.js"></script>
        <script src="../../assets/plugins/flot/jquery.flot.min.js"></script>
        <script src="../../assets/plugins/flot/jquery.flot.time.min.js"></script>
        <script src="../../assets/plugins/flot/jquery.flot.symbol.min.js"></script>
        <script src="../../assets/plugins/flot/jquery.flot.resize.min.js"></script>
        <script src="../../assets/plugins/flot/jquery.flot.tooltip.min.js"></script>
        <script src="../../assets/js/connect.min.js"></script>
        <script src="../../assets/plugins/apexcharts/dist/apexcharts.min.js"></script>
        <script src="../../assets/js/pages/dashboard.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>


    </html>
<?php
}

?>