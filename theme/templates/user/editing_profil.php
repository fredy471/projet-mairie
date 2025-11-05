<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../bdd.php';

$error=[];
if(!isset($_SESSION['id']) || $_SESSION['role']!='citoyen'){
    header('location:../logout.php');
    exit;
}else{

    $id=$_SESSION['id'];
    //recuperation des donnees de l'utilisateur

    $sql="SELECT * FROM users WHERE id_user=:id";
    $stmt=$conn->prepare($sql);
    $stmt->bindParam(':id',$_SESSION['id'],PDO::PARAM_INT);
    $stmt->execute();
    $table=$stmt->fetch(PDO::FETCH_ASSOC);


   if($_SERVER['REQUEST_METHOD']=='POST'){

        //traitement du formulaire de modification de profil
        $nom=htmlspecialchars($_POST['nom']);
        $prenom=htmlspecialchars($_POST['prenom']);
        $email=trim($_POST['email']);
        $tel= trim($_POST['tel']);
        $psd=password_hash($_POST['psd'],PASSWORD_DEFAULT);
        $actu_psd=password_hash($_POST['actu_psd'],PASSWORD_DEFAULT);

      if(!empty($nom) && !empty($prenom) && !empty($email) && !empty($tel)){

        if(empty($psd)){
            $sql_req='UPDATE users SET nom= :nom, prenom= :prenom, email= :email, tel= :tel WHERE id_user= :id_user';
            $stt=$conn->prepare($sql_req);
            $stt->bindParam(':nom',$nom,PDO::PARAM_STR);
            $stt->bindParam(':prenom',$prenom,PDO::PARAM_STR);
            $stt->bindParam(':email',$email,PDO::PARAM_STR);
            $stt->bindParam(':tel',$tel,PDO::PARAM_STR);
            $stt->bindParam(':id_user',$id,PDO::PARAM_INT);
            
            if($stt->execute()){
                header('location:modify_profil.php');
                exit;
            }
        }else{

            if(isset($psd) && empty($actu_psd)){

                if(password_verify($actu_psd,$table['psd'])){

                    $sql_req='UPDATE users SET nom= :nom, prenom= :prenom, email= :email, tel= :tel , psd= :psd WHERE id_user= :id_user';
                    $stt=$conn->prepare($sql_req);
                    $stt->bindParam(':nom',$nom,PDO::PARAM_STR);
                    $stt->bindParam(':prenom',$prenom,PDO::PARAM_STR);
                    $stt->bindParam(':email',$amail,PDO::PARAM_STR);
                    $stt->bindParam(':tel',$tel,PDO::PARAM_STR);
                    $stt->bindParam(':id_user',$id,PDO::PARAM_INT);
                    $stt->bindParam(':psd',$psd,PDO::PARAM_STR);

                    if($stt->execute()){
                        header('location:modify_profil.php');
                        exit;
                    }
                }
                
            }
          
        }

      }else{
        $error[]='veuillez remplir tout les champs';
      }
    }else{
        
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
        <?php require 'header.php'?>
        
        <main class="my-5">
            <div class="container" style='margin-top:100px; margin-bottom:60px;'>
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0">Modifier le profil</h4>
                            </div>
                            <div class="card-body">
                                <form action="" method="POST">
                                    <div class="form-group">
                                        <label for="nom" class="form-label">Nom</label>
                                        <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($table['nom']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="prenom" class="form-label">Prénom</label>
                                        <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($table['prenom']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($table['email']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Numéro de téléphone</label>
                                        <input type="number" class="form-control" id="phone" name="tel" value="<?= htmlspecialchars($table['tel']) ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="psd" class="form-label">Actuel Mot de passe</label>
                                        <input type="password" class="form-control" placeholder='laissez vide pour ne pas changer' name='actu_psd'>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Nouveau mot de passe</label>
                                        <input type="password" class="form-control" id="password" name="psd" placeholder="Laissez vide pour ne pas changer">
                                    </div>
                                    <button type="submit" class="btn btn-primary" style="background-color:#2C3E91; border:none;">Enregistrer les modifications</button>
                                </form>
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