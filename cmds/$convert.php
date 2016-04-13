<?php

  $cmd = array('level' => 'owner',
               'count' => false,
               'help' => 'Converts database');

  if($execute) {
    if($this->isowner()) {
      foreach($this->db as $target => $database) {
        if (!file_exists(DB_PATH.DIRECTORY_SEPARATOR.$target) && !is_dir(DB_PATH.DIRECTORY_SEPARATOR.$target)) {
          mkdir(DB_PATH.DIRECTORY_SEPARATOR.$target);         
        }       
        foreach($database as $table => $data) {
          if (!file_exists(DB_PATH.DIRECTORY_SEPARATOR.$target.DIRECTORY_SEPARATOR.$table) && !is_dir(DB_PATH.DIRECTORY_SEPARATOR.$target.DIRECTORY_SEPARATOR.$table)) {
            mkdir(DB_PATH.DIRECTORY_SEPARATOR.$target.DIRECTORY_SEPARATOR.$table);         
          } 
        
          array_walk_recursive($data, function(&$item, $table){
              if(!mb_detect_encoding($item, 'utf-8', true)){
                      $item = utf8_encode($item);
              }
          });
          
          foreach ($data as $key => $value) {
            if(is_array($value)) {
              foreach ($value as $subkey => $subvalue) {
                if (!file_exists(DB_PATH.DIRECTORY_SEPARATOR.$target.DIRECTORY_SEPARATOR.$table.DIRECTORY_SEPARATOR.$key) && !is_dir(DB_PATH.DIRECTORY_SEPARATOR.$target.DIRECTORY_SEPARATOR.$table.DIRECTORY_SEPARATOR.$key)) {
                  mkdir(DB_PATH.DIRECTORY_SEPARATOR.$target.DIRECTORY_SEPARATOR.$table.DIRECTORY_SEPARATOR.$key);         
                } 
                if($subkey != '!elo?') {
                file_put_contents(DB_PATH.DIRECTORY_SEPARATOR.$target.DIRECTORY_SEPARATOR.$table.DIRECTORY_SEPARATOR.$key.DIRECTORY_SEPARATOR.$subkey, $subvalue, LOCK_EX);
                }
              }
            } else {
              file_put_contents(DB_PATH.DIRECTORY_SEPARATOR.$target.DIRECTORY_SEPARATOR.$table.DIRECTORY_SEPARATOR.$key, $value, LOCK_EX);
            }
          }
        }
      }
    }
  }

?>