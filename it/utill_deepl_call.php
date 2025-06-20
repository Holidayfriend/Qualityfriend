<?php
require '../deepl_vendor/autoload.php';
use DeepL\DeepLException;
use DeepL\Translator;
$outupt = "";
$input_deepl ="";
$deepl_language ="";
$input_deepl = $_POST['input_deepl'];
$deepl_language = $_POST['deepl_language'];


$text_input ="".$input_deepl;
$authKey = "1fc25113-77d5-087e-a084-47542f9c50ba"; // Replace with your key
$translator = new \DeepL\Translator($authKey);
$result = $translator->translateText($text_input, null, $deepl_language);
if($result){
    echo $result->text;
}else{
    $outupt = "error";
    echo $outupt;
}

?>