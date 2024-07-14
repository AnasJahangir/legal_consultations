<?php

if(isset($user)){
    $notification_counter = $pdo->get_one("SELECT COUNT(*) AS counter FROM admin_messages");
$notification_counter = $notification_counter['counter'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>
        <?php echo $page_name; ?>
    </title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="../css/bootstrap.min.css" rel="stylesheet" />

    <link
        href="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/datatables.min.css"
        rel="stylesheet">


    <!-- Font Awesome -->
    <link href="../css/font-awesome.min.css" rel="stylesheet" />

    <link href="../css/toastr.min.css" rel="stylesheet" />

    <link href="../css/custom.css" rel="stylesheet" />
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <?php if (@$open_page != "1") { ?>
            <div class="border-end side_nav" id="sidebar-wrapper">
                <div class="sidebar-heading border-bottom"><img src="../img/logo.jpeg"> Admin</div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="./"><span
                            class="fa fa-dashboard"></span> Dashboard</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3"
                        href="reservations.php"><span class="fa fa-calendar"></span> Reservations</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="services.php"><span
                            class="fa fa-globe"></span> Services</a>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="users.php"><span
                            class="fa fa-users"></span> Users</a>
                    <?php if ($user['role'] == 1) { ?>
                        <a class="list-group-item list-group-item-action list-group-item-light p-3" href="admins.php"><span
                                class="fa fa-user-secret"></span> Admins</a>
                    <?php } ?>
                    <a class="list-group-item list-group-item-action list-group-item-light p-3" href="profile.php"><span
                            class="fa fa-user-circle-o"></span> My Profile</a>
                </div>
            </div>

            <!-- Page content wrapper-->
            <div id="page-content-wrapper">
                <!-- Top navigation-->

                <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom top-nav">
                    <div class="container-fluid">
                        <button class="btn btn-primary" id="sidebarToggle">Menu</button>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation"><span
                                class="navbar-toggler-icon text-white" style="color:#FFF !important"></span></button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                                <li class="nav-item active"><a class="nav-link" href="../">Home</a></li>
                                <li class="nav-item active"><a class="nav-link" href="../about.php">About us</a></li>
                                <li class="nav-item active"><a class="nav-link" href="../our-service.php">Our Service</a>
                                </li>
                                <li class="nav-item active"><a class="nav-link" href="../contact.php">Contact us</a></li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                            class="fa fa-user-circle-o"></i> <?php echo $user['email']; ?>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="profile.php"> Update profile</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="./login.php?logout=1">Logout</a>
                                    </div>
                                </li>
                                <li class="nav-item active position-relative"><a class="nav-link" href="./"><i class="fa fa-bell-o"></i> <?php echo ($notification_counter > 0)?'<span id="message_counter" class="badge rounded-pill bg-dark position-absolute top-0">'.$notification_counter.'</span>':'';  ?></a></li>
                                
                                <li style="display:flex; align-items:center" id="google_translate_element" class="nav-item"></li>

                            </ul>
                        </div>
                    </div>
                </nav>

            <?php } else { ?>

                <!-- Page content wrapper-->
                <div id="page-content-wrapper">
                <div style="display:flex; align-items:center" id="google_translate_element" class="nav-item"></div>


                <?php } ?>

                <script type="text/javascript">

function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'en,ar',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
    }, 'google_translate_element');
}
</script>
<script type="text/javascript"
src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

                