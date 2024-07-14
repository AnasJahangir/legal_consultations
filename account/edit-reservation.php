<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
require "../connect/config.php";

$user_id = intval($_SESSION['user_id']);

$pdo = new mypdo();

$user = $pdo->get_one("SELECT * FROM users WHERE user_id = ?", $user_id);

$cur_page_id = "edit_reservation";


if (isset($_GET['reservation_id'])) {
    $reservation_id = $_GET['reservation_id'];
} else {
    header("Location: ../");
}

$action = "";
if (isset($_GET['action']) && $_GET['action'] == "modify") {
    $page_name = "Modify Reservation";
    $action = "modify_appointment";
} elseif (isset($_GET['action']) && $_GET['action'] == "reschedule") {
    $page_name = "Reschedule Reservation";
    $action = "reschedule_appointment";
} else {
    die('<div class="alert alert-danger py-5 my-5"> We can not determin this action if you want to modify or reschedule appointment. Please co back</div>');
}


$reservation = $pdo->get_one("SELECT * FROM reservations WHERE reservation_id = ? AND user_id = '$user_id'", $reservation_id);

if ($reservation == null) {
    die('<div class="alert alert-danger py-5 my-5"> Reservation not found.  Please check the url is correct</div>');
}



$today = date('Y-m-d');
$upperBound = date('Y-m-d', strtotime('+365 days'));

$services = $pdo->get_all("SELECT * FROM services  ORDER BY service_id");

$reservation_id = intval($reservation_id);

// Fetch the data as numeric array
$appointment_dates = $pdo->get_all_num("SELECT appointment_date, appointment_time FROM reservations  WHERE appointment_date BETWEEN '$today' AND '$upperBound'  AND reservation_id != '$reservation_id'");


;






?>

<?php require_once ("../templates/user_header.php"); ?>
<link href="../css/zebra_datepicker.css" rel="stylesheet" />


