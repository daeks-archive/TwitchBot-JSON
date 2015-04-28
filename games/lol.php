<?php
                           
  class lol {
  
    private $version;
       
    public static function getlivegame() {
      $output = array();
      
      $ch = curl_init(); 
      curl_setopt($ch, CURLOPT_URL, 'https://euw.api.pvp.net/observer-mode/rest/consumer/getSpectatorGameInfo/EUW1/'.LOL_USERID.'?api_key='.LOL_APITOKEN);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      $output = curl_exec($ch);
      if($output != '') {
        $output = json_decode($output, true);
      }
    
      return $output;
    }
    
    public static function getelo($id) {
      $output = array();
      
      $ch = curl_init(); 
      curl_setopt($ch, CURLOPT_URL, 'https://euw.api.pvp.net/api/lol/euw/v2.5/league/by-summoner/'.LOL_USERID.'?api_key='.LOL_APITOKEN);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      $output = curl_exec($ch);
      if($output != '') {
        $output = json_decode($output, true);
      }
    
      return $output;
    }
    
    public static function getstats($id) {
      $output = array();
      
      $ch = curl_init(); 
      curl_setopt($ch, CURLOPT_URL, 'https://euw.api.pvp.net/api/lol/euw/v1.3/stats/by-summoner/'.LOL_USERID.'/summary?season=SEASON'.date('Y').'&api_key='.LOL_APITOKEN);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      $output = curl_exec($ch);
      if($output != '') {
        $output = json_decode($output, true);
      }
    
      return $output;
    }
    
    public static function getchampion($id) {
      $output = array();
      
      $ch = curl_init(); 
      curl_setopt($ch, CURLOPT_URL, 'https://global.api.pvp.net/api/lol/static-data/euw/v1.2/champion/'.LOL_USERID.'?api_key='.LOL_APITOKEN);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      $output = curl_exec($ch);
      if($output != '') {
        $output = json_decode($output, true);
      }
    
      return $output;
    }
    
    public static function getlolking($id) {
      $output = array();
      
      $ch = curl_init(); 
      curl_setopt($ch, CURLOPT_URL, 'http://www.lolking.net/summoner/euw/'.LOL_USERID);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
      $output = curl_exec($ch);
      $regexp = 'var\shistory\s\=\s\[(.*)\]\;';
      if(preg_match('/'.$regexp.'/siU', $output, $match)) {
        $output = json_decode(str_replace('var history = ', '', rtrim($match[0], ';')), true);
      }
      
      $regexp = '<span class=\"tier-flag-(.*)\">(.*)<\/span>';
      if(preg_match('/'.$regexp.'/siU', $output, $match)) {
        $output['tier'] = $match[2];
      }
      
      $regexp = 'LOLKING.champions\s\=\s(.*)\;';
      if(preg_match('/'.$regexp.'/siU', $output, $match)) {
        $output['champions'] = json_decode(str_replace('LOLKING.champions = ', '', rtrim($match[0], ';')), true);
      }
        
      return $output;
    }
}

?>