<?PHP 
  $authChk = true;
  require('app-lib.php'); 
  build_header();
  isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  $total = 0;
?>
  <?PHP build_navBlock(); ?>
  <div class="container">
  <div id="content" align="right">
    <div class="card text-center">
      <div class="card-header">
        <h1 align="left">Products checkout</h1>
      </div>
      <div class="card-body">
        <table class="table table-hover table-dark">
          <thead>
            <tr>
              <th scope="col">Name</th>
              <th scope="col">Description</th>
              <th scope="col">Price</th>
              <th scope="col">Quantity</th>
              <th scope="col">Update</th>
              <th scope="col">Delete</th>
            </tr>
          </thead>
          <tbody>
        <?PHP
          isset($_COOKIE['prod_id'])? $itmID = $_COOKIE['prod_id'] : $itmID = "";
          $splitItmID = explode("@prod", $itmID);
          $occurences = array_count_values($splitItmID);
          openDB();
          $queryItems = "";
          foreach ($splitItmID as $key => $value) {
            $queryItems .= "lpa_stock_ID = '$value' or ";
          }
          $query = "SELECT * FROM lpa_stock WHERE " .$queryItems. "false=true";
          $result = $db->query($query);
          while ($row = $result->fetch_assoc()) {
            if ($row['lpa_image']) {
              $prodImage = $row['lpa_image'];
            } else {
              $prodImage = "question.png";
            }
            $prodID = $row['lpa_stock_ID'];
            $total += $row['lpa_stock_price']*$occurences[$prodID];
            ?>

            <tr>
              <th scope="row"><?PHP echo $row['lpa_stock_name']; ?></th>
              <td><?PHP echo $row['lpa_stock_desc']; ?></td>
              <td>$<?PHP echo $row['lpa_stock_price']*$occurences[$prodID]; ?></td>
              <td><input name="fldQTY-<?PHP echo $prodID; ?>" id="fldQTY-<?PHP echo $prodID; ?>" type="number" value="<?PHP echo $occurences[$prodID]; ?>"></td>
              <td><button type="button" class="btn btn-info" onclick="updateItem('<?PHP echo $prodID; ?>')">Update</button></td>
              <td><button type="button" class="btn btn-danger" onclick="deleteItem('<?PHP echo $prodID; ?>')">Delete</button></td>
            </tr>
        <?PHP } ?>
          </tbody>
        </table>
      </div>
      <div class="card-footer text-muted"  align="right" >
        <h2>Total: $<?php echo $total; ?> </h2>
        <button align="right" type="button" class="btn btn-success" onclick="makePayment()">Confirm list</button>
      </div>
    </div>
  </div>
</div> 
  </br></br>
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
      navMan("products.php?prod_id="+msg);
    }
    function deleteItem(ID) {
      var src = readCookie("prod_id");
      var items = src.split('@prod');
      var msg = "";
      for (var i = 0; i < items.length ; i++) {
        if (items[i] != ID && items[i] != "") {
          msg += "@prod"+ items[i];
        };
      };
      document.cookie = "prod_id="+msg;
      location.reload();
    }
    function updateItem(ID) {
      var num = document.getElementById("fldQTY-"+ID).value;
      var src = readCookie("prod_id");
      var items = src.split('@prod');
      var msg = "";
      for (var i = 0; i < items.length ; i++) {
        if (items[i] != ID && items[i] != "") {
          msg += "@prod"+ items[i];
        };
      };
      for (var i = num - 1; i >= 0; i--) {
        msg += "@prod"+ ID;
      };
      document.cookie = "prod_id="+msg;
      location.reload();
    }
    function makePayment(){
      navMan("confirm.php");
    }
  </script>
</div>
<?PHP
  build_footer();
?>