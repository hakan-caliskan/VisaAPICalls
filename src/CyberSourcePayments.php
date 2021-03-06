<?php

namespace VisaCyberSourcePayments;

Class CyberSourcePayments {

    const requestURL = 'https://sandbox.api.visa.com/cybersource/payments/v1/authorizations?';
    const resource = 'payments/v1/authorizations';
    

    function __construct($apiKey = null, $secret = null, $body = null) {
        
        if (!isset($apiKey) || empty($apiKey) || !isset($secret) || empty($secret)) {
            echo "apikey & screet invalid";
            exit;
        }
        $this->apikey = $apiKey;
        $this->secret = $secret;
        $this->body = $body;
    }

    public function payments() {
        return $this->CyberSourcePaymentsCurlRequest();
    }

    private function CyberSourcePaymentsCurlRequest() {
        $hashtoken = $this->curlToken();
        $url = $this->requestURL();
        $header = (array("X-PAY-TOKEN: " . $hashtoken, "Content-Type: application/json"));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        return $response;
    }

    private function curlToken() {
        $time = time();
        $query_string = $this->queryString();
        $token = $time . self::resource . $query_string . $this->body;
        $hashtoken = "xv2:" . $time . ":" . hash_hmac('sha256', $token, $this->secret);
        return $hashtoken;
    }

    private function requestURL() {
        $query_string = $this->queryString();
        return self::requestURL . $query_string;
    }

    private function queryString() {
        return "apikey=" . $this->apikey;
    }

}
