<?php
$res_id =  $_REQUEST['id'];
$sval = Yii::app()->session['taxcontrol'];
if(isset($sval) && $sval=='ON') $gst_show = " AND gst_show > 0";
else $gst_show = "";
$user_id = yii::app()->user->id;
$user_info = User::model()->findAll("id=$user_id");
foreach($user_info as $u_info){ $user_name = $u_info['username']; }
$branch_id = yii::app()->user->branch_id;	 
$branch_info = HmsBranches::model()->findAll("branch_id=$branch_id");
 foreach($branch_info as $b_info){
	 $branch_address = $b_info['branch_address'];
	 $branch_phone = $b_info['branch_phone'];
	 $branch_fax = $b_info['branch_fax'];
	 $branch_email = $b_info['branch_email'];
	 $hotel_id = $b_info['hotel_id'];
	 $ntn_no = $b_info['ntn_no'];
	 $gst_no = $b_info['gst_no'];
	 		
			$hotel_info = HotelTitle::model()->findAll("id=$hotel_id");
 			foreach($hotel_info as $h_info){
	 		$hotel_title = $h_info['title'];
			$hotel_website = $h_info['website'];
			$hotel_logo_image = $h_info['logo_image'];
	 		}
	 }
$branch_address .= "<br> Phone: $branch_phone Fax: $branch_fax <br> email: $branch_email";
/////////////////////////
$folo_no =  $_REQUEST['id']; $payment_mode = "";
$result = GuestLedger::model()->findAll("chkin_id=$folo_no order by id ASC"); 
$chkin_info = CheckinInfo::model()->findAll("chkin_id=$folo_no");
 foreach($chkin_info as $rs){
	 $guest_id = $rs['guest_id'];
	 $chkin_date = $rs['chkin_date'];
	 $chkout_date = $rs['chkout_date'];
	 $total_days = $rs['total_days'];
	 $total_person = $rs['total_person'];
	 $prev_night = $rs['prev_night'];
	 
	 $guest_folio_no = $rs['guest_folio_no'];
	 $gst_folio_no = $rs['gst_show'];	 
	 	
	 
	 $cash = $rs['cash'];
	 $debit_card = $rs['debit_card'];
	 $credit_card = $rs['credit_card'];
	 $btc = $rs['btc'];
	 
	 if($cash=="Y") $payment_mode .= "Cash";
	 if($debit_card=="Y") $payment_mode .= "/DC";
	 if($credit_card=="Y") $payment_mode .= "/CC";
	 if($btc=="Y") $payment_mode .= "/BTC";
	 
	 $payment_mode = ltrim($payment_mode,"/");
	 
	 $company_name = Company::model()->find("comp_id = ".$rs['guest_company_id'])->comp_name;		
	 $room_name = RoomMaster::model()->find("mst_room_id = ". $rs['room_id'])->mst_room_name;
	 
	 $guest_info = 	GuestInfo::model()->findAll("guest_id=$guest_id");
	 	foreach($guest_info as $r){
			$salutation_id = $r['guest_salutation_id'];
			$salutation_name= 	Salutation::model()->findAll("salutation_id=". $r['guest_salutation_id'])->salutation_name;			
			$guest_name = $r['guest_name'];
			$guest_address = $r['guest_address'];
			$guest_phone = $r['guest_phone'];
			$guest_mobile = $r['guest_mobile'];	
			$guest_email = $r['guest_email'];		
			
		}
}
$sql = "select MIN(id) from hms_guest_ledger gl where gl.chkin_id = ".$_REQUEST['id'];
$gid = Yii::app()->db->createCommand($sql)->queryScalar();

$sql = "select c_time from hms_guest_ledger gl where gl.id = ".$gid;
$c_time = Yii::app()->db->createCommand($sql)->queryScalar();
if($c_time  == '00:00:00') $c_time = '';



