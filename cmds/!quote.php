<?php

  $cmd = array('level' => 'none',
               'count' => true,
               'help' => 'Displays random or specific quote');
  
  if($execute) {
    if(sizeof($this->db[$this->target]['data']['quotes']) > 0) {
      if(isset($this->data[4]) && is_numeric(ltrim($this->data[4], '#')) && ltrim($this->data[4], '#') > 0) {
        if(array_key_exists((ltrim($this->data[4], '#')-1), $this->db[$this->target]['data']['quotes'])) {
          $quote = $this->db[$this->target]['data']['quotes'][(ltrim($this->data[4], '#')-1)];
          $this->say($this->target, false, '#'.ltrim($this->data[4], '#').' - "'.$quote['msg'].'" - ('.date('d.m.Y H:i:s', $quote['time']).')');
        }
      } else {
        $quoteid = rand(0, (sizeof($this->db[$this->target]['data']['quotes'])-1));
        $quote = $this->db[$this->target]['data']['quotes'][$quoteid];
        $this->say($this->target, false, '#'.($quoteid+1).' - "'.$quote['msg'].'" - ('.date('d.m.Y H:i:s', $quote['time']).')');
      }
    }
  }
  
?>