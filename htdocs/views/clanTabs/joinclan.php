<?php 
$sth = $db->prepare("SELECT clanid, factionid
 FROM users WHERE id = :id LIMIT 1");
$sth->execute(array(
				':id' => $_SESSION['player_id']
			));
$datauser = $sth->fetchAll();

if($datauser[0]['clanid'] != 0)
{
	header("Location: view.php?page=clan&tab=claninfos");
	exit();
}

$company_id = $datauser[0]['factionid'];

$sth = $db->prepare("SELECT * FROM clan WHERE clan_company = $company_id");
$sth->execute();
$clansdata = $sth->fetchAll();

$sth = $db->prepare("SELECT * FROM clan_request WHERE player_id = :player_id");
$sth->execute(array(
	':player_id' => $_SESSION['player_id']
));
$user_requests = $sth->fetchAll();

$errors = array();	
$errors = handleJoinClanForm($db);

if (!empty($_GET['cancel']))
{
	handleCancel($db);
}

?>
<script src="views/userTabs/jquery.min.js"></script>
<div class="box" style="margin-left: 100px;">
	<div class="title">New Membership Request</div>
	<div id="clan-request">	
		<?php		
		if (sizeof($errors) > 0) 
		{
			echo '<div class="error">';
			echo '<p class="error">Error(s): <br>';
				foreach ($errors as $err_msg) {
					echo "&nbsp; &nbsp; - {$err_msg} <br>";
				}
				echo '</ul></p><br><br>';
			echo '</div>';
		}
		?>
		<form class="clan-form" action="view.php?page=clan&tab=joinclan" method="post">
				<ul>
					
					<b>Clan</b><li><input id="filter-clan" type="text" /><select id="clan-join-form-clan" name="clan-join-form-clan">
						<?php
						foreach($clansdata as $clan)	
						{
						?>
						<option value="<?=$clan['id']?>">[<?=$clan['clan_tag']?>] <?=$clan['clan_name']?></option>
						<?php
						}	
						?>					  
					  </select></li>
					<b>Request Message</b> (12-120 characters):<li> <textarea name="clan-join-form-message" rows=3 cols=40></textarea></li>
					<li><input name="clan-join-form-submit" type="submit" value="Request Membership" /></li>
				</ul>
		</form>	
	</div>
</div>	

<div class="box" style="margin-left: 100px;">
	<div class="title">Pending Requests</div>
	<div id="clan-pending-request">	
		<?php displayClanRequest($db, $user_requests) ?>
	</div>
</div>	

<script type="text/javascript">
	jQuery.fn.filterByText = function(textbox, selectSingleMatch) {
        return this.each(function() {
            var select = this;
            var options = [];
            $(select).find('option').each(function() {
                options.push({value: $(this).val(), text: $(this).text()});
            });
            $(select).data('options', options);
            $(textbox).bind('change keyup', function() {
                var options = $(select).empty().data('options');
                var search = $(this).val().trim();
                var regex = new RegExp(search,"gi");
              
                $.each(options, function(i) {
                    var option = options[i];
                    if(option.text.match(regex) !== null) {
                        $(select).append(
                           $('<option>').text(option.text).val(option.value)
                        );
                    }
                });
                if (selectSingleMatch === true && $(select).children().length === 1) {
                    $(select).children().get(0).selected = true;
                }
            });            
        });
    };

    $(function() {
        $('#clan-join-form-clan').filterByText($('#filter-clan'), false);
      $("select option").click(function(){
        alert(1);
      });
    });
</script>  

<?php 
function convertToNumericEntities($string) 
{
	$convmap = array(0x80, 0x10ffff, 0, 0xffffff);
	return mb_encode_numericentity($string, $convmap, "UTF-8");
}
function handleJoinClanForm($db)
{
	$errors = array();	
	if (empty($_POST['clan-join-form-submit']))
	{
		return $errors;
	}
	
	if (empty($_POST['clan-join-form-clan']))
	{
		$errors[] = "Clan selection required.";	
	}

	if (empty($_POST['clan-join-form-message']))
	{
		$errors[] = "Message required.";	
	}
	else if (strlen($_POST['clan-join-form-message']) > 120 || strlen($_POST['clan-join-form-message']) < 12) 
	{
		$errors[] = "Invalid Message (12-120 characters).";	
	}
	
	if (sizeof($errors) > 0) 
	{		
		return $errors;
	}
	
	$select = htmlentities($_POST['clan-join-form-clan']);
	$message = convertToNumericEntities(htmlentities($_POST['clan-join-form-message']));
	
	$sth = $db->prepare("SELECT * FROM clan WHERE id = :id");
	$sth->execute(array(
		':id' => $select			
	));
	$count = $sth->rowCount();
	if($count == 0)
	{
		$errors[] = "Selected clan does not exist.";	
	}
	
	$sth = $db->prepare("SELECT * FROM clan_request WHERE player_id = :player_id AND clan_id=:clan_id");
	$sth->execute(array(
		':player_id' => $_SESSION['player_id'],
		':clan_id' => $select	
	));
	$count = $sth->rowCount();
	if($count > 0)
	{
		$errors[] = "You already made a request for this clan";	
	}
	
	if (sizeof($errors) > 0) 
	{		
		return $errors;
	}
	else
	{
		$db->insert('clan_request', array(
			'player_id' => $_SESSION['player_id'],
			'clan_id' => $select,
			'message' => $message
			));		
		
		header("Location: view.php?page=clan&tab=joinclan");
		exit();
	}		
	return $errors;
}

function displayClanRequest($db, $user_requests)
{
	foreach($user_requests as $request)	
	{
		$id = $request['id'];
		$sth = $db->prepare("SELECT clan_tag,clan_name FROM clan WHERE id = :clan_id");
		$sth->execute(array(
			':clan_id' => $request['clan_id']
		));
		$clan_data = $sth->fetchAll();
		$clan_tag = $clan_data[0]['clan_tag'];
		$clan_name = $clan_data[0]['clan_name'];
		?>
				<div class="stat" style="margin-left:-20px;">
					<div class="stat-left">
						[<?=$clan_tag?>]
					</div>
					<div class="stat-right" >
						<?=$clan_name?>
						<a class="leftbutton" href="view.php?page=clan&tab=joinclan&cancel=<?=$id?>">
							Cancel
						</a>						
					</div>
				</div>
		<?php
	}
}
function handleCancel($db)
{
	$id = htmlentities($_GET['cancel']);
	$sth = $db->prepare("DELETE FROM clan_request WHERE id=:id AND player_id=:player_id");
	$sth->execute(array(':id' => $id, ':player_id' => $_SESSION['player_id']));

	header("Location: view.php?page=clan&tab=joinclan");
	exit();
}
?>