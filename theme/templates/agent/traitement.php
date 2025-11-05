<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require '../bdd.php';


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

    //demandes traites par l'agent
    $req_one = 'SELECT * FROM demandes WHERE id_agent= :id_agent';
    $traites = $conn->prepare($req_one);
    $traites->bindParam(':id_agent', $id_user, PDO::PARAM_INT);
    $traites->execute();

    if (isset($_GET['id_demande']) && !empty($_GET['id_demande'])) {
        $id_demande = htmlspecialchars(trim($_GET['id_demande']));

        //recuperation des donnes de la demande avec l'interesse
        $sql_req2 = 'SELECT * FROM demandes d JOIN users u ON d.id_citoyen=u.id_user WHERE id_demande= :id_demande ';
        $stmt2 = $conn->prepare($sql_req2);
        $stmt2->bindParam(':id_demande', $id_demande, PDO::PARAM_INT);
        $stmt2->execute();
        $demande = $stmt2->fetch(PDO::FETCH_ASSOC);
    } else {
        header('location:demande_attente.php');
        exit;
    }
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

                .logo-box {
                    display: none;
                }

                header {
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

                .page-header .navbar.container {
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
                    <a href="all_demande.php">
                        <i class="material-icons-outlined">assignment</i>
                        <span>Toutes les demandes</span>
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
                    <a href="demande_traitee.php">
                        <i class="material-icons-outlined">block</i>
                        <span>Refuses</span>
                    </a>
                </li>


                <li class="sidebar-title">Paramètres</li>

                <li>
                    <a href="modify_profile.php">
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
                                <!-- Sidebar Toggle Button (Mobile) -->
                                <button class="sidebar-toggle" id="sidebarToggle">
                                    <i class="fa-solid fa-bars"></i>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </header>

            <main class="">

                <div class="container" style="margin-top: 50px;">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <?php if ($demande) { ?>
                                <div class="card shadow-sm p-4">
                                    <h5 class="mb-3 text-primary">Fiche de traitement de demande</h5>
                                    <hr>

                                    <p><strong>Citoyen :</strong> <?= htmlspecialchars($demande['nom']) . ' ' . htmlspecialchars($demande['prenom']) ?></p>
                                    <p><strong>Code de la demande :</strong> <?= htmlspecialchars($demande['code_demande']) ?></p>
                                    <p><strong>Type :</strong> <?= htmlspecialchars($demande['types']) ?></p>
                                    <p><strong>Date :</strong> <?= date('d/m/Y', strtotime($demande['date'])) ?></p>
                                    <p><strong>Statut actuel :</strong>
                                        <?php if ($demande['statut'] === 'en attente') { ?>
                                            <span class="badge bg-warning text-dark">En attente</span>
                                        <?php } elseif ($demande['statut'] === 'traitee') { ?>
                                            <span class="badge bg-success">Traité</span>
                                        <?php } else { ?>
                                            <span class="badge bg-danger">Refusée</span>
                                        <?php } ?>
                                    </p>

                                    <hr style='border:1px black dashed;'>

                                    <form method="post" action="traitement_action.php">
                                        <input type="hidden" name="id_demande" value="<?= htmlspecialchars($demande['id_demande']) ?>">

                                        <div class="mb-3">
                                            <label for="commentaire" class="form-label">Commentaire :</label>
                                            <textarea name="commentaire" id="commentaire" class="form-control" rows="4" placeholder="Ajoute un commentaire sur cette demande..."></textarea>
                                        </div>

                                        <div class="mb-3 text-center">
                                            <label for="picture" class="form-label d-block mb-2 fw-bold">Pièce jointe :</label>
                                            <div class="border rounded p-2 d-inline-block bg-light shadow-sm" style="max-width: 300px;">
                                                <img src="../../assets/images/uploads/<?= htmlspecialchars($demande['piece']) ?>"
                                                    alt="Pièce jointe"
                                                    class="img-fluid rounded"
                                                    style="max-height: 300px; object-fit: cover;">
                                            </div>
                                        </div>


                                        <div class="mb-5 justidy-content-center">
                                            <label class="form-label">Pièce jointe :</label><br>
                                            <a href="../../assets/images/uploads/<?= htmlspecialchars($demande['piece']) ?>"
                                                download
                                                class="btn btn-primary">
                                                <i class="fa-solid fa-download"></i> Télécharger la pièce
                                            </a>
                                        </div>


                                        <div class="d-flex justify-content-between">
                                            <button type="submit" name="action" value="valider" class="btn btn-success">
                                                <i class="fa-solid fa-check"></i> Valider
                                            </button>

                                            <button type="submit" name="action" value="refuser" class="btn btn-danger">
                                                <i class="fa-solid fa-xmark"></i> Refuser
                                            </button>

                                            <a href="demande_attente.php" class="btn btn-secondary">
                                                <i class="fa-solid fa-arrow-left"></i> Retour
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            <?php } else { ?>
                                <div class="alert alert-danger">Demande introuvable.</div>
                            <?php } ?>


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
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const form = document.querySelector("form");
                const commentaire = document.getElementById("commentaire");

                form.addEventListener("submit", function(e) {
                    const action = e.submitter ? e.submitter.value : null; // Bouton cliqué
                    const texte = commentaire.value.trim();

                    if (action === "refuser" && texte === "") {
                        e.preventDefault();
                        alert("⚠️ Veuillez renseigner un commentaire avant de refuser la demande !");
                        commentaire.focus();
                    }
                });
            });
        </script>

    </body>

    </html>
<?php } ?>