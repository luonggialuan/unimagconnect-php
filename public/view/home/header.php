<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>UniMagConnect</title>
    <meta name="description" content="WEB-COMP1640">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="#">

    <!-- STYLES -->
    <link rel="stylesheet" href="./css/bootstrap.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="./css/all.min.css" type="text/css" media="all">
    <link rel="stylesheet" href="./css/slick.css" type="text/css" media="all">
    <link rel="stylesheet" href="./css/simple-line-icons.css" type="text/css" media="all">
    <link rel="stylesheet" href="./css/style.css" type="text/css" media="all">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js
"></script>
    <link href="
https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css
" rel="stylesheet">

</head>

<body>
    <?php
    if (!isset($_GET['page'])) {
        ?>
        <div id="preloader">
            <div class="book">
                <div class="inner">
                    <div class="left"></div>
                    <div class="middle"></div>
                    <div class="right"></div>
                </div>
                <ul>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                    <li></li>
                </ul>
            </div>
        </div>
        <?php

    }
    ?>
    <!-- site wrapper -->
    <div class="site-wrapper">


        <!-- header -->
        <header class="header-default">
            <nav class="navbar navbar-expand-lg">
                <div class="container-xl">
                    <!-- site logo -->
                    <a class="navbar-brand" href="index.php">
                        <!-- <img src="images/logo.jpg" alt="logo"
                            style="height: 80px;" /> -->
                        UniMagConnect
                    </a>

                    <div class="collapse navbar-collapse">
                        <!-- menus -->
                        <ul class="navbar-nav mr-auto">
                            <?php
                            if (isset($_GET['page'])) {
                                $page = $_GET['page'];
                                if ($page == "profile") {
                                    $profileActive = 'active';
                                } elseif ($page == "magazineStudent") {
                                    $magazineActive = 'active';
                                } elseif ($page == "your-articles") {
                                    $studentArticles = 'active';
                                } elseif ($page == "statistics") {
                                    $statisticsActive = 'active';
                                }
                            } else {
                                $home = 'active';
                            }
                            ?>
                            <li class="nav-item <?= $home ?>">
                                <a class="nav-link " href="index.php">Home</a>
                            </li>

                            <?php
                            if (!isset($_SESSION['guest'])) {
                                ?>
                                <li class="nav-item <?= $magazineActive ?>">
                                    <a class="nav-link" href="?page=magazineStudent">Magazine</a>
                                </li>
                                <li class="nav-item <?= $studentArticles ?>">
                                    <a class="nav-link " href="?page=your-articles">View Submissions</a>
                                </li>
                                <li class="nav-item <?= $profileActive ?>">
                                    <a class="nav-link" href="?page=profile">Profile</a>
                                </li>
                                <?php
                            }
                            ?>

                            <?php
                            if (isset($_SESSION['guest'])) {
                                ?>
                                <li class="nav-item <?= $statisticsActive ?>">
                                    <a class="nav-link" href="?page=statistics">Statistics</a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>

                    <!-- header right section -->
                    <div class="header-right">
                        <div class="header-buttons d-flex align-items-center">
                            <button class="navbar-toggler burger-menu icon-button me-2">
                                <span class="burger-icon"></span>
                            </button>
                            <button class="icon-button" onclick="confirmLogout()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                                    <path fill-rule="evenodd"
                                        d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="canvas-menu d-flex align-items-end flex-column">
                <!-- close button -->
                <button type="button" class="btn-close" aria-label="Close"></button>

                <!-- logo -->
                <div class="logo">
                    <a class="navbar-brand" href="index.php">
                        UniMagConnect
                    </a>
                </div>

                <!-- menu -->
                <nav>
                    <ul class="vertical-menu">
                        <li class="<?= $home ?>">
                            <a href="index.php">Home</a>
                        </li>

                        <?php
                        if (!isset($_SESSION['guest'])) {
                            ?>
                            <li class="<?= $magazineActive ?>"><a href="?page=magazineStudent">Magazine</a></li>
                            <li class="<?= $studentArticles ?>"><a href="?page=your-articles">View Submissions</a></li>
                            <li class="<?= $profileActive ?>"><a href="?page=profile">Profile</a></li>
                            <?php
                        }
                        ?>
                        <?php
                        if (isset($_SESSION['guest'])) {
                            ?>
                            <li class="<?= $statisticsActive ?>"><a href="?page=statistics">Statistics</a></li>
                            <?php
                        }
                        ?>
                    </ul>
                </nav>

                <!-- social icons -->
                <ul class="social-icons list-unstyled list-inline mb-0 mt-auto w-100">
                    <li class="list-inline-item"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                    <li class="list-inline-item"><a href="#"><i class="fab fa-twitter"></i></a></li>
                    <li class="list-inline-item"><a href="#"><i class="fab fa-instagram"></i></a></li>
                    <li class="list-inline-item"><a href="#"><i class="fab fa-pinterest"></i></a></li>
                    <li class="list-inline-item"><a href="#"><i class="fab fa-medium"></i></a></li>
                    <li class="list-inline-item"><a href="#"><i class="fab fa-youtube"></i></a></li>
                </ul>
            </div>
        </header>