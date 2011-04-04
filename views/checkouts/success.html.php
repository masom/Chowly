<div id="ribbon">
	<span>Thank You!</span>
</div>
<div id="content-wrapper">
	<div style="margin-left: auto; margin-right: auto; width: 800px">
		<div class="whitebox">
			<h3>Your purchase has been processed.</h3>
			<p>An email has been sent to <?=$purchase->email;?></p>
			<p>If you do not receive a email, please check your Spam/Junk folders.</p>
			<p>If there is still no email after 30 minutes, please contact Chowly support: support@chowly.com</p>
		</div>
		<div class="whitebox">
			<h3>Purchase Details</h3>
			<table cellspacing="0" cellpadding="0">
				<tr><td>id</td><td><?=$purchase->_id;?></td></tr>
				<tr><td>date</td><td><?=date('Y-m-d H:i:s', $purchase->created->sec);?></td></tr>
			</table>
	
			<h3>Transaction Details</h3>
			<table cellspacing="0" cellpadding="0">
				<tr><td>id</td><td><?=$purchase->trans_id;?></td></tr>
				<tr><td>auth</td><td><?=$purchase->auth;?></td></tr>
			</table>
		</div>
	</div>
</div>