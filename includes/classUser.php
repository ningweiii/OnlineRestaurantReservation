<?php
require_once "classDatabase.PDO.php";

class User{
	private static $database;
	private $userId;
	private $username;
	private $password;
	private $email;
	private $hash;

	
	function __construct($username,$password,$email, $hash=null,$userId = null)
	{
		$this->userId=$userId;
		$this->username = $username;
		$this->password = $password;
		$this->email = $email;
		$this->hash = $hash;

	}

	private static function init_database(){
		if(!isset(self::$database)){
			self::$database = new Database("restaurant_reservation_db");
		}
	}

	public static function Create_Hash($password)
	{
		$random = MD5($password);
		$salt = Substr($random, 0, 22);
		$hash = '$2y$10$';
		return $hash.$salt;

	}

	public static function Get_hash_by_username($username){
		self::init_database();
		$connection = self::$database->GetConnection();
		try{
			$query = "SELECT * FROM users WHERE username = '$username' ";
			$stmt = $connection->prepare($query);
			$stmt->execute();
			$userObj = $stmt->fetch(PDO::FETCH_OBJ);
			
			if($userObj)
			{
				return $userObj->hash;

			}
			
		}catch(PDOException $e){
			echo "Query Failed ".$e->getMessage();
		}
	}

	public static function Get_hash_by_email($email){
		self::init_database();
		$connection = self::$database->GetConnection();
		try{
			$query = "SELECT * FROM users WHERE email = '$email' ";
			// echo $query;
			$stmt = $connection->prepare($query);
			$stmt->execute();
			$userObj = $stmt->fetch(PDO::FETCH_OBJ);
			if($userObj)
			{
				return $userObj->hash;

			}			
		}catch(PDOException $e){
			echo "Query Failed ".$e->getMessage();
		}
	}

	public function Create(){
		$this->hash=self::Create_Hash($this->password);
		$this->password=crypt($this->password, $this->hash);
		self::init_database();
		$connection = self::$database->GetConnection();

		try{
			$query  = "INSERT INTO users(username, password, email,hash) ";
			$query .= " VALUES(?, ?, ?, ?)";
		
			$stmt = $connection->prepare($query);
			$stmt->bindParam(1,$this->username);
			$stmt->bindParam(2,$this->password);
			$stmt->bindParam(3,$this->email);
			$stmt->bindParam(4,$this->hash);
			
			$stmt->execute();
			
			return $connection-> lastInsertId();
			
		}
		catch(PDOException $e){
			echo "Query Failed ".  $e->getMessage();
		}

	}

	public static function Username_Exists($username){
		self::init_database();
		$connection = self::$database->GetConnection();
		try{
			$query = "SELECT userId FROM users WHERE username = '$username' ";
            $stmt = $connection->prepare($query);	
			$stmt->execute();
			$userObj = $stmt->fetch(PDO::FETCH_OBJ);
			
			return !empty($userObj->userId);
			
		}catch(PDOException $e){
			echo "Query Failed ".$e->getMessage();
		}
	}
	
	public static function Email_Exists($email){
		self::init_database();
		$connection = self::$database->GetConnection();
		try{
			$query = "SELECT userId FROM users WHERE email = '$email' ";
            $stmt = $connection->prepare($query);	
			$stmt->execute();
			$userObj = $stmt->fetch(PDO::FETCH_OBJ);
			
			return !empty($userObj->userId);
			
		}catch(PDOException $e){
			echo "Query Failed ".$e->getMessage();
		}
	}



	//if the username match password
	public static function User_Exists_UserName($username , $password){
		self::init_database();
		$connection = self::$database->GetConnection();
		$hash = self::Get_hash_by_username($username);
		$encrypted = crypt($password , $hash);
		try{
			$query = "SELECT userId FROM users ";
			$query .= "WHERE username = '$username' AND password = '$encrypted'";
			
			$stmt = $connection->prepare($query);
			$stmt->execute();
			$userObj = $stmt->fetch(PDO::FETCH_OBJ);
			
			return !empty($userObj->userId);
		}catch(PDOException $e){
			echo "Query Failed ".$e->getMessage();
		}
	}

		//if the email match password
		public static function User_Exists_Email($email , $password){
			self::init_database();
			$connection = self::$database->GetConnection();
			$hash = self::Get_hash_by_email($email);
			$encrypted = crypt($password , $hash);
			try{
				$query = "SELECT userId FROM users ";
				$query .= "WHERE email = '$email' AND password = '$encrypted'";
				
				$stmt = $connection->prepare($query);
				$stmt->execute();
				$userObj = $stmt->fetch(PDO::FETCH_OBJ);
				
				return !empty($userObj->userId);
			}catch(PDOException $e){
				echo "Query Failed ".$e->getMessage();
			}
		}
	

		public static function Update_Password($username , $new_password){
			self::init_database();
			$connection = self::$database->GetConnection();
			
			$hash = self::Create_Hash($new_password);
			$new_encrypted = crypt($new_password, $hash);
			try{
				$connection->beginTransaction();
				$query = "Update users Set password = '$new_encrypted' WHERE username = '$username' ";
				$connection->exec($query);
				$query = "Update user Set Salt = '$salt' WHERE username = '$username' ";
				$connection->exec($query);
				$connection->commit();
			}catch(PDOException $e){
				echo "Query Update Failed ".$e->getMessage();
				$connection->rollback();
			}
		}

	public static function ReadUsers(){
		$arrayUsers = array();
		self::init_database();
		$connection = self::$database->GetConnection();
		$sql  = "SELECT * FROM users";
		$result = $connection->query($sql);
		if($result->num_rows > 0){
			while($row = $result->fetch_assoc()){
				array_push($arrayUsers ,  $row);
				// print_r($row);
				// $arrayCars += [$row['Make'] => $row];
			}
		}
		return $arrayUsers;
	}

}


?>