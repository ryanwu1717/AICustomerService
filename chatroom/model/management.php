<?php
	Class Management{
		var $result;   
		var $conn;
		function __construct($db){
			$this->conn = $db;
		}
		function login()
		{ 
			$_POST=json_decode($_POST['data'],true);
		   	$loginStaffId = $_POST['loginStaffId'];
			$loginPassword = $_POST['loginPassword'];		 	
			$sql ="SELECT * FROM management.\"user\" WHERE account = :account and password = :password;";
			$sth = $this->conn->prepare($sql);
		   	$sth->bindParam(':account',$loginStaffId);
		   	$sth->bindParam(':password',$loginPassword);
			$sth->execute();
			$row = $sth->fetchAll();
			if(count($row)==1){
				$_SESSION['management']['id']=$row[0]['id'];
				$ack = array(
					'status' => 'success', 
				);
			}else{
				$ack = array(
					'status' => 'failed',
					'row'=>$row
				);
			}
			return $ack;
		} 

		function getName(){ 
			$staff_id = $_SESSION['management']['id'];
			$sql = "SELECT account as staff_name FROM management.\"user\" WHERE id = :id;";
			$sth = $this->conn->prepare($sql);
		   	$sth->bindParam(':id',$staff_id,PDO::PARAM_INT);
			$sth->execute();
			$row = $sth->fetchAll();
			return $row;
		} 
	}

	Class Tables{
		var $result;   
		var $conn;
		function __construct($db){
			$this->conn = $db;
		}
		function deleteStaff()
		{ 
			try{
				$_POST=json_decode($_POST['data'],true);		 	
				$sql ="UPDATE staff.staff
						SET staff_delete = true
						WHERE staff_id= :staff;";
				$sth = $this->conn->prepare($sql);
			   	$sth->bindParam(':staff',$_POST['staff_id']);
				$sth->execute();
				$row = $sth->fetchAll();
				$ack = array(
					'status' => 'success', 
				);
			}catch(PDOException $e){
				$ack = array(
					'status' => 'failed', 
					'message'=> $e
				);
			}	
			return $ack;
		} 
	}

	Class ChatManage{
		var $result;   
		var $conn;
		function __construct($db){
			$this->conn = $db;
		}
		function deleteChat(){
			$_POST=json_decode($_POST['data'],true);
			// var_dump($_POST['time']);
			try{
				$sql = 'DELETE FROM staff_chat."chatContent"
						WHERE "sentTime" < :deleteTime';
				$statement = $this->conn->prepare($sql);
				$statement->bindParam(':deleteTime',$_POST['time']);
				$statement->execute();
				$ack = array(
					'status' => 'success', 
					'message' => '成功'
				);

			}catch(PDOException $e)
			{
				$ack = array(
					'status' => 'failed'
				);
			}	
			return $ack;
		}
	}

	Class Add{
		var $conn;
		var $result; 
		function __construct($db){
			$this->conn = $db;
		}
		function deleteItem(){
			$_POST=json_decode($_POST['data'],true);
			switch($_POST['type']){
				case 'department':
					try{
						$sql = 'DELETE FROM staff_information.department
									WHERE department_name=:deleteItem; ';
						$statement = $this->conn->prepare($sql);
						$statement->bindParam(':deleteItem',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);

					}catch(PDOException $e)
					{
						$ack = array(
							'status' => 'failed', 
							'message'=> '資料重複'
						);
					}	
					return $ack;
				case 'position':
					try{
						$sql = 'DELETE FROM staff_information.position
									WHERE position_name=:deleteItem; ';
						$statement = $this->conn->prepare($sql);
						$statement->bindParam(':deleteItem',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);

					}catch(PDOException $e)
					{
						$ack = array(
							'status' => 'failed', 
							'message'=> '資料重複'
						);
					}	
					return $ack;
				case 'gender':
					try{
						$sql = 'DELETE FROM staff_information.gender
									WHERE type=:deleteItem; ';
						$statement = $this->conn->prepare($sql);
						$statement->bindParam(':deleteItem',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);

					}catch(PDOException $e)
					{
						$ack = array(
							'status' => 'failed', 
							'message'=> '資料重複'
						);
					}	
					return $ack;
				case 'marriage':
					try{
						$sql = 'DELETE FROM staff_information.marriage
									WHERE type=:deleteItem; ';
						$statement = $this->conn->prepare($sql);
						$statement->bindParam(':deleteItem',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);

					}catch(PDOException $e)
					{
						$ack = array(
							'status' => 'failed', 
							'message'=> '資料重複'
						);
					}	
					return $ack;
				case 'insuredcompany':
					try{
						$sql = 'DELETE FROM staff_salary.insuredcompany
									WHERE "companyName"=:deleteItem; ';
						$statement = $this->conn->prepare($sql);
						$statement->bindParam(':deleteItem',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);

					}catch(PDOException $e)
					{
						$ack = array(
							'status' => 'failed', 
							'message'=> '資料重複'
						);
					}	
					return $ack;
				case 'workStatus':
					try{
						$sql = 'DELETE FROM staff_salary."workStatus"
									WHERE status=:deleteItem; ';
						$statement = $this->conn->prepare($sql);
						$statement->bindParam(':deleteItem',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);

					}catch(PDOException $e)
					{
						$ack = array(
							'status' => 'failed', 
							'message'=> '資料重複'
						);
					}	
					return $ack;
				case 'staffType':
					try{
						$sql = 'DELETE FROM staff_salary."staffType"
									WHERE type=:deleteItem; ';
						$statement = $this->conn->prepare($sql);
						$statement->bindParam(':deleteItem',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);

					}catch(PDOException $e)
					{
						$ack = array(
							'status' => 'failed', 
							'message'=> '資料重複'
						);
					}	
					return $ack;
				case 'educationCondition':
					try{
						$sql = 'DELETE FROM staff_education.condition
									WHERE type=:deleteItem; ';
						$statement = $this->conn->prepare($sql);
						$statement->bindParam(':deleteItem',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);

					}catch(PDOException $e)
					{
						$ack = array(
							'status' => 'failed', 
							'message'=> '資料重複'
						);
					}	
					return $ack;
				default:
       			$ack = array(
							'status' => 'failed', 
							'message'=> $e
						);	
					return $ack;
			}
		}
		function addItem(){
			$_POST=json_decode($_POST['data'],true);
			switch ($_POST['type']){
				case '部門':
					try{
						$addsql = 'INSERT INTO staff_information.department(department_name)
								VALUES (:name);';
						$statement = $this->conn->prepare($addsql);
						$statement->bindParam(':name',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);		
						return $ack;
					}catch(PDOException $e){
						$ack = array(
							'status' => 'failed', 
							'message'=> '資料重複'
						);			
					}					
					return $ack;

				case '職位':
					try{
						$addsql = 'INSERT INTO staff_information.position(position_name)
								VALUES (:name);';
						$statement = $this->conn->prepare($addsql);
						$statement->bindParam(':name',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);		
						return $ack;
					}catch(PDOException $e){
						$ack = array(
							'status' => 'failed', 
							'message'=> '資料重複'
						);			
					}					
					return $ack;

				case '性別':
					try{			
						$sql = 'INSERT INTO staff_information.gender(type)
									VALUES (:name);';
						$statement = $this->conn->prepare($sql);
						$statement->bindParam(':name',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);
					}catch(PDOException $e)
					{
						$ack = array(
							'status' => 'failed', 
							'message'=> '資料重複'
						);
					}	
					return $ack;
				case '婚姻狀態':
					try{			
						$sql = 'INSERT INTO staff_information.marriage(type)
									VALUES (:name);';
						$statement = $this->conn->prepare($sql);
						$statement->bindParam(':name',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);
					}catch(PDOException $e)
					{
						$ack = array(
							'status' => 'failed', 
							'message'=> '資料重複'
						);
					}	
					return $ack;
				case '投保公司':
					try{			
						$sql = 'INSERT INTO staff_salary.insuredcompany("companyName")
									VALUES (:name);';
						$statement = $this->conn->prepare($sql);
						$statement->bindParam(':name',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);
					}catch(PDOException $e)
					{
						$ack = array(
							'status' => 'failed', 
							'message'=> '資料重複'
						);
					}	
					return $ack;
				case '在職狀態':
					try{			
						$sql = 'INSERT INTO staff_salary."workStatus"(status)
									VALUES (:name);';
						$statement = $this->conn->prepare($sql);
						$statement->bindParam(':name',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);
					}catch(PDOException $e)
					{
						$ack = array(
							'status' => 'failed', 
							'message'=>'資料重複'
						);
					}	
					return $ack;
				case '員工類型':
					try{			
						$sql = 'INSERT INTO staff_salary."staffType"(type)
									VALUES (:name);';
						$statement = $this->conn->prepare($sql);
						$statement->bindParam(':name',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);
					}catch(PDOException $e)
					{
						$ack = array(
							'status' => 'failed', 
							'message'=> '資料重複'
						);
					}	
					return $ack;
				case '就學狀態':
					try{				
						$sql = 'INSERT INTO  staff_education.condition(type)
									VALUES (:name);';
						$statement = $this->conn->prepare($sql);
						$statement->bindParam(':name',$_POST['item']);
						$statement->execute();
						$ack = array(
							'status' => 'success', 
							'message' => '新增成功'
						);
					}catch(PDOException $e)
					{
						$ack = array(
							'status' => 'failed', 
							'message'=> '資料重複'
						);
					}	
					return $ack;
				default:
       			$ack = array(
							'status' => 'failed', 
							'message'=> $e
						);
					}	
					return $ack;		
		}
	}
?>
