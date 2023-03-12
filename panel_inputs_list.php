<?php
  $page_title = 'Draw Panel Allocation';
  require_once('includes/load.php');
  // Checkin What level user has permission to view this page
   page_require_level(4);
  $all_groups = find_latest_draw_allocation();
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
   <div class="col-md-12">
     <?php echo display_msg($msg); ?>
   </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
    <div class="panel-heading clearfix">
      <strong>
        <span class="glyphicon glyphicon-th"></span>
        <span>Draw Panel Allocation</span>
     </strong>
       <a href="assign_draw.php" class="btn btn-info pull-right btn-sm"> Panel Inputs</a>
    </div>
     <div class="panel-body">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th class="text-center" style="width: 100px;">Draw No</th>
            <th>Draw Date</th>
            <th class="text-center" style="width: 20%;">Mode</th>			 
            <th class="text-center" style="width: 15%;">Status</th>
            <th class="text-center" style="width: 100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($all_groups as $a_group): ?>
          <tr>
           <td class="text-center"><?php echo remove_junk(ucwords($a_group['draw_no']))?></td>
           <td><?php echo remove_junk(ucwords($a_group['draw_date']))?></td>
           <td class="text-center">
             <?php echo remove_junk(ucwords($a_group['mode']))?>
           </td>		   
           <td class="text-center">
           <?php if($a_group['status'] === 'Y'): ?>
            <span class="label label-success"><?php echo "Active"; ?></span>
          <?php else: ?>
            <span class="label label-danger"><?php echo "Deactive"; ?></span>
          <?php endif;?>
           </td>
           <td class="text-center">
             <div class="btn-group">
                <a href="add_panel_input_form.php?id=<?php echo (int)$a_group['id'];?>" class="btn btn-xs btn-warning" data-toggle="tooltip" title="Edit">
                  <i class="glyphicon glyphicon-pencil"></i>
               </a>                
                </div>
           </td>
          </tr>
        <?php endforeach;?>
       </tbody>
     </table>
     </div>
    </div>
  </div>
</div>
  <?php include_once('layouts/footer.php'); ?>
