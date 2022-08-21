<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>SB Admin 2 - Login</title>

  <!-- Custom fonts for this template-->
  <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block" style="background:url(https://tem.fi/documents/1410877/2127184/Hahmot-maapallo/ebfc43d7-3349-43f6-a23b-1b28a9cce164?t=1456743096000)"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">歡迎回來!</h1>
                  </div>
                  <form class="user">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" name="inputStaffId" aria-describedby="emailHelp" placeholder="請輸入<?php if(is_null(@$placeholderAccount)) echo '員工編號'; else echo $placeholderAccount; ?>">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" name="inputPassword" placeholder="密碼">
                    </div>
                    <!-- <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                      </div>
                    </div> -->
                    <button type="button" class="btn btn-primary btn-user btn-block" name="loginButton">
                      登入
                    </button>
                  </form>
                  <!-- <hr> -->
                  <!-- <div class="text-center">
                    <a class="small" href="forgot-password.html">Forgot Password?</a>
                  </div> -->
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

  <!-- Modal -->
  <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="basicModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" >
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="/vendor/jquery/jquery.min.js"></script>
  <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="/js/sb-admin-2.min.js"></script>

</body>

</html>

<script type="text/javascript">
  $(function(){
    $('input').on('keyup',function(e){
      if(e.keyCode===13)
        $("button[name=loginButton]").click();
    });
    $("button[name=loginButton]").on('click', function(){
 
      var loginStaffId = $('[name=inputStaffId]').val();
      var loginPassword = $('[name=inputPassword]').val();

      $.ajax({
        url:'<?=@$url?>/user/login',
        type:'POST',
        data:{data:JSON.stringify({
            loginStaffId: loginStaffId,
            loginPassword: loginPassword
        })},
        success:function(data){
          if(data.status=='success'){
            window.location.href='<?=@$url?>/'; 
          }else{
            $('#basicModal .modal-title').text('錯誤');
            $('#basicModal .modal-body').text('帳號或密碼錯誤');
            $('#basicModal').modal('show');
          }
        },
        error:function(jqXHR, textStatus, errorThrown){
          console.log("failed");
        }
      }); 
    });   
  });
</script>
