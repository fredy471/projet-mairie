<!DOCTYPE html>
<html lang="fr">
<head>

    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">
    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/css/connect.min.css" rel="stylesheet">
    <link href="../../assets/css/admin2.css" rel="stylesheet">
    <link href="../../assets/css/custom.css" rel="stylesheet">

</head>
<body>
        <header class="">
            <div class="align-content-stretch d-flex flex-wrap">
                <div class="page-container">
                    <div class="page-header">
                        <nav class="navbar navbar-expand container">
                            <div class="logo-box"><a href="#" class="logo-text" style="font-size:3rem;">AM</a></div>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                            </button>
                            <ul class="navbar-nav">
                                <li class="nav-item small-screens-sidebar-link">
                                    <a href="#" class="nav-link"><i class="material-icons-outlined">menu</i></a>
                                </li>
                                <li class="nav-item nav-profile dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"          data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <img src="../../assets/images/post-1.jpg" alt="profile image">
                                        <span><?= $table['nom'] ?> <?= $table['prenom']?></span><i class="material-icons dropdown-icon">keyboard_arrow_down</i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" style='color:#3646C9;' href="index.php#infos">A propos</a>
                                
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="../logout.php"> <i class="fa-solid fa-right-from-bracket"></i>
                                        Deconnexion</a>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link"><i class="material-icons-outlined">mail</i></a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link"><i class="material-icons-outlined">notifications</i></a>
                                </li>
                                <!--<li class="nav-item">
                                    <a href="#" class="nav-link" id="dark-theme-toggle"><i class="material-icons-outlined">brightness_2</i><i class="material-icons">brightness_2</i></a>
                                </li>-->
                            </ul>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a href="index.php#infos" class="nav-link">A propos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="index.php#about" class="nav-link">e-services</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="contact.php" class="nav-link">contact</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="navbar-search">
                                    <a class="elm" href='../logout.php'>Déconnexion</a>
                            </div>
                        </nav>
                    </div>
                    <div class="horizontal-bar">
                        <div class="logo-box"><a href="#" class="logo-text" style="font-size:3rem;">AM</a></div>
                        <a href="#" class="hide-horizontal-bar"><i class="material-icons">close</i></a>
                        <div class="container">
                            <div class="row">
                                <div class="col">
                                    <div class="horizontal-bar-menu">
                                        <ul class='d-block d-xl-none'>
                                            <li><a href="index.html" class="active">Dashboard</a></li>
                                            <li>
                                                <a href="#">Apps<i class="material-icons">keyboard_arrow_down</i></a>
                                                <ul>
                                                    <li>
                                                        <a href="mailbox.html">Mailbox</a>
                                                    </li>
                                                    <li>
                                                        <a href="profile.html">Profile</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>    

    <!-- Scripts -->
    <script src="../../assets/plugins/jquery/jquery-3.4.1.min.js"></script>
    <script src="../../assets/plugins/bootstrap/popper.min.js"></script>
    <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../assets/js/connect.min.js"></script>
</body>
</html>
