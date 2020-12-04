<?php 



?>

<?php require_once 'header_include.php'; ?>


<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<!-- TOASTR ALERT OPTIONAL -->
<link href="assets/toastr/toastr.css" rel="stylesheet">


<div class="jumbotron bg-danger">
  <h1 class="text text-warning">Bitcoin Processing</h1>
  <p class="lead"><?= isset($_GET['msg']) && !empty($_GET['msg']) ? $_GET['msg'] : 'The was an error creating your payment invoice address, Please try again or contact support' ?></p>
</div>




      


<?php require_once 'footer_include.php'; ?>
