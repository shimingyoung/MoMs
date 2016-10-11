<?php
$ver = '2.0';//for version report
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Monitor of Monitors (MoMs)</title>
<link rel="stylesheet" href="css\jquery.mobile-1.4.5.min.css">
<script src="js\jquery.min.js"></script>
<script src="js\jquery.mobile-1.4.5.min.js"></script>
<link rel="stylesheet" href="css\style.css">
<script>
    var refreshDelay = 120;//sec
    var cnt = refreshDelay;
    function addZero(n) {
        return n < 10 ? '0' + n : n;
        }

    function updateClock() {
        var d = new Date();

        $("#system_clock").text(addZero(d.getHours()) + ":" + addZero(d.getMinutes()) + ":" + addZero(d.getSeconds()));
        if (cnt>0) {
            cnt--;
            $("#count_down").text(cnt + " sec");
        }
        else
            cnt = refreshDelay;
        setTimeout(updateClock, 1000);
        }
        
        updateClock();

    function countDown(countDown) {
        
        if(countDown > 0) {
            countDown--;
            $("#count_down").text(countDown + " sec");
            setTimeout(countDown, 1000, countDown);
            return;
        }
    }    
    function setBMTable() {
        $.ajax({
                url: "monitor/genBMTable.php",
                success: function(response) {
                    var table_code = response;
                    $("#BM").hide().html(table_code).fadeIn(1000,'swing');

                    //document.getElementById("BM").innerHTML = table_code;
                },
                complete: function() {
                    //
                },
            });
        setTimeout(setBMTable, refreshDelay*1000);
    }    

    setBMTable();
    //countDown(refreshDelay);
    

</script>
<link rel="stylesheet" type="text/css" href="monitor/monitor.css">

</head>
<body style="overflow: auto;">
<!-- /page -->
<div data-role="page" data-theme="b" data-title="MoMs Viewer" id="page_index">
<!-- /header -->
    <div id="bar_header" data-role="header" data-position="fixed">
    <h1 id="header_container" style="margin: 0;">
    <span style="color: gray;">MoMs V<?php echo $ver;?></span>
    <span id="system_clock" data-icon="arrow-r" class="ui-corner-all" style="margin: 0 1em 0 1em; padding: 0.25em; border: 1px solid SandyBrown; color: SandyBrown; text-align: center; font-weight: normal; font-family: digital_mono_italic;">00:00:00</span>
    <span style="font-size: 0.6em; color: gray;">update in: <span id="count_down" style="color: white;"></span>
    </h1>
    <a href="#panel_menu" data-icon="bars" data-iconpos="notext">Menu</a>
    <a data-icon="star" href="index.php" rel="external">CCATT</a>
    </div>
                        
    <!-- /content -->
    <div role="main" id="view_main" class="ui-content" style="position: relative;width:100%;padding: 0.2em; overflow: auto;">
    <!-- /group view -->
        <div id="BM" style="position: relative; height: 100%;">                              
        </div>
        </div>
	<div data-role="footer"> 
    <h5>Shock Trauma Center, Univeristy of Maryland, Baltimore &copy; <?php echo date("Y") ?></h5> 
	</div>	
    </div>
    
</body>
</html>
