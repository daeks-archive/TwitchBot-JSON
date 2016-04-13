<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Displays the brainpower');
  
  if($execute) {
    $this->say($this->target, false, 'There\'s currently only '.rand(0, 49).'% brain available <3');
  }
  
?>