if($prev_night=='Y')  $prev_night="Yes";
else $prev_night="No";
$formatted_folio_no = date('dmy-').$room_name;
if(empty($gst_show))	{ $folio_no =  $guest_folio_no; }
else $folio_no = $gst_folio_no;
$arrayAuthRoleItems = Yii::app()->authManager->getAuthItems(2, Yii::app()->user->getId());
$arrayKeys = array_keys($arrayAuthRoleItems);
$role = strtolower ($arrayKeys[0]);
if($role =='auditor')  $folo_no =  $folio_no = str_pad((int) $folio_no,"5","0",STR_PAD_LEFT); 
else 	$folo_no = str_pad((int)  $_REQUEST['id'],"5","0",STR_PAD_LEFT); //$formatted_folio_no;
?>
<style>
td{
	font-size:14px !important;
	line-height:150%;	
}
</style>
<div class="container_mce">
<table width="100%" border="0" align="center">
  <tr>
    <td width="8%" rowspan="3">&nbsp;</td>
    <td width="16%" rowspan="3"><img src="<?php echo Yii::app()->request->baseUrl; ?>/hotel_logos/<?php echo $hotel_logo_image;?>"  /></td>
    <td colspan="3" align="center">&nbsp;<font style="font-family:'Book Antiqua'; font-size:36px; "><strong><?php echo "LE - ROYAL"; //ucwords($hotel_title); ?></strong></font></td>
    
    <td width="19%"><strong><!--N.T.N--></strong></td>
    <td width="18%"><b><!--1456040-2--></b></td>
  </tr>
  <tr>
    <td colspan="3" align="center"><strong><?php echo "The Guest House <br /> Islamabad"; //ucwords($branch_address); ?></strong></td>
    
    <td valign="top"><strong><!--G.S.T--></strong></td>
    <td valign="top"><b><!--07-01-9801-020-28--></b></td>
  </tr>
  <tr>    
    <td colspan="3" align="center"><strong></strong></td>
    
     <td><strong> <?php //if($role !='auditor') echo "Date:"; ?></strong></td>
    <td><?php //if($role !='auditor') echo date('d/m/y H:i:s');?></td>
  </tr>
</table>
<h2 align="center"> <?php echo "GST Reg. No".$gst_no. " NTN No: ". $ntn_no; ?></h2>

 <!-- <div style="width:100%;; border-bottom: solid 1px #000; margin:5px 0;"></div>-->
 <table width="100%" border="1" align="center" style="margin-top:13px;">     
      <tr>
       <td><strong>ROOM #</strong></td>
        <td><?php echo $room_name; ?></td>      
        <td ><strong>BILL NO</strong></td>
        <td ><?php echo $folo_no;?></td>
      </tr>
      <tr>
        <td><strong>GUEST NAME</strong></td>
        <td><?php echo $salutation_name.strtoupper(strtolower($guest_name));?></td>        
        <td ></td>
        <td ></td>
      </tr>
      <tr>
      	 <td><strong>PERSON</strong></td>
        <td><?php echo $total_person;?></td>              
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td><strong>Arrival</strong></td>
        <td><?php  echo date("d/m/y",strtotime($chkin_date)). " ".$c_time;?></td>  
        <td><strong>DEPARTURE</strong></td>
        <td><?php  echo date("d/m/y",strtotime($chkout_date)); ?></td>
      </tr>
      <tr>
        <td><strong>COMPANY</strong></td>
        <td><?php echo $company_name;?></td>     
        <td> <strong>M.O.P</strong> </td>
        <td><?php echo $payment_mode; ?></td>
      </tr>
        <tr>
        <td><strong>ADDRESS</strong></td>
        <td><?php echo ucwords($guest_address);?></td>  
        <td> <strong>STAY DAYS</strong> </td>
        <td><?php echo $total_days." Night(s)";?></td>
      </tr>
      
    </table>
