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

$cur_page_id = "services";
$page_name = "Services";  

?>

<?php require_once("../templates/admin_header.php"); ?>


<!-- Page content-->
<div class="container-fluid text-center">
 <nav aria-label="breadcrumb">
    <ol class="breadcrumb main_breadcrumb">
      <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Services</li>
    </ol>
</nav>

    <div class="table-responsive">
        <table id="myTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Service name</th>
                    <th>Service Description</th>
                    <!-- <th>Sub Services</th> -->
                    <th></th>
                </tr>
                <tr>
                    <th class="sch"></th>
                    <th class="sch"></th>
                    <!-- <th class="sch"></th> -->
                    <th class="sch"></th>
                </tr>
            </thead>
        </table>
    </div>
</div>





<div class="modal" id="modal_entry" data-backdrop="static" data-keyboard="false">
    <form  onsubmit="update_entry(event)">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="">
                <div class="modal-header">
                    <h4>Update Entry</h4>
                    <div style="text-align:right"> <button type="button" class="btn-close"
                    data-bs-dismiss="modal">&times;</button></div>
                </div>
                <!-- Modal body -->
                <div class="modal-body" style="font-size:14px;">
                    <div class="row main_form">
                        <div class="col-12 col-md-12 form-group mb-3">
                            <input id="entry_id" name="e_id" type="hidden">
                            <input id="parent_id" name="parent_id" type="hidden">
                            <label>Service Name </label>
                            <input id="name" name="name" required class="form-control">
                        </div>
                        <div class="col-12 col-md-12 form-group mb-3">
                            <label>Service Description (optional)</label>
                            <textarea rows="7" id="description" name="description"  class="form-control"></textarea>
                        </div>
                        <div class="col-12 col-md-6 form-group">
                            <label>Other Options</label>
                            <select class="form-control" id="option">
                                <option value="">None</option>
                                <option value="has_subservice">Has Subservice</option>
                                <option value="consultation">Consultation</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6 form-group"></div>
                        <div class="col-12 col-md-6 form-group">
                            <label>Default Price</label>
                            <input type="number" step="0.01" id="price" name="price"  class="form-control" required>
                        </div>
                    </div>

                    <p id="errmsg"></p>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer" id="sbutton">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
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
<script src="../js/service_data.js"></script>