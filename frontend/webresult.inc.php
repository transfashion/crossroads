<?

class WebResultErrorObject {
	public $ErrorId = "0";
	public $ErrorMessage = "";
	
	public function __construct($id, $message) {
		$this->ErrorId = $id;
		$this->ErrorMessage = $message;
	}
}



class WebResultObject {
	public $ObjectName;
	public $totalCount	= 0;
	public $success = true;
	public $errors;
	public $data = array();
	
	public function __construct($name) {
		$this->ObjectName = $name;
		$this->errors = new WebResultErrorObject("0", "");
	
	}
	
	
	public function Serialize() {
		return stripslashes(json_encode($this));
	}

}


?>