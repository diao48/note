	<?php
	/*
	* 获取附近跑友
	*/
	public function nearby(){
		$latitude = 32.6;
		$longitude = 106;
		$sql = "select * from rt_member_token where latitude > $latitude-1 and latitude < $latitude+1 and longitude >$longitude-1 and longitude < $longitude+1 order by ACOS(SIN(($latitude * 3.1415) / 180 ) *SIN((latitude * 3.1415) / 180 ) +COS(($latitude * 3.1415) / 180 ) * COS((latitude * 3.1415) / 180 ) *COS(($longitude* 3.1415) / 180 - (longitude * 3.1415) / 180 ) ) * 6380 asc limit 10";
		
		$member_token = M('Member_token');
		$res = $member_token->query($sql);
		var_dump($res);
	}
