<div id="signupalert" style="display:visible" class="alert alert-danger">
    <p>Greška:</p>
    <span><?php if(isset($_SESSION["error"])) {
    echo($_SESSION["error"]); 
    unset($_SESSION["error"]);
    }?></span>
</div>