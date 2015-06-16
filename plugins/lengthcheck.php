<?php

  $cmd = array('help' => 'Checks for spam');
  
  if($execute) {
    if($this->isnone($this->target) || $this->target == '#'.strtolower(BOTNAME)) {
      $output = '';
      for($i=3;$i<sizeof($this->data);$i++) {
        $output .= $this->data[$i].' ';
      }

      if(strlen($output) > 400) {
         $this->say(null, true, 'Spam found in '.$this->target.': '.strlen($output).' chars');
         
         //$this->say($this->target, true, '/timeout '.$this->username);
         //$this->say($this->target, true, 'No Spam, '.$this->username.' - '.strlen($output).' chars [warning]');
      }
    }
  }

?>