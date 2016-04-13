<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Adds yourself to possible boyfriend list');
  
  if($execute && false) {
    $this->say($this->target, true, '/color '.$this->colors[array_rand($this->colors)]);
    sleep(1);
    $this->say($this->target, true, '@'.$this->username.' I have listed your request as possible boyfriend for '.ucfirst(ltrim($this->target, '#').' Kappa'));
    $this->say($this->target, true, '/color '.COLOR);
  }
  
?>