<?php
echo "HTTP/1.1 200 OK\r\n";
echo "Content-Type: text/plain\r\n\r\n";
echo "You sent via POST:\n";
print_r($_POST);
?>
