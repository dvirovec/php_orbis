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

        $this->_db = new PDO($_SESSION["conn_str"], DB_USERNAME, DB_PASSWORD); 
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->_db->exec('SET NAMES "utf8"');

        $this->generateStatement($this->_CMD_INSERT);
        $this->generateStatement($this->_CMD_UPDATE);
        $this->generateStatement($this->_CMD_DELETE);
        $this->generateStatement($this->_CMD_SELECT_SINGLE_ROW);
        $this->generateStatement($this->_CMD_SELECT_ALL);

    }    
    #-----------------------------------------------
    private function checkDb(){
        if($this->_db!==null) {
            
        } else {
            $this->_db = new PDO($conn_str, DB_USERNAME, DB_PASSWORD); 
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_db->exec('SET NAMES "utf8"');
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

        $this->setValues();

       if (!$this->validate()) { 
          return false;   
       }

        if($this->id == -1) { 
            $this->dbInsert(); }
         else { 
             $this->dbUpdate(); }

        return true;     
     }
    #------------------------------------------------
     public function dbInsert() {
    
        if($stmt = $this->_db->prepare($this->_CMD_INSERT)){        
            foreach($this as $key => $value ) {   
                if(strpos($key,"_")===false and ($key=="id")===false) {
                    $stmt->bindValue(":". $key, $this->$key );       
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
                    $stmt->bindValue(":". $key, $this->$key );       
                }
            }

            $stmt->bindValue(':id', intval($_POST['id']) );  
            $stmt->bindValue(':cid', intval($_SESSION['cid']) );       
        }
        else {  
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

        foreach($this as $key => $value) {           
            if(strpos($key,"_")===False) {
                if(isset($_POST[$key]))  {
                    error_log($key . "->" . $_POST[$key]);
                    $this->$key = $_POST[$key];
                }
                }
        }
        if(isset($_POST["id"])) $this->id = $_POST["id"];
        $this->cid = $_SESSION["cid"];
     }
     #------------------------------------------------
     public function dbSelect($id) {

        $this->checkDb();

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
        
            }
            else  {
                error_log("ERROR - NOT Selected " . $this->_CMD_SELECT_SINGLE_ROW);
            }   
    }
    #------------------------------------------------
    public function dbSelectAll($cid) {

        $this->checkDb();

        if($stmt = $this->_db->prepare($this->_CMD_SELECT_ALL)){                    
            $stmt->bindValue(":cid", $cid);            
            $stmt->execute();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);   

            return $result;             
            }   
    }
    #------------------------------------------------
    public function validate() {
        return true;
    }

  
 
}
     
?>