<?php

  $cmd = array('level' => 'none permit op admin owner',
               'count' => true,
               'help' => 'Displays random or specific quote');
  
  if($execute) {
    if(sizeof($this->db[$this->target]['data']['quotes']) > 0) {
      if(isset($this->data[4]) && is_numeric(ltrim($this->data[4], '#'))) {
        if(array_key_exists((ltrim($this->data[4], '#')), $this->db[$this->target]['data']['quotes'])) {
          if(isset($this->db[$this->target]['data']['quotes'][(ltrim($this->data[4], '#'))])) {
            $quote = $this->db[$this->target]['data']['quotes'][(ltrim($this->data[4], '#'))];
            $this->say($this->target, false, '#'.ltrim($this->data[4], '#').' - "'.$quote['msg'].'" - ('.date('d.m.Y H:i:s', $quote['time']).')');
          }
        }
      } else {
        $quoteid = array_rand($this->db[$this->target]['data']['quotes']);
        $quote = $this->db[$this->target]['data']['quotes'][$quoteid];
        $this->say($this->target, false, '#'.$quoteid.' - "'.$quote['msg'].'" - ('.date('d.m.Y H:i', $quote['time']).')');
      }
    }
  }
  
?>