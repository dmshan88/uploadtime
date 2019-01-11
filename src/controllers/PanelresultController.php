<?php

namespace Controller;

use Jenssegers\Mongodb\Query\Builder;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Model\ReturnValue as ReturnValue; 

class PanelresultController
{

    protected $table;

    public function __construct(
        Builder $celercare_result,
        Builder $pointcare_result,
        Builder $app_result
    ) {
        $this->celercare_result = $celercare_result;
        $this->pointcare_result = $pointcare_result;
        $this->app_result = $app_result;
    }

        
    public function getPanelResult(Request $request, Response $response, $args)
    {
        $data = $request->getParsedBody();

        if (!isset($data['RequestName']) || empty($data['RequestName'])) {
            return $response->withJson(ReturnValue::returnMsg(ReturnValue::RTN_NO_REQUESTNAME, "ReturnValue::RTN_NO_REQUESTNAME"));
        }
        $requestname = $data['RequestName'];
        if (!in_array($requestname, ['QueryPanelResult'])) {
            return $response->withJson(ReturnValue::returnMsg(ReturnValue::RTN_UNSUPPORT_REQUEST, "ReturnValue::RTN_UNSUPPORT_REQUEST ". $requestname));
        }
        if (!isset($data['cID']) || empty($data['cID'])) {
            return $response->withJson(ReturnValue::returnMsg(ReturnValue::RTN_NO_CID, "ReturnValue::RTN_NO_CID"));
        }
        $cid = $data['cID'];
        if (!isset($data['TimeStamp']) || empty($data['TimeStamp']) 
            || !isset($data['MD5']) || empty($data['MD5']) 
            || !isset($data['cqMachineid']) || empty($data['cqMachineid']) 
        ) {
            return $response->withJson(ReturnValue::returnMsg(ReturnValue::RTN_LACK_REQUEST, "ReturnValue::RTN_LACK_REQUEST"));
        }
        $timestamp= $data['TimeStamp'];
        $md5 = $data['MD5'];
        if (!is_numeric($timestamp) || intval($timestamp) <= 0) {
            return $response->withJson(ReturnValue::returnMsg(ReturnValue::RTN_TIMESTAMP_ERROR, "ReturnValue::RTN_TIMESTAMP_ERROR"));
        }
        if (strlen($md5) != 32 || !filter_var($md5, FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[a-fA-F0-9]+$/']])) {
            return $response->withJson(ReturnValue::returnMsg(ReturnValue::RTN_BAD_MD5, "ReturnValue::RTN_BAD_MD5"));
        }
        if (strcasecmp(md5($cid . $timestamp .$cid, false), $md5)) {
            return $response->withJson(ReturnValue::returnMsg(ReturnValue::RTN_MD5_FALI, "ReturnValue::RTN_MD5_FALI"));
        }

        $machineid = filter_var($data['cqMachineid'], FILTER_SANITIZE_STRING);
        if (isset($data['iqBDate']) && is_numeric($data['iqBDate']) && $data['iqBDate'] > 0) {
            $starttime = intval($data['iqBDate']);
        }
        if (isset($data['iqEDate']) && is_numeric($data['iqEDate']) && $data['iqEDate'] > 0) {
            $endtime = intval($data['iqEDate']);
        }
        $page = 1;
        if (isset($data['iPage']) && is_numeric($data['iPage'])) {
            $page = intval($data['iPage']) < 1 ? 1 : intval($data['iPage']);
        }
        $pageper = 100;
        if (isset($data['iPagePer']) && is_numeric($data['iPagePer'])) {
            $tmp = intval($data['iPagePer']);
            if ($tmp == 0 ) {
                $onlycount = true;
            } elseif ($tmp > 1000) {
                $pageper = 1000;
            } elseif ($tmp > 0) {
                $pageper = $tmp;
            }
        }
        $pageper = is_numeric($data['iPagePer']) ? intval($data['iPagePer']) : 100;
        $pageper = $pageper > 1000 ? 1000 : $pageper;
        $pageper = $pageper < 1 ? 100 : $pageper;

        if(isset($args['db']) && $args['db'] == "celercare") {
            $model = $this->celercare_result;
        } else if(isset($args['db']) && $args['db'] == "pointcare") {
            $model = $this->pointcare_result;
        } else if(isset($args['db']) && $args['db'] == "app"){
            $model = $this->app_result;
        } else {
            return $response->withJson(ReturnValue::returnMsg(ReturnValue::RTN_UNSUPPORT_REQUEST, "ReturnValue::RTN_UNSUPPORT_REQUEST"));
        }
        $query = $model->where("machineid", $machineid);
        if (isset($starttime)) {
            $query->where('chkdatetime', '>=', $starttime);
        }
        if (isset($endtime)) {
            $query->where('chkdatetime', '<=', $endtime);
        }
        if (isset($onlycount) && $onlycount) {
            $records = [];
            $count = $query->count();
        } else {
            $records = $query
                ->orderBy('chkdatetime','desc')
                ->offset($pageper * ($page -1))
                ->limit($pageper)
                ->get();
            foreach ($records as $key => $value) {
                if (array_key_exists('_id', $value)) {
                    unset($records[$key]['_id']);
                }
            }
            $count = count($records);
        } 

        if ($count == 0) {
            return $response->withJson(ReturnValue::returnMsg(ReturnValue::RTN_NO_RECORD, "ReturnValue::RTN_NO_RECORD"));
        }
        return $response->withJson( array_merge(
            ['RecList' => $records, 'iTotalRec' => $count], 
            ReturnValue::returnMsg(ReturnValue::RTN_SUCCESS)
        ));
    }
}