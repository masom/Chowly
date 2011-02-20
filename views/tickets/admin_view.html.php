<h1>Ticket</h1>
<table>
<tr><td>Created:</td><td><?=date('M j H:i:s', $ticket->created->sec);?></td></tr>
<tr><td>Email:</td><td><?=$ticket->email;?></td></tr>
<tr><td>Zip:</td><td><?=$ticket->zip;?></td></tr>
<?php if(!empty($ticket->restaurant)):?>
	<tr><td>Restaurant Name:</td><td><?=$ticket->restaurant;?></td></tr>
<?php endif;?>
<?php if(!empty($ticket->phone)):?>
	<tr><td>Phone Number:</td><td><?=$ticket->phone;?></td>
<?php endif;?>
</table>

<h3>Message</h3>
<?=htmlentities(nl2br($ticket->content));?>