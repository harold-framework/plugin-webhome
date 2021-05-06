<?php

function getUserKey(): string {
    if (isset($_COOKIE["user_token"])) { return $_COOKIE["user_token"]; };
    return "NO_FOUND_TOKEN"; 
}

class AuthenticationManager {

    public $key;
    public $isAuthenticated = false;
    public $isPageSecure = false;
    public $userData;
    
    private $apiData;
    private $config;
    private $page;

    function __construct($apiData, $config, $page) {
        $this->key = getUserKey();
        $this->apiData = $apiData;
        $this->config = $config;
        $this->page = $page;

        $this->userData = $this->createAPICall($this->config["APIURL"] . "/info/" . $this->key . "?key=" . $this->config["KEY"] . "&page=" . $this->page);
        $this->isAuthenticated = $this->userData->success;

        // If we have a set user_token and it is invalid, Ensure its destroyed.
        if (isset($_COOKIE["user_token"]) && !$this->isAuthenticated) { $this->logout(); };
    }

    private function createAPICall($url) {
        $apiData = file_get_contents($url);
        if ($apiData == false) { header("Location: " . $this->config["BASEURL"] . "/error/?e=503"); die(); }
        $apiData = json_decode($apiData);
        return $apiData;
    }

    public function textObsfucator($x): string {
        if (!$this->isAuthenticated) { return "Lorum Ipsum"; };
        return $x;
    }

    public function securePage(): void {
        if (!$this->isAuthenticated) {
            header("Location: " . $this->config["BASEURL"] . "/login?".time()."&page=".$this->page);
            die();
        }
    }

    public function logout(): void {
        if (isset($_COOKIE["user_token"])) { setcookie("user_token", null, -1, "/"); };
        $this->isAuthenticated = false;
    }

    public function fetchUserData() {
        if ($this->isAuthenticated) {
            $tmpData = $this->createAPICall($this->config["APIURL"] . "/info/" . $this->key . "?key=" . $this->config["KEY"]);
            if ($tmpData->success) {
                return $tmpData;
            }
        }
        return [];
    }

}