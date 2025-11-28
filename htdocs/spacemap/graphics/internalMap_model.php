<?php

class InternalMap_Model extends Model
{

	public function __construct()
	{
		parent::__construct();
	}

	function generate_sid()
	{
		$sid = sha1(rand(1000, 9999));
		$req = $this->db->prepare('UPDATE users SET AuthTicket=:sid WHERE id=:id');
		$req->execute(array('sid' => $sid, 'id' => Session::get('account_ID')));

		return $sid;
	}

	function IsBanned()
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$sth = $this->db->prepare("SELECT reason_text, timestamp_expire FROM bans WHERE user_id = :id OR remote_address = :ip");
		$sth->execute(array(':id' => Session::get('account_ID'), ':ip' => $ip));

		$data2 = $sth->fetchAll();
		$count = $sth->rowCount();
		$date = date_create();

		if ($count > 0)
		{
			if(date_timestamp_get($date) < $data2[0]['timestamp_expire'])
			{
				echo "You are banned for : ".$data2[0]['reason_text'].".";
				return true;
			}
		}
		return false;
	}
}

?>