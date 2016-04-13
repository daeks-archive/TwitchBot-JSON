<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Song Request troll command');
  
  if($execute) {
    if(isset($this->data[4])) {
      $this->say($this->target, false, '@'.$this->username.' - Your request was added to the haze. It will be played eventually in '.date('Y'). ' Kappa');
    } else {
      $this->say($this->target, false, '@'.$this->username.' - Please provide a valid youtube url but dont expect anything Kappa');
    }
  }
  
?>