<?php
	include "pages/connectdb.php";
	require_once('pages/mpdf/mpdf.php');

	ob_start();
?>

<style type="text/css">
	<!--
		@page rotated { size: landscape; }
			p{
				font-size: 16px;
				font-family: "THSarabunNew";
			}
			table{
				font-size: 16px;
				line-height: 40px;
			}
			table td,th{
				text-align: center;
				vertical-align: middle;
				border: 1px solid black; border-collapse: collapse;
			} 
			.C{
				text-align: center;
			}
			.L{
				padding-left: 50px;
				line-height: 36px;
			}
	-->
</style>

<?php
	$stylesheet = ob_get_contents();
	ob_end_clean();
?>

<?php
	function check_m($month_ot){
		$month = array('01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', 
					   '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', 
		               '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน ', 
		               '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม');
		return $month[$month_ot] ;
	}

	function wordsThai($num){   
		$num=str_replace(",","",$num);
		$num_decimal=explode(".",$num);
		$num=$num_decimal[0];
		$returnNumWord;   
		$lenNumber=strlen($num);   
		$lenNumber2=$lenNumber-1;   
		$kaGroup=array("","สิบ","ร้อย","พัน","หมื่น","แสน","ล้าน","สิบ","ร้อย","พัน","หมื่น","แสน","ล้าน");   
		$kaDigit=array("","หนึ่ง","สอง","สาม","สี่","ห้า","หก","เจ็ด","แปด","เก้า");   
		$kaDigitDecimal=array("ศูนย์","หนึ่ง","สอง","สาม","สี่","ห้า","หก","เจ็ด","แปด","เก้า");   
		$ii=0;   
			for($i=$lenNumber2; $i>=0; $i--){   
				$kaNumWord[$i]=substr($num,$ii,1);   
				$ii++;   
			}   
		$ii=0;   
			for($i=$lenNumber2; $i>=0; $i--){   
				if(($kaNumWord[$i]==2 && $i==1) || ($kaNumWord[$i]==2 && $i==7)){   
					$kaDigit[$kaNumWord[$i]]="ยี่";   
				}else{   
					if($kaNumWord[$i]==2){   
						$kaDigit[$kaNumWord[$i]]="สอง";        
					}   
					if(($kaNumWord[$i]==1 && $i<=2 && $i==0) || ($kaNumWord[$i]==1 && $lenNumber>6 && $i==6)){   
						if($kaNumWord[$i+1]==0){   
							$kaDigit[$kaNumWord[$i]]="หนึ่ง";      
						}else{   
							$kaDigit[$kaNumWord[$i]]="เอ็ด";       
						}   
					}elseif(($kaNumWord[$i]==1 && $i<=2 && $i==1) || ($kaNumWord[$i]==1 && $lenNumber>6 && $i==7)){   
						$kaDigit[$kaNumWord[$i]]="";   
					}else{   
						if($kaNumWord[$i]==1){   
							$kaDigit[$kaNumWord[$i]]="หนึ่ง";   
						}   
					}   
				}   
				if($kaNumWord[$i]==0){   
					if($i!=6){
						$kaGroup[$i]="";   
					}
				}   
				$kaNumWord[$i]=substr($num,$ii,1);   
				$ii++;   
				$returnNumWord.=$kaDigit[$kaNumWord[$i]].$kaGroup[$i];   
			}      
			if(isset($num_decimal[1])){
				$returnNumWord.="จุด";
				for($i=0;$i<strlen($num_decimal[1]);$i++){
					$returnNumWord.=$kaDigitDecimal[substr($num_decimal[1],$i,1)];  
				}
			}       
		return $returnNumWord;   
	}
?>

<?php
	$year = isset($_POST['txt_year']) ? mysqli_real_escape_string($conn, $_POST['txt_year']) : '';
	$month = isset($_POST['txt_month']) ? mysqli_real_escape_string($conn, $_POST['txt_month']) : '';
	if($year == '' || $month == ''){
		exit('<p style="color: red;">กรุณาระบุ เดือน/ปี อีกครั้ง</p><a href="date.php">ระบุ เดือน/ปี</a>');
	} 
?>

<?php
	$pdf = new mPDF('th', 'A3-L', '', 'THSarabunNew');
	ob_start();
?>

<html>
<br><br><br><br><br><br><br>

