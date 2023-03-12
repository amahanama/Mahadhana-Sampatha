<?php
$page_title = 'Sale Report';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(3);   

?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel">
      <div class="panel-heading">
		<h3>Mahadhana Sampatha Digital Draw History</h3>
      </div>
      <div class="panel-body">
          <form class="clearfix" method="post" action="detail_history_report.php">           
			
			<div class="form-group">
			
			<div class="row">			
                 <div class="col-md-6">
				  <b>Draw No</b>
				  <input type="text" id="draw_no" class="form-control" name="draw_no"  placeholder="Enter Draw Number">
				</div>
				
				<div class="col-md-6">
				   <b>Date </b>
				  <!--<input type="text" id="draw_date" class="form-control" name="draw_date"  placeholder="Enter Draw Date">-->
				  <input type="text" style="text-align:left;size:small;" class="datepicker form-control" name="draw_date"  id="draw_date" placeholder="Draw Date" >
				</div>
			</div>
			<div class="row" style="padding-top:10px;">					
				<div class="col-md-6">	
					<label for="mode"><b>Mode</b></label>
					<select class="form-control" name="mode" id="mode">
						<option value="TEST">Test</option>
						<option value="LIVE">Live</option>
					</select>
				</div>
			</div>
				
            </div>
            <div class="form-group">			
                 <button type="submit" name="submit" class="btn btn-primary">Search</button>				 
            </div>
          </form>
      </div>
    </div>
  </div>
</div>
<?php include_once('layouts/footer.php'); ?>
