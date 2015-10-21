<?php
	$size = floor(100./($hostnum+1));
printf("<div id= 'data'>");
	for($i = 0; $i <= $hostnum; $i++){
		printf("<div class='element' style = 'width:calc(".$size."%% - 10px); width:-webkit-calc(".$size."%% - 10px);'>\n");
		printf("Antibody " . ($i + 1)."\n");
		printf("</div>\n");
	}
	for($i = 0; $i <= $hostnum ; $i++){
		printf("<div class='element' style = 'width:calc(".$size."%% - 10px); width:-webkit-calc(".$size."%% - 10px);'>\n");
		printf("<div id = '1sel".$i."'></div>");
		printf("</div>\n");
	}
	for($i = 0; $i <= $hostnum; $i++){
		printf("<div class='element' style = 'width:calc(".$size."%% - 10px); width:-webkit-calc(".$size."%% - 10px);'>\n");
		printf("<select id= '1Col".$i."'>\n");
		printf("<option value='' disabled selected>Select your option</option>");
		if($i == 0){
			printf("<option value='DAPI' selected='selected'>DAPI COLORS</option>\n");
		}
		printf("</select>\n");
		printf("</div>\n");
	}
	printf("<p></p>\n");
	printf("<div class='form-group'>\n");
	printf("<button type='button' id='1add' class='btn btn-default'  >Generate</button>\n");
	printf("</div>\n");
	printf("<div id='1dilution' style = 'position: absolute; left: 40%%;'></div>");
	printf("<div id='1tube'></div>");
	printf("</div>");
	printf("<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><div id = '1newform'></div>");
	
?>


<button type='button' id='print' class='btn btn-default'  >Create Printout</button><br><br>
<div id ='printout' > </div>

<script>
var chosen = <?php echo json_encode($chosen); ?>;
var results = <?php echo json_encode($results); ?>;
var secondary = <?php echo json_encode($secondary); ?>;
var colors = <?php echo json_encode($colors); ?>;
var hostnum = <?php echo json_encode($hostnum); ?>;
var replications = <?php echo json_encode($replications); ?>;
var wellsize = <?php echo json_encode($wellsize); ?>;
var volumewell = <?php echo json_encode($volumewell); ?>;



var antibodiesUsed = [];
var colorsUsed = [];
var hostsUsed = [];
var secondaryhosts = [];


var total = volumewell * replications; 
//	CHANGE 4 CONSTANT
var constant = 250/total;
var abitems = [];
var colitems = [];

//update to adjust
var numcols = hostnum + 1;
if (numcols >= 10){
	numcols = 9;
}
var len = chosen.length;
var dropdown = [];
var array = [];
$(document).ready(function () {
	createnew(1);
});
var currentInit = 1;

var used = [];

function createidstring(hash, identifier, text, num){
	var newstring = "";
	if(hash == "y")
		newstring += "#";
	newstring += identifier;
	newstring += text;
	newstring += num;
	return newstring;
}

