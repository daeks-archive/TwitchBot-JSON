<?php

  $cmd = array('level' => 'owner',
               'count' => false,
               'help' => 'Dumps DB');

  if($execute) {
    if($this->isowner()) {
      
      foreach($this->db as $target => $database) {
        foreach($database as $key => $value) {
          file_put_contents(DB_PATH.DIRECTORY_SEPARATOR.'dump'.DIRECTORY_SEPARATOR.$target.'.'.$key.'-'.time().'.dump', print_r($value, true), LOCK_EX);
        }
      }

      //$this->say($this->target, true, ' - Force saved DB');
    }
  }

?>