<?php 

require_once "system/config.php";

require_once "model/userprofile.php";
require_once "model/company.php";

$profile = new UserProfile();
$company = new Company();

if($_SERVER["REQUEST_METHOD"] == "POST") {    
   # error_log("Savig data ...");

   # $profile->setValues();
    
    #if($profile->id==-1) {
      #  $company->setValues();
       # $company->dbSave();
   #}            
   # $profile->dbSave();

    $company->dbSelect(5);

}
else {
    $profile->username = "dvirovec";
    $profile->email = "davor.virovec@inforbis.hr";
    $company->companyname = "INFORBIS";
}

?>

<div class="container">    
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Prijava</div>
                        <div style="float:right; font-size: 80%; position: relative; top:-10px"><a href="#">Zaboravljena lozinka?</a></div>
                    </div>     

                    <div style="padding-top:30px" class="panel-body" >

                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                            
                        <form id="loginform" class="form-horizontal" role="form">
                                    
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                        <input id="login-username" type="text" class="form-control" name="username" value="" placeholder="korisničko ime ili email">                                        
                                    </div>
                                
                            <div style="margin-bottom: 25px" class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                        <input id="login-password" type="password" class="form-control" name="password" placeholder="lozinka">
                                    </div>
                                
                            <div class="input-group">
                                      <div class="checkbox">
                                        <label>
                                          <input id="login-remember" type="checkbox" name="remember" value="1"> Pohrani prijavu
                                        </label>
                                      </div>
                                    </div>

                                <div style="margin-top:10px" class="form-group">
                                    <!-- Button -->

                                    <div class="col-sm-12 controls">
                                      <a id="btn-login" href="#" class="btn btn-success">Prijava  </a>                                     
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12 control">
                                        <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                            Nemam korisnički profil.
                                        <a href="#" onClick="$('#loginbox').hide(); $('#signupbox').show()">
                                            Registracija
                                        </a>
                                        </div>
                                    </div>
                                </div>    
                            </form>     
                        </div>                     
                    </div>  
        </div>

        <div id="signupbox" style="display:none; margin-top:50px" class="mainbox col-md-9 col-md-offset-2 col-sm-9 col-sm-offset-2">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Registracija</div>
                            <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#" onclick="$('#signupbox').hide(); $('#loginbox').show()">Prijava</a></div>
                        </div>  
                        <div class="panel-body" >
                            <form id="signupform" class="form-horizontal" role="form" method="post" 
                            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                
                                <div id="signupalert" style="display:none" class="alert alert-danger">
                                    <p>Greška:</p>
                                    <span></span>
                                </div>

                                    
                                <div class="form-group">
                                    <label for="username" class="col-md-2 control-label">Korisničko ime</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="username" 
                                        value="<?php echo $profile->username ?>"
                                        placeholder="Korisničko ime">
                                        <input type="hidden" name="id" value="<?php echo $profile->id ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="email" class="col-md-2 control-label">Email</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="email" 
                                        value="<?php echo $profile->email ?>"
                                        placeholder="Email adresa">
                                    </div>
                                </div>
                                    
                                <div class="form-group">
                                    <label for="firstname" class="col-md-2 control-label">Ime</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="firstname"
                                        value="<?php echo $profile->firstname ?>"
                                         placeholder="Ime">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="lastname" class="col-md-2 control-label">Prezime</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="lastname" 
                                        value="<?php echo $profile->lastname ?>"
                                        placeholder="Prezime">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="passwd" class="col-md-2 control-label">Lozinka</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" name="passwd" 
                                        value="<?php echo $profile->passwd ?>"
                                        placeholder="Lozinka">
                                    </div>
                                </div>

                                 <div class="form-group">
                                    <label for="passwd_confirm" class="col-md-2 control-label">Potvrda lozinke</label>
                                    <div class="col-md-9">
                                        <input type="password" class="form-control" name="passwd_confirm" placeholder="Potvrda lozinke">
                                    </div>
                                </div>
                                    
                                <div class="form-group">
                                    <label for="icode" class="col-md-2 control-label">Naziv</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" name="name" 
                                        value="<?php echo $company->name ?>"
                                        placeholder="Naziv poduzeća ili obrta">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="icode" class="col-md-2 control-label">OIB</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="vatnumber" 
                                        value="<?php echo $company->vatnumber ?>"
                                        placeholder="OIB" reqired>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <!-- Button -->                                        
                                    <div class="col-md-offset-2 col-md-9">
                                        <button id="btn-signup" type="submit" class="btn btn-info"><i class="icon-hand-right"></i> &nbsp Spremi</button>
                                    </div>
                                </div>
                                  
                                </div>
                            </form>
                         </div>
                    </div>

         </div> 
    </div>
