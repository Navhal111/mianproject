<?php
$app->post('/customer_add', function ($request, $response, $args){
  $admin_login = $request->getParsedBody();
  // $file = fopen("suerveyanswer.txt","w");
  // echo fwrite($file,json_encode($admin_login));
  // fclose($file);
  $check = cheak_token_user($admin_login['id'],$admin_login['token']);
  if($check['success']==0){
    return $response->withJson(array(
      'success' => 0,
      "msg" => "Not Authorise",
    ));
  }
  $conn = getConnection();
  $now = date('Y-m-d');
  if($admin_login['status'] == "add"){
    if($result=$conn->query("SELECT id FROM customer WHERE customer_email='".$admin_login['customer_email']."'")){
          if($result->num_rows == 1){
            mysqli_close($conn);
            return $response->withJson(array(
              'success' => 0,
              "msg" => "Customer alredy exist",
            ));
          }
    }
    if($conn->query('INSERT INTO customer (admin_id,customer_name,customer_email,customer_phone,customer_state,customer_add,customer_pan,customer_gst,created_at)VALUES ('.$admin_login["id"].',"'.$admin_login["customer_name"].'","'.$admin_login["customer_email"].'","'.$admin_login["customer_phone"].'","'.$admin_login["customer_state"].'","'.$admin_login["customer_add"].'","'.$admin_login["customer_PanCard"].'","'.$admin_login["customer_gst"].'","'.$now.'")')){
      mysqli_close($conn);
      return $response->withJson(array(
        'success' => 1,
        "msg" => "Successfuly Add Customer",
      ));
    }else{
      mysqli_close($conn);
      return $response->withJson(array(
        'success' => 0,
        "msg" => 'Something Went Wrong'
      ));
    }
  }else{
    if($conn->query('UPDATE customer SET admin_id='.$admin_login["id"].',customer_name="'.$admin_login["customer_name"].'",customer_email="'.$admin_login["customer_email"].'",customer_phone="'.$admin_login["customer_phone"].'",customer_state="'.$admin_login["customer_state"].'",customer_add="'.$admin_login["customer_add"].'",customer_pan="'.$admin_login["customer_PanCard"].'",customer_gst="'.$admin_login["customer_gst"].'" WHERE id = '.$admin_login['edit_id'].' AND admin_id='.$admin_login['id'].' ')){
      mysqli_close($conn);
      return $response->withJson(array(
        'success' => 1,
        "msg" => "Successfuly update Customer",
      ));
    }else{
      mysqli_close($conn);
      return $response->withJson(array(
        'success' => 0,
        "msg" => 'Not update customer'
      ));
    }
  }
  mysqli_close($conn);
  return $response->withJson(array(
    'success' => 0,
    "msg" => 'wrong'
  ));
});
$app->post('/customer_list', function($request, $response, $args){
  $admin_login = $request->getParsedBody();
  // $file = fopen("suerveyanswer.txt","w");
  // echo fwrite($file,json_encode($admin_login));
  // fclose($file);
  $check = cheak_token_user($admin_login['id'],$admin_login['token']);
  if($check['success']==0){
      mysqli_close($conn);
    return $response->withJson(array(
      'success' => 0,
      "msg" => "Not Authorise",
    ));
  }
  $conn = getConnection();
  if($list_result= $conn->query("SELECT * from customer WHERE status = 1 ")){
    if($list_result->num_rows <= 0){
      mysqli_close($conn);
      return $response->withJson(array(
        'success' => 0,
        "msg" => "NO customer listed",
      ));
    }
    while($row = $list_result->fetch_assoc()) {
          $module_permition[] =$row;
      }
        mysqli_close($conn);
      return $response->withJson(array(
        'success' => 1,
        "data" =>$module_permition,
      ));
  }else{
    mysqli_close($conn);
    return $response->withJson(array(
      'success' => 0,
      "msg" => "Something went Wrong",
    ));
  }

});

$app->post('/customer_remove', function ($request, $response, $args){
  $admin_login = $request->getParsedBody();
  // $file = fopen("suerveyanswer.txt","w");
  // echo fwrite($file,json_encode($admin_login));
  // fclose($file);
  $check = cheak_token_user($admin_login['id'],$admin_login['token']);
  if($check['success']==0){
    return $response->withJson(array(
      'success' => 0,
      "msg" => "Not Authorise",
    ));
  }
  $conn = getConnection();
  if($conn->query("UPDATE customer SET status=0 WHERE id=".$admin_login["customer_id"])){
      mysqli_close($conn);
      return $response->withJson(array(
        'success' => 1,
        "msg" => "Remove Customer",
      ));
  }
  mysqli_close($conn);
  return $response->withJson(array(
    'success' => 1,
    "msg" => "problam Remove",
  ));
});
$app->post('/customer_edit', function($request, $response, $args){
  $admin_login = $request->getParsedBody();
  // $file = fopen("suerveyanswer.txt","w");
  // echo fwrite($file,json_encode($admin_login));
  // fclose($file);
  $check = cheak_token_user($admin_login['id'],$admin_login['token']);
  if($check['success']==0){
      mysqli_close($conn);
    return $response->withJson(array(
      'success' => 0,
      "msg" => "Not Authorise",
    ));
  }
  $conn = getConnection();
  if($list_result= $conn->query("SELECT * from customer WHERE status = 1 AND id=".$admin_login["customer_id"])){
    if($list_result->num_rows <= 0){
      mysqli_close($conn);
      return $response->withJson(array(
        'success' => 0,
        "msg" => "NO customer listed",
      ));
    }
    while($row = $list_result->fetch_assoc()) {
          $module_permition[] =$row;
      }
        mysqli_close($conn);
      return $response->withJson(array(
        'success' => 1,
        "data" =>$module_permition,
      ));
  }else{
    mysqli_close($conn);
    return $response->withJson(array(
      'success' => 0,
      "msg" => "Something went Wrong",
    ));
  }

});

?>
