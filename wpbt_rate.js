jQuery('document').ready(function($){
	setTimeout(function(){
			$.get("http://testwp.local.lan/wp-content/plugins/wp_bitcoin_widget/data2_bk.php").done( function(data) {
			   	$('#data_contain').html(data);
			});
	  }, 10000);
});