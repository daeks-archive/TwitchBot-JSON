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
          $this->say($this->target, false, ' - '.$cmd['help'].(isset($cmd['syntax']) ? ' - Syntax: '.$cmd['syntax'] : ''));
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
        }
      }
    } else {
      $this->say($this->target, false, ' - Syntax: '.$cmd['syntax']);
    }
  }
  
?>