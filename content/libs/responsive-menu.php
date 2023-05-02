<ul id="gn-menu" class="gn-menu-main">
  <li class="gn-trigger"><a class="gn-icon gn-icon-menu"><span>Main</span></a>
    <nav class="gn-menu-wrapper">
	  <div class="gn-scroller">
	    <ul class="gn-menu">		 
		 <li><a href="?p=personal" class="gn-icon gn-icon-user">PERSONAL DATA</a></li>
		 <li><a href="?p=mdata" class="gn-icon gn-icon-cedex">MASTER DATA</a></li>
		 <li><a href="?tag=32" class="gn-icon gn-icon-repair">MAINTENANCE & REPAIR</a></li>		 
		 <li><a href="?p=fr3" class="gn-icon gn-icon-report">REPORT(s)</a></li>
		 
		 <script language="php">
		   if($_SESSION["uid"] != 'root') {
		    echo '<li><a href="?p=private" class="gn-icon gn-icon-report">PASSWORD REPLACEMENT</a></li>';
		   }
		 </script>  
		 
		 <li><a href="?eof=1" class="gn-icon gn-icon-shutdown">LOGOUT</a></li>
		</ul>
	  </div><!-- /gn-scroller -->
	</nav>
  </li>
  <li><a href="/e-imp/">I-ConS</a></li>  
  <li></li>
  <li></li>
</ul>

<script language="php">

  include("common.php");
/*  
  if($_GET) {
    $reject = 0;
	$url="/e-imp/fr/";	  
  }*/
   
  $reject = 2;  
  if(isset($_GET['tag'])) {	  
    $result = validMenuAccess($_GET['tag']);  
    if($result==1) {		
      if($_GET['tag'] == "14") {
        $reject = 0;
	    $url="/e-imp/repair.group/"; }	  
		
      if($_GET['tag'] == "15") {
        $reject = 0;
	    $url="/e-imp/relations/"; }

  	  if($_GET['tag'] == "16") {
        $reject = 0;
	    $url="/e-imp/harbour/"; }

  	  if($_GET['tag'] == "17") {
        $reject = 0;
	    $url="/e-imp/vessel/"; }
		
      if($_GET['tag'] == "18") {
        $reject = 0;
	    $url="/e-imp/workshop/"; }	  

      if($_GET['tag'] == "19") {
        $reject = 0;
	    $url="/e-imp/price.list/"; }							

	  if($_GET['tag'] == "32") {
        $reject = 0;
	    $url="/e-imp/mnr/"; }							
	}
	else {
	  $reject = 1;
	  $url="/e-imp/reject/"; }
		
  }
  
  if(isset($_GET["p"])) {
    $reject=0;
	if($_GET["p"]=="personal") { $url="/e-imp/personal_data/"; }
	if($_GET["p"]=="private") { $url="/e-imp/passwd/"; }
	if($_GET["p"]=="mdata") { $url="/e-imp/master.data/"; }
    if($_GET["p"]=="fr3") { $url="/e-imp/fr"; }
	
  }
  if((isset($_GET['eof'])) && ($_GET['eof'] == "1")) {
	$reject = 0;
	$url="/e-imp/session/"; } 
  
  if($reject <=1) { echo "<script type='text/javascript'>location.replace('$url');</script>"; }    	  
</script>