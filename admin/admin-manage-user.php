<?php
session_start();
include('vendor/inc/config.php');
include('vendor/inc/checklogin.php');
check_login();

// Handle user deletion
if (isset($_GET['del'])) {
    $user_id = intval($_GET['del']);
    
    // Prepare SQL statement to delete user
    $delete_stmt = $mysqli->prepare("DELETE FROM tms_user WHERE u_id = ?");
    $delete_stmt->bind_param('i', $user_id);

    // Execute the statement and check if successful
    if ($delete_stmt->execute()) {
        // Redirect to the same page with a success message
        header("Location: admin-manage-user.php?msg=deleted");
        exit();
    } else {
        echo "Error deleting record: " . $mysqli->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include('vendor/inc/head.php');?>

<body id="page-top">

 <?php include("vendor/inc/nav.php");?>

  <div id="wrapper">

    <!-- Sidebar -->
    <?php include('vendor/inc/sidebar.php');?>

    <div id="content-wrapper">

      <div class="container-fluid">

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">User</a>
          </li>
          <li class="breadcrumb-item active">View Users</li>
        </ol>

        <!-- DataTables Example -->
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas fa-users"></i>
            Registered Users
          </div>
          <div class="card-body">
            <?php
            // Display success or error messages
            if (isset($_GET['msg']) && $_GET['msg'] == 'deleted') {
                echo '<div class="alert alert-success">User deleted successfully!</div>';
            }
            ?>
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Address</th>
                    <th>Email</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                    $ret = "SELECT * FROM tms_user WHERE u_category = 'User' ORDER BY RAND() LIMIT 1000"; // SQL code to get up to 1000 users randomly
                    $stmt = $mysqli->prepare($ret);
                    $stmt->execute();
                    $res = $stmt->get_result();
                    $cnt = 1;

                    while ($row = $res->fetch_object()) {
                ?>
                  <tr>
                    <td><?php echo htmlspecialchars($cnt); ?></td>
                    <td><?php echo htmlspecialchars($row->u_fname . ' ' . $row->u_lname); ?></td>
                    <td><?php echo htmlspecialchars($row->u_phone); ?></td>
                    <td><?php echo htmlspecialchars($row->u_addr); ?></td>
                    <td><?php echo htmlspecialchars($row->u_email); ?></td>
                    <td>
                      <a href="admin-manage-single-usr.php?u_id=<?php echo htmlspecialchars($row->u_id); ?>" class="badge badge-success">
                        <i class="fa fa-user-edit"></i> Update
                      </a>
                      <a href="admin-manage-user.php?del=<?php echo htmlspecialchars($row->u_id); ?>" class="badge badge-danger" onclick="return confirm('Are you sure you want to delete this user?');">
                        <i class="fa fa-trash"></i> Delete
                      </a>
                    </td>
                  </tr>
                <?php
                      $cnt++;
                    }
                ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer small text-muted">
            <?php
              date_default_timezone_set("Africa/Nairobi");
              echo "Generated: " . date("h:i:sa");
            ?>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->

      <!-- Sticky Footer -->
      <?php include("vendor/inc/footer.php");?>
    </div>
    <!-- /.content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-danger" href="admin-logout.php">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Page level plugin JavaScript-->
  <script src="vendor/datatables/jquery.dataTables.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin.min.js"></script>

  <!-- Demo scripts for this page-->
  <script src="js/demo/datatables-demo.js"></script>

</body>

</html>
