<?php
    /*
	* 计算两个时间间隔，并 显示为（1年前、1月前、1天前|昨天|前天 、1小时前、1分钟前）
	*/
	public function time_span($datetime){
		//header('content-type:text/html;charset=utf-8');
		//$datetime = '2015-03-22 12:55';
		$time = strtotime($datetime);
		
		$rtime = date("m-d H:i",$time);
		$htime = date("H:i",$time);
		
		$ytime = date("Y",$time);
		$mtime = date("m",$time);
		$dtime = date("d",$time);
		
		$time = time() - $time;
			  
		if ($time < 60){
			$str = '刚刚';
		}elseif ($time < 60 * 60){
			$min = floor($time/60);
			$str = $min.'分钟前';
		}elseif ($time < 60 * 60 * 24){
			$h = floor($time/(60*60));
			$str = $h.'小时前 ';
		}else{
			$d = floor($time/(60*60*24));
			$str = $d .'天前'.$rtime;
			
			$y = date('Y') - $ytime;
			$m = date('m') - $mtime;
			$d = date('d') - $dtime;
			
			if($y>0){
				$str = $y.'年前';
			}elseif($m>0){
				$str = $m.'月前';
			}elseif($d>0){
				if($d === 1){
					$str = '昨天';
				}elseif($d === 2){
					$str = '前天';
				}else{
					$str = $d.'天前';
				}
			}	
		}
		return $str;
	}
?>
