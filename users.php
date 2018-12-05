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
    <div class="jumbotron">
      <h1 class="display-4">User consulting</h1>
      <p class="lead">Search the content and information from users.</p>
      <hr class="my-4">
      <form name="frmSearchSales" method="post" id="frmSearchSales" action="<?PHP echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
          <input name="txtSearch" id="txtSearch" class="form-control" placeholder="Search users" value="<?PHP echo $txtSearch; ?>">
        </div>
        <input type="hidden" name="a" value="listSales">
        <div class="formFooter" align="right">
          <button type="button" class="btn btn-info btn-lg" id="btnSearch">Search</button>
          <button type="button" class="btn btn-primary btn-lg"  onclick="loadURL('reg.php')">Add user</button>
        </div>
      </form>
    </div>
  </br>
    <!-- Search Section End -->
    <!-- Search Section List Start -->
    <?PHP
      if($action == "listSales") {
    ?>
    <div align="center">
      <table class="table table-hover" style="width: calc(100% - 15px);">
        <tr style="background: #ddd">
          <td style="width: 120px;"><b>User ID</b></td>
          <td style="border-left: #cccccc solid 1px"><b>Firstname</b></td>
          <td><b>Client Name</b></td>
          <td><b>Lastname</b></td>
          <td style="width: 80px;"><b>Status</b></td>
        </tr>
    <?PHP
      openDB();
      $totalAmount = 0;
      $query =
        "SELECT
            *
         FROM
            lpa_users
         WHERE
            lpa_user_username LIKE '%$txtSearch%'
         OR
            lpa_user_ID LIKE '%$txtSearch%'

         ";
      $result = $db->query($query);
      $row_cnt = $result->num_rows;
      if($row_cnt >= 1) {
        while ($row = $result->fetch_assoc()) {
          $sid = $row['lpa_user_ID'];
          ?>
          <tr class="hl" style="border-bottom: #cccccc solid 1px">
            <td>
              <?PHP echo $sid; ?>
            </td>
            <td style="border-left: #cccccc solid 1px">
                <?PHP echo $row['lpa_user_username']; ?>
            </td>
            <td>
                <?PHP echo $row['lpa_user_firstname']; ?>
            </td>
            <td>
                <?PHP echo $row['lpa_user_lastname']; ?>
            </td>            
            <td <?php if($row['lpa_user_status'] == '1'){ echo 'class="activeItem"';} else { echo 'class="inactiveItem"';} ?>>
              <strong><?php if($row['lpa_user_status'] == '1'){ echo 'Active';} else {echo 'Inactive';} ?></strong>
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
    function loadURL(URL) {
      window.location = URL;
    }
    var action = "<?PHP echo $action; ?>";
    var search = "<?PHP echo $txtSearch; ?>";
    if(action == "recUpdate") {
      alert("Record Updated!");
      navMan("users.php?a=listSales&txtSearch=" + search);
    }
    if(action == "recInsert") {
      alert("Record Added!");
      navMan("users.php?a=listSales&txtSearch=" + search);
    }
    if(action == "recDel") {
      alert("Record Deleted!");
      navMan("users.php?a=listSales&txtSearch=" + search);
    }
    function loadSalesItem(ID,MODE) {
      window.location = "users.php?sid=" +
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