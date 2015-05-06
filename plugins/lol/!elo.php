<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Displays LoL rank');
  
  if($execute) {    
    $elo = array();
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, 'https://euw.api.pvp.net/api/lol/euw/v2.5/league/by-summoner/'.$this->db[$this->target]['config']['plugins']['lol']['userid'].'?api_key='.LOL_APIKEY);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $tmp = curl_exec($ch);
    if($tmp != '') {
      $elo = json_decode($tmp, true);
    }

    if(!isset($elo['status'])) {
      if(isset($elo[$this->db[$this->target]['config']['plugins']['lol']['userid']])) {   
        $output = $this->db[$this->target]['config']['plugins']['lol']['username'].' - ';
        foreach($elo[$this->db[$this->target]['config']['plugins']['lol']['userid']] as $key => $value) {
          if($value['queue'] == 'RANKED_SOLO_5x5') {
            $output .= 'Solo 5v5: '.$value['tier'].' ';
            foreach($value['entries'] as $key2 => $entry) {
              if($entry['playerOrTeamId'] == $value['participantId']) {
                $output .= $entry['division'].' (PWL '.$entry['leaguePoints'].' / '.$entry['wins'].' / '.$entry['losses'].'), ';
              }
            }
          }
        }

        $output = rtrim($output, ', ');
        $this->say($this->target, false, $output);
      }
    }
  }
  
?>