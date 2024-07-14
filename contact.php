<?php
session_start();


require './connect/config.php';

$pdo = new mypdo();

$cur_page_id = "contact";
$page_name = "Contact Us - " . glob_site_name;



?>
<?php require_once("templates/header.php"); ?>

<style>
        .contact-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 40px 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            text-align: center;
        }
        .contact-info {
            margin: 20px 0;
        }
        .contact-info p {
            margin: 10px 0;
            font-size: 18px;
        }
        .social-icons a {
            margin: 0 15px;
            font-size: 24px;
            color: #007bff;
            transition: color 0.3s;
        }
        .social-icons a:hover {
            color: #0056b3;
        }
    </style>

<header class="main_header">
    <h1> Contact Us </h1>
</header>

<!-- Page content-->
<main>


    <div class="container py-5">
        <section class="contact-section">
            <h2 class="section-heading"><i class="fa fa-hand-shake icon"></i> Get in Touch with us</h2>
            <div class="contact-info">
                <p><i class="fa fa-phone icon"></i> Phone: <strong><?php echo glob_site_phone; ?></strong></p>
                <p><i class="fa fa-envelope icon"></i> Email: <strong><a href="mailto:info@<?php echo glob_site_url_fd; ?>">info@<?php echo glob_site_url_fd; ?></a></strong></p>
                <p><i class="fa fa-map-marker icon"></i> Address: <strong><?php echo glob_site_address; ?></strong></p>
            </div>
            <p>We would love to hear from you! Connect with us on social media:</p>
            <div class="social-icons">
                <a href="https://twitter.com/yourusername" target="_blank"><i class="fa fa-twitter"></i></a>
                <a href="https://instagram.com/yourusername" target="_blank"><i class="fa fa-instagram"></i></a>
                <a href="https://youtube.com/yourusername" target="_blank"><i class="fa fa-youtube"></i></a>
                <a href="https://facebook.com/yourusername" target="_blank"><i class="fa fa-facebook"></i></a>
            </div>
        </section>
    </div>


</main>

<script>
    glob_user = "user";
</script>

<?php require_once("templates/footer.php"); ?>