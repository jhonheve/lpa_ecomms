<?PHP 
  $authChk = true;
  require('app-lib.php');
  $msg = null;
  isset($_POST['a'])? $action = $_POST['a'] : $action = "";
  if($action == "doReg" || isset($_POST['a']) ) {
    $chkLogin = false;
    isset($_POST['txtUserID'])? $userID = $_POST['txtUserID'] : $userID = gen_ID();
    isset($_POST['fldfirstName'])? $firstname = $_POST['fldfirstName'] : $firstname = "";
    isset($_POST['fldlastName'])? $lastname = $_POST['fldlastName'] : $lastname = "";
    isset($_POST['fldaddress'])? $address = $_POST['fldaddress'] : $address = "";
    isset($_POST['fldphone'])? $phone = $_POST['fldphone'] : $phone = "0";
    isset($_POST['fldusername'])? $username = $_POST['fldusername'] : $username = "";
    isset($_POST['fldpassword'])? $password = $_POST['fldpassword'] : $password = "";
    $status = "a";
    $group = "administrator";
    $encry_pass = md5($password);
    openDB();
    $queryInsert1 =
    "
      INSERT INTO lpa_clients
        (lpa_client_ID, 
        lpa_client_firstname, 
        lpa_client_lastname, 
        lpa_client_address, 
        lpa_client_phone, 
        lpa_client_status) 
      VALUES 
        ('$userID',
        '$firstname',
        '$lastname',
        '$address',
        '$phone',
        '$status')
    ";
    $queryInsert2 =
    "
      INSERT INTO lpa_users
        (lpa_user_ID,
        lpa_user_username,
        lpa_user_password,
        lpa_user_firstname,
        lpa_user_lastname,
        lpa_user_group)
      VALUES
        ('$userID',
        '$username',
        '$encry_pass',
        '$firstname',
        '$lastname',
        '$group')
      ";
    $querySelect =
    "
      SELECT
        lpa_user_ID,
        lpa_user_username,
        lpa_user_password,
        lpa_user_group
      FROM
        lpa_users
      WHERE
        lpa_user_username = '$username'
      LIMIT 1
      ";

    $resultSelect = $db->query($querySelect);
    $row = $resultSelect->fetch_assoc();
    if ($db->error) {
      printf("Errormessage: %s\n", $db->error);
      exit;
    } else {
      if($row['lpa_user_username'] == $username){
        $msg = "The username has been taken.";
        $chkLogin = false;
      } else {
        $result = $db->query($queryInsert1);
        if($db->error) {
          printf("Errormessage: %s\n", $db->error);
          exit;
        } else {
          $chkLogin = true;
        }
        $result = $db->query($queryInsert2);
        if($db->error) {
          printf("Errormessage: %s\n", $db->error);
          exit;
        } else {
          $chkLogin = true;
        }
      }
    }
    if($chkLogin == false) {
      $msg = "Register failed! Please try again.";
    } else {
      $msg = "User has been register!";
    }
  }

  build_header();

  build_navBlock();
?>
<div style="padding: 20px 10%;">
  <div class="content" align="left">
    <div class="card text-center">
      <div class="card-header">
        New Customer Registration
      </div>
        <form name="frmUserReg" id="frmUserReg" method="post" action="<?PHP echo $_SERVER['PHP_SELF']; ?>"> 
          <div class="card-body" align="left">
            <div class="form-group">
              <label>First Name</label>
              <input class="form-control" type="text" name="fldfirstName" id="fldfirstName" placeholder="Firstname">
            </div>
            <div class="form-group">
              <label>Last Name</label>
              <input class="form-control" type="text" name="fldlastName" id="fldlastName" placeholder="Lastname">
            </div>
            <div class="form-group">
              <label>Address</label>
              <input class="form-control" type="text" name="fldaddress" id="fldaddress" placeholder="Address">
            </div>
            <div class="form-group">
              <label>Phone number</label>
              <input class="form-control" type="number" name="fldphone" id="fldphone" placeholder="Phone number">
            </div>
            <div class="form-group">
              <label>Username</label>
              <input class="form-control" type="text" name="fldusername" id="fldusername" placeholder="Username">
            </div>
            <div class="form-group">
              <label>Password</label> 
              <input class="form-control" type="password" name="fldpassword" id="fldpassword" placeholder="Password">
            </div>
            <div class="form-group">
              <label>Confirm password</label>
              <input class="form-control" type="password" name="fldconfirm" id="fldconfirm" placeholder="Confirm password">
            </div>
          </div>
          <div class="card-footer text-muted" align="right">
            <button type="submit" class="btn btn-success" onclick="do_reg()" value="submit">Register a new user</button>
          </div>
          <input type="hidden" name="a" value="doReg">
        </form>
    </div>
  </div>
</div>
</br></br></br>
<script>
  var msg = "<?PHP echo $msg; ?>";
  if(msg) {
    alert(msg);
  }
  $("#btnLog").click(function(){
    window.location.href = "login.php";
  });
</script>
<?PHP
build_footer();
?>