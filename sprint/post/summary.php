<?php
$total_week = $snapshot['sprint']['total'] + $snapshot['completed']['total'];
foreach($snapshot['included'] as $id=>$data){
	$total_week += $data['total'];
}

$total_achieved = $snapshot['completed']['total'];
$total_remaining = $total_week - $total_achieved;
$percentage_achieved = round(($total_achieved/$total_week)*100);
$percentage_remaining = round(($total_remaining/$total_week)*100);


foreach($additions as $id=>$data){
	$additions[$id]['total'] = 0;
	$additions[$id]['total'] += $snapshot['sprint']['additions'][$id]['total'];
	$additions[$id]['total'] += $snapshot['completed']['additions'][$id]['total'];

	foreach($snapshot['included'] as $idList=>$data_included){
		$additions[$id]['total'] += $data_included['additions'][$id]['total'];
	}

	$additions[$id]['percentage'] = round(($additions[$id]['total'] / $total_week)*100);
}


