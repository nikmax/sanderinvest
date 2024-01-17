<?php
if(!isset($_SESSION['user_id'])) exit("falsch verbunden"); 
$sel0 = "select @c%s:=count(id), @p%s:=group_concat(id) from $t4 where ref_id = %s and id <> %s";
$selX = " union select @c%s:=count(id), @p%s:= group_concat(id) from $t4 where find_in_set(ref_id ,@p%s) > 0"; 
$selE = " union all select @c%s+@c%s+@c%s+@c%s+@c%s+@c%s+@c%s, concat_ws(',',@p%s,@p%s,@p%s,@p%s,@p%s,@p%s,@p%s)"; 
$sql  = sprintf($sel0,1,1,$_SESSION['user_id'],$_SESSION['user_id']); 
$sql .= sprintf($selX,2,2,1);
$sql .= sprintf($selX,3,3,2);
$sql .= sprintf($selX,4,4,3);
$sql .= sprintf($selX,5,5,4);
$sql .= sprintf($selX,6,6,5);
$sql .= sprintf($selX,7,7,6);
$sql .= sprintf($selE,1,2,3,4,5,6,7,1,2,3,4,5,6,7);
//echo "<pre>$sql\n</pre>"; 
$res = $con -> query($sql) or die("ERROR0 : ".$con->error);
$ref = array();
while( $row = $res->fetch_array() ){ $refs = $row[0]; }
/*
  $select0  = "select id from $t4 where ref_id";
  $select  = "select id from $t4 where ref_id";
  $select2 = "$select = @pid  and id <> @pid";
  $sql = "$select0 = @pid  and id <> @pid
  union all $select0 in ($select2)
  union all $select0 in ($select in ($select2))
  union all $select0 in ($select in ($select in ($select2)))
  union all $select0 in ($select in ($select in \n($select in ($select2))))
  union all $select0 in ($select in ($select in \n($select in ($select in ($select2)))))
  union all $select0 in ($select in ($select in ($select in \n($select in ($select in ($select2))))))";
  //echo "<pre>$sql\n</pre>";
  $res = $con -> query("set @pid := ".$_SESSION['user_id']);
  $res = $con -> query($sql) or die("ERROR0 : ".$con->error);
  $row = $res->fetch_array();
  $refs = $res->num_rows;
  */
$sql = "select 
  concat(format(sum(amount),2),' &euro;') sum
  ,concat(format(abs(sum(if(code_id=9,amount,0))),2),' &euro;') inv
  ,concat(format(abs(sum(if(code_id=2,amount,0))),2),' &euro;') fee
  ,concat(format(sum(if(code_id=3,amount,0)),2),' &euro;') bonus,
  date_format(max(date),'%d.%m.%Y %H:%i') date
  from $t3
  where user_id=".$_SESSION['user_id'];
$res = $con -> query($sql) or die("ERROR2 : ".$con->error);
$bal = $res->fetch_array();
?>
                
    <div class="pagetitle">
      <h1>Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="?">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
          <!--li class="breadcrumb-item"><?php var_dump($get2); ?></li-->
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
                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Total</a></li>
                    <li><a class="dropdown-item" href="#">Available</a></li>
                    <li><a class="dropdown-item" href="#">Blocked</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Balance <span>| Total</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-cart"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?=$bal[0];?></h6>
                      <span class="text-success small pt-1 fw-bold">#</span> <span class="text-muted small pt-2 ps-1"><?=$bal[4];?></span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Balance Card -->

            <!-- Investment Card -->
            <div class="col-xxl-4 col-md-6">
              <div class="card info-card revenue-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">in work</a></li>
                    <li><a class="dropdown-item" href="#">blocked</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Invest <span>| in work</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-currency-dollar"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?=$bal[1];?></h6>
                      <!--span class="text-success small pt-1 fw-bold">8%</span> <span class="text-muted small pt-2 ps-1">increase</span-->

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Investment Card -->

            <!-- Referrals Card -->
            <div class="col-xxl-4 col-xl-12">

              <div class="card info-card customers-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Customers <span>| This Year</span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-people"></i>
                    </div>
                    <div class="ps-3">
                      <h6><?=$refs;?></h6>
                      <span class="text-danger small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">decrease</span>

                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Referrals Card -->



          </div>
        </div><!-- End Left side columns -->


      </div>
    </section>
