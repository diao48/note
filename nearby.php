	<?php
	/*
	* 方法1
	* 获取附近跑友
	*/
	public function nearby1(){
		$latitude = 32.6;
		$longitude = 106;
		$sql = "select * from rt_member_token where latitude > $latitude-1 and latitude < $latitude+1 and longitude >$longitude-1 and longitude < $longitude+1 order by ACOS(SIN(($latitude * 3.1415) / 180 ) *SIN((latitude * 3.1415) / 180 ) +COS(($latitude * 3.1415) / 180 ) * COS((latitude * 3.1415) / 180 ) *COS(($longitude* 3.1415) / 180 - (longitude * 3.1415) / 180 ) ) * 6380 asc limit 10";
		
		$member_token = M('Member_token');
		$res = $member_token->query($sql);
		var_dump($res);
	}

	/*
	* 方法2 ： 推荐
	* 获取附近跑友
	*/
	public function nearby(){
		$latitude = 32;//维度
		$longitude = 106;//经度
		
		$distance = 1;//距离(千米) - 搜索附近 1000米之内的 好友
		/* 获取指定点 矩形周围 4 个点的经纬度 start*/
		$earth_radius = 6371;//地球半径
		$dlng =  2 * asin(sin($distance / (2 * $earth_radius)) / cos(deg2rad($latitude)));
		$dlng = rad2deg($dlng);
		
		$dlat = $distance/$earth_radius;
		$dlat = rad2deg($dlat);
		
		$squares = array(
			'left-top'=>array('latitude'=>$latitude + $dlat,'longitude'=>$longitude-$dlng),
			'right-top'=>array('latitude'=>$latitude + $dlat, 'longitude'=>$longitude + $dlng),
			'left-bottom'=>array('latitude'=>$latitude - $dlat, 'longitude'=>$longitude - $dlng),
			'right-bottom'=>array('latitude'=>$latitude - $dlat, 'longitude'=>$longitude + $dlng)
		);
		
		$right_bottom_latitude = $squares['right-bottom']['latitude'];
		$left_top_latitude = $squares['left-top']['latitude'];
		$left_top_logitude = $squares['left-top']['longitude'];
		$right_bottom_longitude = $squares['right-bottom']['longitude'];
		/* 获取指定点 矩形周围 4 个点的经纬度 end*/
		
		$prefix = C('DB_PREFIX');//表前缀
		$sql = "select uid,latitude,longitude from `{$prefix}member_token` where latitude<>0 and latitude>={$right_bottom_latitude} and latitude<={$left_top_latitude} and longitude>={$left_top_logitude} and longitude<={$right_bottom_longitude}";
		
		$model = M();
		$res = $model->query($sql);
		$list = empty($res)?array():$res;

		$uid_arr = array_map(function($v){
			return $v['uid'];
		},$list);
		
		$member = M('Member');
		$where['id'] = array('in',$uid_arr);
		$member_res = $member->field('id as uid,nickname,sex,avatar')->where($where)->select();
		$member_list = empty($member_res)?array():$member_res;
		
		foreach($member_list as $k=>$v){
			$member_list[$k]['uid'] = $v['uid'].'';
			$member_list[$k]['nickname'] = $v['nickname'].'';
			$member_list[$k]['sex'] = $v['sex'].'';
			$member_list[$k]['avatar'] = $v['avatar'].'';
		}
		
		$data['errno'] = "0";
		$data['count'] = count($member_list);
		$data['list'] = $member_list;
		
		ajax_return($data);
	}
