<?php
  require_once '../assets/sql/config.php';
  $version = "2.02";
  error_reporting(E_ALL);
  // vars

    $con = new mysqli($ds,$du,$dp,$db);
    if ($con -> connect_error) {
      $log = date("F j, Y, g:i a") . ' - db connect error : ' . $con -> connect_error ."\n";
      file_put_contents('./log_'.date("j.n.Y").'.log', $log, FILE_APPEND);
      exit("db connect error");
      }
    $post = file_get_contents("php://input");
    $data = explode("\n", addslashes($post));
    list($acc,$response) = credentials(array_shift($data));
    if($response == "OK")        exit(array_shift($data)); // session ends here
    if($response == "START")     start($acc);
    if($response == "UPDATE")    update($data,$acc);
    if($response == "POSITIONS") positions($data,$acc);
    if($response == "DEAL")      deal($data,$acc);
    exit(array_shift($data));    // no command identified
  function credentials($data){
    global $t6,$con;
    list($number,$password,$response) = explode(";", $data);
    if ($password == '') exit("account error 400");// oder neues anlegen
    $sql = "select id from $t6 where Number='$number' and OnlinePassword='$password'";
    if($res = $con->query($sql)){
      if ($res->num_rows == 0) exit("account error 256");// oder neues anlegen
      list($acc) = $res->fetch_array();
      return array($acc,$response);
      }
      exit("db connect error");
    }
  function sql_query($sql){
    global $con;
    if($res = $con->query($sql)) {
      $log = date("F d, Y, g:i a") . " - db sql_query : $sql\n";
      file_put_contents('./log_'.date("d.n.Y").'.log', $log, FILE_APPEND);
      return $res;
      }
    $log = date("F d, Y, g:i a") . " - db error : " . $con->error ." # $sql\n";
    file_put_contents('./log_'.date("d.n.Y").'.log', $log, FILE_APPEND);
    exit("db error : " . $con->error);
    }
  function update($data,$acc){
    global $t1;
    $head = array_shift($data);//"ticket,type,symbol,open_time,lots";
    $len = count($data);
    foreach($data as $row_str){
      $ticket=preg_replace('/;.*/','', $row_str);
      $row_str = str_replace(";", "','", $row_str);
      $sql = "insert into $t1 ($head,brokeraccount) select '$row_str','$acc' 
              where not exists (select id from $t1
              where brokeraccount=$acc and ticket=$ticket) limit 1";
      $res = sql_query($sql);
      }
    exit("GET,POSITIONS");  
    }
  function positions($data,$acc){// update or insert opened positions, and send closable
    global $t1,$t3,$t4;
    $sql = "select group_concat(ticket) from $t1 where brokeraccount=$acc and close_time is null";
    $res = sql_query($sql);    
    list($row) = $res->fetch_array();
    $tickets = explode(',',$row); // get all opened orders from sql
    array_shift($data);//truncate header: ticket,type,symbol,lots,open_time,open_price,profit,swap
    foreach($data as $row){
      list($ticket,$type,$symbol,$lots,$open_time,$open_price,$profit,$swap) = explode(";", $row);
      $sql = "select id,equity from $t1 where ticket=$ticket and brokeraccount=$acc";
      $res = sql_query($sql);
      if($res->num_rows != 0){// Update
        list($id,$equity) = $res->fetch_array();
        if(empty($equity)){
          $sql = "
            update $t1 set equity = (select abs(sum( if(code_id = 9, amount,0)))
            from $t3 where date <= '$open_time' and other = '$acc' ) 
            where id=$id";
          $res = sql_query($sql);
          }
      }else{ // insert
        $sql  = "insert into $t1 set
          ticket='$ticket',brokeraccount='$acc',
          type='$type',symbol='$symbol',lots='$lots',
          open_time='$open_time',open_price='$open_price',
          profit='$profit',swap='$swap'";
        $res = sql_query($sql);
        }
      foreach (array_keys($tickets, $ticket) as $key) unset($tickets[$key]);
      }
    // set one closed position as close and calculate
    // send command for get closed deal
    if(count($tickets) != 0) exit("GET,DEAL,".array_shift($tickets));
    // calculate bonis!!!! here
    require "./04_calculate_commission.php";
    $sql = "select abs(sum( if(code_id = 9, amount,0))) sum from $t3 where other=$acc";
    $res = sql_query($sql);
    list($equity) = $res->fetch_array();
    exit("GET,EQUITY,".number_format($equity,2));
    }
  function deal($data,$acc){
    global $t1,$t3;
    array_shift($data);//"ticket,close_time,close_price,profit,swap"
    $len = count($data);
    if($len != 1){
      //exit("server: deal not recived");
      // // else closed position found , calculate roi
      $sql = "select abs(sum( if(code_id = 9, amount,0))) sum from $t3 where other=$acc";
      $res = sql_query($sql);
      list($equity) = $res->fetch_array();
      exit("GET,EQUITY,".number_format($equity,2));
    }
    $roi = 0;
    list($ticket,$close_time,$close_price,$profit,$swap) = explode(";", array_shift($data));
    $sql = "select id,open_time,equity from $t1 where brokeraccount=$acc and ticket=$ticket";
    $res = sql_query($sql);
    if($res->num_rows != 0){
      list($id,$open_time,$equity) = $res->fetch_array();
      require "./02_calculate_profit.php";
      if($roi != 0) require "./03_calculate_roi.php"; // Return of Investment
      }//if $res->num
    exit("Done: $ticket");
    }
  function start($acc){
    global $t1;
    $sql = "select date_format(max(open_time),'%Y.%m.%d') from $t1 where brokeraccount=$acc;";
    $res = sql_query($sql);
    list($date) = $res->fetch_array();
    if(empty($date)) $date ="2022.02.11";
    exit("GET,UPDATE,".$date);
    }
  ?>
