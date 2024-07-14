<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../");
    exit();
}
require "../connect/config.php";

$user_id = intval($_SESSION['admin_id']);

$pdo = new mypdo();

$user = $pdo->get_one("SELECT * FROM admins WHERE admin_id = ?", $user_id);


$cur_page_id = "profile";
$page_name = "Admin Profile";

?>

<?php require_once("../templates/admin_header.php"); ?>


<!-- Page content-->
<div class="container-fluid text-center">



    <div class="row pt-3">
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
                            <i class="fa fa-user-circle fa-5x" style="color:#CCC"></i>
                        </div>
                        <div class="col-12 col-md-9 align-self-center">
                            <form class="main_form update_form" onsubmit="updateGeneralInfo(event)">

                                <div class="row py-3 text-start">

                                    <div class="col-12 col-md-6 form-group">
                                        <label for="fname">First Name</label>
                                        <input value="<?php echo $user['first_name']; ?>" disabled required class="form-control" placeholder="Enter first name"
                                            id="fname" name="fname">
                                    </div>
                                    <div class="col-12 col-md-6 form-group">
                                        <label for="lname">Last Name</label>
                                        <input value="<?php echo $user['last_name']; ?>" disabled required class="form-control" placeholder="Enter last name"
                                            id="lname" name="lname">
                                    </div>
                                    <div class="col-12 col-md-6 form-group">
                                        <label for="phone">Phone number</label>
                                        <input  value="<?php echo $user['phone']; ?>" disabled required class="form-control" placeholder="" id="phone" name="phone">
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


    </div>







</div>
</div>
</div>
<script>
    let glob_user = "admin";
</script>


<?php require_once("../templates/admin_footer.php"); ?>