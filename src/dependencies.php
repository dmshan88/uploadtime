<?php
// DIC configuration

$container = $app->getContainer();

// Service factory for the ORM
$container['mongodb_c'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->getDatabaseManager()->extend('mongodb', function($config)
	{
	    return new Jenssegers\Mongodb\Connection($config);
	});
    $capsule->addConnection($container['settings']['mongodb_c']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

$container['mongodb_p'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->getDatabaseManager()->extend('mongodb', function($config)
    {
        return new Jenssegers\Mongodb\Connection($config);
    });
    $capsule->addConnection($container['settings']['mongodb_p']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};


$container[Controller\UploadtimeController::class] = function ($c) {
    $celercare = $c->get('mongodb_c')->table('upload_time');
    $pointcare = $c->get('mongodb_p')->table('upload_time');
    return new Controller\UploadtimeController($celercare,$pointcare);
};


//errors
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withJson(['error'=>'page not found']);
    };
};
$container['notAllowedHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(405)
            ->withJson(['error'=>'Method  not found']);
    };
};
$container['phpErrorHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(500)
            ->withJson(['error'=>'bad error']);
    };
};
$container['errorHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(500)
            ->withJson(['error'=>'soming error']);
    };
};
