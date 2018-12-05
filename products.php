<?PHP 
  $authChk = true;
  require('app-lib.php');
  isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  if(!$action) {
    isset($_REQUEST['a'])? $action = $_REQUEST['a'] : $action = "";
  }
  isset($_POST['txtSearch'])? $txtSearch = $_POST['txtSearch'] : $txtSearch = "";
  if(!$txtSearch) {
    isset($_REQUEST['txtSearch'])? $txtSearch = $_REQUEST['txtSearch'] : $txtSearch = "";
  } 
  build_header();
  isset($_POST['a'])? $action = $_POST['a'] : $action = "";
?>
  <?PHP build_navBlock(); ?>
  <div class="container">
  <div id="content">
    <div class="jumbotron">
      <h1 class="display-4">Product List Management</h1>
      <p class="lead">Search and add the products from the stock.</p>
      <hr class="my-4">
      <form name="frmSearchSales" method="post"
          id="frmSearchSales"
          action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
          <div class="form-group" align="left">
            <div align="center" style="width: 100%">
              <input name="txtSearch" class="form-control" id="txtSearch" placeholder="Search Products" value="">
            </div>
          </div>
          <div class="formFooter" align="right" style="width: 100%">
              <button type="submit" class="btn btn-info btn-lg" id="btnSearch">Search</button>
              <button type="button" class="btn btn-primary btn-lg" id="btnAddRec" onclick="loadURL('stockaddedit.php')">Add Stock</button>
              <button align="right" type="button" class="btn btn-outline-success btn-lg" onclick="showCart()">Proceed to Checkout</button>
          </div>
          <input type="hidden" name="a" value="search">
        </form>
    </div>
    </br>
<?PHP
    if($action == "search") {
      isset($_POST['txtSearch'])? $itmSearch = $_POST['txtSearch'] : $itmSearch = "";
      $itemNum = 1;
      openDB();
      $query = "SELECT * FROM lpa_stock " .
        "WHERE lpa_stock_name LIKE '%$itmSearch%' " .
        "AND lpa_stock_status = 'a' " .
        "ORDER BY lpa_stock_name ASC";
      $result = $db->query($query);

      while ($row = $result->fetch_assoc()) {
        if ($row['lpa_image']) {
          $prodImage = $row['lpa_image'];
        } else {
          $prodImage = "question.png";
        }
        $prodID = $row['lpa_stock_ID'];
        ?>
        <div class="productListItem" align="left">
          <div
            class="productListItemImageFrame"
            style="background: url('images/<?PHP echo $prodImage; ?>') no-repeat center center;">
          </div>
          <div class="prodTitle"><?PHP echo $row['lpa_stock_name']; ?></div>
          <div class="prodDesc"><?PHP echo $row['lpa_stock_desc']; ?></div>
          <div class="prodOptionsFrame">
            <div class="prodPriceQty">
              <div class="prodPrice">$<?PHP echo $row['lpa_stock_price']; ?></div>
              <div class="prodQty">QTY:</div>
              <div class="prodQtyFld">
                <input name="fldQTY-<?PHP echo $prodID; ?>" id="fldQTY-<?PHP echo $prodID; ?>" type="number" value="1">
              </div>
            </div>
            <div class="prodAddToCart">
              <button type="button" class="btn btn-warning" onclick="addToCart('<?PHP echo $prodID; ?>')">
                Add
              </button>
            </div>
          </div>
          <div style="clear: left"></div>
        </div>
      <?PHP } ?>
      </div>
    <?PHP } ?>
  </div>
</div>
</br></br></br>
<script>
  var cart = [];
  var msg = "";
  function loadURL(URL) {
    window.location = URL;
  }
  function addToCart(ID) {
    var msg = "";
    var x = document.getElementById("fldQTY-"+ID).value;
    var src = readCookie("prod_id");
    for (var i = x - 1; i >= 0; i--) {
      msg += "@prod"+ ID;
    };
    document.cookie = "prod_id="+src+msg;
    alert("Product added correctly!");
    location.reload();
  }
  function showCart() {
    navMan("checkout.php");
  }
</script>
</div>
<?PHP
  build_footer();
?>