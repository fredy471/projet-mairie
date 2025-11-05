<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../bdd.php';
session_start();


if (!isset($_SESSION['id']) && !isset($_SESSION['role'])) {
    header('location:../logout.php');
    exit;
} else {
    $id_user = $_SESSION['id'];
    $role = $_SESSION['role'];

    //recuperation des donnes de l'agent
    $sql_req = 'SELECT * FROM users WHERE id_user= :id_user';
    $stmt = $conn->prepare($sql_req);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();
    $table = $stmt->fetch(PDO::FETCH_ASSOC);

   //liste des rendez-vous

   $sql='SELECT * FROM rendez_vous r JOIN users u ON r.id_citoyen=u.id_user JOIN demandes d ON r.id_demande=d.id_demande';
   $prep=$conn->prepare($sql);
   $prep->execute();
   $tableau=$prep->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accueil | AM</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined" rel="stylesheet">
    <link href="../../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../../assets/css/connect.min.css" rel="stylesheet">
    <link href="../../assets/css/admin2.css" rel="stylesheet">
    <link href="../../assets/css/custom.css" rel="stylesheet">

    <style>
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100vh;
            /*background:linear-gradient(90deg, #1E3A8A 0%, #2563EB 100%);*/
            background-color: #1E3A8A;
            transition: left 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar.active {
            left: 0;
        }

        .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .sidebar-header h3 {
            color: white;
            margin: 0;
            font-size: 1.5rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding-left: 30px;
        }

        .sidebar-menu li a i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .sidebar-title {
            color: rgba(255, 255, 255, 0.5);
            padding: 20px 20px 10px;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
        }

        /* Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Toggle Button */
        .sidebar-toggle {
            background: none;
            border: none;
            color: #1E3A8A;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .sidebar-toggle:hover {
            transform: scale(1.1);
        }

        .sidebar-toggle i {
            font-size: 1.5rem;
        }

        /* Main Content Adjustment */
        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s ease;
        }

        /* Desktop */
        @media (min-width: 992px) {
            .sidebar {
                left: 0;
            }

            .sidebar-overlay {
                display: none !important;
            }

            .sidebar-toggle {
                display: none;
            }

            .main-content {
                margin-left: 280px;
            }

            .logo-box{
                display:none;
            }
            header{
                display: none;
            }
        }

        /* Mobile */
        @media (max-width: 991px) {
            .sidebar {
                left: -280px;
            }

            .main-content {
                margin-left: 0;
            }

            .page-header .navbar.container{
                justify-content: space-around;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>AM</h3>
            <small style="color: rgba(255,255,255,0.7);">Administration Municipale</small>
        </div>

        <ul class="sidebar-menu">
            <li class="sidebar-title">Menu Principal</li>
            
            <li>
                <a href="dashboard.php">
                    <i class="material-icons-outlined">dashboard</i>
                    <span>Tableau de bord</span>
                </a>
            </li>
            
            <li>
                <a href="demande_attente.php">
                    <i class="material-icons-outlined">pending_actions</i>
                    <span>En attente</span>
                </a>
            </li>
            
            <li>
                <a href="demande_traitee.php">
                    <i class="material-icons">done_all</i>
                    <span>Traitées</span>
                </a>
            </li>
            
            <li>
                <a href="modify_profil.php">
                    <i class="material-icons-outlined">block</i>
                    <span>Refusees</span>
                </a>
            </li>

            <li>
                <a href="all_demande.php">
                    <i class="material-icons-outlined">assignment</i>
                    <span>Toutes les demandes</span>
                </a>
            </li>
            <li class="">
                <a href="rendez_vous.php" class="acitve">
                    <span>rendez_vous</span>
                </a>
            </li>
            
            <li class="sidebar-title">Paramètres</li>
            
            <li>
                <a href="modify_profil.php">
                    <i class="material-icons-outlined">account_circle</i>
                    <span>Mon Profil</span>
                </a>
            </li>

            <li class="sidebar-title">Système</li>
            
            <li>
                <a href="../logout.php">
                    <i class="material-icons-outlined">exit_to_app</i>
                    <span class='text-danger'>Déconnexion</span>
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <header class="">
            <div class="connect-container align-content-stretch d-flex flex-wrap">              
                <div class="page-container">
                    <div class="page-header">
                        <nav class="navbar navbar-expand container">
                            <div class="logo-box"><a href="#" class="logo-text" style="font-size:3rem;">AM</a></div>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <ul class="navbar-nav">
                                <li class="nav-item nav-profile dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                                        <img src="../../assets/images/post-1.jpg" alt="profile image">
                                        <span><?= htmlspecialchars($table['nom'] . ' ' . $table['prenom']) ?></span>
                                        <i class="material-icons dropdown-icon">keyboard_arrow_down</i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="modify_profile.php">
                                            <i class="fa-solid fa-user"></i> Profil
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="../logout.php">
                                            <i class="fa-solid fa-right-from-bracket"></i> Déconnexion
                                        </a>
                                    </div>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link"><i class="material-icons-outlined">mail</i></a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link"><i class="material-icons-outlined">notifications</i></a>
                                </li>
                            </ul>
                            <li class="nav-item d-lg-none"> <!-- visible seulement sur mobile -->
                                <button class="sidebar-toggle" id="sidebarToggle">
                                    <i class="fa-solid fa-bars"></i>
                                </button>
                            </li>
                        </nav>
                    </div>
                </div>
            </div>
        </header>

        <main class="">

            <div class="container" style='margin-top:100px; margin-bottom:60px;'>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">📋 Liste des rendez-vous</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Date</th>
                                        <th>Heure</th>
                                        <th>Demande</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($tableau as $tab) {?>
                                            <tr>
                                                <td><?= htmlspecialchars($tab['nom']) ?></td>
                                                <td><?= htmlspecialchars($tab['date']) ?></td>
                                                <td><?= htmlspecialchars($tab['heure']) ?></td>
                                                <td><?= htmlspecialchars($tab['types']) ?></td>
                                                <td>
                                                    <a href="treat_rend.php?id=1&id_demande=<?=$tab['id_demande']?>" class="bg-success text-white btn-sm">valider</a>
                                                    <a href="treat_rend.php?id=0&id_demande=<?=$tab['id_demande']?>" class="bg-danger text-white btn-sm">Refuser</a>
                                                </td>
                                                
                                            </tr>
                                    <?php 
                                    }
                                    ?>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="../../assets/plugins/jquery/jquery-3.4.1.min.js"></script>
    <script src="../../assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../assets/js/connect.min.js"></script>

    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        // Ouvrir/Fermer sidebar
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        });

        // Fermer sidebar en cliquant sur l'overlay
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        });

        // Fermer sidebar en cliquant sur un lien (mobile)
        const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 992) {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
<?php } ?>