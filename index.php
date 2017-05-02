<?php include "menu.php"; ?>
<script type="text/javascript">
	document.getElementById("locations").setAttribute("class", "active");
</script>
<?php include "connection.php"; ?>
<?php 
if ( isset( $_POST['btnCreate'] ) ) {
	$given_location = filter_var( $_POST['location'], FILTER_SANITIZE_STRING ) ;
	$add = $db -> prepare("INSERT INTO location (name) VALUES (:name)");
	$add -> bindParam(':name', $given_location);
	$add -> execute();
	if ( $add -> errorCode() == 23000 )
		echo '<p class="alert alert-warning"><strong>Error!</strong> The location already exists.</p>';
} else if ( isset( $_POST['btnRemove'] ) ){
  $remove = $db -> prepare("DELETE FROM location WHERE name=:name");
  $remove -> bindParam(':name', $_POST['location']);
  $remove -> execute();
}
?>

<div class="list-group">

<?php

$myquery = "SELECT name FROM location";
$data = $db -> query($myquery);
foreach ($data as $x)
{
    echo '<a href="inventory.php?location='.$x['name'].'" class="list-group-item"><h4>'.$x['name'].'</h4></a>';
} ?>
<?php /*if ( $_SESSION['teacher'] == true ){
echo '<button type="button" class="btn btn-default list-group-item" data-toggle="modal" data-target="#myModal"><h4>Create a new course</h4></button>' ;
}*/ ?>
  <button type="button" class="btn btn-default list-group-item" data-toggle="modal" data-target="#myModal"><h4>Add Location</h4></button>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Location</h4>
      </div>
      <div class="modal-body">
	    <form action="index.php" method="post">
		      <div class="form-group">
				  <label for="location">Location Name:</label>
				  <input type="text" class="form-control" id="location" name="location" required>
			  </div>
			  <button type="submit" class="btn btn-default" name="btnCreate">Submit</button>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<?php include "footer.php"; ?>