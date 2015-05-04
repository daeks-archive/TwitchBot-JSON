<?php

  $cmd = array('level' => 'admin owner',
               'count' => false,
               'help' => 'Checks for viewerbots');

  if($execute) {
    if($this->isowner($this->target) || $this->isowner()) {
      $stream = array();
      $ch = curl_init(); 
      curl_setopt($ch, CURLOPT_URL, 'https://api.twitch.tv/kraken/streams/'.ltrim($this->target, '#'));
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      $tmp = curl_exec($ch);
      if($tmp != '') {
        $stream = json_decode($tmp, true);
      }
      
      if(isset($stream['stream'])) {
        $viewers = $stream['stream']['viewers'];
               
        $ch2 = curl_init(); 
        curl_setopt($ch2, CURLOPT_URL, 'http://tmi.twitch.tv/group/user/'.ltrim($this->target, '#').'/chatters');
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 5);
        $tmp2 = curl_exec($ch2);
        if($tmp2 != '') {
          $chat = json_decode($tmp2, true);
        }
        
        if(!isset($chat['chatter_count'])) {
          $chat['chatter_count'] = 'n/a';
        }
        
        $this->say($this->target, true, '@'.$this->username.' - Viewers: '.$viewers.' - Chatters: '.$chat['chatter_count']);
      } else {
        $this->say($this->target, true, '@'.$this->username.' - No active stream found');
      }
    }
  }

?>