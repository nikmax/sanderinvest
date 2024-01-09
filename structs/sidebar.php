  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">
    <?php
      foreach ($nav['items'] as $item) {
        $name  = $item['name'];
        $class = $item['class'];
        switch ($item['type']) {
          case 'nav-item':
            $path = $item['path'];
            $id = $name.'-nav';
            $collapsed2 = 'collapsed'; // ''
            $aria = 'false';          // 'true'
            $show = '';               // 'show'

            if(isset($item['items'])){
                      $echo2 = '';
                      foreach ($item['items'] as $navitem) {
                        if($navitem['file']==$file){
                          $collapsed2 = '';
                          $collapsed = '';
                          $aria = 'true';
                          $show = 'show';
                          $active = 'class="active"';
                        }else{
                          $collapsed = 'collapsed'; // ''
                          $active = '';
                        }
                        $echo2 .= '<li><a href="'.$path.$navitem['file'].'" '.$active.'>
                                <i class="bi bi-circle"></i><span>'.$navitem['name'].'</span>
                               </a></li>';
                      }
                      $echo1 = '<li class="nav-item">
                            <a class="nav-link '.$collapsed2.'" data-bs-target="#'.$id.'" data-bs-toggle="collapse" href="#" aria-expanded="'.$aria.'">
                              <i class="'.$class.'"></i><span>'.$name.'</span><i class="bi bi-chevron-down ms-auto"></i>
                            </a>
                        <ul id="'.$id.'" class="nav-content collapse '.$show.'" data-bs-parent="#sidebar-nav">';

                      $echo3 = '</ul></li><!-- End '.$name.' Nav -->';
                      echo $echo1.$echo2.$echo3;
            }else{
              $path .= $item['file'];
              if(strpos($path,$file)){
                $collapsed2 = '';
              }
                      echo '<li class="nav-item">
                              <a class="nav-link '.$collapsed2.'" href="'.$path.'">
                                <i class="'.$class.'"></i><span>'.$name.'</span>
                              </a>
                            </li><!-- End '.$name.' Page Nav -->';
            }
            break;
          case 'nav-heading':
            echo '<li class="nav-heading '.$class.'">'.$name.'</li>';
            break;
          default:
        } 
      }
              
      ?>
    </ul>
  </aside><!-- End Sidebar-->