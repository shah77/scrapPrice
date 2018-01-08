<!DOCTYPE html>
<html>
<title>BestPhone.com</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<link rel="stylesheet" href="w3.css">
<style>
	#imaginary_container{
    margin-top:20%; /* Don't copy this */
}
.stylish-input-group .input-group-addon{
    background: white !important; 
}
.stylish-input-group .form-control{
	border-right:0; 
	box-shadow:0 0 0; 
	border-color:#ccc;
}
.stylish-input-group button{
    border:0;
    background:transparent;
}
</style>
<body>

<div class="w3-container w3-black">
	<h4>Smart compare</h4>
</div>
<div class="jumbotron">	
<div class="container">



<div style="">
	<div class="row">

		<form action="index.php" method="get">
        <div class="col-sm-10 col-sm-offset-1">
            <div id="imaginary_container"> 
                <div class="input-group stylish-input-group">
                	
                    <input type="text" class="form-control" name="search" placeholder="Search eg:Iphone" >
                    <span class="input-group-addon">
                        <button type="submit" name="searchdata">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>  
                    </span>
                
                </div>
            </div>
        </div>
        </form>
	</div>
</div>
</div>
<div class="w3-row-padding w3-margin-top">

    
  

<?php 


if(isset($_GET['search'])){

	$search = $_GET['search'];
	$search = strtolower($search); 
	$search = str_replace(" ", "+", $search);


	if($search == null){
		header("location: error.php");
	}
	else{

	$web_page_data = file_get_contents("http://www.pricetree.com/search.aspx?q=".$search);
//echo "output below" . "</br>";
//echo "------------------------------------"."</br>";
echo "</br>";

$item_list = explode('<div class="items-wrap">',$web_page_data);
 //print_r($item_list);


for($i=1;$i<10;$i++){
	//echo "$item_list[$i]";

	$url_link1 = explode('href="', $item_list[$i]);
	$url_link2 = explode('"', $url_link1[1] );
	//echo $url_link2[0]."</br>";

	$image_link1 = explode('data-original="', $item_list[$i]);
	$image_link2 = explode('"', $image_link1[1] );
	//echo $url_link2[0]."</br>";

    $title1 = explode('title="', $item_list[$i]);
    $title2 = explode('"', $title1[1]);

    $availavle1 = explode('avail-stores">', $item_list[$i]);
    $available = explode('</div>', $availavle1[0]);
    if(strcmp($available[0], "not available") == 0){
    	continue;

    }
 
    $item_title = $title2[0];
	$item_like = $url_link2[0];
	$item_image_link = $image_link2[0];
	$item_id1 = explode("-", $item_like);
	$item_id = end($item_id1);

    //images
    echo '
         </br>
         <div class="w3-row">
         <div class="w3-col l3 w3-row-padding">
               
                     <div class="w3-card-2">
                          <img src="'.$item_image_link.'" style="width:100%">
                              <div class="w3-container w3-black">
                                 <h4>'.$item_title.'</h4>
                             </div>
                            </div>
                            
                            
                            </div>';

	//echo $item_title."</br>";
	//echo $item_like."</br>";
	//echo $item_image_link."</br>";
	//echo $item_id."</br>";


   $request = "http://www.pricetree.com/dev/api.ashx?pricetreeId=".$item_id."&apikey=7770AD31-382F-4D32-8C36-3743C0271699";
   $response = file_get_contents($request);
   $results = json_decode($response, TRUE);
   //print_r($results);
   //echo "----------------------------</br>";

    echo '
    <div class="w3-col l8">
    <table class="w3-table-all">
                    <thead>
                       <tr class="w3-blue">
                             <th>Seller Name</th>
                             <th>Price</th>
                             <th>Buy Here</th>
                       </tr>
                    </thead>';

   foreach ($results['data'] as $itemdata) {

        $seller = $itemdata['Seller_Name'];
   	    $price = $itemdata['Best_Price'];
   	    $product_link = $itemdata['Uri'];

   	   if($results['data'] === 0){
          echo '<tr>
                <td>N/A</td>
                <td>N/A</td>
                <td>N/A</td>
              </tr>
              ';
   	   }
   	   else{
   	    

   	     

   	     //echo $seller.", ".$price.", ".$product_link."</br>";
   	     echo '<tr>
                <td>'.$seller.'</td>
                <td>'.$price.'</td>
                <td><a href="'.$product_link.'">Buy</a></td>
              </tr>
              ';
         }

   	   	
   }
   echo "</table>
         </div>
         </div>";

   echo "</br></br>";
   

}

}
}

?>
</div>
</div>
</div>
</div>


</body>
</html>