<table cellpadding="0" cellspacing="0">
	<?php
		date_default_timezone_set("Asia/Bangkok");
		$timeDate = strtotime($year.'-'.$month."-01");
		$lastDay = date("t", $timeDate);
	?>
	<tr>
		<th rowspan="2">ลำดับที่</th>
		<th rowspan="2" align="center" valign="middle">ชื่อ - นามสกุล</th>
		<th colspan="<? echo $lastDay; ?>">วันที่ปฏิบัติงาน</th>
		<th colspan="4">รวมเวลาปฏิบัติงาน</th>
		<th rowspan="2">จำนวนเงิน</th>
		<th rowspan="2">วันเดือนปีที่รับเงิน</th>
		<th rowspan="2">ลายมือชื่อผู้รับเงิน</th>
		<th rowspan="2">หมายเหตุ</th>
	</tr>
	<tr>
		<?php
			for ($day = 1; $day <= $lastDay; $day++){
				echo "<th style='width: 0.8cm'>".substr(" ".$day, -2)."</th>";
			}
		?>
		<th>วันปกติ</th>
		<th>วันละ</th>
		<th>วันหยุด</th>
		<th>วันละ</th>
	</tr>
	<tr>
		<?php
			$timeout = '19:45';
			$timeout2 = '15:45';
			$allEmpData = array();

			$page = 3;
			$perpage = 7;
			$start = ($page-1)*$perpage;

			$sql2 = "SELECT time.id_member, employee.name, employee.lastname, time.out FROM time LEFT OUTER JOIN employee ON employee.id_member=time.`id_member` WHERE `Date` LIKE '$year-$month%' AND status = 1 AND time.out > '$timeout' GROUP BY `id_member`  UNION SELECT time.id_member, employee.name, employee.lastname, time.out FROM time LEFT OUTER JOIN employee ON employee.id_member=time.`id_member` WHERE `Date` LIKE '$year-$month%' AND status = 0 AND time.out > '$timeout2' ";
			$result2 = $conn->query($sql2);
			$total_record = mysqli_num_rows($result2);
			$sql2.="LIMIT {$start},{$perpage} ";
			$result2 = $conn->query($sql2);
			while ($row2=$result2->fetch_array()) {
				$allEmpData[$row2['id_member']] = $row2['name']."&nbsp;&nbsp;".$row2['lastname'];
			}
			$run=1;
			foreach($allEmpData as $empCode=>$empName){
				$allReportData = array();

				$sql3 = "SELECT id_member, DAY(`Date`) AS Date_d , COUNT(*) AS num,status FROM `time` WHERE `Date` LIKE '$year-$month%' AND id_member = '".$empCode."' AND status = 1 AND `time`.`out` > '$timeout' GROUP BY id_member, DAY(`Date`)
				UNION
				SELECT id_member, DAY(`Date`) AS Date_d , COUNT(*) AS num,status FROM `time` WHERE `Date` LIKE '$year-$month%' AND id_member = '".$empCode."' AND status = 0 AND `time`.`out` > '$timeout2' GROUP BY id_member, DAY(`Date`)";
				$result3 = $conn->query($sql3);

				while ($row3=$result3->fetch_array()) {
					$ss=$allReportData[$row3['id_member']][$row3['Date_d']]  = $row3['status'];
				}

				$count_ot =0;
				$count_ot2 =0;
				for($j = 1; $j <= $lastDay; $j++){
					if($allReportData[$empCode][$j] != ''){
						$ot = $allReportData[$empCode][$j] == 1 ? 2 : '';
						$ot2 = $allReportData[$empCode][$j] == 0 ? 1 : '';
						$count_ot = $ot != '' ? $count_ot+=1 : $count_ot;
						$count_ot2 = $ot2 != '' ? $count_ot2+=1 : $count_ot2;
					}else{
						$ot ='';
						$ot2 ='';
					}
					$list.="<td>".$ot."</td>";
					$list2.="<td>".$ot2."</td>";
				}
				$price_ot  = 300;
				$price_ot2  = 600;
				if ($count_ot!=0) {
					$total_ot = $count_ot*$price_ot;
					$total_ot = number_format($total_ot);
				}
				if ($count_ot2!=0) {
					$total_ot2 = $count_ot2*$price_ot2;
					$total_ot2 = number_format($total_ot2);
				}
				$count_ot = $count_ot=='0' ? '' : $count_ot;
				$count_ot2 = $count_ot2=='0' ? '' : $count_ot2;
				$total_ot = $total_ot=='' ? '  -' : $total_ot;
				$total_ot2 = $total_ot2=='' ? '  -' : $total_ot2;
				for ($i=0; $i < $num_rows; $i++) { 
					$hour_ot = "4 ชม.";
					$hour_ot2 = "8 ชม.";
				}
				echo "<tr>
					<td>".$run."</td>
					<td width='200px' align='left'>".$empName."</td>
					".$list."
					<td>".$count_ot."</td>
					<td>".$price_ot."</td>
					<td></td>
					<td></td>
					<td>".$total_ot."</td>
					<td></td>
					<td></td>
					<td>".$hour_ot."</td>
				</tr>";
				echo "<tr>
					<td></td>
					<td></td>
					".$list2."
					<td></td>
					<td></td>
					<td>".$count_ot2."</td>
					<td>".$price_ot2."</td>
					<td>".$total_ot2."</td>
					<td></td>
					<td></td>
					<td>".$hour_ot2."</td>
				</tr>";

				$list ='';
				$list2 ='';
				$total_ot='';
				$total_ot2='';
				$t_cou += $count_ot;
				$t_cou2 += $count_ot2;
				$t_ot = $t_cou*300;
				$t_ot2 = $t_cou2*600;
				$total = $t_ot+$t_ot2;
				$total = number_format($total);
				
				$run++;
				if ($run==7) {
					$page++;
				}
			}
			echo "<tr>";
				for ($i=1; $i <= $lastDay; $i++) { 
					echo "<td style='border-style: none;'></td>";
				}
				echo"
					<td style='border-style: none;' colspan='2'>รวม</td>
					<td>".$t_cou."</td>
					<td></td>
					<td>".$t_cou2."</td>
					<td></td>
					<td>".$total."</td>
					<td></td>
					<td colspan=2 style='border-color: #fff;'></td>
					</tr>
				";

				$t_cou=0;
				$t_cou2=0;
			echo "</tr>"
		?>
	</table>


