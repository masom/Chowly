<div id="ribbon">
	<span>Thank You!</span>
</div>
<div id="content-wrapper">
	<div style="margin-left: auto; margin-right: auto; margin-top: 20px; width: 800px">
		<div style="margin-top: 40px;">
			<div id="choice-print">
				<h2>PRINT</h2>
				<p style="font-weight: bold; font-size: 22px; text-align: center;"><?=$this->html->link('Download your voucher', array('Purchases::download', 'id'=> $purchase->_id, 'type'=>'pdf'));?></p>
			</div>
			<?php if($emailSent):?>
				<div id="choice-email">
					<h2>EMAIL</h2>
					<p>We have also sent an email to <?=$purchase->email;?>. Please check your Spam/Junk folders if it does not appear in your inbox.</p>
					<div id="choice-phone">
						<h2>PHONE</h2>
						<p>If you have access to your email and a PDF reader on your phone, you can open the attachment and show it at the restaurant.</p>
					</div>
				</div>
			<?php else:?>
				<p style="background-color: #FFE91A; border: 1px solid #FFB91A;">We could not send a confirmation email, make sure you download your coupon and save/print the PDF right away.</p>
			<?php endif;?>
			<p>Questions? Comments? Problems? Contact Chowly at <a href="mailto:support@chowly.com">support@chowly.com</a></p>
		</div>
		<div style="width: 400px; margin-left: auto; margin-right: auto; text-align: center; margin-top: 30px;">
			<table cellspacing="0" cellpadding="0">
				<tr><td>#</td><td><?=$purchase->_id;?></td></tr>
				<tr><td>Date</td><td><?=date('Y-m-d H:i:s', $purchase->created->sec);?></td></tr>
				<tr><td>Transaction</td><td><?=$purchase->trans_id;?></td></tr>
			</table>
		</div>
	</div>
</div>