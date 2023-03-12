<?php
  $page_title = 'Allocate Draw';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
	page_require_level(3);  
    $groups = find_all('user_groups');
    $all_categories = find_all('lotteries');
  	$test_draw_no=find_by_mode('TEST');
	$live_draw_no=find_by_mode('LIVE');
  $all_draw_users = find_draw_user();	
  //****************************
			
	$p_id=find_by_mode("Test");
	  
  
  //*****************************************************
  function validating($phone){
	if(preg_match('/^[0-9]{10}+$/', $phone)) {
		echo " Valid Phone Number";
	} else {
		echo " Invalid Phone Number";
	}
  }
?>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="libs/js/moment.min.js"></script>
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
	
	//Delete Table Row
	function deleteRow(btn) {
	  var row = btn.parentNode.parentNode;
	  row.parentNode.removeChild(row);
	}
	
	//Insert Table row and data
	function insertTableData(count,name,nic,designation,panel_id,work_place) {		
		  var table = document.getElementById("data_list");
		  var row = table.insertRow(count);	  
		  
		  var cell0 = row.insertCell(0);
		  var cellCount =row.insertCell(1);
		  var cell1 = row.insertCell(2);
		  var cell2 = row.insertCell(3);
		  var cell3 = row.insertCell(4);
		  var cell4 = row.insertCell(5);	
		  var cell5 = row.insertCell(6);			  
		  
		  cell0.innerHTML = "<input type='text' name='panel_"+count+"' id='panel_"+count+"' value='"+panel_id+"'  disabled='true' style='width:50%'> ";
		  cellCount.innerHTML = count;
		  cell1.innerHTML = name;
		  cell2.innerHTML = nic;		  
		  cell3.innerHTML = designation;
		  cell4.innerHTML = work_place;
		  cell5.innerHTML = "<button onclick='deleteRow(this);'>Remove</button>";
		  
		  document.getElementById('Panel_'+count).value=panel_id;
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
	}
	
	
		
	function calculateSum(a){		
		var sum=[...a].filter( e => isFinite(e)).reduce((a, b)=>parseInt(a)+parseInt(b));
		//console.log([...a].filter( e => isFinite(e)).reduce((a, b)=>parseInt(a)+parseInt(b)));
		var mod= sum % 10;	
		return mod;
	}
	
	function getRandomEngLetter(a){
		var sum=[...a].filter( e => isFinite(e)).reduce((a, b)=>parseInt(a)+parseInt(b));
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
		var arr = str.split(',');

		var sum = arr.reduce(function(a, b) {
		  return +a + +b
		});
		var avg = sum / arr.length;		
		return toPlainString(avg);
		
	}
	
	 function toPlainString(num) {		
		return (''+ +num).replace(/(-?)(\d*)\.?(\d*)e([+-]\d+)/,
		function(a,b,c,d,e) {
		  return e < 0
			? b + '0.' + Array(1-e-c.length).join(0) + c + d
			: b + c + d + Array(e-d.length+1).join(0);
		});
	}
	//Generate Random English Letter
	function generateRandomLetter(i) {		
		const alphabet = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];
		return alphabet[i];
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
	
	
	function get_mode(str) {
		  var xhttp;		  
		  if(str == "Test"){
			  document.getElementById("draw_no").value=<?php echo implode($test_draw_no);?>;
		  }else if(str == "Live"){
			  document.getElementById("draw_no").value=<?php echo implode($live_draw_no);?>;
		  }
		  document.getElementById("test").checked  && !document.getElementById("live").checked
		 /* xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
			document.getElementById("draw_no").innerHTML = this.responseText;
			}
		  };
		  xhttp.open("GET", "get_latest_data.php?q="+str, true);
		  xhttp.send();*/
	}
				
				
    
	
	
	//Search panel members from backend
	$(document).ready(function(){
		
		$('#cancel').click(function(e) {
		  location.reload();
		 });
		 
		 
			$('#draw_date').datepicker().datepicker('setDate', 'today');
		
			
		
		
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
				var work_place=pieces[3];
				
				var rowCount = document.getElementById('data_list').rows.length;
				
				
				//To Add 5 Panel Members
				if(rowCount < 6){
					var contain=containsObject(nic,nic_list);
					
					if(!contain){
						nic_list.push(nic);
						insertTableData(rowCount,name,nic,designation,panel_id,work_place);
					}else{
						alert("Panel Member is already exist.");
					}
					
				}else{
					alert("Exceed the Maximum Panel Member Count.");
				}
			
				$(this).parents(".search-box").find('input[type="text"]').val("");
				$(this).parent(".result").empty();
			});
	
	 
			$('#submit').click(function(){  
			 
			 var rowCount = document.getElementById('data_list').rows.length;
			 			
			   if(!document.getElementById("test").checked  && !document.getElementById("live").checked) {					
					alert("Draw Mode is required");
					return false;
			  }else if (document.getElementById("draw_no").value.length == 0 && document.getElementById("draw_no").value == 0) {
					document.getElementById("draw_no").focus();
					alert("Draw Number is required");
					return false;
			  } else if (document.getElementById("operator").value.length == 0) {
					document.getElementById("operator").focus();
					alert("Operator is required");
					return false;
			  }  else if (rowCount < 6) {
					document.getElementById("sug_nic").focus();
					alert("Please select 5 panel members.....");
					return false;
			  } else {		
			
					var postURL = "/Digital_Draw/addmore.php";  
					var i=1;  	
				   $.ajax({    
						url:postURL,    
						method:"POST",    
						data:$('#add_name').serialize(),  
						type:'json',  
						success:function(data)    
						{ 
						 
							i=1;  
							//$('.dynamic-added').remove();  
							$('#add_name')[0].reset();  
									alert('Record Inserted Successfully.');  
						}    
				   });    
			  }	
		});
	});
