<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ./login.php");
    exit();
}
require "../connect/config.php";

$user_id = intval($_SESSION['admin_id']);

$pdo = new mypdo();

$user = $pdo->get_one("SELECT * FROM admins WHERE admin_id = ?", $user_id);

$cur_page_id = "dashboard";
$page_name = "Dashboard - " . glob_site_name;



$date_now = date("Y-m-d", time());
$time_now = date("H:i:s", time());
$datetime_now = date("H:i:s H:i:s", time());


$reservations = $pdo->get_all("SELECT  b.service_name, a.* FROM reservations a LEFT JOIN services b ON a.service_id = b.service_id  WHERE  CONCAT(appointment_date, ' ', appointment_time) > '$datetime_now' ORDER BY appointment_date ASC, appointment_time ASC LIMIT 10");


$messages = $pdo->get_all("SELECT * FROM  admin_messages ORDER BY created_at DESC LIMIT 10");





?>

<?php require_once ("../templates/admin_header.php"); ?>


<!-- Page content-->
<div class="container-fluid text-center">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb main_breadcrumb">
            <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
        </ol>
    </nav>

    <?php

    foreach ($messages as $message) {
        $alert_code = "alert-warning";
        if (strpos(strtolower($message['message']), "has been modified") !== false) {
            $alert_code = "alert-info";
        } elseif (strpos(strtolower($message['message']), "been reschedule") !== false) {
            $alert_code = "alert-secondary";
        } elseif (strpos(strtolower($message['message']), "new appointment booking") !== false) {
            $alert_code = "alert-success";
        } elseif (strpos(strtolower($message['message']), "was canceled by") !== false) {
            $alert_code = "alert-warning";
        }

        ?>
        <div class="alert <?php echo $alert_code; ?> alert-dismissible fade show" role="alert">
            <?php echo $message['message']; ?>
            <button onclick="dismiss_message(<?php echo $message['id']; ?>, 'admin')" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

    <?php } ?>


    <div class="py-3">
        <div class="card pallet_card text-start primary">
            <div class="card-header">
                <h3><span class="fa fa-calendar"></span> Upcoming Appointment</h3>
            </div>
            <div class="card-body">


                <?php foreach ($reservations as $reservation) { ?>
                    <table class="table table-bordered table-striped" style="border-top:10px solid #990">
                        <tbody>
                            <tr>
                                <th>Next Appointment Date</th>
                                <td>
                                    <?php echo date("Y-m-d h:i a", strtotime($reservation['appointment_date'] . ' ' . $reservation['appointment_time'])); ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Service</th>
                                <td>
                                    <?php echo $reservation['service_name']; ?>
                                </td>
                            </tr>
                            <?php if ($reservation['subservice'] != "") { ?>
                                <tr>
                                    <th>Sub Service.</th>
                                    <td>
                                        <?php echo $reservation['subservice']; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($reservation['call_method'] != "") { ?>
                                <tr>
                                    <th>call method.</th>
                                    <td>
                                        <?php echo $reservation['call_method']; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th>Your Request</th>
                                <td>
                                    <?php echo $reservation['service_description']; ?>
                                </td>
                            </tr>
                        </tbody>

                    </table>

                <?php } ?>

            </div>
        </div>
    </div>


    <div class="text-center mb-5"><a href="reservations.php" class="btn btn-primary"> View Reservations </a></div>



</div>




<?php require_once ("../templates/admin_footer.php"); ?>