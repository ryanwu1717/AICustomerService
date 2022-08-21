var url = new URL(window.location.href);
var UID = url.searchParams.get("id");
var userName = '';
var tmpUrl = 'http://localhost:8084'
$('#basicModal').on('show.bs.modal',function(e){
  // $('#exampleModal .modal-footer').html(basicModalFooter);
  var type = $(e.relatedTarget).data('type');
  if(type=='writeName'){
    writeName();
    // deleteUser( $(e.relatedTarget).data('id'));
  }
  if(type=='failed'){
    failedIn();
  }

});
$(function() {
	if(UID == null){
		// newUser();
	    $('#btnWriteName').click();

	}else{
		login(UID);
	}

	
});

function newUser(tmp){
	$.ajax({
   		url:`/user`,
	    type:'post',
	    data:{
              name : tmp
      	},
	    success:function(response){

	    	tmpdata = JSON.parse(response);
	    	userName = tmpdata.name;
			console.log(tmpdata)
	      	
	      	if(tmpdata.status == 'success'){
				$('#basicModal .modal-title').html(`${userName}歡迎使用`);
				$('#basicModal .modal-body').html(`${tmpUrl+'?id='+tmpdata.id}`);
				$('#basicModal .modal-footer').html(`<button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" id="">確認</button>`);


	      	}
	      	$('#basicModal').on('hide.bs.modal',function(e){
				window.location.replace(tmpUrl+'?id='+tmpdata.id);

	      	});
	      
	    }
	 });

}

function login(id){
	$.ajax({
   		url:`/user/${UID}`,
	    type:'post',
	    success:function(response){
	     	console.log(response)

	    	tmpdata = JSON.parse(response);
	     	console.log(tmpdata.status)
	     	if(tmpdata.status == 'success'){
				window.location.replace('/home');

	     	}else{
	     		$('#failedBtn').click();
	     	}
	      // tmpChapterArr = [];
	      // inMake(response[0]);
	      
	    }
	 });
}

function failedIn(){
	$('#basicModal .modal-title').html('登入失敗');
	$('#basicModal .modal-body').html(`
		未存在此使用者<br />
		按下確定請重新註冊`);
	$('#basicModal .modal-footer').html(`
		<button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
        <button type="button" class="btn btn-primary"  id="reloadBtn">確認</button>`);
	$('#reloadBtn').unbind().on('click',function(){
		window.location.replace(tmpUrl);
	});
}

function writeName(){
	$('#basicModal .modal-title').html('輸入你的名子或暱稱?');
	$('#basicModal .modal-body').html(`
		<form id="writeForm">
		  <div class="form-group row">
		    <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
		    <div class="col-sm-10">
		      <input type="text" class="form-control" id="" data-type="nickname"  placeholder="ex.王曉明" required>
		    </div>
		  </div>
		  <button type="submit" style="display:none" id="writeSubmitBtn" class="btn btn-primary">Submit</button>
		</form>`);
	$('#basicModal .modal-footer').html(`
		<button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
        <button type="button" class="btn btn-primary" id="btnAddName">確認</button>`);


	$('#btnAddName').unbind().on('click',function(){
		$('#writeSubmitBtn').click();
	});
	$('#writeForm').unbind().on('submit',function(){
	    event.preventDefault();
	    userName = $('input[data-type="nickname"]').val();
	    newUser(userName);
	});
}