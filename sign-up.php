<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ./account');
}

require './connect/config.php';


$cur_page_id = "signup";
$page_name = "Sign Up - " . glob_site_name;


?>
<?php require_once ("templates/header.php"); ?>



<header class="main_header">
    <h1> Sign Up </h1>
</header>

<!-- Page content-->
<main>
    <section>

        <div class="container">

            <form onsubmit="sign_up_form(event)" id="sign_form" class="main_form" action="" method="POST">
                <input type="hidden" value="signup" name="ch">
                <div class="row py-3">

                    <div class="col-12 col-md-6 form-group">
                        <label for="first_name">First Name</label>
                        <input value="" required class="form-control has_pattern" placeholder="Enter first name"
                            id="first_name" name="first_name" pattern="^[a-zA-Z]{3,}$"
                            data-error_message="First name must be at least 3 letters long and contain letters only.">
                    </div>
                    <div class="col-12 col-md-6 form-group">
                        <label for="last_name">Last Name</label>
                        <input value="" required class="form-control has_pattern" placeholder="Enter last name"
                            id="last_name" name="last_name" pattern="^[a-zA-Z]{3,}$"
                            data-error_message="Last name must be at least 3 letters long and contain letters only.">
                    </div>
                    <div class="col-12 col-md-6 form-group">
                        <label for="email">Email Address</label>
                        <input required class="form-control has_pattern" type="email" id="email" name="email"
                            pattern="^[a-zA-Z0-9._%+-]+@(gmail\.com|outlook\.sa|outlook\.com|hotmail\.com)$"
                            data-error_message="Email must end with @gmail.com, @outlook.sa, @outlook.com, or @hotmail.com.">
                    </div>
                    <div class="col-12 col-md-6 form-group">
                        <label for="phone">Phone number</label>
                        <input required class="form-control has_pattern" placeholder="" id="phone" name="phone"
                            pattern="^05\d{8}$"
                            data-error_message="Mobile number must be 10 digits long, contain only numbers, and begin with 05.">
                    </div>

                    <div class="col-12 col-md-6 form-group">
                        <label for="password">Password</label>
                        <div class="input-group">
                            <input required class="form-control has_pattern" type="password"
                                placeholder="Enter Password" id="password" name="password" required,
                                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                                data-error_message="Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character.">
                            <div class="input-group-append"><button type="button" onclick="pwd_vtoggle(event)" class="xbtn input-group-text"><i class="fa fa-eye"></i></button></div>
                        </div>
                    </div>
                    <div class=" col-12 col-md-6 form-group">
                        <label for="password2">Repeat Password</label>
                        <div class="input-group">
                        <input required class="form-control has_pattern" type="password" placeholder="Repeat Password"
                            id="password2" name="password2" required,
                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
                            data-error_message="Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character.">
                            <div class="input-group-append"><button type="button" onclick="pwd_vtoggle(event)" class="xbtn input-group-text"><i class="fa fa-eye"></i></button></div>
                        </div>
                        </div>

                    <div class="form-group text-right py-3 sbutton" id="sbutton">
                        <button type="submit" class="btn btn-block btn-lg btn-primary px-5"> Register </button>
                    </div>
                </div>

            </form>


        </div>
    </section>


</main>

<?php require_once ("templates/footer.php"); ?>