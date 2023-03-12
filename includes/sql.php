<?php
  require_once('includes/load.php');

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name
/*--------------------------------------------------------------*/
function find_all($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table));
   }
}

/*--------------------------------------------------------------*/
/* Function for find all database table rows by table name in descending order
/*--------------------------------------------------------------*/
function find_all_desc($table) {
   global $db;
   if(tableExists($table))
   {
     return find_by_sql("SELECT * FROM ".$db->escape($table) ." ORDER BY ID DESC ");
   }
}


/*--------------------------------------------------------------*/
/* Function for Perform queries
/*--------------------------------------------------------------*/
function find_by_sql($sql)
{
  global $db;
  $result = $db->query($sql);
  $result_set = $db->while_loop($result);
 return $result_set;
}
/*--------------------------------------------------------------*/
/*  Function for Find data from table by id
/*--------------------------------------------------------------*/
function find_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}
/*--------------------------------------------------------------*/
/* Function for Delete data from table by id
/*--------------------------------------------------------------*/
function delete_by_id($table,$id)
{
  global $db;
  if(tableExists($table))
   {
    $sql = "DELETE FROM ".$db->escape($table);
    $sql .= " WHERE id=". $db->escape($id);
    $sql .= " LIMIT 1";
    $db->query($sql);
    return ($db->affected_rows() === 1) ? true : false;
   }
}
/*--------------------------------------------------------------*/
/* Function for Count id  By table name
/*--------------------------------------------------------------*/

