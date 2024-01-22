<?php
if(!isset($_SESSION['user_id'])) exit("falsch verbunden"); 


$sql = "select is_superuser from $t4 where is_superuser = 1 and id=".$_SESSION['user_id'];
$res = $con -> query($sql) or die("ERROR1 : ".$con->error);
if($res->num_rows != 1) header('Location: /');
$sql = "select count(*) from $t4";
$res = $con -> query($sql) or die("ERROR2 : ".$con->error);
$usr = $res->fetch_array();
$sql = "select 
  concat(ifnull(format(sum(amount),2),0),' &euro;') sum
  ,concat(ifnull(format(abs(sum(if(code_id=9,amount,0))),2),0),' &euro;') inv
  ,concat(ifnull(format(abs(sum(if(code_id=2,amount,0))),2),0),' &euro;') fee
  ,concat(ifnull(format(sum(if(code_id=3,amount,0)),2),0),' &euro;') bonus,
  ifnull(date_format(max(date),'%d.%m.%Y %H:%i'),'-') date
  from $t3  ";//group by other;"
$res = $con -> query($sql) or die("ERROR3 : ".$con->error);
$bal = $res->fetch_assoc();
$bl = $con -> query("set @a=0");
$bl = $con -> query("set @c=0");
$sql = "select 
    concat('<tr><th scope=\"row\">'
           ,@c:= @c+1,'</th><td>',concat_ws('</td><td>'
           ,date_format(u.date,'%Y&#8209;%m&#8209;%d&nbsp;%H:%i')
           ,c.username
           ,e.Text,format(u.amount,2)
           ,format(@a:=@a+u.amount,2), b.Kurzname, u.reason, u.text)
           ,'</td></tr>') as s
    from $t3 u 
    left join $t4 c on u.user_id = c.id
    left join $t5 e on u.code_id = e.id
    left join $t6 a on u.other = a.id
    left join $t7 b on a.Broker_id = b.id
    order by u.date asc, u.id asc";
$bl = $con -> query($sql) or die("ERROR4 : ".$con->error);



function getTradesHistory(){
    global $t1,$t3,$con;
$sql = "select 
          h.brokeraccount, h.open_time, h.profit,
          h.swap, h.equity, concat('<tr><th scope=\"row\">',b.Kurzname,'-',h.brokeraccount,'</th><td>',concat_ws('</td><td>',h.ticket,if(h.type=0,'BUY','SELL'),REPLACE(h.symbol,'micro',''),h.lots,date_format(h.open_time,'%Y&#8209;%m&#8209;%d&nbsp;%H:%i'),ifnull(date_format(h.close_time,'%Y&#8209;%m&#8209;%d&nbsp;%H:%i'),'in work')),'</td>') as line from $t1 h
          left join adm_brokeraccount a on h.brokeraccount = a.id
          LEFT JOIN adm_broker b ON a.Broker_id = b.id";
$tr = $con -> query($sql) or die("ERROR5 : ".$con->error);

while($row = $tr->fetch_array()){
    $sql = "select ifnull(abs(sum( if(code_id = 9, amount,0))),0) from $t3 
        where other = '${row[0]}' and  date <= '${row[1]}'
        -- and user_id=".$_SESSION['user_id']; 
    $res = $con -> query($sql) or die("ERROR3 : ".$con->error);
    list($equity) = $res->fetch_array();
    if($equity == 0) $e = '<td>'.number_format(($row[2]+$row[3]),2).
      '&euro;</td></tr>';
    else $e = '<td>'.number_format(($row[2]+$row[3])/$row[4]*$equity,2).'&euro;&nbsp;/&nbsp;'.number_format($equity,2).'&euro;</td></tr>';
    echo $row[5].$e;
} 
}

?>
                
    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="?">Home</a></li>
          <li class="breadcrumb-item active">Admin</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">

      <div class="row">
        <!-- Left side columns -->
        <div class="col-lg-12">
          <div class="row">

            <!-- Balance Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title"><a href="?admin">Balance</a> <span>| Total</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-cart"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?=$bal['sum'];?></h6>
                      <span class="text-success small pt-1 fw-bold">#</span> <span class="text-muted small pt-2 ps-1"><?=$bal['date'];?></span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Balance Card -->

            <!-- Investment Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">

                
                <div class="card-body">
                  <h5 class="card-title"><a href="?admin&trades">Invest</a> <span>| in work</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?=$bal['inv'];?></h6>
                      <!--span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span-->

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Investment Card -->

            <!-- Referrals Card -->
            <div class="col-xxl-4 col-xl-12">

              <div class="card info-card customers-card">

                
                <div class="card-body">
                  <h5 class="card-title">Users <span>| Total</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?=$usr[0];?></h6>

                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Referrals Card -->



          </div>
        </div><!-- End Left side columns -->
      </div>

<?php
if(isset($_GET['trades'])){ 
?>

      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body table-responsive">
              <h5 class="card-title">Trading history</h5>
              <table class="table datatable table-striped table-hover" id="trades">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ticket</th>
                    <th scope="col">Type</th>
                    <th scope="col">Symbol</th>
                    <th scope="col">Volume</th>
                    <th scope="col">Open Time</th>
                    <th scope="col">Closed Time</th>
                    <th scope="col">Profit/Invest</th>
                  </tr>
                </thead>
                <tbody>
                  <?php getTradesHistory(); ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
<?php } else { ;?>

      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body table-responsive">
              <h5 class="card-title">Account balance</h5>
              <table class="table datatable table-striped table-hover" id="balance">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Date</th>
                    <th scope="col">User</th>
                    <th scope="col">Type</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Balance</th>
                    <th scope="col">Source</th>
                    <th scope="col">Comment 1</th>
                    <th scope="col">Comment 2</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($row=$bl->fetch_array()) echo $row[0]; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
<?php } ;?>

    </section>
