<?php
use Carbon\Carbon;
?>

<table class="table table-condensed table-striped table-bordered table-hover">
	<thead>
	<tr>
		<th>Name</th>
		<th>Structure</th>
		<th>Type</th>
		<th>Timer</th>
		<th>EVE Time</th>
		<th>Win/Loss</th>
		<th>User</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
	<?php
	foreach($timers as $id => $timer)
	{
		$name = MapItem::find($timer->itemID);
		$user = ApiUser::find($timer->user_id);

		$nowDate = Carbon::now();
		$timerDate = Carbon::createFromTimeStamp(strtotime($timer->timeExiting));

		$hours = $nowDate->diffInHours($timerDate, false);
		$minutes = $nowDate->diffInMinutes($timerDate, false);

		$class = '';
		$style = '';
		if($timer->timerType === '0')
		{
			$class = 'success';
		}
		else if($timer->timerType === '1')
		{
			$class = 'danger';
		}

		// The database does not store system names. Find roman numerals and split out the basename.
		$sys_tmp = preg_split("/\ [IVX]+/", $name->itemName);
		$system = $sys_tmp[0];
		?>
		<tr class="<?=$class?>" style="<?=$style?>" id="item<?=$id?>" hours="<?=$hours?>">
			<td style="<?=$style?>">
				<label title="<?=$name->itemID?>" class="label <?= ($name->groupID == Timers::$POCOGroupID ? 'label-primary' : 'label-default') ?>" style="padding: .2em .7em .3em;"><?= ($name->groupID == Timers::$POCOGroupID ? 'P' : 'M') ?></label>
				<?php
				// Display timer type as an icon to help with red/green colorblind users
				if ($timer->timerType === '0') {
					?>
					<label class="label label-success" style="padding: .2em .7em .3em;">
						<span class="glyphicon glyphicon-fire" title="Offensive Timer"></span>
					</label>
					<?php
				}
				else {
					?>
					<label class="label 'label-danger" style="padding: .2em .7em .3em;">
						<span class="glyphicon glyphicon-tower" title="Defensive Timer"</span>
					</label>
					<?php	
				}
				?>
				<a href="http://evemaps.dotlan.net/system/<?=$system?>"><?=$name->itemName?></a>
			</td>
			<td style="<?=$style?>">
				<?php
				$label_class = 'default';
				switch($timer->structureType)
				{
					case '3':
					case '4':
						$label_class = 'info';
						break;

					case '5':
						$label_class = 'danger';
						break;
				}
				?>
				<label class="label label-<?=$label_class?>"><?=Timers::$structureTypes[$timer->structureType]?></label>
			</td>
			<td style="<?=$style?>">
				<label class="label label-<?=($timer->structureStatus === '1' ? 'primary' : 'danger')?>"><?=Timers::$structureStatus[$timer->structureStatus]?></label>
			</td>
			<td style="<?=$style?>" title="<?=Carbon::createFromTimeStamp(strtotime($timer->timeExiting))->toISO8601String()?>" class="timeago">
				<?=Carbon::createFromTimeStamp(strtotime($timer->timeExiting))->diffForHumans();?>
			</td>
			<td style="<?=$style?>">
				<?=date('Y-m-d H:i:s e', strtotime($timer->timeExiting))?>
			</td>
			<td style="<?=$style?>">
				<?php
				if($timer->bashed === '0')
				{
					?><label class="label label-info">NO DATA</label><?php
				}
				else
				{
					if($timer->outcome === '1')
					{
						?><label class="label label-success">WIN</label><?php
					}
					else if($timer->outcome === '2')
					{
						?><label class="label label-danger">LOSS</label><?php
					}
					else
					{
						?><label class="label label-info">Outcome Needed</label><?php
					}
				}
				?>
			</td>
			<td style="<?=$style?>">
				<?=$user->character_name?> (<?=$user->alliance_name?>)
			</td>
			<td style="<?=$style?>" class="text-right">
				<?php
                                if(Auth::user()->canViewDetails())
                                {
                                        ?>
                                        <a href="<?=URL::route('details', array($timer->id))?>" class="btn btn-info btn-xs">Details</a>
                                <?php
                                }
				if(Auth::user()->permission === '1')
				{
					if($minutes > -15 and $timer->outcome === '0')
					{
                                                ?>
						<a href="<?=URL::route('delete_timer', array($timer->id))?>" class="btn btn-danger btn-xs deleteButton">Delete</a>
					<?php
					}
					else if($minutes <= -15 and $timer->outcome === '0')
					{
						?>
						<a href="<?=URL::route('win_timer', array($timer->id))?>" class="btn btn-primary btn-xs">Win</a>
						<a href="<?=URL::route('fail_timer', array($timer->id))?>" class="btn btn-warning btn-xs">Loss</a>
					<?php
					}
				}
				?>
			</td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
<?php
if(isset($paginate) and $paginate == true)
{
	echo $timers->links();
}
?>
