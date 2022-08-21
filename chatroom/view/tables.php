<?php
  include('partial/header.php');
?>

          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">公司職員</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>中文姓名</th>
                      <th>職稱</th>
                      <th>部門</th>
                      <th>手機</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>ID</th>
                      <th>中文姓名</th>
                      <th>職稱</th>
                      <th>部門</th>
                      <th>手機</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                  <tbody name="tbody_inputData">
                    
                    
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

  <!-- Basic Modal-->
  <div class="modal fade" id="basicModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">關閉</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Delete Modal-->
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
  <!-- Page level plugins -->
  <script src="/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="/vendor/datatables/dataTables.bootstrap4.min.js"></script>


<script type="text/javascript">
  $(function(){

    $.ajax({
      url:'/table/getTable',
      type:'get',
      dataType:'json',
      success:function(response){
        $(response).each(function(){
          $('[name=tbody_inputData]').append(
            '<tr> <td>'+this.id+'</td> <td>'+
             this.name+'</td> <td>'+
             this.position+'</td> <td>'+
             this.department+'</td> <td>'+
             this.phonenumber+'</td> <td>'+
             '<button type="button" class="btn btn-primary" data-id="'+this.id+'" data-toggle="modal" data-target="#basicModal"><i class="far fa-eye"></i></button>'+
             '<button type="button" class="btn btn-success" onclick="window.location.href=\'<?=@$url?>/register?id='+this.id+'\'"><i class="fas fa-edit" name="updateButton" ></i></button>'+
             ' <button type="button" class="btn btn-danger" data-id="'+this.id+'" data-toggle="modal" data-target="#deleteModel"><i class="far fa-trash-alt"></i></button>  </td> </tr>');
        });
        $('#dataTable').DataTable({  
          language: {
            "emptyTable": "無資料...",
            "processing": "處理中...",
            "loadingRecords": "載入中...",
            "lengthMenu": "顯示 _MENU_ 項結果",
            "zeroRecords": "沒有符合的結果",
            "info": "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
            "infoEmpty": "顯示第 0 至 0 項結果，共 0 項",
            "infoFiltered": "(從 _MAX_ 項結果中過濾)",
            "infoPostFix": "",
            "search": "搜尋:",
            "paginate": {
              "first": "第一頁",
              "previous": "上一頁",
              "next": "下一頁",
              "last": "最後一頁"
            },
            "aria": {
              "sortAscending": ": 升冪排列",
              "sortDescending": ": 降冪排列"
            }
          }
        });

      } 
    });
  });
  $('#basicModal').on('show.bs.modal',function(e){
    var staff_id = $(e.relatedTarget).data('id');
    $.ajax({
        url:'/table/profile/'+staff_id,
        type:'get',
        dataType:'json',
        success:function(response){
          $('#basicModal .modal-title').text('個人資料');
          var content = $('<div></div>');
          $.each(response.data,function(key,value){
            $(content).append('<div class="row"><label class="col-sm-4">'+key+'</label><div class="col-sm-8">'+value+'</div></div>')
          });
          $('#basicModal .modal-body').html(content);
        }
    });
  });

  $('#deleteModel').on('show.bs.modal',function(e){
    var staff_id = $(e.relatedTarget).data('id');
    $("button[name=deleteButton]").on('click', function(e){
      $.ajax({
          url:'/management/profile',
          type:'POST',
          data:{
            data:JSON.stringify({staff_id : staff_id}),_METHOD:'delete'
          },
          dataType:'json',
          success:function(response){
            window.location.href='/management/table'; 
          }
      });
    });
  });
</script>

