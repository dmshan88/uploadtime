<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Controller\UploadtimeController;
// Routes

$app->get('/machineid/{mid}[/database/{db}]', UploadtimeController::class . ':getMachine');


