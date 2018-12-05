<?PHP 
  $authChk = true;
  require('app-lib.php'); 
  build_header();
  
  isset($_POST['userid'])? $userid = $_POST['userid'] : $userid = "";
  isset($_POST['totalAmount'])? $totalInvoice = $_POST['totalAmount'] : $totalInvoice = 0;
  isset($_POST['firstname'])? $firstname = $_POST['firstname'] : $firstname = "";
  isset($_POST['lastname'])? $lastname = $_POST['lastname'] : $lastname = "";
  isset($_POST['address'])? $address = $_POST['address'] : $address = "";
  isset($_POST['phone'])? $phone = $_POST['phone'] : $phone = "0";
  isset($_POST['pay_method'])? $pay_method = $_POST['pay_method'] : $pay_method = "";
  isset($_COOKIE['prod_id'])? $itmID = $_COOKIE['prod_id'] : $itmID = "";
  
  $inv_no = gen_ID();
  $splitItmID = explode("@prod", $itmID);
  $itemUnique = array_unique($splitItmID);
  $occurences = array_count_values($splitItmID);
  $queryItems = "";

  openDB();
  foreach ($itemUnique as $key => $value) {
    if ($value != "") {
      $queryItems .= "lpa_stock_ID = '$value' or ";
    }
  }

  $query_invoices = "INSERT INTO lpa_invoices(
  lpa_inv_no,
  lpa_inv_date, 
  lpa_inv_client_ID, 
  lpa_inv_client_name, 
  lpa_inv_client_address, 
  lpa_inv_amount, 
  lpa_inv_status
  ) VALUES (
  '$inv_no',
  '".strtotime(date("D M d, Y G:i"))."',
  '$userid',
  '".$firstname." ".$lastname."',
  '$address',
  '$totalInvoice',
  'P'
  )";
  $result_invoices = $db->query($query_invoices);

  $query = "SELECT * FROM lpa_stock WHERE " .$queryItems. "false=true";
  $result = $db->query($query);
  while ($row = $result->fetch_assoc()) {
    $query_invoice_items = "INSERT INTO lpa_invoice_items (
    lpa_invitem_no,
    lpa_invitem_inv_no,
    lpa_invitem_stock_ID,
    lpa_invitem_stock_name,
    lpa_invitem_qty,
    lpa_invitem_stock_price,
    lpa_invitem_stock_amount,
    lpa_inv_status
    ) VALUES (
    '".gen_ID()."',
    '$inv_no',
    '".$row['lpa_stock_ID']."',
    '".$row['lpa_stock_name']."',
    '".$occurences[$row['lpa_stock_ID']]."',
    '".$row['lpa_stock_price']."',
    '".$row['lpa_stock_price']*$occurences[$row['lpa_stock_ID']]."',
    'P'
    )";
    $result_invoice_items = $db->query($query_invoice_items);
  }

?>
<?PHP build_navBlock(); ?>
  <div class="container">
	  <div id="content" align="center">
      <h2>
      <strong> Welcome to Logic Peripherals Australia (LPA) </strong>
      </h2>
	  </div>

    <div id="content" <?PHP if(isset($_POST['userid'])){ echo 'style="display:block"';}else{ echo 'style="display:none"';}?> align="left">
      <div class="alert alert-success" role="alert">
        <h3>Payment procedure</h3>
        <h4>The process have been successful for <?php echo $firstname." ".$lastname; ?></h4>
        <hr>
    </div>
  </div>
<script>
    var msg = "";
    <?PHP if(isset($_POST['userid'])){ echo 'var payment = true;';}else{ echo 'var payment = false;';}?>
    document.cookie = "prod_id="+msg;
    if (payment) {
      setTimeout(function() {
        navMan("index.php");
    }, 1500);
    };
</script>
<?PHP
build_footer();
?>