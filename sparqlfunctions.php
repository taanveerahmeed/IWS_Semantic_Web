<?php
	include_once('semsol/ARC2.php');  
	$dbpconfig = array("remote_store_endpoint" => "http://localhost:3030/ds/query",); //connecting to fuseki server
	$store = ARC2::getRemoteStore($dbpconfig); 
	if ($errs = $store->getErrors()) {
	 echo "<h1>getRemoteSotre error<h1>" ;
	}
	$queryPrefix = '
						PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
						PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
						PREFIX re: <http://www.w3.org/2000/10/swap/reason#>
						prefix owl: <http://localhost:3030/University#>
					';
	/*
	function to get all the departments in the existing system
	*/	
	function getAllDepartment(){
		$query = $GLOBALS['queryPrefix'].
			  '		
				SELECT DISTINCT  ?itemName
				WHERE 
				{ 		  
					?item rdf:type ?type.
					?type rdfs:label "Department".
					?item owl:Name ?itemName.
				  				
				}
			 ';
		global $store;
		$rows = $store->query($query, 'rows'); 
		return $rows;
	}
	/*
	function to get all the teachers' posts like as lecturer, assistant professor etc.
	*/
	function getAllDesignations (){
		$query = $GLOBALS['queryPrefix'].
			  '		
				SELECT DISTINCT  ?designationName
				WHERE 
				{ 		  
					?person rdf:type ?type.
					?type rdfs:label ?designationName.
					?type rdfs:subClassOf ?superClass.
					?superClass rdfs:label "Teacher".
				  
				}
			 ';
		global $store;
		$rows = $store->query($query, 'rows'); 
		return $rows;		
	}
	/*
	function to get all the program types like as bachelor program,masters program.
	*/
	function getAllPrograms(){
		$query = $GLOBALS['queryPrefix'].
			  '		
				
				SELECT DISTINCT  ?typeName
				WHERE 
				{ 		  
					?person rdf:type ?type.
					?type rdfs:label ?typeName.
					?type rdfs:subClassOf ?superClass.
					?superClass rdfs:label "Student".
				  
				}

			 ';
		global $store;
		$rows = $store->query($query, 'rows'); 
		return $rows;	
		
	} 
	/*
	function to get all individuals 
	*/
	function getAllIndividuals(){
		
		$query = $GLOBALS['queryPrefix'].
			  '		
				
				SELECT  ?Name ?typeName
				WHERE 
				{
					?item owl:Name ?Name. 
					?item rdf:type ?type.
					?type rdfs:label ?typeName.
				}

			 ';
		global $store;
		$rows = $store->query($query, 'rows'); 
		return $rows;	
	}
	/*
	function to get the type of the searched item
	*/
	function getTypeOfTheSearchedItem($item){
		
		$query = $GLOBALS['queryPrefix'].
			  '						
				SELECT $typeName
				WHERE
				{
				  ?item rdf:type ?type.
				  ?type rdfs:label ?typeName.
				  ?item owl:Name '.'"'.$item.'"'.
				'}';
		//echo '<script>alert('.$query.')</script>';
		global $store;
		$rows = $store->query($query, 'rows'); 
		return $rows;	
	}
	/*
	function to get individuals of same concept
	*/
	function getSameConceptIndividuals($concept){
		$query = $GLOBALS['queryPrefix'].
			  '						
				SELECT  ?Name 
				WHERE 
				{
					?item owl:Name ?Name. 
					?item rdf:type ?type.
					?type rdfs:label '.'"'.$concept.'".
					
					
				}
				';
		//echo '<script>alert('.$query.')</script>';
		global $store;
		$rows = $store->query($query, 'rows'); 
		return $rows;	
		
	}
	/*
	function to get the teachers of taking that course
	*/
	function getTeachers($course){
		$query = $GLOBALS['queryPrefix'].
			  '						
				SELECT  ?Name ?deptName
				WHERE 
				{
					?item owl:takes ?course. 
					?course owl:Name '.'"'.$course.'".
					?item owl:Name ?Name.
					?dept owl:offers ?course.
					?dept owl:Name ?deptName.
				}
				';
		//echo '<script>alert('.$query.')</script>';
		global $store;
		$rows = $store->query($query, 'rows'); 
		return $rows;	
		
	}
	function getProgramWiseCourse(&$program){
		$query = $GLOBALS['queryPrefix'].
			  '						
				SELECT DISTINCT ?courseName 
				WHERE 
				{ 		  
				   ?student rdf:type ?type.
				   ?type rdfs:label '.'"'.$program.'".
				   ?student owl:enrolls ?course.
				   ?course owl:Name ?courseName.
				}
				';
		global $store;
		$rows = $store->query($query, 'rows');
		return $rows;
	}
	/*function to get similar program courses*/
	function getSimilarCourses($courseName, &$program){
		$query = $GLOBALS['queryPrefix'].
			  '						
				SELECT DISTINCT ?program
				WHERE
				{
					?student owl:enrolls ?course.
					?course owl:Name '.'"'.$courseName.'".
					?student rdf:type ?type.
					?type rdfs:label ?program.
				}
				';
		//echo '<script>alert('.$query.')</script>';
		global $store;
		$rows = $store->query($query, 'rows');
		$program = $rows[0]['program'];
		//echo '<script>alert("'.$program.'")</script>';
		$query = $GLOBALS['queryPrefix'].
			  '						
				SELECT DISTINCT ?courseName 
				WHERE 
				{ 		  
				   ?student rdf:type ?type.
				   ?type rdfs:label '.'"'.$program.'".
				   ?student owl:enrolls ?course.
				   ?course owl:Name ?courseName.
				}
				';
		global $store;
		$rows = $store->query($query, 'rows');
		return $rows;
		
	}
	/*function to get courses departmentwise*/
	function getCoursesDeptWise ($dept){
		
		$query = $GLOBALS['queryPrefix'].
			  '						
				SELECT DISTINCT  ?courseName
				WHERE 
				{ 		  
				   ?department owl:offers ?course.
				   ?department owl:Name "'.$dept.'".
				   ?course owl:Name ?courseName.
				  
				}
				';
		//echo '<script>alert('.$query.')</script>';
		global $store;
		$rows = $store->query($query, 'rows'); 
		return $rows;
	}
	function getTeachersBasedOnDept($department){
		$query = $GLOBALS['queryPrefix'].
			  '						
				SELECT DISTINCT  ?teacherName
				WHERE { 		  
				   ?department owl:offers ?course.
				   ?department owl:Name "'.$department.'".
				   ?teacher owl:takes ?course.
				   ?teacher owl:Name ?teacherName.
				  
				}
				';
		//echo '<script>alert('.$query.')</script>';
		global $store;
		$rows = $store->query($query, 'rows');
		return $rows;
		
	}
	/*function to get other deparmental teachers*/
	function getDepartmentalTeachers ($nameOfTeacher,&$department){
		$query = $GLOBALS['queryPrefix'].
			  '						
				SELECT DISTINCT ?deptName
				WHERE
				{
					?teacher owl:takes ?course.
					?dept owl:offers ?course.
					?teacher owl:Name "'.$nameOfTeacher.'".
					?dept owl:Name ?deptName.
					
					
				}
				';
		echo '<script>alert("'.$query.'")</script>';
		global $store;
		$rows = $store->query($query, 'rows');
		$department = $rows[0]['deptName'];
		
		$query = $GLOBALS['queryPrefix'].
			  '						
				SELECT DISTINCT  ?teacherName
				WHERE { 		  
				   ?department owl:offers ?course.
				   ?department owl:Name "'.$department.'".
				   ?teacher owl:takes ?course.
				   ?teacher owl:Name ?teacherName.
				  
				}
				';
		//echo '<script>alert('.$query.')</script>';
		global $store;
		$rows = $store->query($query, 'rows');
		return $rows;
		
	}
	function getCourseOfAStudent($student){
		$query = $GLOBALS['queryPrefix'].
			  '						
				SELECT DISTINCT  ?courseName
				WHERE 
				{ 		  
				   ?student owl:enrolls ?course.		  
				   ?course owl:Name ?courseName.
				   ?student owl:Name "'.$student.'".
				  
				}
				';
		//echo '<script>alert('.$query.')</script>';
		global $store;
		$rows = $store->query($query, 'rows'); 
		return $rows;
		
	}
	function getTeacherCourses($teacher){
		$query = $GLOBALS['queryPrefix'].
			  '						
				SELECT DISTINCT  ?courseName
				WHERE { 		  
				   ?teacher owl:takes ?course.		  
				   ?teacher owl:Name "'.$teacher.'".
				   ?course owl:Name ?courseName.
				  
				}
				';
		//echo '<script>alert('.$query.')</script>';
		global $store;
		$rows = $store->query($query, 'rows'); 
		return $rows;
		
	}
?>