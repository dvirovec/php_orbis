<?php 

  class Entity {

    private $_CMD_INSERT;
    private $_CMD_UPDATE;
    private $_CMD_DELETE;
    private $_CMD_SELECT_SINGLE_ROW; 
    private $_CMD_SELECT_ALL;
    
    private $_db;
    
    private $_columns;
    private $_attributes;

    public  $id = -1;
    private $cid = -1;

    function __construct() {
        
        $this->_CMD_INSERT = CMD_INSERT;
        $this->_CMD_UPDATE = CMD_UPDATE;
        $this->_CMD_DELETE = CMD_DELETE;
        $this->_CMD_SELECT_SINGLE_ROW = CMD_SELECT_SINGLE_ROW; 
        $this->_CMD_SELECT_ALL = CMD_SELECT_ALL;

        $this->_db = $_SESSION["db_conn"];

        $this->generateStatement($this->_CMD_INSERT);
        $this->generateStatement($this->_CMD_UPDATE);
        $this->generateStatement($this->_CMD_DELETE);
        $this->generateStatement($this->_CMD_SELECT_SINGLE_ROW);
        $this->generateStatement($this->_CMD_SELECT_ALL);

    }    
    #-----------------------------------------------
    private function checkDb(){
        if($this->_db!==null) {
            error_log("Database ok ....!");
        } else {
            $this->_db = $_SESSION["db_conn"];
        }
    }
    #-----------------------------------------------
    private function processAttributes(){

        $this->_columns = "";    
        $this->_attributes = "";

        foreach($this as $key => $value ) {
            if(strpos($key,"_")===False and ($key=="id")===false) {
              $this->_columns .=  $key . ','; 
              $this->_attributes .=  ":" . $key .",";
            }
        }

        $this->_columns = substr_replace($this->_columns,'',strlen($this->_columns)-1);
        $this->_attributes = substr_replace($this->_attributes,'',strlen($this->_attributes)-1);        
    }
    #-----------------------------------------------
    private function processUpdateAttributes(){

        $this->_columns = "";    
        $this->_attributes = "";

        foreach($this as $key => $value ) {
            if(strpos($key,"_")===False and ($key=="id")===false) {
              $this->_columns .=  $key . ' = :' . $key . ',';               
            }
        }

        $this->_columns = substr_replace($this->_columns,'',strlen($this->_columns)-1);
          
    }
    #------------------------------------------------
     private function generateStatement(&$cmd) {

         if(strpos($cmd,"UPDATE")!==false) {
            $this->processUpdateAttributes();
         }
         else {
            $this->processAttributes();
        }

        $cmd = str_replace('<columns>',$this->_columns,$cmd);
        $cmd = str_replace('<table_name>',strtolower(get_class($this)),$cmd); 
        $cmd = str_replace('<attributes>',$this->_attributes,$cmd); 
    }
    #------------------------------------------------
    public function setSelectSQL($sql) {
        $this->_CMD_SELECT_SINGLE_ROW = $sql;
    }
    #------------------------------------------------
    public function setSelectAllSQL($sql) {
        $this->_CMD_SELECT_ALL = $sql;
    }
    #------------------------------------------------
    public function dbSave() {
        $this->checkDb();

        if(isset($_POST["id"])) $this->id = $_POST["id"];

        if($this->id == -1) { 
            $this->dbInsert(); }
         else { 
             $this->dbUpdate(); }
     }
    #------------------------------------------------
     public function dbInsert() {
    
        if($stmt = $this->_db->prepare($this->_CMD_INSERT)){        
            foreach($this as $key => $value ) {   
                if(strpos($key,"_")===false and ($key=="id")===false) {                    
                    if(isset($_POST[$key])) $value = $_POST[$key];
                    $stmt->bindValue(":". $key, $value );       
                }
            }

            $stmt->bindValue(":cid", $_SESSION["cid"] );
        }
        else {
            error_log("Problem preparing statement " . $this->_CMD_INSERT);    
            return;
        }        
                        
        try {
            $this->_db->beginTransaction();
            $stmt->execute();
            $this->id = $this->_db->lastInsertId();    
            $this->_db->commit();
        }
        catch(PDOException $e) {
                $this->_db->rollback();
                error_log($e->getMessage());                
            }
     }
#------------------------------------------------
       public function dbUpdate() {

        if($stmt = $this->_db->prepare($this->_CMD_UPDATE)){        
            foreach($this as $key => $value ) {   
                if(strpos($key,"_")===false) {
                    if(isset($_POST[$key])) $value = $_POST[$key];
                    $stmt->bindValue(":". $key, $value );       
                }
            }

            $stmt->bindValue(':id', intval($_POST['id']) );  
            $stmt->bindValue(':cid', intval($_SESSION['cid']) );       
        }
        else {
            error_log("Problem preparing statement " . $this->_CMD_UPDATE);    
            return;
        }         
            
        try {
            $this->_db->beginTransaction();
            $stmt->execute();
            $this->_db->commit();    
        }
        catch(PDOException $e) {
                $this->_db->rollback();
                error_log($e->getMessage());                
            }  
     }
  #------------------------------------------------
  public function dbDelete($id) {

    $this->checkDb();

    if($stmt = $this->_db->prepare($this->_CMD_DELETE)){        
        $stmt->bindValue(":id", $id);
    }
    
    try {
        $this->_db->beginTransaction();
        $stmt->execute();
        $this->_db->commit();    
    }
    catch(PDOException $e) {
            $this->_db->rollback();
            error_log($e->getMessage());                
        }  

}     
#------------------------------------------------
    public function setValues() {
       
        error_log("Updating values ...");
        foreach($this as $key => $value) {           
            if(strpos($key,"_")===False) {
                if(isset($_POST[$key]))  {
                    error_log($key . "->" . $_POST[$key]);
                    $this->$key = $_POST[$key];
                    unset($_POST[$key]);
                }
                }
        }
        $this->cid = $_SESSION["cid"];
     }
     #------------------------------------------------
     public function dbSelect($id) {

        $this->checkDb();

        error_log($this->_CMD_SELECT_SINGLE_ROW);

        if($stmt = $this->_db->prepare($this->_CMD_SELECT_SINGLE_ROW)){                    
            $stmt->bindValue(":id", $id);            
            $stmt->execute();
               
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if(count($result) > 0) {               
               
                $row = $result[0];

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
    public function dbSelectAll($cid) {

        $this->checkDb();

        error_log($this->_CMD_SELECT_ALL);

        if($stmt = $this->_db->prepare($this->_CMD_SELECT_ALL)){                    
            $stmt->bindValue(":cid", $cid);            
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);   

            return $result;             
            }   
    }
  
 
}
     
?>