function createnew(init) {
	secondaryhosts[init] = [];
	abitems[init] = [];
	dropdown[init] = [];
	for(var i = 0; i <numcols; i++){
		var str = "<select id = '"
		str += createidstring("n", init, "AB", i);
		str += "'>";
		
		dropdown[init][i] = $(str);
	}
	used[init] = [];
	array[init] = [];
	antibodiesUsed[init] = [];
	hostsUsed[init] = [];
	
	for(var i = 0; i < numcols; i++){
		array[init][i] = [];
		if(i == 0)
		{
			array[init][i].push("DAPI");
		}
		else{
			array[init][i].push("None");
		}
	
		for(var j = 0; j < len; j++){
			var find = 0;
			for(var k = 1; k < init; k++){
				//compare chosen[j]["Name"] to all currently chosen items
				for(l = 0; l < numcols; l++){
					var str = createidstring("y", k, "AB", l);
					if(chosen[j]["Name"] == $(str).val()){
						find ++;
					}
				}
				
			}
			if(find == 0)
				array[init][i].push(chosen[j]["Name"]);
			else{
				array[init][i].push(chosen[j]["Name"]);
				used[init].push(chosen[j]["Name"]);
			}
		}
		
	}

	for(var i = 0; i < numcols; i++) { 
		dropdown[init][i].options = function (data, currentVal) {
			var self = this;
			$.each(data, function (ix, val) {
				var option;
				inder = self.attr('id')[0];
				var user = 0;
				for(var l = 1; l <= inder; l++)
					if(used[l].indexOf(currentVal) != -1)
						user++;
				if(user > 0){
					if(currentVal == val){
						option = $('<option  selected = "selected">').text(val);
					 }
					 else{
						option = $('<option >').text(val);
					 }
				}
				else{
					if(currentVal == val){
						option = $('<option selected = "selected">').text(val);
					 }
					 else{
						option = $('<option>').text(val);
					 }
				 }
				data.push(option);
			});
			self.html(data)
		}
	}
	for(var i = 0; i < numcols; i++){
		var help = createidstring("y", init, "sel", i);
		$(help).append(dropdown[init][i]);

	}
	for(var i = 0; i < numcols; i++){
		var help = createidstring("y", init, "AB", i);
		var current = $(help).val();

		dropdown[init][i].options(array[init][i], current);

		abitems[init][i] = $(help).val();

		$(help).change(function() {
			
			var newstr = "#";
			newstr += this.id;
			changeLists(newstr[4], newstr, this.id[0]);
			updateColors(this.id[0]);
		});
	}

	
	antibodiesUsed[init].push(abitems[init][0]);
	
	colitems[init] = [];
	for(var i = 0; i < numcols; i++){
		colitems[init][i] = undefined;
	}
	colorsUsed[init] = [];
	for(var i = 0; i < numcols; i++){
		var str = createidstring("y", init, "Col", i);
		var el = $(str);
		el.change(function() {
			var newstr = "#";
			newstr += this.id;
			var ex = $(newstr);
			var num = newstr[5];
			var index = colorsUsed[this.id[0]].indexOf(colitems[this.id[0]][num]);
			if (index > -1) {
				colorsUsed[this.id[0]].splice(index, 1);
			}
			var n = ex.val().length;
			
			colorsUsed[this.id[0]].push(ex.val().substring(n-6,n));
			colitems[this.id[0]][num] = ex.val();
			updateColors(this.id[0]);			
		});
	}

	// make microliters
	$(function() {
		var str = createidstring("y", init, "add", "");
		
		$(str).click(function()
		{
			var image = " <img src='images/testtube.png' alt='testube' style='width:128px;height:250px; position: absolute; left:57.75%%;'>";
			var newstr = createidstring("y", this.id[0], "tube", "");
			var position = $(newstr).position();
			var height = 0;
			var top = position.top;
			var left = position.left;
			var selections = [];
			var printer = "<b>Solution " + init + ":</b><br><br><i>Primary Antibody Solution: </i><br>";
			var sum = 0;
			var backcolors = ["blue.png","purp.png", "red.jpg", "green.jpg"];
			var x = 0;
			for(var i = 0; i < numcols; i++){
				var edit = createidstring("y", this.id[0], "AB", i);
				var el = $(edit);
				var edit = createidstring("y", this.id[0], "Col", i);
				var colsel = $(edit);
				var selectedName = el.val();
				var selectedColor = colsel.val();
				var dilution = results[selectedName];
			
				if(undefined !== dilution){
					dilution = dilution["Dilution"];
					var toadd = total/parseInt(dilution);
					var amount = toadd.toFixed(6);
					if(amount > 1){
						printer += amount;
						printer += "ml of ";
					}
					else{
						var holder = amount*1000;
						printer += holder.toFixed(2);
						printer += "&mu;l of ";
					}
					
					sum += parseFloat(toadd.toFixed(5));
					printer += selectedName;
					printer += "<br>";
					height = toadd.toFixed(5)*constant;
					image += " <img src='images/";
					image += backcolors[x];
					x = x+1;
					image+= "' alt='testube' style='width:61px;height: ";
					image += height;
					image += "px; position: absolute; opacity: 0.4;left:60%%; top: ";
					image += top;
					image += "px;'>";
					top = top + height;
				}
			
			}
			var toadd = total/1000;
			var amount = toadd.toFixed(5);

			if(amount > 1){
				printer += amount;
				printer += "ml of DAPI<br>";
			}
			else{
				var holder = amount*1000;
				printer += holder.toFixed(2);
				printer += "&mu;l of DAPI<br>";
			}
			sum += parseFloat(amount);
			var amount = (total - sum).toFixed(3);
			if(amount > 1){
				printer += amount;
				printer += "ml of PBS<br>";
			}
			else{
				printer += amount*1000;
				printer += "&mu;l of PBS<br>";
			}
			printer += "<br><br><i>Secondary Antibody Solution:</i><br>";
			sum = 0;
			for(var i = 0; i < numcols; i++){
				var edit = createidstring("y", this.id[0], "AB", i);
				var el = $(edit);
				var edit = createidstring("y", this.id[0], "Col", i);
				var colsel = $(edit);
				//console.log("HERE");

				var selectedName = el.val();
				var selectedColor = colsel.val();
				var n = 0;
				if(selectedColor != null)
				{
					n = selectedColor.length;
					selectedColor = selectedColor.substring(n-6,n);
				}
				console.log("HERE"+selectedColor);
				var anti = results[selectedName];
			
				if(undefined !== anti){
					anti = anti["Host"];
					//alert(anti);
					//alert(selectedColor);
					for(var entry in secondary){
						var work = secondary[entry];
						if(anti in secondary[entry]){
							work = work[anti];
							if(selectedColor in work){
								var item = (entry + " &alpha; " + anti + "-" + selectedColor);
								var toadd = total/200;
								var amount = toadd.toFixed(6);
								if(amount > 1){
									printer += amount;
									printer += "ml of ";
								}
								else{
									var holder = amount*1000;
									printer += holder.toFixed(2);
									printer += "&mu;l of ";
								}
								sum += parseFloat(amount);
								printer += item + "<br>";
					
							}
						}
					}
				}
			
			}
			var amount = (total - sum).toFixed(3);
			if(amount > 1){
				printer += amount;
				printer += "ml of PBS<br>";
			}
			else{
				printer += amount*1000;
				printer += "&mu;l of PBS<br>";
			}
			
			var newstr = createidstring("y", this.id[0], "dilution", "");
			var working = $(newstr);
			working.empty();
			working.html(printer);
			var newstr = createidstring("y", this.id[0], "tube", "");
			var working = $(newstr);
		
		
			height = (((total - sum)*constant).toFixed(0));
			image += " <img src='images/black.jpg' alt='testube' style='width:61px;height: "
			image += height;
			image += "px; position: absolute; opacity: 0.4;left:60%%; top: ";
			image += top;
			image += "px;'>";
		
		
			working.empty();
			working.html(image);
			generatenextform(this.id[0]);
		});
	});

}

