<?php
      function validateDate($date)
      {
        //YYYY-MM-DD
        $year  = substr($date,0,4);
        $month = substr($date,5,2);
        $day   = substr($date,8,2);
        if(checkdate ( $month, $day, $year ))
        {
        	return true;
        }
        else 
        {
          echo ("Invalid date, please enter date in YYYY-MM-DD format </br>");
          return false;
        }  
      }
      function validateTime($time)
      {
        //HHhMM
        $hours = substr($time,0,2);
        $minutes = substr($time,3,2);
        if(($hours > -1 && $hours < 24) && ($minutes > -1 && $minutes < 60))
        {
          return true;
        }
        else 
        {
          echo ("Invalid time, please enter time in HHhMM 24 hour format </br>");
          return false;        
        }
      }
?>