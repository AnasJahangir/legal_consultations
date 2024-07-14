<?php
session_start();

if (isset($_SESSION['user_id'])) { // Already login
    header('Location: ./');
}





require './connect/config.php';

$pdo = new mypdo();

$cur_page_id = "fgot";
$page_name = "Forgot Password - " . glob_site_name;



?>
<?php require_once("templates/header.php"); ?>


<header class="main_header">
        <h1> Forgot Passsword? </h1>
</header>

<!-- Page content-->
<main>


    <section>

        <div class="container">
            <div class="cform">
                <div class="cform_wrapper" style="max-width:500px">

                <div class="fa fa-alert">Please provide the Email address used in creating the account below:</div>
                <form onsubmit="forgot_pw_form(event, 'user')" id="fget_form" class="main_form" action="" method="POST">
                
                    <input type="hidden" value="forgot_password" name="ch">
                    <input type="hidden" value="user" name="type">

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
    </section>
</main>

<script>
    glob_user = "user";
</script>

<?php require_once("templates/footer.php"); ?>