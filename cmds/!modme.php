<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Checks for mod rights');
  
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
    
    $mods = $this->db[$this->target]['config']['mods'];
    if(sizeof($mods) > 0 && ($this->isop($this->target) || $this->isadmin($this->target) || $this->isowner())) {
      $this->say($this->target, true, '@'.$this->username.' - Added you successfully as MOD for '.BOTNAME);
    }
    
  }
  
?>