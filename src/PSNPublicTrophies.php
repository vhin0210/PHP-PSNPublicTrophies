<?php

namespace PSNPublicTrophiesLib;

class PSNPublicTrophies {
    const PSN_DOMAIN = 'https://io.playstation.com/';
    const PSN_ENDPOINT = 'playstation/psn/';

    const PSN_RESOURCE_PROFILE = 'profile/public/userData';
    const PSN_RESOURCE_TROPHIES = 'public/trophies/';

    const PSN_HEADER_ORIGIN = 'https://www.playstation.com';

    private $psn_id;

    public function __construct($psn_id) {
        $this->psn_id = $psn_id;
    }

    public function getProfile() {
        $url = PSNPublicTrophies::PSN_DOMAIN . PSNPublicTrophies::PSN_ENDPOINT . PSNPublicTrophies::PSN_RESOURCE_PROFILE;
        $options = array(
            'headers' => array(
                'Origin' => PSNPublicTrophies::PSN_HEADER_ORIGIN
            ),
            'method' => 'GET',
            'data' => array(
                'onlineId' => $this->psn_id
            ),
            'timeout' => 60
        );

        $data = $this->sendRequest($url, $options);
        if ($data->status_message == 'OK') {
            return $data;
        }
        return FALSE;
    }

    public function getTrophies() {
        $url = PSNPublicTrophies::PSN_DOMAIN . PSNPublicTrophies::PSN_ENDPOINT . PSNPublicTrophies::PSN_RESOURCE_TROPHIES;
        $options = array(
            'headers' => array(
                'Origin' => PSNPublicTrophies::PSN_HEADER_ORIGIN
            ),
            'method' => 'GET',
            'data' => array(
                'onlineId' => $this->psn_id
            ),
            'timeout' => 60
        );

        $data = $this->sendRequest($url, $options);
        if ($data->status_message == 'OK') {
            return $data;
        }
        return FALSE;
    }

    private function sendRequest($url, $options = array()) {
        $curl = curl_init();
        if (isset($options['headers']) && !empty($options['headers'])) {
          $headers = $this->generateHeaders($options['headers']);
          curl_setopt($curl, CURLOPT_HEADER, FALSE);
          curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        if (isset($options['timeout'])) {
          curl_setopt($curl, CURLOPT_TIMEOUT, $options['timeout']);
        }
        $fields_string = '';
        if (isset($options['data'])) {
          if (isset($options['method']) && strtolower($options['method']) == 'get') {
            $fields_string = http_build_query($options['data']);
          } else {
            $fields_string = $options['data'];
          }
        }
        if (isset($options['method']) && strtolower($options['method']) == 'post') {
          curl_setopt($curl, CURLOPT_POST, TRUE);
          curl_setopt($curl, CURLOPT_POSTFIELDS, $fields_string);
        } else {
          $url .= '?' . $fields_string;
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        $ua = 'PSNPublicTrophiesLib';
        curl_setopt($curl, CURLOPT_USERAGENT, $ua);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_FORBID_REUSE, TRUE);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $options['timeout']);
        curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 10);

        curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);

        $return = new \stdClass;
        try {
          $return_str = curl_exec($curl);
          $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
          curl_close($curl);
          $return->status_message = 'OK';
          $return->data = json_decode($return_str);

          if ($status >= 400) {
            $return->status_message = 'NOT OK';
            $return->code = $status;
            $return->error = $return_str;
          }
        } catch (Exception $e) {
          $return->status_message = 'NOT OK';
          $return->data = json_encode(array(
            'code' => $e->getCode(),
            'message' => $e->getMessage(),
          ));
          $return->code = 400;
          $return->error = 'Unhandled error on request.';
        }

        return $return;
    }

    private function generateHeaders($headers) {
      $return = array();
      foreach($headers as $key => $value) {
        $return[] = $key . ': ' . $value;
      }
      return $return;
    }
}