function count_by_id($table){
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) AS total FROM ".$db->escape($table);
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}
/*--------------------------------------------------------------*/
/* Determine if database table exists
/*--------------------------------------------------------------*/
function tableExists($table){
  global $db;
  $table_exit = $db->query('SHOW TABLES FROM '.DB_NAME.' LIKE "'.$db->escape($table).'"');
      if($table_exit) {
        if($db->num_rows($table_exit) > 0)
              return true;
         else
              return false;
      }
  }
 /*--------------------------------------------------------------*/
 /* Login with the data provided in $_POST,
 /* coming from the login form.
/*--------------------------------------------------------------*/
  function authenticate($username='', $password='') {
    global $db;
    $username = $db->escape($username);
    $password = $db->escape($password);
    $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username = BINARY '%s' LIMIT 1", $username);
    $result = $db->query($sql);
    if($db->num_rows($result)){
      $user = $db->fetch_assoc($result);
      $password_request = sha1($password);
      if($password_request === $user['password'] ){
        return $user['id'];
      }
    }
   return false;
  }
  /*--------------------------------------------------------------*/
  /* Login with the data provided in $_POST,
  /* coming from the login_v2.php form.
  /* If you used this method then remove authenticate function.
 /*--------------------------------------------------------------*/
   function authenticate_v2($username='', $password='') {
     global $db;
     $username = $db->escape($username);
     $password = $db->escape($password);
     $sql  = sprintf("SELECT id,username,password,user_level FROM users WHERE username ='%s' LIMIT 1", $username);
     $result = $db->query($sql);
     if($db->num_rows($result)){
       $user = $db->fetch_assoc($result);
       $password_request = sha1($password);
       if($password_request === $user['password'] ){
         return $user;
       }
     }
    return false;
   }


  /*--------------------------------------------------------------*/
  /* Find current log in user by session id
  /*--------------------------------------------------------------*/
  function current_user(){
      static $current_user;
      global $db;
      if(!$current_user){
         if(isset($_SESSION['user_id'])):
             $user_id = intval($_SESSION['user_id']);
             $current_user = find_by_id('users',$user_id);
        endif;
      }
    return $current_user;
  }
  /*--------------------------------------------------------------*/
  /* Find all user by
  /* Joining users table and user gropus table
  /*--------------------------------------------------------------*/
  function find_all_user(){
      global $db;
      $results = array();
      $sql = "SELECT u.id,u.name,u.username,u.user_level,u.status,u.last_login,";
      $sql .="g.group_name ";
      $sql .="FROM users u ";
      $sql .="LEFT JOIN user_groups g ";
      $sql .="ON g.group_level=u.user_level ORDER BY u.name ASC";
      $result = find_by_sql($sql);
      return $result;
  }
  /*--------------------------------------------------------------*/
  /* Function to update the last log in of a user
  /*--------------------------------------------------------------*/

 function updateLastLogIn($user_id)
	{
		global $db;
    $date = make_date();
    $sql = "UPDATE users SET last_login='{$date}' WHERE id ='{$user_id}' LIMIT 1";
    $result = $db->query($sql);
    return ($result && $db->affected_rows() === 1 ? true : false);
	}

  /*--------------------------------------------------------------*/
  /* Find all Group name
  /*--------------------------------------------------------------*/
  function find_by_groupName($val)
  {
    global $db;
    $sql = "SELECT group_name FROM user_groups WHERE group_name = '{$db->escape($val)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Find group level
  /*--------------------------------------------------------------*/
  function find_by_groupLevel($level)
  {
    global $db;
    $sql = "SELECT group_level FROM user_groups WHERE group_level = '{$db->escape($level)}' LIMIT 1 ";
    $result = $db->query($sql);
    return($db->num_rows($result) === 0 ? true : false);
  }
  /*--------------------------------------------------------------*/
  /* Function for cheaking which user level has access to page
  /*--------------------------------------------------------------*/
   function page_require_level($require_level){
     global $session;
     $current_user = current_user();
     $login_level = find_by_groupLevel($current_user['user_level']);
	 $group_status = $login_level['group_status'] ?? '';  
	  
     //if user not login
     if (!$session->isUserLoggedIn(true)):
            $session->msg('d','Please login...');
            redirect('index.php', false);
      //if Group status Deactive
    // elseif($login_level['group_status'] === '0'):
	 elseif($group_status === '0'):
           $session->msg('d','This level user has been band!'.$current_user['user_level']);
           redirect('home.php',false);
      //cheackin log in User level and Require level is Less than or equal to
     elseif($current_user['user_level'] <= (int)$require_level):
              return true;
      else:
            $session->msg("d", "Sorry! you dont have permission to view the page.");
            redirect('home.php', false);
        endif;

     }
   /*--------------------------------------------------------------*/
   /* Function for Finding all product name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
  function join_product_table(){
     global $db;
     $sql  =" SELECT p.id,p.name,p.quantity,p.buy_price,p.sale_price,p.media_id,p.date,c.name";
    $sql  .=" AS categorie,m.file_name AS image";
    $sql  .=" FROM products p";
    $sql  .=" LEFT JOIN categories c ON c.id = p.categorie_id";
    $sql  .=" LEFT JOIN media m ON m.id = p.media_id";
    $sql  .=" ORDER BY p.id ASC";
    return find_by_sql($sql);

   }
  /*--------------------------------------------------------------*/
  /* Function for Finding all product name
  /* Request coming from ajax.php for auto suggest
  /*--------------------------------------------------------------*/

   function find_product_by_title($product_name){
     global $db;
     $p_name = remove_junk($db->escape($product_name));
     $sql = "SELECT name FROM products WHERE name like '%$p_name%' LIMIT 5";
     $result = find_by_sql($sql);
     return $result;
   }

  /*--------------------------------------------------------------*/
  /* Function for Finding all product info by product title
  /* Request coming from ajax.php
  /*--------------------------------------------------------------*/
  function find_all_product_info_by_title($title){
    global $db;
   /* $sql  = "SELECT * FROM products ";
    $sql .= " WHERE name ='{$title}'";
    $sql .=" LIMIT 1";*/
	
	$sql  = " MAX(draw_no) FROM `draw` ";
    $sql .= " WHERE mode='{$title}'";
    $sql .=" LIMIT 1";
    return find_by_sql($sql);
  }

  /*--------------------------------------------------------------*/
  /* Function for Update product quantity
  /*--------------------------------------------------------------*/
  function update_product_qty($qty,$p_id){
    global $db;
    $qty = (int) $qty;
    $id  = (int)$p_id;
    $sql = "UPDATE products SET quantity=quantity -'{$qty}' WHERE id = '{$id}'";
    $result = $db->query($sql);
    return($db->affected_rows() === 1 ? true : false);

  }
  /*--------------------------------------------------------------*/
  /* Function for Display Recent product Added
  /*--------------------------------------------------------------*/
 function find_recent_product_added($limit){
   global $db;
   $sql   = " SELECT p.id,p.name,p.sale_price,p.media_id,c.name AS categorie,";
   $sql  .= "m.file_name AS image FROM products p";
   $sql  .= " LEFT JOIN categories c ON c.id = p.categorie_id";
   $sql  .= " LEFT JOIN media m ON m.id = p.media_id";
   $sql  .= " ORDER BY p.id DESC LIMIT ".$db->escape((int)$limit);
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Find Highest saleing Product
 /*--------------------------------------------------------------*/
 function find_higest_saleing_product($limit){
   global $db;
   $sql  = "SELECT p.name, COUNT(s.product_id) AS totalSold, SUM(s.qty) AS totalQty";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON p.id = s.product_id ";
   $sql .= " GROUP BY s.product_id";
   $sql .= " ORDER BY SUM(s.qty) DESC LIMIT ".$db->escape((int)$limit);
   return $db->query($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for find all sales
 /*--------------------------------------------------------------*/
 function find_all_sale(){
   global $db;
   $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
   $sql .= " FROM sales s";
   $sql .= " LEFT JOIN products p ON s.product_id = p.id";
   $sql .= " ORDER BY s.date DESC";
   return find_by_sql($sql);
 }
 /*--------------------------------------------------------------*/
 /* Function for Display Recent sale
 /*--------------------------------------------------------------*/
function find_recent_sale_added($limit){
  global $db;
  $sql  = "SELECT s.id,s.qty,s.price,s.date,p.name";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " ORDER BY s.date DESC LIMIT ".$db->escape((int)$limit);
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate sales report by two dates
/*--------------------------------------------------------------*/
function find_sale_by_dates($start_date,$end_date){
  global $db;
  $start_date  = date("Y-m-d", strtotime($start_date));
  $end_date    = date("Y-m-d", strtotime($end_date));
  $sql  = "SELECT s.date, p.name,p.sale_price,p.buy_price,";
  $sql .= "COUNT(s.product_id) AS total_records,";
  $sql .= "SUM(s.qty) AS total_sales,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price,";
  $sql .= "SUM(p.buy_price * s.qty) AS total_buying_price ";
  $sql .= "FROM sales s ";
  $sql .= "LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE s.date BETWEEN '{$start_date}' AND '{$end_date}'";
  $sql .= " GROUP BY DATE(s.date),p.name";
  $sql .= " ORDER BY DATE(s.date) DESC";
  return $db->query($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Daily sales report
/*--------------------------------------------------------------*/
function  dailySales($year,$month){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y-%m' ) = '{$year}-{$month}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%e' ),s.product_id";
  return find_by_sql($sql);
}
/*--------------------------------------------------------------*/
/* Function for Generate Monthly sales report
/*--------------------------------------------------------------*/
function  monthlySales($year){
  global $db;
  $sql  = "SELECT s.qty,";
  $sql .= " DATE_FORMAT(s.date, '%Y-%m-%e') AS date,p.name,";
  $sql .= "SUM(p.sale_price * s.qty) AS total_saleing_price";
  $sql .= " FROM sales s";
  $sql .= " LEFT JOIN products p ON s.product_id = p.id";
  $sql .= " WHERE DATE_FORMAT(s.date, '%Y' ) = '{$year}'";
  $sql .= " GROUP BY DATE_FORMAT( s.date,  '%c' ),s.product_id";
  $sql .= " ORDER BY date_format(s.date, '%c' ) ASC";
  return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
   /* Function for Finding all Panel name
   /* JOIN with categorie  and media database table
   /*--------------------------------------------------------------*/
  function join_panel_table($status){
     global $db;
	  $sql  = "SELECT p.id, p.member_name, p.designation, p.nic, p.work_place, p.email, p.photo, p.status, c.contact_number mob,d.contact_number home";  
	  $sql .= " FROM `panel_member` p";   
	  $sql .= " LEFT JOIN contact_number c ON c.panel_member_id = p.id";
	  $sql .= " LEFT JOIN contact_number d ON d.panel_member_id = p.id";
	  $sql .= " WHERE ";
	  $sql .= " c.contact_type= 'mobile'";
	  $sql .= " AND d.contact_type= 'home'";  
	  $sql .= " AND  p.status= {$status}";	 
      $sql  .=" ORDER BY p.id ASC";
    
	   return find_by_sql($sql);

   }
   
   /*--------------------------------------------------------------*/
/* Function for find panel by NIC
/*--------------------------------------------------------------*/
function  findByNIC($nic,$status){
  global $db;
  $sql  = "SELECT p.id, p.member_name, p.designation, p.nic, p.work_place, p.email, p.photo, p.status, c.contact_number mob,d.contact_number home";  
  $sql .= " FROM `panel_member` p";   
  $sql .= " LEFT JOIN contact_number c ON c.panel_member_id = p.id";
  $sql .= " LEFT JOIN contact_number d ON d.panel_member_id = p.id";
  $sql .= " WHERE p.nic LIKE '%{$nic}%'";
  $sql .= " AND c.contact_type= 'mobile'";
  $sql .= " AND d.contact_type= 'home'";  
  $sql .= " AND  p.status= {$status}";	 
    
  return find_by_sql($sql);
}


  /*--------------------------------------------------------------*/
/* Function for find panel by ID
/*--------------------------------------------------------------*/
function  findPanelById($id){
  global $db;
  $sql  = "SELECT p.id, p.member_name, p.designation, p.nic, p.work_place, p.email, p.photo, p.status, c.contact_number mob,d.contact_number home";  
  $sql .= " FROM `panel_member` p";   
  $sql .= " LEFT JOIN contact_number c ON c.panel_member_id = p.id";
  $sql .= " LEFT JOIN contact_number d ON d.panel_member_id = p.id";
  $sql .= " WHERE p.id = {$id}";
  $sql .= " AND c.contact_type= 'mobile'";
  $sql .= " AND d.contact_type= 'home'";
  $sql .= " AND  p.status= 1";	 
	  
  
  if($result = $db->fetch_assoc($sql))
	return $result;
  else
	return null;
   //  } 
}

 /*--------------------------------------------------------------*/
/* Function for find panel by Mobile No
/*--------------------------------------------------------------*/
function  findByMob($mob,$status){
  global $db;
  $sql  = "SELECT p.id, p.member_name, p.designation, p.nic, p.work_place, p.email, p.photo, p.status, c.contact_number mob,d.contact_number home";  
  $sql .= " FROM `panel_member` p";   
  $sql .= " LEFT JOIN contact_number c ON c.panel_member_id = p.id";
  $sql .= " LEFT JOIN contact_number d ON d.panel_member_id = p.id";
  $sql .= " WHERE c.contact_number LIKE '%{$mob}%'";
  $sql .= " AND c.contact_type= 'mobile'";
  $sql .= " AND d.contact_type= 'home'";  
  $sql .= " AND  p.status= {$status}";	 
    
  return find_by_sql($sql);
}


/*--------------------------------------------------------------*/
/* Function for find panel by NIC and  Mobile No
/*--------------------------------------------------------------*/
function  findByNICMob($nic,$mob,$status){
  global $db;
  $sql  = "SELECT p.id, p.member_name, p.designation, p.nic, p.work_place, p.email, p.photo, p.status, c.contact_number mob,d.contact_number home";  
  $sql .= " FROM `panel_member` p";   
  $sql .= " LEFT JOIN contact_number c ON c.panel_member_id = p.id";
  $sql .= " LEFT JOIN contact_number d ON d.panel_member_id = p.id";
  $sql .= " WHERE c.contact_number LIKE '%{$mob}%'";
  $sql .= " AND c.contact_type= 'mobile'";
  $sql .= " AND d.contact_type= 'home'";  
  $sql .= " AND p.nic LIKE '%{$nic}%'";
  $sql .= " AND  p.status= {$status}";	 
    
  return find_by_sql($sql);
}




/*--------------------------------------------------------------*/
/*  Function for Find Mobile No from table by panel member id
/*--------------------------------------------------------------*/

function find_M_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE panel_member_id='{$db->escape($id)}' and contact_type= 'mobile' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

/*--------------------------------------------------------------*/
/*  Function for Find Mobile No from table by panel member id
/*--------------------------------------------------------------*/

function find_HM_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE panel_member_id='{$db->escape($id)}' and contact_type= 'home' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

/*--------------------------------------------------------------*/
/*  Function for Find Panel Member ID from table by panel member nic
/*--------------------------------------------------------------*/

function find_panel_id_by_nic($table,$nic)
{
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT id FROM ".$db->escape($table) ." WHERE nic='{$db->escape($nic)}' and status= '1' LIMIT 1";
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}

  /*--------------------------------------------------------------*/
/* Function for find panel by Panel Member Name
/*--------------------------------------------------------------*/
function  findByName($name,$status){
  global $db;
  $sql  = "SELECT p.id, p.member_name, p.designation, p.nic, p.work_place, p.email, p.photo, p.status, c.contact_number mob,d.contact_number home";  
  $sql .= " FROM `panel_member` p";   
  $sql .= " LEFT JOIN contact_number c ON c.panel_member_id = p.id";
  $sql .= " LEFT JOIN contact_number d ON d.panel_member_id = p.id";
  $sql .= " WHERE p.member_name LIKE '%{$name}%'";
  $sql .= " AND c.contact_type= 'mobile'";
  $sql .= " AND d.contact_type= 'home'";  
  $sql .= " AND  p.status= {$status}";	 
    
  return find_by_sql($sql);
}

//findByNicNameMob

 /*--------------------------------------------------------------*/
/* Function for find panel by Panel Member Name and NIC
/*--------------------------------------------------------------*/
function  findByNicName($name,$nic,$status){
  global $db;
  $sql  = "SELECT p.id, p.member_name, p.designation, p.nic, p.work_place, p.email, p.photo, p.status, c.contact_number mob,d.contact_number home";  
  $sql .= " FROM `panel_member` p";   
  $sql .= " LEFT JOIN contact_number c ON c.panel_member_id = p.id";
  $sql .= " LEFT JOIN contact_number d ON d.panel_member_id = p.id";
  $sql .= " WHERE p.member_name LIKE '%{$name}%'";
  $sql .= " AND p.nic LIKE '%{$nic}%'";
  $sql .= " AND c.contact_type= 'mobile'";
  $sql .= " AND d.contact_type= 'home'";  
  $sql .= " AND  p.status= {$status}";	 
    
  return find_by_sql($sql);
}


/*--------------------------------------------------------------*/
/* Function for find panel by Panel Member Name and NIC and Mobile
/*--------------------------------------------------------------*/
function  findByNicNameMob($name,$nic,$mob,$status){
  global $db;
  $sql  = "SELECT p.id, p.member_name, p.designation, p.nic, p.work_place, p.email, p.photo, p.status, c.contact_number mob,d.contact_number home";  
  $sql .= " FROM `panel_member` p";   
  $sql .= " LEFT JOIN contact_number c ON c.panel_member_id = p.id";
  $sql .= " LEFT JOIN contact_number d ON d.panel_member_id = p.id";
  $sql .= " WHERE p.member_name LIKE '%{$name}%'";
  $sql .= " AND p.nic LIKE '%{$nic}%'";
  $sql .= " AND c.contact_number LIKE '%{$mob}%'";
  $sql .= " AND c.contact_type= 'mobile'";
  $sql .= " AND d.contact_type= 'home'";  
  $sql .= " AND  p.status= {$status}";	 
    
  return find_by_sql($sql);
}


/*--------------------------------------------------------------*/
/* Function for find by draw no
/*--------------------------------------------------------------*/
function  findDrawBydraw_no($draw_no,$mode){
  global $db;
  $sql  = " SELECT `id`,`draw_date`, `draw_no`, `mode`, `winning_nos`, `english_letter` FROM `draw` WHERE `draw_no`={$draw_no} ";  
  $sql .= " AND  mode= '{$mode}'";
  return find_by_sql($sql);
}

 /*--------------------------------------------------------------*/
/* Function for find by draw date
/*--------------------------------------------------------------*/
function  findDrawByDate($draw_date,$mode){
  global $db;
  $sql  = " SELECT `id`,`draw_date`, `draw_no`, `mode`, `winning_nos`, `english_letter` FROM `draw` WHERE `draw_date` like '%$draw_date%'";  
  $sql .= " AND  mode= '{$mode}'";
  return find_by_sql($sql);
} 
 
 /*--------------------------------------------------------------*/
/* Function for find by draw date,draw no
/*--------------------------------------------------------------*/
function  findDrawByDrawNoDate($draw_no,$draw_date,$mode){
  global $db;
  $sql  = " SELECT `id`,`draw_date`, `draw_no`, `mode`, `winning_nos`, `english_letter` FROM `draw` WHERE `draw_no`={$draw_no} AND `draw_date` like '%$draw_date%' ";  
  $sql .= " AND  mode= '{$mode}'";
  return find_by_sql($sql);
} 
 
 
 /*--------------------------------------------------------------*/
/* Function for find by draw date,draw no
/*--------------------------------------------------------------*/
function  findDrawByMode($mode){
  global $db;
  $sql  = " SELECT `id`,`draw_date`, `draw_no`, `mode`, `winning_nos`, `english_letter` FROM `draw` WHERE ";  
  $sql .= " mode= '{$mode}'";
  return find_by_sql($sql);
} 
 
 
function wh_log($log_msg)
{
    $log_filename = "accees_logs";
    if (!file_exists($log_filename)) 
    {
        // create directory/folder uploads.
        mkdir($log_filename, 0777, true);
    }
    $log_file_data = $log_filename.'/logs.log';
    file_put_contents($log_file_data, $log_msg . "\n", FILE_APPEND);
}


function email_validation($str) {
    return (!preg_match(
"^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", $str))
        ? FALSE : TRUE;
}

/*--------------------------------------------------------------*/
/*  Function for Find Assigned Panel member details by panel table
/*--------------------------------------------------------------*/

function find_panel_allocation_by_id($table,$id)
{
  global $db;
  $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT * FROM {$db->escape($table)} WHERE id='{$db->escape($id)}'");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
}

/*--------------------------------------------------------------*/
/*  Function for Find Assigned Panel members by draw no and mode
/*--------------------------------------------------------------*/

function find_panel_allocation_by_draw_no_mode()
{
	global $db;
	$sql  = " SELECT  panel_1, panel_2, panel_3, panel_4, panel_5 ";
    $sql .= " FROM draw_allocation ";
	$sql .= " WHERE  draw_no=(SELECT MAX(draw_no) FROM draw_allocation) AND mode='Live' AND status='Y'";
	
    return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/*  Function for Find latest panel inputs for test draw and live draw
/*--------------------------------------------------------------*/

function find_panel_test_inputs()
{
	global $db;
	$sql  = " SELECT  * ";
    $sql .= " FROM `panel_inputs` WHERE ";
	$sql .= " draw_no=(SELECT MAX(draw_no) FROM panel_inputs WHERE mode='Test') AND mode='Test'";
	
    return find_by_sql($sql);
}

function find_panel_live_inputs()
{
	global $db;
	$sql  = " SELECT  * ";
    $sql .= " FROM `panel_inputs` WHERE ";
	$sql .= " draw_no=(SELECT MAX(draw_no) FROM panel_inputs WHERE mode='Live') AND mode='Live'";
	
    return find_by_sql($sql);
}

/*--------------------------------------------------------------*/
/*  Function for Find last draw no by draw mode
/*--------------------------------------------------------------*/
function find_by_mode($mode){
	global $db;
    $id = (int)$id;
    if(tableExists($table)){
          $sql = $db->query("SELECT NVL(max(`draw_no`)+1,1) FROM `draw` WHERE mode='{$mode}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
  }
  
  /*--------------------------------------------------------------*/
/*  Function for Find Panel Input Count for a specific draw
/*--------------------------------------------------------------*/
function find_by_mode_draw_no_p_id($mode,$draw_no,$panel_id){
	global $db;
    $id = (int)$id;
    if(tableExists($table)){
		//SELECT COUNT(`id`) FROM `panel_inputs` WHERE `draw_no`=29 AND `mode`='TEST' AND `panel_id`=13;
          $sql = $db->query("SELECT COUNT(`id`) FROM `panel_inputs` WHERE `draw_no`='{$draw_no}' AND `mode`='{$mode}' AND `panel_id`='{$panel_id}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
  }
  
  
   /*--------------------------------------------------------------*/
/*  Check Draw Already Exist
/*--------------------------------------------------------------*/
function check_draw_mode_draw_no($mode,$draw_no){
	global $db;
    $id = (int)$id;
    if(tableExists($table)){
		//SELECT COUNT(`id`) FROM `panel_inputs` WHERE `draw_no`=29 AND `mode`='TEST' AND `panel_id`=13;
          $sql = $db->query("SELECT COUNT(`id`) FROM `draw` WHERE `draw_no`='{$draw_no}' AND `mode`='{$mode}' LIMIT 1");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
  }
  
/*--------------------------------------------------------------*/
/* Get Panel Input Value
/*--------------------------------------------------------------*/
function get_panel_input_val($draw_no,$panel, $mode){
	global $db;
    $id = (int)$id;
    if(tableExists($table)){
		//SELECT COUNT(`id`) FROM `panel_inputs` WHERE `draw_no`=29 AND `mode`='TEST' AND `panel_id`=13;
          $sql = $db->query("SELECT pin_no FROM `panel_inputs` where panel_id={$panel} AND draw_no={$draw_no} and mode='{$mode}';");
          if($result = $db->fetch_assoc($sql))
            return $result;
          else
            return null;
     }
  }
  
  
/*--------------------------------------------------------------*/
/*  Function for Find Panel Member ID from table by panel member nic
/*--------------------------------------------------------------*/

function find_panel_count_by_nic($table,$nic)
{
  global $db;
  if(tableExists($table))
  {
    $sql    = "SELECT COUNT(id) c FROM ".$db->escape($table) ." WHERE nic='{$db->escape($nic)}' and status= '1' LIMIT 1";
    $result = $db->query($sql);
     return($db->fetch_assoc($result));
  }
}

/*--------------------------------------------------------------*/
  /* Function for Finding all draw users
  /* 
  /*--------------------------------------------------------------*/

   function find_draw_user(){
     global $db;     
     $sql = "SELECT `id`, `name` FROM `users` WHERE user_level IN (1,3);";
     $result = find_by_sql($sql);
     return $result;
   }
   
   /*--------------------------------------------------------------*/
  /* Function for Finding latest Draw Allocations
  /* 
  /*--------------------------------------------------------------*/

   function find_latest_draw_allocation(){
     global $db;     
     $sql = "SELECT * FROM `draw_allocation` WHERE id=(SELECT MAX(id) FROM `draw_allocation` where mode='Test' and status='Y') OR id=(SELECT MAX(id) FROM `draw_allocation` where mode='LIVE' and status='Y');";
     $result = find_by_sql($sql);
     return $result;
   }
?>
