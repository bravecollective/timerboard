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

<div class="page-header" style="margin: 0 0 20px;">
	<h2>
		<a href="<?=URL::route('home')?>" class="pull-right btn btn-default">Back to List</a>
		Timer Details
	</h2>
</div>
<?php
        $name = MapItem::find($timer->itemID);
        $user = ApiUser::find($timer->user_id);
        
        $sys_tmp = preg_split("/\ [IVX]+/", $name->itemName);
        $system = $sys_tmp[0];
?>
<h3><a href="http://evemaps.dotlan.net/system/<?=$system?>"><?=$name->itemName?></a></h3>
<h4><?=date('Y-m-d H:i:s e', strtotime($timer->timeExiting))?> - <?=Carbon::createFromTimeStamp(strtotime($timer->timeExiting))->diffForHumans();?></h4>
<label title="<?=$name->itemID?>" class="label <?= ($name->groupID == Timers::$POCOGroupID ? 'label-primary' : 'label-default') ?>" style="padding: .2em .7em .3em;"><?= ($name->groupID == Timers::$POCOGroupID ? 'P' : 'M') ?></label>
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
<label class="label label-<?=($timer->structureStatus === '1' ? 'primary' : 'danger')?>"><?=Timers::$structureStatus[$timer->structureStatus]?></label>
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
<?php
foreach(Timers::$signUpRoles as $roleId => $roleName)
{
        ?><h3><?=$roleName?> Sign Ups</h3>
        <?php
        if(!$timer->signUps->isEmpty())
        {
                ?><ul>
                <?php 
                foreach($timer->signUps()->wherePivot('role', $roleId)->get() as $id => $user)
                {
                        ?><li><?=$user->getNameWithTicker()?>
                                <?php
                                if($user->pivot->confirmed)
                                {
                                        ?><label class="label label-success">Confirmed</label>
                                <?php
                                }
                                else
                                {
                                        ?><label class="label label-warning">Maybe</label>
                                <?php
                                }
                                ?>
                        </li>
                <?php
                }
                ?>
                </ul>
                <?php
        }
        if($timer->userCanSignUp($roleId))
        {
                if($timer->isUserSignedUpAs(Auth::user()->id, $roleId))
                {       
                        //Confirmed sign up
                        $signUp = $timer->signUps()->wherePivot('api_user_id',Auth::user()->id)->wherePivot('role', $roleId)->first();
                        if($signUp->pivot->confirmed)
                        {
                                ?><a href="<?=URL::route('modify_sign_up', array($timer->id, 'role' => $roleId, 'confirmed' => false))?>" class="btn btn-warning btn-xs">Change to Maybe</a>
                        <?php
                        }
                        //Maybe Sign up
                        else
                        {
                                ?><a href="<?=URL::route('modify_sign_up', array($timer->id, 'role' => $roleId, 'confirmed' => true))?>" class="btn btn-success btn-xs">Change to Yes</a>
                        <?php
                        }
                        ?>
                        <a href="<?=URL::route('delete_sign_up', array($timer->id, 'role' => $roleId))?>" class="btn btn-danger btn-xs">Remove Sign Up</a>
                <?php
                }
                else
                {
                        ?>
                        <a href="<?=URL::route('sign_up', array($timer->id, 'role' => $roleId, 'confirmed' => true))?>" class="btn btn-success btn-xs">Sign Up Yes</a>
                        <a href="<?=URL::route('sign_up', array($timer->id, 'role' => $roleId, 'confirmed' => false))?>" class="btn btn-warning btn-xs">Sign Up Maybe</a>
                <?php
                }
        }
}
?>
<h3>Notes:</h3>
<?php
if(!$timer->notes->isEmpty())
{
        foreach($timer->notes()->get() as $id => $note)
        {
                ?><blockquote>
                        <?=$note->content?>
                        <footer><?=$note->user->getNameWithTicker()?> <em data-livestamp="<?=$note->created->toISO8601String()?>"><?=$note->created?></em></footer>
                </blockquote>        
        <?php
        }
}
?>
<?=Form::open(array('route' => array('details', $timer->id)))?>
<div class="form-group">
        <?=Form::textarea('notes', null, array('class' => 'form-control'))?>
</div>
<div class="form-group">
        <button type="submit" class="btn btn-primary">Add Note</button>
</div>
<?=Form::close()?>
