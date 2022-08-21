<?php
  include('partial/header.php');
?>
	<div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">新增選項</h6>
    </div>
  </div>
  <div class="card-columns">
    <div class="card" >
      <div class="card-body">
        <h5 class="card-title">部門</h5>
        <p class="card-text">Department</p>
      </div>
        <ul class="list-group list-group-flush" name = "showDepartment">
        </ul>
      <div class="card-body">
        <button type="button" class="btn btn-primary" data-id="部門" data-toggle="modal" data-target="#firstModel">新增</button>
      </div>
      <footer class="blockquote-footer">
        <small class="text-muted">
          按下新增按鈕後輸入新部門 <cite title="Source Title"></cite>
        </small>
      </footer>  
    </div>
    <div class="card p-3">
      <div class="card-body">
        <h5 class="card-title">職位</h5>
        <p class="card-text">Position</p>
      </div>
        <ul class="list-group list-group-flush" name = "showPosition">
        </ul>
      <div class="card-body">
        <button type="button" class="btn btn-primary" data-id="職位" data-toggle="modal" data-target="#firstModel">新增</button>
      </div>
      <footer class="blockquote-footer">
        <small class="text-muted">
          按下新增按鈕後輸入新職位 <cite title="Source Title"></cite>
        </small>
      </footer>      
    </div>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">性別</h5>
        <p class="card-text">Gender</p>
      </div>
        <ul class="list-group list-group-flush" name = "showGender">
        </ul>
      <div class="card-body">
        <button type="button" class="btn btn-primary" data-dismiss="modal" data-id="性別" data-toggle="modal" data-target="#firstModel">新增</button>
      </div>
      <footer class="blockquote-footer">
        <small class="text-muted">
          按下新增按鈕後輸入新性別 <cite title="Source Title"></cite>
        </small>
      </footer> 
    </div>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">婚姻狀態</h5>
        <p class="card-text">Marriage</p>
      </div>
        <ul class="list-group list-group-flush" name = "showMarriage">
        </ul>
      <div class="card-body">
        <button type="button" class="btn btn-primary" data-dismiss="modal" data-id="婚姻狀態" data-toggle="modal" data-target="#firstModel">新增</button>
      </div>
      <footer class="blockquote-footer">
        <small class="text-muted">
          按下新增按鈕後輸入新婚姻狀態 <cite title="Source Title"></cite>
        </small>
      </footer> 
    </div>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">投保公司</h5>
        <p class="card-text">Insured Company</p>
      </div>
        <ul class="list-group list-group-flush" name = "showInsuredCompany">
        </ul>
      <div class="card-body">
        <button type="button" class="btn btn-primary" data-dismiss="modal" data-id="投保公司" data-toggle="modal" data-target="#firstModel">新增</button>
      </div>
      <footer class="blockquote-footer">
        <small class="text-muted">
          按下新增按鈕後輸入新投保公司 <cite title="Source Title"></cite>
        </small>
      </footer> 
    </div>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">在職狀態</h5>
        <p class="card-text">Work Status</p>
      </div>
        <ul class="list-group list-group-flush" name = "showWorkStatus">
        </ul>
      <div class="card-body">
        <button type="button" class="btn btn-primary" data-dismiss="modal" data-id="在職狀態" data-toggle="modal" data-target="#firstModel">新增</button>
      </div>
      <footer class="blockquote-footer">
        <small class="text-muted">
          按下新增按鈕後輸入新在職狀態 <cite title="Source Title"></cite>
        </small>
      </footer> 
    </div>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">員工類型</h5>
        <p class="card-text">Staff Type</p>
      </div>
        <ul class="list-group list-group-flush" name = "showStaffType">
        </ul>
      <div class="card-body">
        <button type="button" class="btn btn-primary" data-dismiss="modal" data-id="員工類型" data-toggle="modal" data-target="#firstModel">新增</button>
      </div>
      <footer class="blockquote-footer">
        <small class="text-muted">
          按下新增按鈕後輸入新員工類型 <cite title="Source Title"></cite>
        </small>
      </footer> 
    </div>
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">就學狀態</h5>
        <p class="card-text">Education Condition</p>
      </div>
        <ul class="list-group list-group-flush" name = "showEducationCondition">
        </ul>
      <div class="card-body">
        <button type="button" class="btn btn-primary" data-dismiss="modal" data-id="就學狀態" data-toggle="modal" data-target="#firstModel">新增</button>
      </div>
      <footer class="blockquote-footer">
        <small class="text-muted">
          按下新增按鈕後輸入新就學狀態 <cite title="Source Title"></cite>
        </small>
      </footer> 
    </div>
  </div>
  </div>


  

    <!-- Modal -->
  <div class="modal fade" id="firstModel" tabindex="-1" role="dialog" aria-labelledby="showModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" name="showModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" name="showText">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
          <button type="button" class="btn btn-primary" name = "firstBtn" data-toggle="modal" data-target="#lastModel">送出</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="lastModel" tabindex="-1" role="dialog" aria-labelledby="showFinalModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" name="showFinalModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body"  name = "messageModel">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" name ="closeButton">關閉</button>
          <button type="button" class="btn btn-primary" name = "lastButton">確定</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" name="deleteModalLabel"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body"  name = "messageModel">
          確認刪除此資料?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" name ="closeButton">關閉</button>
          <button type="button" class="btn btn-primary" name = "deleteButton">確定</button>
        </div>
      </div>
    </div>
  </div>

<?php
  include('partial/footer.php');
