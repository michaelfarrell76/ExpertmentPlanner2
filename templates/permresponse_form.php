<font size='6'> Permutation Creator: Detail Analysis</font>
<table class="table table-striped">
<div id='stop'>
            <?php
            $vars =pow(2, $_GET["number"]);
            $samps =$vars * $_GET["nnum"];
           	
              printf("<br><thead >");
              printf("<th style=' text-align: right;'>");
              printf('<font size= 4>');
            	printf("Variables:");
            	  printf("</font>");
            	printf("</th>");
            for($i=1;$i<=$_GET["number"];$i++)
            {
            	printf("<th style=' text-align: center;'>");
            	printf('<font size= 4>');
            	printf($_GET[$i]);
            	 printf("</font>");
            	printf("</th>");
            }
            
			$total=1;
    if($_GET["wells"]>0)
	{
		
		//$numplates=intval(($samps+$numcontrols)/$_GET["wells"]);
		//if(($samps+$numcontrols)%$_GET["wells"]!=0)
		$numplates=intval(($samps)/$_GET["wells"]);
		if(($samps)%$_GET["wells"]!=0)
			$numplates++;
		
		if($_GET["wells"]==6)
		{
			$rows=2;
			$cols=3;
		}
		else if($_GET["wells"]==12)
		{
			$rows=3;
			$cols=4;
		}
		else if($_GET["wells"]==24)
		{
			$rows=4;
			$cols=6;
		}
		else if($_GET["wells"]==48)
		{
			$rows=6;
			$cols=8;
		}
		else if($_GET["wells"]==96)
			{
				$rows=8;
			$cols=12;
		}
		$total=$rows*$cols;
		
	}
          
            
            printf("</thead><tbody><tr><th><div style='text-align:center;text-decoration:underline;'>Wells</div></th></tr>");
            $samplearr=array('empty');
      
           $counter = 0;
        	$sample = 0;
        	$helpcols=0;
        	$helprows=64;
        	$totcount=0;
        	$tray=0;
            for($i =0; $i<$vars; $i++)
            {
            	$u=$i%2;
           		switch($u)
           		{
           			case 0:
           				$randomcolor='blue';
           				break;
           			default:
           				$randomcolor='orange';
           				break;
           		}		
           			printf("<div >");
           			for($l=0; $l<$_GET["nnum"];$l++)
            		{
            			if($totcount%$total==0)
            			{
            				$helprows=64;
            				$helpcols=0;
            				$tray++;	
            			}	
            			printf("<tr>");
            			$sample++;
           				printf("<td style=' text-align: center;border-left:1px solid black; ");
           				if($l+1==$_GET["nnum"])
           					printf("border-bottom: 1px solid black; ");
           				if($_GET["wells"]==0)
           				{	
           					printf("font-weight:bold; color:".$randomcolor."'>Sample ".$sample."</td>");
           				}else
           				{
           					if($helpcols%$cols==0)
            					$helprows++;
           					printf("font-weight:bold; color:".$randomcolor."'>Tray ".$tray." ".chr($helprows).(($helpcols++%$cols)+1)."</td>");
           				}
           				$totcount++;
           				array_push($samplearr, $counter);
           				$helper = $counter;
            		
            			for($j=0;$j<$_GET["number"];$j++)
            			{
            				printf("<td style=' ");
            				if($l+1==$_GET["nnum"])
            					printf("border-bottom: 1px solid black; ");
            				if($j+1==$_GET["number"])
            					printf("border-right:1px solid black;");
            				printf("text-align: center;'> <font size = 4 color=");
            			
        					if($helper%2==0)
        						printf("red>-");
        					else
        						printf("green>+");
        					$helper=$helper/2;	
        					printf("</td>");	
        				}
        			printf("</tr>");
        			}
            		printf("</div>");
            	
            	$counter++;
            	
            }
            $a=1;
            $numcontrols=0;
            while(!empty($_GET["cont".$a]))
            {
            	
            	for($l=0; $l<$_GET["nnum"];$l++)
            	{
            		if($totcount%$total==0)
            			{
            				$helprows=64;
            				$helpcols=0;
            				$tray++;	
            			}	
            		$numcontrols++;
            		printf("<tr>");
            		printf("<td style=' text-align: center;border-left:1px solid black; ");
            		if($_GET["wells"]==0)
           				{	
           				if($l+1==$_GET["nnum"])
            					printf("border-bottom: 1px solid black;");
           					printf("font-weight:bold; color:Magenta'>Cont" . $a.": "."Sample ".$sample++."</td>");
           				}else
           				{
           					if($helpcols%$cols==0)
            					$helprows++;
            				if($l+1==$_GET["nnum"])
            					printf("border-bottom: 1px solid black;");
           					printf("font-weight:bold; color:Magenta'>Tray ".$tray." ".chr($helprows).(($helpcols++%$cols)+1).": ".$_GET["cont".$a]."</font></td>");
           				}
            		
            		$totcount++;
            		for($j=0;$j<$_GET["number"];$j++)
            		{
            			printf("<td style=' text-align: center; ");
            			if($l+1==$_GET["nnum"])
            				printf("border-bottom: 1px solid black;");
            			if($j+1==$_GET["number"])
            				printf("border-right: 1px solid black;");
            			printf(" '><font size = 2 color=Magenta>");
        				printf($_GET["cont".$a]);
        				printf("</font></td>");	
        			}
        			
            		printf("</tr>");
            		
            	}
            	$a++;
            }
