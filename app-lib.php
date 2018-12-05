<?PHP
/**
 * Set the global time zone
 *   - for Brisbane Australia (GMT +10)
 */
date_default_timezone_set('Australia/Queensland');

/**
 * Global variables
 */

// Database instance variable
$db = null;
$displayName = "";
$numItemsCart = 0;


// Start the session
session_name("lpaecomms");
session_start();

isset($_SESSION["authUser"])?
  $authUser = $_SESSION["authUser"] :
  $authUser = "";
isset($_SESSION["isAdmin"])?
  $isAdmin = $_SESSION["isAdmin"] :
  $isAdmin = "";

if(isset($authChk) == true) {
  if($authUser) {
    openDB();
    $query = "SELECT * FROM lpa_users WHERE lpa_user_ID = '$authUser' LIMIT 1";
    $result = $db->query($query);
    $row = $result->fetch_assoc();
    $displayName = $row['lpa_user_firstname']." ".$row['lpa_user_lastname'];
    $msg = "Success login USER_ID=".$authUser;
    file_log($msg);
  } else {
    header("location: login.php");
  }
}

if(isset($adminChk) == true) {
	if(!$isAdmin)
	{
		header("location: index.php");
	}
}

/**
* Log file 
**/
function file_log($msg){
  $current = "";
  $myfile = fopen("lpalog.log", "r+");
  $current = fread($myfile,9999);
  fwrite($myfile, "[".date("H:i:s d-m-Y")."]  ".$msg.";"."\n");
  fclose($myfile);
}

/**
 * Connect to database Function
 * - Connect to the local MySQL database and create an instance
 */
function openDB() {
  global $db;
  if(!is_resource($db)) {
    /* Conection String eg.: mysqli("localhost", "lpaecomms", "letmein", "lpaecomms")
     *   - Replace the connection string tags below with your MySQL parameters
     */
     $db = new mysqli(
      "localhost",
      "root",
      "",
      "lpa_ecomms"
    );
    if ($db->connect_errno) {
      $msg = "Failed to connect to MySQL: (" .
        $db->connect_errno . ") " .
        $db->connect_error;
        echo $msg;
        file_log($msg);
    }
	
  }
}

/**
 * Close connection to database Function
 * - Close a connection to the local MySQL database instance
 * @throws Exception
 */
function closeDB() {
  global $db;
  try {
    if(is_resource($db)) {
      $db->close();
    }
  } catch (Exception $e)
  {
    file_log('Error closing database');
    throw new Exception( 'Error closing database', 0, $e);
  }
}


/**
 * System Logout check
 *
 *  - Check if the logout button has been clicked, if so kill session.
 */
if(isset($_REQUEST['killses']) == "true") {
  $_SESSION = array();
  if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
      $params["path"], $params["domain"],
      $params["secure"], $params["httponly"]
    );
  }
  session_destroy();
  header("location: login.php");
}




/**
 *  Build the page header function
 */
function build_header() {
  global $displayName;

  include 'header.php';
}

/**
 *  Build the cart icon
 */
function build_cartIcon() {
  isset($_COOKIE['prod_id'])? $itmID = $_COOKIE['prod_id'] : $itmID = "";
  $splitItmID = explode("%$", $itmID);
  global $numItemsCart;
  $numItemsCart = count($splitItmID);
  echo $numItemsCart;
}
/**
 * Build the Navigation block
 */
function build_navBlock() {
  isset($_COOKIE['prod_id'])? $itmID = $_COOKIE['prod_id'] : $itmID = "";
  $splitItmID = explode("@prod", $itmID);
  $numItemsCart = count($splitItmID)-1;
  global $displayName;
	isset($_SESSION["isAdmin"])?
		$isAdmin = $_SESSION["isAdmin"] :
		$isAdmin = "";
	?>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">eCommerce</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
          <a class="nav-link" onclick="navMan('index.php')">Home <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" onclick="navMan('stock.php')">Stock</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" onclick="navMan('sales.php')">Sales</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" onclick="navMan('users.php')">Users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" onclick="navMan('products.php')">Products</a>
        </li>             
      </ul>
      <span class="navbar-text">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link btn btn-info" onclick="navMan('checkout.php')"><i class="fa fa-shopping-cart"></i>  <span class="badge badge-light"><?php echo $numItemsCart; ?></span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-outline-danger" onclick="navMan('login.php?killses=true')"> Logout</a>
          </li>                
        </ul>
      </span>
    </div>
  </nav>
<?PHP
}

/**
 * Create an ID
 * - Create a unique id.
 *
 * @param string $prefix
 * @param int $length
 * @param int $strength
 * @return string
 */
function gen_ID($prefix='',$length=3, $strength=0) {
  $final_id='';
  for($i=0;$i< $length;$i++)
  {
    $final_id .= mt_rand(0,9);
  }
  if($strength == 1) {
    $final_id = mt_rand(100,999).$final_id;
  }
  if($strength == 2) {
    $final_id = mt_rand(10000,99999).$final_id;
  }
  if($strength == 4) {
    $final_id = mt_rand(1000000,9999999).$final_id;
  }
  return $prefix.$final_id;
}

/**
 *  Build the page footer function
 */
function build_footer() {
  include 'footer.php';
}


?>