<!-- Created on : Nov 30, 2017 
  -->

<style>
.cust-li {
  height: 35px;
  float: left;
  margin-right: 0px;
  padding: 0 10px;
}
 
.cust-li a {
  text-decoration: none;
  display: block;
 
  -webkit-transition: font-size 0.3s ease, background-color 0.3s ease;
  -moz-transition: font-size 0.3s ease, background-color 0.3s ease;
  -o-transition: font-size 0.3s ease, background-color 0.3s ease;
  -ms-transition: font-size 0.3s ease, background-color 0.3s ease;
  transition: font-size 0.3s ease, background-color 0.3s ease;
}
 
.cust-li a:hover {
  font-size: 18px;
  background: #f6f6f6;
  color: #666;
}
 
.cust-li.active a {
  font-weight: bold;
  color: #333;
}

</style>
 

<div class="w3-container">  

  <div style="width:100%;padding:10px 10px 15px 10px;border:1px solid #f7f9f9;background:#fbfcfc">
    <div style="border-top:5px solid #a1cb2f;background:#fff;-moz-box-shadow: 0 2px 3px 0px rgba(0, 0, 0, 0.16);-webkit-box-shadow: 0 2px 3px 0px rgba(0, 0, 0, 0.16);
                box-shadow: 0 2px 3px 0px rgba(0, 0, 0, 0.16);padding:0 5px 15px 5px">
      <h3 style="padding:5px 0 5px 0;background:#2196F3;color:#fff">&nbsp;&nbsp;S L D</h3>				
	  	  
	  <div class="w3-container">
	    <ul style="list-style-type:none;font-size:13px">
	      <li class="cust-li">
		    <a href="/e-imp/mnr/page_template.php?dl=template" target="_blank" 
			   class="w3-button w3-text-blue w3-round-small" style="border:1px solid #e5e7e9">SLD Template File&nbsp;&nbsp;<i class="fa fa-download"></i></a></li>
		  <li class="cust-li">
		    <a href="/e-imp/mnr/?do=sld&page=loadsld" target="_blank" 
			   class="w3-button w3-text-blue w3-round-small" style="border:1px solid #e5e7e9">Load new SLD&nbsp;&nbsp;<i class="fa fa-upload"></i></a></li>
		  <li class="cust-li">
		    <a href="/e-imp/mnr/?do=sld&page=sld_log" target="_blank" 
			   class="w3-button w3-text-blue w3-round-small" style="border:1px solid #e5e7e9">Upload Log Table&nbsp;&nbsp;<i class="fa fa-chevron-right"></i></a></li>		  
	    </ul>
      </div>
	  
	  <div class="height-20"></div>
	  <h3 style="padding:5px 0 5px 0;background:#2196F3;color:#fff">&nbsp;&nbsp;Hamparan</h3>	
      
      <div class="w3-container">	  
	    <ul style="list-style-type:none;font-size:13px">
		  <li class="cust-li">
		    <a href="/e-imp/mnr/page_template.php?dl=hw_template" target="_blank" 
			   class="w3-button w3-text-blue w3-round-small" style="border:1px solid #e5e7e9">LHW Template File&nbsp;&nbsp;<i class="fa fa-download"></i></a></li>
			
		  <li class="cust-li">
		    <a href="/e-imp/mnr/?do=hWhsp&page=loadhw" target="_blank" 
			   class="w3-button w3-text-blue w3-round-small" style="border:1px solid #e5e7e9">Load new LHW&nbsp;&nbsp;<i class="fa fa-upload"></i></a></li>
        </ul>		
	  </div>
	  
	  <div class="height-30" style="border-bottom:1px solid #f1c40f"></div>

      <div class="w3-container">	  

	    <ul style="list-style-type:none;font-size:13px">
		  <li class="cust-li">
		    <a href="#" target="_blank" class="w3-text-orange">In Event</a></li>			
		  <li class="cust-li">
		    <a href="#" target="_blank" class="w3-text-orange">Out Event</a></li>
		  <li class="cust-li">
		    <a href="#" target="_blank" class="w3-text-orange">Overview</a></li>
		  <li class="cust-li">
		    <a href="#" target="_blank" class="w3-text-orange">Manage Hamparan</a></li>
			
        </ul>
	
	  </div>	  
	  <div class="height-10"></div>
	</div>
  </div> 
</div>

<script>
$("<select />").appendTo("nav");

// Create default option "Go to..."
$("<option />", {
   "selected": "selected",
   "value"   : "",
   "text"    : "Go to..."
}).appendTo("nav select");

// Populate dropdown with menu items
$("nav a").each(function() {
 var el = $(this);
 $("<option />", {
     "value"   : el.attr("href"),
     "text"    : el.text()
 }).appendTo("nav select");
});
</script>