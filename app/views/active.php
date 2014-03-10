<?php

use Carbon\Carbon;

if (Session::has('flash_error'))
{
	?>
	<div id="flash_error" class="alert alert-danger"><?=Session::get('flash_error')?></div>
	<?php
}

if (Session::has('flash_msg'))
{
	?>
	<div id="flash_error" class="alert alert-info"><?=Session::get('flash_msg')?></div>
	<?php
}
?>

<?php
if($activeTimers->count() > 0)
{
	?>
	<div id="flash_error" class="alert alert-success">Next Timer: <strong><?=Carbon::createFromTimeStamp(strtotime($activeTimers[0]->timeExiting))->diffForHumans();?></strong></div>
	<?php
}
?>

<div class="row">
	<div class="col-lg-12">
		<h3>
			<a href="<?=URL::to('add')?>" class="btn btn-success pull-right btn-sm">New</a>
			Active Timers <label class="label label-default now"><?=date('Y-m-d H:i:s e', time())?></label>
		</h3>
		<div>
			<table class="table table-condensed table-striped table-bordered table-hover">
				<thead>
				<tr>
					<th>Name</th>
					<th>Structure</th>
					<th>Timer</th>
					<th>Date</th>
					<th>Showed Up</th>
					<th>Win/Loss</th>
					<th>User</th>
					<th>Actions</th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach($activeTimers as $id => $timer)
				{
					$name = MapItem::find($timer->itemID);
					$user = ApiUser::find($timer->user_id);

					$nowDate = Carbon::now();
					$timerDate = Carbon::createFromTimeStamp(strtotime($timer->timeExiting));

					$hours = $nowDate->diffInHours($timerDate, false);
					$minutes = $nowDate->diffInMinutes($timerDate, false);


					$class = '';
					$style = '';
					if($timer->timerType == 0)
					{
						$class = 'success';
						if($hours < 4)
						{
							$style = 'background: #BAE7A8';
						}
					}
					else if($timer->timerType == 1)
					{
						$class = 'danger';
						if($hours < 4)
						{
							$style = 'background: #EBA8A8';
						}
					}

					?>
					<tr class="<?=$class?>" id="item<?=$id?>" hours="<?=$hours?>">
						<td style="<?=$style?>">
							<label title="<?=$name->itemID?>" class="label <?= ($name->groupID == Timers::$POCOGroupID ? 'label-primary' : 'label-default') ?>" style="padding: .2em .7em .3em;"><?= ($name->groupID == Timers::$POCOGroupID ? 'P' : 'M') ?></label>
							<?=$name->itemName?>
						</td>
						<td style="<?=$style?>">
							<?=Timers::$structureIDs[$timer->structureType]?>
						</td>
						<td style="<?=$style?>">
							<?=Carbon::createFromTimeStamp(strtotime($timer->timeExiting))->diffForHumans();?>
						</td>
						<td style="<?=$style?>">
							<?=date('Y-m-d H:i:s e', strtotime($timer->timeExiting))?>
						</td>
						<td style="<?=$style?>">
							<?php
							if($timer->bashed === 0 and strtotime($timer->timeExiting) > time())
							{
								?><label class="label label-info">NO DATA</label><?php
							}
							else if($timer->bashed === 0 and strtotime($timer->timeExiting) < time())
							{
								?><label class="label label-danger">NO</label><?php
							}
							elseif($timer->bashed === 1)
							{
								?><label class="label label-success">YES</label><?php
							}
							?>
						</td>
						<td style="<?=$style?>">
							<?php
							if($timer->bashed === 0)
							{
								?><label class="label label-info">NO DATA</label><?php
							}
							else
							{
								if($timer->outcome === 1)
								{
									?><label class="label label-success">WIN</label><?php
								}
								else if($timer->outcome === 2)
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
						<td style="<?=$style?>">
							<?php
							if($minutes > -15 and $timer->outcome === 0)
							{
								?>
								<a href="<?=URL::route('delete_timer', array($timer->id))?>" class="btn btn-danger btn-xs deleteButton">Delete</a>
							<?php
							}
							else if($minutes <= -15 and $timer->outcome === 0)
							{
								?>
								<a href="<?=URL::route('win_timer', array($timer->id))?>" class="btn btn-primary btn-xs">Win</a>
								<a href="<?=URL::route('fail_timer', array($timer->id))?>" class="btn btn-warning btn-xs">Loss</a>
							<?php
							}
							?>
						</td>
					</tr>
				<?php
				}
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>