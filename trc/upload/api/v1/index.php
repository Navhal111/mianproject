<?php
session_start();
error_reporting();
ini_set('display_errors', 'On');
// set_error_handler("var_dump");
// extension=php_mbstring.dll
require_once 'vendor/autoload.php';
require_once 'vendor/silalahi/slim-logger/Logger.php';
use Slim\LogFileWriter;
use Slim\Middleware\SessionCookie;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Tracy\Debugger;
require_once 'config.php';
// require_once 'dompdf/autoload.inc.php';
// use Dompdf\Dompdf;
date_default_timezone_set("Asia/Kolkata");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');


$app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);

$app->add(new Silalahi\Slim\Logger(
  [
  'path' => 'log/'
  ]
));

$app->add(new \Slim\Middleware\Session());


$app->get('/', function($request, $response, $args){
  $tokenExpiration = date('Y-m-d');
  $day = date("l", strtotime($tokenExpiration));
  $msg = array('success' => 1,'day'=>$day,'Time'=>$tokenExpiration);
  return $response->withJson($msg);

});
$app->get('/ritesh', function($request, $response, $args){
  $tokenExpiration = date('Y-m-d');
  $day = date("l", strtotime($tokenExpiration));
  $msg = array('success' => 1,'day'=>$day,'Time'=>$tokenExpiration);
  $conn = getConnection();
  return $response->withJson($msg);

});

$app->post('/admin_login', function ($request, $response, $args){
  $admin_login = $request->getParsedBody();
  // $file = fopen("suerveyanswer.txt","w");
  // echo fwrite($file,json_encode($admin_login));
  // fclose($file);
  $conn = getConnection();
  $pwd_hash = hash('sha512', $admin_login['password']);
  $result = $conn->prepare("SELECT id, username FROM admin_user WHERE username= ? AND password= ? AND status=1");
  $result->bind_param('ss', $admin_login['username'], $pwd_hash);
  $result->execute();
  $result->store_result();
  $result->bind_result($id,$username);
  $result->fetch();

  if($result->num_rows == 1){
    $token = login_user();
    if($conn->query("UPDATE admin_user SET token='".$token['token']."', token_exp='".$token['exptime']."' WHERE id=".$id."")){
      mysqli_close($conn);
      return $response->withJson(array(
        'success' => 1,
        "msg" => "Login Successfull...",
        "token" => $token['token'],
        "user_id" => $id,
        "email" => $username
      ));
    }else{
      mysqli_close($conn);
      return $response->withJson(array(
        'success' => 0,
        "msg" => "Login Failed..."
      ));
    }
  }else{
    mysqli_close($conn);
    return $response->withJson(array(
      'success' => 0,
      "msg" => "Login Failed..."
    ));
  }
});


require_once 'customer.php';

$app->run();
?>
