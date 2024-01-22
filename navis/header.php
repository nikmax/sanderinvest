<?php if(!isset($_SESSION['user_id'])) exit("falsch verbunden"); 


$sql = "select is_superuser from $t4 where
        is_superuser = 1 and id=".$_SESSION['user_id'];
$res = $con -> query($sql) or die("ERROR1 : ".$con->error);
if($res->num_rows != 0)
  if(isset($_GET['admin'])) $user ='<li>
            <a class="dropdown-item d-flex align-items-center" href="/">
                <i class="bi bi-person"></i><span>Privatzone</span></a></li>
            <li><hr class="dropdown-divider"></li>';
  else $user ='<li>
            <a class="dropdown-item d-flex align-items-center" href="?admin">
                <i class="bi bi-person"></i><span>adminzone</span></a></li>
            <li><hr class="dropdown-divider"></li>';
?>




<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="?home" class="logo d-flex align-items-center">
        <img src="assets/img/favicon.ico" alt="">
        <span class="d-none d-lg-block"><?=$company;?></span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <!--div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div--><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">
        <!--li class="nav-item d-block d-lg-none">
          <a class="nav-link nav-icon search-bar-toggle " href="#">
            <i class="bi bi-search"></i></a>
          </li--><!-- End Search Icon-->

        <li class="nav-item dropdown pe-3">
          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/user_icons/<?=$_SESSION['user'];?>.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2"><?=$_SESSION['user_name'];?></span>
            </a><!-- End Profile Iamge Icon -->

          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header"><h6><?=$_SESSION['user_name'];?></h6><span>you are the best!</span></li>
            <li><hr class="dropdown-divider"></li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="?users/profile">
                <i class="bi bi-person"></i><span>My Profile</span></a></li>
            <li><hr class="dropdown-divider"></li>

            <?= $user; ?>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="?logout">
                <i class="bi bi-box-arrow-right"></i>
                <span>Sign Out</span>
                </a></li>

          </ul>
        </li><!-- End Profile Nav -->
      </ul>
    </nav>
</header>
