<?php



function getDaysFromMonth($year, $month, $preferredDay){
	
	 $mondays = array(); 
    # First weekday in specified month: 1 = monday, 7 = sunday 
    $firstDay = date('N', mktime(0, 0, 0, $month, 1, $year)); 
     
    /* Add 0 days if monday ... 6 days if tuesday, 1 day if sunday 
        to get the first monday in month */ 
    
   /*  echo date('l', mktime(0, 0, 0, $month, 1, $year));
    $addDays = (8 - $firstDay) % 7;
    echo $addDays; */
    
    
    
    if(date('l', mktime(0, 0, 0, $month, 1, $year)) == 'Monday'){
    	$addDays = 0;
    }elseif(date('l', mktime(0, 0, 0, $month, 1, $year)) == 'Tuesday'){
    	$addDays = 6;
    	
    }elseif(date('l', mktime(0, 0, 0, $month, 1, $year)) == 'Wednesday'){
    	$addDays = 5;
    	
    }elseif(date('l', mktime(0, 0, 0, $month, 1, $year)) == 'Thursday'){
    	$addDays = 4;
    	
    }elseif(date('l', mktime(0, 0, 0, $month, 1, $year)) == 'Friday'){
    	$addDays = 3;
    	
    }elseif(date('l', mktime(0, 0, 0, $month, 1, $year)) == 'Saturday'){
    	$addDays = 2;
    	
    }elseif(date('l', mktime(0, 0, 0, $month, 1, $year)) == 'Sunday'){
    	$addDays = 1;
    	
    } 
     
   /*  $countInc = (sizeof($mondays)+1);
    if($preferredDay == date('N', mktime(0, 0, 0, $month, 1, $year))){
    
    	$mondays[$countInc] = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
    } */
    
    
    $mondays[] = date('Y-m-d', mktime(0, 0, 0, $month, $preferredDay + $addDays, $year));
    
     
    $nextMonth = mktime(0, 0, 0, $month + 1, 1, $year); 
    

    # Just add 7 days per iteration to get the date of the subsequent week 
    for ($week = 1, $time = mktime(0, 0, 0, $month, $preferredDay + $addDays + $week * 7, $year); 
        $time < $nextMonth; 
        ++$week, $time = mktime(0, 0, 0, $month, $preferredDay + $addDays + $week * 7, $year)) 
    { 
    	
    	
        $mondays[] = date('Y-m-d', $time);
    } 
    
    
    
    
  /*   $numberOfDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);    
    
    if($preferredDay == date('N', mktime(0, 0, 0, $month, $numberOfDaysInMonth, $year))){
    	 
    	$mondays[$countInc] = date('Y-m-d', mktime(0, 0, 0, $month, $numberOfDaysInMonth, $year));
    } */
    
    $countInc = (sizeof($mondays)+1);
    for($i = 1; $i <=7; $i++){
    	 
    	if(date('N', mktime(0, 0, 0, $month, $i, $year)) == $preferredDay){
    		if(!in_array(date('Y-m-d', mktime(0, 0, 0, $month, $i, $year)), $mondays)){
    			 
    			$mondays[$countInc] = date('Y-m-d', mktime(0, 0, 0, $month, $i, $year));
    		}
    	}
    	 
    }
    
    
   
     
    return $mondays; 
}




function getMonthsBetweenDates($startDate, $endDate){
	
	$date1  = $startDate;
	$date2  = $endDate;
	$output = [];
	$time   = strtotime($date1);
	$last   = date('m-Y', strtotime($date2));
	
	do {
		$month = date('m-Y', $time);
		$total = date('t', $time);
	
		$output[] = [
				'month' => $month,
				'total' => $total,
		];
	
		$time = strtotime('+1 month', $time);
	} while ($month != $last);
	
	return $output;
	
	
}



function getWeekends($Startdate, $endDate, $weekendDay){
	
	$begin  = new DateTime($Startdate);
	$end    = new DateTime($endDate);
	
	$weekendDaysArray = array();
	
	$i = 0;
	while ($begin <= $end) // Loop will work begin to the end date
	{
		if($begin->format("D") == $weekendDay) //Check that the day is Sunday here
		{
			//echo $begin->format("Y-m-d") . "<br>";
			$weekendDaysArray[$i] = $begin->format("d M Y");
			
			$i++;
		}
	
		$begin->modify('+1 day');
	}
	
	return $weekendDaysArray;
}



