<div id="ribbon">
	<span>Thank You!</span>
</div>
<div id="content-wrapper">
	<div style="margin-left: auto; margin-right: auto; margin-top: 20px; width: 800px">
		<h3>Your purchase has been processed.</h3>
		<div style="margin-top: 20px;">
			<?php if($emailSent):?>
				<p>An email has been sent to <?=$purchase->email;?></p>
				<p>If you do not receive a email, please check your Spam/Junk folders.</p>
				<p>If there is still no email after 30 minutes, please contact Chowly support: support@chowly.com</p>
			<?php else:?>
				<p>An error occured while sending the email. Make sure you download your coupon (following the link bellow) and save/print the PDF right away.</p>
				<p><?=$this->html->link('Download your Coupon', array('Purchases::download', 'id'=> $purchase->_id, 'type'=>'pdf'));?></p>
			<?php endif;?>
		</div>
		<table cellspacing="0" cellpadding="0">
			<tr><td>Purchase id</td><td><?=$purchase->_id;?></td></tr>
			<tr><td>date</td><td><?=date('Y-m-d H:i:s', $purchase->created->sec);?></td></tr>
			<tr><td>Transaction id</td><td><?=$purchase->trans_id;?></td></tr>
			<tr><td>Auth $</td><td><?=$purchase->auth;?></td></tr>
		</table>
	</div>
</div>