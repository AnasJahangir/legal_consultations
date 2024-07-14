<?php
session_start();

if (isset($_GET['logout'])) {
    if (isset($_SESSION['admin_id'])) {
        //unset the seesion
        session_unset();
        session_destroy();
        header('Location: ./');
    }
}

if (isset($_SESSION['admin_id'])) {

    header('Location: ./');
}



require "../connect/config.php";


$cur_page_id = "signin";
$page_name = "Admin Login - " . glob_site_name;
$open_page = "1";

?>

<?php require_once ("../templates/admin_header.php"); ?>

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
                    <h3>Admin Login</h3>
                </div>
                <div class="card-body">
                    <form onsubmit="sign_in_form(event)" id="sign_form" class="main_form" action="" method="POST">

                        <input type="hidden" value="admin_signin" name="ch">

                        <div class="form-group">
                            <label for="email"><b>Email</b></label>
                            <input value="" required class="form-control" type="email" placeholder="Enter Email"
                                id="email" name="email" type="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password"><b>Password</b></label>
                            <div class="input-group">
                                <input required class="form-control" type="password" placeholder="Enter Password"
                                    id="password" name="password" required>
                                <div class="input-group-append"><button type="button" onclick="pwd_vtoggle(event)"
                                        class="xbtn input-group-text"><i class="fa fa-eye"></i></button></div>
                            </div>


                        </div>
                        <div class="form-group sbutton " id="sbutton">
                            <a href="./forgot-password.php">Forgot Password?</a>
                            <button type="submit" class="btn pull-right btn-block btn-primary px-4">Login</button>
                            <!-- <a style="float: right;" href="forgot-password.php">Forgot password?</a> -->
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

<?php require_once ("../templates/admin_footer.php"); ?>