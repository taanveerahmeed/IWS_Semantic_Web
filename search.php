<!DOCTYPE html>
<html lang="en" >
<?php  include_once('sparqlfunctions.php');  ?>
<head>
    <meta charset="utf-8">
    <title>UMS</title>
    <!--<link rel="stylesheet" type="text/css" href="style.css">-->
    <link rel="stylesheet" type="text/css" href="jquery-ui.css">
    <script src="jquery-1.11.0.min.js"></script>
    <script src="jquery-ui.js"></script>
    <script src="ontranetbd.js"></script>

    <script type="text/javascript" src="./assets/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="./assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css">

	<script>
        $(function() {
			
            var availableTags = [
                <?php
                   
                    $allLabels = getAllIndividuals();
					 $i = 0;
                    foreach ($allLabels as $label){
						 if( $i > 0 ) echo ',';
                        echo '"'.$label['Name'].'"';
						$i++;
                   
                    };
                ?>
            ];
            $( "#key" ).autocomplete({
                source: availableTags
            });
          });

    </script>
    
    <script type="text/javascript">
        function triggerSearch(e){
			//alert(e);
           var name = $(e).text();

            if( name.indexOf('\(') >= 0 ) {
                name = name.substr(0, name.indexOf('\('));
            }

            name = name.trim();

            $('#key').val(name);

            $('#search').trigger('click');
        }

        function beforeSearch(e){
			
            if($(key).val() != ''){
                $("html, body").animate({ scrollTop: 0 }, "slow");
                $('#result').html('<div class="row text-center"><img src="./assets/images/hourglass.gif" /></div>');				 
				$.get("queryProcessor.php?search="+$(key).val(),function (data,status){
					$('#result').html(data);
					
				}); 
            }
        }
    </script>

    <style rel="stylesheet" type="text/css" >
	
    body {
        overflow-x: hidden;
    }
    .list-group-item {
        cursor: pointer;    
    }
    .list-group-item:hover {
        background-color: #ddd;
        /*color: #fff;*/
    }
    .header {

    }
    .container {
        width: 80%;
        
    }
    .contents {
        /*margin: 60px 0 0 0;*/
        overflow: hidden;
    } 
    .concepts {
        font-size: 15px;
    }  
    #result {
        padding-top: 15px;
    }
    </style>
</head>

<body>
    <div class="site-wrapper">
        <div class="site-wrapper-inner">
            <div class="cover-container">
                <div class="clearfix">
                    <div class="inner">
                        <h3 class="masthead-brand">UMS</h3>
                        <nav>
                            <ul class="nav masthead-nav">                             
                                <li class="active"><a href="search.php">Search</a></li>                              
                            </ul>
                        </nav>
                    </div>
                </div>
               
                <div style='margin-top: 0px;'>
                    <div class="inner cover col-sm-8">    
                        <div class="">
                            <div class="col-sm-12">
                                <div class="input-group">
                                    <input id="key" type="text" class="form-control" placeholder="Search for Teacher Student Course...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="button" id="search" value="Search" onclick="beforeSearch(this)">Search</button>
                                    </span>
                                </div><!-- /input-group -->
                            </div><!-- /.col-lg-6 -->

                            <!--<form method="post">
                                <div class="form-group"></div>
                                
                            </form>
                             <div class="panel panel-primary">...</div> -->
                            <br>
                            <div id="result" ></div>

                        </div>                    
                    </div>
                    <div class="inner cover col-md-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Criterias</h3>
                            </div>
                            <div class="panel-body"> 
                                <?php
                                    
                                    $programs = getAllPrograms();
									$departments = getAllDepartment();
									$designations = getAllDesignations();
                                    
                                    //sort( $leaves );
									 echo "<span style='cursor: pointer; color:black' class='label' '>".'Programs'."</span><br>";
                                    foreach( $programs as $l ) {
                                        echo "<span style='cursor: pointer' class='label label-primary' onclick='triggerSearch(this)'>".$l['typeName']."</span><br>";
                                        //echo $l." ";
                                    }
									echo "<span style='cursor: pointer; color:black' class='label' '>".'Departments'."</span><br>";
									foreach( $departments as $l ) {
                                        echo "<span style='cursor: pointer' class='label label-danger' onclick='triggerSearch(this)'>".$l['itemName']."</span><br>";
                                        //echo $l." ";
                                    }
									echo "<span style='cursor: pointer; color:black' class='label' '>".'Teachers'."</span><br>";
                                    foreach( $designations as $l ) {
                                        echo "<span style='cursor: pointer' class='label label-success' onclick='triggerSearch(this)'>".$l['designationName']."</span><br>";
                                        //echo $l." ";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</body>
</html>
