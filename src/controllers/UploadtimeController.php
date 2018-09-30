<?php

namespace Controller;

use Jenssegers\Mongodb\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class UploadtimeController
{

    protected $table;

    public function __construct(
        Builder $celercare,
        Builder $pointcare
    ) {
        $this->celercare = $celercare;
        $this->pointcare = $pointcare;
    }

    public function getMachine(Request $request, Response $response, $args)
    {
        $arr = ["error"=>"unknown error"];
        if(isset($args['db']) && $args['db'] == "celercare") {
            $model = $this->celercare;
        } else {
            $model = $this->pointcare;
        }
        if (isset($args["mid"])) {
            $machineid = str_pad($args["mid"], 5, '0', STR_PAD_LEFT);
            $machineid = str_pad($machineid, 6, ' ', STR_PAD_LEFT);
            $records = $model
                ->select('machineid','uploadtime','softversion','hardware1version','hardware2version')
                ->where("machineid", $machineid)
                ->orderBy('uploadtime','desc')
                ->limit(1)
                ->get();
            if (!empty($records)) {

                $arr = $records[0];
                $arr["_id"] = $arr["_id"]->__toString();
                $arr["uploadtime"] = strval($arr["uploadtime"]);
            }
            else {
                $arr = ["error"=>"no record"];
            }
        } else {
            $arr = ["error"=>"empty machine id"];
        }
        return $response->withJson($arr);
    }
}