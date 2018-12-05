<!--
-->
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
      <h1 class="display-4">Sales Management Search</h1>
      <p class="lead">Search and edit the content or information from sales.</p>
      <hr class="my-4">
      <form name="frmSearchSales" method="post"
          id="frmSearchSales"
          action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
          <div class="form-group">
            <div align="center" style="width: 100%">
              <input name="txtSearch" class="form-control" id="txtSearch" placeholder="Search Sales" value="<?PHP echo $txtSearch; ?>">
            </div>
          </div>
          <div class="formFooter" align="right" style="width: 100%">
              <button type="button" class="btn btn-info btn-lg" id="btnSearch">Search</button>
              <button type="button" class="btn btn-primary btn-lg" id="btnAddRec">Add</button>
          </div>
          <input type="hidden" name="a" value="listSales">
        </form>
    </div>
    <!-- Search Section End -->
    <!-- Search Section List Start -->
    <?PHP
      if($action == "listSales") {
    ?>
    </br>
    <div align="center">
      <table class="table table-hover" style="width: calc(100% - 15px);">
        <tr style="background: #ddd">
          <td style="width: 120px;"><b>Client ID</b></td>
          <td style="border-left: #cccccc solid 1px"><b>Client ID Name</b></td>
          <td><b>Client Name</b></td>
          <td><b>Date</b></td>
          <td style="width: 80px;"><b>Status</b></td>
          <td style="width: 80px;text-align: right"><b>Amount</b></td>
        </tr>
    <?PHP
      openDB();
      $totalAmount = 0;
      $query =
        "SELECT
            *
         FROM
            lpa_invoices
         WHERE
            lpa_inv_no LIKE '%$txtSearch%' AND 	lpa_inv_status <> 'D'
         OR
            lpa_inv_client_ID LIKE '%$txtSearch%' AND 	lpa_inv_status <> 'D'

         ";
      $result = $db->query($query);
      $row_cnt = $result->num_rows;
      if($row_cnt >= 1) {
        while ($row = $result->fetch_assoc()) {
          $sid = $row['lpa_inv_no'];
          ?>
          <tr class="hl" style="border-bottom: #cccccc solid 1px">
            <td onclick="loadSalesItem(<?PHP echo $sid; ?>,'Edit')"
                style="cursor: pointer;">
              <?PHP echo $sid; ?>
            </td>
            <td onclick="loadSalesItem(<?PHP echo $sid; ?>,'Edit')"
                style="cursor: pointer;border-left: #cccccc solid 1px">
                <?PHP echo $row['lpa_inv_client_ID']; ?>
            </td>
            <td onclick="loadSalesItem(<?PHP echo $sid; ?>,'Edit')"
                style="cursor: pointer;">
                <?PHP echo $row['lpa_inv_client_name']; ?>
            </td>
            <td>
                <?PHP echo $row['lpa_inv_date']; ?>
            </td>            
            <td <?php if($row['lpa_inv_status'] == 'U'){ echo 'class="inactiveItem"';} else { echo 'class="activeItem"';} ?>>
              <strong><?php if($row['lpa_inv_status'] == 'P'){ echo 'Active';} else {echo 'Inactive';} ?></strong>              
            </td>            
            <td style="text-align: right">
              <?PHP
              $totalAmount += $row['lpa_inv_amount']; 
              echo $row['lpa_inv_amount']; ?>
            </td>           
          </tr>
        <?PHP } ?>
          <tr class="hl" style="border-top: 2px solid grey;">
            <td colspan="5" style="text-align: right; padding-right: 30px;font-weight: bolder;">
                Total
            </td>            
            <td style="text-align: right">
            	<?php echo $totalAmount; ?>
            </td>            
          </tr>        
    <?php  } else { ?>
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
</div>
  </br></br>
  <script>
    var action = "<?PHP echo $action; ?>";
    var search = "<?PHP echo $txtSearch; ?>";
    if(action == "recUpdate") {
      alert("Record Updated!");
      navMan("sales.php?a=listSales&txtSearch=" + search);
    }
    if(action == "recInsert") {
      alert("Record Added!");
      navMan("sales.php?a=listSales&txtSearch=" + search);
    }
    if(action == "recDel") {
      alert("Record Deleted!");
      navMan("sales.php?a=listSales&txtSearch=" + search);
    }
    function loadSalesItem(ID,MODE) {
      window.location = "salesaddedit.php?sid=" +
      ID + "&a=" + MODE + "&txtSearch=" + search;
    }
    $("#btnSearch").click(function() {
      $("#frmSearchSales").submit();
    });
    $("#btnAddRec").click(function() {
      loadSalesItem("","Add");
    });
    setTimeout(function(){
      $("#txtSearch").select().focus();
    },1);
  </script>
  </div>
<?PHP
build_footer();
?>