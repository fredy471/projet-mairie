<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../bdd.php';

$error=[];
if(!isset($_SESSION['id']) || $_SESSION['role']!='agent'){
    header('location:../login.php');
    exit;
}else{

    //recuperation des donnees de l'utilisateur

    $sql="SELECT * FROM users WHERE id_user=:id";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':id',$_SESSION['id'],PDO::PARAM_INT);
    $stmt->execute();
    $table=$stmt->fetch(PDO::FETCH_ASSOC);

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
            <div class="container"  style='margin-top:100px; margin-bottom:40px;'>
                <div class="row">
                   <div class="col-md-6 mx-auto" style='background-color:#fff; box-shadow:1px 1px 5px #7d7d7d;'>
                        <div class="col-12">
                            <div class="d-flex justify-content-center mt-4">
                                <img src="../../assets/images/post-1.jpg"  class="rounded-circle img-thumbnail shadow" alt="profile image" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                        </div>
                        <div class="justify-content-center col-md-12 my-5">
                                <div class="d-flex" style=' padding:5px;'>
                                        <p class='col-10'><strong class="">Nom</strong>: <?=htmlspecialchars($table['nom']) ?></p>
                                </div>
                                <div class="d-flex" style=' padding:5px;'>
                                        <p class='col-10'><strong>Prenom:</strong> <?=htmlspecialchars($table['prenom']) ?></p>
                                </div>
                                <div class="d-flex" style=' padding:5px;'>
                                        <p class='col-10'><strong>Email:</strong> <?=htmlspecialchars($table['email']) ?></p>
                                </div>
                                <div class="d-flex" style=' padding:5px;'>
                                        <p class='col-10'><strong>Telephone:</strong> <?=htmlspecialchars($table['tel']) ?></p>
                                </div>
                                <div class="d-flex" style=' padding:5px;'>
                                        <p class='col-10'><strong>Mot de passe:</strong> **********</p>
                                </div>
                                <div class="">
                                    <a class='nav-link bg-primary p-2 text-white col-6 text-center' href='editing_profil.php'>
                                        faire une modification
                                    </a>
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