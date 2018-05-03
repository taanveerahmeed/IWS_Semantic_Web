<?php
	include_once('sparqlfunctions.php');
	$individual = getTypeOfTheSearchedItem($_REQUEST['search']);
	
	$strArea = '<div class="well"><span class="label label-success">';
	echo $strArea;
	
	if (count($individual)){	
		echo $individual[0]['typeName'];
		
		//if course
		$criteria = $individual[0]['typeName'];
		if ($criteria == "Course"){
			findTeachersOfCourse($_REQUEST['search']);
		}
		if ($criteria == "Lecturer" || $criteria == "Professor" || $criteria == "Assistant Professor" || $criteria == "Associate Professor" ){
			
			findTeacherTakingCourses($_REQUEST['search']);
			findTeachersOfSameDepartment($_REQUEST['search']);
			findTeachersWithSameRank($individual[0]['typeName'],$_REQUEST['search']);
		}
		if ($criteria == "Bachelor" || $criteria == "Masters"){
			findTheEnrolledCourses($_REQUEST['search']);
			findOtherStudentsOfSameConcept ($individual[0]['typeName'],$_REQUEST['search']);
		}
		if ($criteria == "Department"){
			
			findOfferedCourse($_REQUEST['search']);
			findTeachers($_REQUEST['search']);
		}
		
	}
	
	else{
		
		echo '</span></div>';
		if ($_REQUEST['search'] == "Bachelor" || $_REQUEST['search'] == "Masters"){
			findCourseBasedOnProgram($_REQUEST['search']);
			echo '<span style = "color:white"> Students of '.$_REQUEST['search'].' Program </span>';
		}
		$individuals = getSameConceptIndividuals($_REQUEST['search']);
		$test = count($individuals);
		echo "<ul class='list-group'>";
		foreach($individuals as $ind) {
			echo "<li class='list-group-item' onclick='triggerSearch(this)'>".$ind['Name'];
			 echo '</li>';
		}
		echo "</ul>";
		//echo '<span style = "color:white">'.'Concept'.$test.'</span>';
	}
	

	function findTeachersOfCourse($courseName){
		$result = getTeachers($courseName);
		if ($result){
			echo "Course of ".$result[0]['deptName']." Department";
		
			echo '</span></div>';
			echo '<span style = "color:white"> Teacher(s) Taking This Course </span>';
			echo "<ul class='list-group'>";
			foreach($result as $ind) {
				echo "<li class='list-group-item' onclick='triggerSearch(this)'>".$ind['Name'];
				 echo '</li>';
			}
			echo "</ul>";
			findSimilarCoursesBasedOnProgram($courseName);
			findSimilarCoursesBasedOnDept($result[0]['deptName'],$courseName);
			
		}
	}
	function findSimilarCoursesBasedOnDept ($dept,$courseName){
		$result = getCoursesDeptWise($dept);
		echo '<span style = "color:white"> Other  Courses with Same Department</span>';
			echo "<ul class='list-group'>";
			foreach($result as $ind) {
				if ($ind['courseName'] != $courseName){
					echo "<li class='list-group-item' onclick='triggerSearch(this)'>".$ind['courseName'];
				 echo '</li>';
				}
				
			}
			echo "</ul>";
	}
	function findSimilarCoursesBasedOnProgram ($courseName){
		$result = getSimilarCourses($courseName,$program);
		echo '<span style = "color:white"> Other  Courses with '.$program.' Program</span>';
			echo "<ul class='list-group'>";
			foreach($result as $ind) {
				if ($ind['courseName'] != $courseName){
					echo "<li class='list-group-item' onclick='triggerSearch(this)'>".$ind['courseName'];
				 echo '</li>';
				}
				
			}
			echo "</ul>";
	}
	function findTeachersOfSameDepartment($name){
		$result = getDepartmentalTeachers($name,$dept);
		echo '</span></div>';
		echo '<span style = "color:white"> Other Teachers from '.$dept.' Department</span>';
		echo "<ul class='list-group'>";
			foreach($result as $ind) {
				if ($ind['teacherName'] != $name){
					echo "<li class='list-group-item' onclick='triggerSearch(this)'>".$ind['teacherName'];
				 echo '</li>';
				}
				
			}
			echo "</ul>";
	}
	function findTeachersWithSameRank($rank, $name){
		echo '</span></div>';
		$individuals = getSameConceptIndividuals($rank);
		$test = count($individuals);
		echo '<span style = "color:white"> Other '.$rank.'(s) </span>';
		echo "<ul class='list-group'>";
		foreach($individuals as $ind) {
			if ($name != $ind['Name']){
				echo "<li class='list-group-item' onclick='triggerSearch(this)'>".$ind['Name'];
				echo '</li>';
			}
			
		}
		echo "</ul>";
	}
	function findTheEnrolledCourses($studentName){
		$result =getCourseOfAStudent ($studentName);
		echo '</span></div>';
		echo '<span style = "color:white"> Enrolled Courses </span>';
		echo "<ul class='list-group'>";
		foreach($result as $ind) {
			echo "<li class='list-group-item' onclick='triggerSearch(this)'>".$ind['courseName'];
			 echo '</li>';
		}
		echo "</ul>";
	}
	function findOtherStudentsOfSameConcept ($concept,$student){
		$individuals = getSameConceptIndividuals($concept);
		echo '<span style = "color:white"> Other  Students with '.$concept.' Program</span>';
		$test = count($individuals);
		echo "<ul class='list-group'>";
		foreach($individuals as $ind) {
			if ($ind['Name'] != $student){
				echo "<li class='list-group-item' onclick='triggerSearch(this)'>".$ind['Name'];
				echo '</li>';
			}
			
		}
		echo "</ul>";
	}
	function findOfferedCourse ($dept){
		
		$result =getCoursesDeptWise ($dept);
		echo '</span></div>';
		echo '<span style = "color:white"> Offered Courses </span>';
		echo "<ul class='list-group'>";
		foreach($result as $ind) {
			echo "<li class='list-group-item' onclick='triggerSearch(this)'>".$ind['courseName'];
			 echo '</li>';
		}
		echo "</ul>";
	}
	function findTeachers ($dept){
		$result =getTeachersBasedOnDept ($dept);
		echo '</span></div>';
		echo '<span style = "color:white"> Teachers </span>';
		echo "<ul class='list-group'>";
		foreach($result as $ind) {
			echo "<li class='list-group-item' onclick='triggerSearch(this)'>".$ind['teacherName'];
			 echo '</li>';
		}
		echo "</ul>";
	}
	function findTeacherTakingCourses($teacher){
		
		$result =getTeacherCourses ($teacher);
		echo '</span></div>';
		echo '<span style = "color:white"> Courses </span>';
		echo "<ul class='list-group'>";
		foreach($result as $ind) {
			echo "<li class='list-group-item' onclick='triggerSearch(this)'>".$ind['courseName'];
			 echo '</li>';
		}
		echo "</ul>";
	}
	function findCourseBasedOnProgram($program){
		$result =getProgramWiseCourse($program);
		echo '</span></div>';
		echo '<span style = "color:white"> Courses </span>';
		echo "<ul class='list-group'>";
		foreach($result as $ind) {
			echo "<li class='list-group-item' onclick='triggerSearch(this)'>".$ind['courseName'];
			 echo '</li>';
		}
		echo "</ul>";
	}
	
	
?>