<?php
require("../vendor/autoload.php");
$swagger = \Swagger\scan('/e2emercury/mercurye2e/source/app/Http/Controllers');
header('Content-Type: application/json');
echo $swagger;