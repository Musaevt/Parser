<?php
  // require_once 'include/common.php';
define("Q_PATH",dirname(__FILE__));

require_once Q_PATH.'/application/config/autoload/global.php';
\Autoloader\Autoload::init();
\Library\Application::init(require Q_PATH.'/application/config/application.config.php')->run();



// if(isset($_POST['function'])){  
//$Request= new API($_POST['function'],$_POST);
//$answ=$Request->send_request($connect, $tables);
//$str=json_encode($answ);
//echo $str;
// }
