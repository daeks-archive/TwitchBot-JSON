<?php

  $cmd = array('level' => 'permit op admin owner',
               'count' => false,
               'help' => 'Add a text based quote');
  
  if($execute) {
    if($this->ispermit($this->target) || $this->isop($this->target) || $this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[4])) {
        $output = '';
        for($i=4;$i<sizeof($this->data);$i++) {
          $output .= $this->data[$i].' ';
        }
        if(strlen(trim($output)) < 400) {
          $quote = array ('msg' => trim($output), 'time' => time());
          $this->db[$this->target]['data']['quotes'] = $this->add($this->db[$this->target]['data']['quotes'], $quote);
          $this->save();
          $this->say($this->target, true, '@'.$this->username.' quote added - #'.array_search($quote, $this->db[$this->target]['data']['quotes']));
        } else {
          $this->say($this->target, true, '@'.$this->username.' quote excided 400 chars');
        }
      }
    }
  }
  
?>