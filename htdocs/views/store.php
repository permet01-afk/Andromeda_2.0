 <?php
	$displayPage = 'store';
	if(isset($_GET['tab']))
	{
		if($_GET['tab'] == 'successful')
		{
			$displayPage = 'successful';
		}
		else if($_GET['tab'] == 'cancelled')
		{
			$displayPage = 'cancelled';
		}
	}	
?>	
<link rel="stylesheet" type="text/css" href="styles/home.css" />
<link rel="stylesheet" type="text/css" href="styles/store.css" />
<div class="CMSContent">
	<div class="box" style="margin-left:110px;margin-bottom:20px;">
		<div class="title">Store </div>
		<div id="store">
			 <?php if($displayPage == 'successful')
			 {
				 ?>
				 <br><br>
				 <h1>Thank You</h1><br><br>
				 <p>Your payment was successful.
				 <br><br>
				 Your tokens will be available soon.</p>
				 <?php
			 }
			 else if($displayPage == 'cancelled')
			 {
				 ?>
				 <br><br><br>
				 <h1>Payment Cancelled</h1>
				 <br>
				 <br><br>
				 <p>Your payment was cancelled.</p>
				 <?php
			 }
			  else if($displayPage == 'store')
			 {
				 ?>
				 
			
				<h1>Buy tokens !</h1>  
				</br>Tokens are needed to perform special actions such as changing your in-game name, email, password, etc.. 
				<center>
				<br><br><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="the_king_of_kosovo96@hotmail.com">
				<input type="hidden" name="lc" value="CH">
				<input type="hidden" name="item_name" value="Andromeda's tokens">
				<input type="hidden" name="button_subtype" value="services">
				<input type="hidden" name="no_note" value="0">
				<input type="hidden" name="currency_code" value="CHF">
				<input type="hidden" name="custom" value="<?=$_SESSION['player_id']?>">
				<input type="hidden" name="return" value="http://andromeda-server.com/view.php?page=store&tab=successful">
				<input type="hidden" name="cancel_return" value="http://andromeda-server.com/view.php?page=store&tab=cancelled">
				<input type="hidden" name="notify_url" value="http://andromeda-server.com/PAYPAL/paypal_ipn.php">
				<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest">
				<table>
				<tr><td><input type="hidden" name="on0" value="Amount of token:">Amount of token:</td></tr><tr><td><select name="os0">
					<option value="5 tokens">5 tokens 5.00 CHF</option>
					<option value="25 tokens">25 tokens 25.00 CHF</option>
					<option value="50 tokens">50 tokens 50.00 CHF</option>
					<option value="150 tokens">150 tokens 100.00 CHF</option>
				</select> </td></tr>
				</table>
				<input type="hidden" name="currency_code" value="CHF">
				<input type="hidden" name="option_select0" value="5 tokens">
				<input type="hidden" name="option_amount0" value="5.00">
				<input type="hidden" name="option_select1" value="25 tokens">
				<input type="hidden" name="option_amount1" value="25.00">
				<input type="hidden" name="option_select2" value="50 tokens">
				<input type="hidden" name="option_amount2" value="50.00">
				<input type="hidden" name="option_select3" value="150 tokens">
				<input type="hidden" name="option_amount3" value="100.00">
				<input type="hidden" name="option_index" value="0">
				<br><input type="image" src="https://www.paypalobjects.com/en_US/CH/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
				<br><img alt="" border="0" src="https://www.paypalobjects.com/fr_XC/i/scr/pixel.gif" width="1" height="1">
				</center>
			<?php
			 }
			 ?>
		</div>
	</div>	
</div>