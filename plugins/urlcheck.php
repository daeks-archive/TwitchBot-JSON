<?php

  $cmd = array('help' => 'Checks for spam');
  
  if($execute) {
    if($this->isnone($this->target) || $this->target == '#'.strtolower(BOTNAME)) {
      $output = '';
      for($i=3;$i<sizeof($this->data);$i++) {
        $output .= $this->data[$i].' ';
      }

      $regexp = "/[(http|https|ftp|ftps)\:\/\/}?[a-zA-Z0-9\-]+\.[a-zA-Z]{2,3}(\/\S*)?/";

      if(preg_match($regexp, ltrim($output, ':'), $match)) {
        $nurl = parse_url($match[0]);
        $socket = @fsockopen($nurl['host'], (isset($nurl['port'])? $nurl['port'] : 80), $errno, $errstr, 5);
        if($socket) {
            fclose($socket);
            $this->say(null, true, 'Found URL in '.$this->target.': '.$match[0]);
        }
      
         //$this->say($this->target, true, '/timeout '.$this->username);
         //$this->say($this->target, true, 'No links allowed, '.$this->username.' [warning]');
      }
    }
  }

?>