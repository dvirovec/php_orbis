<?php

class FormElements {

public function Id($name, $php_value ) {
    echo('<input type="hidden" name="' . $name . '" value=' . $php_value . '>');
}

public function Input($label, $name, $width, $placeholder, $php_value, $required) {
   
    if($required)
       $required = "required";
    else 
       $required = "";    

    echo('<div class="form-group"><label for='. $name . ' class="col-md-2 control-label">' . $label . '</label>
    <div class="col-md-'. $width .'"><input type="text" class="form-control" 
    name="' . $name . '" value="' . $php_value . '" placeholder="' . $placeholder . '" "' . $required . ' ></div></div>');

}

public function Select($label, $name, $width, $source, $display_value, $php_value) {

    echo('<div class="form-group">
    <label for="' . $name . '" class="col-md-2 control-label">' . $label . '</label>
    <div class="col-md-' . $width . '">
    <select class="form-control" name="' . $name . '">');
    
    foreach($source as $row) {        
        
        $selected = "";

        if($row["id"] === $php_value) $selected = " selected='selected'";

            echo "<option value=" . $row["id"] . $selected . ">" . $row[$display_value] . "</option>";
        }
        echo '</select></div></div>';    
    }
}

$formElements = new FormElements();

?>