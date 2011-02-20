<h1>Tickets</h1>

<table>
	<thead>
	<tr><th style="width: 100px;">Submitted</th><th>Email</th><th style="width: 400px;">Subject</th></tr>
	</thead>
	
	<?php foreach($tickets as $ticket):?>
		<tr>
			<td><?=date('M j H:i:s', $ticket->created->sec);?></td>
			<td><?=$ticket->email;?></td>
			<td><?=$this->html->link((strlen($ticket->content) > 30)? substr($ticket->content, 0, 27).'...' : $ticket->content, array('action'=>'view', 'id'=>$ticket->_id,'admin'=>true));?></td>
		</tr>
	<?php endforeach;?>
</table>