function ok(entry, init){
	for(var i = 0; i < numcols; i++){
		var str = createidstring("y", init, "AB", i);
		//name
		var currentVal = $(str).val();
		if(currentVal != "DAPI" && currentVal != "None"){
			var hoste = results[currentVal]["Host"];
			if(hoste == entry)
				return 1;
		}
	}
	return 0;
}
function getsecondaryhosts(init){
	secondaryhosts[init] = [];
	for(var i = 0; i < numcols; i++){
		var str = createidstring("y", init, "AB", i);
		var prim = $(str).val();
		var str = createidstring("y", init, "Col", i);
		var color = $(str).val();
		if(color != null && prim != "DAPI" && prim != "None"){
			var anti = results[prim]["Host"];
			for(var entry in secondary){
				for (var ent in secondary[entry]){
					if(ent == anti){
						secondaryhosts[init].push(entry);
					}
				}
			}			
		}
	}
	//alert(secondaryhosts[init]);
}

function updateColors(init) {
	getsecondaryhosts(init);
	for(var i = 0; i < numcols; i++){
		var str = createidstring("y", init, "AB", i);
		//name
		var currentVal = $(str).val();
		var cols = [];
		var secslist = [];
		if(currentVal != "DAPI" && currentVal != "None"){
			var currhost = results[currentVal]["Host"];
			console.log("currhost:"+currhost);
			for(var entry in secondary){
			console.log("entry:"+entry);
				if(ok(entry, init) == 0){
					var index = hostsUsed[init].indexOf(entry);
					if (index <= -1) {
						//CHECK FOR CROSS REACTIVITY AGAINST OTHER 2nd SECONDADYHOSTS[init][?] needs to be instatnatiated
						if(currhost in secondary[entry]){
							for(var ent in secondary[entry][currhost]){
								
								cols.push(entry + " anti "+ currhost + "-"+ent);
								
								secslist.push(entry)
							}
						
						}
					}
				}
			}
			
			
		}
		
		var str = createidstring("y", init, "Col", i);
		var el = $(str);
		var currentCol = el.val();
		el.empty();
		var list = " <option value='' disabled selected>Select your option</option>";
		if(cols.length >0){
			var newArr = {};
			cols.forEach(function(entry){
				newArr[entry] = colors[entry];
			});
			var count = 0;
			cols.forEach(function(entry){
			console.log(currentCol + " " + entry);
				if(currentCol != entry){
					var n = entry.length;
			
			
					var index = colorsUsed[init].indexOf(entry.substring(n-6,n));//MUST FIX SO NO TWO REDS
					if (index <= -1) {
						list += "<option value='" +entry+"'label = '" +entry+ "'></option>";
					}
				}
				else{
					list += "<option selected = 'selected' value='" +entry+"'label = '"+entry+ "'></option>";
				}
				count = count + 1;
			});
			el.html(list);
		}
		else if (currentVal == "DAPI"){
			list += "<option selected = 'selected' value='DAPI'> DAPI COLORS</option>";
			el.html(list);
		}
		else{
			el.html(list);
		}

	}
	//alert(colorsUsed[init]);
	updateArrays(init);

	for(var i = 0; i < numcols; i++){
		var str = createidstring("y", init, "AB", i);
		var hold = $(str).val();
		dropdown[init][i].options(array[init][i], hold);
	}
}