<table width="100%" border="0" align="center"> 
  	<tr class="tr_border">
            <td ><strong></strong></td>
            <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><strong>Date</strong></td>            
            <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><strong>Service</strong></td>            
            <td  class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td ><strong>Price</strong></td>
            <td  class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td ><strong>GST</strong></td>
            <td  class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td ><strong>Total</strong></td>
            <td  class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
            <td><strong>Balance</strong></td>
            <td  class="td_border">&nbsp;</td>
  </tr>
  <?php
  $h=0; $gst_total = 0; $balance =0; $t_dr =0; $t_cr=0;
  $gst_rate = ServiceGst::model()->find("branch_id = $branch_id")->gst_percent;
 
  foreach($result as $row){
	$h++;
	$gst =0; $amount = 0; $total = 0;  
				
	$S_name = Services::model()->find("service_id = ".$row['service_id'])->service_description;
	$service_code = Services::model()->find("service_id = ".$row['service_id'])->service_code;		
	$remarks = $row['remarks'];
	$sr_date = $row['c_date'];
	
	$dr = $row['debit'];
	if($dr==0){$dr=0;}else{$dr = $row['debit']; $amount = $dr; $t_dr += $dr; }
	$cr = $row['credit'];
	if($cr==0){$cr=0;}else{$cr = $row['credit']; $amount = $cr;   }
	$company_overdue = $row['balance'];	
	
	if($S_name!="ROOM RENT" && $S_name!="GST" && $S_name!="BED TAX" && $S_name!="Payment" && $S_name!="PAID" && $S_name!="ADVANCE" && $cr==0){
		$raw_amount = round(($amount*100 / (100 + $gst_rate)));
		$gst = $amount - $raw_amount;	
		$total = $amount;
		$amount = $raw_amount;
	}
	else $total = $amount;	
	if($S_name=="GST")	{$gst = $amount; $amount = 0;}	
				
	if($cr==0){	$balance += $dr; $total_charges += $amount;	 }
	else{ $balance -= $cr; $total_charges -= $amount;	}
	
	$total_balance  =+ $balance;	
	$gst_total += $gst;
  ?>
  <tr>
    <td><?php //echo $h;?></td>
    <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><?php echo date("d/m/y",strtotime($sr_date));?></td>    
     <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><?php echo $S_name;?></td>     
    
    <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><?php 
	if($dr==0)$amount = "<b>". $amount ."</b>"; 
	echo $amount;
	
	?></td>
    <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><?php echo $gst;?></td>
    <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><?php 
	if($dr==0){ $cr =  -1 * abs($cr);  $total = "<b>". $cr ."</b>"; }
	echo $total	;?></td>
    <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td><?php  echo number_format($balance, 2, '.', ','); ?></td>
    <td  class="td_border">&nbsp;</td>
  </tr>
  <?php
  }
  $limit=0;
  if($h<17){  $limit = 17-$h;}
  for($j=1;$j<=$limit;$j++){
  ?>
  <tr>
    <td>&nbsp;</td>
    <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;</td>    
     <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;</td>
    <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   <td>&nbsp;</td>
     <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td >&nbsp;</td>    
    <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;</td>  
    <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;</td>  
     <td  class="td_border">&nbsp;</td>  
  </tr>
  <?php
  }
  ?>
  <tr>
   <td>&nbsp;</td>
   <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   <td>&nbsp;</td>   
   <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   <td>&nbsp;</td>
   <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   <td>&nbsp;</td>
   <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
   <td >&nbsp;</td>     
    <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;</td> 
    <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>&nbsp;</td>
     <td  class="td_border">&nbsp;</td>        
  </tr>
  
  <tr class="tr_border">
    <td colspan="5" align="center">&nbsp;<b>Grand Total (In Rs)</b></td>    
    <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td colspan="1"><strong> <?php echo number_format($total_charges, 2, '.', ','); ?> </strong></td>
    <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td colspan="1"> <strong><?php echo number_format($gst_total, 2, '.', ','); ?> </strong></td>
    <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td colspan="1"><strong><?php echo number_format($total_balance, 2, '.', ',');?></strong></td>
   <td class="td_border">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td colspan="1"><strong><?php  echo number_format($total_balance, 2, '.', ','); ?></strong></td>
   <td>&nbsp;</td>  
  </tr>
</table>

    <h5 align="center" style="font-size:14px">&nbsp; * I Agree that liability for my bill is not waved and agree to be held personally liable in the event that the indicated party fails to pay any part of the full amount of the charges incurred. </h5></td>
  
   <table width="100%" border="0" align="center">
  
  <tr>
    <td height="10" colspan="3"><hr /></td>
  </tr>
</table>
  
  <table width="100%" border="0" align="center">
  <tr>
	<td height="40"><strong>Cashier Signature___________</strong></td>
   
    <td height="40" align="right"><strong>Guest Signature_________________</strong></td>
  </tr>
</table>

<h2 align="center"><?php echo $branch_address; ?></h2>

<!-- <div style="width:100%;; border-bottom: solid 1px #000; margin:5px 0;"></div> -->


<p align="center" style="font-size:9px"><!--99-E Jinnah Avenue, Blue Area, Islamabad. Phone: +92-51-2277890 Fax: +92-51-2273967/2827180. Email: Info@hotelcrownplaza.com-->  Elodger Application Developed By <a href="http://www.maaliksoft.com">www.maaliksoft.com</a>, Contact No:  +92 051 2287033</p>
<p id="printit" align="center" style="padding-right:10px;"> <input type="button" value="Print" onclick="javascript:hide();  window.print(); " />    </p>  
</div>
  
</div>
<script>function hide(){ document.getElementById('printit').style.display = "none";}</script>
