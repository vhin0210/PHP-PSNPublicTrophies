<?php

namespace PSNPublicTrophiesLib;

use PSN\Auth;

require_once __DIR__ . '/../vendor/autoload.php';

class PSNPublicTrophiesAuth {

    public $auth;

    //GET data for the X-NP-GRANT-CODE
    private $code_request = array(
        "state" => "06d7AuZpOmJAwYYOWmVU63OMY",
        "duid" => "0000000d000400808F4B3AA3301B4945B2E3636E38C0DDFC",
        "app_context" => "inapp_ios",
        "client_id" => "b7cbf451-6bb6-4a5a-8913-71e61f462787",
        "scope" => "capone:report_submission,psn:sceapp,user:account.get,user:account.settings.privacy.get,user:account.settings.privacy.update,user:account.realName.get,user:account.realName.update,kamaji:get_account_hash,kamaji:ugc:distributor,oauth:manage_device_usercodes",
        "response_type" => "code"
    );

    public function __construct()
    {
        // nothing to do
    }

    public function authenticate($email, $password, $ticket = "", $code = "")
    {
        $this->auth = new Auth($email, $password, $ticket, $code);
        return $this->auth;
    }

    public function refreshToken($access_token)
    {
        $new_token = Auth::GrabNewTokens($access_token);
        return $new_token;
    }

    public function getAuthenticateUrl() {
        $query_string = http_build_query($this->code_request);
        $url = CODE_URL . '?' . $query_string;
        return $url;
    }

    public function getAuthenticateUrlParameters($url) {
        $parts = parse_url($url);
        parse_str($parts['fragment'], $query);
        return $query;
    }
}
