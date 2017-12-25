<?php

function getConnection(){
    $username = 'root';
    $password = 'temp123';
    $host = '192.168.99.100';
    $db = 'tcar_master';
    $port = 3309;
    $con = mysqli_connect($host,$username,$password,$db,$port) or die('not conection');
    return $con;
}

function sqlConnection(){
    $username = 'root';
    $password = 'temp123';
    $host = '192.168.99.100';
    $db = 'tcar_master';
    $port = 3309;
    $con = mysqli_connect($host,$username,$password,$db,$port) or die('not conection');
    return $con;
}

function login_user() {

                $arrRtn= bin2hex(openssl_random_pseudo_bytes(8)."KEY12345");
                $tokenExpiration = date('Y-m-d H:i:s', strtotime('+8 hour'));
                $token=array('token'=>$arrRtn,'exptime'=>$tokenExpiration);
                return $token;
  }

  function cheak_token_user($id,$token){
    $server_path ="http://192.168.0.3:8080/api/v1/";
        $conn = getConnection();
          try{
            if($result = $conn->prepare("SELECT token_exp from admin_user where id=? AND token = ? AND status =1 ")) {
              $result->bind_param('ss', $id,$token);
              $result->execute();
              $result->store_result();
              $now = date('Y-m-d H:i:s');
              $result->bind_result($token_exp);
              $result->fetch();
              if($result->num_rows == 1 && strtotime($now) < strtotime($token_exp)){
                 $result->close();
                 mysqli_close($conn);
                 return array('success'=> 1,'path'=>$server_path);
               }else{
                 $result->close();
                 mysqli_close($conn);
                 return json_encode(array('success'=> 0));
               }

             }
          }catch(Exception $e){
                  return array('success'=>0,'data'=>$e);
            }

    }


?>
