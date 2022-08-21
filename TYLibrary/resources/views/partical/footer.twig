      <!-- Footer -->
      <!-- <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; LiWen 2019</span>
          </div>
        </div>
      </footer> -->
      <!-- End of Footer -->

    <!-- </div> -->
    <!-- End of Content Wrapper -->

  <!-- </div> -->
  <!-- End of Page Wrapper -->

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">確定要離開嗎?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">登出前，請確認!</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">取消</button>
          <a class="btn btn-primary" href="<?=@$url?>/login">登出</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Password Modal-->
  <div class="modal fade" id="passwordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">密碼修改</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-4 col-form-label">原密碼</label>
            <div class="col-sm-8">
              <input type="password" class="form-control" name="inputPasswordOrg">
              <div class="invalid-feedback">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-4 col-form-label">新密碼</label>
            <div class="col-sm-8">
              <input type="password" class="form-control" name="inputPasswordNew">
              <div class="invalid-feedback">
              </div>
            </div>
          </div>
          <div class="form-group row">
            <label for="inputPassword" class="col-sm-4 col-form-label">新密碼確認</label>
            <div class="col-sm-8">
              <input type="password" class="form-control" name="inputPasswordNewCheck">
              <div class="invalid-feedback">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">取消</button>
          <button class="btn btn-primary" name="buttonSubmit" type="button">確認</button>
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
  
  var url = new URL(window.location.href);
  $('li.nav-item').each(function(){
    $(this).find('a.nav-link').attr('href')==url.pathname?$(this).addClass('active'):$(this).removeClass('active');
  });
  $('#passwordModal [name=buttonSubmit]').unbind().on('click',function(){
    var data = new Object();
    $('#passwordModal input').each(function(){
      $(this).removeClass('is-invalid');
      data[this.name] = this.value;
    });
    $.ajax({
      url:'/user/password',
      type:'post',
      data:{data:JSON.stringify(data),_METHOD:'PATCH'},
      dataType:'json',
      success:function(response){
        if(response.status=='failed'){
          $('#passwordModal [name='+response.input+']').addClass('is-invalid');
          $('#passwordModal [name='+response.input+']').next().text(response.message); 
        }else{
          $('#passwordModal [name=buttonSubmit]').remove();
          $('#passwordModal .modal-body').text('修改完成\n請重新登入！');
          $('#passwordModal').on('hide.bs.modal',function(){
            window.location='/login';
          });
        }
      }
    });
  });
</script>