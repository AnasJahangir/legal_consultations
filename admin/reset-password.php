<?php
session_start();

if (isset($_SESSION['admin_id'])) {

    header('Location: ./');
}


require "../connect/config.php";


if (!isset($_GET['pl'])) {
    header("Location: ./");
    exit();
}

$token = $_GET['pl'];


$cur_page_id = "";
$page_name = "Reset Password - " . glob_site_name;
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
                    <h2>Reset Pasword</h2>
                </div>
                <div class="card-body">
                    <p>Provide new password details below</p>
                    <form onsubmit="reset_pw_form(event, 'admin')" class="main_form" action="" method="POST">
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
    </div>


    <br><br>

</div>


<script>
    const glob_user = "admin";
</script>

<?php require_once ("../templates/admin_footer.php"); ?>