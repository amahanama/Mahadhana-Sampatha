<?php
require_once('includes/load.php');   
include_once('fpdf/fpdf.php');
	class ConductPDF extends FPDF {
		function vcell($c_width,$c_height,$x_axis,$text){
			$w_w=$c_height/3;
			$w_w_1=$w_w+2;
			$w_w1=$w_w+$w_w+$w_w+3;
			$len=strlen($text);// check the length of the cell and splits the text into 7 character each and saves in a array 

			$lengthToSplit = 25;
			if($len>$lengthToSplit){
				$w_text=str_split($text,$lengthToSplit);
				$this->SetX($x_axis);
				$this->Cell($c_width,$w_w_1,$w_text[0],'','','');
				if(isset($w_text[1])) {
				$this->SetX($x_axis);
				$this->Cell($c_width,$w_w1,$w_text[1],'','','');
				}
				$this->SetX($x_axis);
				$this->Cell($c_width,$c_height,'','LTRB',0,'L',0);
			}else{
				$this->SetX($x_axis);
				$this->Cell($c_width,$c_height,$text,'LTRB',0,'L',0);
			}
		}
		
		function header_val($c_width,$c_height,$x_axis,$text){
			$w_w=$c_height/3;
			$w_w_1=$w_w+2;
			$w_w1=$w_w+$w_w+$w_w+3;
			$len=strlen($text);// check the length of the cell and splits the text into 7 character each and saves in a array 	
		    
			$this->SetX($x_axis);
			$this->Cell($c_width,$c_height,$text,'LTRB',0,'C',0);			
		}
	 }
	 
	

/*********************************/	
	$draw = find_by_id('draw',(int)$_GET['id']);
  $draw_date =$draw['draw_date'];
  $draw_no =$draw['draw_no'];
  $mode =$draw['mode'];
  $operator =$draw['operator'];
  $panel_1 =$draw['panel_1'];
  $panel_2 =$draw['panel_2'];
  $panel_3 =$draw['panel_3'];
  $panel_4 =$draw['panel_4'];
  $panel_5 =$draw['panel_5'];
  
  $pin_1 =$draw['pin_1'];
  $pin_2 =$draw['pin_2'];
  $pin_3 =$draw['pin_3'];
  $pin_4 =$draw['pin_4'];
  $pin_5 =$draw['pin_5'];  
  
  $auto_generated_pin =$draw['auto_generated_pin'];
  $winning_nos =$draw['winning_nos'];  
  $str_arr = explode (",", $winning_nos); 
  $english_letter =$draw['english_letter'];
  $seed =$draw['seed'];
  
  $pane1_member_1 = find_by_id('panel_member',(int)$panel_1);//name,nic,designation
  $pane1_member_2 = find_by_id('panel_member',(int)$panel_2);
  $pane1_member_3 = find_by_id('panel_member',(int)$panel_3);
  $pane1_member_4 = find_by_id('panel_member',(int)$panel_4);
  $pane1_member_5 = find_by_id('panel_member',(int)$panel_5);
	 
	 
	
	
 //$pdf = new FPDF();
 $pdf = new ConductPDF();
 $pdf->AddPage();
 
 $pdf->Cell(80);  	
 $pdf->SetFont('Times','B',8);
 $pdf->Cell(20,8,'Development Lotteries Board');
 $pdf->Ln();
 $pdf->SetFont('Times','B',10);
 $pdf->Cell(30,10,'Mahadhana Sampatha - '.$draw['mode'] ." Draw  NO - ".$draw['draw_no']);
 $pdf->Ln();
 $pdf->Line(20, 25, 210-20, 25); 
 $pdf->SetFont('Times','B',16);
 $pdf->Cell(70); 
 $pdf->Cell(30,15,'Mahadhana Sampatha');
 $pdf->Ln(10);
 $pdf->SetFont('Times','',12);
 $pdf->Cell(55); 
 $pdf->Cell(30,22,'Draw No - '.$draw['draw_no']. ' & Date '.$draw['draw_date']);
 $pdf->Ln(10);
 $pdf->SetFont('Times','B',16);
 $pdf->Cell(75); 
 $pdf->Cell(50,23,'Winning Numbers');
 $pdf->Ln(20);
 $pdf->Cell(60); 
 $pdf->SetFont('Times','',14);
 $pdf->Cell(10,15,$str_arr[0],1,0,'C');
 $pdf->Cell(5,15,'',0);
 $pdf->Cell(10,15,$str_arr[1],1,0,'C');
 $pdf->Cell(5,15,'',0);
 $pdf->Cell(10,15,$str_arr[2],1,0,'C');
 $pdf->Cell(5,15,'',0);
 $pdf->Cell(10,15,$str_arr[3],1,0,'C');
 $pdf->Cell(5,15,'',0);
 $pdf->Cell(10,15,$str_arr[4],1,0,'C');
 $pdf->Cell(5,15,'',0);
 $pdf->Cell(10,15,$str_arr[5],1,0,'C');
 //$pdf->SetFont('Times','B',20);
 //$pdf->Cell(30,110,$draw['winning_nos']);
 $pdf->Ln(10);
 $pdf->SetFont('Times','B',16);
 $pdf->Cell(78);
 $pdf->Cell(30,30,'English Letter');
 $pdf->SetFont('Times','',14);
 $pdf->Ln(25);
 $pdf->Cell(88);
 $pdf->Cell(12,15,$draw['english_letter'],1,0,'C');
 //$pdf->Cell(30,50,$draw['english_letter']);
 $pdf->Ln(10);
 $pdf->Cell(85);
 $pdf->SetFont('Times','B',12);
 $pdf->Cell(30,32,'Draw Seed');
 $pdf->Ln(10);
 $pdf->SetFont('Times','B',8);
 $pdf->Cell(30,34,$seed);
 $pdf->Ln(25);

