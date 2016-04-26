<?php
//session_start();
//header("P3P: CP=".$_SERVER["HTTP_HOST"]."");
function db_q ($query)
{
	$hostname="127.0.0.1";
	$username="root";
	$password="jj1216";
	$db_link=mysql_pconnect("$hostname","$username","$password");
	$db_name="VCReader";
	$charset=mysql_db_query($db_name,"SET NAMES 'utf8'",$db_link);
	$result=mysql_db_query($db_name,$query,$db_link);
	$get_error=mysql_error($db_link);
	if($get_error)
		echo  $get_error;
	else
		return $result;
}
function chk_login()
{
	if($_SESSION[user][id] && $_SESSION[user][pwd])
	{
		$mb_chk_q="select * from member where id='".$_SESSION[user][id]."' and pwd='".$_SESSION[user][pwd]."' and status='Y' ";
		$mb_chk_r=db_q($mb_chk_q);
		$num_r=r_size($mb_chk_r);
	}
	if(!$num_r)
	{
		header("Location: index.php");
	} 
}
function chk_admin()
{
session_start();
$username = $_SESSION["SETTING"]["username"];
if (! $username)
{
                header("Location: login.php");

}
}
function admin_chk($uid,$upwd)
{
	$upwd=str_replace(" ","",$upwd);
	$uid=str_replace(" ","",$uid);
	if($upwd and $uid)
	{
		$mb_chk_q="select * from admin where id='".$uid."' and pwd='".$upwd."' and status='Y' ";
		$mb_chk_r=db_q($mb_chk_q);
		$num_chk=r_size($mb_chk_r);
		$row_chk=get_data($mb_chk_r);   
		if($num_chk)
		{
			$_SESSION['SETTING']['id']=$row_chk['id'];
			$_SESSION['SETTING']['pwd']=$row_chk['pwd'];
			$_SESSION['SETTING']['oid']=$row_chk['oid'];
			$_SESSION['SETTING']['username']=$row_chk['cname'];
			if($user_type=="admin")
			{
				$_SESSION['SETTING']['user_type']="admin";
				$_SESSION['SETTING']['power']="admin";
			}
			else
			{
				$_SESSION['admin']['user_type']=$row_chk['worker_type'];
				$_SESSION['admin']['power']=$row_chk['worker_type'];
			}
			header("Location:products.php");
		}
		else
		{
			print "<script>alert('帳號或密碼不正確請重新再試一次');</script>";
		}
	}
}
function member_chk($uid,$upwd,$mac_address)
{
        $upwd=str_replace(" ","",$upwd);
        $uid=str_replace(" ","",$uid);
        if($upwd and $uid)
        {
                $mb_chk_q="select * from member where id='".$uid."' and pwd=md5('".$upwd."') and status='1' ";
                //$mb_chk_q="select * from member where id='".$uid."' and pwd='".$upwd."' and status='1' ";
                $mb_chk_r=db_q($mb_chk_q);
                $num_chk=r_size($mb_chk_r);
                $row_chk=get_data($mb_chk_r);
                if($num_chk)
                {
                        $_SESSION['user']['id']=$row_chk['id'];
                        $_SESSION['user']['pwd']=$row_chk['pwd'];
                        $_SESSION['user']['oid']=$row_chk['oid'];
$m_id=$row_chk['oid'];
$sql1="select oid from member_mac where m_id='$m_id' and mac_address='$mac_address'";

$list_r=db_q($sql1);
$num_r1=r_size($list_r);
        if(!$num_r1){
                $s_ind="insert into member_mac (oid,m_id,mac_address,mtime) values('','$m_id','$mac_address',now())";
                db_q($s_ind);
        }


	header('Location: /view.php?mac_address='.$mac_address);
                }
                else
                {
                        return '0';
                }
        }
}
function member_chk1($uid,$upwd,$url)
{
        $upwd=str_replace(" ","",$upwd);
        $uid=str_replace(" ","",$uid);
        if($upwd and $uid)
        {
//                $mb_chk_q="select * from member where id='".$uid."' and pwd=md5('".$upwd."') and status='1' and pwd!='@@@@' ";
                $mb_chk_q="select * from member where id='".$uid."' and pwd=md5('".$upwd."') and pwd!='@@@@' ";
                $mb_chk_r=db_q($mb_chk_q);
                $num_chk=r_size($mb_chk_r);
                $row_chk=get_data($mb_chk_r);
                if($num_chk)
                {
		if(!$url)$url="news.php";
			if($row_chk['status']==1){
                        $_SESSION['user']['id']=$row_chk['id'];
                        $_SESSION['user']['pwd']=$row_chk['pwd'];
                        $_SESSION['user']['oid']=$row_chk['oid'];
                        $_SESSION['user']['name']=$row_chk["first_name"];
                        header("Location:".$url);
			}else return '5';
			
                }
                else
                {
			return '0';

                }
        }
}


