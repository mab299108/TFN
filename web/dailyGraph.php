<?php 
//CONFIG
$primary='ambient';//This is the main sensor
//END CONFIG

//Construct database connection
$db = new PDO('mysql:host=localhost;dbname=dataLogger;charset=utf8', 'user1', 'password1');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

//Query data for each sensor, and the overall ave for the main
$stmt = $db->query('select * from daily order by sensor, month, day, hour DESC');
$aveQuery  = $db->query("select avg(temp) as temp from weekly where sensor = '".$primary."' ");
$currentTemps = $db->query("select temp, MAX(time) as time, sensor from tempData group by sensor");
while($row = $currentTemps->fetch(PDO::FETCH_ASSOC)) {
  $averageArr[$row['sensor']] = round($row['temp'], 1);
  $asOfTime = date("h:i A", strtotime($row['time']));
}

//set average temp
$result = $aveQuery->fetch(PDO::FETCH_ASSOC);
$average = round($result['temp'], 1);

//Declare vars
$log = array();//this holds the actual graph data set
$ticks = array();//this holds the x axis names
$maxCount = array();//this holds counts per sensor, so that the graph can be scaled to the largest dataset.
$color = array(0=>'#42282F',1=>'#74A588', 4=>'#D6CCAD', 3=>'#DC9C76', 2=>'#D6655A');//THese are the colors for the graph lines

$current = '';//these two are used to reset the count when the loop jumps from one sensor to the next. 
$last = '-99';//set to -99 to ensure tht $current and $last dont match on first run

$prevDay = '00/00 - ';
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $current = $row['sensor'];
    if($current != $last){$count = 0;} 
    if($count == 0 and $row['sensor']==$primary){//then value will get shown as current temp.
        $currentT = round($row['temp'], 1)." F";
    } 
    
    //$log array gets sensor data for the graph as a string
    $log[$row['sensor']] = $log[$row['sensor']]."[".$count.", ".$row['temp']."],";
    $maxCount[$row['sensor']] = $count;
    
    $currDay = "<br/>".$row['month']."/".$row['day'];

    if ($count % 2 == 0) {
      $displayHour = '';
    }else{
      $displayHour = $row['hour'];
    }
 
    if($prevDay ==  $currDay)
    {

      $displayDayHour = $displayHour;

    }else{

      $displayDayHour = $displayHour.$currDay;

    }
    

    $ticks[$row['sensor']] = $ticks[$row['sensor']]."[".$count.", '".$displayDayHour."'],";
    $prevDay = $currDay;

    $count++;
    $last = $current;
}

$masterKey = array_search(max($maxCount), $maxCount);//the key of the array with most elements
for ($i=0; $i<=$maxCount[$masterKey]; $i++)//builds graph data for the overall average of $primary sensor
{
  $log['average'] = $log['average']."[".$i.", ".$average."],"; 
}

$template = '';//THis will be used to hold formatted graph data
$count = 0;//used to pick a color for the $color array
foreach($log as $key => $v)
{
  //$log[$key] = rtrim($v, ",");//strip trailing ,
  $template = $template."{data: [".rtrim($v, ",")."], label: \"".$key."\", color:\"".$color[$count]."\"},";
  $count++;
}
$template = rtrim($template, ",");
$db = null;
unset($count, $db, $row, $log, $last, $current, $color, $maxCount, $result, $aveQuery, $stmt);

//Vars set for page below:
//$currentT
//$template
//$ticks[$masterKey]
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
  
    <title>Temp Table</title>

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
        <h2>Current Temp</h2>
    </div>
    <div id="main">

    <div class="panel-body">
      <div class="beer_info">
        <p class="brewSpecs">
          <div class="graph-container">
            <div id="placeholder" class="graph-placeholder"></div>
          </div>
        </p>
      </div>
      <div class="beer_chart">  
        <div class="beer_infoHeader">  
          <p><b>Inside:</b><?php echo $averageArr['primary']; ?>F</p>
          <p><b>Ambient:</b><?php echo $averageArr['ambient']; ?>F</p>
          <p><b>Average:</b><?php echo $average; ?>F</p>
          <p><b>As Of: </b><?php echo $asOfTime; ?></p>
        </div>
      </div>
    </div>

        
    </div>


    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/holder.js"></script>
    <script src="js/jquery.flot.js"></script>
    <script>
      $(function() {
        //var d1 = [<?php echo $log1; ?>];
        //var d2 = [<?php echo $log2; ?>];
        var LogT = [<?php echo $template; ?>];
        //var data = [{ data: d1, label: "Pressure", color: "#333" }];
        
      //  var AverageT = [{data: d2, label: "Average", color: "#777" }];
        var placeholder = $("#placeholder");

            var plot = $.plot(placeholder, LogT,  {
              xaxis: { ticks: [<?php echo $ticks[$masterKey]; ?>]}
              //,yaxis: { min: 20, max: 85 }
            });

      });
    </script>

  </body>
</html>