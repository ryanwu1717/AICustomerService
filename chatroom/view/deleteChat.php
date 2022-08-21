<?php
  include('partial/header.php');
?>
	<div class="card-body">
		<form>
      <div class="row">
        <div class="col-md-12">
        	<div class="text-center">
            <h1  class="font-weight-bold" name = "typeUpdate">刪除訊息</h1>
           </div>
        </div>
        <div class="col-md-1"></div>
        <label class="col-form-label col-md-2">刪除時間</label>
        <div class="col-md-6">
          <input required="required"  type="date" class="form-control"name="inputDeleteDate" > 
        </div>
        <div class="col-md-1"></div>
        <button type="" class="btn btn-danger col-md-2" name="btnDeleteTime">
        	<!-- <i class="far fa-trash-alt"></i> -->
        	<span>刪除</span>
        </button>
      </div>
     </form>
	</div>
<?php
  include('partial/footer.php');
?>

<script type="text/javascript">
	$("button[name=btnDeleteTime]").on('click', function(){
      var deleteTime = $("[name=inputDeleteDate]").val();
      $.ajax({
        url:'/management/chat',
        type:'POST',
        data:{
          data:JSON.stringify({time : deleteTime}),_METHOD:'delete'
        },
        dataType:'json',
        success:function(response){
          // window.location.href='/management/table'; 
        }
      });
   });
</script>

