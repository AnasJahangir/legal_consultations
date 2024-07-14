<?php
session_start();

if (isset($_GET['logout'])) {
    if (isset($_SESSION['user_id'])) {
        //unset the seesion
        session_unset();
        session_destroy();
        header('Location: ./');
    }
}

if (isset($_SESSION['user_id'])) { // Already login
    header('Location: ./');
}





require './connect/config.php';

$pdo = new mypdo();

$cur_page_id = "signin";
$page_name = "User Login - " . glob_site_name;



?>
<?php require_once("templates/header.php"); ?>


<header class="main_header">
        <h1> Sign in </h1>
</header>

<!-- Page content-->
<main>


    <section>

        <div class="container">
            <div class="cform">
                <div class="cform_wrapper">

                <form onsubmit="sign_in_form(event)" id="sign_form" class="main_form" action="" method="POST">
                
                    <input type="hidden" value="signin" name="ch">

                        <div class="form-group">
                            <label for="email"><b>Email</b></label>
                            <input value="" required class="form-control" type="email" placeholder="Enter Email" id="email" name="email" type="email" required>
                        </div>
                        <div class="form-group">
                            <label for="password"><b>Password</b></label>
                            <input required class="form-control" type="password" placeholder="Enter Password" id="password" name="password" required>
                        </div>
                        <div class="form-group sbutton" id="sbutton">
                            <button type="submit" class="btn btn-block btn-primary px-4">Login</button>
                            <a style="float: right;" href="forgot-password.php">Forgot password?</a>
                        </div>
                        <label>
                            <!-- <input type="checkbox" checked="checked" name="remember"> Remember me -->
                        </label>

                        <div style="float:right">
                            <span>Don't Have An account? <a href="sign-up"> Sign Up Here</a></span>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </section>
</main>

<script>
    glob_user = "user";
</script>

<?php require_once("templates/footer.php"); ?>