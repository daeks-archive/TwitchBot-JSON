<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Bot loves nobody');
  
  if($execute) {
    if(isset($this->data[4])) {
      if(strtolower($this->data[4]) == strtolower(BOTNAME)) {
        $this->say($this->target, false, '@'.$this->username.' - Moobot lies, my one and only love is '.ucfirst(ltrim($this->target, '#')).' <3');
      }
    }
  }
  
?>