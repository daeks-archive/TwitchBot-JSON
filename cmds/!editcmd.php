<?php

  $cmd = array('level' => 'op admin owner',
               'count' => false,
               'help' => 'Edit existing command with new text');
  
  if($execute) {
    if($this->isop($this->target) || $this->isadmin($this->target) || $this->isowner()) {
      if(isset($this->data[5])) {
        if(in_array(substr($this->data[4], 0, 1), $this->cmdtriggers)) {
          $this->data[4] = strtolower($this->data[4]);
          if(array_key_exists($this->data[4], $this->db[$this->target]['data']['cmds'])) {
            $output = '';
            for($i=5;$i<sizeof($this->data);$i++) {
              $output .= $this->data[$i].' ';
            }
            if(strlen(trim($output)) < 400) {
              unset($this->db[$this->target]['data']['cmds'][$this->data[4]]);
              $tmp = array('enabled' => true, 'level' => 'none', 'text' => trim($output));
              $this->db[$this->target]['data']['cmds'][$this->data[4]] = $tmp;
              $this->save();
              $this->say($this->target, true, '@'.$this->username.' command edited');
            } else {
              $this->say($this->target, true, '@'.$this->username.' command excided 400 chars');
            }
          }
        } else if(substr($this->data[4], 0, 4) == '-ul=') {
          $this->data[4] = strtolower($this->data[4]);
          $this->data[5] = strtolower($this->data[5]);
          if(array_key_exists($this->data[5], $this->db[$this->target]['data']['cmds'])) {
            $output = '';
            for($i=6;$i<sizeof($this->data);$i++) {
              $output .= $this->data[$i].' ';
            }
            if(strlen(trim($output)) < 400) {
              unset($this->db[$this->target]['data']['cmds'][$this->data[5]]);
              $tmp = array('enabled' => true, 'level' => strtolower(ltrim($this->data[4], '-ul=')), 'text' => trim($output));
              $this->db[$this->target]['data']['cmds'][$this->data[5]] = $tmp;
              $this->save();
              $this->say($this->target, true, '@'.$this->username.' command edited');
            } else {
              $this->say($this->target, true, '@'.$this->username.' command excided 400 chars');
            }
          }
        }
      }
    }
  }
  
?>