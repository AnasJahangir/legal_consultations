<?php
session_start();
require './connect/config.php';

$pdo = new mypdo();

$cur_page_id = "booking";
$page_name = "Book an Appointment - " . glob_site_name;

$today = date('Y-m-d');
$upperBound = date('Y-m-d', strtotime('+365 days'));

$services = $pdo->get_all("SELECT * FROM services  ORDER BY service_id");

// Fetch the data as numeric array
$appointment_dates = $pdo->get_all_num("SELECT appointment_date, appointment_time FROM reservations  WHERE appointment_date BETWEEN '$today' AND '$upperBound' ");


$ref_service_id = "";
if (isset($_GET['service_id'])) {
    $ref_service_id = $_GET['service_id'];
}
;


?>

<?php require_once ("templates/header.php"); ?>
<link href="./css/zebra_datepicker.css" rel="stylesheet" />

<header class="main_header">
    <h1>Book an Appointment</h1>
</header>

<style>

</style>

<!-- Page content-->
<main>
    <div class="container py-4">

        <ul id="progressbar">
            <li id="step1" class="active"><strong>Select Service</strong></li>
            <li id="step2"><strong>Select Date and Time</strong></li>
            <li id="step3"><strong>Payment</strong></li>
        </ul>

        <?php if (!isset($_SESSION['user_id'])) { ?>

            <div class="alert text-center my-5 alert-warning">
                <h2><i class="fa fa-info-circle fa-2x"></i></h2>
                <p style="font-size:20px">Please <a href="./login.php">login</a> or <a href="./sign-up.php">Create an account</a> in order to book an appointment</p>
            </div>


        <?php } else { ?>

            <form onsubmit="submitAppointment(event)" id="app_form" class="main_form" action="" method="POST">

                <div id="step_1">
                    <h3 class="text-center mb-5">Service</h3>
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <div class="form-group">
                                <label>Selected Service</label>
                                <select required class="form-control" onChange="updateSubService()" id="service_id">
                                    <option value=""></option>
                                    <?php foreach ($services as $service) {
                                        echo '<option ' . (($ref_service_id == $service['service_id']) ? 'selected' : '') . '  data-option="' . $service['option'] . '" data-price="' . $service['default_price'] . '" value="' . $service['service_id'] . '">' . $service['service_name'] . '</option>';
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
                                    placeholder="Please describe your service request here"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="py-3"><button type="button" onclick="step_forward(1)"
                            class="btn btn-primary px-5">continue</button></div>
                </div>


                <div id="step_2" style="display:none">
                    <div class="py-3"><a class="text-backward" type="button" onclick="step_backward(event, 2)"><i
                                class="fa fa-arrow-left"></i> Previous</a></div>

                    <h3 class="text-center mb-3">Date and Time:</h3>
                    <p class="text-center mb-3">Select Date and Time: <b><span id="cur_date"></span> <span
                                id="cur_time"></span></b></p>
                    <div class="row">
                        <div id="date_h_container" class="col-12 col-md-6">
                            <input id="app_date" class="d-none">
                        </div>
                        <div id="date_h_container" class="col-12 col-md-6">
                            <div class="timeslots">
                            </div>
                        </div>
                    </div>

                    <div class="py-5 text-center"><button type="button" onclick="step_forward(2)"
                            class="btn btn-primary px-5">continue</button></div>

                </div>

                <div class="mb-5" id="step_3" style="display:none">
                    <div class="py-3"><a class="text-backward" type="button" onclick="step_backward(event, 3)">
                            <i class="fa fa-arrow-left"></i> Previous</a></div>
                    <h3 class="text-center mb-3">Payment</h3>

                    <div class="text-center mb-3" style="font-size:22px">Service Fee: <b id="sfee">3040 pmm</b></div>

                    <p class="text-center">Select payment method below</p>

                    <div class="row">
                        <div class="col text-center">
                            <div class="pay_method" data-value="Apple Pay">
                                <h5>Apple Pay</h5>
                                <img style="width:200px; max-width:100%" src="img/icon/apple_pay_logo.svg">
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="pay_method" data-value="Mada bank card">
                                <h5>Mada bank card</h5>
                                <img style="width:200px; max-width:100%" src="img/icon/mada-card.jpg">
                            </div>
                        </div>
                        <div class="col text-center">
                            <div class="pay_method" data-value="Bank Transfer">
                                <h5>Bank Transfer</h5>
                                <img style="width:200px; max-width:100%" src="img/icon/banj-transfer.png">
                            </div>
                        </div>
                    </div>

                    <div class="py-5 text-center" id="sbutton_app"><button type="button" onclick="step_forward(3)"
                            class="btn btn-primary px-5"> Book Appoint </button></div>

                </div>

                <button style="display: none;" type="submit" id="sub_btn"></button>
            </form>

        <?php } ?>



    </div>
</main>

<script>
    glob_user = "user";

    const glob_action = "new_appointment";

    let glob_service_fee = "";
    let glob_subservice = "";
    let glob_call_method = "";
    let glob_appointment_date = "";
    let glob_appointment_time = "";

    
    const glob_sub_services = <?php echo json_encode($glob_sub_services); ?>;
    const glob_appointment_dates = <?php echo json_encode($appointment_dates); ?>;
</script>



<?php require_once ("templates/footer.php"); ?>

<script src="./js/zebra_datepicker.min.js"></script>

<script src="./js/appointment_booking.js"></script>

