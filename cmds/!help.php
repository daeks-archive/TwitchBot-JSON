<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => false,
               'help' => 'Shows command help',
               'syntax' => '!help <command>');
  
  if($execute) {
    if(isset($this->data[4])) {
      if(file_exists(CMDS_PATH.DIRECTORY_SEPARATOR.$this->data[4].'.php')) {
        try {
          $execute = false;
          include(CMDS_PATH.DIRECTORY_SEPARATOR.$this->data[4].'.php');
          $levels = explode(' ', $cmd['level']);
          $this->say($this->target, false, ' - '.$cmd['help'].(isset($cmd['syntax']) ? ' - Syntax: '.$cmd['syntax'] : '').($levels[0] != 'none' ? ' ('.$this->levels[$levels[0]] : '').' cmd)');
        } catch (Exception $e) {
          $this->error($e);
        }
      } else {
        if($this->plugincmd($this->data[4]) != '') {
          try {
            $execute = false;
            include(PLUGINS_PATH.DIRECTORY_SEPARATOR.$this->plugincmd($this->data[4]).DIRECTORY_SEPARATOR.$this->data[4].'.php');
            $this->say($this->target, false, ' - '.$cmd['help'].(isset($cmd['syntax']) ? ' - Syntax: '.$cmd['syntax'] : ''));
          } catch (Exception $e) {
            $this->error($e);
          }
        } else {
          if($this->data[4] == 'level') {
            $this->say($this->target, false, ' - Possible user levels: '.implode(' ', array_keys($this->levels)));
          }
        }
      }
    } else {
      $this->say($this->target, false, ' - Syntax: '.$cmd['syntax']);
    }
  }
  
?>