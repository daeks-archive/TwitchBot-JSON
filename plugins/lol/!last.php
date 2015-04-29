<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Displays last game stats');
  
  if($execute) {        
    $gamestats = array();
      
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, 'http://www.lolking.net/summoner/euw/'.$this->db[$this->target]['config']['plugins']['lol']['userid']);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    $tmp = curl_exec($ch);
    $regexp = 'var\shistory\s\=\s\[(.*)\]\;';
    if(preg_match('/'.$regexp.'/siU', $tmp, $match)) {
      $gamestats = json_decode(str_replace('var history = ', '', rtrim($match[0], ';')), true);
    }
    
    $regexp = '<span class=\"tier-flag-(.*)\">(.*)<\/span>';
    if(preg_match('/'.$regexp.'/siU', $tmp, $match)) {
      $gamestats['tier'] = $match[2];
    }
    
    $regexp = 'LOLKING.champions\s\=\s(.*)\;';
    if(preg_match('/'.$regexp.'/siU', $tmp, $match)) {
      $gamestats['champions'] = json_decode(str_replace('LOLKING.champions = ', '', rtrim($match[0], ';')), true);
    }
    
    if(isset($gamestats[0])) {
      if(isset($gamestats[0])) {
        $lastgame = $gamestats[0];
        $output = ' - Last stats for '.$lastgame['match']['summoner']['name'].': ';
        $output .= $lastgame['mode'].' - Played as '.$lastgame['match']['summoner']['champion_name'];
        
        if(!isset($lastgame['match']['summoner']['CHAMPIONS_KILLED'])) {
          $lastgame['match']['summoner']['CHAMPIONS_KILLED'] = 0;
        }
        if(!isset($lastgame['match']['summoner']['ASSISTS'])) {
          $lastgame['match']['summoner']['ASSISTS'] = 0;
        }
        if(!isset($lastgame['match']['summoner']['NUM_DEATHS'])) {
          $lastgame['match']['summoner']['NUM_DEATHS'] = 0;
        }
        
        $output .= ' (KDA '.$lastgame['match']['summoner']['CHAMPIONS_KILLED'].'/'.$lastgame['match']['summoner']['NUM_DEATHS'].'/'.$lastgame['match']['summoner']['ASSISTS'].')';
        
        if($lastgame['win'] == 1) {
          $output .= ' - WIN';
        } else {
          $output .= ' - LOSS';
        }
        $output .= ' (ID '.$lastgame['match']['game_id'].')';
        $this->say($this->target, false, $output);
      }
    }
  }
  
?>