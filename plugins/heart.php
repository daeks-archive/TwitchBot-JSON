<?php

  $cmd = array('help' => 'Heart Spam');
  
  if($execute) {
    
    $cachefile = 'heart.db';
    $msg = '<3';
    $delay = 300;
  
    if(file_exists(CACHE_PATH.DIRECTORY_SEPARATOR.$this->target.'.'.$cachefile)) {
      $time = file_get_contents(CACHE_PATH.DIRECTORY_SEPARATOR.$this->target.'.'.$cachefile);
      if((time()-$time) > $delay) {
        file_put_contents(CACHE_PATH.DIRECTORY_SEPARATOR.$this->target.'.'.$cachefile, time(), LOCK_EX);
        $this->say($this->target, true, $msg);
      }
    } else {
      file_put_contents(CACHE_PATH.DIRECTORY_SEPARATOR.$this->target.'.'.$cachefile, time(), LOCK_EX);
      $this->say($this->target, true, $msg);
    }
  }
?>