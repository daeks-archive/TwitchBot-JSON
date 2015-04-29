<?php

  $cmd = array('level' => 'admin owner',
               'count' => false,
               'help' => 'Shows all enabled plugins');
  
  if($execute) {
    if($this->isadmin($this->target) || $this->isowner()) {
      if(sizeof($this->db[$this->target]['config']['plugins']) > 0) {
        $this->say($this->target, true, ' - Enabled Plugins: '.implode(' ',array_keys($this->db[$this->target]['config']['plugins'])));
      } 
    }
  }
  
?>