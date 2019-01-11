<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Controller\UploadtimeController;
use Controller\PanelresultController;
// Routes

$app->get('/machineid/{mid}[/database/{db}]', UploadtimeController::class . ':getMachine');
$app->post('/get_result/{db}', PanelresultController::class . ':getPanelResult');


