<?php

require_once "model/currency.php";

$ds = new Currency();

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
  if(isset($_GET["add"]))  $ds = new Currency();

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
                              $formElements->Input("Oznaka","code", 2, "Oznaka", $ds->code, true);  
                              $formElements->Input("Naziv valute","name", 6, "Naziv valute", $ds->name, true);  
                              $formElements->Input("Kod","numcode", 2, "Numerička oznaka", $ds->numcode, true);  
                          
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
                                <td width="10%">Oznaka</td>
                                <td>Naziv valute</td>
                                <td width="5%">Oznaka</td>
                                <td width="5%"> </td>
                                <td width="5%"> </td>
                              </tr>
                           </thead>
                           <tbody>
                  <?php foreach($rows as $row) { ?>
                           <tr class="align-text-middle" style="height:45px;">
                             
                             <td><?php echo $row["code"] ?></td><td><?php echo $row["name"] ?></td>
                             <td><?php echo $row["numcode"] ?></td>
                             
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