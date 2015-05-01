<?php

  $cmd = array('level' => 'admin owner',
               'count' => true,
               'help' => 'Hosts user');
  
  if($execute) {
    if($this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[4])) {
        $this->say(null, true, '/host '.$this->data[4]);
        $this->say($this->target, true, '- I am hosting my true leader http://twitch.tv/'.$this->data[4]);
      } else {
        $this->say(null, true, '/host '.ltrim($this->target, '#'));
        $this->say($this->target, true, '- I am hosting my true leader http://twitch.tv/'.ucfirst(ltrim($this->target, '#')));
      }
    }
  }
  
?>