$pdf->SetFont('Times','B',10);
/*********########################################################################################**/
/*$width_cell=array(72,23,57,40);
$pdf->SetFillColor(190,200,200); // Background color of header 

// Header starts /// 
$pdf->Cell($width_cell[0],12,'Name',1,0,'C',true); // First header column 
$pdf->Cell($width_cell[1],12,'NIC',1,0,'C',true); // Second header column
$pdf->Cell($width_cell[2],12,'Designation',1,0,'C',true); // Third header column 
$pdf->Cell($width_cell[3],12,'PIN NO',1,1,'C',true); // Fourth header column

//// header is over ///////

$pdf->SetFont('Times','',10);
// First row of data 
$pdf->Cell($width_cell[0],10,$pane1_member_1['member_name'],1,0,'L',false); // First column of row 1 
$pdf->Cell($width_cell[1],10,$pane1_member_1['nic'],1,0,'L',false); // Second column of row 1 
$pdf->Cell($width_cell[2],10,$pane1_member_1['designation'],1,0,'L',false); // Third column of row 1 
$pdf->Cell($width_cell[3],10,$pin_1,1,1,'L',false); // Fourth column of row 1 

//  First row of data is over 
//  Second row of data 
$pdf->Cell($width_cell[0],10,$pane1_member_2['member_name'],1,0,'L',false); // First column of row 2 
$pdf->Cell($width_cell[1],10,$pane1_member_2['nic'],1,0,'L',false); // Second column of row 2
$pdf->Cell($width_cell[2],10,$pane1_member_2['designation'],1,0,'L',false); // Third column of row 2
$pdf->Cell($width_cell[3],10,$pin_2,1,1,'L',false); // Fourth column of row 2 

//   Sedond row is over 
//  Third row of data
$pdf->Cell($width_cell[0],10,$pane1_member_3['member_name'],1,0,'L',false); // First column of row 3
$pdf->Cell($width_cell[1],10,$pane1_member_3['nic'],1,0,'L',false); // Second column of row 3
$pdf->Cell($width_cell[2],10,$pane1_member_3['designation'],1,0,'L',false); // Third column of row 3
$pdf->Cell($width_cell[3],10,$pin_3,1,1,'L',false); // fourth column of row 3

$pdf->Cell($width_cell[0],10,$pane1_member_4['member_name'],1,0,'L',false); // First column of row 3
$pdf->Cell($width_cell[1],10,$pane1_member_4['nic'],1,0,'L',false); // Second column of row 3
$pdf->Cell($width_cell[2],10,$pane1_member_4['designation'],1,0,'L',false); // Third column of row 3
$pdf->Cell($width_cell[3],10,$pin_4,1,1,'L',false); // fourth column of row 3

$pdf->Cell($width_cell[0],10,$pane1_member_5['member_name'],1,0,'L',false); // First column of row 3
$pdf->Cell($width_cell[1],10,$pane1_member_5['nic'],1,0,'L',false); // Second column of row 3
$pdf->Cell($width_cell[2],10,$pane1_member_5['designation'],1,0,'L',false); // Third column of row 3
$pdf->Cell($width_cell[3],10,$pin_5,1,1,'L',false); // fourth column of row 3
$pdf->Ln(10);
*/

