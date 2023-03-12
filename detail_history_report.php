<?php error_reporting (E_ALL ^ E_NOTICE); ?> 
<?php
$page_title = 'Mahadhana Sampatha Draw History Report';
$results = '';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);
  // page_require_level(4);
?>

<?php
  if(isset($_POST['submit'])){
    //$req_dates = array('start-date','end-date');
    //validate_fields($req_dates);

    if(empty($errors)):
     
	 $draw_no   = remove_junk($db->escape($_POST['draw_no']));	 
	 $draw_date   = remove_junk($db->escape($_POST['draw_date']));	
	 $mode =remove_junk($db->escape($_POST['mode']));
	 	 
				
	if($draw_no != "" && $draw_date == "" ):	
	echo $draw_no;
     $results      = findDrawBydraw_no($draw_no,$mode);	
	 endif;	 
	 
	 if($draw_no == "" &&  $draw_date != "" ):	
     $results      = findDrawByDate($draw_date,$mode);	
	 endif; 
	 
	 
	 if($draw_no != "" && $draw_date != "" ):	
     $results      = findDrawByDrawNoDate($draw_no,$draw_date,$mode);	
	 endif;
	 
	 if($draw_no == "" &&  $draw_date == "" ):	
     $results      = findDrawByMode($mode);	
	 endif;
	  
	   
    else:
      $session->msg("d", $errors);
      redirect('draw_history_search.php', false);
    endif;

  } else {
    $session->msg("d", "Select dates");
    redirect('draw_history_search.php', false);
  }
?>
<!doctype html>
<html lang="en-US">
 <head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>Default Page Title</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"/>
   <style>
   @media print {
     html,body{
        font-size: 9.5pt;
        margin: 0;
        padding: 0;
     }.page-break {
       page-break-before:always;
       width: auto;
       margin: auto;
      }
    }
    .page-break{
      width: 980px;
      margin: 0 auto;
    }
     .sale-head{
       margin: 100px 0;
       text-align: center;
     }.sale-head h1,.sale-head strong{
       padding: 10px 20px;
       display: block;
     }.sale-head h1{
       margin: 0;
       border-bottom: 1px solid #212121;
     }.table>thead:first-child>tr:first-child>th{
       border-top: 1px solid #000;
      }
      table thead tr th {
       text-align: center;
       border: 1px solid #000;
     }table tbody tr td{
       vertical-align: middle;
	    padding: 10px;
     }.sale-head,table.table thead tr th,table tbody tr td,table tfoot tr td{
       border: 1px solid #212121;       
     }.sale-head h1,table thead tr th,table tfoot tr td{
       background-color: #f8f8f8;
     }tfoot{
       color:#000;
       text-transform: uppercase;
       font-weight: 500;
     }
	 .center {
		  margin-left: 20px;
		  margin-right: 10px;
	}
	
   </style>
</head>
<body>
  
  <?php if($results): ?>
    <div>
       <div style="text-align:center;padding-bottom:50px;">
           <h1 >Mahadhana Sampatha Draw</h1>           
       </div>
	   
	   <div style="overflow-y:scroll;" >
      <table class="center" style="width:80%;align:center; padding-left:5px;padding-right:5px;align:center;" id="panel_table" name="panel_table">
        <thead>
          <tr>
			  <th class="text-center" >#</th>
                <th> Draw Date & Time</th>
                <th> Draw No </th>
                <th > Mode</th>
                <th > Winning Numbers </th>
                <th> English Letter </th>                
                <th> Actions </th>	  
			  
          </tr>
        </thead>
		
        <tbody>
		<?php $count=1 ?>
		<?php foreach($results as $result): ?>
		  <tr>	 
			  <td class="text-center"><?php echo count_id();?></td>                
                <td> <?php echo remove_junk($result['draw_date']); ?></td>
                <td> <?php echo remove_junk($result['draw_no']); ?></td>
                <td> <?php echo remove_junk($result['mode']); ?></td>
                <td> <?php echo remove_junk($result['winning_nos']); ?></td>
				<td> <?php echo remove_junk($result['english_letter']); ?></td>
                <td>
                  <div class="btn-group">
                    <a href="generate_pdf_report.php?id=<?php echo (int)$result['id'];?>" class="btn btn-info btn-xs"  title="View" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>                    
                  </div>
                </td>				  
          </tr>
        <?php endforeach; ?>
        </tbody>        
      </table>    
	<div style="padding-top:20px; padding-left:80px; padding-bottom:40px;">
	
	</div>
	</div>
	</div>
  <?php
    else:
	
        $session->msg("d", "Sorry no records has been found. ");		 
        redirect('draw_history_search.php', false);
     endif;
  ?>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
<script type="text/javascript" src="libs/js/moment.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
<script>  
 
 $(document).ready(function(){  
      if ( window.history.replaceState ) {
		  window.history.replaceState( null, null, window.location.href );
		}
 }); 
function ExportToExcel(type, fn, dl) {
       var elt = document.getElementById('panel_table');
       var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
       return dl ?
         XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
         XLSX.writeFile(wb, fn || ('PANEL_LIST.' + (type || 'xlsx')));
    }
	

 </script>  
</html>
<?php if(isset($db)) { $db->db_disconnect(); } ?>