?>

    </tbody>
    

</div>
</table>

<?php
	if($_GET["wells"]>0)
	{
		
		$numplates=intval(($samps+$numcontrols)/$_GET["wells"]);
		if(($samps+$numcontrols)%$_GET["wells"]!=0)
			$numplates++;
		
		if($_GET["wells"]==6)
		{
			$rows=2;
			$cols=3;
		}
		else if($_GET["wells"]==12)
		{
			$rows=3;
			$cols=4;
		}
		else if($_GET["wells"]==24)
		{
			$rows=4;
			$cols=6;
		}
		else if($_GET["wells"]==48)
		{
			$rows=6;
			$cols=8;
		}
		else if($_GET["wells"]==96)
			{
				$rows=8;
			$cols=12;
		}
	
	
		$count=1;
		printf("<font size='6'> Visual Representation</font>");
		for($k =1; $k<=$numplates; $k++)
		{
			printf("<br><br><font size='4'>Plate #" . $k."</font>");
			printf('<table class="table table-striped">');
			printf('<thead><th></th>');
	
			for($i=1;$i<=$cols; $i++)
			{
				printf("<th style=' text-align: center;'>");
				printf($i);
				printf("</th>");
			}	
	
			printf('</thead><tbody>');

			for($i=65, $x=65+$rows;$i<$x; $i++)
			{
				printf("<tr>");
				printf("<td>".chr($i)."</td>");
				for($n=1; $n<=$cols; $n++)
				{
				
				
						printf("<td style=' text-align: center;'>");
						
						if($count<=$samps)
						{
						printf("<div class='circle_green'>");
							printf("Sample ".$count."<br>");
						
							$helper = $samplearr[$count];
							for($j=0;$j<$_GET["number"];$j++)
            				{
            					printf("<font size = 2 color=");
            			
        						if($helper%2==0)
        							printf("red>".$_GET[$j+1].": - </font><br>");
        						else
        							printf("green>".$_GET[$j+1].": + </font><br>");
        						$helper=$helper/2;	
        					
        					}
						
						
						}	
						else if($numcontrols>0)
						{
							printf("<div class='circle_green'>");
            					printf("<font size = 2 color= magenta>");
            					printf("C: ".$_GET["cont".intval(1+($numcontrols-1)/$_GET["nnum"])]."</font>");
        						
        						$helper=$helper/2;	
        					
        					
							
							$numcontrols--;
						}
						else
						
							printf("N/A");
							printf("<div>");	
						printf("</td>");
						$count++;
				
				}
				printf("</div>");
				printf("</tr>");
			}
		
			printf('</tbody><table>');
		}
	}
	?>
	
	
	
  


      
            