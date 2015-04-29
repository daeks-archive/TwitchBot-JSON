<?php

  $cmd = array('level' => 'permit op admin owner',
               'count' => false,
               'help' => 'Add a text based quote');
  
  if($execute) {
    if($this->isop($this->target) || $this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[5]) && (substr($this->data[4], 0, 1) == '!')) {
        $output = '';
        for($i=5;$i<sizeof($this->data);$i++) {
          $output .= $this->data[$i].' ';
        }
        $this->db[$this->target]['data']['quotes'][$this->data[4]] = trim($output);
        $this->save();
        $this->say($this->target, true, '@'.$this->username.' quote added');
      }
    }
  }
  
?>