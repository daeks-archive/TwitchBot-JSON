<?php

  $cmd = array('level' => 'permit op admin owner',
               'count' => false,
               'help' => 'Add a text based quote');
  
  if($execute) {
    if($this->isop($this->target) || $this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[4])) {
        $output = '';
        for($i=4;$i<sizeof($this->data);$i++) {
          $output .= $this->data[$i].' ';
        }
        $quote = array ('msg' => trim($output), 'time' => time());
        $this->db[$this->target]['data']['quotes'] = $this->add($this->db[$this->target]['data']['quotes'], $quote);
        $this->save();
        $this->say($this->target, true, '@'.$this->username.' quote added');
      }
    }
  }
  
?>