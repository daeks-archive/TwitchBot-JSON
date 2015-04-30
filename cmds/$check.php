<?php

  $cmd = array('level' => 'owner',
               'count' => false,
               'help' => 'Checks <command>');

  if($execute) {
    if($this->isowner()) {
      if(isset($this->data[4])) {
        if(file_exists(CMDS_PATH.DIRECTORY_SEPARATOR.$this->data[4].'.php')) {
          $output = shell_exec('php -l "'.CMDS_PATH.DIRECTORY_SEPARATOR.$this->data[4].'.php"');
          $output = str_replace(array(chr(10), chr(13)), ' ', $output);
          $syntaxError = preg_replace("/Errors parsing.*$/", "", $output, -1, $count);
          $this->say($this->target, true, ' - '.trim(str_replace('.php', '', $output)));
        } else {
          if($this->plugincmd($this->data[4]) != '') {
            if(file_exists(PLUGINS_PATH.DIRECTORY_SEPARATOR.$this->plugincmd($this->data[4]).DIRECTORY_SEPARATOR.$this->data[4].'.php')) {
              $output = shell_exec('php -l "'.PLUGINS_PATH.DIRECTORY_SEPARATOR.$this->plugincmd($this->data[4]).DIRECTORY_SEPARATOR.$this->data[4].'.php"');
              $output = str_replace(array(chr(10), chr(13)), ' ', $output);
              $syntaxError = preg_replace("/Errors parsing.*$/", "", $output, -1, $count);
              $this->say($this->target, true, ' - '.trim(str_replace('.php', '', $output)));
            }
          } else {
            if(strtolower($this->data[4]) == strtolower(BOTNAME)) {
              $output = shell_exec('php -l "'.$_SERVER['SCRIPT_FILENAME'].'"');
              $output = str_replace(array(chr(10), chr(13)), ' ', $output);
              $syntaxError = preg_replace("/Errors parsing.*$/", "", $output, -1, $count);
              $this->say($this->target, false, ' - '.trim(str_replace('.php', '', $output)));
            }
          }
        }
      }
    }
  }

?>