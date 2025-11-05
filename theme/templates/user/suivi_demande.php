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

    //recuperation des demandes en attente de l'utilisateur
    $sql_req_one = "SELECT * FROM demandes WHERE id_citoyen=:id_user AND statut='en attente' ORDER BY date DESC";
    $stmt_demande = $conn->prepare($sql_req_one);
    $stmt_demande->bindParam(':id_user', $_SESSION['id'], PDO::PARAM_INT);
    $stmt_demande->execute();
    $attentes = $stmt_demande->fetchAll(PDO::FETCH_ASSOC);

    //recuperation des demandes validee de l'utilisateur
    $sql_req_two = "SELECT * FROM demandes WHERE id_citoyen=:id_user AND statut='valide'  ORDER BY date DESC";
    $stt = $conn->prepare($sql_req_two);
    $stt->bindParam(':id_user', $_SESSION['id'], PDO::PARAM_INT);
    $stt->execute();
    $valides = $stt->fetchAll(PDO::FETCH_ASSOC);


    //recuperation des demandes rejete de l'utilisateur
    $sql_req_tree = "SELECT * FROM demandes WHERE id_citoyen=:id_user AND statut='rejete' ORDER BY date DESC";
    $stmt_act = $conn->prepare($sql_req_tree);
    $stmt_act->bindParam(':id_user', $_SESSION['id'], PDO::PARAM_INT);
    $stmt_act->execute();
    $rejetes = $stmt_act->fetchAll(PDO::FETCH_ASSOC);

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
        <?php require 'header.php' ?>

        <main class="my-5">
            <div class="container my-5">
                <div class="row my-5">
                    <div class="col-md-12 mt-5 justify-content-center">

                        <h3 class="text-center">Recapitulatif de vos demandes</h3>

                    </div>
                    <!--je dois faire un grid ici poour afficher les statitiques globales avant de faire les tableaux soecifiques pour chaque demandes -->
                </div>
                <div class="row stats-row">
                    <div class="col-lg-4 col-md-12">
                        <div class="card card-transparent stats-card">
                            <div class="card-body">
                                <div class="stats-info">
                                    <h5 class="card-title"><?= $stmt_demande->rowCount(); ?></h5>
                                    <p class="stats-text">total des demandes en attente</p>
                                </div>
                                <div class="stats-icon" style='background-color:darkgrey;'>
                                    <i class="material-icons">trending_up</i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="card card-transparent stats-card">
                            <div class="card-body">
                                <div class="stats-info">
                                    <h5 class="card-title"><?= $stt->rowCount() ?></h5>
                                    <p class="stats-text">Total des demandes validees</p>
                                </div>
                                <div class="stats-icon change-success">
                                    <i class="material-icons">trending_up</i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="card card-transparent stats-card">
                            <div class="card-body">
                                <div class="stats-info">
                                    <h5 class="card-title"><?= $stmt_act->rowCount() ?></h5>
                                    <p class="stats-text">Total des demandes refusees</p>
                                </div>
                                <div class="stats-icon change-danger">
                                    <i class="material-icons">trending_down</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container my-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="">
                            <h4>Demande en attente</h4>

                            <?php
                            if ($attentes) {
                            ?>
                                <table class="table table-striped table-responsive">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">code_demande</th>
                                            <th scope="col">date de soumission</th>
                                            <th scope="col">type de demande</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($attentes as $demande) { ?>
                                            <tr>
                                                <td><?= $demande['code_demande'] ?></td>
                                                <td><?= date('d/m/Y', strtotime($demande['date'])) ?></td>
                                                <td><?= $demande['types'] ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php
                            } else {
                                echo "<p class='text-center'>Aucune demande en cours pour le moment</p>";
                            }
                            ?>

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="">
                            <h4>Demande validée</h4><!---je dois utiliser uene boucle pour afficher les demandes en cours-->
                            <?php
                            if ($valides) { ?>

                                <table class="table  text-success bg-dark table-striped table-responsive">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">code_demande</th>
                                            <th scope="col">date de soumission</th>
                                            <th scope="col">type de demande</th>
                                            <th scope='col'>acion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($valides as $demande): ?>
                                            <?php
                                            // Vérifie si cette demande a déjà un rendez-vous
                                            $check = $conn->prepare("SELECT COUNT(*) FROM rendez_vous WHERE id_demande = :id_demande");
                                            $check->execute(['id_demande' => $demande['id_demande']]);
                                            $has_rdv = $check->fetchColumn() > 0;
                                            ?>
                                            <tr>
                                                <td><?= htmlspecialchars($demande['code_demande']) ?></td>
                                                <td><?= date('d/m/Y', strtotime($demande['date'])) ?></td>
                                                <td><?= htmlspecialchars($demande['types']) ?></td>
                                                <td>
                                                    <?php if ($has_rdv): ?>
                                                        <span class="badge bg-secondary">Déjà pris</span>
                                                    <?php else: ?>
                                                        <a href="rendez_vous.php?id_user=<?= urlencode($table['id_user']) ?>&id_demande=<?= urlencode($demande['id_demande']) ?>"
                                                            class="badge bg-info text-dark text-decoration-none">
                                                            Prendre un rendez-vous
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php
                            } else {
                                echo "<p class='text-center'>Aucune demande validée pour le moment</p>";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="">
                            <h4>Demande refusée</h4><!---je dois utiliser uene boucle pour afficher les demandes en cours-->
                            <?php
                            if ($rejetes) { ?>
                                <table class="table  text-danger bg-dark table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">code_demande</th>
                                            <th scope="col">date de soumission</th>
                                            <th scope="col">type de demande</th>
                                            <th scope='col'>Commentaire</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        foreach ($rejetes as $demande) { ?>
                                            <tr>
                                                <td><?= $demande['code_demande'] ?></td>
                                                <td><?= date('d/m/Y', strtotime($demande['date'])) ?></td>
                                                <td><?= $demande['types'] ?></td>
                                                <td class=""><?= $demande['commentaire']?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php
                            } else {
                                echo "<p class='text-center'>Aucune demande refusée pour le moment</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="col-md-9 justify-content-center mx-auto">
                        <div class="mail-info">
                            <p class="">NB: Un mail vous sera envoyé lorsque /lorsqu'une de vos/votre demande(s) change de statut</p>
                        </div>
                    </div>
                </div>    
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-12 justify-content-center mx-auto">
                            <table class='table table-striped'>
                                <thead>
                                    <tr>
                                        <th scope='col'>Légende des statuts</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class='text-dark'>En attente:</span> Votre demande est en cours de traitement par la mairie.</td>
                                    </tr>
                                    <tr>
                                        <td><span class='text-success'>Validée:</span> Votre demande a été approuvée. Vous pouvez prendre rendez-vous.</td>
                                    </tr>
                                    <tr>
                                        <td><span class='text-danger'>Refusée:</span> Votre demande n'a pas été approuvée. Veuillez contacter la mairie pour plus d'informations.</td>
                                    </tr>
                                </tbody>

                            </table>
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