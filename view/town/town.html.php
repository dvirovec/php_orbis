<?php

require_once "model/town.php";
require_once "model/country.php";

$ds = new Town();

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
  if(isset($_GET["add"]))  $ds = new Town();

  $country = new Country();
  $countries = $country->dbSelectAll($_SESSION["cid"]); 

  ?>

<div id="countryform" style="display:visible; margin-top:50px" class="mainbox col-md-9 col-md-offset-2 col-sm-9 col-sm-offset-2">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Valute</div>                    
                        </div>  
                        <div class="panel-body" >
                            <form id="signupform" class="form-horizontal" role="form" method="post" 
                            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                
                            <?php if(isset($_SESSION["error"])) {     
                                include 'tools/form_error.html.php';    
                             } ?>
                                    
                          <?php 
                             
                             $formElements->Id("id", $ds->id);
                             
                              $formElements->Input("Naziv","name", 5, "Naziv mjesta", $ds->name, true);  
                              $formElements->Select("Država","countryid", 5, $countries, "name", $ds->countryid, true);
                             
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
    <div id="countrytable" style="display:visible; margin-top:50px" 
              class="mainbox col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">
        
        <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Valuta</div>
                            <div style="float:right; font-size: 15%; position: relative; top:-20px"> 
                            <a href="?add" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span></a>
                            </div>     
                        </div>  
                        <div class="panel-body">                         
                           <table class="table table-striped">
                           <thead>
                              <tr>
                                <td>Naziv mjesta</td>
                                <td width="20%">Država</td>
                                <td width="5%"> </td>
                                <td width="5%"> </td>
                              </tr>
                           </thead>
                           <tbody>
                  <?php foreach($rows as $row) { ?>
                           <tr class="align-text-middle" style="height:45px;">
                             
                             <td><?php echo $row["name"] ?></td>
                             <td><?php echo $row["countryname"] ?></td>
                             
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
<?php  } 

?>