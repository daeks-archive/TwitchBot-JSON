<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Displays the hate');
  
  if($execute) {
    if(isset($this->data[4])) {
        $this->say($this->target, false, 'There\'s absolutly no <3 between '.$this->username.' and '.$this->data[4]);
    }
  }
  
?>