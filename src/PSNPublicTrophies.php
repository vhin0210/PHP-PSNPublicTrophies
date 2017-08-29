<?php

namespace PSNPublicTrophiesLib;

class PSNPublicTrophies {
    private $psn_id;

    public function __construct($psn_id) {
        $this->psn_id = $psn_id;
    }
}
