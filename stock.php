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
  build_header($displayName);
?>
  <?PHP build_navBlock(); ?>
  <div class="container">
  <div id="content">
    <!-- Search Section Start -->
    <div class="jumbotron">
      <h1 class="display-4">Stock Management Search</h1>
      <p class="lead">Search and edit the content or information from the stock products.</p>
      <hr class="my-4">
      <form name="frmSearchStock" method="post"
            id="frmSearchStock"
            action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
          <div align="center" style="width: 100%">
            <input name="txtSearch" class="form-control" id="txtSearch" placeholder="Search Stock" value="<?PHP echo $txtSearch; ?>">
          </div>
        </div>
        <div class="formFooter" align="right" style="width: 100%">
            <button type="button" class="btn btn-info btn-lg" id="btnSearch">Search</button>
            <button type="button" class="btn btn-primary btn-lg" id="btnAddRec">Add</button>
        </div>
        <input type="hidden" name="a" value="listStock">
      </form>
    </div>
    <!-- Search Section End -->
    <!-- Search Section List Start -->
    <?PHP
      if($action == "listStock") {
    ?>
    </br>
    <div align="center">
      <table class="table table-hover" style="width: calc(100% - 15px);">
        <tr style="background: #ddd">
          <td style="width: 120px;"><b>Stock Code</b></td>
          <td style="border-left: #cccccc solid 1px"><b>Stock Name</b></td>
          <td style="width: 80px;"><b>Status</b></td>
          <td style="width: 80px;text-align: right"><b>Price</b></td>
        </tr>
    <?PHP
      openDB();
      $query =
        "SELECT
            *
         FROM
            lpa_stock
         WHERE
            lpa_stock_ID LIKE '%$txtSearch%' AND lpa_stock_status <> 'D'
         OR
            lpa_stock_name LIKE '%$txtSearch%' AND lpa_stock_status <> 'D'

         ";
      $result = $db->query($query);
      $row_cnt = $result->num_rows;
      if($row_cnt >= 1) {
        while ($row = $result->fetch_assoc()) {
          $sid = $row['lpa_stock_ID'];
          ?>
          <tr class="hl" style="border-bottom: #cccccc solid 1px">
            <td onclick="loadStockItem(<?PHP echo $sid; ?>,'Edit')"
                style="cursor: pointer;">
              <?PHP echo $sid; ?>
            </td>
            <td onclick="loadStockItem(<?PHP echo $sid; ?>,'Edit')"
                style="cursor: pointer;border-left: #cccccc solid 1px">
                <?PHP echo $row['lpa_stock_name']; ?>
            </td>
            <td <?php if($row['lpa_stock_status'] == 'i'){ echo 'class="inactiveItem"';} else { echo 'class="activeItem"';} ?>>
              <strong><?php if($row['lpa_stock_status'] == 'a'){ echo 'Active';} else {echo 'Inactive';} ?></strong>
            </td>            
            <td style="text-align: right">
              <?PHP echo $row['lpa_stock_price']; ?>
            </td>
          </tr>
        <?PHP }
      } else { ?>
        <tr>
          <td colspan="3" style="text-align: center">
            No Records Found for: <b><?PHP echo $txtSearch; ?></b>
          </td>
        </tr>
      <?PHP } ?>
      </table>
    </div>
    <?PHP } ?>
    <!-- Search Section List End -->
  </div>
  </br></br>
  <script>
    var action = "<?PHP echo $action; ?>";
    var search = "<?PHP echo $txtSearch; ?>";
    if(action == "recUpdate") {
      alert("Record Updated!");
      navMan("stock.php?a=listStock&txtSearch=" + search);
    }
    if(action == "recInsert") {
      alert("Record Added!");
      navMan("stock.php?a=listStock&txtSearch=" + search);
    }
    if(action == "recDel") {
      alert("Record Deleted!");
      navMan("stock.php?a=listStock&txtSearch=" + search);
    }
    function loadStockItem(ID,MODE) {
      window.location = "stockaddedit.php?sid=" +
      ID + "&a=" + MODE + "&txtSearch=" + search;
    }
    $("#btnSearch").click(function() {
      $("#frmSearchStock").submit();
    });
    $("#btnAddRec").click(function() {
      loadStockItem("","Add");
    });
    setTimeout(function(){
      $("#txtSearch").select().focus();
    },1);
  </script>
  </div>
<?PHP
build_footer();
?>