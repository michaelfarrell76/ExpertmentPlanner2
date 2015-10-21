<?php
//print_r($csv->data);
function prnt($mark, $x){
		printf("<input type=checkbox name='chex[]' value= '".$mark["Name"]."' onclick='resetSelectAll".$x."();' class ='selectedId".$x."'><a class='popped' data-tooltip='#".$mark["Name"]."'> ".$mark["Host"].":".$mark["Antigen"] ."</a><br>");
		printf("<div class= 'markers' id='".$mark["Name"]."'>");
		printf("<div>");
		printf("Antigen: ".$mark["Antigen"]);
		printf("</div>");
		printf("<div>");
		printf("Cell type/tissue: ".$mark["Cell type/tissue"]);
		printf("</div>");
		printf("<div>");
		printf("Host: ".$mark["Host"]);
		printf("</div>");
		printf("<div>");
		printf("Supplier: ".$mark["Supplier"]);
		printf("</div>");
		printf("<div>");
		printf("Cat. no.: ".$mark["Cat. no."]);
		printf("</div>");
		printf("</div>");
	}
?>
<form action="antiresponse.php" method="get">
   			<fieldset>
        		<form action="helper.php" method="get">
   			<fieldset>
   				



        		<div class="form-group">
        			<p>Enter the Number of <a id='Repnoting'>Replications</a></p>
            		<input class="form-control" id='reps' name="nnum" placeholder="Ex. 3" type="text"/>
       			</div>
       			<div class='note' id='repping'>
        		Replications: The number of trials you run for each permutation to catch for biological and technical variability.
        		This number should be at least 3 when possible.
        		</div>
       			
       			
       			<p>Format the Experiment with a Specific Sized Well-Plate:</p>
       		
       			<select name='wells' id='well'>
       				<option value="0">None</option>
  					<option value="6">6 Well</option>
  					<option  value="12">12 Well</option>
  					<option  value="24">24 Well</option>
  					<option  value="48">48 Well</option>
  					<option  value="96">96 Well</option>
  
				</select>
				<br><br>
				<div id = 'welldepth'> Volume per Well:
					<input type="text" name="vol" value='0'>
				</div>
				<br><br>
				<big>Primary Antobodies:</big><br><br>
				<table  border = "1" >
   				<thead>
				  <tr>
				  	<th width="150">Include</th>
				    <th width="150">Organization</th>
				    <th width="150">Cell type/tissue</th> 
				    <th width="150">Antigen</th>
				    <th width="150">Host</th>
				    <th width="150">Name</th>
				    <th width="150">Supplier</th>
				    <th width="150">Cat. no.</th>
				    <th width="150">Dilution</th> 
				  </tr>
				</thead>
				<tbody>
				<tr>
			    	<td colspan="9">
			      <div class="scrollit">
			        <table>  	
   				<?php
        			foreach($data as $row){
        				printf("<tr>");
        				printf("<td class = 'center' width='180'>");
        				printf('<div class="squaredOne"><input type="checkbox" value="'.$row[4].'" id="' . $row[4] .'" name="chex[]"" /><label for="'. $row[4] .'"></label></div>');
        				printf("</td>");
        				for($i = 0; $i < 8; $i++){

        					printf("<td width='150'>" . $row[$i] . "</td>");
        				}
        				printf("</tr>");
        			}
				
   				?>
   				</table>  	
   			</div>
   			</td>
   			</tr>
			    	
			      
			        
   				</tbody>
   				</table>

   				<br><br>
				<big>Secondary Antobodies:</big><br><br>
				<table  border = "1" >
   				<thead>
				  <tr>
				  	<th width="150">Include</th>
				  	<th width="150">Code</th>
				    <th width="150">Host</th>
				    <th width="150">Antibody Agent</th> 
				    <th width="150">Exitation</th>
				    <th width="150">Dilution</th>
				    <th width="150">Cat#</th>
					<th width="150">Name</th>
				  </tr>
				</thead>
				<tbody>
				<tr>
			    	<td colspan="9">
			      <div class="scrollit">
			        <table>  	
   				<?php
        			foreach($data2 as $row){
        				printf("<tr>");
        				printf("<td class = 'center' width='190'>");
        				printf('<div class="squaredOne"><input type="checkbox" value="_SECOND_'.$row[6].'" id="' . $row[6] .'" name="chex[]"" /><label for="'. $row[6] .'"></label></div>');
        				printf("</td>");
        				for($i = 0; $i < 7; $i++){

        					printf("<td width='150'>" . $row[$i] . "</td>");
        				}
        				printf("</tr>");
        			}
				
   				?>
   				</table>  	
   			</div>
   			</td>
   			</tr>
			    	
			      
			        
   				</tbody>
   				</table>
				

