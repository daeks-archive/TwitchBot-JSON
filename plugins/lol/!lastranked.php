<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Displays last ranked game stats');
  
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
    
    foreach($gamestats as $game) {
      if(substr($game['match']['type'], 0, 6) == 'RANKED') {
        $output = ' - Last stats for '.$game['match']['summoner']['name'].': ';
        $output .= $game['mode'].' - Played as '.$game['match']['summoner']['champion_name'];
        
        if(!isset($game['match']['summoner']['CHAMPIONS_KILLED'])) {
          $game['match']['summoner']['CHAMPIONS_KILLED'] = 0;
        }
        if(!isset($game['match']['summoner']['ASSISTS'])) {
          $game['match']['summoner']['ASSISTS'] = 0;
        }
        if(!isset($game['match']['summoner']['NUM_DEATHS'])) {
          $game['match']['summoner']['NUM_DEATHS'] = 0;
        }
        
        $output .= ' (KDA '.$game['match']['summoner']['CHAMPIONS_KILLED'].'/'.$game['match']['summoner']['NUM_DEATHS'].'/'.$game['match']['summoner']['ASSISTS'].')';
        
        if($game['win'] == 1) {
          $output .= ' - WIN';
        } else {
          $output .= ' - LOSS';
        }
        $output .= ' (ID '.$game['match']['game_id'].')';
        $this->say($this->target, false, $output);
        break;
      }
    }
  }
  
?>