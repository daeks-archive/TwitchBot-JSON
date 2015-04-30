<?php

  $cmd = array('level' => 'owner',
               'count' => false,
               'help' => 'Reload configuration');

  if($execute) {
    if($this->isowner()) {
      $this->save();
      $output = shell_exec('php -l "'.$_SERVER['SCRIPT_FILENAME'].'"');
      $output = str_replace(array(chr(10), chr(13)), ' ', $output);
      $syntaxError = preg_replace("/Errors parsing.*$/", "", $output, -1, $count);
      if($count > 0) {
        $this->say($this->target, false, ' - '.trim(str_replace('.php', '', $output)));
      } else {
        $this->say(null, true, '@'.$this->username.' - reloading');
        $this->log('Reloading');
        exit(1);
      }
    }
  }

?>