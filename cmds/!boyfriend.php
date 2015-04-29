<?php

  $cmd = array('level' => 'none',
               'count' => true,
               'help' => 'Adds yourself to possible boyfriend list');
  
  if($execute) {
    $this->say($this->target, true, '/color '.$this->colors[rand(0, sizeof($this->colors))]);
    sleep(1);
    $this->say($this->target, false, '@'.$this->username.' I have listed your request as possible boyfriend for '.ucfirst(ltrim($this->target, '#').' Kappa'));
    $this->say($this->target, true, '/color '.$this->color);
  }
  
?>