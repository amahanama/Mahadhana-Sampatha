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
 ?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script type="text/javascript" src="libs/js/moment.min.js"></script>
<script type="text/javascript">
/*
$(document).on("submit", "form", function(event)
{
    event.preventDefault();

    var url  = $(this).attr("action");
    $.ajax({
        url: url,
        type: 'POST',
        dataType: "JSON",
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function (data, status)
        {

        },
        error: function (xhr, desc, err)
        {
            console.log("error");
        }
    });
});
*/
	function update(str){ 
	   //alert('MODE : '+str);
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
				document.getElementById("draw_no").value = <?php echo $live_draw_inputs[0]['draw_no'];?> ;
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
		  
		  cell0.innerHTML = "<input type='text' id='panel_"+count+"' name='panel_"+count+"' value='"+panel_id+"'  disabled='true' style='width:50%'> ";
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
	
	
	//function to generate winning numbers
	function initiateDraw(){		
	//document.getElementById("save_draw").submit();
		var auto_generated_pin=document.getElementById('auto_generated_pin').value;
		
		var mode = document.getElementById("mode").value;
		var panel_1=panel_2=panel_3=panel_4=panel_5=null;
		var pin_1=pin_2=pin_3=pin_4=pin_5=null;
		
		//alert("TEST MODE : "+mode);
		
		if(mode == "TEST"){	
			panel_1=document.getElementById('panel_1').value;
			panel_2=document.getElementById('panel_2').value;
			panel_3=document.getElementById('panel_3').value;
			panel_4=document.getElementById('panel_4').value;
			panel_5=document.getElementById('panel_5').value;
			
			
			pin_1=document.getElementById('data_list').rows[1].cells[5].innerHTML;
			pin_2=document.getElementById('data_list').rows[2].cells[5].innerHTML;
			pin_3=document.getElementById('data_list').rows[3].cells[5].innerHTML;
			pin_4=document.getElementById('data_list').rows[4].cells[5].innerHTML;
			pin_5=document.getElementById('data_list').rows[5].cells[5].innerHTML;
			
			/*alert("TEST pin_1 : "+pin_1);
			alert("TEST pin_2 : "+pin_2);
			alert("TEST pin_3 : "+pin_3);
			alert("TEST pin_4 : "+pin_4);
			alert("TEST pin_5 : "+pin_5);*/
			
		}else if(mode == "LIVE"){
			panel_1=document.getElementById('lpanel_1').value;
			panel_2=document.getElementById('lpanel_2').value;
			panel_3=document.getElementById('lpanel_3').value;
			panel_4=document.getElementById('lpanel_4').value;
			panel_5=document.getElementById('lpanel_5').value;
			
			pin_1=document.getElementById('data_list').rows[6].cells[5].innerHTML;
			pin_2=document.getElementById('data_list').rows[7].cells[5].innerHTML;
			pin_3=document.getElementById('data_list').rows[8].cells[5].innerHTML;
			pin_4=document.getElementById('data_list').rows[9].cells[5].innerHTML;
			pin_5=document.getElementById('data_list').rows[10].cells[5].innerHTML;
			
			/*alert("LIVE pin_1 : "+pin_1);
			alert("LIVE pin_2 : "+pin_2);
			alert("LIVE pin_3 : "+pin_3);
			alert("LIVE pin_4 : "+pin_4);
			alert("LIVE pin_5 : "+pin_5);*/
			
		}
		
		//alert("pin_5 : "+document.getElementById('data_list').rows[5].cells[5].innerHTML);		
		//alert("AUTO: "+auto_generated_pin+"-"+pin_1+"-"+pin_2+"-"+pin_3+"-"+pin_4+"-"+pin_5);
		
		const nums=[];
		nums.push(auto_generated_pin);
		nums.push(pin_1);
		nums.push(pin_2);
		nums.push(pin_3);
		nums.push(pin_4);
		nums.push(pin_5);
		
		let avg=computeAverageOfNumbers(nums);
		
		var hash_val=SHA256(avg);
		//alert('hash_val : '+hash_val);
		
		let result1 = hash_val.substring(0,10);
		let result2 = hash_val.substring(10,20);
		let result3 = hash_val.substring(20,30);
		let result4 = hash_val.substring(30,40);
		let result5 = hash_val.substring(40,50);
		let result6 = hash_val.substring(50,60);
		let eng     = hash_val.substring(60,64);
		//alert(result1 + " ,"+result2+" ,"+result3+" ,"+result4+" ,"+result5+ ", "+result6+ " ,"+eng );
		
		var tot1=calculateSum(result1);
		var tot2=calculateSum(result2);
		var tot3=calculateSum(result3);
		var tot4=calculateSum(result4);
		var tot5=calculateSum(result5);
		var tot6=calculateSum(result6);
		var tot7=getRandomEngLetter(eng);
		
		//alert("Winning Nos: "+tot1 + " ,"+tot2+" ,"+tot3+" ,"+tot4+" ,"+tot5+ ", "+tot6+ " ,"+tot7 );
				
		//var skillsSelect = document.getElementById("lottery-name");
		//var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
		
				
		var draw_no=document.getElementById("draw_no").value;
		//alert("draw_no "+draw_no);
		var draw_date=document.getElementById("draw_date").value;
		//alert("draw_date "+draw_date);
		var pan1=document.getElementById("data_list").rows[0].cells[1].innerHTML;
		//alert("pan1 "+pan1);
		var rand_no=document.getElementById("auto_generated_pin").value;
		
		
		document.getElementById('win_no_1').innerHTML=tot1;
		document.getElementById('win_no_2').innerHTML=tot2;
		document.getElementById('win_no_3').innerHTML=tot3;
		document.getElementById('win_no_4').innerHTML=tot4;
		document.getElementById('win_no_5').innerHTML=tot5;
		document.getElementById('win_no_6').innerHTML=tot6;
		document.getElementById('win_eng').innerHTML=tot7;
		document.getElementById('win_no_1').value=tot1;
		document.getElementById('win_no_2').value=tot2;
		document.getElementById('win_no_3').value=tot3;
		document.getElementById('win_no_4').value=tot4;
		document.getElementById('win_no_5').value=tot5;
		document.getElementById('win_no_6').value=tot6;
		document.getElementById('win_eng').value=tot7;
		
		
		  
  var tbl='<table class="table table-bordered" style="padding-top:10px;" id="data_list" name="data_list">';
	tbl+=			'<colgroup>';									
	tbl+=				'<col span="1" style="visibility: collapse">';
	tbl+=				'<col span="5">';
	tbl+=			 '</colgroup>';
	tbl+=			'<thead>	';	
	tbl+=				'<th style="width=5%;">#</th>';					
	tbl+=				'<th style="text-align:center;width=15%">Name</th>';					
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
	tbl+=				'<td style="width=5%;"><input type="text" id="panel_5" name="panel_5" value="<?php echo $tp5['id']?>"  disabled="true"></td>';
	tbl+=				'<td style="width=5%;">5</td>';					
	tbl+=				'<td style="text-align:left;width=15%"><?php echo $tp5['member_name']?></td>';					
	tbl+=				'<td style="text-align:left;width=15%"><?php echo $tp5['nic']?></td>';					
	tbl+=				'<td style="text-align:left;width=10%"><?php echo $tp5['designation']?></td>';
	tbl+=				'<td style="text-align:left;width=15%" id="pin_5" name="pin_5"><?php echo $pin5?></td>';						
	tbl+=			'</tr>';				
	tbl+=			'<tr class="live_draw_data">';					
	tbl+=				'<td style="width=5%;"><input type="text" id="lpanel_1" name="lpanel_1" value="<?php echo $lp1['id']?>"  disabled="true"></td>';
	tbl+=				'<td style="width=5%;">1</td>';					
	tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp1['member_name']?></td>';					
	tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp1['nic']?></td>';					
	tbl+=				'<td style="text-align:left;width=10%"><?php echo $lp1['designation']?></td>';
	tbl+=				'<td style="text-align:left;width=15%" id="lpin_1" name="lpin_1"><?php echo $lpin1?></td>';					
	tbl+=			'</tr>';
	tbl+=			'<tr class="live_draw_data">';					
	tbl+=				'<td style="width=5%;"><input type="text" id="lpanel_2" name="lpanel_2" value="<?php echo $lp2['id']?>"  disabled="true"></td>';
	tbl+=				'<td style="width=5%;">2</td>';					
	tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp2['member_name']?></td>';					
	tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp2['nic']?></td>';					
	tbl+=				'<td style="text-align:left;width=10%"><?php echo $lp2['designation']?></td>';
	tbl+=				'<td style="text-align:left;width=15%" id="lpin_2" name="lpin_2"><?php echo $lpin2?></td>';					
	tbl+=			'</tr>';
	tbl+=			'<tr class="live_draw_data">';					
	tbl+=				'<td style="width=5%;"><input type="text" id="lpanel_3" name="lpanel_3" value="<?php echo $lp3['id']?>"  disabled="true"></td>';
	tbl+=				'<td style="width=5%;">3</td>';					
	tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp3['member_name']?></td>';					
	tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp3['nic']?></td>';					
	tbl+=				'<td style="text-align:left;width=10%"><?php echo $lp3['designation']?></td>';
	tbl+=				'<td style="text-align:left;width=15%" id="lpin_3" name="lpin_3"><?php echo $lpin3?></td>';					
	tbl+=			'</tr>';
	tbl+=			'<tr class="live_draw_data">';					
	tbl+=				'<td style="width=5%;"><input type="text" id="lpanel_4" name="lpanel_4" value="<?php echo $lp4['id']?>"  disabled="true"></td>';
	tbl+=				'<td style="width=5%;">4</td>';					
	tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp4['member_name']?></td>';					
	tbl+=				'<td style="text-align:left;width=15%"><?php echo $lp4['nic']?></td>';					
	tbl+=				'<td style="text-align:left;width=10%"><?php echo $lp4['designation']?></td>';
	tbl+=				'<td style="text-align:left;width=15%" id="lpin_4" name="lpin_4"><?php echo $lpin4?></td>';				
	tbl+=			'</tr>';
	tbl+=			'<tr class="live_draw_data">';					
	tbl+=				'<td style="width=5%;"><input type="text" id="lpanel_5" name="lpanel_5" value="<?php echo $lp5['id']?>"  disabled="true"></td>';
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
				document.body.innerHTML +="<div style='align:center'><p style='font-size: 14px;padding-left: 260px;font-family:Lucida Sans;padding-bottom: 1em;'>"+mode+" Draw Report - "+draw_no+" </p></div>";
				document.body.innerHTML += "<hr>";
				document.body.innerHTML +="<div style='align:center'><p style='font-size: 25px;padding-left: 260px;font-family:Lucida Sans;'><b>Mahadhana Sampatha</b></p></div>";
				document.body.innerHTML +="<div style='align:center'><p style='font-size: 14px;padding-left: 260px;font-family:Lucida Sans;'>Draw NO & Date "+draw_no+" "+moment(new Date()).format("YYYY-MM-DD HH:mm:ss A")+"</p></div><br/>";
				document.body.innerHTML +="<div style='align:center'><p style='font-size: 25px;padding-left: 260px;font-family:Lucida Sans;'><b>Winning Numbers</b></p></div><br/>";
				document.body.innerHTML +="<p style='font-size: 20px;padding-left: 160px;font-family:Lucida Sans;'><span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot1 + "</span> &emsp; <span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot2+"</span> &emsp; <span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot3+"</span> &emsp; <span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot4+"</span>&emsp; <span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot5+ "</span> &emsp; <span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot6+ "</span> </p><br/><br/>";
                
				document.body.innerHTML +='<div style="align:center"><p style="font-size: 25px;padding-left: 260px;font-family:Lucida Sans;"><b>English Letter</b></p></div><br/>';
				document.body.innerHTML +='<div style="align:center"><p style="font-size: 25px;padding-left: 320px;font-family:Lucida Sans;"><span style="border-width:1px; border-style:solid; border-color:#000000;padding: 0.5em;">'+tot7+ '</span></p><br/></div>';
				
				//Draw Seed
				document.body.innerHTML +='<div style="align:center"><p style="font-size: 16px;padding-left: 300px;font-family:Lucida Sans;"><b>Draw Seed</b></p></div>';
				document.body.innerHTML +='<div><p style="font-size: 14px;padding-left: 150px;font-family:Lucida Sans;">'+hash_val+'</p></div>';
				
				//document.body.innerHTML +='<div>'+tbl+'</div>';				
				document.body.innerHTML += printsection;
				document.body.innerHTML += '<dv>Computer Generated Random Number &emsp;&emsp; '+rand_no+' &emsp;&emsp;&emsp;&emsp;........................................................</div>';
				document.body.innerHTML += '<div><p style="font-size: 14px;padding-left: 500px;">AGM(IT)/System Analyst/IT Assistant</p></div>';
                window.print();
                document.body.innerHTML = getFullContent;
				 //document.getElementById("test_draw").submit();				
				//location.reload();
				/*$.ajax({
				   url:'test_draw.php',
				   type:'post',
				   //data:{username:username,fname:fname,lname:lname,email:email},
				   success:function(response){
					  location.reload(); // reloading page
				   }
				});*/
				save_data();
				
		}
		
		function save_data(){   
		var mode               = $('#mode').val();		   
	    var draw_date          = document.getElementById('draw_date').value;
	    var draw_no            = document.getElementById('draw_no').value;
	    var operator           = document.getElementById('operator').value;
	    var auto_generated_pin = document.getElementById('auto_generated_pin').value;	   
		
		
			var postURL = "/Digital_Draw/save_draw_data.php";  
			var i=1;  	
			var panel_1=panel_2=panel_3=panel_4=panel_5=null;
			var pin_1=pin_2=pin_3=pin_4=pin_5=null;
		/*	
			
		if($('#mode').val() == "TEST"){
		   
		   panel_1   = <?php echo $tp1['id'];?>;		   
		   panel_2   = <?php echo $tp2['id'];?>;
		   panel_3   = <?php echo $tp3['id'];?>;
		   panel_4   = <?php echo $tp4['id'];?>;
		   panel_5   = <?php echo $tp5['id'];?>;   
		   
		   
		   pin_1   = <?php echo $test_draw_inputs[0]['pin_no'];?>;
		   pin_2   = <?php echo $test_draw_inputs[1]['pin_no'];?>;
		   pin_3   = <?php echo $test_draw_inputs[2]['pin_no'];?>;
		   pin_4   = <?php echo $test_draw_inputs[3]['pin_no'];?>;
		   pin_5   = <?php echo $test_draw_inputs[4]['pin_no'];?>; 
		   
		    
	   }else if($('#mode').val() == "LIVE"){
		   panel_1   = <?php echo $lp1['id'];?>;
		   panel_2   = <?php echo $lp2['id'];?>;
		   panel_3   = <?php echo $lp3['id'];?>;
		   panel_4   = <?php echo $lp4['id'];?>;
		   panel_5   = <?php echo $lp5['id'];?>;
		   
		   		   
		   pin_1= <?php echo $live_draw_inputs[0]['pin_no'];?>;
		   pin_2= <?php echo $live_draw_inputs[1]['pin_no'];?>;
		   pin_3= <?php echo $live_draw_inputs[2]['pin_no'];?>;
		   pin_4= <?php echo $live_draw_inputs[3]['pin_no'];?>;
		   pin_5= <?php echo $live_draw_inputs[4]['pin_no'];?>;
	   }*/
			
		alert("mode :"+mode+" draw_date :"+draw_date+" draw_no :"+draw_no);	
			
			var formData = {
      mode: $("#mode").val(),
      draw_date: $("#draw_date").val(),
      draw_no: $("#draw_no").val(),
    };
	
	alert("formData "+formData.toString());
			
			//var dataString = 'mode='+ mode + '&draw_date=' + draw_date+ '&draw_no=' + draw_no+ '&operator=' + operator + '&auto_generated_pin=' + auto_generated_pin;
			
           $.ajax({    
                url:postURL,    
                method:"POST",    
                data: {
				  mode: $("#mode").val(),
				  draw_date: $("#draw_date").val(),
				  draw_no: $("#draw_no").val(),
				},
				//draw_date=' + draw_date+ '&draw_no=' + draw_no+ '&operator=' + operator + '&auto_generated_pin=' + auto_generated_pin;, 
                type:'json',
				cache: false,		
			success: function(dataResult){
			var dataResult = JSON.parse(dataResult);
			if(dataResult.statusCode==200){				
				alert('Data added successfully !'); 						
			}
			else if(dataResult.statusCode==201){
				alert("Error occured !");
			}
			
		}				
                /*success:function(data)    
                {  
				 alert("Data: " + (data) + "\nStatus: " + status);
                    i=1;  
                    //$('.dynamic-added').remove();                     
					alert('Record Inserted Successfully.');  
                }   */ 
           });    
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
	
	function getRandomEngLetter(a){
		var sum=[...a].filter( e => isFinite(e)).reduce((a, b)=>parseInt(a)+parseInt(b));
		//alert('SUM : '+sum);
		var eng_count=0;
		if(sum > 26){
			eng_count=sum - 26;
		}else{
			eng_count=sum;
		}
		let letter=generateRandomLetter(eng_count);
		return letter;
	}
	
	function computeAverageOfNumbers(arr) {	
		var str = arr.join(',');
		console.log("BBBB "+str);
		var arr = str.split(',');

		var sum = arr.reduce(function(a, b) {
		  return +a + +b
		});
		var avg = sum / arr.length;
		console.log("AVERAGE >>> "+toPlainString(avg));
		return toPlainString(avg);
		
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
	
	
	//Search panel members from backend
	$(document).ready(function(){
		
		$('#draw_date').datepicker().datepicker('setDate', 'today');
		$(".test_draw_data").hide();
		$(".live_draw_data").hide();
				
    $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
        if(inputVal.length){
            $.get("backend-search.php", {term: inputVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });
    const nic_list =[];
    // Set search input value on click of result item
    $(document).on("click", ".result p", function(){
		var content=$(this).text();			
		var pieces = content.split(";"); 
		var panel_id=pieces[0];
		var name=pieces[1];
		var nic=pieces[2];		
		var designation=pieces[4];
		
		var rowCount = document.getElementById('data_list').rows.length;
		
		
		//To Add 5 Panel Members
		if(rowCount < 6){
			var contain=containsObject(nic,nic_list);
			
			if(!contain){
				nic_list.push(nic);
				insertTableData(rowCount,name,nic,designation,panel_id);
			}else{
				alert("Panel Member is already exist.");
			}
			
		}else{
			alert("Exceed the Maximum Panel Member Count.");
		}
		//document.getElementById("name1").innerHTML = name;		
        //$(this).parents(".search-box").find('input[type="text"]').val($(this).text());
        //$(this).parent(".result").empty();
		$(this).parents(".search-box").find('input[type="text"]').val("");
        $(this).parent(".result").empty();
    });
	
	
});
</script>
<?php
/****************************************************************************************************/
if (isset($_POST['submit'])) {
if(isset($_POST['add'])){   
   //$req_fields = array('mode','lottery-name','draw_date','draw_no','operator','auto_generated_pin','pin_1','pin_2','pin_3','pin_4','pin_5');
  // validate_fields($req_fields);  
   echo '<script type="text/javascript">',
     'jsfunction();',
     '</script>'
	;
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
	   
	   
	   
	   $win_1   = remove_junk($db->escape($_POST['win_no_1']));
	   $win_2   = remove_junk($db->escape($_POST['win_no_2']));
	   $win_3   = remove_junk($db->escape($_POST['win_no_3']));
	   $win_4   = remove_junk($db->escape($_POST['win_no_4']));
	   $win_5   = remove_junk($db->escape($_POST['win_no_5']));
	   $win_6   = remove_junk($db->escape($_POST['win_no_6']));
	   $win_eng = remove_junk($db->escape($_POST['win_eng']));  
	   	   

        $query  = "INSERT INTO `draw`(";
        $query .="  `draw_date`, `draw_no`, `mode`, `operator`, `panel_1`, `panel_2`, `panel_3`, `panel_4`, `panel_5`, `pin_1`, `pin_2`, `pin_3`, `pin_4`, `pin_5`, `auto_generated_pin`, `winning_nos`, `english_letter`, `seed`, `created_by`";
        $query .=") VALUES (";
        $query .=" '{$draw_date}','{$draw_no}','{$mode}','{$operator}','{$panel_1}','{$panel_2}','{$panel_3}','{$panel_4}','{$panel_5}','{$pin_1}','{$pin_2}','{$pin_3}','{$pin_4}','{$pin_5}','{$auto_generated_pin}','{$winning_nos}', '{$english_letter}', '{$seed}','{$created_by}'";
        $query .=")";
		
		//console.log("Query : "+$query);
		

		
        if($db->query($query)){
          //sucess
          $session->msg('s',"Lottery has been created! ".$query);
          redirect('test_draw.php', false);
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
 }
 
 
 function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

?>
<?php include_once('layouts/header.php'); ?>
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  </head>
  <?php echo display_msg($msg); ?>
  <div class="row">
    <div class="panel panel-default">
      <div class="panel-heading">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Mahadhana Sampatha Digital  Draw </span>
       </strong>
      </div>
      <div class="panel-body">	  
	  <form action="test_draw.php" name="save_draw" id="save_draw" method="POST" >
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
				<div class="form-group">
				  <label for="operator">Operator</label>
					 <input class="form-control" name="operator" id="operator" type="text">
				</div>
			</div>
			
			
			<div class="col-md-4">
				<div class="form-group">
					<label for="auto_generated_pin">Computer Generated Random Number</label>
					<input type="text" class="form-control" name="auto_generated_pin" id="auto_generated_pin" style="font-size:18px" disabled="true">
				</div>	
			</div>
			<div class="col-md-4">
			<br/>
			<button type="button" name="generate" class="btn btn-primary" onclick="calcRandomNo();">Generate</button>		
			</div>
				
        
		
		<div class="col-md-12">			
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
					<th style="width=5%;display:none;">ID</th>
					<th style="width=5%;">#</th>					
					<th style="text-align:center;width=15%">Name</th>					
					<th style="text-align:center;width=15%">NIC</th>					
					<th style="text-align:center;width=10%">Designation</th>
					<th style="text-align:center;width=15%">PIN NO</th>					
				</thead>
				
				<tr class="test_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='panel_1' name='panel_1' value='<?php echo $tp1['id']?>'  disabled='true'> </td>
					<td style="width=5%;">1</td>					
					<td style="text-align:left;width=15%"><?php echo $tp1['member_name']?></td>					
					<td style="text-align:left;width=15%"><?php echo $tp1['nic']?></td>					
					<td style="text-align:left;width=10%"><?php echo $tp1['designation']?></td>
					<td style="text-align:left;width=15%" id='pin_1' name='pin_1'><?php echo $pin1?></td>					
				</tr>
				<tr class="test_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='panel_2' name='panel_2' value='<?php echo $tp2['id']?>'  disabled='true'> </td>
					<td style="width=5%;">2</td>					
					<td style="text-align:left;width=15%"><?php echo $tp2['member_name']?></td>					
					<td style="text-align:left;width=15%"><?php echo $tp2['nic']?></td>					
					<td style="text-align:left;width=10%"><?php echo $tp2['designation']?></td>
					<td style="text-align:left;width=15%" id='pin_2' name='pin_2'><?php echo $pin2?></td>					
				</tr>
				<tr class="test_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='panel_3' name='panel_3' value='<?php echo $tp3['id']?>'  disabled='true'> </td>
					<td style="width=5%;">3</td>					
					<td style="text-align:left;width=15%"><?php echo $tp3['member_name']?></td>					
					<td style="text-align:left;width=15%"><?php echo $tp3['nic']?></td>					
					<td style="text-align:left;width=10%"><?php echo $tp3['designation']?></td>
					<td style="text-align:left;width=15%" id='pin_3' name='pin_3'><?php echo $pin3?></td>					
				</tr>
				<tr class="test_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='panel_4' name='panel_4' value='<?php echo $tp4['id']?>'  disabled='true'> </td>
					<td style="width=5%;">4</td>					
					<td style="text-align:left;width=15%"><?php echo $tp4['member_name']?></td>					
					<td style="text-align:left;width=15%"><?php echo $tp4['nic']?></td>					
					<td style="text-align:left;width=10%"><?php echo $tp4['designation']?></td>
					<td style="text-align:left;width=15%" id='pin_4' name='pin_4'><?php echo $pin4?></td>				
				</tr >
				<tr class="test_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='panel_5' name='panel_5' value='<?php echo $tp5['id']?>'  disabled='true'></td>
					<td style="width=5%;">5</td>					
					<td style="text-align:left;width=15%"><?php echo $tp5['member_name']?></td>					
					<td style="text-align:left;width=15%"><?php echo $tp5['nic']?></td>					
					<td style="text-align:left;width=10%"><?php echo $tp5['designation']?></td>
					<td style="text-align:left;width=15%" id='pin_5' name='pin_5'><?php echo $pin5?></td>						
				</tr>
				
				
				
				<tr class="live_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='lpanel_1' name='lpanel_1' value='<?php echo $lp1['id']?>'  disabled='true'></td>
					<td style="width=5%;">1</td>					
					<td style="text-align:left;width=15%"><?php echo $lp1['member_name']?></td>					
					<td style="text-align:left;width=15%"><?php echo $lp1['nic']?></td>					
					<td style="text-align:left;width=10%"><?php echo $lp1['designation']?></td>
					<td style="text-align:left;width=15%" id='lpin_1' name='lpin_1'><?php echo $lpin1?></td>					
				</tr>
				<tr class="live_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='lpanel_2' name='lpanel_2' value='<?php echo $lp2['id']?>'  disabled='true'></td>
					<td style="width=5%;">2</td>					
					<td style="text-align:left;width=15%"><?php echo $lp2['member_name']?></td>					
					<td style="text-align:left;width=15%"><?php echo $lp2['nic']?></td>					
					<td style="text-align:left;width=10%"><?php echo $lp2['designation']?></td>
					<td style="text-align:left;width=15%" id='lpin_2' name='lpin_2'><?php echo $lpin2?></td>					
				</tr>
				<tr class="live_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='lpanel_3' name='lpanel_3' value='<?php echo $lp3['id']?>'  disabled='true'></td>
					<td style="width=5%;">3</td>					
					<td style="text-align:left;width=15%"><?php echo $lp3['member_name']?></td>					
					<td style="text-align:left;width=15%"><?php echo $lp3['nic']?></td>					
					<td style="text-align:left;width=10%"><?php echo $lp3['designation']?></td>
					<td style="text-align:left;width=15%" id='lpin_3' name='lpin_3'><?php echo $lpin3?></td>					
				</tr>
				<tr class="live_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='lpanel_4' name='lpanel_4' value='<?php echo $lp4['id']?>'  disabled='true'></td>
					<td style="width=5%;">4</td>					
					<td style="text-align:left;width=15%"><?php echo $lp4['member_name']?></td>					
					<td style="text-align:left;width=15%"><?php echo $lp4['nic']?></td>					
					<td style="text-align:left;width=10%"><?php echo $lp4['designation']?></td>
					<td style="text-align:left;width=15%" id='lpin_4' name='lpin_4'><?php echo $lpin4?></td>				
				</tr>
				<tr class="live_draw_data">					
					<td style="width=5%;display:none;"><input type='text' id='lpanel_5' name='lpanel_5' value='<?php echo $lp5['id']?>'  disabled='true'></td>
					<td style="width=5%;">5</td>					
					<td style="text-align:left;width=15%"><?php echo $lp5['member_name']?></td>					
					<td style="text-align:left;width=15%"><?php echo $lp5['nic']?></td>					
					<td style="text-align:left;width=10%"><?php echo $lp5['designation']?></td>
					<td style="text-align:left;width=15%" id='lpin_5' name='lpin_5'><?php echo $lpin5?></td>						
				</tr>
				
			</table>
			</div>
			
						
			<div id="result" name="result">
			<input type="text" id="win_no_1" name="win_no_1"><br>
			<input type="text" id="win_no_2" name="win_no_2"><br>
			<input type="text" id="win_no_3" name="win_no_3"><br>
			<input type="text" id="win_no_4" name="win_no_4"><br>
			<input type="text" id="win_no_5" name="win_no_5"><br>
			<input type="text" id="win_no_6" name="win_no_6"><br>
			<input type="text" id="win_eng" name="win_eng"><br>
			</div>
			
			<div class="col-md-4">
						<br>						
						<button type="button" name="add"  class="btn btn-primary" style="width:100px;" onclick="initiateDraw()">Initiate Draw</button>
						<button type="button" name="cancel" class="btn btn-primary" style="width:100px;">Cancel</button>
			</div>	
		</div>
		</div>
        </form>
      </div>

    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
