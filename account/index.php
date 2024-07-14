<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../");
    exit();
}
require "../connect/config.php";

$user_id = intval($_SESSION['user_id']);

$pdo = new mypdo();

$user = $pdo->get_one("SELECT * FROM users WHERE user_id = ?", $user_id);

$cur_page_id = "dashboard";
$page_name = "Dashboard - " . glob_site_name;

$date_now = date("Y-m-d", time());
$time_now = date("H:i:s", time());
$datetime_now = date("H:i:s H:i:s", time());


$reservations = $pdo->get_all("SELECT  b.service_name, a.* FROM reservations a LEFT JOIN services b ON a.service_id = b.service_id  WHERE a.user_id = '$user_id' AND CONCAT(appointment_date, ' ', appointment_time) > '$datetime_now' ORDER BY appointment_date ASC, appointment_time ASC");




?>

<?php require_once("../templates/user_header.php"); ?>


<!-- Page content-->
<div class="container-fluid text-center">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb main_breadcrumb">
            <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
        </ol>
    </nav>


    <div class="py-3">
        <div class="card pallet_card text-start primary">
            <div class="card-header">
                <h3><span class="fa fa-calendar"></span> Upcoming  Appointment</h3>
            </div>
            <div class="card-body">


 <?php if(count($reservations) > 0){ $reservation = $reservations[0];  ?>
            <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>Next Appointment Date</th>
                            <td>
                                <?php echo date("Y-m-d h:i a", strtotime($reservation['appointment_date']. ' '.$reservation['appointment_time'])); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Service</th>
                            <td>
                                <?php echo $reservation['service_name']; ?>
                            </td>
                        </tr>
                        <?php if($reservation['subservice'] != ""){ ?>
                        <tr>
                            <th>Sub Service.</th>
                            <td>
                            <?php echo $reservation['subservice']; ?>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php if($reservation['call_method'] != ""){ ?>
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


    <div class="text-center"><a href="my-reservations.php" class="btn btn-primary"> View Reservations </a></div>



</div>



<?php require_once("../templates/user_footer.php"); ?>