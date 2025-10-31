<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../bdd.php';

if(!isset($_SESSION['id']) || $_SESSION['role']!='citoyen'){
    header('location:../login.php');
    exit;
}else{


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
    <title>Accueil | AM</title>

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
        <?php require 'header.php'?>
        <main class="">
            <div class=''>
            <div id="carouselExampleControls" class="carousel slide overlay-carousel" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="d-block w-100" src="../../assets/images/caroussel.jpg" alt="First slide">
                            <div class="carousel-caption d-none d-md-block text-white">
                                <h5>Bienvenue sur le Portail Municipal</h5>
                                <p>Accédez facilement à vos démarches administratives en ligne, 24h/24 et 7j/7.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="../../assets/images/caroussel1.jpg" alt="Second slide">
                            <div class="carousel-caption d-none d-md-block text-white">
                                <h5>Simplifiez vos démarches</h5>
                                <p>Légalisation de documents, demandes d’attestation et bien plus, directement depuis chez vous.</p>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="../../assets/images/carrousel3.jpg" alt="Third slide">
                            <div class="carousel-caption d-none d-md-block text-white">
                                <h5>Suivi rapide et sécurisé</h5>
                                <p>Suivez l’avancement de vos demandes en temps réel, en toute sécurité.</p>
                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>

            <div class="container  about-section" id='about' style='margin-bottom:110px;'>
                <div class="row">
                    <div class="col-md-6 text-content mb-5">
                        <h2>Bienvenue, <?= htmlspecialchars($table['prenom']) ?>!</h2>
                        <p>Nous sommes ravis de vous accueillir sur notre plateforme e-service de l'administration municipale  dédiée aux citoyens.<br> Ici, vous pouvez effectuer diverses démarches administratives en ligne, de manière simple et sécurisée.</p>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-striped table-responsive">
                            <thead>
                                <tr>
                                <th scope="col">Nos services</th>
                                <th scope="col">Types</th>
                                <th scope='col'>Horaires</th>
                                <th scope='col'>Delai d'execution</th>
                                
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <th scope="row">1</th>
                                    <td>Certficat de residence</td>
                                    <td>24/7</td>
                                    <td> 24h</td>
                                
                                </tr>
                                <tr>
                                <th scope="row">2</th>
                                    <td>Certficat d'Etat Civil</td>
                                    <td>24/7</td>
                                     <td> 24h</td>
                                
                                </tr>
                                <tr>
                                <th scope="row">3</th>
                                        <td>Certificat de marriage</td>
                                        <td>24/7</td>
                                         <td> 24h</td>
                                </tr>
                                <tr>
                                <th scope="row">4</th>
                                        <td>Legalisation de document</td>
                                        <td>24/7</td>
                                        <td> 24h</td>
                                </tr>
                                <tr>
                                <th scope="row">+</th>
                                        <td>+++</td>
                                        <td>24/7</td>
                                        <td> 24h</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!---Cards-->
            <div class="container my-5">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fa-solid fa-file fa-3x mb-3" style="color:#2C3E91;"></i>
                                <h5 class="card-title">Demandes de documents</h5>
                                <p class="card-text">Effectuez vos demandes de documents administratifs en ligne, tels que les actes de naissance, de mariage, et plus encore.</p>
                                <a href="demande.php" class="btn btn-primary" style="background-color:#2C3E91; border:none;">Accéder</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body"> 
                                <i class="fa-solid fa-id-card fa-3x mb-3" style="color:#2C3E91;"></i>
                                <h5 class="card-title">Gestion de compte</h5>
                                <p class="card-text">Mettez à jour vos informations personnelles, changez votre mot de passe, et gérez vos préférences de compte facilement.</p>
                                <a href="modify_profil.php" class="btn btn-primary" style="background-color:#2C3E91; border:none;">Accéder</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fa-solid fa-envelope fa-3x mb-3" style="color:#2C3E91;"></i>
                                <h5 class="card-title   ">Contactez-nous</h5>
                                <p class="card-text">Besoin d'aide ou d'informations supplémentaires ? Contactez notre équipe municipale via le formulaire de contact en ligne.</p>
                                <a href="contact.php" class="btn btn-primary" style="background-color:#2C3E91; border:none;">Accéder</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <i class="fa-solid fa-chart-line fa-3x mb-3" style="color:#2C3E91;"></i>
                                <h5 class="card-title">Suivi des demandes</h5>
                                <p class="card-text">Suivez l'état d'avancement de vos demandes administratives en temps réel, de la soumission à la réception.</p>
                                <a href="suivi_demande.php" class="btn btn-primary" style="background-color:#2C3E91; border:none;">Accéder</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container my-5" id='infos'>
                    <div class="row">
                        <div class="col text-center">
                            <h2 class="my-4 fw-bold">Nos Engagements</h2>
                            <p class="text-muted mb-5">
                                La mairie s’engage à offrir des services publics efficaces, accessibles et transparents pour tous les citoyens.
                            </p>
                        </div>
                    </div>

                    <div class="row text-center">
                        <div class="col-md-4 mb-4">
                            <i class="fa-solid fa-clock fa-3x mb-3 text-primary"></i>
                            <h5>Rapidité</h5>
                            <p>Des démarches simplifiées et un traitement rapide de vos demandes administratives, sans déplacement inutile.</p>
                        </div>

                        <div class="col-md-4 mb-4">
                            <i class="fa-solid fa-lock fa-3x mb-3 text-primary"></i>
                            <h5>Fiabilité</h5>
                            <p>Vos données sont protégées et vos démarches se font en toute sécurité via notre plateforme certifiée.</p>
                        </div>

                        <div class="col-md-4 mb-4">
                            <i class="fa-solid fa-users fa-3x mb-3 text-primary"></i>
                            <h5>Proximité</h5>
                            <p>Un service public digital, mais toujours proche de vous, avec un accompagnement humain à chaque étape.</p>
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