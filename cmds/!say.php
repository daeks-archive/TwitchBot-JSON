<?php

  $cmd = array('level' => 'owner',
               'count' => false,
               'help' => 'Says <text> to <channel>');

  if($execute) {
    if($this->isowner()) {
      if(isset($this->data[5])) {
        $output = '';
        for($i=5;$i<sizeof($this->data);$i++) {
          $output .= $this->data[$i].' ';
        }
        $this->say('#'.ltrim($this->data[4], '#'), true, $output);
      }
    }
  }

?>