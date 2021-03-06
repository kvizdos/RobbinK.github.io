<?php
include 'userLogin.php';
include 'header.php';
include 'menu.php';
include 'answerTickets.php';
if(@$isLogin and $userinfo->role == "admin"):
function _numpage($co_tot,$co)
{
	if ($co_tot == 0)
	{
		$page = 1;
		return $page;
	}
	
	if($co > $co_tot)
	{
		$co_tot = $co;
		$page = $co_tot / $co;
		return $page;
	}
	else 
	{
		$page = $co_tot / $co;
		if ($page > 0 and $page < 1)
		{
			$page = 2;
			return $page;
		}
		else if ($page > 1 and $page < 2)
		{
			$page = 2;
			return $page;
		}
		 return ceil($page);
	}
}
?>
<?php 
	if (isset($_GET["method"]))
	{
		$method = $_GET["method"];
		$all = md5("all");
		$closed = md5("closed");
		$answered = md5("answered");
		$open = md5("open");
		$progress = md5("progress");
		$Freopen = md5("Freopen");
		switch ($method)
		{
			case $all:
				$count = count($ticketInfo->id);
				$co_tot = 0;
				$co_show = 10;
				for($c = 0; $c < $count; $c++)
				{
					$co_tot++;
				}
				if(isset($_GET["page"]))
				{
					$showPage = $_GET["page"];
				}
				else
				{
					$showPage = 1;
				}
				$_page = _numpage($co_tot, $co_show);
				if($ticketInfo->res == "true")
				{
					echo '<article class="container" style="margin-top: -18px;">
							<section class="well well-small mePadd">
        						<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-comment meMarg"></span> All tickets</h2>';
					if($showPage == 1)
					{
						$start = 0;
						if($count < $co_show)
						{
							$end = $count;
						}
						else 
						{
							$end = $co_show;
						}
					}
					else 
					{
						$_count = $showPage * $co_show;
						$start = $_count - $co_show;
						if($count < $_count)
						{
							$end = $count;
						}
						else 
						{
							$end = --$_count;
						}
					}
					for ($i=$start;$i<$end;$i++)
					{
						switch ($ticketInfo->status[$i]) 
						{
							case "open":
								$class = "badge-info";
								$_method = md5("answer");
								$href = "answer.php?method=".$_method.":".$ticketInfo->id[$i];
								$text = "Open";
								$__method = md5("Fclosed");
								$_href = "answer.php?method=".$__method.":".$ticketInfo->id[$i].":".urlencode($ticketInfo->subject[$i]);
								$continue = md5("showO");
								$icon = "icon-folder-open";
								$ticketId = 0;
						        $_buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button">Close</button></a>';
								$buttons = '<a href="'.$href.'"><button class="btn btn-success btnDetails" type="button">Reply</button></a>';
								$___method = md5("showO");
								$___href = "show.php?method=".$___method.":".$ticketInfo->id[$i];
								$__buttons = '<a href="'.$___href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
							break;
							
							case "closed":
								$class = "badge-close";
								$_method = md5("showC");
								$href = "show.php?method=".$_method.":".$ticketInfo->id[$i];
								$disabled = "";
								$btn_success = "";
								$text = "Close";
								$continue = $_method;
								$icon = "icon-folder-close";
								$__buttons = "";
								$ticketId = $ticketInfo->id[$i];
								$_moderator = $userinfo->moderatorRole;
								$_href = "tickets.php?method=".$Freopen.":".$ticketInfo->id[$i].":".urlencode($ticketInfo->subject[$i]);
								$_buttons = '<button class="btn btn-success btnDetails" type="button" data-toggle="modal" data-target="#myModal'.$ticketId.'">Reopen</button>';
								$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
							break;
			
							case "answered":
								$class = "badge-success";
								$_method = md5("showA");
								$__method = md5("Fclosed");
								$_href = "answer.php?method=".$__method.":".$ticketInfo->id[$i].":".urlencode($ticketInfo->subject[$i]);
								$href = "show.php?method=".$_method.":".$ticketInfo->id[$i];
								$disabled = "";
								$btn_success = "btn-success";
								$text =  "Replied";
								$ticketId = 0;
								$icon = "icon-ok";
								$continue = $_method; 
								$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
								$_buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button">Close</button></a>';
								$__buttons = "";
							break;
							
							case "in progress":
								$class = "badge-inverse";
								$_method = md5("openC");
								$__method = md5("Fclosed");
								$_href = "answer.php?method=".$__method.":".$ticketInfo->id[$i].":".urlencode($ticketInfo->subject[$i]);
								$href = "answer.php?method=".$_method.":".$ticketInfo->id[$i];
								$text = "Pending";
								$ticketId = 0;
								$continue = $_method;
								$icon = "icon-time";
								$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
								$_buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button">Close</button></a>';
								$__buttons = "";
							break;
								
							default:
								$class = "error";
								$href = "#";
								$disabled = "disabled";
								$btn_success = "";
								$text = "error";
								$a = "";
								$ticketId = 0;
								$continue = "";
								$_buttons = "";
								$__buttons = "";
								$buttons = "";
								$icon = "";
							break;
						}
						$message = urldecode($ticketInfo->msg[$i]);
						$message = str_replace("\'", "'", $message);
						$exp = explode(" ",$message);
						if(count($exp) > 20)
						{
							$discription = substr($message, 0, 400);
							$show = $continue;
							$show = $show.":".$ticketInfo->id[$i];
							$discription = $discription.' '.'&hellip; <a href="show.php?method='.$show.'"> Continue reading <span class="meta-nav">&rarr;</span></a>';
						}
						else 
						{
							$discription = $message;
						}
						$dateModified = $_ticketInfo->change_date_format($ticketInfo->dateAded[$i]);
						$dateActivity = $_ticketInfo->change_date_format($ticketInfo->dateActivity[$i]);
						echo '<div class="myTable">
               				 <div class="tableH">
               				 <div class="resTable1">
				                  <table class="tables" width="100%" style="border:none;">
				                    <tbody>
				                    <tr>
				                    <th style="border-top:none;" scope="row" class="tableFont" align="left">ID</th>
				                    <td style="border-top:none;" class="tableFont2">'.$ticketInfo->id[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Status</th>
				                    <td class="tableFont2"><div class="badge '.$class.'" style="width:40px;">'.$text.'</div></td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Subject</th>
				                    <td class="tableFont2">'.$ticketInfo->subject[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Department</th>
				                    <td class="tableFont2">'.$ticketInfo->department[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Date Modified</th>
				                    <td class="tableFont2">'.$dateModified.'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Last Activity</th>
				                    <td class="tableFont2">'.$dateActivity.'</td>
				                    </tr>
				                    </tbody>
				                  </table>
				                <div class="paddingRL resTable1 mePadB">
				                <div class="tableW">
				                '.$discription.'
				                </div>
				                    <div class="pull-right paddingTop">
				                    	'.$buttons.'
						                '.$__buttons.'
						                '.$_buttons.'
				                    </div>
				                </div>
				                </div>
				                
			                    <div class="resTable2" style="margin-top:-32px;">
				                    <table class="table table-striped table-condensed" style="border:none;">
				                      <tr class="tableFont">
			                        <td>ID</td>
			                        <td>Status</td>
			                        <td>Subject</td>
			                        <td>Department</td>
			                        <td>Date Modified</td>
			                        <td>Last Activity</td>
			                      </tr>';
					
				echo '		  <tr class="tableFont2">';
					
				echo ' 			<td>'.$ticketInfo->id[$i].'</td>
			                      <td>
			                        	<center><div class="badge '.$class.'" style="width:50px;">'.$text.'</div></center>
			                        </td>
			                        <td>'.$ticketInfo->subject[$i].'</td>
			                        <td>'.$ticketInfo->department[$i].'</td>
			                        <td>'.$dateModified.'</td>
			                        <td>'.$dateActivity.'</td>
			                      </tr>
			                    </table>
			                </div>
			                <div class="paddingRL resTable2 mePadB">
			                <div class="tableW">'.$discription.'</div>
			                 <div class="pull-right paddingTop">
			                 '.$buttons.'
			                  '.$__buttons.'
			                 '.$_buttons.'
			              	</div>
			              	</div>
                   		 </div>
                   		 </div>
			                ';	
						echo '<div class="modal hide fade" id="myModal'.$ticketId.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><!--Modal Starts-->
				        	<div class="modal-header">
				            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
				            <h3 id="myModalLabel">Send a new message</h3>
				            </div>
				            <form action="answer.php" method="post">
				            <div class="modal-body">
				            <textarea class="modalTextarea" required="required" name="message"></textarea>
				            </div>
				            <div class="modal-footer">
				            <input type="hidden" name="id" value="'.$ticketId.'" >
	                        <input type="hidden" name="moderator" value="'.@$_moderator.'" >
				            <button class="btn btn-success" type="submit" name="openC"><span class="icon-arrow-right icon-white"></span> Send</button>
				            <button class="btn" data-dismiss="modal" aria-hidden="true"><span class="icon-thumbs-down"></span> Cancel</button>
				            </div>
				            </form>
				        	</div><!--Modal Ends--> <!--Details Ends-->';
					}
					if ($_page == 1)
					{
						echo '<div class="pagination pagination-centered">
					  			<ul>
							    <li class="active"><a href="#">1</a></li>
								</ul>
							  </div>';
					}
					else 
					{
						echo '<div class="pagination pagination-centered"><ul>';
						for($i = 1;$i <= $_page; $i++)
						{
							if ($showPage == $i)
							{
								echo '<li class="active"><a href="#">'.$i.'</a></li>';
							}
							else 
							{
								echo '<li><a href="tickets.php?method='.$method.'&page='.$i.'">'.$i.'</a></li>';
							}
						}
						echo '</ul></div>';
					}
					echo '</section></article>';
					echo '<div class="modal hide fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><!--Modal Starts-->
			        	<div class="modal-header">
			            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
			            <h3 id="myModalLabel">Send a new message</h3>
			            </div>
			            <form action="answer.php" method="post">
			            <div class="modal-body">
			            <textarea class="modalTextarea" required="required" name="message"></textarea>
			            </div>
			            <div class="modal-footer">
			            <input type="hidden" name="id" value="'.$ticketId.'" >
                        <input type="hidden" name="moderator" value="'.@$_moderator.'" >
			            <button class="btn btn-success" type="submit" name="openC"><span class="icon-arrow-right icon-white"></span> Send</button>
			            <button class="btn" data-dismiss="modal" aria-hidden="true"><span class="icon-thumbs-down"></span> Cancel</button>
			            </div>
			            </form>
			        </div><!--Modal Ends--> <!--Details Ends-->';
				}
				else 
				{
					echo '
						<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
							<div class="alert alert-info">
							<center><b>NO TICKETS</b></center>
							</div>
							</section>
					    </article>';
				}		  
			break;
			
			case $closed:
				$count = count($ticketInfo->id);
				$co_tot = 0;
				$co_show = 10;
				for($c = 0; $c < $count; $c++)
				{
					if(@$ticketInfo->status[$c] == "closed")
					{
						$co_tot++;
					}
				}
				if(isset($_GET["page"]))
				{
					$showPage = $_GET["page"];
				}
				else
				{
					$showPage = 1;
				}
				$_page = _numpage($co_tot, $co_show);
				if($ticketInfo->res == "true")
				{
						echo '<article class="container" style="margin-top: -18px;">
								<section class="well well-small mePadd">
									<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-comment meMarg"></span> Close tickets</h2>';
					$j = 0;
					$start = 0;
					$closed_count = 0;
					while ($start < $count)
					{
						if($ticketInfo->status[$start] == "closed")
						{
							$ticketInfo_id[] = $ticketInfo->id[$start];
							$ticketInfo_msg[] = urldecode($ticketInfo->msg[$start]);
							$ticketInfo_subject[] = $ticketInfo->subject[$start];
							$ticketInfo_department[] = $ticketInfo->department[$start];
							$ticketInfo_status[] = $ticketInfo->status[$start];
							$ticketInfo_dateAded[] = $ticketInfo->dateAded[$start];
							$ticketInfo_dateActivity[] = $ticketInfo->dateActivity[$start];
							$closed_count++;
						}
						$start++;
					}
					if($showPage == 1)
					{
						$start = 0;
						if($closed_count >= 10)
						{
							$end = $co_show;
						}
						else 
						{
							$end = $closed_count;
						}
					}
					else 
					{
						$_count = $showPage * $co_show;
						$start = $_count - $co_show;
						if($count < $_count)
						{
							$end = $count;
						}
						else 
						{
							$end = --$_count;
						}
					}
					for ($i=$start;$i<$end;$i++)
					{
						if(@$ticketInfo_status[$i] == "closed")
						{
							$j++;
							$class = "badge-close";
							$_method = md5("showC");
							$href = "show.php?method=".$_method.":".$ticketInfo_id[$i];
							$text = "Close";
							$continue = $_method;
							$icon = "icon-folder-close";
							$ticketId = $ticketInfo_id[$i];
							$_moderator = $userinfo->moderatorRole;
							$_href = "tickets.php?method=".$Freopen.":".$ticketInfo_id[$i].":".urlencode($ticketInfo_subject[$i]);
							$_buttons = '<button class="btn btn-success btnDetails" type="button" data-toggle="modal" data-target="#myModal'.$ticketId.'">Reopen</button>';
							$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
							$message = urldecode($ticketInfo_msg[$i]);
							$message = str_replace("\'", "'", $message);
							$exp = explode(" ",$message);
							if(count($exp) > 20)
							{
								$discription = substr($message, 0, 400);
								$show = $_method;
								$show = $show.":".$ticketInfo_id[$i];
								$discription = $discription.' '.'&hellip; <a href="show.php?method='.$show.'"> Continue reading <span class="meta-nav">&rarr;</span></a>';
							}
							else 
							{
								$discription = $message;
							}
							$dateModified = $_ticketInfo->change_date_format($ticketInfo_dateAded[$i]);
							$dateActivity = $_ticketInfo->change_date_format($ticketInfo_dateActivity[$i]);
							echo '<div class="myTable">
               				 <div class="tableH">
               				 <div class="resTable1">
				                  <table class="tables" width="100%" style="border:none;">
				                    <tbody>
				                    <tr>
				                    <th style="border-top:none;" scope="row" class="tableFont" align="left">ID</th>
				                    <td style="border-top:none;" class="tableFont2">'.$ticketInfo_id[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Status</th>
				                    <td class="tableFont2"><div class="badge '.$class.'" style="width:40px;">'.$text.'</div></td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Subject</th>
				                    <td class="tableFont2">'.$ticketInfo_subject[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Department</th>
				                    <td class="tableFont2">'.$ticketInfo_department[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Date Modified</th>
				                    <td class="tableFont2">'.$dateModified.'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Last Activity</th>
				                    <td class="tableFont2">'.$dateActivity.'</td>
				                    </tr>
				                    </tbody>
				                  </table>
				                <div class="paddingRL resTable1 mePadB">
				                <div class="tableW">
				                '.$discription.'
				                </div>
				                    <div class="pull-right paddingTop">
				                    	'.$buttons.'
						                '.$_buttons.'
				                    </div>
				                </div>
				                </div>
				                
			                    <div class="resTable2" style="margin-top:-32px;">
				                    <table class="table table-striped table-condensed" style="border:none;">
				                      <tr class="tableFont">
			                        <td>ID</td>
			                        <td>Status</td>
			                        <td>Subject</td>
			                        <td>Department</td>
			                        <td>Date Modified</td>
			                        <td>Last Activity</td>
			                      </tr>';
					
							echo '		  <tr class="tableFont2">';
								
							echo ' 			<td>'.$ticketInfo_id[$i].'</td>
						                        <td>
						                        	<center><div class="badge '.$class.'" style="width:50px;">'.$text.'</div></center>
						                        </td>
						                        <td>'.$ticketInfo_subject[$i].'</td>
						                        <td>'.$ticketInfo_department[$i].'</td>
						                        <td>'.$dateModified.'</td>
						                        <td>'.$dateActivity.'</td>
						                      </tr>
						                    </table>
						                </div>
						                <div class="paddingRL resTable2 mePadB">
						                <div class="tableW">'.$discription.'</div>
						                 <div class="pull-right paddingTop">
						                 '.$buttons.'
						                 '.$_buttons.'
						              	</div>
						              	</div>
			                   		 </div>
			                   		 </div>
						                ';	
							echo '<div class="modal hide fade" id="myModal'.$ticketId.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><!--Modal Starts-->
				        	<div class="modal-header">
				            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
				            <h3 id="myModalLabel">Send a new message</h3>
				            </div>
				            <form action="answer.php" method="post">
				            <div class="modal-body">
				            <textarea class="modalTextarea" required="required" name="message"></textarea>
				            </div>
				            <div class="modal-footer">
				            <input type="hidden" name="id" value="'.$ticketId.'" >
	                        <input type="hidden" name="moderator" value="'.@$_moderator.'" >
				            <button class="btn btn-success" type="submit" name="openC"><span class="icon-arrow-right icon-white"></span> Send</button>
				            <button class="btn" data-dismiss="modal" aria-hidden="true"><span class="icon-thumbs-down"></span> Cancel</button>
				            </div>
				            </form>
				        	</div><!--Modal Ends--> <!--Details Ends-->';
						}
					}
					if ($_page == 1 and $j != 0)
					{
						echo '<div class="pagination pagination-centered">
					  			<ul>
							    <li class="active"><a href="#">1</a></li>
								</ul>
							  </div>';
					}
					else 
					{
						if($j != 0)
						{
							echo '<div class="pagination pagination-centered"><ul>';
							for($i = 1;$i <= $_page; $i++)
							{
								if ($showPage == $i)
								{
									echo '<li class="active"><a href="#">'.$i.'</a></li>';
								}
								else 
								{
									echo '<li><a href="tickets.php?method='.$method.'&page='.$i.'">'.$i.'</a></li>';
								}
							}
							echo '</ul></div>';
						}
					}
					echo '</section>
						</article>';
				}
				else 
				{
					echo '
						<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">
						<div class="alert alert-info">
						<center><b>NO TICKETS</b></center>
						</div>
						</section>
						</article>';
				}
				if(@$j == "0")
				{
					echo '
						<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">
						<div class="alert alert-info">
						<center><b>NO TICKETS</b></center>
						</div>
						</section>
						</article>';
				}	
			break;
			
			case $answered:
				$count = count($ticketInfo->id);
				$co_tot = 0;
				$co_show = 10;
				for($c = 0; $c < $count; $c++)
				{
					if(@$ticketInfo->status[$c] == "answered")
					{
						$co_tot++;
					}
				}
				if(isset($_GET["page"]))
				{
					$showPage = $_GET["page"];
				}
				else
				{
					$showPage = 1;
				}
				$_page = _numpage($co_tot, $co_show);
				if($ticketInfo->res == "true")
				{
					echo '<article class="container" style="margin-top: -18px;">
							<section class="well well-small mePadd">
        					 	<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-comment meMarg"></span> Replied tickets</h2>';
					$j = 0;
					$start = 0;
					$answered_count = 0;
					while ($start < $count)
					{
						if(@$ticketInfo->status[$start] == "answered")
						{
							$ticketInfo_id[] = $ticketInfo->id[$start];
							$ticketInfo_msg[] = urldecode($ticketInfo->msg[$start]);
							$ticketInfo_subject[] = $ticketInfo->subject[$start];
							$ticketInfo_department[] = $ticketInfo->department[$start];
							$ticketInfo_status[] = $ticketInfo->status[$start];
							$ticketInfo_dateAded[] = $ticketInfo->dateAded[$start];
							$ticketInfo_dateActivity[] = $ticketInfo->dateActivity[$start];
							$answered_count++;
						}
						$start++;
					}
					if($showPage == 1)
					{
						$start = 0;
						if($answered_count >= 10)
						{
							$end = $co_show;
						}
						else 
						{
							$end = $answered_count;
						}
					}
					else 
					{
						$_count = $showPage * $co_show;
						$start = $_count - $co_show;
						if($count < $_count)
						{
							$end = $count;
						}
						else 
						{
							$end = --$_count;
						}
					}
					for ($i=$start;$i<$end;$i++)
					{
						if(@$ticketInfo_status[$i] == "answered")
						{
							$j++;
							$class = "badge-success";
							$_method = md5("showA");
							$__method = md5("Fclosed");
							$_href = "answer.php?method=".$__method.":".$ticketInfo_id[$i].":".urlencode($ticketInfo_subject[$i]);
							$href = "show.php?method=".$_method.":".$ticketInfo_id[$i];
							$text =  "answered";
							$text =  "Replied";
							$continue = $_method; 
							$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
							$_buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button">Close</button></a>';
							$icon = "icon-ok";							
							$message = urldecode($ticketInfo_msg[$i]);
							$message = str_replace("\'", "'", $message);
							$exp = explode(" ",$message);
							if(count($exp) > 20)
							{
								$discription = substr($message, 0, 400);
								$show = $_method;
								$show = $show.":".$ticketInfo_id[$i];
								$discription = $discription.' '.'&hellip; <a href="show.php?method='.$show.'"> Continue reading <span class="meta-nav">&rarr;</span></a>';
							}
							else 
							{
								$discription =$message;
							}
							$dateModified = $_ticketInfo->change_date_format($ticketInfo_dateAded[$i]);
							$dateActivity = $_ticketInfo->change_date_format($ticketInfo_dateActivity[$i]);
							echo '<div class="myTable">
               				 <div class="tableH">
               				 <div class="resTable1">
				                  <table class="tables" width="100%" style="border:none;">
				                    <tbody>
				                    <tr>
				                    <th style="border-top:none;" scope="row" class="tableFont" align="left">ID</th>
				                    <td style="border-top:none;" class="tableFont2">'.$ticketInfo_id[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Status</th>
				                    <td class="tableFont2"><div class="badge '.$class.'" style="width:40px;">'.$text.'</div></td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Subject</th>
				                    <td class="tableFont2">'.$ticketInfo_subject[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Department</th>
				                    <td class="tableFont2">'.$ticketInfo_department[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Date Modified</th>
				                    <td class="tableFont2">'.$dateModified.'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Last Activity</th>
				                    <td class="tableFont2">'.$dateActivity.'</td>
				                    </tr>
				                    </tbody>
				                  </table>
				                <div class="paddingRL resTable1 mePadB">
				                <div class="tableW">
				                '.$discription.'
				                </div>
				                    <div class="pull-right paddingTop">
				                    	'.$buttons.'
						                '.$_buttons.'
				                    </div>
				                </div>
				                </div>
				                
			                    <div class="resTable2" style="margin-top:-32px;">
				                    <table class="table table-striped table-condensed" style="border:none;">
				                      <tr class="tableFont">
			                        <td>ID</td>
			                        <td>Status</td>
			                        <td>Subject</td>
			                        <td>Department</td>
			                        <td>Date Modified</td>
			                        <td>Last Activity</td>
			                      </tr>';
					
							echo '		  <tr class="tableFont2">';
								
							echo ' 			<td>'.$ticketInfo_id[$i].'</td>
						                      <td>
						                        	<center><div class="badge '.$class.'" style="width:50px;">'.$text.'</div></center>
						                        </td>
						                        <td>'.$ticketInfo_subject[$i].'</td>
						                        <td>'.$ticketInfo_department[$i].'</td>
						                        <td>'.$dateModified.'</td>
						                        <td>'.$dateActivity.'</td>
						                      </tr>
						                    </table>
						                </div>
						                <div class="paddingRL resTable2 mePadB">
						                <div class="tableW">'.$discription.'</div>
						                 <div class="pull-right paddingTop">
						                 '.$buttons.'
						                 '.$_buttons.'
						              	</div>
						              	</div>
			                   		 </div>
			                   		 </div>
						                ';	
						}
					}
					if ($_page == 1 and $j != 0)
					{
						echo '<div class="pagination pagination-centered">
					  			<ul>
							    <li class="active"><a href="#">1</a></li>
								</ul>
							  </div>';
					}
					else 
					{
						if($j != 0)
						{
							echo '<div class="pagination pagination-centered"><ul>';
							for($i = 1;$i <= $_page; $i++)
							{
								if ($showPage == $i)
								{
									echo '<li class="active"><a href="#">'.$i.'</a></li>';
								}
								else 
								{
									echo '<li><a href="tickets.php?method='.$method.'&page='.$i.'">'.$i.'</a></li>';
								}
							}
							echo '</ul></div>';
						}
					}
					echo '</section></article>';
				}
				else 
				{
					echo '
						<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">
						<div class="alert alert-info">
						<center><b>NO TICKETS</b></center>
						</div>
						</section>
						</article>';
				}
				if(@$j == "0")
				{
					echo '
						<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">
						<div class="alert alert-info">
						<center><b>NO TICKETS</b></center>
						</div>
						</section>
						</article>';
				}	
			break;

			case $open:
				$count = count($ticketInfo->id);
				$co_tot = 0;
				$co_show = 10;
				for($c = 0; $c < $count; $c++)
				{
					if(@$ticketInfo->status[$c] == "open")
					{
						$co_tot++;
					}
				}
				if(isset($_GET["page"]))
				{
					$showPage = $_GET["page"];
				}
				else
				{
					$showPage = 1;
				}
				$_page = _numpage($co_tot, $co_show);
				if($ticketInfo->res == "true")
				{
					echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
								<h2 class="meFont" style="padding-bottom:30px;"><span class="icon-comment meMarg"></span> Open tickets</h2>';
					$j = 0;
					$start = 0;
					$open_count = 0;
					while ($start < $count)
					{
						if($ticketInfo->status[$start] == "open")
						{
							$ticketInfo_id[] = $ticketInfo->id[$start];
							$ticketInfo_msg[] = urldecode($ticketInfo->msg[$start]);
							$ticketInfo_subject[] = $ticketInfo->subject[$start];
							$ticketInfo_department[] = $ticketInfo->department[$start];
							$ticketInfo_status[] = $ticketInfo->status[$start];
							$ticketInfo_dateAded[] = $ticketInfo->dateAded[$start];
							$ticketInfo_dateActivity[] = $ticketInfo->dateActivity[$start];
							$open_count++;
						}
						$start++;
					}
					if($showPage == 1)
					{
						$start = 0;
						if($open_count >= 10)
						{
							$end = $co_show;
						}
						else 
						{
							$end = $open_count;
						}
					}
					else 
					{
						$_count = $showPage * $co_show;
						$start = $_count - $co_show;
						if($count < $_count)
						{
							$end = $count;
						}
						else 
						{
							$end = --$_count;
						}
					}
					for ($i=$start;$i<$end;$i++)
					{
						if(@$ticketInfo_status[$i] == "open")
						{
							$j++;
							$class = "badge-info";
							$_method = md5("answer");
							$href = "answer.php?method=".$_method.":".$ticketInfo_id[$i];
							$text = "Open";
							$__method = md5("Fclosed");
							$_href = "answer.php?method=".$__method.":".$ticketInfo_id[$i].":".urlencode($ticketInfo_subject[$i]);
							$continue = md5("showO");
							$icon = "icon-folder-open";
					        $buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button">Close</button></a>';
							$_buttons = '<a href="'.$href.'"><button class="btn btn-success btnDetails" type="button">Reply</button></a>';
							$___method = md5("showO");
							$___href = "show.php?method=".$___method.":".$ticketInfo_id[$i];
							$__buttons = '<a href="'.$___href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
							$message = urldecode($ticketInfo_msg[$i]);
							$message = str_replace("\'", "'", $message);
							$exp = explode(" ",$message);
							if(count($exp) > 20)
							{
								$discription = substr($message, 0, 400);
								$show = md5("showO");
								$show = $show.":".$ticketInfo_id[$i];
								$discription = $discription.' '.'&hellip; <a href="show.php?method='.$show.'"> Continue reading <span class="meta-nav">&rarr;</span></a>';
							}
							else 
							{
								$discription = $message;
							}
							$dateModified = $_ticketInfo->change_date_format($ticketInfo_dateAded[$i]);
							$dateActivity = $_ticketInfo->change_date_format($ticketInfo_dateActivity[$i]);
							echo '<div class="myTable">
               				 <div class="tableH">
               				 <div class="resTable1">
				                  <table class="tables" width="100%" style="border:none;">
				                    <tbody>
				                    <tr>
				                    <th style="border-top:none;" scope="row" class="tableFont" align="left">ID</th>
				                    <td style="border-top:none;" class="tableFont2">'.$ticketInfo_id[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Status</th>
				                    <td class="tableFont2"><div class="badge '.$class.'" style="width:40px;">'.$text.'</div></td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Subject</th>
				                    <td class="tableFont2">'.$ticketInfo_subject[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Department</th>
				                    <td class="tableFont2">'.$ticketInfo_department[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Date Modified</th>
				                    <td class="tableFont2">'.$dateModified.'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Last Activity</th>
				                    <td class="tableFont2">'.$dateActivity.'</td>
				                    </tr>
				                    </tbody>
				                  </table>
				                <div class="paddingRL resTable1 mePadB">
				                <div class="tableW">
				                '.$discription.'
				                </div>
				                    <div class="pull-right paddingTop">
				                    	'.$buttons.'
						                '.$__buttons.'
						                '.$_buttons.'
				                    </div>
				                </div>
				                </div>
				                
			                    <div class="resTable2" style="margin-top:-32px;">
				                    <table class="table table-striped table-condensed" style="border:none;">
				                      <tr class="tableFont">
			                        <td>ID</td>
			                        <td>Status</td>
			                        <td>Subject</td>
			                        <td>Department</td>
			                        <td>Date Modified</td>
			                        <td>Last Activity</td>
			                      </tr>';
					
							echo '		  <tr class="tableFont2">';
								
							echo ' 			<td>'.$ticketInfo_id[$i].'</td>
						                      <td>
						                        	<center><div class="badge '.$class.'" style="width:50px;">'.$text.'</div></center>
						                        </td>
						                        <td>'.$ticketInfo_subject[$i].'</td>
						                        <td>'.$ticketInfo_department[$i].'</td>
						                        <td>'.$dateModified.'</td>
						                        <td>'.$dateActivity.'</td>
						                      </tr>
						                    </table>
						                </div>
						                <div class="paddingRL resTable2 mePadB">
						                <div class="tableW">'.$discription.'</div>
						                 <div class="pull-right paddingTop">
						                 '.$buttons.'
						                  '.$__buttons.'
						                 '.$_buttons.'
						              	</div>
						              	</div>
			                   		 </div>
			                   		 </div>
						                ';	
						}
					}
					if ($_page == 1 and $j != 0)
					{
						echo '<div class="pagination pagination-centered">
					  			<ul>
							    <li class="active"><a href="#">1</a></li>
								</ul>
							  </div>';
					}
					else 
					{
						if($j != 0)
						{
							echo '<div class="pagination pagination-centered"><ul>';
							for($i = 1;$i <= $_page; $i++)
							{
								if ($showPage == $i)
								{
									echo '<li class="active"><a href="#">'.$i.'</a></li>';
								}
								else 
								{
									echo '<li><a href="tickets.php?method='.$method.'&page='.$i.'">'.$i.'</a></li>';
								}
							}
							echo '</ul></div>';
						}
					}
					echo '</section></article>';
				}
				else 
				{
						echo '
						<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">
						<div class="alert alert-info">
						<center><b>NO TICKETS</b></center>
						</div>
						</section>
						</article>';
				}
				if(@$j == "0")
				{
						echo '
						<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">
						<div class="alert alert-info">
						<center><b>NO TICKETS</b></center>
						</div>
						</section>
						</article>';
				}		
			break;
			
			case $progress:
				$count = count($ticketInfo->id);
				$co_tot = 0;
				$co_show = 10;
				for($c = 0; $c < $count; $c++)
				{
					if(@$ticketInfo->status[$c] == "in progress")
					{
						$co_tot++;
					}
				}
				if(isset($_GET["page"]))
				{
					$showPage = $_GET["page"];
				}
				else
				{
					$showPage = 1;
				}
				$_page = _numpage($co_tot, $co_show);
				if($ticketInfo->res == "true")
				{
					echo '<article class="container" style="margin-top:-18px;">
							<section class="well well-small mePadd">
							    <h2 class="meFont" style="padding-bottom:30px;"><span class="icon-comment meMarg"></span> Pending tickets</h2>';
					$j = 0;
					$start = 0;
					$progress_count = 0;
					while ($start < $count)
					{
						if($ticketInfo->status[$start] == "in progress")
						{
							$ticketInfo_id[] = $ticketInfo->id[$start];
							$ticketInfo_msg[] = urldecode($ticketInfo->msg[$start]);
							$ticketInfo_subject[] = $ticketInfo->subject[$start];
							$ticketInfo_department[] = $ticketInfo->department[$start];
							$ticketInfo_status[] = $ticketInfo->status[$start];
							$ticketInfo_dateAded[] = $ticketInfo->dateAded[$start];
							$ticketInfo_dateActivity[] = $ticketInfo->dateActivity[$start];
							$progress_count++;
						}
						$start++;
					}
					if($showPage == 1)
					{
						$start = 0;
						if($progress_count >= 10)
						{
							$end = $co_show;
						}
						else 
						{
							$end = $progress_count;
						}
					}
					else 
					{
						$_count = $showPage * $co_show;
						$start = $_count - $co_show;
						if($count < $_count)
						{
							$end = $count;
						}
						else 
						{
							$end = --$_count;
						}
					}
					for ($i=$start;$i<$end;$i++)
					{
						if(@$ticketInfo_status[$i] == "in progress")
						{
							$j++;
							$class = "badge-inverse";
							$_method = md5("openC");
							$__method = md5("Fclosed");
							$_href = "answer.php?method=".$__method.":".$ticketInfo_id[$i].":".urlencode($ticketInfo_subject[$i]);
							$href = "answer.php?method=".$_method.":".$ticketInfo_id[$i];
							$text = "Pending";
							$continue = $_method;
							$icon = "icon-time";
							$buttons = '<a href="'.$href.'"><button class="btn btn-info btnDetails" type="button">Details</button></a>';
							$_buttons = '<a href="'.$_href.'"><button class="btn btn-danger btnClose" type="button">Close</button></a>';
							$message = urldecode($ticketInfo_msg[$i]);
							$message = str_replace("\'", "'", $message);
							$exp = explode(" ",$message);
							if(count($exp) > 20)
							{
								$discription = substr($message, 0, 400);
								$show = $_method;
								$show = $show.":".$ticketInfo_id[$i];
								$discription = $discription.' '.'&hellip; <a href="show.php?method='.$show.'"> Continue reading <span class="meta-nav">&rarr;</span></a>';
							}
							else 
							{
								$discription = $message;
							}
							$dateModified = $_ticketInfo->change_date_format($ticketInfo_dateAded[$i]);
							$dateActivity = $_ticketInfo->change_date_format($ticketInfo_dateActivity[$i]);
							echo '<div class="myTable">
               				 <div class="tableH">
               				 <div class="resTable1">
				                  <table class="tables" width="100%" style="border:none;">
				                    <tbody>
				                    <tr>
				                    <th style="border-top:none;" scope="row" class="tableFont" align="left">ID</th>
				                    <td style="border-top:none;" class="tableFont2">'.$ticketInfo_id[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Status</th>
				                    <td class="tableFont2"><div class="badge '.$class.'" style="width:40px;">'.$text.'</div></td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Subject</th>
				                    <td class="tableFont2">'.$ticketInfo_subject[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Department</th>
				                    <td class="tableFont2">'.$ticketInfo_department[$i].'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Date Modified</th>
				                    <td class="tableFont2">'.$dateModified.'</td>
				                    </tr>
				                    <tr>
				                    <th scope="row" class="tableFont" align="left">Last Activity</th>
				                    <td class="tableFont2">'.$dateActivity.'</td>
				                    </tr>
				                    </tbody>
				                  </table>
				                <div class="paddingRL resTable1 mePadB">
				                <div class="tableW">
				                '.$discription.'
				                </div>
				                    <div class="pull-right paddingTop">
				                    	'.$buttons.'
						                '.$_buttons.'
				                    </div>
				                </div>
				                </div>
				                
			                    <div class="resTable2" style="margin-top:-32px;">
				                    <table class="table table-striped table-condensed" style="border:none;">
				                      <tr class="tableFont">
			                        <td>ID</td>
			                        <td>Status</td>
			                        <td>Subject</td>
			                        <td>Department</td>
			                        <td>Date Modified</td>
			                        <td>Last Activity</td>
			                      </tr>';
					
							echo '		  <tr class="tableFont2">';
								
							echo ' 			<td>'.$ticketInfo_id[$i].'</td>
						                      <td>
						                        	<center><div class="badge '.$class.'" style="width:50px;">'.$text.'</div></center>
						                        </td>
						                        <td>'.$ticketInfo_subject[$i].'</td>
						                        <td>'.$ticketInfo_department[$i].'</td>
						                        <td>'.$dateModified.'</td>
						                        <td>'.$dateActivity.'</td>
						                      </tr>
						                    </table>
						                </div>
						                <div class="paddingRL resTable2 mePadB">
						                <div class="tableW">'.$discription.'</div>
						                 <div class="pull-right paddingTop">
						                 '.$buttons.'
						                 '.$_buttons.'
						              	</div>
						              	</div>
			                   		 </div>
			                   		 </div>
						                ';	
						}
					}
					if ($_page == 1 and $j != 0)
					{
						echo '<div class="pagination pagination-centered">
					  			<ul>
							    <li class="active"><a href="#">1</a></li>
								</ul>
							  </div>';
					}
					else 
					{
						if($j != 0)
						{
							echo '<div class="pagination pagination-centered"><ul>';
							for($i = 1;$i <= $_page; $i++)
							{
								if ($showPage == $i)
								{
									echo '<li class="active"><a href="#">'.$i.'</a></li>';
								}
								else 
								{
									echo '<li><a href="tickets.php?method='.$method.'&page='.$i.'">'.$i.'</a></li>';
								}
							}
							echo '</ul></div>';
						}
					}
					echo '</section>
						</article>';
				}
				else 
				{
					echo '
						<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">
						<div class="alert alert-info">
						<center><b>NO TICKETS</b></center>
						</div>
						</section>
						</article>';
				}
				if(@$j == "0")
				{
					echo '
						<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">
						<div class="alert alert-info">
						<center><b>NO TICKETS</b></center>
						</div>
						</section>
						</article>';
				}
			break;

			default:
				echo '<article class="container" style="margin-top:-18px;">
						<section class="well well-small mePadd">
						<div class="alert alert-info">
						<center>ERROR!</center>
						</div>
						</section>
					  </article>';
			break;	
		}
	}
?>
<?php include 'footer.php'; endif;?>