function changeLists(num, id, init){
	var index = hostsUsed[init].indexOf(abitems[init][num]);
	if (index > -1) {
		hostsUsed[init].splice(index, 1);
	}

	var hold = $(id).val();
	var ex = typeof results[hold] === "undefined";
	if(!ex){
		abitems[init][num] = results[hold]["Host"];
		hostsUsed[init].push(results[hold]["Host"]);
	}	
	else{
		abitems[init][num] = "";
	}
	updateArrays(init);

	for(var i = 0; i < numcols; i++){
		var str = createidstring("y", init, "AB", i);
		var hold = $(str).val();
		dropdown[init][i].options(array[init][i], hold);
	}
}

function getColors(obj){
	var name = obj["Host"];
	var colorstoret = [];
	//secondary.forEach(function(entry, index){
	for (var entry in secondary) {
	//console.log(secondary[entry]);
		if(name in secondary[entry]) {
			for(var ent in secondary[entry]){
				colorstoret.push(ent);
			};
			
		}
		
	};
	//console.log(obj);
	//console.log(colorstoret);
	return colorstoret;
}

function updateArrays(init) {	
	getsecondaryhosts(init);
	for(var i = 0; i < numcols; i++){
		var currHost = abitems[init][i];
		array[init][i] = [];
		if(i == 0)
		{
			array[init][i].push("DAPI");
		}
		else{
			array[init][i].push("None");
		}
		for(var j = 0; j < len; j++){
			if(chosen[j]["Host"] == currHost){
				var colors = getColors(chosen[j]);
				var free = 0;
				colors.forEach(function(item){
				var n = item.length;
					index = colorsUsed[init].indexOf(item);
					if(index <= -1){
						free = free + 1;
					}
				});
				var str = createidstring("y", init, "Col", i);
				var checkcol = $(str);
				var str = createidstring("y", init, "AB", i);
				var checkname = $(str);
				if(free > 0 || checkcol.val() != null ){
					var find = 0;
					for(var k = 1; k < init; k++){
						//compare chosen[j]["Name"] to all currently chosen items
						for(l = 0; l < numcols; l++){
							var str = createidstring("y", k, "AB", l);
							if(chosen[j]["Name"] == $(str).val()){
								find ++;
							}
						}
						
					}
					if(find == 0)
						array[init][i].push(chosen[j]["Name"]);
					else//change color
						array[init][i].push(chosen[j]["Name"]);
				}
			}
			else{
				var host = chosen[j]["Host"];
				var index = hostsUsed[init].indexOf(host);
				var ind2 = secondaryhosts[init].indexOf(host);
				if(index <= -1 && ind2 <= -1) {
					var colors = getColors(chosen[j]);
					var free = 0;
					colors.forEach(function(item){
					var n = item.length;
						index = colorsUsed[init].indexOf(item);
						if(index <= -1){
							free = free + 1;
						}
					});
					
					
				
					var str = createidstring("y", init, "Col", i);
					var checkcol = $(str);
					var str = createidstring("y", init, "AB", i);
					var checkname = $(str);
					if(free > 0 || checkcol.val() != null){
						var find = 0;
						for(var k = 1; k < init; k++){
							//compare chosen[j]["Name"] to all currently chosen items
							for(l = 0; l < numcols; l++){
								var str = createidstring("y", k, "AB", l);
								if(chosen[j]["Name"] == $(str).val()){
									find ++;
								}
							}
						
						}
						if(find == 0)
							array[init][i].push(chosen[j]["Name"]);
						else//change color
							array[init][i].push(chosen[j]["Name"]);
						
					}
				}
			}

		}	
	}
}