<br>
<!-- <iframe style='width: 825px; height: 400px' src="https://docs.google.com/spreadsheets/d/1hRSs1FOcUqOsDVK0r6vESSkVmDuapWq8ULgMv_F9EVs/pubhtml?widget=true&amp;headers=false"></iframe>
				<br><a href='https://docs.google.com/spreadsheets/d/1hRSs1FOcUqOsDVK0r6vESSkVmDuapWq8ULgMv_F9EVs/edit?usp=sharing'> Edit the Spreadsheet </a>
       			<div id='controls'></div> -->
        
<div class="form-group">
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            <big><button type="submit" class="button big-btn" style="position:relative; top: -280px;">Next -></button></big>
            
        </div>
    </fieldset>
</form>


<script>

var volumes = <?php echo json_encode($volumes); ?>;
$("#well").change(function() {

	var el = $("#welldepth");
	el.empty();
	var wellsize = $("#well").val();
	var str = "Volume per Well:<input type='text' name='vol' value='";
	str += volumes[wellsize];
	str +="'>";
	el.html(str);
});



$(document).ready(function () {
    $('#selectallnsc').click(function () {
        $('.selectedIdnsc').prop('checked', isChecked('selectallnsc'));
    });
});
$(document).ready(function () {
    $('#selectallpluri').click(function () {
        $('.selectedIdpluri').prop('checked', isChecked('selectallpluri'));
    });
});

$(document).ready(function () {
    $('#selectallbregion').click(function () {
        $('.selectedIdbregion').prop('checked', isChecked('selectallbregion'));
    });
});
$(document).ready(function () {
    $('#selectallother').click(function () {
        $('.selectedIdother').prop('checked', isChecked('selectallother'));
    });
});

function isChecked(checkboxId) {
    var id = '#' + checkboxId;
    return $(id).is(":checked");
}
function resetSelectAllPluri() {
    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    if ($(".selectedIdpluri").length == $(".selectedIdpluri:checked").length) {
        $("#selectallpluri").attr("checked", "checked");
    } else {
        $("#selectallpluri").removeAttr("checked");
    }

    if ($(".selectedIdpluri:checked").length > 0) {
        $('#edit').attr("disabled", false);
    } else {
        $('#edit').attr("disabled", true);
    }
}

function resetSelectAllNsc() {
    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    if ($(".selectedIdnsc").length == $(".selectedIdnsc:checked").length) {
        $("#selectallnsc").attr("checked", "checked");
    } else {
        $("#selectallnsc").removeAttr("checked");
    }

    if ($(".selectedIdnsc:checked").length > 0) {
        $('#edit').attr("disabled", false);
    } else {
        $('#edit').attr("disabled", true);
    }
}

function resetSelectAllBregion() {
    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    if ($(".selectedIdbregion").length == $(".selectedIdbregion:checked").length) {
        $("#selectallbregion").attr("checked", "checked");
    } else {
        $("#selectallbregion").removeAttr("checked");
    }

    if ($(".selectedIdbregion:checked").length > 0) {
        $('#edit').attr("disabled", false);
    } else {
        $('#edit').attr("disabled", true);
    }
}

function resetSelectAllOther() {
    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    if ($(".selectedIdother").length == $(".selectedIdother:checked").length) {
        $("#selectallother").attr("checked", "checked");
    } else {
        $("#selectallother").removeAttr("checked");
    }

    if ($(".selectedIdother:checked").length > 0) {
        $('#edit').attr("disabled", false);
    } else {
        $('#edit').attr("disabled", true);
    }
}


</script>


<script>

