<?php  
function time_elapsed_string($ptime) {
    $etime = time() - $ptime;
    if ($etime < 1) {
        return '0 seconds';
    }
    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
    );
    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
        }
    }
}
$url="https://maps.googleapis.com/maps/api/place/details/json?reference=CoQBcwAAAKkZDZXgFlZArtLoVIIWcKchGRGR92PvHkpiKbUUfWKzeMcJZBeXJvdUPv_gJkUrYxHOk1FTTu9E5Wz_A0bnCnugcLyD3guyjp5WNpIaW6T9nKUP61hUe5PxrkDGMVyy0L3SHQS0SelJvKzKaGkqSaOH_Vg7FHVsV3Qs5NVzSoYBEhDecT89hXnnmuN28caJAp3YGhToYKWP-lUoKkhxl_CKKiKcrBV13Q&sensor=false&key=AIzaSyA_C717-NtrzSbyoOCujwlobjTRXL-FerM";
$ch = curl_init();
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL,$url);
$result = curl_exec($ch);
$result = json_decode($result, true);
$reviews = array();
foreach ($result['result']['reviews'] as $review) {
	$rating = array();
	foreach ($review['aspects'] as $aspect) {
		$rating[] = $aspect['rating'];
	}
	$ratio = round(array_sum($rating)/count($rating)/3*5,1);
	$reviews[] = array(
		'author_name' => $review['author_name'],
		'author_url' => $review['author_url'],
		'text' => $review['text'],
		'time' => time_elapsed_string($review['time']),
		'rating' => $ratio,
	);
}
$response = array(
	'rating' => $result['result']['rating'],
	'url' => $result['result']['url'],
	'reviews' => $reviews
);
?>
<div class="google-reviews">
	<h4>
		<?php echo $response['rating']; ?>
		<?php 
		$starNumber = ceil($response['rating']*2)/2;
		for($x=1;$x<=$starNumber;$x++) {
        	echo '<img src="/skin/frontend/avianne/default/images/star-large.png" />';
	    }
	    if (strpos($starNumber,'.')) {
	        echo '<img src="/skin/frontend/avianne/default/images/half-star-large.png" />';
	        $x++;
	    }
	    while ($x<=5) {
	        echo '<img src="/skin/frontend/avianne/default/images/blank-star-large.png" />';
	        $x++;
	    }	
	    ?>
	</h4>
	<hr />
	<div class="reviews">
		<?php foreach ($response['reviews'] as $review): ?>
			<div class="rev-box">
				<div class="rev-image"><a href="<?php echo $review['author_url'];?>"><img src="/skin/frontend/avianne/default/images/gavatar.png" /></a></div>
				<div class="rev-block">
					<a href="<?php echo $review['author_url'];?>"><strong><?php echo $review['author_name'];?></strong></a>
					<p>
					<?php 
					$starNumber = ceil($review['rating']*2)/2;
					for($x=1;$x<=$starNumber;$x++) {
			        	echo '<img src="/skin/frontend/avianne/default/images/star.png" />';
				    }
				    if (strpos($starNumber,'.')) {
				        echo '<img src="/skin/frontend/avianne/default/images/half-star.png" />';
				        $x++;
				    }
				    while ($x<=5) {
				        echo '<img src="/skin/frontend/avianne/default/images/blank-star.png" />';
				        $x++;
				    }	
				    ?>
				    <span><?php echo $review['time']; ?></span>
					</p>
					<?php echo $review['text'];?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>