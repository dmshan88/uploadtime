<?php

namespace Model;


class ReturnValue
{

    const RTN_NO_ERROR = 0;
    const RTN_SUCCESS = 1;
    // const RTN_FIELD_DUP = -1;
    const RTN_NO_RECORD = -2;
    const RTN_LACK_REQUEST = -3;
    const RTN_UNSUPPORT_REQUEST = -4;
    const RTN_NO_REQUESTNAME = -5;
    // const RTN_FIELD_PARSE_ERROR = -6;
    // const RTN_SAFE_ERROR = -7;
    // const RTN_NO_AUTH = -8;
    const RTN_DB_ERROR = -9;
    const RTN_QUERY_ERROR = -10;
    // const RTN_BAD_JSON = -11;
    // const RTN_NO_FILE = -12;
    // const RTN_PWD_ERROR = -13;
    // const RTN_MSG_FAIL = -14;
    // const RTN_DUP_PHONE = -15;
    // const RTN_REGISTER_ERROR = -16;
    // const RTN_PHONE_ERROR = -17;
    // const RTN_NO_FIND = -18;

    const RTN_NO_CID = -710;
    // const RTN_NO_MACHINE = -711;
    const RTN_TIMESTAMP_ERROR = -720;
    const RTN_BAD_MD5 = -730;
    const RTN_MD5_FALI = -731;

    static public function returnMsg($code, $value = '')
    {
        if (!in_array($code, [
            self::RTN_NO_ERROR,
            self::RTN_SUCCESS,
            // self::RTN_FIELD_DUP,
            self::RTN_NO_RECORD,
            self::RTN_LACK_REQUEST,
            self::RTN_UNSUPPORT_REQUEST,
            self::RTN_NO_REQUESTNAME,
            // self::RTN_FIELD_PARSE_ERROR,
            // self::RTN_SAFE_ERROR,
            // self::RTN_NO_AUTH,
            self::RTN_DB_ERROR,
            self::RTN_QUERY_ERROR,
            // self::RTN_BAD_JSON,
            // self::RTN_NO_FILE,
            // self::RTN_PWD_ERROR,
            // self::RTN_MSG_FAIL,
            // self::RTN_DUP_PHONE,
            // self::RTN_REGISTER_ERROR,
            // self::RTN_PHONE_ERROR,
            // self::RTN_NO_FIND,
            self::RTN_NO_CID,
            // self::RTN_NO_MACHINE,
            self::RTN_TIMESTAMP_ERROR,
            self::RTN_BAD_MD5,
            self::RTN_MD5_FALI,
            ])
        ) { 
            die("bad code");
        }
        if (empty($value)) {
            return ['ReturnValue' => $code];
        }
        return ['ReturnValue' => $code, 'Message' => $value];
    }
};