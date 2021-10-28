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
		
		// Begin codes from https://stackoverflow.com/a/54385402/5371561
		function isFunction(functionToCheck) {
			return functionToCheck && {}.toString.call(functionToCheck) === '[object Function]';
		}

		function debounce(func, wait) {
			var timeout;
			var waitFunc;
			
			return function() {
				if (isFunction(wait)) {
					waitFunc = wait;
				}
				else {
					waitFunc = function() { return wait };
				}
				
				var context = this, args = arguments;
				var later = function() {
					timeout = null;
					func.apply(context, args);
				};
				clearTimeout(timeout);
				timeout = setTimeout(later, waitFunc());
			};
		}

		// reconnectFrequencySeconds doubles every retry
		var reconnectFrequencySeconds = 1;
		var evtSource;

		var reconnectFunc = debounce(function() {
			setupEventSource();
			// Double every attempt to avoid overwhelming server
			reconnectFrequencySeconds *= 2;
			// Max out at ~1 minute as a compromise between user experience and server load
			if (reconnectFrequencySeconds >= 64) {
				reconnectFrequencySeconds = 64;
			}
		}, function() { return reconnectFrequencySeconds * 1000 });

		function setupEventSource() {
			evtSource = new EventSource("getMessages.php"); 
			evtSource.onmessage = function(e) {
				document.getElementById("result").innerHTML += e.data + "<br>";
			};
			evtSource.onopen = function(e) {
			// Reset reconnect frequency upon successful connection
			reconnectFrequencySeconds = 1;
			};
			evtSource.onerror = function(e) {
			evtSource.close();
			reconnectFunc();
			};
		}
		setupEventSource();
		// End codes from https://stackoverflow.com/a/54385402/5371561
		
		
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
