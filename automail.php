<?php 

//Database specs
define('DB_HOST_TV','localhost');
// define('DB_HOST_TV','72.167.59.135:3306');
define('DB_NAME_TV','tsp_pTimeSheetSaves');
define('DB_USNAME_TV','lCuellar');
define('DB_PASS_TV','2021@CuellaR');
$dbname = DB_NAME_TV;
$tablesDB = new mysqli(DB_HOST_TV, DB_USNAME_TV, DB_PASS_TV, $dbname);
$to = 'lcuellar@gss-inc.us';
$subject = '';
$htmltext = "";
$assignedhours = 0;
// To send HTML mail, the Content-type header must be set
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//Date
date_default_timezone_set("America/Bogota");
$subdate = date("Y-m-d");
$currentweek = intval(date("W",strtotime($subdate)));
$monday = firstDayOfWeek($currentweek);
$sunday = lastDayOfWeek($currentweek);
$flag = 0;
$extraflag = 0;
$extratank = 0;
$tempdayhour = 0;
$extratanktohours = "";
$tempdayhourtohours = "";

$sql = "SELECT workername,lore from $dbname.worker_list";
if ($tablesDB->query($sql) == TRUE) {
    $result = $tablesDB->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $workername = $row['workername'];
            $subject = $workername . $monday . "-" . $sunday;
            if($row['lore'] == 'Local'){
                $assignedhours = 40;
            } else if($gorow['lore'] == 'Expat'){
                $assignedhours = 48;
            }
            $sql = "SELECT dayofweek(workdate) AS Day,project,totalhours,workdate,supervisor FROM $dbname.registered_hours WHERE WEEK(workdate,1) = '$currentweek' AND worker = '$workername'";
            if ($tablesDB->query($sql) == TRUE) {
                $html2write = $tablesDB->query($sql);
                if ($html2write->num_rows > 0) {
                    while ($gorow = $html2write->fetch_assoc()) {
                        $htmltext = "<table><tr><th>Name:</th><th>$workername</th><th>Week:</th><th>$monday</th><th>$sunday</th></tr>";
                        $htmlteext .= "<tr><th>Day</th><th>Project</th><th>Hours</th><th>Extra</th><th>Supervisor</th></tr>";
                        $daygoat = $dayname;
                        $dayname = sayMyDayName($gorow['Day']);
                        if($total >= ($assignedhours*60)){
                            if($extraflag == 0){
                                $extratank = $total - ($assignedhours*60);
                                $tempdayhour = $daytotal - $extratank;
                                $extratanktohours = intdiv($extratank,60) . ':' . ($extratank%60);
                                $tempdayhourtohours = intdiv($tempdayhour,60) . ':' . ($tempdayhour%60);
                                echo "<tr bgcolor = '#D7DBDD'><td colspan = '2'>Total on $daygoat</td><td>$tempdayhourtohours</td><td>$extratanktohours</td><td></td></tr>";
                                
                            } else {
                            $dayhours = intdiv($daytotal,60) . ':' . ($daytotal%60);
                            echo "<tr bgcolor = '#D7DBDD'><td colspan = '2'>Total on $daygoat</td><td>0</td><td>$dayhours</td><td></td></tr>";
                            
                            }
                            $daytotal = 0;
                            $extraflag = 1;
                        }
                    }
                }
            }
            //Sent email part
        }
    }
}
function sayMyDayName($zename){
    switch($zename){
        case "1":
            $sayit = "Sunday";
            break;
        case "2":
            $sayit = "Monday";
            break;
        case "3":
            $sayit = "Tuesday";
            break;
        case "4":
            $sayit = "Wednesday";
            break;
        case "5":
            $sayit = "Thursday";
            break;
        case "6":
            $sayit = "Friday";
            break;
        case "7":
            $sayit = "Saturday";
            break;
        default:
            $sayit = "Error";
            break;
    }
    return $sayit;
}
?>