<!DOCTYPE html>
<html lang="">
	<head>
		<title></title>
		<meta charset="UTF-8">
		<meta name=description content="">
		<meta name=viewport content="width=device-width, initial-scale=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link type="text/css" rel="stylesheet" href="css/step2-order.css"/>
	</head>
	<body>
		<div class="order-block-01">
			<div class="wrapper">
				<div class="order-summary">
					<div class="discount-bar">Special Discount Activated!</div>
					<div class="cart-contents">
						Dermarose Eye Serum 30 Day Supply<br>
			            <span class="item-desc">14 Day Trial, No Commitments, Cancel Anytime!</span><br>
			            <br>
			            Shipping<br>
			            <span class="red">Discount Activated! Savings Applied</span><br>
			            <br>
			            Total
			        </div>
			        <div class="cart-prices">
			            $0.00<br>
			            <span class="red">TRIAL</span><br>
			            <br>
			            $9.95<br>
			            <span class="red" id="promoamountoff">-$5.00</span><br>
			            <br>
			            <span id="promodisountprice">$4.95</span>
			        </div>
			        <div class="clear"></div>
				</div>
				<div class="arrival-date">
					Your Shipment is estimated to arrive by <span class="red">[DATE]</span>
				</div>
				<div class="order-form-container">	
					<div class="form-element cc-icons">
						<label>We Accept </label> <img alt="Visa" src="img/cc-icon-visa.png" title="Visa"> <img alt="Mastercard" src="img/cc-icon-mastercard.png" title="Mastercard"> <img alt="American Express" src="img/cc-icon-amex.png" title="American Express"> <img alt="Discover Card" src="img/cc-icon-discover.png" title="Discover Card">
					</div>
					<div class="form-total">
						<b>Shipping:</b> $4.95<br>
						<b>Total:</b> $4.95
					</div>
					<div class="form-element form-input-full">
						<form name='opt_in_form' id='opt_in_form'>
				  	   			<input type="hidden" name="step" id="step" value="2"/>
				  	   			<input type="hidden" name="max_upsells" id="max_upsells" value="1">	
						    <input type="hidden" id="has_upsell" name="has_upsell" value="1"/>
								<input type='hidden' id='hasFormSubmitted' name="hastFormSubmitted" value='' />
								<input type='hidden' name ='product_name' id='product_name' value='VitaGarcinia HCA' />
								<input type='hidden' name='triangle_campaign_id' id='triangle_campaign_id' value='118'>
					  		<input type='hidden' name='offer_total' id='offer_total' value='4.95'>	
					  		<input type='hidden' name='cc_expires' id='cc_expires' value=''>	
					  			<?php
									foreach($_GET as $key => $value) {
										 if(!in_array($key,array('upsell_count','upsell_product_ids','max_upsells','current_upsell','run_order','cc_cvv','fields_expyear','fields_expmonth','cc_number','cc_type','has_upsell','combine_upsell','cc_expires')))
									     echo "<input type='hidden' name='".trim($key)."' id='".trim($key)."' value='".$value."'>\n";
								   }
								?>
					<div class="form-element form-input-full">
						<label>Card Type </label>
							<select onchange="payment_change(this)" onkeydown="this.onchange();" onkeyup="this.onchange();" id="cc_type" name="cc_type">

													<option value="">Select Payment Method</option>

													<option value="visa">Visa</option>

													<option value="master">Master Card</option>

													<option value="discover">Discover</option>
                          <option value="amex">American Express</option>
												</select>
						</div>
								<div class="form-element form-input-full">
									<label>Credit Card # </label> <input type="text" maxlength="16" onkeydown="return onlyNumbers(event,'cc')" id="cc_number" name="cc_number" autocomplete="off"></div>
								<div class="form-element form-input-2col">
									<label>Expiration Date </label> 
									<select name="fields_expmonth" onchange="javascript:update_expire()" id="fields_expmonth" class="dropdown month">
							<option value="01">January (1)</option>
							<option value="02">February (2)</option>
							<option value="03">March (3)</option>
							<option value="04">April (4)</option>
							<option value="05">May (5)</option>
							<option value="06">June (6)</option>
							<option value="07">July (7)</option>
							<option value="08">August (8)</option>
							<option value="09">September (9)</option>
							<option value="10">October (10)</option>
							<option value="11">November (11)</option>
							<option value="12">December (12)</option></select></div>
								<div class="form-element form-input-2col">
									<select name="fields_expyear" onchange="javascript:update_expire()" id="fields_expyear" class="dropdown year">
			 				<option value="2014">2014</option>
			 				<option value="2015">2015</option>
			 				<option value="2016">2016</option>
			 				<option value="2017">2017</option>
			 				<option value="2018">2018</option>
			 				<option value="2019">2019</option>
			 				<option value="2020">2020</option>
			 				<option value="2021">2021</option>
			 				<option value="2022">2022</option>
			 				<option value="2023">2023</option>
			 				<option value="2024">2024</option>
			 				<option value="2025">2025</option></select></div>
								<div class="clear">
								</div>
								<div class="form-element form-input-2col">
									<label>Security Code </label> <input class="textInput required digits" onkeydown="return onlyNumbers(event,'cc')" id="cc_cvv" name="cc_cvv" maxlength="4" style="width: 50px ! important;" title="Please, enter valid CVV" type="text"> <a class="cvv-help" href="javascript:;">Whats This?</a>
									<div class="whatiscvv">
									</div>
									<div class="clear">
									</div>
									</div>
									<input class="form-button" onclick="form_validator();" type="button"/>
									<div class="button-processing" id="button-processing" style="display:none;"><img src="images/loading.gif" /><br />Processing...</div>
								</div>

				<div class="disclaimer">
					 You will receive a jar of Dermarose to try for 15 days! All you pay today is the shipping and handling fee via USPS First Class Mail. If you are not satisfied with the results of Dermarose, call and cancel and your credit card information will be deleted from our system. You won't be charged anything besides the initial shipping and handling fee. However, if you absolutely love Dermarose, (which we expect you will!) and keep it past the 15 Day Trial period, your original form of payment will be charged $97.95 (regular retail price $143.00). 30 Day Money Back Guarantee: If for whatever reason you are not happy with Dermarose, simply send back the jar within 30-days from the date of delivery, and get a full refund (minus shipping and handling charges) on your credit card.
				</div>
			</div>
		</div>
	</body>
</html>