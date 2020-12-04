<?php 

require_once 'blockchain_config.php';

$satoshi_amount = ($amount / 100000000);



//USING THE FUNCTION IN THE CONFIG FILE

$createInvoice = (object)create_payment_invoice($app_receive_url);//return object
//$createInvoice = create_payment_invoice($app_receive_url); //Returns Array
if($createInvoice->status){//status == TRUE
  //the newly created address will be stored under 'address' in the JSON response
  $payTo = $createInvoice->response;//respone is a bitcoin address
}else{//status == FALSE
  echo $createInvoice->response;//response is just an error message from blockchain
  //here you can redirect / you can print error on screen / you can send email to dev team or web master
  //die();//this die() must be remove in real life
  header('Location: error_page.php?error=');
}

//END USING FUNCTION

?>

<?php require_once 'header_include.php'; ?>


<script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
<!-- TOASTR ALERT OPTIONAL -->
<link href="assets/toastr/toastr.css" rel="stylesheet">
<script src="assets/toastr/toastr.js"></script>

<script>
var btcs = new WebSocket('<?= $app_webSocketURL ?>');


btcs.onopen = function(){
  btcs.send( JSON.stringify( {"op":"addr_sub", "addr":"<?= $payTo ?>"} ) );
};

btcs.onmessage = function(onmsg){
  var response = JSON.parse(onmsg.data);
  var getOuts = response.x.out;
  var countOuts = getOuts.length; 
  for(i = 0; i < countOuts; i++)
  {
      //check every output to see if it matches specified address
      var outAdd = response.x.out[i].addr;
      var specAdd = "<?= $payTo ?>";
      var expectedAmnt = "<?= $satoshi_amount ?>";
      if (outAdd == specAdd )
      {
        //var expectedAmnt = expectedval / 100000000;
        var amount = response.x.out[i].value;
        var calAmount = amount / 100000000;
        //check if amount sent is equal to the expected
        if(calAmount < expectedAmnt){
          toastr.error("Received "+calAmount+" BTC amount was lower than the expected ");
        }
        //
        $('#messages').removeClass('text-warning').addClass('text-success').html("<span class='text text-success'><i class='fa fa-thumbs-o-up'></i> Your payment of " + calAmount + " BTC has been Received </span>");
        //uses toastr/toastr.js and toastr/toastr.css
        toastr.success("Received " + calAmount + " BTC");
        //

        var beep_notify_sound = <?= $beed_notify ?>;
        if(beep_notify_sound == true){
         var snd = new  Audio("<?= $beed_sound_code ?>");  
         snd.play();
        }
      } 
  }
}







</script>


<div class="jumbotron">
  <h1 class="text text-warning">EXTRA EXAMPLE</h1>
  <p class="lead">This example uses a function found in the config file which simplifies the CURL request and only returns status and reponse <b>create_payment_invoice</b></p>
</div>




<link href="assets/example_1.css" rel="stylesheet">

<div class="col-md-12">
    <div class="offer offer-warning" id="Example2PaymentBox_<?= $orderID ?>">
      <div class="shape">
        <div class="shape-text">
          BTC               
        </div>
      </div>
      <div class="offer-content">
        <h3 class="lead">
          Send <b><?= $amount ?> BTC</b>
        </h3>
        <p>

        <div class="col-sm-4 col-md-4 col-lg-4">
          <img src="https://chart.googleapis.com/chart?chs=125x125&cht=qr&chl=bitcoin:<?= $payTo ?>?amount=<?= $amount ?>&choe=UTF-8"  class="img-responsive">
        </div>

        <div class="col-sm-8 col-md-8 col-lg-8" style="padding:10px;">
          Send <b><?= $amount ?> BTC</b><br/><input type="text" id="address_<?= $orderID ?>" class="form-control" value="<?= $payTo ?>" onclick="this.select();" readonly>
          Simply scan QR Code with your mobile device or copy one in the input box<br/><br/>
          <small class="text text-warning" id="messages">
          No need to refresh page, your payment status will be updated automatically.<br/>
          <span class="text text-info"><i class="fa fa-spin fa-circle-o-notch"></i> Awaiting <?= $amount ?> bitcoin payment ....</span>
          </small>
        </div>
        </p>
      </div>
    </div>
  </div>




<div id="messages"></div>
      


<?php require_once 'footer_include.php'; ?>
