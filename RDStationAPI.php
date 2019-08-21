<?php
class RDStationAPI {
    /**
    * Api de autenticação
    * https://api.rd.services/auth/dialog?client_id={client_id}&redirect_url={redirect_url}
    */
    
    private $clienteId;
    private $clienteSecret;
    private $refreshToken;
    private $token = "";
    private $email;
    const URL_EVENT  = "https://api.rd.services/platform/events";
    
    function __construct() {
        // instancia o token para realizar as requisicoes
    }
    
    
    function getClienteId(){
        return $this->clienteId;
    }
    
    function setClienteId($id){
        $this->clienteId = $id;
    }
    
    function getClienteSecret(){
        return $this->clienteSecret;
    }
    
    function setClienteSecret($clienteSecret){
        $this->clienteSecret = $clienteSecret;
    }
    
    function getRefreshToken(){
        return $this->refreshToken;
    }
    
    function getEmail(){
        return $this->email;
    }
    
    function setEmail($email){
        $this->email = $email;
    }
    
    function setRefreshToken($refreshToken){
        $parametros =  "{\n  \"client_id\": \"{$this->getClienteId()}\",\n  \"client_secret\": \"{$this->getClienteSecret()}\",\n  \"refresh_token\": \"{$refreshToken}\"\n}";
        $this->refreshToken = $this->dispararEventos("POST", "https://api.rd.services/auth/token", $parametros);
        $this->token = $this->refreshToken->access_token;
    }
    
    /**
    * The stage in the funnel which the contact belongs to. 
    * Valid options: 
    * 'Lead', 'Qualified Lead' and 'Client'. 
    {
        "lifecycle_stage": "Client",
        "opportunity": true,
        "contact_owner_email": "email@example.org",
        "fit": 60,
        "interest": 100
    }
    */
    function mudarEstagioFunil($parametro){
        return $this->dispararEventos("PUT","https://api.rd.services/platform/contacts/email:{$this->getEmail()}/funnels/default", json_encode($parametro));
    }
    
    /**
    * https://api.rd.services/platform/events
    * he conversion event represents Contact’s conversions on forms, landing pages, popups and etc.
    */
    function converterLead(){
        /** estrutura
        * https://api.rd.services/platform/events
        {
            "event_type": "CONVERSION",
            "event_family":"CDP",
            "payload": {
                "conversion_identifier": "Name of the conversion event",
                "name": "Nome",
                "email": "luizinho@jornada.com",
                "job_title": "job title value",
                "state": "state of the contact",
                "city": "city of the contact",
                "country": "country of the contact",
                "personal_phone": "phone of the contact",
                "mobile_phone": "mobile_phone of the contact",
                "twitter": "twitter handler of the contact",
                "facebook": "facebook name of the contact",
                "linkedin": "linkedin user name of the contact",
                "website": "website of the contact",
                "cf_custom_field_api_identifier*": "custom field value",
                "company_name": "company name",
                "company_site": "company website",
                "company_address": "company address",
                "client_tracking_id": "lead tracking client_id",
                "traffic_source": "Google",
                "traffic_medium": "cpc",
                "traffic_campaign": "easter-50-off",
                "traffic_value": "easter eggs",
                "tags": ["mql", "2019"]
            }
        }
        */
        
    }
    
    /**
    * The opportunity event, marks a Contact as Opportunity in a specfic RD Station funnel.
    * event_type : 
    * "SALE", "OPPORTUNITY", "OPPORTUNITY_LOST"
    */
    function manipularLead($type , $value = NULL, $reason = NULL){
        $param = array("email"=> $this->getEmail(), "funnel_name"=> "default", "value" => $value , "reason"=> $reason);

        return $this->dispararEventos("POST", self::URL_EVENT, json_encode(array(
            "event_type"=> $type,
            "event_family"=>"CDP", 
            "payload"=>array_filter($param))));
        }
        
        function buscarContato($email = null){
            // executar a funcao
        }
        private function dispararEventos($method, $url, $data = "")
        {
            $curl = curl_init();
            if(!empty($this->token)) $token = "authorization: Bearer $this->token";
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    $token,
                    "content-type: application/json"
                ),
            ));
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err) {
                return $err;
            } else {
                
                return json_decode($response);
            }
        }
    }
    
    
