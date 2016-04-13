<?php
    
  define('DB_PATH', 'db');
  define('CMDS_PATH', 'cmds');
  define('PLUGINS_PATH', 'plugins');
  define('LOG_PATH', 'logs');
  define('CACHE_PATH', 'cache');

  register_shutdown_function('CatchFatalError');
  function CatchFatalError() {
    $error = error_get_last();
    $ignore = E_WARNING | E_NOTICE | E_USER_WARNING | E_USER_NOTICE | E_STRICT | E_DEPRECATED | E_USER_DEPRECATED;
    if (($error['type'] & $ignore) == 0) {
      file_put_contents(CACHE_PATH.DIRECTORY_SEPARATOR.'error.db', json_encode($error, JSON_FORCE_OBJECT), LOCK_EX);
    }
  }
  
  set_error_handler('exceptions_error_handler');
  function exceptions_error_handler($severity, $message, $filename, $lineno) {
    if (error_reporting() == 0) {
      return;
    }
    if (error_reporting() & $severity) {
      throw new ErrorException($message, 0, $severity, $filename, $lineno);
    }
  }

  require_once('config.php');
  
  set_time_limit(0);
  error_reporting(E_ALL);
  ini_set('display_errors', 'on');
  ini_set('output_buffering', 'off');
  ini_set('zlib.output_compression', false);
  while (@ob_end_flush());
  ini_set('implicit_flush', true);
  ob_implicit_flush(true);

  $bot = new twitchbot(filemtime(__FILE__));
                         
  class twitchbot {
  
    private $version;
    
    private $server = 'irc.twitch.tv';
    private $port = 6667;

    private $mode;
    private $command;
    private $target;
    private $username;
    
    private $socket;
    private $data;
    private $stats;
    private $db;
    
    private $error;
    private $tmp = array();
    
    // Constants;
    private $cmdtriggers = array('!', '$');
    private $levels = array('none' => 'all', 'permit' => 'permitted user', 'op' => 'moderator', 'admin' => 'broadcaster', 'owner' => 'owner');
    private $storages = array('data', 'stats', 'config');
    private $tmp_tables = array('history', 'plugins', 'active');
    private $data_tables = array('quotes', 'cmds');
    private $stats_tables = array('cmds');
    private $config_tables = array('plugins', 'mods', 'permits', 'banned_cmds', 'banned_users', 'options');
    private $colors = array('Blue', 'Coral', 'DodgerBlue', 'SpringGreen', 'YellowGreen', 'Green', 'OrangeRed', 'Red', 'GoldenRod', 'HotPink', 'CadetBlue', 'SeaGreen', 'Chocolate', 'BlueViolet', 'Firebrick');
    private $userentry = array('add' => null, 'join' => null, 'part' => null, 'account' => null, 'points' => 0, 'coins' => 100, 'violations' => 0, 'count' => 1);
     
    function __construct($version) { 
      $this->version = date('YmdHi', $version);
      
      if(file_exists(CACHE_PATH.DIRECTORY_SEPARATOR.'error.db')) {
        $this->error = json_decode(file_get_contents(CACHE_PATH.DIRECTORY_SEPARATOR.'error.db'), true);
        if(isset($this->error)) {
          rename(CACHE_PATH.DIRECTORY_SEPARATOR.'error.db', CACHE_PATH.DIRECTORY_SEPARATOR.'lasterror.db');
        } else {
          unlink(CACHE_PATH.DIRECTORY_SEPARATOR.'error.db');
        }
      }
      
      foreach($this->tmp_tables as $table) {
        $this->tmp[$table] = array();
      }
        
      $this->socket = fsockopen($this->server, $this->port);
      $this->send('PASS '.OAUTH);
      $this->send('NICK '.BOTNAME);
      $this->send('USER '.BOTNAME.' '.BOTNAME.' '.BOTNAME.' '.BOTNAME);
      
      $this->db['#'.strtolower(BOTNAME)]['channels'] = $this->load('#'.strtolower(BOTNAME).'.channels.db');
      if(!in_array('#'.strtolower(BOTNAME), $this->db['#'.strtolower(BOTNAME)]['channels'])) {
        $this->db['#'.strtolower(BOTNAME)]['channels'] = $this->add($this->db['#'.strtolower(BOTNAME)]['channels'], '#'.strtolower(BOTNAME));
      }
      $this->save();
      
      foreach($this->db['#'.strtolower(BOTNAME)]['channels'] as $channel) {
        $this->init($channel);        
        $this->send('JOIN '.$channel);
      }
      
      $this->db['#'.strtolower(BOTNAME)]['channels'] = $this->remove($this->db['#'.strtolower(BOTNAME)]['channels'], '#'.strtolower(BOTNAME));
      $this->say(null, true, '/color '.COLOR);
      if(isset($this->error)) {
        $this->log('Error: '.$this->error['message']. ' in '.basename($this->error['file'], ".php").' on line '.$this->error['line']);
        $this->say(null, true, 'Error: '.$this->error['message']. ' in '.basename($this->error['file'], ".php").' on line '.$this->error['line']);
      }
      $this->listen();
    }
 
    function listen() {            
      $line = fgets($this->socket, 256);
      if(strlen(trim($line)) > 0) {
        $this->log($line);
        flush();
        $this->data = explode(' ', str_replace(array(chr(10), chr(13)), '', $line));        
        if($this->data[0] != 'PING') {
          if(sizeof($this->data) >= 3) {
            $tmp = explode('!', ltrim($this->data[0], ':'));
            if(isset($tmp[0])) {
              $this->username = $tmp[0];
            } else {
              $this->username = ltrim($this->data[0], ':');
            }
            $this->mode = $this->data[1];
            $this->target = $this->data[2];
            switch($this->mode) {  
              case 'JOIN':
                if(ltrim($this->data[0], ':') == strtolower(BOTNAME.'!'.BOTNAME.'@'.BOTNAME.'.tmi.twitch.tv')) {
                  if(!in_array(OWNER, $this->db[$this->target]['config']['mods'])) {
                    $this->db[$this->target]['config']['mods'] = $this->add($this->db[$this->target]['config']['mods'], OWNER);
                  }
                  
                  if($this->target == '#'.strtolower(BOTNAME)) {
                    $this->say($this->target, true, '/mod '.OWNER);
                    $this->say(null, true, 'Loaded v'.$this->version);
                  } else {
                    $this->say($this->target, true, '/me is dancing again. HeyGuys');
                    $this->say(null, true, 'Joined '.$this->target);
                    
                    $chat = array();
                    $ch2 = curl_init(); 
                    curl_setopt($ch2, CURLOPT_URL, 'http://tmi.twitch.tv/group/user/'.ltrim($this->target, '#').'/chatters');
                    curl_setopt($ch2, CURLOPT_HEADER, 0);
                    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1); 
                    curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 5);
                    $tmp2 = curl_exec($ch2);
                    if($tmp2 != '') {
                      $chat = json_decode($tmp2, true);
                    }
                    
                    if(isset($chat['chatters']['moderators'])) {
                      foreach($chat['chatters']['moderators'] as $mod) {
                        if(!in_array($mod, $this->db[$this->target]['config']['mods'])) {
                          $this->db[$this->target]['config']['mods'] = $this->add($this->db[$this->target]['config']['mods'], $mod);
                        }
                      }
                    }                   
                  }
                } else {
                  /*if(!isset($this->db[$this->target]['users'][$this->username])) {
                    $this->db[$this->target]['users'][$this->username] = $this->userentry;
                    $this->db[$this->target]['users'][$this->username]['add'] = time();
                    $this->db[$this->target]['users'][$this->username]['join'] = time();
                  } else {
                    $this->db[$this->target]['users'][$this->username]['join'] = time();
                    $this->db[$this->target]['users'][$this->username]['count'] = $this->db[$this->target]['users'][$this->username]['count'] + 1;
                  }
                  $this->save();*/
                  
                  if($this->username == ltrim($this->target,'#')) {
                    $this->say($this->target, true, 'Welcome, '.$this->username);
                    $this->tmp['active'] = $this->add($this->tmp['active'], $this->username);
                    $this->say(null, true, 'Possible live stream detected - '.$this->username.' joined '.$this->target);
                  }
                }
              break;
              case 'PART':
                if(ltrim($this->data[0], ':') == strtolower(BOTNAME.'!'.BOTNAME.'@'.BOTNAME.'.tmi.twitch.tv')) {
                    $this->say($this->target, true, '/me is dancing somewhere else. PJSalt');
                    $this->say(null, true, 'Parted '.$this->target);
                } else {
                  if($this->username == ltrim($this->target,'#')) {
                    $this->tmp['active'] = $this->remove($this->tmp['active'], $this->username);
                  }
                  /*if(isset($this->db[$this->target]['users'][$this->username])) {
                    $this->db[$this->target]['users'][$this->username]['part'] = time();
                    $this->save();
                  }*/
                }
              break;
              case '353':
                $start = array_search($this->data[4], $this->data)+1;
                for($i=$start;$i<sizeof($this->data);$i++) {
                  if(strtolower(ltrim($this->data[$i], ':')) == strtolower(ltrim($this->target,'#')) && strtolower(ltrim($this->data[$i], ':')) != strtolower(BOTNAME)) {
                    $this->tmp['active'] = $this->remove($this->tmp['active'], ltrim($this->data[$i], ':'));
                    $this->say(null, true, 'Possible live stream detected - '.ltrim($this->data[$i], ':').' found in '.$this->target);
                  }
                  /*if(!isset($this->db[$this->data[4]]['users'][ltrim($this->data[$i], ':')])) {
                    $this->db[$this->data[4]]['users'][ltrim($this->data[$i], ':')] = $this->userentry;
                    $this->db[$this->data[4]]['users'][ltrim($this->data[$i], ':')]['add'] = time();
                    $this->db[$this->data[4]]['users'][ltrim($this->data[$i], ':')]['join'] = time();
                  } else {
                    $this->db[$this->data[4]]['users'][ltrim($this->data[$i], ':')]['join'] = time();
                    $this->db[$this->data[4]]['users'][ltrim($this->data[$i], ':')]['count'] = $this->db[$this->data[4]]['users'][ltrim($this->data[$i], ':')]['count'] + 1;
                  }
                  $this->save();*/
                }
              break;
              case 'MODE':
                if($this->data[3] == '+o') {
                  if(!in_array($this->data[4], $this->db[$this->target]['config']['mods'])) {
                    $this->db[$this->target]['config']['mods'] = $this->add($this->db[$this->target]['config']['mods'], $this->data[4]);
                    if($this->target == '#'.strtolower(BOTNAME)) {
                      $this->say(null, true, 'Added '.$this->data[4].' to modlist');
                    }
                  }
                }
                if($this->data[3] == '-o') {
                  $this->db[$this->target]['config']['mods'] = $this->remove($this->db[$this->target]['config']['mods'], $this->data[4]);
                }
              break;
              case 'PRIVMSG':
                if($this->username == ltrim($this->target,'#') && strtolower($this->username) != strtolower(BOTNAME)) {
                  $this->tmp['active'] = $this->add($this->tmp['active'], $this->username);
                }
                if(isset($this->data[3])) {
                  $this->command = strtolower(ltrim($this->data[3], ':'));
                  if(in_array(substr($this->command, 0, 1), $this->cmdtriggers)) {
                    if($this->command == substr($this->command, 0, 1).strtolower(BOTNAME)) {
                      $this->command = substr($this->command, 0, 1).'BOTNAME';
                    }
                    if(!in_array($this->command, $this->db[$this->target]['config']['banned_cmds']) && !in_array($this->username, $this->db[$this->target]['config']['banned_users'])) {
                      if(file_exists(CMDS_PATH.DIRECTORY_SEPARATOR.$this->command.'.php')) {
                        try {
                          $execute = true;
                          include(CMDS_PATH.DIRECTORY_SEPARATOR.$this->command.'.php');
                        } catch (Exception $e) {
                          $this->error($e);
                        }
                      } else if($this->plugincmd($this->command) != '') {
                        try {
                          $execute = true;
                          include(PLUGINS_PATH.DIRECTORY_SEPARATOR.$this->plugincmd($this->command).DIRECTORY_SEPARATOR.$this->command.'.php');
                        } catch (Exception $e) {
                          $this->error($e);
                        }
                      } 
                      
                      if(array_key_exists($this->command, $this->db[$this->target]['data']['cmds'])) {
                        if($this->db[$this->target]['data']['cmds'][$this->command]['enabled']) {
                          $hasaccess = false;
                          if($this->db[$this->target]['data']['cmds'][$this->command]['level'] == 'none') {
                            $hasaccess = true;
                          } else {
                            $levels = explode(',', $this->db[$this->target]['data']['cmds'][$this->command]['level']);
                            foreach($levels as $level) {
                              if($level == 'permit') {
                                if($this->ispermit($this->target) || $this->isop($this->target) || $this->isadmin($this->target) || $this->isowner()) {
                                  $hasaccess = true;
                                  break;
                                }
                              } else if($level == 'op') {
                                if($this->isop($this->target) || $this->isadmin($this->target) || $this->isowner()) {
                                  $hasaccess = true;
                                  break;
                                }
                              } else if($level == 'admin') {
                                if($this->isadmin($this->target) || $this->isowner()) {
                                  $hasaccess = true;
                                  break;
                                }
                              } else if($level == 'owner') {
                                if($this->isowner()) {
                                  $hasaccess = true;
                                  break;
                                }
                              }
                            }
                            $hasaccess = false;
                          }
                          if($hasaccess) {
                            $message = str_replace('@user@', $this->username, $this->db[$this->target]['data']['cmds'][$this->command]['text']);
                            if(isset($this->data[4])) {
                              $message = str_replace('@touser@', $this->data[4], $message);
                            } else {
                              $message = str_replace('@touser@', 'nobody', $message);
                            }
                            $this->say($this->target, false, ''.$message);
                          }
                        }
                      }
                    } else {
                      if(isset($this->db[$this->target]['config']['plugins'])) {
                        foreach($this->db[$this->target]['config']['plugins'] as $ext => $config) {
                          if(file_exists(PLUGINS_PATH.DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR.$ext.'.php')) {
                            try {
                              $execute = true;
                              include(PLUGINS_PATH.DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR.$ext.'.php');
                            } catch (Exception $e) {
                              $this->error($e);
                            }
                          }
                        }
                      }
                    }
                    
                    $savestatistic = true;
                    if(file_exists(CMDS_PATH.DIRECTORY_SEPARATOR.$this->command.'.php')) {
                      $execute = false;
                      include(CMDS_PATH.DIRECTORY_SEPARATOR.$this->command.'.php');
                      $savestatistic = $cmd['count'];
                    }
                    
                    if($savestatistic) {
                      if(!isset($this->db[$this->target]['stats']['cmds'][$this->command])) {
                        $this->db[$this->target]['stats']['cmds'][$this->command] = 1;
                      } else {
                        $this->db[$this->target]['stats']['cmds'][$this->command] = $this->db[$this->target]['stats']['cmds'][$this->command] + 1;
                      }
                      $this->save();
                    }
                  } else {
                    if(isset($this->db[$this->target]['config']['plugins'])) {
                      foreach($this->db[$this->target]['config']['plugins'] as $ext => $config) {
                        if(file_exists(PLUGINS_PATH.DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR.$ext.'.php')) {
                          try {
                            $execute = true;
                            include(PLUGINS_PATH.DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR.$ext.'.php');
                          } catch (Exception $e) {
                            $this->error($e);
                          }
                        }
                      }
                    }
                  }
                }
              break;
              default:
                //$this->log('Unknown mode'.$this->mode);
            }
          }
        } else {
          $this->send('PONG '.$this->data[1]);
        }
        flush();
      }
      $this->listen();
    }
    
    function say($channel, $force, $message) {
      if($channel == null) {
        $channel = '#'.strtolower(BOTNAME);
      }
      if($force || $this->isop($channel) || $this->ispermit($channel) || !isset($this->tmp['history'][$channel]) || (time()-$this->tmp['history'][$channel]) > DELAY ) {
        $this->send('PRIVMSG '.$channel.' :'.$message);
        $this->tmp['history'][$channel] = time();
      }
    }
        
    function load($database) {
      $output = array();      
      if(file_exists(DB_PATH.DIRECTORY_SEPARATOR.$database)) {
        $output = json_decode(file_get_contents(DB_PATH.DIRECTORY_SEPARATOR.$database), true);
      }
      return $output;
    }
    
    function save() {
      foreach($this->db as $target => $database) {
        foreach($database as $key => $value) {
        
          array_walk_recursive($value, function(&$item, $key){
              if(!mb_detect_encoding($item, 'utf-8', true)){
                      $item = utf8_encode($item);
              }
          });
        
          $output = json_encode($value, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE);
          if(json_last_error() === JSON_ERROR_NONE) {
            file_put_contents(DB_PATH.DIRECTORY_SEPARATOR.$target.'.'.$key.'.db', $output, LOCK_EX);
          } else {
            $this->log('ERROR - Unable to save DB '.$target.'.'.$key.'.db - '.json_last_error_msg());
            $this->say(null, true, ' - Unable to save DB '.$target.'.'.$key.'.db - '.json_last_error_msg());
          }
        }
      }
    }
    
    function plugincmd($command) {
      $output = '';
      foreach($this->db[$this->target]['config']['plugins'] as $ext => $config) {
        if(file_exists(PLUGINS_PATH.DIRECTORY_SEPARATOR.$ext.DIRECTORY_SEPARATOR.$command.'.php')) {
          $output = $ext;
          break;
        }
      }
      return $output;
    }
    
    function isnone($channel) {
      return !$this->ispermit($channel) && !$this->isop($channel) && !$this->isadmin($channel) && !$this->isowner();
    }
    
    function ispermit($channel) {
      return in_array(strtolower($this->username), array_map('strtolower', $this->db[$channel]['config']['permits']));
    }

    function isop($channel) {
      if(in_array(strtolower($this->username), array_map('strtolower', $this->db[$channel]['config']['mods']))) {
        return true;
      } else {
        $chat = array();
        $ch2 = curl_init(); 
        curl_setopt($ch2, CURLOPT_URL, 'http://tmi.twitch.tv/group/user/'.ltrim($this->target, '#').'/chatters');
        curl_setopt($ch2, CURLOPT_HEADER, 0);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 5);
        $tmp2 = curl_exec($ch2);
        if($tmp2 != '') {
          $chat = json_decode($tmp2, true);
        }
        
        if(isset($chat['chatters']['moderators'])) {
          foreach($chat['chatters']['moderators'] as $mod) {
            if(!in_array($mod, $this->db[$this->target]['config']['mods'])) {
              $this->db[$this->target]['config']['mods'] = $this->add($this->db[$this->target]['config']['mods'], $mod);
            }
          }
        }
        return in_array(strtolower($this->username), array_map('strtolower', $this->db[$channel]['config']['mods']));
      }
    }
    
    function isadmin($channel) {
      return (strtolower(ltrim($channel, '#')) == strtolower($this->username)) ? true : false;
    }
    
    function isowner() {
      return in_array(strtolower($this->username), array_map('strtolower', $this->db['#'.strtolower(BOTNAME)]['config']['mods']));
    }

    function send($cmd) {
      fputs($this->socket, $cmd."\r\n");
      $this->log($cmd);
      flush();
    }
    
    function add($cache, $object) {
      if(!in_array($object, $cache)) {
        array_push($cache, $object);
      }
      return $cache;
    }
    
    function remove($cache, $object) {
      if(($key = array_search($object, $cache)) !== false) {
        unset($cache[$key]);
      }
      return $cache;
    }
    
    function init($channel) {
      foreach($this->storages as $database) {
        $this->db[$channel][$database] = $this->load($channel.'.'.$database.'.db');
      }
      
      foreach($this->data_tables as $name) {
        if(!isset($this->db[$channel]['data'][$name])) {
          $this->db[$channel]['data'][$name] = array();
        }
      }
      
      foreach($this->stats_tables as $name) {
        if(!isset($this->db[$channel]['stats'][$name])) {
          $this->db[$channel]['stats'][$name] = array();
        }
      } 
      
      foreach($this->config_tables as $name) {
        if(!isset($this->db[$channel]['config'][$name])) {
          $this->db[$channel]['config'][$name] = array();
        }
      }  
    }
    
    function destroy($channel, $purge) {
      foreach($this->storages as $database) {
        unset($this->db[$channel][$database]);
        if($purge) {
          if(file_exists(DB_PATH.DIRECTORY_SEPARATOR.$channel.'.'.$database.'.db')) {
            unlink(DB_PATH.DIRECTORY_SEPARATOR.$channel.'.'.$database.'.db');
          }
        }
      }
    }
    
    function error($e) {
      $this->log('ERROR: '.$e->getMessage(). ' in '.str_replace(dirname(__FILE__).DIRECTORY_SEPARATOR, '', str_replace('.php', '', $e->getFile())).' on line '.$e->getLine());
      $this->say($this->target, true, 'Error: '.$e->getMessage(). ' in '.str_replace(dirname(__FILE__).DIRECTORY_SEPARATOR, '', str_replace('.php', '', $e->getFile())).' on line '.$e->getLine());
    }
       
    function log($msg) {
      $msg = str_replace(array(chr(10), chr(13)), '', $msg);
      if($_SERVER['DOCUMENT_ROOT'] != '') {
        echo nl2br(trim($msg)).'<br>';
      } else {
        file_put_contents(LOG_PATH.DIRECTORY_SEPARATOR.get_class($this).'-'.date('Ymd').'.log', $msg.PHP_EOL, FILE_APPEND | LOCK_EX);
      }
    }
}

?>