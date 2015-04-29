<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Displays random funfacts');
  
  if($execute) {    
    $stats = array();
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, 'https://euw.api.pvp.net/api/lol/euw/v1.3/stats/by-summoner/'.$this->db[$this->target]['config']['plugins']['lol']['userid'].'/summary?season=SEASON'.date('Y').'&api_key='.LOL_APIKEY);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $tmp = curl_exec($ch);
    if($tmp != '') {
      $stats = json_decode($tmp, true);
    }

    if(!isset($stats['status'])) {
      if(isset($stats['playerStatSummaries'])) {
        $total = 0;
        $neutraltotal = 0;
        foreach($stats['playerStatSummaries'] as $key => $value) {
          if(isset($value['aggregatedStats']['totalMinionKills'])) {
            $total = $value['aggregatedStats']['totalMinionKills'];
          }
          if(isset($value['aggregatedStats']['totalNeutralMinionsKilled'])) {
            $neutraltotal = $value['aggregatedStats']['totalNeutralMinionsKilled'];
          }
        }

        $this->say($this->target, false, $this->db[$this->target]['config']['plugins']['lol']['username'].' killed already '.($total + $neutraltotal).' poor minions :(');
      }
    }
  }
  
?>