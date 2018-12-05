<?PHP
  $authChk = true;
  require('app-lib.php');
  isset($_REQUEST['sid'])? $sid = $_REQUEST['sid'] : $sid = "";
  if(!$sid) {
    isset($_POST['sid'])? $sid = $_POST['sid'] : $sid = "";
  }
  isset($_REQUEST['a'])? $action = $_REQUEST['a'] : $action = "";
  if(!$action) {
    isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  }
  isset($_POST['txtSearch'])? $txtSearch = $_POST['txtSearch'] : $txtSearch = "";
  if(!$txtSearch) {
    isset($_REQUEST['txtSearch'])? $txtSearch = $_REQUEST['txtSearch'] : $txtSearch = "";
  }
  if($action == "delRec") {
    $query =
      "UPDATE lpa_invoices SET
         lpa_inv_status = 'D'
       WHERE
         lpa_inv_no = '$sid' LIMIT 1
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
      printf("Errormessage: %s\n", $db->error);
      exit;
    } else {
      header("Location: Sales.php?a=recDel&txtSearch=$txtSearch");
      exit;
    }
  }

  isset($_POST['txtSalesID'])? $SalesID = $_POST['txtSalesID'] : $SalesID = gen_ID();
  isset($_POST['txtSalesDate'])? $SalesDate = $_POST['txtSalesDate'] : $SalesDate = date("Y-m-d");
  isset($_POST['txtSalesClientID'])? $SalesClientID = $_POST['txtSalesClientID'] : $SalesClientID = "";
  isset($_POST['txtSalesClientName'])? $SalesClientName = $_POST['txtSalesClientName'] : $SalesClientName = "0";
  isset($_POST['txtSalesAddress'])? $SalesClientAddress = $_POST['txtSalesAddress'] : $SalesClientAddress = "";
  isset($_POST['txtSalesAmount'])? $SalesAmount = $_POST['txtSalesAmount'] : $SalesAmount = "0.00";
  isset($_POST['txtStatus'])? $SalesStatus = $_POST['txtStatus'] : $SalesStatus = "";
  $mode = "insertRec";
  if($action == "updateRec") {
    $query =
      "UPDATE lpa_invoices SET
         lpa_inv_no = '$SalesID',
         lpa_inv_date = '$SalesDate',
         lpa_inv_client_ID = '$SalesClientID',
         lpa_inv_client_name = '$SalesClientName',
         lpa_inv_client_address = '$SalesClientAddress',
         lpa_inv_amount = '$SalesAmount',
         lpa_inv_status = '$SalesStatus'
       WHERE
         lpa_inv_no = '$sid' LIMIT 1
      ";
     openDB();
     $result = $db->query($query);
     if($db->error) {
       printf("Errormessage: %s\n", $db->error);
       exit;
     } else {
         header("Location: Sales.php?a=recUpdate&txtSearch=$txtSearch");
       exit;
     }
  }
  if($action == "insertRec") {
    $query =
      "INSERT INTO lpa_invoices (
         lpa_inv_no,
         lpa_inv_date,
         lpa_inv_client_ID,
         lpa_inv_client_name,
         lpa_inv_client_address,
         lpa_inv_amount,
         lpa_inv_status
       ) VALUES (
         '$SalesID',
         '$SalesDate',
         '$SalesClientID',
         '$SalesClientName',
         '$SalesClientAddress',
         '$SalesAmount',
         '$SalesStatus'
       )
      ";
    openDB();
    $result = $db->query($query);
    if($db->error) {
      printf("Errormessage: %s\n", $db->error);
      exit;
    } else {
      header("Location: Sales.php?a=recInsert&txtSearch=".$SalesID);
      exit;
    }
  }

  if($action == "Edit") {
    $query = "SELECT * FROM lpa_invoices WHERE lpa_inv_no = '$sid' LIMIT 1";
    $result = $db->query($query);
    $row_cnt = $result->num_rows;
    $row = $result->fetch_assoc();
    $SalesID     = $row['lpa_inv_no'];
    $SalesDate   = date_format(date_create($row['lpa_inv_date']), "Y-m-d");
    $SalesClientID   = $row['lpa_inv_client_ID'];
    $SalesClientName = $row['lpa_inv_client_name'];
    $SalesClientAddress  = $row['lpa_inv_client_address'];
    $SalesAmount  = $row['lpa_inv_amount'];
    $SalesStatus = $row['lpa_inv_status'];
    $mode = "updateRec";
  }
  build_header($displayName);
  build_navBlock();
  echo '<div class="container">';
  $fieldSpacer = "5px";
