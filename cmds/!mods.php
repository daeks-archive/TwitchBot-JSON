<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => false,
               'help' => 'Displays list of moderators');
  
  if($execute) {
    $chat = array();
    $ch2 = curl_init(); 
    curl_setopt($ch2, CURLOPT_URL, 'http://tmi.twitch.tv/group/user/'.ltrim($this->target, '#').'/chatters');
    curl_setopt($ch2, CURLOPT_HEADER, 0);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 5);
    $tmp2 = curl_exec($ch2);
    if($tmp2 != '') {
      $chat = json_decode($tmp2, true);
    }
    
    if(isset($chat['chatters']['moderators'])) {
      foreach($chat['chatters']['moderators'] as $mod) {
        if(!in_array($mod, $this->db[$this->target]['config']['mods'])) {
          $this->db[$this->target]['config']['mods'] = $this->add($this->db[$this->target]['config']['mods'], $mod);
        }
      }
    }

    if(sizeof($this->db[$this->target]['config']['mods']) > 0) {
      $this->say($this->target, true, trim('- Mods of '.$this->target.': '.implode(' ', $this->db[$this->target]['config']['mods'])));
    }
  }
  
?>