</html>

<?php
	$html = ob_get_contents();
	ob_end_clean();
	$pdf->SetTitle("ประจำเดือน ".check_m($month)." ".($year+543));
	$pdf->WriteHTML($stylesheet,1);
	$pdf->SetHTMLHeader('
		<div style="text-align: right;">{PAGENO}</div>
		<p class=C><b>หลักฐานการเบิกจ่ายเงินตอบแทนการปฏิบัติงานนอกเวลาราชการ</b></p>
		<p class=C><b>โรงพยาบาลราชวิถี&nbsp;&nbsp;กลุ่มงาน</b>.....เทคโนโลยีสารสนเทศ.....<b>งาน</b>.....ศุนย์คอมพิวเตอร์.....<b>ตำแหน่ง</b>.....นักวิชาการคอมพิวเตอร์.....</p>
		<p class=C><b>ประจำเดือน</b>.....'.check_m($month).'.....<b>พ.ศ.</b>.....'.($year+543).'.....<b>เวลา</b>..(1)...08.00 - 16.00 น....(2)...16.00 - 20.00 น......</p>
	');
	
	$pdf->SetHTMLFooter('
		<p class=L>รวมเป็นจำนวนเงินทั้งสิ้น &nbsp;(ตัวอักษร)...'.wordsThai($total).'บาทถ้วน......</p>
		<p class=L>ขอรับรองว่าผู้มีรายชื่อข้างต้นได้ปฏิบัติงานจริง และไม่ได้ใช้เวลาปฏิบัติงานปกติ มาปฏิบัติงานนอกเวลาราชการจริง</p><br>
		<p class= "L">ลงชื่อ............................................................ผู้รับรองการปฏิบัติงาน
		&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
		<span class=L>ลายมือชื่อ............................................................ผู้จ่ายเงิน</span></p>
		<p style="line-height: 30px; padding-left: 135px; bgcolor: red;">(นางสาวกชพร&nbsp;&nbsp;น้ำค้าง)</p>
		<p style="line-height: 40px; padding-left: 131px;">หัวหน้าศูนย์คอมพิวเตอร์</p><br><br>
	');
	$total_page = ceil($total_record / $perpage);
	for ($i=0; $i < 2; $i++) { 
		
		$pdf->AddPage();
		$pdf->WriteHTML($html, 2);
	}
	
	
	$pdf->Output();
?>