<!-- Page content-->
<div class="container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb main_breadcrumb">
            <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $page_name; ?></li>
        </ol>
    </nav>


    <div class="container">

        <?php if ($_GET['action'] == "modify") { ?>
            <ul id="progressbar">
                <li id="step1" class="active"><strong>Select Service</strong></li>
                <li id="step2"><strong>Select Date and Time</strong></li>
                <li id="step3"><strong>Payment</strong></li>
            </ul>
        <?php } ?>

        <form onsubmit="submitAppointment(event)" id="app_form" class="main_form" action="" method="POST">
            <input type="hidden" value="<?php echo $reservation_id; ?>" name="reservation_id" id="reservation_id">
            <div id="step_1" class="<?php if ($_GET['action'] != "modify") {
                echo 'd-none';
            } ?>">
                <h3 class="text-center mb-5">Service</h3>
                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label>Selected Service</label>
                            <select required class="form-control" onChange="updateSubService()" id="service_id">
                                <option value=""></option>
                                <?php foreach ($services as $service) {
                                    echo '<option ' . (($reservation['service_id'] == $service['service_id']) ? 'selected' : '') . '  data-option="' . $service['option'] . '" data-price="' . $service['default_price'] . '" value="' . $service['service_id'] . '">' . $service['service_name'] . '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="form-group subservice_n d-none">
                            <label>What aspect of service do you want?</label>
                            <select class="form-control" id="subservice_id" onchange="updateCallMethod()">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12"></div>
                    <div class="col-12 col-md-4">
                        <div class="form-group call_method d-none">
                            <label>Please select call method</label>
                            <select class="form-control" id="call_method">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12"></div>
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label>Service Description</label>
                            <textarea id="service_description" class="form-control" required minlength="12"
                                placeholder="Please describe your service request here"><?php echo $reservation['service_description'] ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="py-3"><button type="button" onclick="step_forward(1)"
                        class="btn btn-primary px-5">continue</button></div>
            </div>

            <div id="step_2" <?php if ($_GET['action'] == "modify") {
                echo 'style="display:none"';
            } ?>>
                <div class="py-3"><a class="text-backward" type="button" onclick="step_backward(event, 2)"><i
                            class="fa fa-arrow-left"></i> Previous</a></div>

                <h3 class="text-center mb-3"> <?php if ($_GET['action'] != "modify") {
                    echo 'Reschedule ';
                } ?> Date and
                    Time:</h3>
                <p class="text-center mb-3">Select Date and Time: <b><span id="cur_date"></span> <span
                            id="cur_time"></span></b></p>
                <div class="row">
                    <div id="date_h_container" class="col-12 col-md-6">
                        <input id="app_date" class="d-none" value="<?php echo $reservation['appointment_date'] ?>">
                    </div>
                    <div id="date_h_container" class="col-12 col-md-6">
                        <div class="timeslots">
                        </div>
                    </div>
                </div>
                <?php if ($_GET['action'] != "modify") { ?>
                    <div class="py-5 text-center"><button type="button" onclick="step_forward(3)"
                            class="btn btn-primary px-5 py-2">Submit</button></div>

                <?php } else { ?>
                    <div class="py-5 text-center"><button type="button" onclick="step_forward(2)"
                            class="btn btn-primary px-5">continue</button></div>


                <?php } ?>


            </div>

            <div class="mb-5" id="step_3" style="display:none">
                <div class="py-3"><a class="text-backward" type="button" onclick="step_backward(event, 3)">
                        <i class="fa fa-arrow-left"></i> Previous</a></div>
                <h3 class="text-center mb-3">Payment</h3>

                <div class="text-center mb-3" style="font-size:22px">Service Fee: <b id="sfee">3040 pmm</b></div>

                <div id="textExtran" class="text-center"></div>

                <p class="text-center">Select payment method below</p>

                <div class="row">
                    <div class="col text-center">
                        <div class="pay_method <?php echo ($reservation['payment_method'] == 'Apple Pay') ? 'active' : '' ?>"
                            data-value="Apple Pay">
                            <h5>Apple Pay</h5>
                            <img style="width:200px; max-width:100%" src="../img/icon/apple_pay_logo.svg">
                        </div>
                    </div>
                    <div class="col text-center">
                        <div class="pay_method <?php echo ($reservation['payment_method'] == 'Mada bank card') ? 'active' : '' ?>"
                            data-value="Mada bank card">
                            <h5>Mada bank card</h5>
                            <img style="width:200px; max-width:100%" src="../img/icon/mada-card.jpg">
                        </div>
                    </div>
                    <div class="col text-center">
                        <div class="pay_method <?php echo ($reservation['payment_method'] == 'Bank Transfer') ? 'active' : '' ?>"
                            data-value="Bank Transfer">
                            <h5>Bank Transfer</h5>
                            <img style="width:200px; max-width:100%" src="../img/icon/banj-transfer.png">
                        </div>
                    </div>
                </div>

                <div class="py-5 text-center" id="sbutton_app"><button type="button" onclick="step_forward(3)"
                        class="btn btn-primary px-5"> Book Appoint </button></div>

            </div>

            <button style="display: none;" type="submit" id="sub_btn"></button>
        </form>


    </div>
    </main>

    <script>
        glob_user = "user";

        const glob_action = "<?php echo $action; ?>";


        let glob_service_fee = <?php echo $reservation['service_fee']; ?>;
        let glob_subservice = "<?php echo $reservation['subservice']; ?>";
        let glob_call_method = "<?php echo $reservation['call_method']; ?>";
        let glob_appointment_date = "<?php echo $reservation['appointment_date']; ?>";
        let glob_appointment_time = "<?php echo $reservation['appointment_time']; ?>";

        const glob_sub_services = <?php echo json_encode($glob_sub_services); ?>;
        const glob_appointment_dates = <?php echo json_encode($appointment_dates); ?>;
    </script>



    <?php require_once ("../templates/user_footer.php"); ?>

    <script src="../js/zebra_datepicker.min.js"></script>

    <script src="../js/appointment_booking.js"></script>