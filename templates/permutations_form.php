<form action="permresponse.php" method="get">
   			<fieldset>
        		<form action="helper.php" method="get">
   			<fieldset>
        		<div class="form-group" >
        			<p>Enter the Number of <a id='varnoting'>Variables</a> You Will Study in the Experiment</p>
            		<input autofocus class="form-control" id="vars" name="number" placeholder="Ex. 2" type="text"/>
        		</div>
        		
        		<div class='note' id='varnote'>
        		Variable: an element, feature, or factor that is liable to vary or change.
        		This may be the addition of a drug, exposure to an element and so on...
        		</div>
        		
        		<p> Enter the <a id='namez'>Name(s)</a> of the Variables you Wish to Study</p>
        		<div class='note' id='naming'>
        		These names may be anything, however they should allow you to easily identify which variables are being manipulated.
        		</div>
        		<div id='names'>...<br><br></div>
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

<br>
				<div class="form-group">
        			<p>Would You Like to Add Any <a id='add'>Additional Controls</a><p>
        			<div class='note' id='addc'>
        		One set of positive and negative controls are already contained. Add additional if you require more, but name them well.
        		</div>
        			<button type='button' id='pos' class="btn btn-default"  >+</button>
        			
        			<button type='button' id ='neg'  class="btn btn-default" >-</button>
        			
            		
       			</div>
       			<div id='controls'></div>
        

<div id='submitbox' style="text-align: center; position:relative; top: -100px;">
<div style=' font-size:25px; ' >Overview</div><br>
# of Variables: <span class='subitem' id='variables'>0</span><br>
# of Replications: <span class='subitem' id='replications'>0</span><br>
# of Additional Controls: <span class='subitem' id='numcont'>0</span><br><br>
<strong>Samples</strong><br>
# of Samples Needed: <span class='subitem' id='samples'>0</span><br>
+ <br>
# of Additional Control Samples Needed: <span class='subitem' id='contsamp'>0</span><br>
= <br>
# of Total Samples <span class='subitem' id='totsamples'>0</span><br><br>
Size of Well-Plate: <span class='subitem' id='wellsize'>0</span><br>
# of Well-Plates Needed: <span class='subitem' id='wellnum'>0</span><br>

</div>
<div class="form-group">
            
            <button type="submit" class="btn btn-default" style="position:relative; top: -280px;">Calculate</button>
            
        </div>
    </fieldset>
</form>


<script>
var varhidden=true;
var namehidden=true;
var rephidden=true;
var addhidden=true;
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
</script>


<script>
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
            
   