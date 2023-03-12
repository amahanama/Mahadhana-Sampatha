<?php
  $page_title = 'All Product';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(2);
   $panels="";
 
?>

<?php

		if(isset($_POST['button1'])) {
	        $nic   = remove_junk($db->escape($_POST['sug_input']));	 
            $panels = findByNIC($nic);
        }else{
			 $panels = join_panel_table();
			
		}
 /* if(isset($_POST['submit'])){
    //$req_dates = array('start-date','end-date');
    //validate_fields($req_dates);

    if(empty($errors)):
     $nic   = remove_junk($db->escape($_POST['sug_input']));	 
	  
		
	if($nic != "" ):	
     $panels = findByNIC($nic);
	 endif;
 
	else:
     $panels = join_panel_table();
    endif;

  } else {
    $session->msg("d", "Select NIC");
    redirect('add_panel.php', false);
  }*/
?>


<?php include_once('layouts/header.php'); ?>

<div class="row">
  <div class="col-md-6">
    <?php echo display_msg($msg); ?>
    <form method="post" action="ajax.php" autocomplete="off" id="sug-form">
        <div class="form-group">
          <div class="input-group">
            
            <input type="text" id="sug_input" class="form-control" name="sug_input"  placeholder="Search by NIC">
			<span class="input-group-btn">
              <button type="submit" class="btn btn-primary" value="button1">Find It</button>
			   
            </span>
         </div>
         <div id="result" class="list-group"></div>
        </div>
    </form>
  </div>
</div>
  <div class="row">
     <div class="col-md-12">
       <?php echo display_msg($msg); ?>
     </div>
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading clearfix">
         <div class="pull-right">
           <a href="add_product.php" class="btn btn-primary">Add New</a>
         </div>
        </div>
        <div class="panel-body">
		<div style="height:300px;overflow-x:auto;">
          <table class="table table-bordered" style="width: 100%;">
            <thead>
              <tr>
                <th class="text-center" >#</th>
                <th> Name</th>
                <th> Designation </th>
                <th > NIC </th>
                <th > Work Place </th>
                <th> Email </th>
                <th > Photo </th>
                <th > Status </th>
                <th> Actions </th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($panels as $panel):?>
              <tr>
                <td class="text-center"><?php echo count_id();?></td>                
                <td> <?php echo remove_junk($panel['member_name']); ?></td>
                <td> <?php echo remove_junk($panel['designation']); ?></td>
                <td> <?php echo remove_junk($panel['nic']); ?></td>
                <td> <?php echo remove_junk($panel['work_place']); ?></td>
                <td> <?php echo remove_junk($panel['email']); ?></td>
				<td>
                  <?php if($panel['photo'] === '0'): ?>
                    <img class="img-avatar img-circle" src="uploads/products/no_image.png" alt="">
                  <?php else: ?>
                  <img class="img-avatar img-circle" src="uploads/products/<?php echo $panel['image']; ?>" alt="">
                <?php endif; ?>
                </td>
                <td> <?php echo read_date($panel['status']); ?></td>
                <td>
                  <div class="btn-group">
                    <a href="edit_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-info btn-xs"  title="Edit" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <a href="delete_product.php?id=<?php echo (int)$product['id'];?>" class="btn btn-danger btn-xs"  title="Delete" data-toggle="tooltip">
                      <span class="glyphicon glyphicon-trash"></span>
                    </a>
                  </div>
                </td>
              </tr>
             <?php endforeach; ?>
            </tbody>
          </tabel>
		  </div>
        </div>
      </div>
    </div>
  </div>
  <?php include_once('layouts/footer.php'); ?>
