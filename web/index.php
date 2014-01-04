<?php

    $emptyStatus = 'empty';
   
    
    //Construct database connection
    $db = new PDO('mysql:host=localhost;dbname=dataLogger;charset=utf8', 'user1', 'password1');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    //$stmt = $db->query("select * from onTap a Left JOIN brewData b ON a.brewID = b.brewID where a.status != '".$emptyStatus."' order by a.currVol ASC");
    $stmt = $db->query("select * from onTap as a Left JOIN brewData as b ON a.brewID = b.brewID order by a.currVol ASC");
    $count = 0;
    
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        /*
            `name` varchar(200) NOT NULL DEFAULT '',
            `initVol` decimal(11,0) DEFAULT '5',
            `currVol` decimal(11,0) DEFAULT NULL,
            `recipeID` int(11) DEFAULT NULL,
            `status` varchar(11) DEFAULT NULL,
            `start_date` datetime DEFAULT NULL,
            `end_date` datetime DEFAULT NULL,
            `added_date` datetime DEFAULT NULL,
        */
        //echo var_dump($row);

        $arr = array(
                    'name'=>$row['name'], 
                    'volume' => $row['initVol'], 
                    'remainder' => $row['currVol'], 
                    'status' => $row['status'], 
                    'date' => date("m/d/y", strtotime($row['start_date'])),
                    'broadStyle' => $row['styleFlag'],
                    'style' => $row['style'],
                    'desc' => $row['desc'],
                    'notes' => $row['notes'],
                    'abv' => $row['abv'],
                    'ibu' => $row['ibu'],
                    'calories' => $row['calories'],
                    'OG' => $row['og'],
                    'FG' => $row['fg']
                );

        $onTap[$count] = $arr;
        $count++;
        
    }   

    //echo var_dump($onTap);
    $onTap = json_encode($onTap); 

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="">
  
    <title>Current Brews</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->

  <link href="css/style1.css" rel="stylesheet" type="text/css">
  </head>
  <body>

    <div id="header">
        <h2>Current Brews</h2>
    </div>
    <div id="main">


        <div class="panel-group" id="accordion">
            <!-- data goes here from json -->
        </div>
        <div id="temperature">
            <!-- data goes here from json -->
        </div>


    </div>    



    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/holder.js"></script>
    <script src="js/circle.js"></script>
    <script>
var data =  <?php echo $onTap; ?>;
$.each(data,function(i,b)
{
        var collapse = '';
        if(i == 0){collapse = ' in';} 

        var content = '<div class="beer_info"><div class="beer_infoHeader"><h3>'+b.name+'</h3><h4>'+b.style+'</h4><h5>Status: '+b.status+'</h5></div><p class="brewDesc">'+b.desc+'</p><p class="brewSpecs"><b>OG:</b>'+b.OG+' <b>FG:</b>'+b.FG+' <b>IBU:</b>'+b.ibu+' <b>ABV:</b>'+b.abv+'%</p></div>';

        var panel = '<div class="panel panel-default"><div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse'+i+'">'+b.name+'</a></h4></div><div id="collapse'+i+'" class="panel-collapse collapse'+collapse+'"><div class="panel-body">'+content+'<div class="beer_chart"><div id="graph-'+i+'"></div><p>Started: '+b.date+'</p></div></div></div></div>';

        $('#main .panel-group').prepend(panel);

        var consumption = Math.round(((b.remainder/b.volume)*100) * 10) / 10;
        Circles.create({
            id:         'graph-'+i,
            percentage: consumption,
            radius:     80,
            width:      10,
            text:       consumption+'%',
            colors:     ['#D3B6C6', '#4B253A'],
            duration:   400
        });

});
    
        $('#temperature').load('dailyGraph.php');

    </script>

  </body>
</html>