<?php

  $cmd = array('level' => 'op admin owner',
               'count' => false,
               'help' => 'Add new command to display text');
  
  if($execute) {
    if($this->isop($this->target) || $this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[5])) {
        $this->data[4] = strtolower($this->data[4]);
        if(substr($this->data[4], 0, 1) == '!') {
          $found = false;
          foreach($this->db[$this->target]['config']['plugins'] as $ext => $config) {
            if(file_exists(PLUGINS_PATH.DIRECTORY_SEPARATOR.$ext.DIRECTORY_SEPARATOR.$this->data[4].'.php')) {
              $found = true;
              break;
            }
          }
          if(file_exists(CMDS_PATH.DIRECTORY_SEPARATOR.$this->data[4].'.php') || array_key_exists($this->data[4], $this->db[$this->target]['data']['cmds'])) {
            $found = true;
          }
          if(!$found) {
            $output = '';
            for($i=5;$i<sizeof($this->data);$i++) {
              $output .= $this->data[$i].' ';
            }
            $tmp = array('enabled' => true, 'level' => 'none', 'text' => trim($output));
            $this->db[$this->target]['data']['cmds'][$this->data[4]] = $tmp;
            $this->save();
            $this->say($this->target, true, '@'.$this->username.' command added');
          } else {
            $this->say($this->target, true, '@'.$this->username.' command already exists');
          }
        } else if(substr($this->data[4], 0, 4) == '-ul=') {
          $this->data[5] = strtolower($this->data[5]);
          $found = false;
          foreach($this->db[$this->target]['config']['plugins'] as $ext => $config) {
            if(file_exists(PLUGINS_PATH.DIRECTORY_SEPARATOR.$ext.DIRECTORY_SEPARATOR.$this->data[5].'.php')) {
              $found = true;
              break;
            }
          }
          if(file_exists(CMDS_PATH.DIRECTORY_SEPARATOR.$this->data[5].'.php') || array_key_exists($this->data[5], $this->db[$this->target]['data']['cmds'])) {
            $found = true;
          }
          
          if(!$found) {
            $output = '';
            for($i=6;$i<sizeof($this->data);$i++) {
              $output .= $this->data[$i].' ';
            }
            $tmp = array('enabled' => true, 'level' => strtolower(ltrim($this->data[4], '-ul=')), 'text' => trim($output));
            $this->db[$this->target]['data']['cmds'][$this->data[5]] = $tmp;
            $this->save();
            $this->say($this->target, true, '@'.$this->username.' command added');
          } else {
            $this->say($this->target, true, '@'.$this->username.' command already exists');
          }
        }
      }
    }
  }
  
?>