</script>


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
          <span>Mahadhana Sampatha Digital Draw</span>
       </strong>
      </div>
      <div class="panel-body">
	  <form action="assign_draw.php" name="add_name" id="add_name" method = "POST">
	  <div class="col-md-12">	
	  
	  
	  <label>Mode</label><br>	
		<table class="table" style="width:50%;background:#bebebe;">
		<thead>
		<tr>
        <th>
		<input type="radio" id="test" name="mode" value="Test" onchange="get_mode(this.value);">
					  <label for="test">Test</label><br>
		</th>
        <th><input type="radio" id="live" name="mode" value="Live" onchange="get_mode(this.value);">
					  <label for="live">Live</label><br></th>
		</tr>
		</thead>
		</table>
			
	  </div>
			<div class="col-md-3">          
				<div class="form-group">
					<label for="lottery-name">Lottery</label>
					<select class="form-control" id="lottery-name" name="lottery-name">                      
                    <?php  foreach ($all_categories as $cat): ?>
                      <option value="<?php echo (int)$cat['id'] ?>">
                        <?php echo $cat['lottery_name'] ?></option>
                    <?php endforeach; ?>
                    </select>	
				</div>
			</div>
			
			<div class="col-md-3">
				<div class="form-group">
					<label for="draw_date">Draw Date</label>
					<input type="text" style="text-align:left" class="datepicker form-control" name="draw_date"  id="draw_date"placeholder="Draw Date">
				</div>
            </div>
			<div class="col-md-3">
				<div class="form-group">
				  <label for="draw_no">Draw No</label>
					 <input class="form-control" name="draw_no" id="draw_no" placeholder="Draw No" readonly  type="number" value="<?php echo remove_junk(ucwords($res_draw)); ?>">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
				  <label>Operator</label><br>	
					<select class="form-control" name="operator" id="operator" onchange="update(this.value)">
							  <option value="">Select Operator</option>                    
								   <?php  foreach ($all_draw_users as $d_user): ?>
								<option value="<?php echo $d_user['name'] ?>">
                        <?php echo $d_user['name'] ?></option>
                    <?php endforeach; ?>							                      
					</select><br/>
				</div>
			</div>
					
			<div class="col-md-12">		
			<div class="row" style="background:#A3E4D7;text-align:center;">
				<label for="hm_tp">Search Panel Members</label>				
			</div>
			<div class="form-group">
				<div class="row">			
					<div class="col-md-4">
						<div class="search-box">
						Name/NIC
						<input type="text" id="sug_nic" class="form-control" name="sug_nic"  autocomplete="off" placeholder="Search Panel Member...">
						<div class="result"></div>
						</div>
					</div>
					<div class="col-md-4">
						<br>
						<!--<button type="button" name="search" class="btn btn-primary" readonly >Search & Add Panel Member</button>-->
						<div><p style='font-size: 14px;'><span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;background-color:powderblue;'>Search & Add Panel Member</span></p></div>
					</div>
					
				</div>
            
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
					<col span="6">
				 </colgroup>
				<thead>
					
					<th style="width=5%;">ID</th>
					<th style="width=5%;">#</th>					
					<th style="text-align:center;width=20%">Name</th>					
					<th style="text-align:center;width=15%">NIC</th>					
					<th style="text-align:center;width=25%">Designation</th>	
					<th style="text-align:center;width=20%">Workplace</th>					
					<th style="text-align:center;width=10%">Action</th>
				</thead>
			</table>
			</div>
			
			<div id="result" name="result" style="display:none;">
			<input type="text" id="Panel_1" name="Panel_1" value=""><br>
			<input type="text" id="Panel_2" name="Panel_2" value=""><br>
			<input type="text" id="Panel_3" name="Panel_3" value=""><br>
			<input type="text" id="Panel_4" name="Panel_4" value=""><br>
			<input type="text" id="Panel_5" name="Panel_5" value=""><br>			
			</div>
			
			<div class="col-md-4">
						<br>
						<button type="submit" name="submit" id="submit" class="btn btn-primary" style="width:100px;" >Allocate</button>
						<button type="button" name="cancel" id="cancel" class="btn btn-primary" style="width:100px;">Cancel</button>
			</div>	
			
		</div>
		</div>
        </form>
      </div>

    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
