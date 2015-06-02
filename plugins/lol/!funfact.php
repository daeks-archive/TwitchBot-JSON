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
        switch(rand(1,4)) {
          case 1:
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
          break;
          case 2:
            $total = 0;
            foreach($stats['playerStatSummaries'] as $key => $value) {
              if(isset($value['aggregatedStats']['totalTurretsKilled'])) {
                $total = $value['aggregatedStats']['totalTurretsKilled'];
              }
            }

            $this->say($this->target, false, $this->db[$this->target]['config']['plugins']['lol']['username'].' destroyed already '.$total.' turrets SSSsss');
          break;
          case 3:
            $total = 0;
            foreach($stats['playerStatSummaries'] as $key => $value) {
              if(isset($value['aggregatedStats']['totalChampionKills'])) {
                $total = $value['aggregatedStats']['totalChampionKills'];
              }
            }

            $this->say($this->target, false, $this->db[$this->target]['config']['plugins']['lol']['username'].' killed already '.$total.' noobs KAPOW');
          break;
          case 4:
            $total = 0;
            foreach($stats['playerStatSummaries'] as $key => $value) {
              if(isset($value['aggregatedStats']['totalAssists'])) {
                $total = $value['aggregatedStats']['totalAssists'];
              }
            }

            $this->say($this->target, false, $this->db[$this->target]['config']['plugins']['lol']['username'].' assists in '.$total.' situations PJHarley');
          break;
          case 5:
            $total = 0;
            foreach($stats['playerStatSummaries'] as $key => $value) {
              if(isset($value['aggregatedStats']['totalNeutralMinionsKilled'])) {
                $total = $value['aggregatedStats']['totalNeutralMinionsKilled'];
              }
            }

            $this->say($this->target, false, $this->db[$this->target]['config']['plugins']['lol']['username'].' killed already '.$total.' minions in da jungle MrDestructoid');
          break;
        }
      }
    }
  }
  
?>