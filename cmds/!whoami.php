<?php

  $cmd = array('level' => '',
               'help' => 'Shows version information');

  $this->say($this->target, false, '@'.$this->username.' I am '.BOTNAME.' (v'.$this->version.') - (c) daeks '.date('Y').' MIT license');

?>