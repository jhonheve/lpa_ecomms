<?PHP 
  $authChk = true;
  require('app-lib.php'); 
  build_header();
  $total = 0;
  isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  $userID = $_SESSION['authUser'];
  $queryClient ="SELECT lpa_client_firstname, lpa_client_lastname, lpa_client_address, lpa_client_phone 
  FROM lpa_clients WHERE lpa_client_ID = '$userID' LIMIT 1";
  openDB();
  $result = $db->query($queryClient);
  $row = $result->fetch_assoc();
  isset($row['lpa_client_firstname'])? $firstname = $row['lpa_client_firstname'] : $firstname = "";
  isset($row['lpa_client_lastname'])? $lastname = $row['lpa_client_lastname'] : $lastname = "";
  isset($row['lpa_client_address'])? $address = $row['lpa_client_address'] : $address = "";
  isset($row['lpa_client_phone'])? $phone = $row['lpa_client_phone'] : $phone = "0";
?>
  <?PHP build_navBlock(); ?>
<div class="container">
  <div id="content" align="right">
    <div class="jumbotron text-center">
      <h3 class="display-4">Payment details</h3>
      <hr class="my-4">
      <form id="cart_checkout" name="cart_checkout" action="index.php" method="post">
        <div class="container">
          <div class="row" style="width: 90%;">
            <div class="form-group col-md-4" align="left">
              <input type="checkbox" style="display:none;" id="userid" name="userid" value="<?php echo $userID; ?>" checked> 
              <input type="checkbox" style="display:none;" id="totalAmount" name="totalAmount" value="<?php echo $total; ?>" checked>  
              <label for="firstname">Firstname</label>
              <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $firstname; ?>">  
              <label for="lastname">Lastname</label>
              <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $lastname; ?>">                               
            </div>
            <div class="col-md-4" align="left">
              <label for="address">Address</label>
              <input type="text" class="form-control" id="address" name="address" value="<?php echo $address; ?>">
              <label for="phone">Phone</label>
              <input type="number" class="form-control" id="phone" name="phone" value="<?php echo $phone; ?>">
            </div>
            <div class="col-md-4" align="left">
              <label for="phone">Select a payment method:</label>
              <div style="font-size:27px">
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pay_method" id="pay_method" value="mastercard">
                  <label class="form-check-label" for="exampleRadios1">
                    <i class="fab fa-cc-mastercard"></i>
                  </label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pay_method" id="pay_method" value="visa">
                  <label class="form-check-label" for="exampleRadios2">
                    <i class="fab fa-cc-visa"></i>
                  </label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pay_method" id="pay_method" value="paypal">
                  <label class="form-check-label" for="exampleRadios3">
                    <i class="fab fa-cc-paypal"></i>
                  </label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="pay_method" id="pay_method" value="direct">
                  <label class="form-check-label" for="exampleRadios3">
                    <i class="fa fa-credit-card"></i>
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr class="my-4">
        <div align="right">
          <button type="button" class="btn btn-outline-danger" onclick="cancelPayment()">Cancel the order</button>
          <button type="submit" class="btn btn-success">Proceed to Pay</button>
        </div>
      </form>
    </div>

  </div>
</div>
</br></br></br>
<script>
    var cart = [];
    var msg = "";
    function addToCart(ID) {
      cart.push(ID);
      console.log(cart);
    }
    function showCart() {
      for (var i = cart.length - 1; i >= 0; i--) {
        msg += "%$#"+ cart[i];
      };
      navMan("products.php?sid="+msg);
    }
    function cancelPayment() {
      navMan("checkout.php");
    }
    function updateItem(ID) {
      var num = document.getElementById("fldQTY-"+ID).value;
      var src = readCookie("sid");
      var items = src.split('%$');
      var msg = "";
      for (var i = 0; i < items.length ; i++) {
        if (items[i] != ID && items[i] != "") {
          msg += "%$"+ items[i];
        };
      };
      for (var i = num - 1; i >= 0; i--) {
        msg += "%$"+ ID;
      };
      document.cookie = "sid="+msg;
      location.reload();
    }
    function makePayment(){
      navMan("index.php");
    }
</script>

<?PHP
  build_footer();
?>