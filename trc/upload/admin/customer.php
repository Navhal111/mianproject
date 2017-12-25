<?php
require_once "config.php";
if(empty($_SESSION['user_id']) AND empty($_SESSION['token'])){
    header("Location:index.php");
}
 ?>
<!DOCTYPE html>
<html>
  <head>
    <?php require_once 'header.php'; ?>
      <title>Hugo CMS Dashboard</title>
      <script type="text/javascript" src="js/jquery.min.js"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
  </head>
  <body>
    <?php require_once 'header1.php'; ?>

    <?php require_once 'sidebar.php'; ?>
    <div class="content">
    <div class="col-lg-6">
      <div class="panel panel-widget">
        <div class="panel-title">
        Add Customer
      </div>
      <div class="panel-body table-responsive">
        <div >
          <form class="fieldset-form" id="creat_customer">
            <input type="hidden" id="id" name="id" value="<?php echo  $_SESSION['user_id']; ?>" >
            <input type="hidden" id="token" name="token" value="<?php echo  $_SESSION['token']; ?>" >
            <input type="hidden" id="status" name="status" value="add" >
            <fieldset>
              <legend>Create New Customer</legend>
              <div class="form-group">
                <label for="example10"  class="form-label">Name</label>
                <input type="text" id="customer_name" name="customer_name" class="form-control">
              </div>
              <div class="form-group">
                <label for="example10"  class="form-label">Email</label>
                <input type="text" id="customer_email" name="customer_email" class="form-control">
              </div>
              <div class="form-group">
                <label for="example10"  class="form-label">Phone No</label>
                <input type="text" id="customer_phone" name="customer_phone" class="form-control">
              </div>
              <div class="form-group" id="match_text_div" >
                <label for="example10"  class="form-label">State</label><br>
                <select name="customer_state" id="customer_state"  data-style="btn-option2">
                    <option value="0">Gujrat</option>
                    <option value="1">Mharasstaa</option>
                </select>
              </div>
              <div class="form-group">
                <label for="example10"  class="form-label">Address</label>
                <input type="text" id="customer_add" name="customer_add" class="form-control">
              </div>
              <div class="form-group">
                <label for="example10"  class="form-label">PanCard NO.</label>
                <input type="text" id="customer_PanCard" name="customer_PanCard" class="form-control">
              </div>
              <div class="form-group">
                <label for="example10"  class="form-label">Gst NO.</label>
                <input type="text" id="customer_gst" name="customer_gst" class="form-control">
              </div>
              <br>
              <input type="submit" class="btn btn-default btn-block" value="ADD">
            </fieldset>
          </form>

        </div>

      </div>
    </div>
  </div>
</div>

  </body>
  <script>
  $(document).ready(function(){

    $('#creat_customer').validate({
        rules:{
          customer_name: {
            required: true,
          },
          customer_email:{
            required: true,
            email: true
          },
          customer_phone: {
            required: true,
            number: true,
            minlength:10,
            maxlength:10
          },
          customer_state: {
            required: true,
          },
          customer_gst: {
            required: true,
            minlength:15,
            maxlength:15

          }   ,
          customer_PanCard: {
            required: true,
            minlength:10,
            maxlength:10
          }   ,
          customer_add: {
            required: true,
          }
        },
        messages: {
          customer_email: {
            required: "<p style='color:red;'>Please enter Username...</p>",
            email: "<p style='color:red;'>Please enter valid email </p>"
          },
          customer_name: {
            required: "<p style='color:red;'>Please enter Name...</p>"
          },
          customer_phone: {
            required: "<p style='color:red;'>Please enter Phone...</p>",
            number: "<p style='color:red;'>Please enter valid Phone...</p>",
            minlength: "<p style='color:red;'>Please enter valid Phone...</p>",
            maxlength: "<p style='color:red;'>Please enter valid Phone...</p>"
          },
          customer_state: {
            required: "<p style='color:red;'>Please enter State...</p>"
          },
          customer_gst: {
            required: "<p style='color:red;'>Please enter GST no...</p>",
            minlength: "<p style='color:red;'>Please enter valid GST no...</p>",
            maxlength: "<p style='color:red;'>Please enter valid GST no...</p>"
          },
          customer_PanCard: {
            required: "<p style='color:red;'>Please enter PAN no...</p>",
            minlength: "<p style='color:red;'>Please enter valid PAN no...</p>",
            maxlength: "<p style='color:red;'>Please enter valid PAN no...</p>"
          },
          customer_add: {
            required: "<p style='color:red;'>Please enter Address...</p>"
          }

          },
          submitHandler: addcustomer
    });
  function addcustomer(){
    // e.preventDefault();
    // alert("das");
   var data = $( "#creat_customer" ).serializeArray();
   console.log(data);
    $.ajax({
       type: 'POST',
       url: '<?php echo $backend_ip; ?>index.php/customer_add',
       data: data,
       dataType: 'json',
       beforeSend: function(){
         $("#mybutton").html("<button class='btn btn-lg btn-primary btn-block'><i class='fa fa-spinner fa-spin'></i> Loading</button>");
       },
       success: function (res){
           if(res){
              // console.log(res);
             if(res['success'] == 1){
              //  alert('login');
              //  console.log(JSON.stringify( res , null, '\t'));
              alert(res.msg);
              var loc = window.location;
              var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
              window.location.replace(pathName + "customerlist.php");
             }else{
              alert(res.msg);
             }
           }else{
            alert("Plz...Login try letter...");
           }
       },
       complete: function(){
         $("#mybutton").html("");
         $("#mybutton").html("<input type='submit' value='Login' id='load' class='btn btn-lg btn-primary btn-block' />");
       },
       error: function(response, status, xhr){
           alert("Plz...Login try letter...");
       }
     });
   event.preventDefault();
  }
  });
  </script>
  <?php require_once 'script.php'; ?>
</html>
