<?php

require_once "model/company.php";
require_once "model/town.php";

$ds = new Company();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!$ds->dbSave()) {
        $ds->setValues(); 
    }
}

if(isset($_GET["del"])) { 
  $ds->dbDelete($_GET["del"]);
}

if(isset($_GET["add"]) or isset($_GET["edit"]) or isset($_SESSION["error"])) {  
  
  if(isset($_GET["edit"])) $ds->dbSelect($_GET["edit"]);
  if(isset($_GET["add"])) $ds = new Company();

  $town = new Town();
  $towns = $town->dbSelectAll($_SESSION["cid"]);   

  ?>

<div id="countryform" style="display:visible; margin-top:10px" class="mainbox col-md-9 col-md-offset-2 col-sm-9 col-sm-offset-2">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Poduzeće</div>                    
                        </div>  
                        <div class="panel-body" >
                            <form id="signupform" class="form-horizontal" role="form" method="post" 
                            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                
                            <?php if(isset($_SESSION["error"])) {      
                                include 'tools/form_error.html.php';    
                             } ?>

                          <?php 
                              $formElements->Id("id", $ds->id);
                              $formElements->Input("OIB","vatnumber", 3, "OIB", $ds->vatnumber, true);  
                              $formElements->Input("Naziv","name", 7, "Naziv poduzeća", $ds->name, true);  
                              $formElements->Select("Sjedište","townid", 5, $towns, "name", $ds->townid, true);
                              $formElements->Input("Adresa","address", 8, "Adresa", $ds->address, true);  
                              $formElements->Input("","address_ext", 8, "Dodatna adresa", $ds->address_ext, true);  

                              include 'tools/form_buttons.html.php'; 

                          ?>
                            </form>
                         </div>
                    </div>

         </div> 
    </div>

<?php
   }
   else {

    $rows = $ds->dbSelectAll($_SESSION["cid"]);  
    
?>
    <div id="countrytable" style="display:visible; margin-top:10px" 
              class="mainbox col-md-12 col-md-offset-0 col-sm-12 col-sm-offset-1">
        
        <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Poduzeća</div>
                            <div style="float:right; font-size: 15%; position: relative; top:-20px"> 
                            <a href="?add" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span></a>
                            </div>     
                        </div>  
                        <div class="panel-body">                         
                           <table class="table table-striped">
                           <thead>
                              <tr>
                                <td width="15%">OIB</td>
                                <td>Naziv poduzeća</td>
                                <td width="20%">Sjedište</td>
                                <td width="2%"> </td>
                                <td width="2%"> </td>
                              </tr>
                           </thead>
                           <tbody>
                  <?php foreach($rows as $row) { ?>
                           <tr class="align-text-middle" style="height:45px;">
                            
                             <td><?php echo $row["vatnumber"] ?></td>
                             <td><?php echo $row["name"] ?>
                             </td><td><?php echo $row["townname"] ?></td>
                            
                             <?php include 'tools/table_buttons.html.php'?>

                          </tr>
                  <?php  } ?> 
                           </tbody>
                           </table>
                                </div>
                            </form>
                         </div>
                    </div>
         </div> 
    </div>
<?php  } ?>