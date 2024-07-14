<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../");
    exit();
}
require "../connect/config.php";

$user_id = intval($_SESSION['user_id']);

$pdo = new mypdo();

$user = $pdo->get_one("SELECT * FROM users WHERE user_id = ?", $user_id);


$cur_page_id = "profile";
$page_name = "My Profile - " . glob_site_name;

?>

<?php require_once ("../templates/user_header.php"); ?>


<!-- Page content-->
<div class="container-fluid text-center">



    <div class="row mt-3">
        <div class="col-12 col-md-12">
            <div class="card dashboard_card">
                <div class="card-header">
                    <a id="toglle_gen_btn" href="#" onclick="toggleEditMode(event, '.card')"
                        class="form_etion fa fa-edit text-main float-end"></a>
                    <h3>MY ACCOUNT</h3>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-md-3 align-self-center">
                            <i style="color:#CCC" class="fa fa-user-circle fa-5x"></i>
                        </div>
                        <div class="col-12 col-md-9 align-self-center">
                            <form class="main_form update_form" onsubmit="updateGeneralInfo(event)">

                                <div class="row py-3 text-start">

                                    <div class="col-12 col-md-6 form-group">
                                        <label for="first_name">First Name</label>
                                        <input value="<?php echo $user['first_name']; ?>" disabled required
                                            class="form-control" placeholder="Enter first name" id="first_name"
                                            name="first_name">
                                    </div>
                                    <div class="col-12 col-md-6 form-group">
                                        <label for="last_name">Last Name</label>
                                        <input value="<?php echo $user['last_name']; ?>" disabled required
                                            class="form-control" placeholder="Enter last name" id="last_name"
                                            name="last_name">
                                    </div>
                                    <div class="col-12 col-md-6 form-group">
                                        <label for="phone">Phone number</label>
                                        <input value="<?php echo $user['phone']; ?>" disabled required
                                            class="form-control" placeholder="" id="phone" name="phone">
                                    </div>


                                </div>


                                <div id="update_sbutton" class="text-center py-3">
                                    <button style="display:none" class="btn btn-success py-2 px-5"><span
                                            class="fa fa-save mr-2"></span>
                                        Save</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>



    <div class="row py-5">
        <div class="col-12 col-md-6">
            <div class="card dashboard_card mt-3">
                <div class="card-header">
                    <h3>UPDATE PASSWORD</h3>
                </div>
                <div class="card-body text-start">


                    <form id="login_form" onsubmit="update_password(event)" class="n_form main_form mt-3"
                        autocomplete="off">
                        <input type="password" name="mm" style="display:none">
                        <div class="form-group">
                            <label for="old_password">Old Password</label>
                            <div class="input-group">
                                <input required class="form-control has_pattern" type="password"
                                    placeholder="Enter Old Password" id="old_password" name="old_password" required>
                                <div class="input-group-append"><button type="button" onclick="pwd_vtoggle(event)"
                                        class="xbtn input-group-text"><i class="fa fa-eye"></i></button></div>
                            </div>

                        </div>

                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <div class="input-group">
                                <input required class="form-control has_pattern" type="password"
                                    placeholder="Enter New Password" id="new_password" name="new_password" required,
                                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                                    data-error_message="Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character.">
                                <div class="input-group-append"><button type="button" onclick="pwd_vtoggle(event)"
                                        class="xbtn input-group-text"><i class="fa fa-eye"></i></button></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="rnew_password">Repeat New Password</label>
                            <div class="input-group">
                                <input required class="form-control has_pattern" type="password"
                                    placeholder="Re: Enter New Password" id="rnew_password" name="rnew_password"
                                    required,
                                    pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                                    data-error_message="Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character.">
                                <div class="input-group-append"><button type="button" onclick="pwd_vtoggle(event)"
                                        class="xbtn input-group-text"><i class="fa fa-eye"></i></button></div>
                            </div>
                        </div>

                        <div id="pwd_sbutton" class="py-3">
                            <button class="btn btn-success py-2 px-3"> Update Password</button>
                        </div>
                    </form>

                </div>

            </div>

        </div>

        <div class="col-12 col-md-6">
            <div class="card dashboard_card mt-3">
                <div class="card-header">
                    <h3>UPDATE EMAIL ADDRESS</h3>
                </div>
                <div class="card-body text-start">

                    <form id="register_form" onsubmit="update_user_email(event)" class="n_form main_form py-5">
                        <div class="form-group py-3">
                            <label for="email"> Email Address</label>
                            <input value="<?php echo $user['email']; ?>" class="form-control" id="update_email"
                                type="email">
                        </div>
                        <div id="ema_sbutton" class="py-3 text-end">
                            <button class="btn btn-success py-2 px-5"> Update </button>
                        </div>
                    </form>

                </div>

            </div>

        </div>

    </div>









</div>
</div>
</div>
<script>
    let glob_user = "user";
</script>


<?php require_once ("../templates/user_footer.php"); ?>