$("a").hover(function(e) {
    $($(this).data("tooltip")).css({
        left: e.pageX + 40,
        top: e.pageY + 1
    }).stop().show(100);
}, function() {
    $($(this).data("tooltip")).hide();
});




var varhidden=true;
var namehidden=true;
var rephidden=true;
var addhidden=true;

var plurihidden=true;
var nschidden=true;
var bregionhidden=true;
var otherhidden=true;

$("#pluri").on('click',function(){
	$("#lists").css("display", "block" );
	if(plurihidden)
	{
		$("#pluriL").css("visibility", "visible" );
		plurihidden = false;
	}	
	else	
	{
		$("#pluriL").css("visibility", "hidden" );
		plurihidden=true;
	}
});
$("#nsc").on('click',function(){
$("#lists").css("display", "block" );
	if(nschidden)
	{
		$("#nscL").css("visibility", "visible" );
		nschidden = false;
	}	
	else	
	{
		$("#nscL").css("visibility", "hidden" );
		nschidden=true;
	}
});
$("#bregion").on('click',function(){
$("#lists").css("display", "block" );
	if(bregionhidden)
	{
		$("#bregionL").css("visibility", "visible" );
		bregionhidden = false;
	}	
	else	
	{
		$("#bregionL").css("visibility", "hidden" );
		bregionhidden=true;
	}
});
$("#other").on('click',function(){
$("#lists").css("display", "block" );
	if(otherhidden)
	{
		$("#otherL").css("visibility", "visible" );
		otherhidden = false;
	}	
	else	
	{
		$("#otherL").css("visibility", "hidden" );
		otherhidden=true;
	}
});


$("#varnoting").on('click',function(){
	if(varhidden)
	{
		$("#varnote").css( "display", "block" );
		varhidden=false;
		$("#repping").css( "display", "none" );
		rephidden=true;
		$("#naming").css( "display", "none" );
		namehidden=true;
		$("#addc").css( "display", "none" );
		addhidden=true;
	}	
	else	
	{
		$("#varnote").css( "display", "none" );
		varhidden=true;
	}	
});

$("#Repnoting").on('click',function(){
	if(rephidden)
	{
		$("#repping").css( "display", "block" );
		rephidden=false;
		$("#varnote").css( "display", "none" );
		varhidden=true;
		$("#naming").css( "display", "none" );
		namehidden=true;
		$("#addc").css( "display", "none" );
		addhidden=true;
	}	
	else	
	{
		$("#repping").css( "display", "none" );
		rephidden=true;
	}	
});
$("#namez").on('click',function(){
	if(namehidden)
	{
		$("#naming").css( "display", "block" );
		namehidden=false;
		$("#varnote").css( "display", "none" );
		varhidden=true;
		$("#repping").css( "display", "none" );
		rephidden=true;
		$("#addc").css( "display", "none" );
		addhidden=true;
	}	
	else	
	{
		$("#naming").css( "display", "none" );
		namehidden=true;
	}	
});

$("#add").on('click',function(){
	if(addhidden)
	{
		$("#addc").css( "display", "block" );
		addhidden=false;
		$("#varnote").css( "display", "none" );
		varhidden=true;
		$("#repping").css( "display", "none" );
		rephidden=true;
		$("#naming").css( "display", "none" );
		namehidden=true;
	}	
	else	
	{
		$("#addc").css( "display", "none" );
		addhidden=true;
	}	
});

var control=0;
var vari=0;
var replics=0;
var size=0;
var plates=0;
var samps=0;
var totsamp=0;
var contsamps=0;
$("#pos").on('click',function(){
	control++;
	$("#controls").append( "<div class='form-group'><input autofocus class='form-control' id ='cont"+control+"' name='cont"+control+"' value='Control " + control +"' placeholder='Ex. Water' type='text'/></div>" );
	$("#numcont").empty().append(control);
	contsamps=control*replics;
	$("#contsamp").empty().append(contsamps);
	totsamp=contsamps+samps;
	$("#totsamples").empty().append(totsamp);
	plates=	parseInt(totsamp/size);
    if(totsamp%size!=0)
    {
        plates++;
    }
    if(size==0)
    	plates=0;
    $("#wellnum").empty().append(plates);
	

});
$("#neg").on('click',function(){
	if(control>0)
	{
		var str ='#cont' + control--;
	
		$(str).remove();
	
		$("#numcont").empty().append(control);
		contsamps=control*replics;
		$("#contsamp").empty().append(contsamps);
		totsamp=contsamps+samps;
		$("#totsamples").empty().append(totsamp);
	}	
	plates=	parseInt(totsamp/size);
    if(totsamp%size!=0)
    {
        plates++;
    }
    if(size==0)
    	plates=0;
    $("#wellnum").empty().append(plates);
});

