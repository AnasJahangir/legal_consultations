<?php
session_start();

if (isset($_SESSION['admin_id'])) {

    header('Location: ./');
}


require "../connect/config.php";


$cur_page_id = "";
$page_name = "Forgot Password - " . glob_site_name;
$open_page = "1";

?>

<?php require_once("../templates/admin_header.php"); ?>

<style>
    body {
        background-color: #ddb458 !important;
    }
</style>
<!-- Page content-->
<div class="container-fluid text-center">

<div><a href="../">Home</a></div>

    <div class="cform">
        <div class="cform_wrapper">
            <div class="card">
                <div class="card-header text-center">
                    <h2>Forgot Pasword</h2>
                </div>
                <div class="card-body">
                <form onsubmit="forgot_pw_form(event, 'admin')" id="fget_form" class="main_form" action="" method="POST">
                
                <input type="hidden" value="forgot_password" name="ch">
                <input type="hidden" value="admin" name="type">

                    <div class="form-group">
                        <label for="email"><b>Email</b></label>
                        <input value="" required class="form-control" type="email" placeholder="Enter Email" id="email" name="email" type="email" required>
                    </div>
                    <div class="form-group sbutton text-end" id="sbutton">
                        <button type="submit" class="btn btn-block btn-primary px-4">Submit</button>
                    </div>

                </form>

                </div>
            </div>
        </div>
    </div>


    <br><br>

</div>


<script>
    const glob_user = "admin";
</script>

<?php require_once("../templates/admin_footer.php"); ?>