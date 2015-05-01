<?php

  $cmd = array('level' => 'op admin',
               'count' => true,
               'help' => 'Bot goes crazy for some seconds. Syntax: !riot <text>');
  
  if($execute) {
    if($this->isop($this->target) || $this->isadmin($this->target)) {
      if(isset($this->data[4])) {
        $output = '';
        for($i=4;$i<sizeof($this->data);$i++) {
          $output .= $this->data[$i].' ';
        }
        for($i=0;$i<3;$i++) {
          $this->say($this->target, true, '/color '.$this->colors[array_rand($this->colors)]);
          sleep(1);
          $this->say($this->target, false, '/me warns: '.$output.' or RIOT');
        }
        $this->say($this->target, true, '/color '.COLOR);
      } else {
        $this->say($this->target, false, '@'.$this->username.' - no useless riot pls - Syntax: !riot <message>');
      }
    }
  }
  
?>