<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Displays player stats');
  
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
        $output = $this->db[$this->target]['config']['plugins']['lol']['username'].' - ';
        foreach($stats['playerStatSummaries'] as $key => $value) {
          $types = array('RankedSolo5x5' => 'Solo 5v5', 'Unranked' => 'Unranked');
          foreach ($types as $type => $label) {
            if($value['playerStatSummaryType'] == $type) {
              if(!isset($value['wins'])) {
                $value['wins'] = 0;
              }
              if(!isset($value['losses'])) {
                $value['losses'] = 0;
              }
              $output .= $label.' (WL '.$value['wins'].' / '.$value['losses'].', KA '.$value['aggregatedStats']['totalChampionKills'].' / '.$value['aggregatedStats']['totalAssists'].'), ';
            }
          }
        }

        $output = rtrim($output, ', ');
        $this->say($this->target, false, $output);
      }
    }
  }
  
?>