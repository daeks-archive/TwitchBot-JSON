<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Displays live game stats');
  
  if($execute) {    
    $game = array();
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, 'https://euw.api.pvp.net/observer-mode/rest/consumer/getSpectatorGameInfo/EUW1/'.$this->db[$this->target]['config']['plugins']['lol']['userid'].'?api_key='.LOL_APIKEY);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $tmp = curl_exec($ch);
    if($tmp != '') {
      $game = json_decode($tmp, true);
    }

    if(!isset($game['status'])) {
      if(isset($game['gameId'])) {                        
        foreach($game['participants'] as $key => $value) {
          if($value['summonerId'] == $this->db[$this->target]['config']['plugins']['lol']['userid']) {
            $output = ' - Live stats for '.$value['summonerName'].': ';
            $output .= $game['gameMode'].'/'.$game['gameType'];
            
            $champion = array();
      
            $ch2 = curl_init(); 
            curl_setopt($ch2, CURLOPT_URL, 'https://global.api.pvp.net/api/lol/static-data/euw/v1.2/champion/'.$value['championId'].'?api_key='.LOL_APIKEY);
            curl_setopt($ch2, CURLOPT_HEADER, 0);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
            $tmp2 = curl_exec($ch2);
            if($tmp2 != '') {
              $champion = json_decode($tmp2, true);
              $output .= ' - Playing as '.$champion['name'];
            }
          }
        }
        
        if($game['gameStartTime'] > 0) {
          $output .= ' - Game started at '.date('d.m.Y H:i:s', $game['gameStartTime']/1000);
          $game['gameLength'] = time() - ($game['gameStartTime']/1000);
        }
        if($game['gameLength'] > 0) {
          $output .= ' - Duration: '.gmdate("i:s", $game['gameLength']);
        }
        
        $output .= ' (ID '.$game['gameId'].')';
        $this->say($this->target, false, $output);
      } else {
        $this->say($this->target, false, ' - There is currently no game running');
      }
    }
  }
  
?>