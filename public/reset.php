<?php
$hash = password_hash('password123', PASSWORD_BCRYPT, ['cost' => 10]);
echo $hash;