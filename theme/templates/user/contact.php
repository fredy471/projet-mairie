<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../bdd.php';
$error = [];
$success = ''; // Variable pour le succès

if(!isset($_SESSION['id']) || $_SESSION['role']!='citoyen'){
    header('location:../logout.php');
    exit;
}else{

    $sql="SELECT * FROM users WHERE id_user=:id";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':id',$_SESSION['id'],PDO::PARAM_INT);
    $stmt->execute();
    $table=$stmt->fetch(PDO::FETCH_ASSOC);

    if($_SERVER['REQUEST_METHOD']=='POST'){
        if(isset($_POST['send'])){
            $nom=htmlspecialchars($_POST['nom']);
            $prenom=htmlspecialchars($_POST['prenom']);
            $numero=(int)$_POST['numero'];
            $email=htmlspecialchars($_POST['email']);
            $message=htmlspecialchars($_POST['message']);

            if(filter_var($email,FILTER_VALIDATE_EMAIL)){
                $check = $conn->prepare("SELECT id_user FROM users WHERE email = :email");
                $check->execute(['email' => $email]);
    
                if(empty($error)){
                    $insert = $conn->prepare("
                        INSERT INTO contact (nom, prenom, numero, email, message)
                        VALUES (:nom, :prenom, :numero, :email, :message)
                    ");
                    $row = $insert->execute([
                        'nom' => $nom,
                        'prenom' => $prenom,
                        'numero' => $numero,
                        'email' => $email,
                        'message' => $message
                    ]);

                    if($row) {
                        $success = 'success'; // Message envoyé avec succès
                    } else {
                        $error[] = "Erreur lors de l'envoi du message.";
                    }
                }
            }else{
                $error[] = 'Email invalide';
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
    <title>Contact | AM</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">
    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/css/connect.min.css" rel="stylesheet">
    <link href="../../assets/css/admin2.css" rel="stylesheet">
    <link href="../../assets/css/custom.css" rel="stylesheet">
</head>
<body>
        <?php if ($success === 'success'): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Message envoyé !',
                    text: 'Votre message a été envoyé avec succès. Nous vous répondrons bientôt.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6'
                });
            </script>
        <?php endif; ?>
      <?php require 'header.php' ?>

    
    <main>
        <div class="container" style='margin-top:150px;'>
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="my-5">
                        <form action="" class="my-5" method='post'>
                            <h3 class='fw-bold my-3'>
                                <i class="fa-solid fa-envelope"></i> Contactez-nous
                            </h3>
                            <small>Bienvenue sur notre formulaire de contact. Veuillez remplir correctement le formulaire pour un bon traitement</small>
                            <hr>

                            <!-- Affichage des erreurs -->
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

                            <div style='padding:10px;'>
                                <div class="mb-3">
                                    <label for="nom" class="form-label">
                                        <i class="fa-solid fa-user"></i> Votre nom
                                    </label>
                                    <input type="text" class="form-control" id="nom" name='nom' 
                                           value="<?= htmlspecialchars($table['nom']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="prenom" class="form-label">
                                        <i class="fa-solid fa-user"></i> Votre prénom
                                    </label>
                                    <input type="text" class="form-control" id="prenom" name='prenom' 
                                           value="<?= htmlspecialchars($table['prenom']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="numero" class="form-label">
                                        <i class="fa-solid fa-phone"></i> Votre numéro
                                    </label>
                                    <input type="number" class="form-control" id="numero" name='numero' required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fa-solid fa-envelope"></i> Votre email
                                    </label>
                                    <input type="email" class="form-control" id="email" name='email' required>
                                </div>

                                <div class="mb-4">
                                    <label for="message" class="form-label">
                                        <i class="fa-solid fa-message"></i> Votre demande
                                    </label>
                                    <textarea class="form-control" id="message" name='message' rows="5" required></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block" name='send'>
                                    <i class="fa-solid fa-paper-plane"></i> Envoyer le message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-white pt-5 pb-4" style='background-color:#2C3E91;'>
        <div class="container text-md-left">
            <div class="row">
                <div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold">Portail Municipal</h5>
                    <p>Simplifiez vos démarches administratives depuis chez vous. Suivi rapide et sécurisé, 24h/24.</p>
                </div>

                <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold">Liens utiles</h5>
                    <p><a href="#about" class="text-white" style="text-decoration:none;">Nos services</a></p>
                    <p><a href="#infos" class="text-white" style="text-decoration:none;">À propos</a></p>
                    <p><a href="contact.php" class="text-white" style="text-decoration:none;">Contact</a></p>
                    <p><a href="../logout.php" class="text-white" style="text-decoration:none;">Déconnexion</a></p>
                </div>

                <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mt-3">
                    <h5 class="text-uppercase mb-4 font-weight-bold">Contact</h5>
                    <p><i class="fa fa-home mr-3"></i> 123 Rue de la Mairie, Ville</p>
                    <p><i class="fa fa-envelope mr-3"></i> contact@mairie.gov</p>
                    <p><i class="fa fa-phone mr-3"></i> +229 22 00 00 00</p>
                </div>
            </div>

            <hr class="mb-4">

            <div class="row align-items-center">
                <div class="col-md-7 col-lg-8">
                    <p>© 2025 Portail Municipal. Tous droits réservés.</p>
                </div>
                <div class="col-md-5 col-lg-4">
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

    <!-- Scripts -->
    <script src="../../assets/plugins/jquery/jquery-3.4.1.min.js"></script>
    <script src="../../assets/plugins/bootstrap/popper.min.js"></script>
    <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../assets/js/connect.min.js"></script>
</body>
</html>
<?php
}
?>