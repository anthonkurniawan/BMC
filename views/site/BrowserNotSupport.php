<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="/bmc2//favicon.ico" type="image/vnd.microsoft.icon"></link>
    <title>BMC2-Notice</title>
		<!--<link href="/bmc2/css/site.css" rel="stylesheet">-->
		<script>
		var oldBrowser = false;
		var browser = navigator.userAgent.toLowerCase();
		var ieCheck = /msie [\w.]+/.exec(browser);
		if(ieCheck){
			var ver = /\d+/.exec(ieCheck[0]);  console.log('IE:', ieCheck[0], 'ver:', ver);
			if(ver < 9) oldBrowser = true;
		}

		if(!oldBrowser){ 
			window.location = '/bmc2';
		}
		</script>
	</head>
	<body>
		<div style="position:absolute;left:40%;top:50%;text-align:center;/*background-color:#FF4136;width:300px;height:300px;/*">
				<b>BROWSER NOT SUPPORTTED</b><br>
				Please Update Your Browsser
		</div>
	</body>
</html>
	