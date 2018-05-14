<?php
require_once("classes/Security.php");

$s = new Security();
$plaintext = "message to be encrypted";

$ciphertext = $s->encrypt($plaintext);

echo "ciphertext:".$ciphertext."<br/>"."\n";

$original_plaintext = $s->decrypt($ciphertext);

echo "plaintext:".$original_plaintext."<br/>"."\n";

echo $s->hash("tester");
?>
