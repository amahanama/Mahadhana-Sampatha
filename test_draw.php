<?php
  $page_title = 'Mahadhana Sampatha Draw';
	require_once('includes/load.php');
	// Checkin What level user has permission to view this page
	page_require_level(2);
	$groups = find_all('user_groups');
	$all_categories = find_all('lotteries');
	$draw_inputs=null;
	$panel = find_by_id('panel_member',(int)$_GET['id']);  
	$test_draw_inputs=find_panel_test_inputs();
	$live_draw_inputs=find_panel_live_inputs();  
	
	$test_draw_no=find_by_mode('TEST');
	$live_draw_no=find_by_mode('LIVE');
	$all_draw_users = find_draw_user();	//draw user list	
?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script type="text/javascript" src="libs/js/moment.min.js"></script>
<script type="text/javascript">
	function cancelData(){
		  location.reload();
	}


	function update(str){ 	   
		if (str == "TEST") {						
				<?php 
					$tp1=find_by_id('panel_member',$test_draw_inputs[0]['panel_id']);
					$tp2=find_by_id('panel_member',$test_draw_inputs[1]['panel_id']);
					$tp3=find_by_id('panel_member',$test_draw_inputs[2]['panel_id']);
					$tp4=find_by_id('panel_member',$test_draw_inputs[3]['panel_id']);
					$tp5=find_by_id('panel_member',$test_draw_inputs[4]['panel_id']);
				  
					$pin1= $test_draw_inputs[0]['pin_no'];
					$pin2= $test_draw_inputs[1]['pin_no'];
					$pin3= $test_draw_inputs[2]['pin_no'];
					$pin4= $test_draw_inputs[3]['pin_no'];
					$pin5= $test_draw_inputs[4]['pin_no'];  
				?>
				
				$(".test_draw_data").show();
				$(".live_draw_data").hide();
				document.body.style.backgroundColor = "blue";
				document.getElementById("draw_no").value = <?php echo $test_draw_inputs[0]['draw_no'];?> ;
				
				
			} else if(str == "LIVE") {	
				<?php 				  
				  $lp1=find_by_id('panel_member',$live_draw_inputs[0]['panel_id']);
				  $lp2=find_by_id('panel_member',$live_draw_inputs[1]['panel_id']);
				  $lp3=find_by_id('panel_member',$live_draw_inputs[2]['panel_id']);
				  $lp4=find_by_id('panel_member',$live_draw_inputs[3]['panel_id']);
				  $lp5=find_by_id('panel_member',$live_draw_inputs[4]['panel_id']);
				  
				  $lpin1= $live_draw_inputs[0]['pin_no'];
				  $lpin2= $live_draw_inputs[1]['pin_no'];
				  $lpin3= $live_draw_inputs[2]['pin_no'];
				  $lpin4= $live_draw_inputs[3]['pin_no'];
				  $lpin5= $live_draw_inputs[4]['pin_no'];
				?>
				$(".test_draw_data").hide();
				$(".live_draw_data").show();
				document.body.style.backgroundColor = "green";
				var d_no = <?PHP echo (!empty($live_draw_inputs[0]['draw_no']) ? json_encode($live_draw_inputs[0]['draw_no']) : '""'); ?>;
				if(d_no > 0){
					document.getElementById("draw_no").value = d_no;
				}
				//document.getElementById("draw_no").value = <?php echo $live_draw_inputs[0]['draw_no'];?> ;
			}	   
   }
	
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
	
	//Delete Table Row
	function deleteRow(btn) {
	  var row = btn.parentNode.parentNode;
	  row.parentNode.removeChild(row);
	}
	
	//Insert Table row and data
	function insertTableData(count,name,nic,designation,panel_id) {		
		  var table = document.getElementById("data_list");
		  var row = table.insertRow(count);
		  var cell0 = row.insertCell(0);
		  var cellCount =row.insertCell(1);
		  var cell1 = row.insertCell(2);
		  var cell2 = row.insertCell(3);
		  var cell3 = row.insertCell(4);
		  var cell4 = row.insertCell(5);	
		  var cell5 = row.insertCell(6);			  
		  
		  cell0.innerHTML = "<input type='text' id='panel_"+count+"' name='panel_"+count+"' value='"+panel_id+"'  readonly style='width:50%'> ";
		  cellCount.innerHTML = count;
		  cell1.innerHTML = name;
		  cell2.innerHTML = nic;		  
		  cell3.innerHTML = designation;
		  cell4.innerHTML = "<input type='text' id='pin_"+count+"' name='pin_"+count+"' maxlength='20' onkeypress='if ( isNaN( String.fromCharCode(event.keyCode) )) return false;'>";
		  cell5.innerHTML = "<button onclick='deleteRow(this);'>Remove</button>";
    }
	
	//Validate already existing records/panel member
	function containsObject(obj, list) {
		var i;
		for (i = 0; i < list.length; i++) {
			if (list[i] === obj) {
				return true;
			}
		}
		return false;
	}
	
	//Calculate Computer Generated Random Number with 20 Digits
	function calcRandomNo(){
		var min = 1000000000;
		var max = 9999999999;
		//var num = (Math.floor(Math.random() * min)) + max;
		//document.getElementByName('auto_generated_pin').value=num;	
		var down = document.getElementById('auto_generated_pin');
		var out1 = Math.floor(Math
            .random() * (max - min + 1)) + min;		
		var out2 = Math.floor(Math
            .random() * (max - min + 1)) + min;	
		//Append 2 variables with 10 digits	
		let out = (out1.toString()).concat(out2.toString());		
		document.getElementById('auto_generated_pin').value=out;
		document.getElementsByName('auto_generated_pin').value=out;
	}
	
	//function to calculate median value
	function median(values){
		  if(values.length ===0) throw new Error("No inputs");

		  values.sort(function(a,b){
			return a-b;
		  });

		  var half = Math.floor(values.length / 2);
		  
		  if (values.length % 2)
			return values[half];
		  
		  return (values[half - 1] + values[half]) / 2.0;
	}
	
	function validateInputs(){
		var test_draw_no=<?php echo implode($test_draw_no);?>;	
		var live_draw_no=<?php echo implode($live_draw_no);?>;
		
				
		if(document.getElementById('mode').value.length == 0){
			alert("Please select the mode to proceed.");	
			document.getElementById("mode").focus();
			return false;
		}else if(document.getElementById('mode').value == "TEST" && document.getElementById('draw_no').value == (test_draw_no-1) ){
			alert("Draw Number already exist for Test Mode.");	//&& document.getElementById('draw_no').value == test_draw_no		
			return false;
		}else if(document.getElementById('mode').value == "LIVE" && document.getElementById('draw_no').value == (live_draw_no-1)){
			alert("Draw Number already exist for Live Mode.");			
			return false;		
		}else if(document.getElementById('auto_generated_pin').value.length == 0){
			alert("Please generate the Computer Generated Random Number");	
			document.getElementById('auto_generated_pin').focus();
			return false;
		}else if(document.getElementById('operator').value.length == 0){
			alert("Please enter the Draw Application Operater Name.");	
			document.getElementById('operator').focus();
			return false;		
		}else{
			return true;
		}
		
	}
	
	
	//Convert Hexa Decimal to Decimal
	function h2d(s) {

		function add(x, y) {
			var c = 0, r = [];
			var x = x.split('').map(Number);
			var y = y.split('').map(Number);
			while(x.length || y.length) {
				var s = (x.pop() || 0) + (y.pop() || 0) + c;
				r.unshift(s < 10 ? s : s - 10); 
				c = s < 10 ? 0 : 1;
			}
			if(c) r.unshift(c);
			return r.join('');
		}

		var dec = '0';
		s.split('').forEach(function(chr) {
			var n = parseInt(chr, 16);
			for(var t = 8; t; t >>= 1) {
				dec = add(dec, dec);
				if(n & t) dec = add(dec, '1');
			}
		});
		return dec;
	}
	
	
	//function to generate winning numbers
	function initiateDraw(){
		if(validateInputs()){		
		//document.getElementById("save_draw").submit();
		var auto_generated_pin=document.getElementById('auto_generated_pin').value;
		var mode = document.getElementById("mode").value;
		var panel_1=panel_2=panel_3=panel_4=panel_5=null;
		var pin_1=pin_2=pin_3=pin_4=pin_5=null;
		var operator=document.getElementById('operator').value;
		
		if(mode == "TEST"){	
			panel_1=document.getElementById('panel_1').value;
			panel_2=document.getElementById('panel_2').value;
			panel_3=document.getElementById('panel_3').value;
			panel_4=document.getElementById('panel_4').value;
			panel_5=document.getElementById('panel_5').value;
			
			
			pin_1=document.getElementById('data_list').rows[1].cells[6].innerHTML;
			pin_2=document.getElementById('data_list').rows[2].cells[6].innerHTML;
			pin_3=document.getElementById('data_list').rows[3].cells[6].innerHTML;
			pin_4=document.getElementById('data_list').rows[4].cells[6].innerHTML;
			pin_5=document.getElementById('data_list').rows[5].cells[6].innerHTML;			
						
		}else if(mode == "LIVE"){
			panel_1=document.getElementById('lpanel_1').value;
			panel_2=document.getElementById('lpanel_2').value;
			panel_3=document.getElementById('lpanel_3').value;
			panel_4=document.getElementById('lpanel_4').value;
			panel_5=document.getElementById('lpanel_5').value;
			
			pin_1=document.getElementById('data_list').rows[6].cells[6].innerHTML;
			pin_2=document.getElementById('data_list').rows[7].cells[6].innerHTML;
			pin_3=document.getElementById('data_list').rows[8].cells[6].innerHTML;
			pin_4=document.getElementById('data_list').rows[9].cells[6].innerHTML;
			pin_5=document.getElementById('data_list').rows[10].cells[6].innerHTML;
			
		}
		
		var avg_pin_1=avg_pin_2=avg_pin_3=avg_pin_4=avg_pin_5=avg_auto_generated_pin=null;
		
		if(pin_1.length>15){
			avg_pin_1=Math.round(pin_1/100000);
		}
		if(pin_2.length>15){
			avg_pin_2=Math.round(pin_2/100000);
		}
		if(pin_3.length>15){
			avg_pin_3=Math.round(pin_3/100000);
		}
		if(pin_4.length>15){
			avg_pin_4=Math.round(pin_4/100000);
		}
		if(pin_5.length>15){
			avg_pin_5=Math.round(pin_5/100000);	
		}
		avg_auto_generated_pin=Math.round(document.getElementById('auto_generated_pin').value/100000);
				
		const nums=[];
		//nums.push(auto_generated_pin);
		/*
		nums.push(pin_1);
		nums.push(pin_2);
		nums.push(pin_3);
		nums.push(pin_4);
		nums.push(pin_5);*/
		
		//let median_val=median(nums);
		
		const avg_arr=[];
		//avg_arr.push(median_val);
		avg_arr.push(avg_pin_1);
		avg_arr.push(avg_pin_2);
		avg_arr.push(avg_pin_3);
		avg_arr.push(avg_pin_4);
		avg_arr.push(avg_pin_5);
		avg_arr.push(avg_auto_generated_pin);
		
		
		let avg=computeAverageOfNumbers(avg_arr);
		
		//let avg=computeAverageOfNumbers(nums);
		
		//var hash_val=SHA256(avg);
		var hash_val=SHA512(avg);
		
		
		/*
		let result1 = hash_val.substring(0,20);
		let result2 = hash_val.substring(20,40);
		let result3 = hash_val.substring(40,60);
		let result4 = hash_val.substring(60,80);
		let result5 = hash_val.substring(80,100);
		let result6 = hash_val.substring(100,120);
		let eng     = hash_val.substring(120,128);*/
		
		let result1 = hash_val.substring(0,18);
		let result2 = hash_val.substring(18,36);
		let result3 = hash_val.substring(36,54);
		let result4 = hash_val.substring(54,72);
		let result5 = hash_val.substring(72,90);
		let result6 = hash_val.substring(90,108);
		let eng     = hash_val.substring(108,128);
		let eng_dec= h2d(eng);
		
		var tot1=calculateSum(result1);
		var tot2=calculateSum(result2);
		var tot3=calculateSum(result3);
		var tot4=calculateSum(result4);
		var tot5=calculateSum(result5);
		var tot6=calculateSum(result6);
		var tot7=getRandomEngLetter(eng_dec);
		
		
		var winnings=	tot1 + ","+tot2+","+tot3+","+tot4+","+tot5+ ","+tot6;
		var eng_letter=tot7;
		//var skillsSelect = document.getElementById("lottery-name");
		//var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
		
				
		var draw_no=document.getElementById("draw_no").value;
		//alert("draw_no "+draw_no);
		var draw_date=document.getElementById("draw_date").value;
		//alert("draw_date "+draw_date);
		var pan1=document.getElementById("data_list").rows[0].cells[1].innerHTML;
		//alert("pan1 "+pan1);
		var rand_no=document.getElementById("auto_generated_pin").value;
		
		var result = "'"+tot1 + " ,"+tot2+" ,"+tot3+" ,"+tot4+" ,"+tot5+ ", "+tot6+ " ,"+tot7+"'" ;
		event.preventDefault();		
		
		
			
		
		
		
		
		  
			  var tbl='<table class="table table-bordered" style="padding-top:10px;" id="data_list" name="data_list">';
				tbl+=			'<colgroup>';									
				tbl+=				'<col span="1" style="visibility: collapse">';
				tbl+=				'<col span="5">';
				tbl+=			 '</colgroup>';
				tbl+=			'<thead>	';	
				tbl+=				'<th style="width=5%;">#</th>';					
				tbl+=				'<th style="text-align:center;width=15%">Nameeee</th>';					
				tbl+=				'<th style="text-align:center;width=15%">NIC</th>';					
				tbl+=				'<th style="text-align:center;width=10%">Designation</th>';
				tbl+=				'<th style="text-align:center;width=15%">PIN NO</th>';					
				tbl+=			'</thead>';				
				tbl+=			'<tr class="test_draw_data">';
				tbl+=				'<td style="width=5%;">1</td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $tp1['member_name']?></td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $tp1['nic']?></td>';					
				tbl+=				'<td style="text-align:left;width=10%"><?php echo $tp1['designation']?></td>';
				tbl+=				'<td style="text-align:left;width=15%" id="pin_1" name="pin_1"><?php echo $pin1?></td>';					
				tbl+=			'</tr>';
				tbl+=			'<tr class="test_draw_data">';					
				tbl+=				'<td style="width=5%;">2</td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $tp2['member_name']?></td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $tp2['nic']?></td>';					
				tbl+=				'<td style="text-align:left;width=10%"><?php echo $tp2['designation']?></td>';
				tbl+=				'<td style="text-align:left;width=15%" id="pin_2" name="pin_2"><?php echo $pin2?></td>';					
				tbl+=			'</tr>';
				tbl+=			'<tr class="test_draw_data">';	
				tbl+=				'<td style="width=5%;">3</td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $tp3['member_name']?></td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $tp3['nic']?></td>';					
				tbl+=				'<td style="text-align:left;width=10%"><?php echo $tp3['designation']?></td>';
				tbl+=				'<td style="text-align:left;width=15%" id="pin_3" name="pin_3"><?php echo $pin3?></td>';					
				tbl+=			'</tr>';
				tbl+=			'<tr class="test_draw_data">';	
				tbl+=				'<td style="width=5%;">4</td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $tp4['member_name']?></td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $tp4['nic']?></td>';					
				tbl+=				'<td style="text-align:left;width=10%"><?php echo $tp4['designation']?></td>';
				tbl+=				'<td style="text-align:left;width=15%" id="pin_4" name="pin_4"><?php echo $pin4?></td>';				
				tbl+=			'</tr >';
				tbl+=			'<tr class="test_draw_data">';					
				tbl+=				'<td style="width=5%;"><input type="text" id="panel_5" name="panel_5" value="<?php echo $tp5['id']?>"  readonly></td>';
				tbl+=				'<td style="width=5%;">5</td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $tp5['member_name']?></td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $tp5['nic']?></td>';					
				tbl+=				'<td style="text-align:left;width=10%"><?php echo $tp5['designation']?></td>';
				tbl+=				'<td style="text-align:left;width=15%" id="pin_5" name="pin_5"><?php echo $pin5?></td>';						
				tbl+=			'</tr>';				
				tbl+=			'<tr class="live_draw_data">';					
				tbl+=				'<td style="width=5%;"><input type="text" id="lpanel_1" name="lpanel_1" value="<?php echo $lp1['id']?>"  readonly></td>';
				tbl+=				'<td style="width=5%;">1</td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp1['member_name']?></td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp1['nic']?></td>';					
				tbl+=				'<td style="text-align:left;width=10%"><?php echo $lp1['designation']?></td>';
				tbl+=				'<td style="text-align:left;width=15%" id="lpin_1" name="lpin_1"><?php echo $lpin1?></td>';					
				tbl+=			'</tr>';
				tbl+=			'<tr class="live_draw_data">';					
				tbl+=				'<td style="width=5%;"><input type="text" id="lpanel_2" name="lpanel_2" value="<?php echo $lp2['id']?>"  readonly></td>';
				tbl+=				'<td style="width=5%;">2</td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp2['member_name']?></td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp2['nic']?></td>';					
				tbl+=				'<td style="text-align:left;width=10%"><?php echo $lp2['designation']?></td>';
				tbl+=				'<td style="text-align:left;width=15%" id="lpin_2" name="lpin_2"><?php echo $lpin2?></td>';					
				tbl+=			'</tr>';
				tbl+=			'<tr class="live_draw_data">';					
				tbl+=				'<td style="width=5%;"><input type="text" id="lpanel_3" name="lpanel_3" value="<?php echo $lp3['id']?>"  readonly></td>';
				tbl+=				'<td style="width=5%;">3</td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp3['member_name']?></td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp3['nic']?></td>';					
				tbl+=				'<td style="text-align:left;width=10%"><?php echo $lp3['designation']?></td>';
				tbl+=				'<td style="text-align:left;width=15%" id="lpin_3" name="lpin_3"><?php echo $lpin3?></td>';					
				tbl+=			'</tr>';
				tbl+=			'<tr class="live_draw_data">';					
				tbl+=				'<td style="width=5%;"><input type="text" id="lpanel_4" name="lpanel_4" value="<?php echo $lp4['id']?>"  readonly></td>';
				tbl+=				'<td style="width=5%;">4</td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp4['member_name']?></td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp4['nic']?></td>';					
				tbl+=				'<td style="text-align:left;width=10%"><?php echo $lp4['designation']?></td>';
				tbl+=				'<td style="text-align:left;width=15%" id="lpin_4" name="lpin_4"><?php echo $lpin4?></td>';				
				tbl+=			'</tr>';
				tbl+=			'<tr class="live_draw_data">';					
				tbl+=				'<td style="width=5%;"><input type="text" id="lpanel_5" name="lpanel_5" value="<?php echo $lp5['id']?>"  readonly></td>';
				tbl+=				'<td style="width=5%;">5</td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp5['member_name']?></td>';					
				tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp5['nic']?></td>';					
				tbl+=				'<td style="text-align:left;width=10%"><?php echo $lp5['designation']?></td>';
				tbl+=				'<td style="text-align:left;width=15%" id="lpin_5" name="lpin_5"><?php echo $lpin5?></td>';						
				tbl+=			'</tr>';				
				tbl+='</table>';
  
    	
	
				var getFullContent = document.body.innerHTML;
                var printsection = document.getElementById('winner_report').innerHTML;
								
				document.body.innerHTML ="Development Lotteries Board <br>";
				document.body.innerHTML +="<div style='align:center'><p style='font-size: 12px;padding-left: 290px;font-family:Lucida Sans;'>"+mode+" Draw Report - "+draw_no+" </p></div>";
				document.body.innerHTML += "<hr>";
				document.body.innerHTML +="<div style='align:center'><p style='font-size: 20px;padding-left: 290px;font-family:Lucida Sans;'><b>Mahadhana Sampatha</b></p></div>";
				document.body.innerHTML +="<div style='align:center'><p style='font-size: 14px;padding-left: 290px;font-family:Lucida Sans;'>Draw NO & Date "+draw_no+" "+moment(new Date()).format("YYYY-MM-DD HH:mm:ss A")+"</p></div><br/>";
				document.body.innerHTML +="<div style='align:center'><p style='font-size: 20px;padding-left: 290px;font-family:Lucida Sans;'><b>Winning Numbers</b></p></div><br/>";
				document.body.innerHTML +="<p style='font-size: 20px;padding-left: 190px;font-family:Lucida Sans;'><span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot1 + "</span> &emsp; <span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot2+"</span> &emsp; <span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot3+"</span> &emsp; <span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot4+"</span>&emsp; <span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot5+ "</span> &emsp; <span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot6+ "</span> </p><br/>";
                
				document.body.innerHTML +='<div style="align:center"><p style="font-size: 20px;padding-left: 290px;font-family:Lucida Sans;"><b>English Letter</b></p></div><br/>';
				document.body.innerHTML +='<div style="align:center"><p style="font-size: 20px;padding-left: 350px;font-family:Lucida Sans;"><span style="border-width:1px; border-style:solid; border-color:#000000;padding: 0.5em;">'+tot7+ '</span></p><br/></div>';
				
				//Draw Seed
				document.body.innerHTML +='<div style="align:center"><p style="font-size: 16px;padding-left: 330px;font-family:Lucida Sans;"><b>Draw Seed</b></p></div>';
				document.body.innerHTML +='<div><p style="font-size: 12px;font-family:Lucida Sans;">'+hash_val+'</p></div>';
				
				//document.body.innerHTML +='<div>'+tbl+'</div>';				
				document.body.innerHTML += printsection;
				document.body.innerHTML += '<dv>Computer Generated Random Number &emsp;&emsp; '+rand_no+' &emsp;&emsp;&emsp;&emsp;........................................................</div>';
				document.body.innerHTML += '<div><p style="font-size: 12px;padding-left: 530px;">AGM(IT)/System Analyst/IT Assistant</p></div>';
                window.print();
                document.body.innerHTML = getFullContent;
				
				var draw_time=moment(new Date()).format("YYYY-MM-DD HH:mm:ss A");
				 				
				var dataString = 'draw_no='+ draw_no + '&draw_date=' + draw_time + '&rand_no=' + rand_no +'&mode='+mode+'&operator='+operator;
				    dataString += '&panel_1='+ panel_1 + '&panel_2=' + panel_2 + '&panel_3=' + panel_3 +'&panel_4='+panel_4+'&panel_5='+panel_5;
					dataString += '&pin_1='+ pin_1 + '&pin_2=' + pin_2 + '&pin_3=' + pin_3 +'&pin_4='+pin_4+'&pin_5='+pin_5;
					dataString += '&winnings='+ winnings + '&eng_letter=' + eng_letter + '&hash_val=' + hash_val;		
				
				var postURL = "/Digital_Draw/join.php";  
				var i=1;  	
			   $.ajax({    
					url:postURL,    
					method:"POST",    
					data:dataString,  
					type:'json',  
					success:function(data)    
					{  
						console.log(data);
						//i=1;  
						//$('.dynamic-added').remove();  
						//$('#add_name')[0].reset();  
						alert('Record Inserted Successfully.');  
					}    
			   });    
					
				 location.href='test_draw.php';
				 
			}	
		}
		
				
		
		
		function setOperator(val){
			document.getElementById('operator').value=val;			
		}
	
	function createPDF(){
		//alert("CREATE PDF");
		    var divContents = $("#dvContainer").html();
            var printWindow = window.open('', '', 'height=400,width=800');
            printWindow.document.write('<html><head><title>DIV Contents</title>');
            printWindow.document.write('</head><body >');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');           
            printWindow.open();       
			printWindow.document.close();			
	}
	
	function calculateSum(a){		
		var sum=[...a].filter( e => isFinite(e)).reduce((a, b)=>parseInt(a)+parseInt(b));
		//console.log([...a].filter( e => isFinite(e)).reduce((a, b)=>parseInt(a)+parseInt(b)));
		var mod= sum % 10;
		console.log("MOD "+mod);		
		return mod;		
	}
	
		
	function sumDigits(n){
		let numArr = n.toString().split("");

		let sum = numArr.reduce(function(a, b){
			return parseInt(a) + parseInt(b);
		}, 0);
		
		return sum;
	}
	
	
	function getRandomEngLetter(a){
		var sum=[...a].filter( e => isFinite(e)).reduce((a, b)=>parseInt(a)+parseInt(b));
		
		var eng_count=0;
		if(sum > 26){
			//while(eng_count < 27){
				//eng_count=sumDigits(sum);
				eng_count=sum % 26;
			//}
			//eng_count=sum - 26;
		}else{
			eng_count=sum;
		}
		
		let letter=generateRandomLetter(eng_count);
		return letter;
	}
	
	function computeAverageOfNumbers(arr) {	
		var str = arr.join(',');		
		var arr = str.split(',');

		var sum = arr.reduce(function(a, b) {
		  return +a + +b
		});
		var avg = sum / arr.length;
		console.log("AVERAGE >>> "+toPlainString(avg));
		return toPlainString(Math.round(avg));
		
	}
	
	 function toPlainString(num) {
		 console.log("NUMBER :"+num);
		return (''+ +num).replace(/(-?)(\d*)\.?(\d*)e([+-]\d+)/,
		function(a,b,c,d,e) {
		  return e < 0
			? b + '0.' + Array(1-e-c.length).join(0) + c + d
			: b + c + d + Array(e-d.length+1).join(0);
		});
	}
	//Generate Random English Letter
	function generateRandomLetter(i) {
		//alert('i : '+i);
		const alphabet = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
		return alphabet[i];
	}
	
	
	//GENERATE pdf_add_annotation
	function pdfCreate() {
            var divContents = $("#dvContainer").html();
            var printWindow = window.open('', '', 'height=400,width=800');
            printWindow.document.write('<html><head><title>DIV Contents</title>');
            printWindow.document.write('</head><body >');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
		
		 function printSection(el){
                var getFullContent = document.body.innerHTML;
                var printsection = document.getElementById(el).innerHTML;
                document.body.innerHTML = printsection;
                window.print();
                document.body.innerHTML = getFullContent;
            }
		
	/**
	* Secure Hash Algorithm (SHA256)
	**/

	function SHA256(s){
		 var chrsz = 8;
		 var hexcase = 0;

		 function safe_add (x, y) {
		 var lsw = (x & 0xFFFF) + (y & 0xFFFF);
		 var msw = (x >> 16) + (y >> 16) + (lsw >> 16);
		 return (msw << 16) | (lsw & 0xFFFF);
		 }

		 function S (X, n) { return ( X >>> n ) | (X << (32 - n)); }
		 function R (X, n) { return ( X >>> n ); }
		 function Ch(x, y, z) { return ((x & y) ^ ((~x) & z)); }
		 function Maj(x, y, z) { return ((x & y) ^ (x & z) ^ (y & z)); }
		 function Sigma0256(x) { return (S(x, 2) ^ S(x, 13) ^ S(x, 22)); }
		 function Sigma1256(x) { return (S(x, 6) ^ S(x, 11) ^ S(x, 25)); }
		 function Gamma0256(x) { return (S(x, 7) ^ S(x, 18) ^ R(x, 3)); }
		 function Gamma1256(x) { return (S(x, 17) ^ S(x, 19) ^ R(x, 10)); }

		 function core_sha256 (m, l) {
		 var K = new Array(0x428A2F98, 0x71374491, 0xB5C0FBCF, 0xE9B5DBA5, 0x3956C25B, 0x59F111F1, 0x923F82A4, 0xAB1C5ED5, 0xD807AA98, 0x12835B01, 0x243185BE, 0x550C7DC3, 0x72BE5D74, 0x80DEB1FE, 0x9BDC06A7, 0xC19BF174, 0xE49B69C1, 0xEFBE4786, 0xFC19DC6, 0x240CA1CC, 0x2DE92C6F, 0x4A7484AA, 0x5CB0A9DC, 0x76F988DA, 0x983E5152, 0xA831C66D, 0xB00327C8, 0xBF597FC7, 0xC6E00BF3, 0xD5A79147, 0x6CA6351, 0x14292967, 0x27B70A85, 0x2E1B2138, 0x4D2C6DFC, 0x53380D13, 0x650A7354, 0x766A0ABB, 0x81C2C92E, 0x92722C85, 0xA2BFE8A1, 0xA81A664B, 0xC24B8B70, 0xC76C51A3, 0xD192E819, 0xD6990624, 0xF40E3585, 0x106AA070, 0x19A4C116, 0x1E376C08, 0x2748774C, 0x34B0BCB5, 0x391C0CB3, 0x4ED8AA4A, 0x5B9CCA4F, 0x682E6FF3, 0x748F82EE, 0x78A5636F, 0x84C87814, 0x8CC70208, 0x90BEFFFA, 0xA4506CEB, 0xBEF9A3F7, 0xC67178F2);
		 var HASH = new Array(0x6A09E667, 0xBB67AE85, 0x3C6EF372, 0xA54FF53A, 0x510E527F, 0x9B05688C, 0x1F83D9AB, 0x5BE0CD19);
		 var W = new Array(64);
		 var a, b, c, d, e, f, g, h, i, j;
		 var T1, T2;

		 m[l >> 5] |= 0x80 << (24 - l % 32);
		 m[((l + 64 >> 9) << 4) + 15] = l;

		 for ( var i = 0; i<m.length; i+=16 ) {
		 a = HASH[0];
		 b = HASH[1];
		 c = HASH[2];
		 d = HASH[3];
		 e = HASH[4];
		 f = HASH[5];
		 g = HASH[6];
		 h = HASH[7];

		 for ( var j = 0; j<64; j++) {
		 if (j < 16) W[j] = m[j + i];
		 else W[j] = safe_add(safe_add(safe_add(Gamma1256(W[j - 2]), W[j - 7]), Gamma0256(W[j - 15])), W[j - 16]);

		 T1 = safe_add(safe_add(safe_add(safe_add(h, Sigma1256(e)), Ch(e, f, g)), K[j]), W[j]);
		 T2 = safe_add(Sigma0256(a), Maj(a, b, c));

		 h = g;
		 g = f;
		 f = e;
		 e = safe_add(d, T1);
		 d = c;
		 c = b;
		 b = a;
		 a = safe_add(T1, T2);
		 }

		 HASH[0] = safe_add(a, HASH[0]);
		 HASH[1] = safe_add(b, HASH[1]);
		 HASH[2] = safe_add(c, HASH[2]);
		 HASH[3] = safe_add(d, HASH[3]);
		 HASH[4] = safe_add(e, HASH[4]);
		 HASH[5] = safe_add(f, HASH[5]);
		 HASH[6] = safe_add(g, HASH[6]);
		 HASH[7] = safe_add(h, HASH[7]);
		 }
		 return HASH;
		 }

		 function str2binb (str) {
		 var bin = Array();
		 var mask = (1 << chrsz) - 1;
		 for(var i = 0; i < str.length * chrsz; i += chrsz) {
		 bin[i>>5] |= (str.charCodeAt(i / chrsz) & mask) << (24 - i % 32);
		 }
		 return bin;
		 }

		 function Utf8Encode(string) {
		 string = string.replace(/\r\n/g,'\n');
		 var utftext = '';

		 for (var n = 0; n < string.length; n++) {

		 var c = string.charCodeAt(n);

		 if (c < 128) {
		 utftext += String.fromCharCode(c);
		 }
		 else if((c > 127) && (c < 2048)) {
		 utftext += String.fromCharCode((c >> 6) | 192);
		 utftext += String.fromCharCode((c & 63) | 128);
		 }
		 else {
		 utftext += String.fromCharCode((c >> 12) | 224);
		 utftext += String.fromCharCode(((c >> 6) & 63) | 128);
		 utftext += String.fromCharCode((c & 63) | 128);
		 }

		 }

		 return utftext;
		 }

		 function binb2hex (binarray) {
		 var hex_tab = hexcase ? '0123456789ABCDEF' : '0123456789abcdef';
		 var str = '';
		 for(var i = 0; i < binarray.length * 4; i++) {
		 str += hex_tab.charAt((binarray[i>>2] >> ((3 - i % 4)*8+4)) & 0xF) +
		 hex_tab.charAt((binarray[i>>2] >> ((3 - i % 4)*8 )) & 0xF);
		 }
		 return str;
		 }

		 s = Utf8Encode(s);
		 return binb2hex(core_sha256(str2binb(s), s.length * chrsz));
	}
	
	
	/****************SHA 512 ALGORITHM*********************/
	/*
* Secure Hash Algorithm (SHA512)
* http://www.happycode.info/
*/

		function SHA512(str) {
			 function int64(msint_32, lsint_32) {
			 this.highOrder = msint_32;
			 this.lowOrder = lsint_32;
			 }

			 var H = [new int64(0x6a09e667, 0xf3bcc908), new int64(0xbb67ae85, 0x84caa73b),
			 new int64(0x3c6ef372, 0xfe94f82b), new int64(0xa54ff53a, 0x5f1d36f1),
			 new int64(0x510e527f, 0xade682d1), new int64(0x9b05688c, 0x2b3e6c1f),
			 new int64(0x1f83d9ab, 0xfb41bd6b), new int64(0x5be0cd19, 0x137e2179)];

			 var K = [new int64(0x428a2f98, 0xd728ae22), new int64(0x71374491, 0x23ef65cd),
			 new int64(0xb5c0fbcf, 0xec4d3b2f), new int64(0xe9b5dba5, 0x8189dbbc),
			 new int64(0x3956c25b, 0xf348b538), new int64(0x59f111f1, 0xb605d019),
			 new int64(0x923f82a4, 0xaf194f9b), new int64(0xab1c5ed5, 0xda6d8118),
			 new int64(0xd807aa98, 0xa3030242), new int64(0x12835b01, 0x45706fbe),
			 new int64(0x243185be, 0x4ee4b28c), new int64(0x550c7dc3, 0xd5ffb4e2),
			 new int64(0x72be5d74, 0xf27b896f), new int64(0x80deb1fe, 0x3b1696b1),
			 new int64(0x9bdc06a7, 0x25c71235), new int64(0xc19bf174, 0xcf692694),
			 new int64(0xe49b69c1, 0x9ef14ad2), new int64(0xefbe4786, 0x384f25e3),
			 new int64(0x0fc19dc6, 0x8b8cd5b5), new int64(0x240ca1cc, 0x77ac9c65),
			 new int64(0x2de92c6f, 0x592b0275), new int64(0x4a7484aa, 0x6ea6e483),
			 new int64(0x5cb0a9dc, 0xbd41fbd4), new int64(0x76f988da, 0x831153b5),
			 new int64(0x983e5152, 0xee66dfab), new int64(0xa831c66d, 0x2db43210),
			 new int64(0xb00327c8, 0x98fb213f), new int64(0xbf597fc7, 0xbeef0ee4),
			 new int64(0xc6e00bf3, 0x3da88fc2), new int64(0xd5a79147, 0x930aa725),
			 new int64(0x06ca6351, 0xe003826f), new int64(0x14292967, 0x0a0e6e70),
			 new int64(0x27b70a85, 0x46d22ffc), new int64(0x2e1b2138, 0x5c26c926),
			 new int64(0x4d2c6dfc, 0x5ac42aed), new int64(0x53380d13, 0x9d95b3df),
			 new int64(0x650a7354, 0x8baf63de), new int64(0x766a0abb, 0x3c77b2a8),
			 new int64(0x81c2c92e, 0x47edaee6), new int64(0x92722c85, 0x1482353b),
			 new int64(0xa2bfe8a1, 0x4cf10364), new int64(0xa81a664b, 0xbc423001),
			 new int64(0xc24b8b70, 0xd0f89791), new int64(0xc76c51a3, 0x0654be30),
			 new int64(0xd192e819, 0xd6ef5218), new int64(0xd6990624, 0x5565a910),
			 new int64(0xf40e3585, 0x5771202a), new int64(0x106aa070, 0x32bbd1b8),
			 new int64(0x19a4c116, 0xb8d2d0c8), new int64(0x1e376c08, 0x5141ab53),
			 new int64(0x2748774c, 0xdf8eeb99), new int64(0x34b0bcb5, 0xe19b48a8),
			 new int64(0x391c0cb3, 0xc5c95a63), new int64(0x4ed8aa4a, 0xe3418acb),
			 new int64(0x5b9cca4f, 0x7763e373), new int64(0x682e6ff3, 0xd6b2b8a3),
			 new int64(0x748f82ee, 0x5defb2fc), new int64(0x78a5636f, 0x43172f60),
			 new int64(0x84c87814, 0xa1f0ab72), new int64(0x8cc70208, 0x1a6439ec),
			 new int64(0x90befffa, 0x23631e28), new int64(0xa4506ceb, 0xde82bde9),
			 new int64(0xbef9a3f7, 0xb2c67915), new int64(0xc67178f2, 0xe372532b),
			 new int64(0xca273ece, 0xea26619c), new int64(0xd186b8c7, 0x21c0c207),
			 new int64(0xeada7dd6, 0xcde0eb1e), new int64(0xf57d4f7f, 0xee6ed178),
			 new int64(0x06f067aa, 0x72176fba), new int64(0x0a637dc5, 0xa2c898a6),
			 new int64(0x113f9804, 0xbef90dae), new int64(0x1b710b35, 0x131c471b),
			 new int64(0x28db77f5, 0x23047d84), new int64(0x32caab7b, 0x40c72493),
			 new int64(0x3c9ebe0a, 0x15c9bebc), new int64(0x431d67c4, 0x9c100d4c),
			 new int64(0x4cc5d4be, 0xcb3e42b6), new int64(0x597f299c, 0xfc657e2a),
			 new int64(0x5fcb6fab, 0x3ad6faec), new int64(0x6c44198c, 0x4a475817)];

			 var W = new Array(64);
			 var a, b, c, d, e, f, g, h, i, j;
			 var T1, T2;
			 var charsize = 8;

			 function utf8_encode(str) {
			 return unescape(encodeURIComponent(str));
			 }

			 function str2binb(str) {
			 var bin = [];
			 var mask = (1 << charsize) - 1;
			 var len = str.length * charsize;

			 for (var i = 0; i < len; i += charsize) {
			 bin[i >> 5] |= (str.charCodeAt(i / charsize) & mask) << (32 - charsize - (i % 32));
			 }

			 return bin;
			 }

			 function binb2hex(binarray) {
			 var hex_tab = '0123456789abcdef';
			 var str = '';
			 var length = binarray.length * 4;
			 var srcByte;

			 for (var i = 0; i < length; i += 1) {
			 srcByte = binarray[i >> 2] >> ((3 - (i % 4)) * 8);
			 str += hex_tab.charAt((srcByte >> 4) & 0xF) + hex_tab.charAt(srcByte & 0xF);
			 }

			 return str;
			 }

			 function safe_add_2(x, y) {
			 var lsw, msw, lowOrder, highOrder;

			 lsw = (x.lowOrder & 0xFFFF) + (y.lowOrder & 0xFFFF);
			 msw = (x.lowOrder >>> 16) + (y.lowOrder >>> 16) + (lsw >>> 16);
			 lowOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

			 lsw = (x.highOrder & 0xFFFF) + (y.highOrder & 0xFFFF) + (msw >>> 16);
			 msw = (x.highOrder >>> 16) + (y.highOrder >>> 16) + (lsw >>> 16);
			 highOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

			 return new int64(highOrder, lowOrder);
			 }

			 function safe_add_4(a, b, c, d) {
			 var lsw, msw, lowOrder, highOrder;

			 lsw = (a.lowOrder & 0xFFFF) + (b.lowOrder & 0xFFFF) + (c.lowOrder & 0xFFFF) + (d.lowOrder & 0xFFFF);
			 msw = (a.lowOrder >>> 16) + (b.lowOrder >>> 16) + (c.lowOrder >>> 16) + (d.lowOrder >>> 16) + (lsw >>> 16);
			 lowOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

			 lsw = (a.highOrder & 0xFFFF) + (b.highOrder & 0xFFFF) + (c.highOrder & 0xFFFF) + (d.highOrder & 0xFFFF) + (msw >>> 16);
			 msw = (a.highOrder >>> 16) + (b.highOrder >>> 16) + (c.highOrder >>> 16) + (d.highOrder >>> 16) + (lsw >>> 16);
			 highOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

			 return new int64(highOrder, lowOrder);
			 }

			 function safe_add_5(a, b, c, d, e) {
			 var lsw, msw, lowOrder, highOrder;

			 lsw = (a.lowOrder & 0xFFFF) + (b.lowOrder & 0xFFFF) + (c.lowOrder & 0xFFFF) + (d.lowOrder & 0xFFFF) + (e.lowOrder & 0xFFFF);
			 msw = (a.lowOrder >>> 16) + (b.lowOrder >>> 16) + (c.lowOrder >>> 16) + (d.lowOrder >>> 16) + (e.lowOrder >>> 16) + (lsw >>> 16);
			 lowOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

			 lsw = (a.highOrder & 0xFFFF) + (b.highOrder & 0xFFFF) + (c.highOrder & 0xFFFF) + (d.highOrder & 0xFFFF) + (e.highOrder & 0xFFFF) + (msw >>> 16);
			 msw = (a.highOrder >>> 16) + (b.highOrder >>> 16) + (c.highOrder >>> 16) + (d.highOrder >>> 16) + (e.highOrder >>> 16) + (lsw >>> 16);
			 highOrder = ((msw & 0xFFFF) << 16) | (lsw & 0xFFFF);

			 return new int64(highOrder, lowOrder);
			 }

			 function maj(x, y, z) {
			 return new int64(
			 (x.highOrder & y.highOrder) ^ (x.highOrder & z.highOrder) ^ (y.highOrder & z.highOrder),
			 (x.lowOrder & y.lowOrder) ^ (x.lowOrder & z.lowOrder) ^ (y.lowOrder & z.lowOrder)
			 );
			 }

			 function ch(x, y, z) {
			 return new int64(
			 (x.highOrder & y.highOrder) ^ (~x.highOrder & z.highOrder),
			 (x.lowOrder & y.lowOrder) ^ (~x.lowOrder & z.lowOrder)
			 );
			 }

			 function rotr(x, n) {
			 if (n <= 32) {
			 return new int64(
			 (x.highOrder >>> n) | (x.lowOrder << (32 - n)),
			 (x.lowOrder >>> n) | (x.highOrder << (32 - n))
			 );
			 } else {
			 return new int64(
			 (x.lowOrder >>> n) | (x.highOrder << (32 - n)),
			 (x.highOrder >>> n) | (x.lowOrder << (32 - n))
			 );
			 }
			 }

			 function sigma0(x) {
			 var rotr28 = rotr(x, 28);
			 var rotr34 = rotr(x, 34);
			 var rotr39 = rotr(x, 39);

			 return new int64(
			 rotr28.highOrder ^ rotr34.highOrder ^ rotr39.highOrder,
			 rotr28.lowOrder ^ rotr34.lowOrder ^ rotr39.lowOrder
			 );
			 }

			 function sigma1(x) {
			 var rotr14 = rotr(x, 14);
			 var rotr18 = rotr(x, 18);
			 var rotr41 = rotr(x, 41);

			 return new int64(
			 rotr14.highOrder ^ rotr18.highOrder ^ rotr41.highOrder,
			 rotr14.lowOrder ^ rotr18.lowOrder ^ rotr41.lowOrder
			 );
			 }

			 function gamma0(x) {
			 var rotr1 = rotr(x, 1), rotr8 = rotr(x, 8), shr7 = shr(x, 7);

			 return new int64(
			 rotr1.highOrder ^ rotr8.highOrder ^ shr7.highOrder,
			 rotr1.lowOrder ^ rotr8.lowOrder ^ shr7.lowOrder
			 );
			 }

			 function gamma1(x) {
			 var rotr19 = rotr(x, 19);
			 var rotr61 = rotr(x, 61);
			 var shr6 = shr(x, 6);

			 return new int64(
			 rotr19.highOrder ^ rotr61.highOrder ^ shr6.highOrder,
			 rotr19.lowOrder ^ rotr61.lowOrder ^ shr6.lowOrder
			 );
			 }

			 function shr(x, n) {
			 if (n <= 32) {
			 return new int64(
			 x.highOrder >>> n,
			 x.lowOrder >>> n | (x.highOrder << (32 - n))
			 );
			 } else {
			 return new int64(
			 0,
			 x.highOrder << (32 - n)
			 );
			 }
			 }

			 str = utf8_encode(str);
			 strlen = str.length*charsize;
			 str = str2binb(str);

			 str[strlen >> 5] |= 0x80 << (24 - strlen % 32);
			 str[(((strlen + 128) >> 10) << 5) + 31] = strlen;

			 for (var i = 0; i < str.length; i += 32) {
			 a = H[0];
			 b = H[1];
			 c = H[2];
			 d = H[3];
			 e = H[4];
			 f = H[5];
			 g = H[6];
			 h = H[7];

			 for (var j = 0; j < 80; j++) {
			 if (j < 16) {
			 W[j] = new int64(str[j*2 + i], str[j*2 + i + 1]);
			 } else {
			 W[j] = safe_add_4(gamma1(W[j - 2]), W[j - 7], gamma0(W[j - 15]), W[j - 16]);
			 }

			 T1 = safe_add_5(h, sigma1(e), ch(e, f, g), K[j], W[j]);
			 T2 = safe_add_2(sigma0(a), maj(a, b, c));
			 h = g;
			 g = f;
			 f = e;
			 e = safe_add_2(d, T1);
			 d = c;
			 c = b;
			 b = a;
			 a = safe_add_2(T1, T2);
			 }

			 H[0] = safe_add_2(a, H[0]);
			 H[1] = safe_add_2(b, H[1]);
			 H[2] = safe_add_2(c, H[2]);
			 H[3] = safe_add_2(d, H[3]);
			 H[4] = safe_add_2(e, H[4]);
			 H[5] = safe_add_2(f, H[5]);
			 H[6] = safe_add_2(g, H[6]);
			 H[7] = safe_add_2(h, H[7]);
			 }

			 var binarray = [];
			 for (var i = 0; i < H.length; i++) {
			 binarray.push(H[i].highOrder);
			 binarray.push(H[i].lowOrder);
			 }
			 return binb2hex(binarray);
		}
	
	//Search panel members from backend
	$(document).ready(function(){
		//document.getElementById("data_list").style.display = "none";
		$('#draw_date').datepicker().datepicker('setDate', 'today');
		$(".test_draw_data").hide();
		$(".live_draw_data").hide();				
     
    const nic_list =[];
    // Set search input value on click of result item
    
	
});

</script>



<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?php
  if(isset($_POST['add_user'])){
 if(empty($errors)){
	   
       $mode               = remove_junk($db->escape($_POST['mode']));	
	   //$lottery_name       = remove_junk($db->escape($_POST['lottery-name']));
	   $draw_date          = remove_junk($db->escape($_POST['draw_date']));
	   $draw_no            = remove_junk($db->escape($_POST['draw_no']));
	   $operator           = remove_junk($db->escape($_POST['operator']));
	   $auto_generated_pin = remove_junk($db->escape($_POST['auto_generated_pin']));
	   //$auto_generated_pin = test_input($_POST["auto_generated_pin"]);
	   $panel_1=$panel_2=$panel_3=$panel_4=$panel_5=NULL;
	   $pin_1=$pin_2=$pin_3=$pin_4=$pin_5=NULL;
	   
	   
	   if($mode == "TEST"){
		   
		   $panel_1   = $tp1['id'];		   
		   $panel_2   = $tp2['id'];
		   $panel_3   = $tp3['id'];
		   $panel_4   = $tp4['id'];
		   $panel_5   = $tp5['id'];	   
		   
		   
		   $pin_1   = $test_draw_inputs[0]['pin_no'];
		   $pin_2   = $test_draw_inputs[1]['pin_no'];
		   $pin_3   = $test_draw_inputs[2]['pin_no'];
		   $pin_4   = $test_draw_inputs[3]['pin_no'];
		   $pin_5   = $test_draw_inputs[4]['pin_no']; 
		   
		    
	   }else if($mode == "LIVE"){
		   $panel_1   = $lp1['id'];
		   $panel_2   = $lp2['id'];
		   $panel_3   = $lp3['id'];
		   $panel_4   = $lp4['id'];
		   $panel_5   = $lp5['id'];
		   
		   		   
		   $pin_1= $live_draw_inputs[0]['pin_no'];
		   $pin_2= $live_draw_inputs[1]['pin_no'];
		   $pin_3= $live_draw_inputs[2]['pin_no'];
		   $pin_4= $live_draw_inputs[3]['pin_no'];
		   $pin_5= $live_draw_inputs[4]['pin_no'];
	   }
	   
		   	   
	   
	   	   
		
		$query  = "INSERT INTO `draw`(";
        $query .="  `draw_date`, `draw_no`, `mode`, `operator`, `panel_1`, `panel_2`, `panel_3`, `panel_4`, `panel_5`, `pin_1`, `pin_2`, `pin_3`, `pin_4`, `pin_5`, `auto_generated_pin`,`created_by`";
        $query .=") VALUES (";
        $query .=" '{$draw_date}','{$draw_no}','{$mode}','{$operator}','{$panel_1}','{$panel_2}','{$panel_3}','{$panel_4}','{$panel_5}','{$pin_1}','{$pin_2}','{$pin_3}','{$pin_4}','{$pin_5}','{$auto_generated_pin}','{$created_by}'";
        $query .=")";
		
						
		echo "<script> location.href='test_draw.php'; </script>";		
        if($db->query($query)){
          //sucess
          $session->msg('s',"Draw data has been inserted successfully.! ");
		  redirect('test_draw.php', true);
        } else {
          //failed
          $session->msg('d',' Sorry failed to create Lottery!'.$query);
          redirect('test_draw.php', false);
        }
   } else {
     $session->msg("d", "$errors");
      //redirect('test_draw.php',false);
   }
   
 }
?>
<?php include_once('layouts/header.php'); ?>
  <?php echo display_msg($msg);?>
  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Mahadhana Sampatha Digital  Draw</span>
       </strong>
      </div>
      <div class="panel-body">
	  <form name="myForm" id="myForm" action="test_draw.php" method = "POST" enctype = "multipart/form-data">
       <!--------------------------------------------------->
		<div class="col-md-12">	
	  		<div class="row" style="background:#bebebe;">
				<div class="col-md-4"> 
				<label>Mode</label><br>	
					<select class="form-control" name="mode" id="mode" onchange="update(this.value)">
							  <option value="">Select Mode</option>                    
								  <option value="TEST">Test</option>
								  <option value="LIVE">Live</option>
							   </option>                    
					</select><br/>
				</div>
			</div>			
			
			<div class="col-md-4">
				<div class="form-group">
					<label for="draw_date">Draw Date</label>
					<input type="text" style="text-align:left" class="datepicker form-control" name="draw_date"  id="draw_date" placeholder="Draw Date">
				</div>
            </div>
			<div class="col-md-4">
				<div class="form-group">
				  <label for="draw_no">Draw No</label>
					 <input class="form-control" name="draw_no" id="draw_no" placeholder="Draw No"  type="number">
				</div>
			</div>
			
			<div class="col-md-4"> 
				<label>Operator</label><br>	
					<select class="form-control" name="operator" id="operator" onchange="update(this.value)">
							  <option value="">Select Operator</option>                    
								   <?php  foreach ($all_draw_users as $d_user): ?>
								<option value="<?php echo $d_user['name'] ?>">
                        <?php echo $d_user['name'] ?></option>
                    <?php endforeach; ?>							                      
					</select><br/>
				</div>
			
						
			<div class="col-md-4">
				<div class="form-group">
					<label for="auto_generated_pin">Computer Generated Random Number</label>
					<input type="text" class="form-control" name="auto_generated_pin" id="auto_generated_pin" style="font-size:18px" readonly>
				</div>	
			</div>
			<div class="col-md-4">
			<br/>
			<button type="button" name="generate" class="btn btn-primary" onclick="calcRandomNo();">Generate</button>		
			</div>
				
        
		
			
		<div class="col-md-12">	
			<div class="row" style="background:#A3E4D7;text-align:center;">
				<label for="hm_tp"><b>Draw Panel</b></label>								
			</div>
			
			<div id="winner_report" name="winner_report">
			<br>
			<table class="table table-bordered" style="padding-top:10px;" id="data_list" name="data_list">
				<colgroup>									
					<col span="1" style="visibility: collapse">
					<col span="5">
				 </colgroup>
				<thead>					
					<th style="width=5%;display:none;font-size: 12px;">ID</th>
					<th style="font-size: 12px;">#</th>	
					<th style="text-align:center;width=5%;font-size: 12px;">#</th>						
					<th style="text-align:center;width=20%;font-size: 12px;">Name</th>					
					<th style="text-align:center;width=15%;font-size: 12px;">NIC</th>					
					<th style="text-align:center;width=10%;font-size: 12px;">Designation</th>
					<th style="text-align:center;width=15%;font-size: 12px;">PIN NO</th>		
					<th style="text-align:center;width=15%;font-size: 12px;">Signature</th>	
				</thead>
				
				<tr class="test_draw_data">					
					<td style="width=5%;display:none;font-size: 12px;"><input type='text' id='panel_1' name='panel_1' value='<?php echo $tp1['id']?>'  readonly> </td>
					<td style="width=5%;font-size: 12px;">1</td>		
					<td style="width=5%;font-size: 12px;">1</td>							
					<td style="text-align:left;width=20%;font-size: 12px;"><?php echo $tp1['member_name']?></td>					
					<td style="text-align:left;width=15%;font-size: 12px;"><?php echo $tp1['nic']?></td>					
					<td style="text-align:left;width=10%;font-size: 12px;"><?php echo $tp1['designation']?></td>
					<td style="text-align:left;width=15%;font-size: 12px;" id='pin_1' name='pin_1'><?php echo $pin1?></td>
					<td style="text-align:left;width=15%;font-size: 12px;" >.........................</td>						
				</tr>
				<tr class="test_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='panel_2' name='panel_2' value='<?php echo $tp2['id']?>'  readonly> </td>
					<td style="width=5%;font-size: 12px;">2</td>
					<td style="width=5%;font-size: 12px;">2</td>					
					<td style="text-align:left;width=20%;font-size: 12px;"><?php echo $tp2['member_name']?></td>					
					<td style="text-align:left;width=15%;font-size: 12px;"><?php echo $tp2['nic']?></td>					
					<td style="text-align:left;width=10%;font-size: 12px;"><?php echo $tp2['designation']?></td>
					<td style="text-align:left;width=15%;font-size: 12px;" id='pin_2' name='pin_2'><?php echo $pin2?></td>
					<td style="text-align:left;width=15%;font-size: 12px;" >.........................</td>
				</tr>
				<tr class="test_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='panel_3' name='panel_3' value='<?php echo $tp3['id']?>' readonly> </td>
					<td style="width=5%;font-size: 12px;">3</td>
					<td style="width=5%;font-size: 12px;">3</td>
					<td style="text-align:left;width=20%;font-size: 12px;"><?php echo $tp3['member_name']?></td>					
					<td style="text-align:left;width=15%;font-size: 12px;"><?php echo $tp3['nic']?></td>					
					<td style="text-align:left;width=10%;font-size: 12px;"><?php echo $tp3['designation']?></td>
					<td style="text-align:left;width=15%;font-size: 12px;" id='pin_3' name='pin_3'><?php echo $pin3?></td>	
					<td style="text-align:left;width=15%;font-size: 12px;" >.........................</td>
				</tr>
				<tr class="test_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='panel_4' name='panel_4' value='<?php echo $tp4['id']?>'  readonly> </td>
					<td style="width=5%;font-size: 12px;">4</td>	
					<td style="width=5%;font-size: 12px;">4</td>	
					<td style="text-align:left;width=20%;font-size: 12px;"><?php echo $tp4['member_name']?></td>					
					<td style="text-align:left;width=15%;font-size: 12px;"><?php echo $tp4['nic']?></td>					
					<td style="text-align:left;width=10%;font-size: 12px;"><?php echo $tp4['designation']?></td>
					<td style="text-align:left;width=15%;font-size: 12px;" id='pin_4' name='pin_4'><?php echo $pin4?></td>	
						<td style="text-align:left;width=15%;font-size: 12px;" >.........................</td>
				</tr >
				<tr class="test_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='panel_5' name='panel_5' value='<?php echo $tp5['id']?>'  readonly></td>
					<td style="width=5%;font-size: 12px;">5</td>
					<td style="width=5%;font-size: 12px;">5</td>
					<td style="text-align:left;width=20%;font-size: 12px;"><?php echo $tp5['member_name']?></td>					
					<td style="text-align:left;width=15%;font-size: 12px;"><?php echo $tp5['nic']?></td>					
					<td style="text-align:left;width=10%;font-size: 12px;"><?php echo $tp5['designation']?></td>
					<td style="text-align:left;width=15%;font-size: 12px;" id='pin_5' name='pin_5'><?php echo $pin5?></td>	
					<td style="text-align:left;width=15%;font-size: 12px;" >.........................</td>					
				</tr>
				
				
				
				<tr class="live_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='lpanel_1' name='lpanel_1' value='<?php echo $lp1['id']?>'  readonly></td>
					<td style="width=5%;font-size: 12px;">1</td>
					<td style="width=5%;font-size: 12px;">1</td>
					<td style="text-align:left;width=20%;font-size: 12px;"><?php echo $lp1['member_name']?></td>					
					<td style="text-align:left;width=15%;font-size: 12px;"><?php echo $lp1['nic']?></td>					
					<td style="text-align:left;width=10%;font-size: 12px;"><?php echo $lp1['designation']?></td>
					<td style="text-align:left;width=15%;font-size: 12px;" id='lpin_1' name='lpin_1'><?php echo $lpin1?></td>	
					<td style="text-align:left;width=15%;font-size: 12px;" >.........................</td>
				</tr>
				<tr class="live_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='lpanel_2' name='lpanel_2' value='<?php echo $lp2['id']?>'  readonly></td>
					<td style="width=5%;font-size: 12px;">2</td>			
					<td style="width=5%;font-size: 12px;">2</td>		
					<td style="text-align:left;width=20%;font-size: 12px;"><?php echo $lp2['member_name']?></td>					
					<td style="text-align:left;width=15%;font-size: 12px;"><?php echo $lp2['nic']?></td>					
					<td style="text-align:left;width=10%;font-size: 12px;"><?php echo $lp2['designation']?></td>
					<td style="text-align:left;width=15%;font-size: 12px;" id='lpin_2' name='lpin_2'><?php echo $lpin2?></td>
					<td style="text-align:left;width=15%;font-size: 12px;" >.........................</td>					
				</tr>
				<tr class="live_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='lpanel_3' name='lpanel_3' value='<?php echo $lp3['id']?>'  readonly></td>
					<td style="width=5%;font-size: 12px;">3</td>	
					<td style="width=5%;font-size: 12px;">3</td>	
					<td style="text-align:left;width=20%;font-size: 12px;"><?php echo $lp3['member_name']?></td>					
					<td style="text-align:left;width=15%;font-size: 12px;"><?php echo $lp3['nic']?></td>					
					<td style="text-align:left;width=10%;font-size: 12px;"><?php echo $lp3['designation']?></td>
					<td style="text-align:left;width=15%;font-size: 12px;" id='lpin_3' name='lpin_3'><?php echo $lpin3?></td>
					<td style="text-align:left;width=15%;font-size: 12px;" >.........................</td>					
				</tr>
				<tr class="live_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='lpanel_4' name='lpanel_4' value='<?php echo $lp4['id']?>'  readonly></td>
					<td style="width=5%;font-size: 12px;">4</td>
					<td style="width=5%;font-size: 12px;">4</td>	
					<td style="text-align:left;width=20%;font-size: 12px;"><?php echo $lp4['member_name']?></td>					
					<td style="text-align:left;width=15%;font-size: 12px;"><?php echo $lp4['nic']?></td>					
					<td style="text-align:left;width=10%;font-size: 12px;"><?php echo $lp4['designation']?></td>
					<td style="text-align:left;width=15%;font-size: 12px;" id='lpin_4' name='lpin_4'><?php echo $lpin4?></td>	
					<td style="text-align:left;width=15%;font-size: 12px;" >.........................</td>					
				</tr>
				<tr class="live_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='lpanel_5' name='lpanel_5' value='<?php echo $lp5['id']?>'  readonly></td>
					<td style="width=5%;font-size: 12px;">5</td>	
					<td style="width=5%;font-size: 12px;">5</td>					
					<td style="text-align:left;width=20%;font-size: 12px;"><?php echo $lp5['member_name']?></td>					
					<td style="text-align:left;width=15%;font-size: 12px;"><?php echo $lp5['nic']?></td>					
					<td style="text-align:left;width=10%;font-size: 12px;"><?php echo $lp5['designation']?></td>
					<td style="text-align:left;width=15%;font-size: 12px;" id='lpin_5' name='lpin_5'><?php echo $lpin5?></td>	
					<td style="text-align:left;width=15%;font-size: 12px;">.........................</td>
				</tr>
				
			</table>
			</div>
			</div>
			
			<div class="col-md-4">
						<br>	
												
						<button type="button" name="add"  class="btn btn-primary" style="width:100px;" onclick="initiateDraw()">Initiate Draw</button>
						<button type="button" name="cancel" id="cancel" class="btn btn-primary" style="width:100px;" onclick="cancelData()">Cancel</button>
			</div>	
		</div>
		
        
      </div>
		
		
		 <!--------------------------------------------------->
        </form>
      </div>

    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
