<div class="height-10"></div>

<form id="fdetail" method="post" action="gatein_saving">
	<input type="hidden" name="kodeBooking" value="<?php echo $kodeBooking?>" />
	<input type="hidden" name="noCnt" value="<?php echo $noCnt?>" />	  
		   
    <div class="w3-row-padding">	    
	    <div class="w3-quarter">Hamparan Name</div>
		<div class="w3-quarter">
			<select name="location" class="w3-select w3-border">
		  
			<?php
				$query = "Select * From m_Location Order By locationDesc ";
				$result = mssql_query($query);
				while($arr=mssql_fetch_array($result)) 
				{ 
					echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'</option>';
				}
				mssql_free_result($result);
			?>
  
			</select>
        </div>
		<div class="w3-quarter"></div>
		<div class="w3-quarter"></div>
	</div>	
	<div class="height-5"></div>
	  
    <div class="w3-row-padding">
		<div class="w3-quarter">Ex. Vessel</div>
		<div class="w3-quarter">   
			<input type="text" class="w3-input w3-border" name="vesselName" maxlength="61" value="<?php echo $vesselName?>" style="text-transform:uppercase" />
		</div>	 
		<div class="w3-quarter"></div>
		<div class="w3-quarter"></div>
    </div>
    <div class="height-5"></div>   
	 
    <div class="w3-row-padding">
		<div class="w3-quarter">Voyage Number</div>
		<div class="w3-quarter">
			<input type="text" class="w3-input w3-border" name="voyageNo" maxlength="50" value="<?php echo $voyageNo?>" style="text-transform:uppercase" />
		</div>	 
		<div class="w3-quarter"></div>
		<div class="w3-quarter"></div>
    </div>	
    <div class="height-5"></div>   
	  
	<div class="w3-row-padding">
		<div class="w3-quarter">DTTM In Hamparan</div>
		<div class="w3-quarter">
			<input class="w3-input w3-border" type="date" name="eventDate" id="fDate" required value="<?php echo date("Y-m-d")?>" title="Year-Month-Date"  />			  
		</div>
		<div class="w3-quarter"></div>
		<div class="w3-quarter"></div>
	</div>	
    <div class="height-5"></div>		
        
	<div class="w3-row-padding">  
	    <div class="w3-quarter">Unit Size</div>
		<div class="w3-quarter">
			<select name="contSize" class="w3-select w3-border">
           
		    <?php
				if ($size != '') 
				{
					if($size == "20") {echo '<option selected value="20">&nbsp;20&nbsp;</option>';}
					else {echo '<option value="20">&nbsp;20&nbsp;</option>';}
					if($size == "40") {echo '<option selected value="40">&nbsp;40&nbsp;</option>';}
					else {echo '<option value="40">&nbsp;40&nbsp;</option>';}
					if($size == "45") {echo '<option selected value="45">&nbsp;45&nbsp;</option>';}
					else {echo '<option value="45">&nbsp;45&nbsp;</option>';}	  
				}
				else 
				{
					echo '<option value="20">&nbsp;20&nbsp;</option>
						  <option value="40">&nbsp;40&nbsp;</option>
	                      <option value="45">&nbsp;45&nbsp;</option>';
				}
            ?>
		  
			</select>
		</div>
		<div class="w3-quarter"></div>
		<div class="w3-quarter"></div>
	</div>
	<div class="height-5"></div>

    <div class="w3-row-padding">	  
		<div class="w3-quarter">Unit Type</div>
		<div class="w3-quarter">
           <select name="contType" class="w3-select w3-border">
			   
				<?php
					if ($tipe != '') 
					{
						if($tipe == "GP") {echo '<option selected value="GP">&nbsp;GP&nbsp;</option>';}
						else {echo '<option value="GP">&nbsp;GP&nbsp;</option>';}
						if($tipe == "OT") {echo '<option selected value="OT">&nbsp;OT&nbsp;</option>';}
						else {echo '<option value="OT">&nbsp;OT&nbsp;</option>';}
						if($tipe == "OS") {echo '<option selected value="OS">&nbsp;OS&nbsp;</option>';}
						else {echo '<option value="OS">&nbsp;OS&nbsp;</option>';}
						if($tipe == "FR") {echo '<option selected value="FR">&nbsp;FR&nbsp;</option>';}
						else {echo '<option value="FR">&nbsp;FR&nbsp;</option>';}
						if($tipe == "TW") {echo '<option selected value="TW">&nbsp;TW&nbsp;</option>';}
						else {echo '<option value="TW">&nbsp;TW&nbsp;</option>';}
						if($tipe == "RF") {echo '<option selected value="RF">&nbsp;RF&nbsp;</option>';}
						else {echo '<option value="RF">&nbsp;RF&nbsp;</option>';}
						if($tipe == "TK") {echo '<option selected value="TK">&nbsp;TK&nbsp;</option>';}
						else {echo '<option value="TK">&nbsp;TK&nbsp;</option>';}
						if($tipe == "VT") {echo '<option selected value="VT">&nbsp;VT&nbsp;</option>';}
						else {echo '<option value="VT">&nbsp;VT&nbsp;</option>';}
						if($tipe == "BK") {echo '<option selected value="BK">&nbsp;BK&nbsp;</option>';}
						else {echo '<option value="BK">&nbsp;BK&nbsp;</option>';}
						if($tipe == "OTH") {echo '<option selected value="OTH">&nbsp;OTW&nbsp;</option>';}
						else {echo '<option value="OTH">&nbsp;OTH&nbsp;</option>';}	  
					}
					else 
					{
						echo '<option value="GP">&nbsp;GP&nbsp;</option>
							  <option value="OT">&nbsp;OT&nbsp;</option>
							  <option value="OS">&nbsp;OS&nbsp;</option>
							  <option value="FR">&nbsp;FR&nbsp;</option>
							  <option value="TW">&nbsp;TW&nbsp;</option>
							  <option value="RF">&nbsp;RF&nbsp;</option>
							  <option value="TK">&nbsp;TK&nbsp;</option>
							  <option value="VT">&nbsp;VT&nbsp;</option>
							  <option value="BK">&nbsp;BK&nbsp;</option>
							  <option value="OTH">&nbsp;OTH&nbsp;</option>';
					}
				?>
	
			</select>		
		</div>
		<div class="w3-quarter"></div>
		<div class="w3-quarter"></div>
	</div>
	<div class="height-5"></div>
	  
	<div class="w3-row-padding">
	    <div class="w3-quarter">Unit Height</div>
		<div class="w3-quarter">
			<select name="contHeight" class="w3-select w3-border">

				<?php
					if ($height != '') 
					{
						if($height == "STD") {echo '<option selected value="STD">&nbsp;STD&nbsp;</option>';}
						else {echo '<option value="STD">&nbsp;STD&nbsp;</option>';}	  
						if($height == "HC") {echo '<option selected value="HC">&nbsp;HC&nbsp;</option>';}
						else {echo '<option value="HC">&nbsp;HC&nbsp;</option>';}	  
						if($height == "OTH") {echo '<option selected value="OTH">&nbsp;OTH&nbsp;</option>';}
						else {echo '<option value="OTH">&nbsp;OTH&nbsp;</option>';}	  	  
					}
					else 
					{
						echo '<option value="STD">&nbsp;STD&nbsp;</option>
							  <option value="HC">&nbsp;HC&nbsp;</option>
							  <option value="OTH">&nbsp;OTH&nbsp;</option>';
					}				
				?>	
		  
			</select>
		</div>
		<div class="w3-quarter"></div>
		<div class="w3-quarter"></div>
	</div>	
    <div class="height-5"></div>		
	  
	<div class="w3-row-padding">
	    <div class="w3-quarter">Manufacture (Month/Year)</div>
		<div class="w3-quarter">
			<input class="w3-input w3-border" type="text" name="mnfr" maxlength="10" value="<?php echo $mnfr?>" />  		  
		</div> 
		<div class="w3-quarter"></div>
		<div class="w3-quarter"></div>
	</div>
	<div class="height-5"></div>
	  
	<div class="w3-row-padding">
	    <div class="w3-quarter">Construction</div>
		<div class="w3-quarter">
			<select name="constr" class="w3-select w3-border">

				<?php
					if ($constr != '') 
					{
						if($constr == "STL") {echo '<option selected value="STL">&nbsp;STL&nbsp;</option>';}
						else {echo '<option value="STL">&nbsp;STL&nbsp;</option>';} 
						if($constr == "AL") {echo '<option selected value="AL">&nbsp;AL&nbsp;</option>';}
						else {echo '<option value="AL">&nbsp;AL&nbsp;</option>';} 
						if($constr == "FRP") {echo '<option selected value="FRP">&nbsp;FRP&nbsp;</option>';}
						else {echo '<option value="FRP">&nbsp;FRP&nbsp;</option>';} 	  
					}
					else 
					{	
						echo '<option value="STL">&nbsp;STL&nbsp;</option>
							  <option value="AL">&nbsp;AL&nbsp;</option>
							  <option value="FRP">&nbsp;FRP&nbsp;</option>';
					}
				?>
	
			</select>
  	    </div> 		
        <div class="w3-quarter"></div>
        <div class="w3-quarter"></div>
    </div>
    <div class="height-5"></div>

    <div class="w3-row-padding">
        <div class="w3-quarter">Ventilation</div>	  
		<div class="w3-quarter">
			<input class="w3-input w3-border" type="text" name="vent" onkeypress="return isNumber(event)" maxlength="1" value="1" required />  
		</div> 			
		<div class="w3-quarter"></div>
		<div class="w3-quarter"></div>
	</div>			
	<div class="height-5"></div> 
		
    <div class="w3-row-padding">
        <div class="w3-quarter">Shipping Line/Principle (MLO)</div>
		<div class="w3-quarter">
			<select name="mlo" class="w3-select w3-border">
			
				<?php
					$subquery = "Select custRegID, completeName From m_Customer Where asMLO=1 Order By completeName ";
					$hasilquery = mssql_query($subquery);
					while($arr = mssql_fetch_array($hasilquery)) 
					{
						if($principle == $arr['custRegID']) {echo '<option selected value="'.$arr[0].'">&nbsp;'.$arr[1].'&nbsp;</option>';}	
						else {echo '<option value="'.$arr["custRegID"].'">&nbsp;'.$arr["completeName"].'&nbsp;</option>';}
					}	
					mssql_free_result($hasilquery);
				?>
				
			</select>
	    </div>
		<div class="w3-quarter"></div>
		<div class="w3-quarter"></div>
	</div>
	<div class="height-5"></div>
	  
	<div class="w3-row-padding">	  
	    <div class="w3-quarter">Ex. User</div>
		<div class="w3-quarter">
			<select name="consignee" class="w3-select w3-border">
				<option value="">&nbsp;</option>
				<?php
					$subquery = "Select custRegID, completeName From m_Customer Where asExp=1 Or asImp=1 Order By completeName ";
					$hasilquery = mssql_query($subquery);
					while($arr = mssql_fetch_array($hasilquery)) 
					{
						if($consignee == $arr['custRegID']) {echo '<option selected value="'.$arr[0].'">&nbsp;'.$arr[1].'&nbsp;</option>';}	
						else {echo '<option value="'.$arr[0].'">&nbsp;'.$arr[1].'&nbsp;</option>';}
					}	
					mssql_free_result($hasilquery);	   
				?>
			</select>
	    </div>
		<div class="w3-quarter"></div>
		<div class="w3-quarter"></div>
    </div>
				
	<div class="height-20"></div>	
	<div class="w3-row-padding">
		<div class="w3-third">
			<div class="w3-row-padding">
				<div class="w3-third"><button type="submit" class="button-blue">Save</button></div>
					<div class="w3-third"><input type="button" class="button-blue" onclick="doreset_inhamparan()" value="Discharge" /></div>
					<div class="w3-third"></div>
				</div>	
			<div class="w3-twothird"></div>
		</div>
	</div>	