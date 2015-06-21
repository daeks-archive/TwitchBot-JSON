<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Displays all quote numbers');
  
  if($execute) {
    if(sizeof($this->db[$this->target]['data']['quotes']) > 0) {
      $this->say($this->target, false, '@'.$this->username.' - Available quotes are: #'.implode(' #', array_keys($this->db[$this->target]['data']['quotes'])));
    }
  }
  
?>