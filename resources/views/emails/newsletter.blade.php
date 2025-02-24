@php
	$newsletter = [
		'content' => $array['content'],
		'date' => date('Y-m-d'),
		'timestamp' => time()
	];
	echo $newsletter['content'];
@endphp
