<?php
session_start();
require './connect/config.php';

$pdo = new mypdo();

$cur_page_id = "services";
$page_name = "Our Services - " . glob_site_name;

$services = $pdo->get_all("SELECT * FROM services  ORDER BY service_id");


?>

<?php require_once ("templates/header.php"); ?>

<header class="main_header">
    <h1>Our Services</h1>
</header>

<!-- Page content-->
<main>
    <div class="container py-4">
        <section class="services-section">
            <p style="font-size:18px">Below are services we offer. You can go throught and book an appointment for the
                one that is appropriate
                for you</p>
            <ul class="services mt-3">

                <?php foreach ($services as $service) {

                    // $subservices = $pdo->get_all("SELECT * FROM services WHERE parent_id = ? ORDER BY service_id", $service['service_id']);
                
                    ?>
                    <li class="service">
                        <h2 class="service_header" data-open="1">
                            <div>
                                <?php echo $service['service_name']; ?>
                            </div>
                            <span class="fa fa-caret-down"></span>
                        </h2>

                        <div class="px-3 mb-4 subservices">
                            <div class="mb-3" style="white-space:pre-wrap"><?php echo $service['service_description'] ?></div>
                            <div><a href="./book-appointment.php?service_id=<?php echo $service['service_id']; ?>&tm=" class="btn btn-primary"><i class="fa fa-calendar"></i> Book an appointment </a></div>
                        </div>


                    </li>

                <?php } ?>


                

            </ul>

        </section>
    </div>
</main>

<script>
    glob_user = "user";
</script>

<?php require_once ("templates/footer.php"); ?>