$x_axis=$pdf->getx();
$c_width=30;// cell width 
$c_height=15;// cell height
$text="aim success ";// content 
$pdf->SetFont('Times','',10);
$pdf->header_val(5,$c_height,$x_axis,'#'); 
$x_axis=$pdf->getx();
$pdf->header_val(45,$c_height,$x_axis,'Name');
$x_axis=$pdf->getx();
$pdf->header_val(20,$c_height,$x_axis,'NIC');
$x_axis=$pdf->getx();
$pdf->header_val(42,$c_height,$x_axis,'Disignation');
$x_axis=$pdf->getx();
$pdf->header_val(40,$c_height,$x_axis,'PIN');
$x_axis=$pdf->getx();
$pdf->header_val(40,$c_height,$x_axis,'Signature');
$pdf->Ln();

$x_axis=$pdf->getx();
$pdf->vcell(5,$c_height,$x_axis,'1');// pass all values inside the cell 
$x_axis=$pdf->getx();// now get current pdf x axis value
$pdf->vcell(45,$c_height,$x_axis,$pane1_member_1['member_name']);// pass all values inside the cell 
$x_axis=$pdf->getx();// now get current pdf x axis value
$pdf->vcell(20,$c_height,$x_axis,$pane1_member_1['nic']);
$x_axis=$pdf->getx();
$pdf->vcell(42,$c_height,$x_axis,$pane1_member_1['designation']);
$x_axis=$pdf->getx();
$pdf->vcell(40,$c_height,$x_axis,$pin_1);
$x_axis=$pdf->getx();
$pdf->vcell(40,$c_height,$x_axis,'');
$pdf->Ln();

$x_axis=$pdf->getx();
$pdf->vcell(5,$c_height,$x_axis,'2');// pass all values inside the cell 
$x_axis=$pdf->getx();// now get current pdf x axis value
$pdf->vcell(45,$c_height,$x_axis,$pane1_member_2['member_name']);// pass all values inside the cell 
$x_axis=$pdf->getx();// now get current pdf x axis value
$pdf->vcell(20,$c_height,$x_axis,$pane1_member_2['nic']);
$x_axis=$pdf->getx();
$pdf->vcell(42,$c_height,$x_axis,$pane1_member_2['designation']);
$x_axis=$pdf->getx();
$pdf->vcell(40,$c_height,$x_axis,$pin_2);
$x_axis=$pdf->getx();
$pdf->vcell(40,$c_height,$x_axis,'');
$pdf->Ln();

$x_axis=$pdf->getx();
$pdf->vcell(5,$c_height,$x_axis,'3');// pass all values inside the cell 
$x_axis=$pdf->getx();// now get current pdf x axis value
$pdf->vcell(45,$c_height,$x_axis,$pane1_member_3['member_name']);// pass all values inside the cell 
$x_axis=$pdf->getx();// now get current pdf x axis value
$pdf->vcell(20,$c_height,$x_axis,$pane1_member_3['nic']);
$x_axis=$pdf->getx();
$pdf->vcell(42,$c_height,$x_axis,$pane1_member_3['designation']);
$x_axis=$pdf->getx();
$pdf->vcell(40,$c_height,$x_axis,$pin_3);
$x_axis=$pdf->getx();
$pdf->vcell(40,$c_height,$x_axis,'');
$pdf->Ln();

