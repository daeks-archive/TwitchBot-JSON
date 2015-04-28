<?php

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
    private $target;
    private $username;
    
    private $socket;
    private $data;
    private $stats;
    private $db;
    
    private $last = array();
    
    // Constants;
    private $storage = array('mods', 'permits', 'cmds', 'data', 'users', 'stats');
    private $colors = array('Blue', 'Coral', 'DodgerBlue', 'SpringGreen', 'YellowGreen', 'Green', 'OrangeRed', 'Red', 'GoldenRod', 'HotPink', 'CadetBlue', 'SeaGreen', 'Chocolate', 'BlueViolet', 'Firebrick');
    private $userentry = array('add' => null, 'join' => null, 'part' => null, 'account' => null, 'points' => 0, 'coins' => 100, 'violations' => 0, 'count' => 1);
     
    function __construct($version) { 
      $this->version = date('YmdHi', $version);
        
      $this->socket = fsockopen($this->server, $this->port);
      $this->send('PASS '.OAUTH);
      $this->send('NICK '.BOTNAME);
      $this->send('USER '.BOTNAME.' '.BOTNAME.' '.BOTNAME.' '.BOTNAME);
      
      foreach(array('#'.strtolower(BOTNAME), strtolower('#'.STREAMER)) as $channel) {
        foreach($this->storage as $database) {
          $this->db[$channel][$database] = $this->load($channel.'.'.$database.'.db');
        }
        if($channel == '#'.strtolower(BOTNAME)) {
          $this->say($channel, true, '/color '.COLOR);
        }
        $this->send('JOIN '.$channel);
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
          $this->username = explode('!', ltrim($this->data[0], ':'))[0];
          $this->mode = $this->data[1];
          $this->target = $this->data[2];
          switch($this->mode) {  
            case 'JOIN':
              if(ltrim($this->data[0], ':') == strtolower(BOTNAME.'!'.BOTNAME.'@'.BOTNAME.'.tmi.twitch.tv')) {
                array_push($this->db[$this->target]['mods'], OWNER);
                
                if($this->target == '#'.strtolower(BOTNAME)) {
                  $this->say($this->target, true, '/mod '.OWNER);
                } else {
                  $this->say(null, true, 'Joined '.$this->target. ' - v'.$this->version);
                }
              } else {                
                if(!isset($this->db[$this->target]['users'][$this->username])) {
                  $this->db[$this->target]['users'][$this->username] = $this->userentry;
                  $this->db[$this->target]['users']['add'] = time();
                  $this->db[$this->target]['users']['join'] = time();
                } else {
                  $this->db[$this->target]['users']['join'] = time();
                  $this->db[$this->target]['users']['count'] = $this->userstats[$this->target][$this->username]['count'] + 1;
                }
                $this->save();
                
                if($this->username == ltrim($this->target,'#')) {
                  $this->say($this->target, true, 'Welcome, '.$this->username);
                  $this->say(null, true, 'Possible live stream detected - '.$this->username.' joined '.$this->target);
                }
              }
            break;
            case 'PART':
              if(isset($this->db[$this->target]['users'][$this->username])) {
                $this->db[$this->target]['users']['part'] = time();
                $this->save();
              }
            break;
            case '353':
              $start = array_search($this->data[4], $this->data)+1;
              for($i=$start;$i<sizeof($this->data);$i++) {
                if(!isset($this->db[$this->data[4]]['users'][ltrim($this->data[$i], ':')])) {
                  $this->db[$this->data[4]]['users'][ltrim($this->data[$i], ':')] = $this->userentry;
                  $this->db[$this->data[4]]['users']['add'] = time();
                  $this->db[$this->data[4]]['users']['join'] = time();
                } else {
                  $this->db[$this->data[4]]['users'][ltrim($this->data[$i], ':')]['join'] = time();
                  $this->db[$this->data[4]]['users'][ltrim($this->data[$i], ':')]['count'] = $this->db[$this->data[4]]['users'][ltrim($this->data[$i], ':')]['count'] + 1;
                }
                $this->save();
              }
            break;
            case 'MODE':
              if($this->data[3] == '+o') {
                if(!in_array($this->data[4], $this->db[$this->target]['mods'])) {
                  array_push($this->db[$this->target]['mods'], $this->data[4]);
                }
              }
              if($this->data[3] == '-o') {
                unset($this->db[$this->target]['mods'][$this->data[4]]);
              }
            break;
            case 'PRIVMSG':
              if(isset($this->data[3])) {
                $command = ltrim($this->data[3], ':');
                if(substr($command, 0, 1) == '!') {
                  if(!isset($this->db[$this->target]['stats'][$command])) {
                    $this->db[$this->target]['stats'][$command] = 1;
                  } else {
                    $this->db[$this->target]['stats'][$command] = $this->db[$this->target]['stats'][$command] + 1;
                  }
                  $this->save();
                  if(file_exists('cmds/'.$command.'.php')) {
                    include('cmds/'.$command.'.php');
                  } else {
                    if(array_key_exists($command, $this->db[$this->target]['cmds'])) {
                      $this->say($this->target, false, ''.$this->db[$this->target]['cmds'][$command]);
                    }
                  }
                }
              }
            break;
            default:
              //$this->log('Invalid mode'.$this->mode);
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
      if($force || $this->isop($channel) || $this->ispermit($channel) || !isset($this->last[$channel]) || (time()-$this->last[$channel]) > $this->delay ) {
        $this->send('PRIVMSG '.$channel.' :'.$message);
        $this->last[$channel] = time();
      }
    }
        
    function load($database) {
      $output = array();      
      if(file_exists('db/'.$database)) {
        $output = json_decode(file_get_contents('db/'.$database), true);
      }
      return $output;
    }
    
    function save() {
      foreach($this->db as $target => $database) {
        foreach($database as $key => $value) {
          file_put_contents('db/'.$target.'.'.$key.'.db', json_encode($value, JSON_FORCE_OBJECT), LOCK_EX);
        }
      }
    }
    
    function ispermit() {
      return in_array($this->username, $this->db[$this->target]['users']);
    }

    function isop($channel) {
      return in_array($this->username, $this->db[$this->target]['mods']);
    }
    
    function isstreamer() {
      if(ltrim($this->target, '#') == STREAMER) {
        return true;
      } else {
        return false;
      }
    }
    
    function isadmin() {
      return in_array($this->username, $this->db['#'.strtolower(BOTNAME)]['mods']);
    }
    
    function isowner() {
      if(ltrim($this->target, '#') == OWNER) {
        return true;
      } else {
        return false;
      }
    }

    function send($cmd) {
      fputs($this->socket, $cmd."\r\n");
      $this->log($cmd);
      flush();
    }
       
    function log($msg) {
      $msg = str_replace(array(chr(10), chr(13)), '', $msg);
      if($_SERVER['DOCUMENT_ROOT'] != '') {
        echo nl2br(trim($msg)).'<br>';
      } else {
        file_put_contents(get_class($this).'-'.date('Ymd').'.log', $msg.PHP_EOL, FILE_APPEND | LOCK_EX);
      }
    }
}

?>