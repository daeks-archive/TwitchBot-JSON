<?php

  $cmd = array('level' => 'admin owner',
               'count' => false,
               'help' => 'Shows existing plugin');
  
  if($execute) {
    if($this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[4])) {
        $this->data[4] = strtolower($this->data[4]);
        if(isset($this->db[$this->target]['config']['plugins'][$this->data[4]])) {
          $output = '';
          $plugin = $this->db[$this->target]['config']['plugins'][$this->data[4]];
          foreach($plugin as $key => $value) {
            $output .= $key.'='.$value.', ';
          }
          $output = rtrim($output, ', ');
          $this->say($this->target, true, 'Plugin: '.$this->data[4].' - Parameters: '.$output);
        }
      }
    }
  }
  
?>