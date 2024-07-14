<?php
session_start();
require './connect/config.php';

$pdo = new mypdo();

$cur_page_id = "about";
$page_name = "About Us - " . glob_site_name;
?>

<?php require_once("templates/header.php"); ?>

<style>
        
    </style>
<header class="main_header">
    <h1>About Us</h1>
</header>

<!-- Page content-->
<main>
    <div class="container about-page py-5 page-section">
        
        <section class="about-section py-3">
            <h2 class="section-heading"><i class="fa fa-info-circle icon"></i>About Us</h2>
            <div class="row">
                <div class="col-12 col-md-6 align-self-center">
                    <p>Sameer Al Yousef law firm and legal consultations was established in the year 2021 and is one of the leading law firms in providing legal solutions to commercial entities, local and foreign companies, businessmen, and individuals. We provide legal representation before all courts of all degrees and types, draft all types of contracts, and offer mediation and arbitration services. These services are provided by elite, highly qualified legal advisors and lawyers with extensive experience to provide legal advisory solutions according to the best professional quality standards.</p>
                </div>
                <div class="col-12 col-md-6 px-3">
                    <img src="./img/bg_1.png" alt="Team photo" class="img-fluid rounded">
                </div>
            </div>
        </section>

        <section class="founder-section py-3">
            <h2 class="section-heading"><i class="fa fa-user-o icon"></i>About the Founder</h2>
            <p>Dr. Sameer ALYousef graduated in the Legal Affairs Department of the Royal Commission in Jubail as a legal researcher and then as a legal advisor, until he was appointed as a Director of the Legal Department from 2010 until the year 2020.</p>
            <p>He prepared draft regulations, by-laws, and instructions related to the activities of the Royal Commission, such as preparing, drafting, and studying contracts, agreements, and memorandums of understanding, and providing legal advice to all departments of the Royal Commission.</p> 
            <p>He was a member of several committees, including the advisory committee of the Royal Commission, the management committee of the City Services and Social Activities Support Fund, the Crisis and Disaster Management team, the Committee for the Investigation of Administrative Violations and Abuses, the Employment Committee, the Committee for Considering Grievances against the Degree of Job Performance Evaluation, and the Investment Opportunities Evaluation Committee in the industrial cities of Jubail and Ras Al-Khair. He also represented the Royal Commission before the regulatory authorities.</p>
        </section>

        <section class="values-section py-3">
            <h2 class="section-heading"><i class="fa fa-gem icon"></i>Our Values</h2>
            <ol>
                <li class="py-3"><strong>EXCELLENCE:</strong> We provide our services to all beneficiaries with the highest standards of quality and reliability, without diminution, indication, or hint to any category; as they all are a part of our services.</li>
                <li class="py-3"><strong>PROFESSIONALISM:</strong> We seek to work with high professional standards governed first by what our true religion dictates. Then, we work by following internationally approved policies, regulations, and laws to maintain a high level of professionalism.</li>
                <li class="py-3"><strong>CONFIDENTIALITY AND RELIABILITY:</strong> We aim to gain the clients' trust by committing to integrity, privacy, and confidentiality, and by applying the highest ethical standards while dealing with our customers.</li>
            </ol>
        </section>

        <div class="row">
            <div class="col-12 col-md-6">
                <section class="vision-section py-3">
                    <h2 class="section-heading"><i class="fa fa-eye icon"></i>Our Vision</h2>
                    <p>Our vision is to make our company a pioneer in the field of legal consulting and services, providing legal protection for local and foreign companies and individuals through a selection of experienced and specialized consultants, looking forward to upgrading to the highest standards of quality and professionalism.</p>
                </section>
            </div>
            <div class="col-12 col-md-6">
                <section class="mission-section py-3">
                    <h2 class="section-heading"><i class="fa fa-bullseye icon"></i>Our Mission</h2>
                    <p>We seek to provide legal services with professionalism, quality, and continuous development in order to reach systemic and legal rights and preserve the interests of clients.</p>
                </section>
            </div>
        </div>

        <section class="goals-section py-3">
            <h2 class="section-heading"><i class="fa fa-tasks icon"></i>Our Strategic Goals</h2>
            <ol>
                <li class="py-2">To be a role model in achieving community justice by providing legal consultation with the highest professional standards.</li>
                <li class="py-2">To cooperate with all parties to build strong and solid relationships, contributing to meeting the needs of our clients and producing the best results in record time.</li>
                <li class="py-2">To obtain the satisfaction and added value of beneficiaries, after the satisfaction of Allah, by contributing to providing our services to them, as they are the main indicator of the quality and professionalism of what we do.</li>
                <li class="py-2">To support excellent legal work by raising awareness and legal culture in society, enabling the rising generation of legal professionals.</li>
            </ol>
        </section>
    </div>
</main>
<script>
    glob_user = "user";
</script>

<?php require_once("templates/footer.php"); ?>
