<?php

  $cmd = array('level' => 'owner',
               'count' => false,
               'help' => 'Checks the last error');

  if($execute) {
    if($this->isowner()) {
      if(file_exists(CACHE_PATH.DIRECTORY_SEPARATOR.'lasterror.db')) {
        $error = json_decode(file_get_contents(CACHE_PATH.DIRECTORY_SEPARATOR.'lasterror.db'), true);
        $this->say($this->target, true, 'Error: '.$error['message']. ' in '.basename($error['file'], ".php").' on line '.$error['line']);
        unlink(CACHE_PATH.DIRECTORY_SEPARATOR.'lasterror.db');
      } else {
        $this->say($this->target, true, 'No Error detected.');
      }
    }
  }

?>