?>
	<script src="/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="/vendor/datatables/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
	$(function(){
    $('#firstModel').on('show.bs.modal',function(e){
      var addType = $(e.relatedTarget).data('id');
      $('[name=showModalLabel]').empty();
      $('[name=showModalLabel]').append('新增'+ addType);
      console.log( $(e.relatedTarget).data('id'));
       $('[name=showText]').empty();
      $('[name=showText]').append('<input required type="text" class="form-control form-control-user" name="addItem" placeholder="'+ addType +'名稱">');
      $("button[name=firstBtn]").on('click', function(){
        var newItem= $("[name=addItem]").val();
        $.ajax({
          url:'/management/item/add/post',
          type:'post',
          data:{data:JSON.stringify({
              type : addType, 
              item : newItem
          })},
          dataType:'json',
          success:function(data){    
            if(data.status == "success"){
              $('[name=closeButton]').hide();
              $('[name=lastButton]').show();
            }else{
              $('[name=lastButton]').hide();
              $('[name=closeButton]').show();
            }
            $('[name=messageModel]').append(data.message);
          } 
        });
        
      });
    
    });

    $("button[name=lastButton]").on('click', function(e){
      window.location.href='/management/add'; 
    });

    $('#deleteModel').on('show.bs.modal',function(e){
      var deleteType = $(e.relatedTarget).data('type');
      var deleteValue = $(e.relatedTarget).data('id');
      console.log(deleteType+" "+deleteValue);
      $("button[name=deleteButton]").on('click', function(e){
        $.ajax({
          url:'/management/item/delete/post',
          type:'post',
          data:{data:JSON.stringify({
              type : deleteType, 
              item : deleteValue
          })},
          dataType:'json',
          success:function(data){    
            window.location.href='/management/add'; 
          } 
        });
      });
    });

    $.ajax({
        url:'/staff/department/get',
        type:'get',
        dataType:'json',
        success:function(response){
          $(response).each(function(eachid){
            $('[name=showDepartment]').append('<li class="list-group-item">'+this.department_id+'\t'+this.department_name+'<button type="button" class="btn  btn-xs pull-right"  data-toggle="modal" data-target="#deleteModel" data-type="department" data-id="'+this.department_name+'" ><i class="fas fa-trash"></i></button>');
            
          });
        } 
      });

    $.ajax({
        url:'/staff/position/get',
        type:'get',
        dataType:'json',
        success:function(response){
          $(response).each(function(eachid){
            $('[name=showPosition]').append('<li class="list-group-item">'+this.position_id+'\t'+this.position_name+'<button type="button" class="btn  btn-xs pull-right"  data-toggle="modal" data-target="#deleteModel" data-type="position" data-id="'+this.position_name+'" ><i class="fas fa-trash"></i></button>');
            
          });
        } 
      });

    $.ajax({
        url:'/staff/gender/get',
        type:'get',
        dataType:'json',
        success:function(response){
          $(response).each(function(eachid){
            $('[name=showGender]').append('<li class="list-group-item">'+this.type+'<button type="button" class="btn  btn-xs pull-right"  data-toggle="modal" data-target="#deleteModel" data-type="gender" data-id="'+this.type+'" ><i class="fas fa-trash"></i></button>');
            
          });
        } 
      });

    $.ajax({
        url:'/staff/marriage/get',
        type:'get',
        dataType:'json',
        success:function(response){
          $(response).each(function(eachid){
            $('[name=showMarriage]').append('<li class="list-group-item">'+this.type+'<button type="button" class="btn  btn-xs pull-right"  data-toggle="modal" data-target="#deleteModel" data-type="marriage" data-id="'+this.type+'" ><i class="fas fa-trash"></i></button>');
            
          });
        } 
      });

    $.ajax({
        url:'/staff/insuredcompany/get',
        type:'get',
        dataType:'json',
        success:function(response){
          $(response).each(function(eachid){
            $('[name=showInsuredCompany]').append('<li class="list-group-item">'+this.companyName+'<button type="button" class="btn  btn-xs pull-right"  data-toggle="modal" data-target="#deleteModel" data-type="insuredcompany" data-id="'+this.companyName+'" ><i class="fas fa-trash"></i></button>');
            
          });
        } 
      });

    $.ajax({
        url:'/staff/workStatus/get',
        type:'get',
        dataType:'json',
        success:function(response){
          $(response).each(function(eachid){
            $('[name=showWorkStatus]').append('<li class="list-group-item">'+this.status+'<button type="button" class="btn  btn-xs pull-right"  data-toggle="modal" data-target="#deleteModel" data-type="workStatus" data-id="'+this.status+'" ><i class="fas fa-trash"></i></button>');
            
          });
        } 
      });
    $.ajax({
        url:'/staff/staffType/get',
        type:'get',
        dataType:'json',
        success:function(response){
          $(response).each(function(eachid){
            $('[name=showStaffType]').append('<li class="list-group-item">'+this.type+'<button type="button" class="btn  btn-xs pull-right"  data-toggle="modal" data-target="#deleteModel" data-type="staffType" data-id="'+this.type+'" ><i class="fas fa-trash"></i></button>');
            
          });
        } 
      });

    $.ajax({
        url:'/staff/educationCondition/get',
        type:'get',
        dataType:'json',
        success:function(response){
          $(response).each(function(eachid){
            $('[name=showEducationCondition]').append('<li class="list-group-item">'+this.type+'<button type="button" class="btn  btn-xs pull-right"  data-toggle="modal" data-target="#deleteModel" data-type="educationCondition" data-id="'+this.type+'" ><i class="fas fa-trash"></i></button>');
            
          });
        } 
      });

		

    
  });
</script>