function generatenextform(init){
	var newinit = parseInt(init) + 1;
	var str = createidstring("y", init, "newform", "");
	var el = $(str);
	var newadd = "<div id= 'data2'>";
	var nums = hostnum + 1;
	var size = Math.floor(100/nums);
	for(var i = 0; i < nums; i++){
		newadd += "<div class='element' style = 'width:calc(";
		newadd += size;
		newadd += "%% - 10px); width:-webkit-calc(";
		newadd += size;
		newadd += "%% - 10px);'>\n";
		newadd += "Antibody ";
		newadd += (i + 1);
		newadd += "\n";
		newadd += "</div>\n";
	}
	newadd += "<br><br>";
	for(var i = 0; i < nums; i++){
		newadd += "<div class='element' style = 'width:calc(";
		newadd += size;
		newadd += "%% - 10px); width:-webkit-calc(";
		newadd += size;
		newadd += "%% - 10px);'>\n";
		newadd +="<div id = '";
		newadd += newinit;
		newadd += "sel";
		newadd += i;
		newadd += "'></div>";
		newadd += "</div>\n";
	}
	newadd += "<br><br>";
	for(var i = 0; i < nums; i++){
		newadd += "<div class='element' style = 'width:calc(";
		newadd += size;
		newadd += "%% - 10px); width:-webkit-calc(";
		newadd += size;
		newadd += "%% - 10px);'>\n";
		newadd += "<select id= '";
		newadd += newinit;
		newadd += "Col";
		newadd += i;
		newadd += "'>\n";
		newadd += "<option value='' disabled selected>Select your option</option>";
		if(i == 0){
			newadd+= "<option value='DAPI' selected='selected'>DAPI COLORS</option>\n";
		}
		newadd +="</select>\n";
		newadd +="</div>\n";
	}
	newadd += "<br><br>";
	newadd += "<p></p>\n";
	newadd += "<div class='form-group'>\n";
	newadd +="<button type='button' id='";
	newadd += newinit;
	newadd += "add' class='btn btn-default'  >Generate</button>\n";
	newadd += "</div>\n";
	newadd += "<div id='";
	newadd += newinit; 
	newadd += "dilution' style = 'position: absolute; left: 40%%;'></div>";
	newadd += "<div id='";
	newadd += newinit;
	newadd += "tube'></div>";
	newadd += "</div>";
	newadd += "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><div id ='";
	newadd += newinit;
	newadd += "newform'></div>";
	el.empty();
	el.html(newadd);
	createnew(newinit);
	currentInit = currentInit + 1;
}



