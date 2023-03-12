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
	//session_start();
	
	if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 	

?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
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
		if(document.getElementById('auto_generated_pin').value.length == 0){
			alert("Please enter Computer Generated Rrandom Number");	
			document.getElementById('auto_generated_pin').focus();
			return false;
		}else if(document.getElementById('pin_1').value.length == 0){
			alert("Please enter the PIN No 01.");	
			document.getElementById('pin_1').focus();
			return false;		
		}else if(document.getElementById('pin_2').value.length == 0){
			alert("Please enter the PIN No 02.");	
			document.getElementById('pin_2').focus();
			return false;	
		}else if(document.getElementById('pin_3').value.length == 0){
			alert("Please enter the PIN No 03.");	
			document.getElementById('pin_3').focus();
			return false;	
		}else if(document.getElementById('pin_4').value.length == 0){
			alert("Please enter the PIN No 04.");	
			document.getElementById('pin_4').focus();
			return false;		
		}else if(document.getElementById('pin_5').value.length == 0){
			alert("Please enter the PIN No 05.");	
			document.getElementById('pin_5').focus();
			return false;								
		}else{
			return true;
		}
		
	}
	
	
	//function to generate winning numbers
	function initiateDraw(){
		if(validateInputs()){		
		//document.getElementById("save_draw").submit();
		var auto_generated_pin=document.getElementById('auto_generated_pin').value;		
		var pin_1=pin_2=pin_3=pin_4=pin_5=null;
			
					
		pin_1=document.getElementById('pin_1').value;
		pin_2=document.getElementById('pin_2').value;
		pin_3=document.getElementById('pin_3').value;
		pin_4=document.getElementById('pin_4').value;
		pin_5=document.getElementById('pin_5').value;			
						
		
		
		const nums=[];
		//nums.push(auto_generated_pin);
		nums.push(pin_1);
		nums.push(pin_2);
		nums.push(pin_3);
		nums.push(pin_4);
		nums.push(pin_5);
		
		let median_val=median(nums);
		
		const avg_arr=[];
		avg_arr.push(median_val);
		avg_arr.push(auto_generated_pin);
		
		
		let avg=computeAverageOfNumbers(avg_arr);		
				
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
		
		
		var tot1=calculateSum(result1);
		var tot2=calculateSum(result2);
		var tot3=calculateSum(result3);
		var tot4=calculateSum(result4);
		var tot5=calculateSum(result5);
		var tot6=calculateSum(result6);
		var tot7=getRandomEngLetter(eng);
		
		
		var winnings=	tot1 + ","+tot2+","+tot3+","+tot4+","+tot5+ ","+tot6;
		var eng_letter=tot7;
		//var skillsSelect = document.getElementById("lottery-name");
		//var selectedText = skillsSelect.options[skillsSelect.selectedIndex].text;
		
				
		var rand_no=document.getElementById("auto_generated_pin").value;
		
		var result = "'"+tot1 + " ,"+tot2+" ,"+tot3+" ,"+tot4+" ,"+tot5+ ", "+tot6+ " ,"+tot7+"'" ;
		event.preventDefault();		
		
			
		var getFullContent = document.body.innerHTML;
		//var printsection = document.getElementById('winner_report').innerHTML;
						
		document.body.innerHTML ="Development Lotteries Board <br>";
		document.body.innerHTML +="<div style='align:center'><p style='font-size: 12px;padding-left: 290px;font-family:Lucida Sans;'>  Verification Report  </p></div>";
		document.body.innerHTML += "<hr>";
		document.body.innerHTML +="<div style='align:center'><p style='font-size: 20px;padding-left: 290px;font-family:Lucida Sans;'><b>Mahadhana Sampatha</b></p></div>";
		document.body.innerHTML +="<div style='align:center'><p style='font-size: 14px;padding-left: 290px;font-family:Lucida Sans;'> Date "+moment(new Date()).format("YYYY-MM-DD HH:mm:ss A")+"</p></div><br/>";
		document.body.innerHTML +="<div style='align:center'><p style='font-size: 20px;padding-left: 290px;font-family:Lucida Sans;'><b>Winning Numbers</b></p></div><br/>";
		document.body.innerHTML +="<p style='font-size: 20px;padding-left: 190px;font-family:Lucida Sans;'><span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot1 + "</span> &emsp; <span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot2+"</span> &emsp; <span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot3+"</span> &emsp; <span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot4+"</span>&emsp; <span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot5+ "</span> &emsp; <span style='border-width:1px; border-style:solid; border-color:#000000; padding: 0.5em;'>"+tot6+ "</span> </p><br/>";
		
		document.body.innerHTML +='<div style="align:center"><p style="font-size: 20px;padding-left: 290px;font-family:Lucida Sans;"><b>English Letter</b></p></div><br/>';
		document.body.innerHTML +='<div style="align:center"><p style="font-size: 20px;padding-left: 350px;font-family:Lucida Sans;"><span style="border-width:1px; border-style:solid; border-color:#000000;padding: 0.5em;">'+tot7+ '</span></p><br/></div>';
		
		//Draw Seed
		document.body.innerHTML +='<div style="align:center"><p style="font-size: 16px;padding-left: 330px;font-family:Lucida Sans;"><b>Draw Seed</b></p></div>';
		document.body.innerHTML +='<div><p style="font-size: 12px;font-family:Lucida Sans;">'+hash_val+'</p></div><br/><br/>';
		
		//document.body.innerHTML +='<div>'+tbl+'</div>';				
		//document.body.innerHTML += printsection;
		document.body.innerHTML += "<div><p style='font-size: 16px;padding-left: 200px;font-family:Lucida Sans;'> Pin Number 01 &emsp;&emsp; "+pin_1+" &emsp;&emsp;&emsp;&emsp;</p></div><br/>";
		document.body.innerHTML += "<div><p style='font-size: 16px;padding-left: 200px;font-family:Lucida Sans;'>Pin Number 02 &emsp;&emsp; "+pin_2+" &emsp;&emsp;&emsp;&emsp;</p></div><br/>";
		document.body.innerHTML += "<div><p style='font-size: 16px;padding-left: 200px;font-family:Lucida Sans;'>Pin Number 03 &emsp;&emsp; "+pin_3+" &emsp;&emsp;&emsp;&emsp;</p></div><br/>";
		document.body.innerHTML += "<div><p style='font-size: 16px;padding-left: 200px;font-family:Lucida Sans;'>Pin Number 04 &emsp;&emsp; "+pin_4+" &emsp;&emsp;&emsp;&emsp;</p></div><br/>";
		document.body.innerHTML += "<div><p style='font-size: 16px;padding-left: 200px;font-family:Lucida Sans;'>Pin Number 05 &emsp;&emsp; "+pin_5+" &emsp;&emsp;&emsp;&emsp;</p></div><br/>";
		document.body.innerHTML += "<div><p style='font-size: 16px;padding-left: 200px;font-family:Lucida Sans;'>Auto Generated Random No &emsp;&emsp; "+rand_no+" &emsp;&emsp;&emsp;&emsp;</p></div><br/>";
		//document.body.innerHTML += '<div><p style="font-size: 12px;padding-left: 530px;">AGM(IT)/System Analyst/IT Assistant</p></div>';
		window.print();
		document.body.innerHTML = getFullContent;
		
		var draw_time=moment(new Date()).format("YYYY-MM-DD HH:mm:ss A");				
				 
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
				eng_count=sumDigits(sum);
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
	  
	  <form action="add_panel.php" method = "POST" enctype = "multipart/form-data">
        <div class="col-md-6" id="winner_report" name="winner_report">
          
            <div class="form-group">
                <label for="member_name">Pin Number 01</label>
                <input type="text" class="form-control" name="pin_1" id="pin_1" placeholder="Pin Number 01" type="text" onkeypress="return /[0-9]/i.test(event.key)" maxlength="20">
            </div>
			
            <div class="form-group">
                <label for="nic">Pin Number 02</label>
                <input type="text" class="form-control" name="pin_2" id="pin_2" placeholder="Pin Number 02" type="text" onkeypress="return /[0-9]/i.test(event.key)" maxlength="20">
            </div>            
            
			<div class="form-group">
              <label for="hm_tp">Pin Number 03</label>
                 <input class="form-control" name="pin_3" id="pin_3" placeholder="Pin Number 03"  type="text" onkeypress="return /[0-9]/i.test(event.key)" maxlength="20">
            </div>
			
			<div class="form-group">
              <label for="email">Pin Number 04</label>
                 <input type="email" required class="form-control" name="pin_4" id="pin_4" placeholder="Pin Number 04" type="text" onkeypress="return /[0-9]/i.test(event.key)" maxlength="20">
            </div>
			<div class="form-group">
                <label for="designation">Pin Number 05</label>
                <input type="text" class="form-control" name ="pin_5" id ="pin_5"  placeholder="Pin Number 05" type="text" onkeypress="return /[0-9]/i.test(event.key)" maxlength="20">
            </div>
			
			<div class="form-group">
                <label for="designation">Computer Generated Random Number</label>
                <input type="text" class="form-control" name ="auto_generated_pin" id="auto_generated_pin"  placeholder="Computer Generated Random Number" type="text" onkeypress="return /[0-9]/i.test(event.key)" maxlength="20">
            </div>
			
            <div class="form-group clearfix">
              <button type="button" name="add"  class="btn btn-primary" style="width:100px;" onclick="initiateDraw()">Initiate Draw</button>
				<button type="button" name="cancel" class="btn btn-primary" style="width:100px;">Cancel</button>
            </div>

        </div>
		
        </form>
      
    </div>
  </div>

<?php include_once('layouts/footer.php'); ?>
