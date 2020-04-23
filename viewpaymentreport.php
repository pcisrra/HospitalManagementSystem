<?php
session_start();
include("dbconnection.php");
if(isset($_GET[delid]))
{
	$sql ="DELETE FROM billing_records WHERE billingid='$_GET[delid]'";
	$qsql=mysqli_query($con,$sql);
	if(mysqli_affected_rows($con) == 1)
	{
		echo "<script>alert('billing record deleted successfully..');</script>";
	}
}

?>
 <section class="container">
<?php
$sqlbilling_records ="SELECT * FROM billing WHERE appointmentid='$billappointmentid'";
$qsqlbilling_records = mysqli_query($con,$sqlbilling_records);
$rsbilling_records = mysqli_fetch_array($qsqlbilling_records);
?>
 	<table width="1713" border="3">
      <tbody>
        <tr>
          <th scope="col"><div align="right">Id de Facturación &nbsp; </div></th>
          <td><?php echo $rsbilling_records[billingid]; ?></td>
          <td>Id de cita &nbsp;</td>
          <td><?php echo $rsbilling_records[appointmentid]; ?></td>
        </tr>
        <tr>
          <th width="442" scope="col"><div align="right">Fecha de Facturación &nbsp; </div></th>
          <td width="413"><?php echo $rsbilling_records[billingdate]; ?></td>
          <td width="413">Hora de Facturación&nbsp; </td>
          	<td width="413"><?php echo $rsbilling_records[billingtime] ; ?></td>
        </tr>
         
		<tr>
          <th scope="col"><div align="right"></div></th>
          <td></td>
          <th scope="col"><div align="right">Monto &nbsp; </div></th>
          <td><?php
		$sql ="SELECT * FROM billing_records where billingid='$rsbilling_records[billingid]'";
		$qsql = mysqli_query($con,$sql);
		$billamt= 0;
		while($rs = mysqli_fetch_array($qsql))
		{
			$billamt = $billamt +  $rs[bill_amount];
		}
?>
  &nbsp;Bs. <?php echo $billamt; ?></td>
        </tr>
        <tr>
          <th width="442" scope="col"><div align="right"></div></th>
          <td width="413">&nbsp;</td>
          <th width="442" scope="col"><div align="right">Impuesto (5%) &nbsp; </div></th>
          <td width="413">&nbsp;Bs. <?php echo $taxamt = 5 * ($billamt / 100); ?></td>
       	</tr>
         
		<tr>
		  <th scope="col"><div align="right">Razón de Descuento</div></th>
		  <td rowspan="4" valign="top"><?php echo $rsbilling_records[discountreason]; ?></td>
		  <th scope="col"><div align="right">Descuento &nbsp; </div></th>
		  <td>&nbsp;Bs. <?php echo $rsbilling_records[discount]; ?></td>
	    </tr>
        
		<tr>
		  <th rowspan="3" scope="col">&nbsp;</th>
		  <th scope="col"><div align="right">Total &nbsp; </div></th>
		  <td>&nbsp;Bs. <?php echo $grandtotal = ($billamt + $taxamt)  - $rsbilling_records[discount] ; ?></td>
	    </tr>
		<tr>
		  <th scope="col"><div align="right">Monto pagado </div></th>
		  <td>Bs. <?php
		  	$sqlpayment ="SELECT sum(paidamount) FROM payment where appointmentid='$billappointmentid'";
			$qsqlpayment = mysqli_query($con,$sqlpayment);
			$rspayment = mysqli_fetch_array($qsqlpayment);
			echo $rspayment[0];		  
		   ?></td>
	    </tr>
		<tr>
		  <th scope="col"><div align="right">Balance Total</div></th>
		  <td>Bs. <?php echo $balanceamt = $grandtotal - $rspayment[0]  ; ?></td>
	    </tr>
      </tbody>
    </table>
   <p><strong>Reporte de Pago:</strong></p>
<?php
$sqlpayment = "SELECT * FROM payment where appointmentid='$billappointmentid'";
$qsqlpayment = mysqli_query($con,$sqlpayment);
if(mysqli_num_rows($qsqlpayment) == 0)
{
	echo "<strong>No se encontraron detalles de pago...</strong>";
}
else
{
?>
   <table width="840" border="3">
     <tbody>
       <tr>
         <th scope="col">Fecha de pagado</th>
         <th scope="col">Hora de pagaod</th>
         <th scope="col">Monto pagado</th>
       </tr>
<?php       
		while($rspayment = mysqli_fetch_array($qsqlpayment))
		{
		?>
			   <tr>
				 <td>&nbsp;<?php echo $rspayment[paiddate]; ?></td>
				 <td>&nbsp;<?php echo $rspayment[paidtime]; ?></td>
				 <td>&nbsp;Bs. <?php echo $rspayment[paidamount]; ?></td>
			   </tr>
		<?php
		}
?>

     </tbody>
   </table>
<?php
}
?>   
   <p><strong></strong></p>
</section>