<div id="ribbon">
	<span>Thank You!</span>
</div>
<div id="content-wrapper">
	<div style="margin-left: auto; margin-right: auto; margin-top: 20px; width: 800px">
		<div style="margin-top: 40px;">
			<p style="font-weight: bold; font-size: 22px; text-align: center;"><?=$this->html->link('Download your voucher', array('Purchases::download', 'id'=> $purchase->_id, 'type'=>'pdf'));?></p>
			<div style="font-size: 11px; text-align: center; margin-left: auto; margin-right: auto; width: 600px;">
			<?php if($emailSent):?>
				<p>We have also sent an email to <?=$purchase->email;?>. Please check your Spam/Junk folders if it does not appear in your inbox.</p>
			<?php else:?>
				<p style="background-color: #FFE91A; border: 1px solid #FFB91A;">We could not send a confirmation email, make sure you download your coupon and save/print the PDF right away.</p>
			<?php endif;?>
			<p>Questions? Comments? Problems? Contact Chowly at <a href="mailto:support@chowly.com">support@chowly.com</a></p>
			</div>
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