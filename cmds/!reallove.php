<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Displays the real love');
  
  if($execute) {
    if(isset($this->data[4])) {
      //if(strtolower($this->data[4]) == strtolower(OWNER) || strtolower($this->data[4]) == strtolower('siggi')) {
      //  $this->say($this->target, false, 'There\'s -'.rand(0, 100000).'% real ... ERROR');
      //} else {
        $this->say($this->target, false, 'There\'s '.rand(0, 100).'% real <3 between '.$this->username.' and '.$this->data[4]);
      //}
    }
  }
  
?>