<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
require "../connect/config.php";

$admin_id = intval($_SESSION['admin_id']);

$pdo = new mypdo();

$user = $pdo->get_one("SELECT * FROM admins WHERE admin_id = ?", $admin_id);

$cur_page_id = "reservation";
$page_name = "Reservations";

?>

<?php require_once ("../templates/admin_header.php"); ?>

<link
    href="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/datatables.min.css"
    rel="stylesheet">

<style>
    .editor_btn {
        white-space: nowrap;
    }

    .service_desc {
        max-width: 150px;
    }

    #myTable td,
    #myTable th {
        font-size: 14px !important;
    }
</style>
<!-- Page content-->
<div class="container-fluid text-center">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb main_breadcrumb">
            <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Reservations</li>
        </ol>
    </nav>


    <div class="table-responsive">
        <table id="myTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                <th>Date</th>
                    <th>First Name</th>
                    <th>SLast Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Service</th>
                    <th>Sub Service</th>
                    <th>Appointment Date</th>
                    <th>Service Fee / Payment</th>
                    <th>Service Description</th>
                    <th>Modified at</th>
                </tr>
                <tr>
                    <th class="sch"></th>
                    <th class="sch"></th>
                    <th class="sch"></th>
                    <th class="sch"></th>
                    <th class="sch"></th>
                    <th class="sch"></th>
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


<div class="modal" id="modal_changes">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Modifications for Appointment</h4>
                <div style="text-align:right"> <button type="button" class="btn-close"
                        data-bs-dismiss="modal">&times;</button></div>
            </div>
            <!-- Modal body -->
            <div class="modal-body" style="font-size:18px; color:#000">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Label</th>
                        <th>Changes</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
            </div>
            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
            </div>

        </div>
    </div>
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




<?php require_once ("../templates/user_footer.php"); ?>
<!-- Datatable files -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script
    src="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-1.13.8/b-2.4.2/b-colvis-2.4.2/b-html5-2.4.2/b-print-2.4.2/datatables.min.js"></script>

<script src="../js/reservations.js"></script>