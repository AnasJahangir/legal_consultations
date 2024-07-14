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

$cur_page_id = "user";
$page_name = "Users";

?>

<?php require_once("../templates/admin_header.php"); ?>

<link href="../css/select2.min.css" rel="stylesheet" />

<!-- Page content-->
<div class="container-fluid text-center">
<style>
    .select2.select2-container{
        width: 100% !important;
    }
    .select2-dropdown{
        z-index: 30000 !important;

    }
</style>
 <nav aria-label="breadcrumb">
    <ol class="breadcrumb main_breadcrumb">
      <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Users</li>
    </ol>
</nav>


    <div class="table-responsive">
        <table id="myTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th></th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Date Registered</th>
                </tr>
                <tr>
                    <th></th>
                    <th class="sch"></th>
                    <th class="sch"></th>
                    <th class="sch"></th>
                    <th class="sch"></th>
                    <th class="sch"></th>
                </tr>
            </thead>
        </table>
    </div>
</div>



<div class="modal modal-extra" id="modal_pentry">
    <form action="" id="update_profile" onsubmit="update_pentry(event)">
        <div class="modal-dialog" style="max-width: 600px;">
            <div class="modal-content bg-info">
                <div class="modal-header">
                    <h4 style="font-weight:normal;"></h4>
                    <div style="text-align:right"> <button data-bs-dismiss="modal" type="button"
                            class="btn-close">&times;</button></div>
                </div>
                <!-- Modal body -->
                <div class="modal-body" style="font-size:14px;">
                    <div class="row">
                        <div class="col-12 form-group">
                            <label>New Password </label>
                            <input id="password2" name="password2" required class="form-control">
                        </div>
                    </div>
                    <p id="errmsg3"></p>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer" id="sbutton3" style="justify-content:space-between">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary" style="font-weight:bold" type="submit"> Update</button>
                </div>

            </div>
        </div>
    </form>
</div>




<!--  Modal  ALert -->
<div class="modal" id="modal_delete">
    <div class="modal-dialog">
        <div class="modal-content alert alert-warning  bg-warning">
            <div style="text-align:right"> <button type="button" class="btn-close"
                    data-bs-dismiss="modal">&times;</button></div>
            <!-- Modal body -->
            <div class="modal-body" style="font-size:18px; color:#000">
            </div>
            <!-- Modal footer -->
            <div style="border-top:1px solid #777; padding:20px 10px;">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn  pull-right delete_action_btn" data-bs-dismiss="modal">Delete</button>
            </div>

        </div>
    </div>
</div>



<script>
    let glob_user = "admin";
</script>


<?php require_once("../templates/admin_footer.php"); ?>
<script src="../js/select2.min.js"></script>
<script src="../js/user_data.js"></script>