function logout()
{
	unset($_SESSION[admin]);
	unset($_SESSION[user]);
}
function get_data($list_r)
{
	$result=mysql_fetch_array($list_r);
	return $result;
}
function fix_str($string)
{
	$string=str_replace("'","''",$string);
	return $string;
}
function fix_url($string)
{
	$string=str_replace("http://","",$string);
	if($string)$string="http://".$string;
	return $string;
}
function r_size($list_r)
{
	$result=mysql_num_rows($list_r);
	return $result;
}
function q_num()
{
	$result=mysql_affected_rows();
	return $result;
}
function last_id()
{
	$result=mysql_insert_id();
	return $result;
}
function col_size($list_r)
{
	$result=mysql_num_fields($list_r);
	return $result;
}
function bk_string($string,$x,$y,$more)
{
	$string = strip_tags($string);
	$string = mb_strimwidth($string, $x, $y, $more, 'UTF-8');
	return $string;
}
function get_age($birth)
{
	$birth=strtotime($birth);
	$today=strtotime(date("Y-m-d"));
	$age=(int)(($today-$birth)/(24*60*60)/365);
	return $age;
}
function get_num($string,$num)
{
	$string=str_repeat("0",($num+ -1 - floor(log10($string)))).$string;
	return $string;
}
//刪除資料庫內容時，同時刪除該筆內容的檔案
function del_file($tb_name,$col_name,$sn,$s_path)
{
	if($sn)
	{
		$list_q="select ".$col_name." from ".$tb_name." where sn=".$sn." ";
		$list_r=db_q($list_q);
		$rs=get_data($list_r);
		$col_num=mysql_num_fields($list_r);
		for($i=0;$i<$col_num;$i++)
		{
			//$col_name=mysql_field_name($list_r,$i);
			$del_f=$rs[$i];
			if($del_f and file_exists($s_path."/".$del_f))
			{
				unlink($s_path."/".$del_f);
			}
		}
	}
}
function get_code($max,$num)
{
	$del_string="L,Q,O,U,V";
	if($max<$num)$max=$num+2;
	for($i=1,$passwd="";$i<=$max;$i++)
	{
		if($i<=$num)
		{
			$a=rand(0,9);
			$passwd.=$a;
		}
		else
		{
			$b=chr(rand(65,90));
			while(($b=="L" or $b=="Q" or $b=="O" or $b=="U" or $b=="V"))
			{
				$b=chr(rand(65,90));
			}
			$passwd.=$b;
		}
	}
	$pass_code=$passwd;
	$_SESSION[user][pass_code]=$pass_code;
	return $pass_code;
}
function chk_power($power)
{
	$apower=$_SESSION[admin][power];
	if($apower!=$power)
	{
	  $chk_power=strpos($power,$apower);
	  if(is_bool($chk_power) and !$chk_power)
		$chk_power=0; 
	  else 
		$chk_power=1;
	}
	else
		$chk_power=$power;
	if(!$chk_power)
	{
		print "<script>alert('您沒有管理本項資料的權限');</script>";
		header("Location: no_power.htm");
		die("您沒有管理本項資料的權限");
	}
}
function instr($str,$chk_str)
{
  $chk_status=strpos($str,$chk_str);
  if($chk_status===false)
  	return 0;
  else
	  return $chk_status;
}
function repeat_str($str,$x,$first,$end)
{
	$mid=$first+$end;
	$str=substr($str,0,$first).str_repeat($x,strlen($str)-$mid).substr($str,strlen($str)-$end,$end);
	return $str;
}
for(reset($_REQUEST);$k2=key($_REQUEST);next($_REQUEST))
{
	${$k2}=str_replace("'","''",$_REQUEST[$k2]);
	//${$k2}=mysql_real_escape_string($_REQUEST[$k2]);
}
@defined("YOUR_SITE_URL","http://www.kizipad.com/");
@defined("APP_ID","168019063394729");
@defined("APP_SEC","eabda76112659ef2c50c68b13c17deb5");
function age($Time) {
$n1=date("Y-m-d",$Time/1000);
$d_array=explode("-",$n1);
//$UTime = $Time;
//   $age = date('Y') - date('Y',$UTime);
   $age = date('Y') - $d_array[0];
//     if(date('m') - date('m',$UTime) < 0){ // 月相減為 負值 沒超過生日
     if(date('m') - $d_array[1] < 0){ // 月相減為 負值 沒超過生日
       $age = $age - 1;
//       $age_m=12-date('m',$UTime)+date('m');
       $age_m=12-$d_array[1]+date('m');
//     } else if(date('m') == date('m',$UTime) && date('d') - date('d',$UTime) < 0){ // 同月並且日相減為 負值 沒超過生日
     } else if(date('m') == $d_array[1] && date('d') - $d_array[2] < 0){ // 同月並且日相減為 負值 沒超過生日
       $age = $age - 1;
       $age_m=11;
     }else
        $age_m=date('m') - $d_array[1];
//        $age_m=date('m') - date('m',$UTime);
     if($age < 0){ // 判斷年齡為負值,表示為今年出生為零歲,修正負值為0
       $age = 0;
     }
$r_age["Y"]=$age;
$r_age["M"]=$age_m;
return $r_age;
//   return $age;
 }
//$Mid_value="請提供";
$Mid_value="2789";

//set_time_limit(30);
//ini_set("display_errors",true);
//ini_set("error_reporting",E_ALL & ~E_NOTICE);
//error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(0);//不輸出所有的erro
//php5.1.1 for win 要指定時區，不然可能會錯
//date_default_timezone_set('Asia/Taipei');
//$sys_forder="/seo";
//$web="http://".$_SERVER["HTTP_HOST"].$sys_forder;
?>
