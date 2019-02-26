<?php 
session_start();
if (empty( csrf_token() )) {
    $_SESSION['token'] =  csrf_token();
}
$token = $_SESSION['token'];
$myfile = fopen("newfile.html", "w") or die("Unable to open file!");
$txt = file_get_contents(route('print_confirmation').'?order_id=98&lang=fi');
fwrite($myfile, $txt);
fclose($myfile);
?>

