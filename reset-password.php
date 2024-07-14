<?php
session_start();


require './connect/config.php';


$cur_page_id = "resetpw";
$page_name = "Reset Password - " . glob_site_name;


if (!isset($_GET['pl'])) {
    header("Location: ./");
    exit();
}

$token = $_GET['pl'];


?>
<?php require_once ("templates/header.php"); ?>



<header class="main_header">
    <h1> Reset Password </h1>
</header>

<!-- Page content-->
<main>
    <section>

        <div class="container">

            <div class="cform">
                <div class="cform_wrapper" style="max-width:500px">
                    <p>Provide new password details below</p>
                    <form onsubmit="reset_pw_form(event, 'user')" class="main_form" action="" method="POST">
                        <input type="password" style="display:none">
                        <input type="hidden" value="reset_password" name="ch">
                        <input type="hidden" value="<?php echo $token; ?>" name="token">
                        <div class="row py-3">
                            <div class="col-12 form-group">
                                <label for="password">New Password</label>
                                <div class="input-group">
                                    <input required class="form-control has_pattern" type="password"
                                        placeholder="Enter Password" id="password" name="password" required,
                                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                                        data-error_message="Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character.">
                                    <div class="input-group-append"><button type="button" onclick="pwd_vtoggle(event)"
                                            class="xbtn input-group-text"><i class="fa fa-eye"></i></button></div>
                                </div>
                            </div>
                            <div class=" col-12  form-group">
                                <label for="password2">Repeat Password</label>
                                <div class="input-group">
                                    <input required class="form-control has_pattern" type="password"
                                        placeholder="Repeat Password" id="password2" name="password2" required,
                                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                                        data-error_message="Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character.">
                                    <div class="input-group-append"><button type="button" onclick="pwd_vtoggle(event)"
                                            class="xbtn input-group-text"><i class="fa fa-eye"></i></button></div>
                                </div>
                            </div>

                            <div class="form-group text-right py-3 sbutton" id="sbutton">
                                <button type="submit" class="btn btn-block btn-lg btn-primary px-5"> Reset Password
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>


        </div>
    </section>


</main>

<?php require_once ("templates/footer.php"); ?>