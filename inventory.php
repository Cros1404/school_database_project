<?php 
	include "menu.php" ;
	include "connection.php";
	session_start();
	if ( isset( $_POST['btnUse']) )
		$db -> exec("UPDATE item SET used=true WHERE ID=".$_POST['id']);
	else if ( isset( $_POST['btnUnuse']) )
		$db -> exec("UPDATE item SET used=false WHERE ID=".$_POST['id']);
	else if ( isset( $_POST['btnRemoveItemsMode']))
		$_SESSION['RemoveMode'] = true;
	else if ( isset( $_POST['btnExitRemoveItemsMode']))
		unset( $_SESSION['RemoveMode'] );
	else if ( isset( $_POST['btnRemove']) )
		$db -> exec("DELETE FROM item WHERE ID=".$_POST['id']);
	else if ( isset( $_POST['btnAddItem'] ) ){
		$name = filter_var( $_POST['name'], FILTER_SANITIZE_STRING );
		$add = $db -> prepare( "INSERT INTO item(name, location) VALUES (:name, :location)" );
		$add -> bindParam(':name', $name);
		$add -> bindParam(':location', $_GET['location']);
		$add -> execute();
	}


	echo '<div class="page-header"><h1>'.htmlspecialchars( $_GET['location'] ).'</h1></div> '; 
?>
<p> Add items with "Add Item" button. Press "Yes" and "No" in "In Use" column to toggle whether item is in use or not. To remove items enable "Remove Items Mode".</p>
  <table class="table table-hover table-striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Item Name</th>
        <th>In Use</th>
<?php 
	if ( isset( $_SESSION['RemoveMode'] ) )
		echo '<th>Remove Item</th>';
?>
      </tr>
    </thead>
    <tbody>
<?php
	$stmt = $db -> prepare("SELECT name, ID, used FROM item WHERE location=:location");
	$stmt -> bindParam(':location', $_GET['location']);
	$stmt -> execute();
	foreach ( $stmt as $x ) {
		echo '<tr>
		        <td>'.$x['ID'].'</td>
		        <td>'.$x['name'].'</td>
		        <form action="'.htmlspecialchars( $_SERVER['REQUEST_URI'] ).'" method=post>
		        	<input type="hidden" value="'.$x['ID'].'" name="id">
		        	<td>';

      	if ( $x['used'] )
      		echo '<input type="submit" name="btnUnuse" value="Yes" class="btn btn-danger btn-xs">';
      	else
      		echo '<input type="submit" name="btnUse" value="No" class="btn btn-success btn-xs">';

      	echo '		</td>';
      	if ( isset( $_SESSION['RemoveMode'] ) )
      		echo '  <td>
      					<input type="submit" name="btnRemove" value="Remove" class="btn btn-default btn-xs">
      				</td>';
      	echo '	</form>
      		  </tr>';
	}
?>
	</tbody>
  </table>
  <button type="button" class="btn btn-default" data-toggle="modal" data-target="#item">Add Item</button>
  <br><br>
  <form action="<?php echo htmlspecialchars( $_SERVER['REQUEST_URI'] )?>" method="post">
<?php
	if ( isset( $_SESSION['RemoveMode'] ) )
		echo '<button type="submit" class="btn btn-primary" name="btnExitRemoveItemsMode">Exit Remove Items Mode</button>';
	else
		echo '<button type="submit" class="btn btn-primary" name="btnRemoveItemsMode">Enable Remove Items Mode</button>';
?>
	<br><br>
	<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#location">Remove This Location</button>
  </form>
  <br><br>
</div>


<!-- Modal -->
<div id="item" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Item</h4>
      </div>
      <div class="modal-body">
	    <form action="<?php echo htmlspecialchars( $_SERVER['REQUEST_URI'] )?>" method="post">
		      <div class="form-group">
				  <label for="name">Item Name:</label>
				  <input type="text" class="form-control" id="name" name="name" required>
			  </div>
			  <button type="submit" class="btn btn-default" name="btnAddItem">Submit</button>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="location" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Item</h4>
      </div>
      <div class="modal-body">
	    <form action="index.php" method="post">
		      <div class="form-group">
				  <label for="location">Are you sure you want to remove this location?</label>
				  <input type="hidden" class="form-control" id="location" name="location" value="<?php echo htmlspecialchars($_GET['location']) ?>">
			  </div>
			  <button type="submit" class="btn btn-default" name="btnRemove">Yes</button>
			  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<?php include "footer.php"; ?>