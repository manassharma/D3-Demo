
 <?php 
	$con = mysqli_connect("localhost","root","","collapsibletree");
	$level2 = mysqli_query($con,"SELECT name FROM entities where type='requirement' and decomposition_id is NULL");
	$level3 = mysqli_query($con, "SELECT name FROM entities where type='requirement' and decomposition_id is NOT NULL");
	$element = mysqli_query($con, "SELECT name FROM entities where type='requirement' and id = ((select entity_id from decompositions where decompositions.entity_id = entities.id and entities.type='requirement'))");
	
	$rows = array();
	$rows2 = array();
	$rows3;
	
	$aux = array();
	
	while($r = mysqli_fetch_assoc($level2)) {
    $rows[] = $r;
}
	while($r2 = mysqli_fetch_assoc($level3)) {
		$rows2[] = $r2;
	}
	
	while($r3 = mysqli_fetch_assoc($element)) {
		$rows3[] = $r3;
	}
	
	for($i=0;$i<sizeof($rows);$i++) {
		if(!checkForSubNodes($rows[$i],$rows3)) {
			
			$aux[] = $rows[$i];
		}
		else {
			$builder = array(
			'name' => $rows3,
			'children' => $rows2
			);
			$aux[$i] = $builder;
		}
	}
	
	
	$jsobj = array(
			'name' => 'requirements',
			'children' => $aux,
		);
	
	$js = json_encode($jsobj);
   

	print $js;
	
	function checkForSubNodes($args,$rows3) {
		for($i=0;$i<sizeof($rows3);$i++) {
			if($args==$rows3[$i]){
				return true;
			}
		}
        return false;		
	}
	file_put_contents('flare.json',$js);
	?>
