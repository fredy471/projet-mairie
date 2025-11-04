<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../bdd.php';

$error = [];
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'mayor') {
    header('location:../login.php');
    exit;
} else {

    //recuperation des donnees de l'agent


    //traitement du formulaire de modification
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (isset($_POST['send'])) {


            $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
            $prenom = htmlspecialchars(trim($_POST['prenom'] ?? ''));
            $email = htmlspecialchars(trim($_POST['email'] ?? ''));
            $tel = htmlspecialchars(trim($_POST['tel'] ?? ''));
            $adresse = htmlspecialchars(trim($_POST['adresse'] ?? ''));
            $psd=password_hash($_POST['psd'],PASSWORD_DEFAULT);
            $role='agent';

           
                //mise a jour des donnees
                $create_sql = "INSERT INTO users (nom, prenom, adresse, tel, email, psd, role) VALUES (:nom, :prenom, :adresse, :tel, :email, :psd, :role)";

                $stt = $conn->prepare($create_sql);
                $stt->bindParam(':nom', $nom, PDO::PARAM_STR);
                $stt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
                $stt->bindParam(':adresse', $adresse, PDO::PARAM_STR);
                $stt->bindParam(':tel', $tel, PDO::PARAM_STR);
                $stt->bindParam(':email', $email, PDO::PARAM_STR);
                $stt->bindParam(':psd', $psd,PDO::PARAM_STR);
                $stt->bindParam(':role',$role,PDO::PARAM_STR);
               

                if ($stt->execute()) {
                    header('location:agent_list.php');
                    exit;
                } else {
                    $error[] = 'Erreur lors de la mise a jour';
                }
           
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
    </head>

    <body>

        <main class="my-5">
            <div class="container" style='margin-top:100px; margin-bottom:40px;'>
                <div class="row">
                    <div class="col-md-6 mx-auto" style='background-color:#fff; box-shadow:1px 1px 5px #7d7d7d;'>
                        <div class="col-12">
                            <div class="d-flex justify-content-center mt-4">
                                <img src="../../assets/images/post-1.jpg" class="rounded-circle img-thumbnail shadow" alt="profile image" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="justify-content-center col-md-12 my-5">


                            <form action="" method="POST">
                                <div class="form-group">
                                    <label for="nom" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="nom" name="nom"  required>
                                </div>
                                <div class="form-group">
                                    <label for="prenom" class="form-label">Prénom</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom"  required>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"  required>
                                </div>
                                <div class="form-group">
                                    <label for="tel" class="form-label">Numéro de téléphone</label>
                                    <input type="text" class="form-control" id="tel" name="tel"  required>
                                </div>
                                <div class="form-group">
                                    <label for="adresse" class="form-label">Adresse</label>
                                    <input type="text" class="form-control" id="adresse" name="adresse"  required>
                                </div>
                                <div class="form-group">
                                    <label for="psd" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control" id="psd" name="psd"  required>
                                </div>
                                


                                <button type="submit" class="btn btn-primary" style="background-color:#2C3E91; border:none;" name='send'>Enregistrer les modifications</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

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
    </body>

    </html>
<?php
}

?>