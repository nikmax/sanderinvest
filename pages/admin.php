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
            <h5 class="card-title"><a href="?admin&acc_user">Balance</a> <span>| Total</span></h5>

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
            <h5 class="card-title"><a href="?admin&acc_history">Invest</a> <span>| in work</span></h5>

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

<?php if(isset($_GET['acc_history'])){ ?>
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body table-responsive">
        <h5 class="card-title">Trading history</h5>
        <table class="table datatable table-striped table-hover" id="acc_history"></table>
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
        <table class="table datatable table-striped table-hover" id="acc_user"></table>
      </div>
    </div>
  </div>
</div>
<?php } ;?>

</section>
