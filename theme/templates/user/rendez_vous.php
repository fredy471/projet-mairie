<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../bdd.php';

$error = [];
if (!isset($_SESSION['id']) || $_SESSION['role'] != 'citoyen') {
    header('location:../login.php');
    exit;
} else {


    //recuperation des donnees de l'utilisateur
    $sql = "SELECT * FROM users WHERE id_user=:id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    $table = $stmt->fetch(PDO::FETCH_ASSOC);




    if (isset($_GET['id_demande']) && isset($_GET['id_user'])) {
        $id_demande = htmlspecialchars(trim($_GET['id_demande'] ?? ''));
        $id_citoyen = htmlspecialchars(trim($_GET['id_user'] ?? ''));

        $date_actuelle = date('Y-m-d');
        $error = [];
        $success = '';



        // Récupérer la demande validée
        $sql = 'SELECT * FROM demandes WHERE id_demande= :id_demande AND id_citoyen = :id_citoyen';
        $tth = $conn->prepare($sql);
        $tth->execute(['id_demande' => $id_demande, 'id_citoyen' => $id_citoyen]);
        $demande = $tth->fetch(PDO::FETCH_ASSOC);



        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
            $date = htmlspecialchars(trim($_POST['date'] ?? ''));
            $heure = htmlspecialchars(trim($_POST['heure'] ?? ''));

            // Validation des champs
            if (empty($date)) {
                $error[] = "La date du rendez-vous est requise.";
            }
            if (empty($heure)) {
                $error[] = "L'heure du rendez-vous est requise.";
            }


            $request = 'SELECT * FROM rendez_vous WHERE date= :date AND heure= :heure';
            $ttmt = $conn->prepare($request);
            $ttmt->bindParam(':date', $date, PDO::PARAM_STR);
            $ttmt->bindParam(':heure', $heure, PDO::PARAM_STR);
            $ttmt->execute();
            $checking = $ttmt->fetch(PDO::FETCH_ASSOC);

            if (!$checking) {
                if (empty($error)) {
                    $sql = 'INSERT INTO rendez_vous (id_citoyen, date, heure, id_demande)
                    VALUES (:id_citoyen, :date, :heure, :id_demande)';

                    $stmt = $conn->prepare($sql);
                    $params = [
                        'id_citoyen' => $id_citoyen,
                        'date' => $date,
                        'heure' => $heure,
                        'id_demande' => $id_demande,

                    ];
                    if ($stmt->execute($params)) {
                        $success = "✅ Rendez-vous confirmé pour le $date à $heure.";
                    } else {
                        $error[] = "Erreur lors de la prise de rendez-vous. Veuillez réessayer.";
                    }
                }
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
        <?php require 'header.php' ?>

        <main class="my-5">


            <div class="container my-5 py-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow-lg border-0">
                            <div class="card-header bg-white py-4">
                                <h3 class="mb-2 text-primary">
                                    <i class="fa-solid fa-calendar-check me-2"></i> Rendez-vous
                                </h3>
                                <small class="text-muted">
                                    Votre demande a été validée. Veuillez choisir un créneau pour retirer votre document.
                                </small>
                            </div>

                            <div class="card-body bg-white p-4">
                                <!-- Info demande -->
                                <div class="alert alert-info mb-4">
                                    <strong>Document à retirer :</strong> <?= htmlspecialchars($demande['types']) ?><br>
                                    <strong>Code :</strong> <?= htmlspecialchars($demande['code_demande']) ?>
                                </div>

                                <!-- Erreurs -->
                                <?php if (!empty($error)) : ?>
                                    <div class="alert alert-danger">
                                        <?php foreach ($error as $err) : ?>
                                            <p class="mb-0"><?= htmlspecialchars($err) ?></p>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($success)) : ?>
                                    <div class="alert alert-success">
                                        <?= htmlspecialchars($success) ?>
                                    </div>
                                <?php endif; ?>

                                <form method="POST" action=''>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="date" class="form-label">
                                                <i class="fa-solid fa-calendar me-1"></i> Date du rendez-vous <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="date" name="date" value="<?= $date_actuelle ?>" min="<?= $date_actuelle ?>"
                                                required>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="heure" class="form-label">
                                                <i class="fa-solid fa-clock me-1"></i> Heure <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" id="heure" name="heure" required>
                                                <option value="">Choisir un créneau</option>
                                                <option value="08:00">08:00 - 09:00</option>
                                                <option value="09:00">09:00 - 10:00</option>
                                                <option value="10:00">10:00 - 11:00</option>
                                                <option value="11:00">11:00 - 12:00</option>
                                                <option value="14:00">14:00 - 15:00</option>
                                                <option value="15:00">15:00 - 16:00</option>
                                                <option value="16:00">16:00 - 17:00</option>
                                                <option value="17:00">17:00 - 18:00</option>
                                            </select>
                                        </div>

                                        <div class="col-12">
                                            <div class="alert alert-warning mt-3">
                                                <i class="fa-solid fa-exclamation-triangle me-2"></i>
                                                <strong>Important :</strong>
                                                <ul class="mb-0 mt-2 ps-3">
                                                    <li>Munissez-vous de votre pièce d'identité</li>
                                                    <li>Arrivez 10 minutes avant l'heure prévue</li>
                                                    <li>Le document sera disponible uniquement à la date choisie</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button type="submit" name="submit" class="btn btn-primary btn-lg w-100">
                                                <i class="fa-solid fa-check-circle me-2"></i> Confirmer le rendez-vous
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <div class="mt-4">
                                    <a href="suivi_demande.php" class="btn btn-outline-secondary w-100">
                                        <i class="fa-solid fa-arrow-left me-2"></i> Retour
                                    </a>
                                </div>
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