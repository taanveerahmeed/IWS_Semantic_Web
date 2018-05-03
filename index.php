<html>
  <body>
 
  <?php
  

  include_once('sparqlfunctions.php');  
  $rows = getAllPrograms();
  echo count($rows);
 
    if ($errs = $store->getErrors()) {
       echo "Query errors" ;
       print_r($errs);
    }
 
   
    echo "<table border='1'>
    <thead>
        <th>#</th>
        <th>Teacher </th>
        <th>Staff ID</th>   
		<th>Takes</th> 
    </thead>";
	
	
    /* loop for each returned row */
	$id = 0;
	echo '<pre>';
	print_r($rows);
	echo '<pre>';
	
	foreach ($rows as $row){
		print "<tr><td>".++$id. "</td>";
		
		/*echo "<td>".explode("#",$row['teacher'])[1]."</td>";
		echo "<td>".explode("#",$row['teaches'])[1]."</td> </tr>";*/
		echo "<td>".$row['typeName']."</td>";
		
		
	}
    /*foreach( $rows as $row ) { 
    print "<tr><td>".++$id. "</td>
    <td><a href='". explode($row['teacher'],"#")[1] . "'>" . 
    $row['teaches']."</a></td><td>" . 
    $row['M101']. "</td><td>" 
   ;
    }*/
    echo "</table>" ;

  ?>
  </body>
</html>
