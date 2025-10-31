<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'bdd.php';

$error = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['send'])) {
        // Validation des champs
        $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
        $prenom = htmlspecialchars(trim($_POST['prenom'] ?? ''));
        $adresse = htmlspecialchars(trim($_POST['adresse'] ?? ''));
        $tel = htmlspecialchars(trim($_POST['tel'] ?? ''));
        $email = htmlspecialchars(trim($_POST['email'] ?? ''));
        $psd = htmlspecialchars(trim($_POST['psd'] ?? ''));

        // Vérification des champs obligatoires
        if (empty($nom) || empty($prenom) || empty($adresse) || empty($tel) || empty($email) || empty($psd)) {
            $error[] = 'Tous les champs sont obligatoires.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error[] = 'Adresse email invalide.';
        } else {
            // Vérifier si l'email existe déjà
            $cheking_req = 'SELECT email FROM users WHERE email = :email';
            $stmt = $conn->prepare($cheking_req);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $error[] = 'Cet email est déjà utilisé.';
            } else {
                // Hachage du mot de passe
                $hashed_password = password_hash($psd, PASSWORD_DEFAULT);

                // Insertion dans la base de données
                $insert_req = 'INSERT INTO users(nom, prenom, adresse, tel, email, psd) 
                               VALUES(:nom, :prenom, :adresse, :tel, :email, :psd)';
                $stmt = $conn->prepare($insert_req);
                $success = $stmt->execute([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'adresse' => $adresse,
                    'tel' => $tel,
                    'email' => $email,
                    'psd' => $hashed_password
                ]);

                if ($success) {
                    // Récupérer l'ID de l'utilisateur inséré
                    $id = $conn->lastInsertId();

                    // Démarrer la session utilisateur
                    $_SESSION['role'] = 'citoyen';
                    $_SESSION['id'] = $id;

                    // Rediriger vers la page d'accueil
                    header('Location:user/index.php');
                    exit;
                } else {
                    $error[] = 'Erreur lors de l\'inscription. Veuillez réessayer.';
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
        <meta name="description" content="plateforme e-service de l'administration municipale">
        <meta name="keywords" content="admin,dashboard">
        <meta name="author" content="stacks">
        <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        
        <!-- Title -->
        <title>Inscription | AM</title>

        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp" rel="stylesheet">
        <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../assets/plugins/font-awesome/css/all.min.css" rel="stylesheet">

      
        <!-- Theme Styles -->
        <link href="../assets/css/connect.min.css" rel="stylesheet">
        <link href="../assets/css/dark_theme.css" rel="stylesheet">
        <link href="../assets/css/custom.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body class="auth-page sign-in">    
        </div>
        <div class="connect-container align-content-stretch d-flex flex-wrap">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="auth-form">
                            <div class="row">
                                <div class="col">


                                    <div class="text-center my-5">
                                        <h1 class="fw-bold" style="font-size:5rem; color:#2C3E91;" data-aos='fade-up' data-aos-delay='300' data-aos-duration='1000'>
                                            AM
                                            <small class="d-block fs-5" style="color:#6c757d;">Administration municipale</small>
                                            <span class="">e-services</span>
                                        </h1>
                                    </div>

                                    <div class="logo-box"><a href="#" class="logo-text">Inscription</a></div>
                         
                                       <?php if (!empty($error)): ?>
                                        <?php foreach ($error as $er): ?>
                                            <p class="error-message text-danger"><?= htmlspecialchars($er) ?></p>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                    
                                    <form action="" method='post'>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="nom" placeholder="Nom" name='nom' value="<?= htmlspecialchars($nom ?? '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="prenom" placeholder="Prenom" name='prenom' value="<?= htmlspecialchars($prenom ?? '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="adresse" placeholder="Adresse" name='adresse' value="<?= htmlspecialchars($adresse ?? '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="tel" class="form-control" id="telephone" placeholder="Téléphone" name='tel' value="<?= htmlspecialchars($tel ?? '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="email" class="form-control" id="email" placeholder="Email" name='email' value="<?= htmlspecialchars($email ?? '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Mot de passe" name='psd' required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block btn-submit" name='send'>S'inscrire</button>
                                        <div class="auth-options">
                                            <a href="login.php" class="forgot-link">Déjà un compte ?</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 d-none d-lg-block d-xl-block">
                        <div class="auth-image"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Javascripts -->
        <script src="../assets/plugins/jquery/jquery-3.4.1.min.js"></script>
        <script src="../assets/plugins/bootstrap/popper.min.js"></script>
        <script src="../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="../assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="../assets/js/connect.min.js"></script>
    </body>
</html>