$(function() {
		var str = createidstring("y", "", "print", "");
		$(str).click(function(){
			var initLim = currentInit - 1;
			var numberplates = Math.floor(initLim*replications/wellsize);
			if((initLim * replications) % wellsize !=0){
				numberplates = numberplates + 1;
			}
			var rows = 0;
			var cols = 0;
			switch (parseInt(wellsize)) {
				case 6:
					rows = 2;
					cols = 3;
					break;
				case 12:
					rows = 3;
					cols = 4;
					break;
				case 24:
					rows = 4;
					cols = 6;
					break;
				case 48:
					rows = 6;
					cols = 8;
					break;
				case 96:
					rows = 8;
					cols = 12;
					break;
			}
			
			var toprint = "";
			toprint += "<font size='6'> Visual Representation</font><br><br>";
			var prntInit = 1;
			var prntInitCnt = 0;
			var numsamps = 0;
			for(var i = 0; i < numberplates; i++)
			{
				toprint += "<font size='4'>Plate #";
				toprint += i + 1;
				toprint += "</font><table class='table table-striped'><thead><th></th>";
				for(var j = 0; j < cols; j++)
				{
					toprint += "<th style=' text-align: center;'>";
					toprint += j + 1;
					toprint += "</th>";
				}
				toprint += "</thead><tbody>";
				var x = 65 + rows;
				for(var j = 65; j < x; j++)
				{
					toprint += "<tr><td>";
					toprint += String.fromCharCode(j);
					toprint += "</td>";
					
					for(var k = 0; k < cols; k++)
					{
						toprint += "<td style=' text-align: center;'>";
						if(prntInit <= initLim){
							toprint += "Solution:" + prntInit;
							numsamps++;
						}
						toprint += "</td>";
						prntInitCnt = prntInitCnt + 1;
						if(prntInitCnt % parseInt(replications) == 0){
							prntInit = prntInit + 1;
						}
						
					}
					toprint += "</tr>";
				}
				toprint += "</tbody><table>";
			}
			toprint += "<button onclick='printOutput()' type='button'>Print</button><br><br>"
			toprint += "Number of samples: ";
			toprint += numsamps;
			$("#printout").empty();
			$("#printout").html(toprint);
		});
});

function printOutput(){
	var prtContent = document.getElementById("printout");
	var cssLinkTag = "<link href='/Planner/css/bootstrap.min.css' rel='stylesheet'/><link href='/Planner/css/bootstrap-theme.min.css' rel='stylesheet'/><link href='/Planner/css/styles.css' rel='stylesheet'/><link href='/Planner/css/jquery.sidr.dark.css' rel='stylesheet'/>";
	var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
	WinPrint.document.write(cssLinkTag);
	WinPrint.document.write(prtContent.innerHTML);
	var initLim = currentInit - 1;
	for(var i = 1; i <= initLim; i++){
		var str = i + "dilution";
		prtContent = document.getElementById(str);
		WinPrint.document.writeln(prtContent.innerHTML);
		WinPrint.document.writeln("<br><br>");
	}
	
	
	
	WinPrint.document.close();
	WinPrint.focus();
	
	WinPrint.print();
	//WinPrint.close();
}








</script>

