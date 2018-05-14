<?php

namespace PSNPublicTrophiesLib;

use PSN\Auth;

use PSN\User;
use PSN\Trophy;
use PSN\Friend;

require_once __DIR__ . '/../vendor/autoload.php';

class PSNPublicTrophiesAuth {

    public $auth;
    public $access_token;

    //GET data for the X-NP-GRANT-CODE
    private $code_request = array(
        "state" => "06d7AuZpOmJAwYYOWmVU63OMY",
        "duid" => "0000000d000400808F4B3AA3301B4945B2E3636E38C0DDFC",
        "app_context" => "inapp_ios",
        "client_id" => "b7cbf451-6bb6-4a5a-8913-71e61f462787",
        "scope" => "capone:report_submission,psn:sceapp,user:account.get,user:account.settings.privacy.get,user:account.settings.privacy.update,user:account.realName.get,user:account.realName.update,kamaji:get_account_hash,kamaji:ugc:distributor,oauth:manage_device_usercodes",
        "response_type" => "code"
    );

    public function __construct($access_token = NULL)
    {
        $this->access_token = $access_token;
        if ($this->access_token) {
            $this->refreshToken();
        }
    }

    public function authenticate($email, $password, $ticket = "", $code = "")
    {
        $this->auth = new Auth($email, $password, $ticket, $code);
        $this->access_token = $this->auth->GetTokens();
        return $this->auth;
    }

    public function refreshToken()
    {
        if (!isset($this->access_token['refresh'])) {
            throw new \Exception('Can\'t find a refresh token');
        }

        $new_token = Auth::GrabNewTokens($this->access_token['refresh']);
        $this->access_token = $new_token;
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

    public function getUser() {
        return new User($this->access_token);
    }

    public function getFriend() {
        return new Friend($this->access_token);
    }

    public function getTrophy() {
        return new Trophy($this->access_token);
    }

    public function getProfile() {
        $user = $this->getUser();

        return $user->Me();
    }

    public function getTrophies() {
        $trophy = $this->getTrophy();

        return $trophy->GetMyTrophies();
    }
}
