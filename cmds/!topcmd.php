<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Shows the most used command');
  
  if($execute) {
    if(sizeof($this->db[$this->target]['stats']['cmds']) > 0) {
      $count = 0;
      $output = '';
      foreach($this->db[$this->target]['stats']['cmds'] as $key => $value) {
        if($value > $count) {
          $output = $key;
          $count = $value;
        }
      }
      $this->say($this->target, false, '- Most used command is: '.$output.' - '.$count.' hits');
    }
  }
  
?>