(function($){
    $.fn.extend({
        donetyping: function(callback,timeout){
            timeout = timeout || 1e3; // 1 second default timeout
            var timeoutReference,
                doneTyping = function(el){
                    if (!timeoutReference) return;
                    timeoutReference = null;
                    callback.call(el);
                };
            return this.each(function(i,el){
                var $el = $(el);
                $el.is(':input') && $el.keypress(function(){
                    if (timeoutReference) clearTimeout(timeoutReference);
                    timeoutReference = setTimeout(function(){
                        doneTyping(el);
                    }, timeout);
                }).blur(function(){
                    doneTyping(el);
                });
            });
        }
    });
})(jQuery);
var oldvari=0;
var first=true;
$('#vars').donetyping(function(){
	if(first)
	{
		first=false;
		$("#names").empty();
	}	
	else
	{
		oldvari=vari;
	}
	vari= $("#vars").val()	
    
    $("#variables").empty().append(vari);
    var diff=oldvari-vari;
    if(vari>0&&replics>0)
    {
    	samps=replics* Math.pow(2,vari);
    	$("#samples").empty().append(samps);
    	totsamp=contsamps+samps;
		$("#totsamples").empty().append(totsamp);
    }
    else
    {
    	$("#samples").empty().append(0);
    	totsamp=contsamps+samps;
		$("#totsamples").empty().append(totsamp);
    }
    if(diff<0)
    {
    	x=oldvari;
    	x++;
		while(x<=vari)
		{
			$("#names").append( "<div class='form-group'><input autofocus class='form-control' id='dog"+x+"' name='"+x+"' placeholder='Ex. Drug "+x+"' type='text'/></div>" );
			x++;
		}
	}	
	else if(diff>0)
	{
		var x=vari;
		x++;
		while(x<=oldvari)
		{
			var string= '#dog' + x;
			$(string).remove();
			x++;
		}
	}
	plates=0;
	if(size>0&&replics>0&&vari>0)
    {
    	plates=	parseInt(totsamp/size);
    	if(totsamp%size!=0)
        {
        	plates++;
    	}
	}
    $("#wellnum").empty().append(plates);
}, 1000);
$('#reps').donetyping(function(){
    replics= $("input#reps").val()
    contsamps=control*replics;
    $("#contsamp").empty().append(contsamps);
    totsamp=contsamps+samps;
	$("#totsamples").empty().append(totsamp);
    $("#replications").empty().append(replics);
    if(vari>0&&replics>0)
    {
    	samps=replics* Math.pow(2,vari);
    	$("#samples").empty().append(samps);
    	totsamp=contsamps+samps;
		$("#totsamples").empty().append(totsamp);
    }
    else
    {
    	$("#samples").empty().append(0);
    	totsamp=contsamps+samps;
		$("#totsamples").empty().append(totsamp);
    }
    plates=0;
	if(size>0&&replics>0&&vari>0)
    {
    	plates=	parseInt(totsamp/size);
    	if(totsamp%size!=0)
        {
        	plates++;
    	}
	}
    $("#wellnum").empty().append(plates);
	
}, 1000);

    $(function () {
        $("#well").change(function () {
        	size=$("#well").val();
            $("#wellsize").empty().append(size);
            plates=0;
            if(size>0&&replics>0&&vari>0)
            {
            	plates=	parseInt(totsamp/size);
            	if(totsamp%size!=0)
            	{
            		plates++;
            	}
            	
            }
             $("#wellnum").empty().append(plates);
        });

   
    
}, 1000);
	
</script>
            
 