$x_axis=$pdf->getx();
$pdf->vcell(5,$c_height,$x_axis,'4');// pass all values inside the cell 
$x_axis=$pdf->getx();// now get current pdf x axis value
$pdf->vcell(45,$c_height,$x_axis,$pane1_member_4['member_name']);// pass all values inside the cell 
$x_axis=$pdf->getx();// now get current pdf x axis value
$pdf->vcell(20,$c_height,$x_axis,$pane1_member_4['nic']);
$x_axis=$pdf->getx();
$pdf->vcell(42,$c_height,$x_axis,$pane1_member_4['designation']);
$x_axis=$pdf->getx();
$pdf->vcell(40,$c_height,$x_axis,$pin_4);
$x_axis=$pdf->getx();
$pdf->vcell(40,$c_height,$x_axis,'');
$pdf->Ln();

$x_axis=$pdf->getx();
$pdf->vcell(5,$c_height,$x_axis,'5');// pass all values inside the cell 
$x_axis=$pdf->getx();// now get current pdf x axis value
$pdf->vcell(45,$c_height,$x_axis,$pane1_member_5['member_name']);// pass all values inside the cell 
$x_axis=$pdf->getx();// now get current pdf x axis value
$pdf->vcell(20,$c_height,$x_axis,$pane1_member_5['nic']);
$x_axis=$pdf->getx();
$pdf->vcell(42,$c_height,$x_axis,$pane1_member_5['designation']);
$x_axis=$pdf->getx();
$pdf->vcell(40,$c_height,$x_axis,$pin_5);
$x_axis=$pdf->getx();
$pdf->vcell(40,$c_height,$x_axis,'');
$pdf->Ln();


/*******###########################################################################*************/

 //$pdf->Cell(10);
 $pdf->Cell(30,30,'Computer Generated Random Number         '.$draw['auto_generated_pin'].'                   .............................................................................');
 $pdf->SetFont('Times','',10);
 $pdf->Ln(5);
 $pdf->Cell(120);
 $pdf->Cell(30,30,'AGM(IT)/System Analyst/IT Assistant');
 $pdf->SetFont('Times','',14);
 $pdf->Ln(5);
 

 $pdf->Output();
	 /*******************************************************/
$pdf = new ConductPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',16);
$pdf->Ln();

$x_axis=$pdf->getx();
$c_width=50;// cell width 
$c_height=15;// cell height
$text="aim success ";// content

$pdf->vcell($c_width,$c_height,$x_axis,'Name');// pass all values inside the cell 
$x_axis=$pdf->getx();// now get current pdf x axis value
$pdf->vcell($c_width,$c_height,$x_axis,'NIC');
$x_axis=$pdf->getx();
$pdf->vcell($c_width,$c_height,$x_axis,'NIC');
$x_axis=$pdf->getx();
$pdf->vcell($c_width,$c_height,$x_axis,'Designation');
$x_axis=$pdf->getx();
$pdf->vcell($c_width,$c_height,$x_axis,'PIN');
$pdf->Ln();

$x_axis=$pdf->getx();
$c_width=50;
$c_height=15;
$text="aim success ";
$pdf->vcell($c_width,$c_height,$x_axis,'Hi4ccccccccccccccccccc');
$x_axis=$pdf->getx();
$pdf->vcell($c_width,$c_height,$x_axis,'Hi5(xtra)aaaaaaaaaaaaaaaaaaaa');
$x_axis=$pdf->getx();
$pdf->vcell($c_width,$c_height,$x_axis,'Hi5');
$pdf->Ln();
$x_axis=$pdf->getx();
$c_width=20;
$c_height=12;
$text="All the best";
$pdf->vcell($c_width,$c_height,$x_axis,'Haizzzzzzzzzzzzzzzzzzzzz');
$x_axis=$pdf->getx();
$pdf->vcell($c_width,$c_height,$x_axis,'VICKY');
$x_axis=$pdf->getx();
$pdf->vcell($c_width,$c_height,$x_axis,$text);
$pdf->Ln();
$x_axis=$pdf->getx();
$c_width=20;
$c_height=6;
$text="Good";
$pdf->vcell($c_width,$c_height,$x_axis,'Hai');
$x_axis=$pdf->getx();
$pdf->vcell($c_width,$c_height,$x_axis,'vignesh');
$x_axis=$pdf->getx();
$pdf->vcell($c_width,$c_height,$x_axis,$text);
$pdf->Output();
?>


