<?php

function encrypt($message) {
    $aes_method =  'aes-256-cbc';
$password = '9bqqBzfYHcvvXZFSh94EcxELJhICq1m4';
    // if (OPENSSL_VERSION_NUMBER <= 268443727) {
       // throw new RuntimeException('OpenSSL Version too old, vulnerability to Heartbleed');
   // }
    
    $iv_size        = openssl_cipher_iv_length($aes_method);
    $iv             = openssl_random_pseudo_bytes($iv_size);
    $ciphertext     = openssl_encrypt($message, $aes_method, $password, OPENSSL_RAW_DATA, $iv);
    $ciphertext_hex = bin2hex($ciphertext);
    $iv_hex         = bin2hex($iv);
    return "$iv_hex:$ciphertext_hex";
}

function decrypt($ciphered) { 
    $aes_method =  'aes-256-cbc';
    $password = '9bqqBzfYHcvvXZFSh94EcxELJhICq1m4';
    $iv_size    = openssl_cipher_iv_length($aes_method);
	$data       = explode(":", $ciphered);
    $iv         = hex2bin($data[0]);
    $ciphertext = hex2bin($data[1]);
    return openssl_decrypt($ciphertext, $aes_method, $password, OPENSSL_RAW_DATA, $iv);
}


?>
