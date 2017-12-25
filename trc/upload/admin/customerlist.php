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
  </head>
  <body>
    <?php require_once 'header1.php'; ?>

    <?php require_once 'sidebar.php'; ?>
    <div class="content">
    <div class="col-lg-12">
      <div class="panel panel-widget">
        <div class="panel-title">
        List Customer
        <ul class="panel-tools">
        <li><a href="<?php echo $admin_ip; ?>customer.php" class="btn btn-default"><i class="fa fa-plus-circle"></i>Create New customer</a></li>
      </ul>
        </div>
      <div class="panel-body table-responsive">

        <table class="table table-dic table-hover ">
          <tbody id = "sitelist">
          </tbody>
        </table>

      </div>
  </div>
  </div>
</div>

  </body>
  <script>
  $(document).ready(function(){
    var main_user={};
    main_user['id']="<?php echo $_SESSION['user_id'];?>"
    main_user['token']= "<?php echo $_SESSION['token'];?>"
          console.log(JSON.stringify( main_user , null, '\t'));
    $.ajax({
       type: 'POST',
       url: '<?php echo $backend_ip; ?>index.php/customer_list',
       data: main_user,
       dataType: 'json',
       success: function (res){
           if(res){
             if(res['success'] == 1){
              //  console.log(res);
               // console.log (JSON.stringify( res , null, '\t'));
              //  debugger;
              var AllSite;
              for(var i=0;i<res.data.length;i++){
                AllSite += "<tr>"+
                 "<td><i class='fa fa-folder-o'></i>"+res.data[i].customer_name+"</td>"+
                 "<td>"+res.data[i].customer_email+"</td>"+
                 "<td class='text-r'><a data-id='"+res.data[i].id+"' class='btn btn-rounded btn-option1 manage_site' >Edit Customer</a>&nbsp&nbsp&nbsp<a data-id='"+res.data[i].id+"' class='btn btn-rounded btn-danger site_id'>Remove</a></td>"+
               "</tr>";

              }
           $('#sitelist').html(AllSite);
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

     $(document).on('click', '.site_id', function(){

  var main_user={};
  main_user['id']="<?php echo $_SESSION['user_id'];?>"
  main_user['token']= "<?php echo $_SESSION['token'];?>"
  main_user['customer_id']= $(this).data("id");
      $.confirm({
          title: 'Remove!',
          content: 'Remove Customer!',
          buttons: {
              confirm: function () {
                $.ajax({
                   type: 'POST',
                   url: '<?php echo $backend_ip; ?>index.php/customer_remove',
                   data: main_user,
                   dataType: 'json',
                   success: function (res){
                       if(res){
                          console.log(res);
                         if(res['success'] == 1){
                           var loc = window.location;
                           var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
                           window.location.replace(pathName + "customerlist.php");
                         }else{
                           alert(res['msg']);
                         }
                       }else{
                         alert(res['msg']);
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
              },
              cancel: function () {
                  $.alert('Canceled!');
              },
          }
      });

  });
  $(document).on('click', '.manage_site', function(){

    var main_user={};
    main_user['user_id']="<?php echo $_SESSION['user_id'];?>"
    main_user['token']= "<?php echo $_SESSION['token'];?>"
    main_user['site_id']= $(this).data("id");
      var uid = $(this).data('id'); // get id of clicked row
      var token = $("input#token").val();
      var admin_id = $("input#admin_id").val();
      var url = 'customers.php';
      var form = $('<form action="' + url + '" method="POST">' +
        '<input type="text" name="editid" value="'+uid+'" />' +
        '</form>');
      $('body').append(form);
      form.submit();

    });

  });
  </script>
  <?php require_once 'script.php'; ?>
</html>