?>

  <div id="content">
    <div class="card text-center">
      <div class="card-header">
        Sales storage Management (<?PHP echo $action; ?>)
      </div>
      <div class="card-body" align="left">
        <form name="frmSalesRec" id="frmSalesRec" method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
          <div>
            <label>ID</label>
            <input name="txtSalesID" id="txtSalesID" class="form-control" placeholder="Sales ID" value="<?PHP echo $SalesID; ?>" title="Sales ID">
          </div>
          <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
            <label>Date</label>
            <input name="txtSalesDate" id="txtSalesDate" class="form-control" placeholder="Date" value="<?PHP echo $SalesDate; ?>" type="date" title="Sales Name">
          </div>
          <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
            <label>Client id</label>
            <input name="txtSalesClientID" id="txtSalesClientID" class="form-control" placeholder="Client ID" value="<?PHP echo $SalesClientID; ?>" title="Client ID">
          </div>
          <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
            <label>Client name</label>
            <input name="txtSalesClientName" id="txtSalesClientName" class="form-control" placeholder="Client name" value="<?PHP echo $SalesClientName; ?>" title="Client name">
          </div>
          <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
            <label>Address</label>
            <input name="txtSalesAddress" id="txtSalesAddress" class="form-control" placeholder="Address" value="<?PHP echo $SalesClientAddress; ?>" title="Address">
          </div>
          <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
            <label>Amount</label>
            <input name="txtSalesAmount" id="txtSalesAmount" class="form-control" placeholder="Sales Amount" value="<?PHP echo $SalesAmount; ?>" title="Sales Amount">
          </div>
          <div style="margin-top: <?PHP echo $fieldSpacer; ?>">
            <div>Sales Status</div>
            <input name="txtStatus" id="txtSalesStatusActive" type="radio" value="P">
              <label for="txtSalesStatusActive">Active</label>
            <input name="txtStatus" id="txtSalesStatusInactive" type="radio" value="U">
              <label for="txtSalesStatusInactive">Inactive</label>
          </div>
          <input name="a" id="a" value="<?PHP echo $mode; ?>" type="hidden">
          <input name="sid" id="sid" value="<?PHP echo $sid; ?>" type="hidden">
          <input name="txtSearch" id="txtSearch" value="<?PHP echo $txtSearch; ?>" type="hidden">
        </form>
      </div>
      <div class="card-footer text-muted" align="right">
          <button type="button" class="btn btn-success" id="btnSalesSave">Save</button>
          <button type="button" class="btn btn-danger" onclick="navMan('stock.php')">Close</button>
          <?PHP if($action == "Edit") { ?>
          <button type="button" class="btn btn-outline-danger" onclick="delRec('<?PHP echo $sid; ?>')" style="margin-left: 20px">DELETE</button>
          <?PHP } ?>
      </div>
    </div>
  </div>
</div>
  </br></br>
  <script>
    var SalesRecStatus = "<?PHP echo $SalesStatus; ?>";
    if(SalesRecStatus == "a") {
      $('#txtSalesStatusActive').prop('checked', true);
    } else {
      $('#txtSalesStatusInactive').prop('checked', true);
    }
    $("#btnSalesSave").click(function(){
        $("#frmSalesRec").submit();
    });
    function delRec(ID) {
      navMan("salesaddedit.php?sid=" + ID + "&a=delRec");
    }
    setTimeout(function(){
      $("#txtSalesName").focus();
    },1);
  </script>
<?PHP
build_footer();
?>