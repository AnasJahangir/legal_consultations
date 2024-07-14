<?php

session_start();


require './connect/config.php';

$cur_page_id = "home";
$page_name = glob_site_name;

$pdo = new mypdo();


?>
<?php require_once("templates/header.php"); ?>

<style>
	body {
		background-color: #FFF !important;
	}
</style>
<!-- carousel section -->
<!-- Header Carousel -->
<section style="position: relative;">

	<!-- Carousel -->
	<div id="demo" class="carousel slide" data-bs-ride="carousel">

		<!-- Indicators/dots -->
		<div class="carousel-indicators">
			<button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
			<button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
			<button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
		</div>

		<!-- The slideshow/carousel -->
		<div class="carousel-inner">
			<div class="carousel-item active">
				<div class="carousel-item-wrapper">
					<div class="px-1 px-md-5">
						<h1>Do not lie or make promises;</h1>
						<p>On top of that, know that to be a lawyer, you have to be a human in the first place.</p>
						<a href="sign-up.php">Create an Account </a>
					</div>
				</div>
				<img class="d-block w-100" src="img/bg_1.png" alt="First slide">
			</div>
			<div class="carousel-item">
				<div class="carousel-item-wrapper">
					<div class="px-1 px-md-5">
						<h1>You are not the decision-maker and you are not responsible for the results</h1>
						<p>Never lose yourself for the sake of winning a lawsuit” Dr. Abdul Razzaq Al-Sanhouri.</p>
						<a href="sign-up.php">Sign Up </a>
					</div>
				</div>
				<img class="d-block w-100" src="img/bg_2.png" alt="Second slide">
			</div>
		</div>

		<!-- Left and right controls/icons -->
		<button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
			<span class="carousel-control-prev-icon"></span>
		</button>
		<button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
			<span class="carousel-control-next-icon"></span>
		</button>
	</div>


</section>

<!-- end carousel section -->

<!-- Page content-->
<main>

	<div class="container page-section text-start">

	
	<section id="about-us">
		<div class="container py-5">
			<div class="row">
				<div class="col-12 col-md-8">

					<h2>ABOUT US</h2>

					<p>Sameer Al Yousef law firm and legal consultations was established in the year 2021 and is one of the leading law firms in providing legal solutions to commercial entities, local and foreign companies, businessmen, and individuals. We provide legal representation before all courts of all degrees and types, draft all types of contracts, and offer mediation and arbitration services. These services are provided by elite, highly qualified legal advisors and lawyers with extensive experience to provide legal advisory solutions according to the best professional quality standards.</p>
               
						<a class="btn btn-primary mb-5" href="about.php">Read more</a>
					
				</div>
				<div class="col-12 col-md-4">
					<img src="./img/bg_3.png" style="max-width: 100%;">
				</div>
			</div>

		</div>
	</section>





	</div>


</main>

<?php require_once("templates/footer.php"); ?>