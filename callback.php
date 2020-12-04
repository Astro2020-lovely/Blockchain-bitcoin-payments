<?php


require_once 'blockchain_config.php';
//
$now = date('Y-m-d H:i:s');

/**/
//implementing the callback file
$secret = $app_secret;
if($_GET['secret'] != $secret)
{
	die('APIKEY secret does not match the original on which was created.');
}
else
{

  //update DB
  $order_num = $_GET['invoice'];
  $amount = $_GET['value']; //default value is in satoshis
  /*
  $_GET RESPONSE HAS 
  $_GET['invoice']
  $_GET['secret']
  $_GET['address']
  $_GET['transaction_hash']
  $_GET['confirmations']
  //

  //$amountCalc = $amount / 100000000; //optional satoshi convert to bitcoins
  HERE IS WHERE YOU CAN UPDATE YOUR DATABASE USIND THE $order_num
  */
  //ALSO MARKED THESE AS PAID
  $_SESSION['pay_address_ispaid'] = TRUE;//this can be read from the Database
  //
  $fff = fopen("response_".$order_num.".txt","w");
  $fw = fwrite($fff, json_encode($_GET));
  fclose($fff);  //
  echo "*ok*"; // you must echo *ok* on the page or blockchain will keep sending callback requests every block up to 1,000 times!
  
}
