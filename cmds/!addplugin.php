<?php

  $cmd = array('level' => 'admin owner',
               'count' => false,
               'help' => 'Add plugin');
  
  if($execute) {
    if($this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[4])) {
        $this->data[4] = strtolower($this->data[4]);
        if(!isset($this->db[$this->target]['config']['plugins'][$this->data[4]])) {
          $plugin = array();
          for($i=5;$i<sizeof($this->data);$i++) {
            $tmp = explode('=', $this->data[$i]);
            if(sizeof($tmp) == 2) {
              $plugin[$tmp[0]] = $tmp[1];
            }
          }
          $this->db[$this->target]['config']['plugins'][$this->data[4]] = $plugin;
          $this->save();
          $this->say($this->target, true, '@'.$this->username.' plugin added');
        }
      } 
    }
  }
  
?>