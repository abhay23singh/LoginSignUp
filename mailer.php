<?php
function send_email($to, $subject, $message) {
    $headers = "From: no-reply@yourwebsite.com\r\n";
    $headers .= "Reply-To: no-reply@yourwebsite.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    return mail($to, $subject, $message, $headers);
}
?>
