<?php 

  class Entity {

    private $_CMD_INSERT = 'INSERT INTO <table_name> (<columns>) VALUES (<attributes>);';
    private $_CMD_UPDATE = 'UPDATE <table_name> SET <columns> WHERE id = ?;';
    private $_CMD_DELETE = 'DELETE FROM <table_name> WHERE id = ?;';
    private $_CMD_SELECT_SINGLE_ROW = 'SELECT <columns> FROM <table_name> WHERE';
    public $_TYPE_LIST;

    public $id = -1;

    function __construct($type_list) {
        
        $this->_TYPE_LIST = $type_list;
        
        $this->generateInsertStatement();
        $this->generateUpdateStatement();     
        $this->generateDeleteStatement();     
        $this->generateSingleRowSelectStatement();

    }    
    #------------------------------------------------
     private function generateInsertStatement() {

        $columns = "";    
        $attributes = "";
        
        foreach($this as $key => $value ) {
            if(strpos($key,"_")===False and ($key=="id")===false) {
              $columns .=  $key . ','; 
              $attributes .=  "?,";
            }
        }

        $columns = substr_replace($columns,'',strlen($columns)-1);
        $attributes = substr_replace($attributes,'',strlen($attributes)-1);        

        $this->_CMD_INSERT = str_replace('<columns>',$columns,$this->_CMD_INSERT);
        $this->_CMD_INSERT = str_replace('<table_name>',strtolower(get_class($this)),$this->_CMD_INSERT); 
        $this->_CMD_INSERT = str_replace('<attributes>',$attributes,$this->_CMD_INSERT); 

    }
    #------------------------------------------------
    private function generateSingleRowSelectStatement(){

        $columns = "";

        foreach($this as $key => $value) {
            if(strpos($key,"_")===false) {             
                  $columns .= $key . ",";  
            }
        }

        $columns = substr_replace($columns,'',strlen($columns)-1);

        $this->_CMD_SELECT_SINGLE_ROW = str_replace('<columns>',$columns,$this->_CMD_SELECT_SINGLE_ROW);
        $this->_CMD_SELECT_SINGLE_ROW = str_replace('<table_name>',strtolower(get_class($this)),$this->_CMD_SELECT_SINGLE_ROW); 

        $this->_CMD_SELECT_SINGLE_ROW .= " id = ?;"; 

    }
    #------------------------------------------------
    private function generatDetailRowsSelectStatement(){

        $columns = "";

        foreach($this as $key => $value) {
            if(strpos($key,"_")===false) {             
                  $columns .= $key . ",";  
            }
        }

        $columns = substr_replace($columns,'',strlen($columns)-1);

        $this->_CMD_SELECT_SINGLE_ROW = str_replace('<columns>',$columns,$this->_CMD_SELECT_SINGLE_ROW);
        $this->_CMD_SELECT_SINGLE_ROW = str_replace('<table_name>',strtolower(get_class($this)),$this->_CMD_SELECT_SINGLE_ROW); 

        $this->_CMD_SELECT_SINGLE_ROW .= " id = ?;"; 

    }
    #------------------------------------------------
     private function generateUpdateStatement() {

        $columns = "";    
        
        foreach($this as $key => $value ) {
            if(strpos($key,"_")===False and ($key=="id")===false) {
              $columns .=  $key . " = ?,";               
            }
        }

        $columns = substr_replace($columns,'',strlen($columns)-1);
       
        $this->_CMD_UPDATE = str_replace('<columns>',$columns,$this->_CMD_UPDATE);
        $this->_CMD_UPDATE = str_replace('<table_name>',strtolower(get_class($this)),$this->_CMD_UPDATE); 

     }
    #------------------------------------------------
     private function generateDeleteStatement() {

        $this->_CMD_DELETE = str_replace('<table_name>',strtolower(get_class($this)),$this->_CMD_DELETE); 

     }
    #------------------------------------------------
     public function dbInsert() {

        $mysqli = $_SESSION["db_conn"]; 

        $a_params[] = & $this->_TYPE_LIST;

        #error_log($this->_TYPE_LIST);

        $value_list = array();

        #error_log($this->_CMD_INSERT);

        if($stmt = $mysqli->prepare($this->_CMD_INSERT)){        
            foreach($this as $key => $value ) {   
                if(strpos($key,"_")===false and ($key=="id")===false) {             
                    error_log($key . "->" . $value);
                    $value_list[$key] = $value;    
                    $a_params[] = & $value_list[$key];             
                }
            }        
                        
            call_user_func_array(array($stmt, 'bind_param'), $a_params);

            if($stmt->execute()){
                error_log("OK Saved " . $this->_CMD_INSERT);
                $this->id = $_SESSION["db_conn"]->insert_id;
            }
            else  {
                error_log("NOT Saved " . $this->_CMD_INSERT);
            }
        }
     }
     #------------------------------------------------
     public function dbUpdate() {

        $mysqli = $_SESSION["db_conn"]; 

        $type_list = $this->_TYPE_LIST . "i";

        $a_params[] = & $type_list;

        $value_list = array();

        if($stmt = $mysqli->prepare($this->_CMD_UPDATE)){        
            foreach($this as $key => $value ) {   
                if(strpos($key,"_")===false and ($key=="id")===false) {             
                    $value_list[$key] = $value;    
                    $a_params[] = & $value_list[$key];             
                }              
            }    
            
            $value_list["id"] = $this->id;
            $a_params[] = & $value_list["id"];
                        
            call_user_func_array(array($stmt, 'bind_param'), $a_params);

            if($stmt->execute()){
                error_log("OK Updated " . $this->_CMD_UPDATE);
            }
            else  {
                error_log("NOT Updated " . $this->_CMD_UPDATE);
            }
        }    
     }
    #------------------------------------------------
    public function setValues() {
       
        foreach($this as $key => $value) {           
            if(strpos($key,"_")===False) {
                if(isset($_POST[$key]))  {
                    #error_log($key . "->" . $_POST[$key]);
                    $this->$key = $_POST[$key];
                    unset($_POST[$key]);
                }
                }
        }
     }
     #------------------------------------------------
     public function dbSelect($id) {

        $mysqli = $_SESSION["db_conn"]; 
        
        if($stmt = $mysqli->prepare($this->_CMD_SELECT_SINGLE_ROW)){        
            
            mysqli_stmt_bind_param($stmt, "i", $id);
            
            $stmt->execute();
            
            $result = $stmt->get_result();
                
            if($result->num_rows > 0) {
                
                $row = $result->fetch_array(1);
                 
                foreach($this as $key => $value) {
                    
                    if(strpos($key,"_")===false){
                        $this->$key = $row[$key];                     
                    }
                }
            }            
            error_log("OK Selected " . $this->_CMD_SELECT_SINGLE_ROW);
            }
            else  {
                error_log("NOT Selected " . $this->_CMD_SELECT_SINGLE_ROW);
            }   
    }
    #------------------------------------------------
     public function dbSave() {
        if($this->id == -1) { 
            #error_log($this->id);
            $this->dbInsert(); }
         else { 
             $this->dbUpdate(); }
     }
     #------------------------------------------------
    public function dbDelete($id) {

        $mysqli = $_SESSION["db_conn"]; 

        $type_list = "i";

        $a_params[] = & $type_list;
    
        if($stmt = $mysqli->prepare($this->_CMD_DELETE)){        
            $a_params[] = & $id;
        }

        call_user_func_array(array($stmt, 'bind_param'), $a_params);

        if($stmt->execute()){
            error_log("OK Deleted " . $this->_CMD_DELETE);
            }
            else  {
                error_log("NOT Deleted " . $this->_CMD_DELETE);
            }
        }

  }

?>