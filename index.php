<!DOCTYPE html>
<html>

<head>
	<title>SimpleMessageSSE</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8"/>
</head>

<body>
	<div id="msgdiv">
		<input type="text" id="msg">
		<input type="button" value=">" onclick="sendMsg()">
	</div>

	<div id="result"></div>

	<script>
		var source = new EventSource("getMessages.php");
		source.onmessage = function(event) {
			document.getElementById("result").innerHTML += event.data + "<br>";
		};
		
		function sendMsg()
		{
			var mmsg = document.getElementById("msg");
			if (mmsg.value != "")
			{
				var request = new XMLHttpRequest();
				request.open("GET", "sendMessage.php?msg=" + encodeURIComponent(mmsg.value), true);
				request.send();
				mmsg.value = "";
			}
		}
	</script>

</body>
</html> 
