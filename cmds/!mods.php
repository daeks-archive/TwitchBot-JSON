<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => false,
               'help' => 'Displays list of moderators');
  
  if($execute) {
    $mods = $this->db[$this->target]['config']['mods'];
    /*foreach($mods as $name) {
      if(in_array($name, $this->db['#'.strtolower(BOTNAME)]['config']['mods'])) {
        $mods = $this->remove($mods, $name);
      }
    }*/
    if(sizeof($mods) > 0) {
      $this->say($this->target, true, trim('- Mods of '.$this->target.': '.implode(' ', $mods)));
    }
  }
  
?>