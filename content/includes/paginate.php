<?php

function paginateResults($page, $resultPageMax, $adjacents, $totalEntries){
	$pagination = "";
	$prev = $page - 1;
	$next = $page + 1;
	$lastpage = ceil($totalEntries/$resultPageMax);
	$lpm1 = $lastpage - 1; 

	//pagination algo modified from: http://www.phpeasystep.com/phptu/29.html
	if($lastpage > 1){   
	    $pagination .= "<div class='row-fluid'>
							<div class='span12'><!--pagination -->
								<div class='pagination pagination-small pagination-centered'>
									<ul>";
	    if ($page > 1) //print 'previous' with link or without
	        $pagination.= "<li class='jobPagination'><a href=\"#pageNum=".($prev)."\" onClick='changePagination(".($prev).");'>&laquo; Previous&nbsp;&nbsp;</a></li>";
	    else
	        $pagination.= "<li class='jobPagination'><span class='disabled'>&laquo; Previous&nbsp;&nbsp;</span></li>";   
	    
	    if($lastpage < 7 + ($adjacents * 2)){

			for ($counter = 1; $counter <= $lastpage; $counter++){
		    	if ($counter == $page)
					$pagination.= "<li class='jobPagination active'><span class=\"current\">$counter</span></li>";
				else
					$pagination.= "<li class='jobPagination'><a href=\"#pageNum=".($counter)."\" onClick='changePagination(".($counter).");'>$counter</a></li>";
			}				
	    }
	  	else{ 
	        if($page < 1 + ($adjacents * 2)){
	            for($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
	            {
	                if($counter == $page)
	                    $pagination.= "<li class='jobPagination active'><span class='current'>$counter</span></li>";//current page
	                else
	                    $pagination.= "<li class='jobPagination'><a href=\"#pageNum=".($counter)."\" onClick='changePagination(".($counter).");'>$counter</a></li>";     
	            }
	            $pagination.= "<li class='jobPagination'><a href=\"#pageNum=".($lpm1)."\" onClick='changePagination(".($lpm1).");'>$lpm1</a></li>"; //print second to the last page
	            $pagination.= "<li class='jobPagination'><a href=\"#pageNum=".($lastpage)."\" onClick='changePagination(".($lastpage).");'>$lastpage</a></li>"; //print last page   

	       }
	       elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)){
	           $pagination.= "<li class='jobPagination'><a href=\"#pageNum=\"1\"\" onClick='changePagination(1);'>1</a></li>";
	           $pagination.= "<li class='jobPagination'><a href=\"#pageNum=\"2\"\" onClick='changePagination(2);'>2</a></li>";
	           for($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++){
	               if($counter == $page)
	                   $pagination.= "<li class='jobPagination active'><span class='current'>$counter</span></li>";
	               else
	                   $pagination.= "<li class='jobPagination'><a href=\"#pageNum=".($counter)."\" onClick='changePagination(".($counter).");'>$counter</a></li>";     
	           }
	           $pagination.= "<li class='jobPagination'><a href=\"#pageNum=".($lpm1)."\" onClick='changePagination(".($lpm1).");'>$lpm1</a></li>";
	           $pagination.= "<li class='jobPagination'><a href=\"#pageNum=".($lastpage)."\" onClick='changePagination(".($lastpage).");'>$lastpage</a></li>";   
	       }
	       else{
	           $pagination.= "<li class='jobPagination'><a href=\"#pageNum=\"1\"\" onClick='changePagination(1);'>1</a></li>";
	           $pagination.= "<li class='jobPagination'><a href=\"#pageNum=\"2\"\" onClick='changePagination(2);'>2</a></li>";
	           for($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++){
	               if($counter == $page)
	                    $pagination.= "<li class='jobPagination active'><span class='current'>$counter</span></li>";
	               else
	                    $pagination.= "<li class='jobPagination'><a href=\"#pageNum=".($counter)."\" onClick='changePagination(".($counter).");'>$counter</a></li>";     
	           }
	       }
	    }
	    if($page < $counter - 1)
	        $pagination.= "<li class='jobPagination'><a href=\"#pageNum=".($next)."\" onClick='changePagination(".($next).");'>Next &raquo;</a></li>";
	    else
	        $pagination.= "<li class='jobPagination'><span class='disabled'>Next &raquo;</span></li>";

	    $pagination.= "</ul></div></div><!--/pagination --></div>";       
	}
	return $pagination;
}
?>