<?php
include(ROOTDIR.'config.php');

// datbase exist
if(tablexist('site_options') === 0){
include('IQ_installation/rewrite_tables.php');
include(ADMINROOTDIR.'IQ_installation/final-setup/setup.php');
die();
}

// user exist



// update sql
function updatedata($data, $condition,$tablename){
    tablexist($tablename, $condition);

    global $conn; $i = 0; $dot = '';   $sqldata = '';
    foreach($data as $dta => $val){
        $val = htmlentities($val);
        if(is_integer($val)){ $val = htmlspecialchars($val); }
     $i++;
    if($i % 2 == 0){ $dot = ','; }
    $sqldata .=  $dot.$dta.'="'.$val.'" '; 
}
    $query = 'update '.$tablename.' set '.$sqldata.' ';
    if(!empty($condition)){ $query .= 'where '.$condition.'';    }

    $conn->query($query);
    if($conn->affected_rows > 0){
        $_SESSION['success'] = 'Data Updated Successfully';
    }
}


// FETCH DATA
// DISTICT USE THIS FOR GET NO DUPLICATE VALUE
function fetch_distictdata($tablename, $data){
    tablexist($tablename, $data);
    global $conn;
    $query = 'SELECT DISTINCT '.$data.' from '.$tablename.' ';
   $result = $conn->query($query);
   return  $result->fetch_all(MYSQLI_ASSOC);
}


// Fetch Data
function fetch($tablename, $condition){
    tablexist($tablename, $condition);
    global $conn;
    $query = 'select * from '.$tablename.' ';
    if(!empty($condition)){
        $query .= 'where '.$condition.'';
    }
   $result = $conn->query($query);
   if(mysqli_num_rows($result) == 1){
    return  $result->fetch_array(MYSQLI_ASSOC);
   }
}

// fetch data all
class db{
    private $number_of_page;
    private $results_per_page = 10;
    private $paginationstatus = 0;
    private $tablename;
    private $condition;
    private $pagination_totalnumber_of_result;
    function __construct(){
    global $conn;

    }

    function fetch_all($tablename, $condition=null, $pagination = null, $limit=null, $search=null){
        if(isset($limit) && !empty($limit)){ $this->results_per_page = $limit; }
        tablexist($tablename, $condition);
        $this->tablename = $tablename;
        $this->condition = $condition;
        global $conn;
        $query = 'select * from '.$tablename.' ';
        $search = '';
        // search
        if(isset($_GET['search'])){
        $searchresult = $conn->query($query.'Limit 1');
        $resulte = $searchresult->fetch_all(MYSQLI_ASSOC);
        $search = $_GET['search'];
        $tabledta = '';
        foreach($resulte[0] as $rows=>$val){
            $tabledta .= $rows.' LIKE "%'.$search.'%" ';
            $tabledta .= ' OR ';
        }
        $tabledta = substr($tabledta, 0, -3);
        }

        // search end 
        if(!empty($condition)){ $query .= 'where '.$condition.''; }
        if(isset($search) && !empty($condition)){ if(isset($_GET['search'])){$query .= ' and '.$tabledta;} }
        if(isset($search) && empty($condition)){ if(isset($_GET['search'])){$query .= ' where '.$tabledta;} }
        
        $query .= ' order by id DESC';
        // pagination
        if(isset($pagination) && $pagination == true){
            $this->paginationstatus = 1;
        if (!isset ($_GET['page']) ) { $page = 1;} else { $page = $_GET['page'];} 
        $results_per_page = $this->results_per_page;  
        $page_first_result = ($page-1) * $results_per_page;    
        $number_of_result  = mysqli_num_rows(mysqli_query($conn, $query)); 
        $this->pagination_totalnumber_of_result = $number_of_result;
        $number_of_page = ceil ($number_of_result / $results_per_page);  
        $this->number_of_page = $number_of_page;
        $query .= " LIMIT " . $page_first_result . ',' . $results_per_page;  
        }
    

       $result = $conn->query($query);
        return  $result->fetch_all(MYSQLI_ASSOC);
    }
     
    function paginationlast($total = null){
        if($this->paginationstatus == 1){
            if(isset($total)){
                $resultpage = [ $this->results_per_page, $this->pagination_totalnumber_of_result];
                return $resultpage;
            }else{
        if (!isset ($_GET['page']) ) { $page = 1;} else { $page = $_GET['page'];} 
        $i = 0;
        $link = '';
        foreach($_GET as $data=>$val){
            if($data != 'page'){
            if($i == 0){$link = '?'.$data.'='.$val;}else{$link .= '&'.$data.'='.$val;}
            }
            $i++;
        }
        if(empty($link)){ $link = '?'; }
        $pagearray = array();
        if($page != 1){ $pagearray[] = [  'Previous' => $link . '&page='.$page-1, ]; }
        for($pagecount = 1; $pagecount<= $this->number_of_page; $pagecount++) { 
            $pagearray[] = [  $pagecount => $link . '&page='.$pagecount, ];
        }  
        if($page != $this->number_of_page && $this->number_of_page != 0){ $pagearray[] = [  'Next' => $link . '&page='.$page+1, ]; }
        return $pagearray;
        
    }}
        
}


}

$db = new db();


// fetch data all end

// insert
function insertdata($data, $tablename){
    tablexist($tablename);

    global $conn; $i = 0; $dot = '';   $sqldata = ''; $sqlvalue = '';
    foreach($data as $dta => $val){
        $val = htmlentities($val);
        if(is_integer($val)){ $val = htmlspecialchars($val); }
     $i++;
    if($i % 2 == 0){ $dot = ','; }
    $sqldata .=  $dot.$dta; 
    $sqlvalue .=  $dot.'"'. $val.'"'; 
}
    $query = 'INSERT INTO '.$tablename.' ( '.$sqldata.' ) values ('.$sqlvalue.') ';
  
    $conn->query($query);
    if($conn->affected_rows > 0){
        // $_SESSION['success'] = 'Data Inserted Successfully';
    }
}

// Delete
function deletedata($tablename, $condition){
    tablexist($tablename);
    global $conn;
    $query = 'DELETE FROM '.$tablename.' ';
    if(!empty($condition)){ $query .= 'where '.$condition.'';    }

  
    $conn->query($query);
    if($conn->affected_rows > 0){
        $_SESSION['success'] = 'Data Delete Successfully';
    }
}

// table exist
function tablexist($tablename, $datacondition = null){
    global $conn, $database;
    if(isset($datacondition) && $datacondition == 'get'){
        $titled = $conn->query('SHOW COLUMNS FROM '.$tablename.';');
        $titled = $titled->fetch_all(MYSQLI_ASSOC);
        foreach($titled as $tablerows){
        $tabledata[$tablerows['Field']] = $tablerows['Field'];
        }
        return $tabledata;

    }else{

    $query = "SHOW TABLES FROM ".$database;
    $return = $conn->query($query);
    $data = $return->fetch_all();
    $status = 0;
    foreach($data as $val){
        if($val[0] == $tablename){ $status = 1; }
    }
    
    return $status;
    // if($status == 0){
    //     $_SESSION['error'] = 'Table Not Found';        
    //     $_SESSION['error_notice'] = 'Table Not Found';        
    //     back(); }
}
}
?>