<?php
/*
 * Plugin Name: BTCInfoWidget
 * Plugin URI: http://webandseoguide.tk
 * Description: Get live details about visitor count, current online peoples etc
 * Version: 1.0.0
 * Author: Ganesh Veer
 * Author URI: 
 * License: GPL2
*/

defined('ABSPATH') or die('Hey, what are you doing here?');

/**
 * Adds Foo_Widget widget.
 */
class BTC_Live_Rate_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'btc_live_rate_widget', // Base ID
			esc_html__( 'BTC Live Rate', 'text_domain' ), // Name
			array( 'description' => esc_html__( 'BTC live rate widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		//https://www.bitstamp.net/api/v2/ticker_hour/xrpusd/ - api to get ripple to usd

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

		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		echo esc_html__( '1 BTC', 'text_domain' ); ?>
		<div id="data_contain">		
		<table>
		<tr><td id="lastprice">$<?php echo $price; ?>USD/<?php echo number_format($price*$ausdinrs, 2); ?>INR</td>
			<td align="right" style="color:<?php echo $color; ?>"><?php echo $percentagechange; ?></td></tr>
		<tr><td align="left">HIGH</td><td align="right">$<?php echo $high; ?></td></tr>
		<tr><td align="left">LOW</td><td align="right">$<?php echo $low; ?></td></tr>
		<tr><td align="left">OPEN</td><td align="right">$<?php echo $open; ?></td></tr>
		<tr style="border-top: 1px solid #000;"><td align="left" style="padding-right: 15px;">1USD/<?php echo $ausdinrs.'INR'; ?> </td><td align="right"><?php echo $date; ?></td></tr>	
		<tr><td align="left"></td><td align="right"><input id="refreshdata" type="button" value="Refresh Data" onClick="history.go(0)"></td><tr>		
		</table>
		</div>
		<?php
	
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class Foo_Widget

// register Foo_Widget widget
function register_btc_widget() {
    register_widget( 'BTC_Live_Rate_Widget' );
}
add_action( 'widgets_init', 'register_btc_widget' );


/*
class WPBitcoinWidget{
	function __construct(){
		add_action('init', array($this, 'wpbitcoinwidget_function'));
	}
	function activate(){
		//flush rewrite rule
		$this->wpbitcoinwidget_function();
		flush_rewrite_rules();
	}
	function deactivate(){
		//flush rewrite rule
		flush_rewrite_rules();
	}
	function wpbitcoinwidget_function(){

	}
}
if(class_exists('WPBitcoinWidget')){
	$wpbitwidget = new WPBitcoinWidget();
}

//Activation
register_activation_hook('__FILE__', array($wpbitwidget,'activate'));

//Deactivation
register_deactivation_hook('__FILE__', array($wpbitwidget,'deactivate'));

//uninstall
*/
