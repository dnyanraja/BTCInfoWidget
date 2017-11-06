<?php
		$url = 'https://www.bitstamp.net/api/ticker/';
		$fgc = file_get_contents($url);
		$json = json_decode($fgc, true);

		$inr = file_get_contents('http://api.fixer.io/latest?base=USD');
		$parsed_inr = json_decode($inr, true);
 		$ausdinrs =  $parsed_inr['rates']['INR'];

		$price = $json["last"];
		$high  = $json["high"];
		$low   = $json["low"];
		$date = date("m-d-Y - h:i:sa");
		$open= $json["open"];

		if($open < $price){
			//price went up
			$indicator = '+';
			$change = $price-$open;
			$percent = $change/$open;
			$percent = $percent*100;
			$percentagechange = $indicator.number_format($percent, 2);
			$color ='green';
		}
 		
 		if($open > $price){
			//price went down
			$indicator = '-';
			$change =$open-$price;
			$percent = $change/$open;
			$percent = $percent*100;
			$percentagechange = $indicator.number_format($percent, 2);
			$color ='red';
		}
$table = <<<EOT	
		<table>
		<tr><td id="lastprice">$ $price; </td>
			<td align="right" style="color: $color;"> $percentagechange </td></tr>
		<tr><td align="left">INR</td><td align="right"> $price*$ausdinrs </td></tr>
		<tr><td align="left">HIGH</td><td align="right">$ $high </td></tr>
		<tr><td align="left">LOW</td><td align="right">$ $low; </td></tr>
		<tr><td align="left">Date</td><td colspan="2" id="timedata" align="right"> $date <td></tr>
		<tr><td align="left"></td><td align="right"><input id="refreshdata" type="button" value="Refresh Data" onClick="history.go(0)"></td><tr>
		<tr><td align="left">1 USD </td><td align="right"> $ausdinrs 'INR'</td></tr>	
		</table>		
EOT;

echo $table;