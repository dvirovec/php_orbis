
<?php

require_once "system/config.php";
require_once "model/company.php";
require_once "model/country.php";
require_once "model/currency.php";
require_once "tools/form_elements.php";

$company = new Company();
$country = new Country();

$formElements = new FormElements();

if(isset($_GET["del"])) { 
  $country->dbDelete($_GET["del"]);
  unset($_POST["id"]);
}

if(isset($_GET["add"]) or isset($_GET["edit"])) {  
  if(isset($_GET["edit"])) {
      $country->dbSelect($_GET["edit"]);
  }
 
  $currency = new Currency();

  $currencies = $currency->dbSelectAll($_SESSION["cid"]);

  ?>

<div id="countryform" style="display:visible; margin-top:50px" class="mainbox col-md-9 col-md-offset-2 col-sm-9 col-sm-offset-2">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Država</div>                    
                        </div>  
                        <div class="panel-body" >
                            <form id="signupform" class="form-horizontal" role="form" method="post" 
                            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                
                                <div id="signupalert" style="display:none" class="alert alert-danger">
                                    <p>Greška:</p>
                                    <span></span>
                                </div>
                                    
                                <?php 
                                    $formElements->Id("id", $country->id);
                                    $formElements->Input("Oznaka","code", 2, "Oznaka", $country->code);  
                                    $formElements->Input("Naziv","name", 6, "Naziv države", $country->name);  
                                    $formElements->Select("Valuta","currencyid", 2, $currencies, "name", $country->currencyid);
                                ?>

                                
<div class="form-inline col-md-9 col-md-offset-8 col-sm-9 col-sm-offset-8" >
                                <div class="form-group">
                                    <!-- Button -->                                        
                                    <div class="col-md-8">
                                        <button id="btn-signup" type="submit" class="btn btn-info">
                                        <i class="icon-hand-right"></i>Spremi</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <!-- Button -->                                        
                                    <div class="col-md-8">
                                        <button id="btn-signup" type="submit" class="btn btn-info">
                                        <i class="icon-hand-right"></i>Odustani</button>
                                    </div>
                                </div>
</div>
                                </div>
                            </form>
                         </div>
                    </div>

         </div> 
    </div>

<?php
   }
   else {

  #  if($_SERVER["REQUEST_METHOD"] == "POST") 
      if(isset($_POST["id"])) {            
        $country->dbSave();       
      }
  
    $countries = $country->dbSelectAll($_SESSION["cid"]);
  
?>
    <div id="countrytable" style="display:visible; margin-top:50px" 
              class="mainbox col-md-10 col-md-offset-1 col-sm-10 col-sm-offset-1">
        
        <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Države</div>
                            <div style="float:right; font-size: 15%; position: relative; top:-20px"> 
                            <a href="?add" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span></a>
                            </div>     

                        </div>  
                        <div class="panel-body" >
                           
                           <table class="table table-striped">
                           <thead>
                              <tr>
                                <td width="10%">Oznaka</td>
                                <td>Naziv države</td>
                                <td width="10%">Valuta</td>
                                <td width="5%"></td>
                                <td width="5%"></td>
                              </tr>
                           </thead>
                           <tbody>

                  <?php foreach($countries as $row) { ?>
                           <tr class="align-text-middle" style="height:45px;">
                             <td><?php echo $row["code"] ?></td><td><?php echo $row["name"] ?></td><td><?php echo $row["currency_code"] ?></td>
                             <td><a href="?edit=<?php echo $row["id"]?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-edit"></span></a></td>
                             <td><a href="?del=<?php echo $row["id"]?>"  class="btn btn-default btn-sm"><span class="glyphicon glyphicon-remove"></span></a></td>
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