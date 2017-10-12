<?php
if (@!$_GET['go'])
    exit('no go');

$res = mysql_pconnect('192.168.100.233', 'aviannea_ijMVXma', 'T4rAflZetaB3U');
if (strpos(__FILE__, 'dev.avianneandco.com') === false) {
    mysql_select_db('aviannea_store');
} else {
    mysql_select_db('aviannea_update');
}
mysql_query("SET NAMES 'utf8';", $res);
mysql_query("SET CHARACTER SET 'utf8';", $res);

$query = 'SELECT `entity_id` FROM `catalog_product_entity_varchar` WHERE attribute_id = 96 AND value LIKE "%eternity%" AND (value LIKE "%ring%" OR value LIKE "%band%")' 
	. (@$_GET['go'] != 'go' && is_numeric($_GET['go']) ? 'AND entity_id=' . (int)$_GET['go'] : '');
$res = mysql_query($query);
$products=array();
while ($ret = mysql_fetch_array($res)) {
    $products[]=$ret['entity_id'];
    $query = "UPDATE catalog_product_entity set has_options=1 where entity_id=".$ret['entity_id']." ";
    $res1 = mysql_query($query);
}

var_dump('<hr>$products', $products, '<hr>');

foreach ($products as $product){
    /* Remove previous attempts */
    $ss = mysql_query("SELECT t1.option_id 
			FROM catalog_product_option AS t1 
			INNER JOIN catalog_product_option_title AS t2 
			    USING ( option_id ) 
			WHERE (t2.title LIKE '%Ring Size%' OR t2.title LIKE '%Ring Sizing%') AND t1.product_id =".$product);
    
    while ($rr = mysql_fetch_array($ss)) {
	mysql_query("DELETE FROM catalog_product_option_title WHERE option_id = ".$rr['option_id']);
	mysql_query("DELETE FROM catalog_product_option_price WHERE option_id = ".$rr['option_id']);
	mysql_query("DELETE FROM catalog_product_option WHERE option_id = ".$rr['option_id']);
    }
    $query = "insert into catalog_product_option (product_id,type,is_require,file_extension,image_size_x,image_size_y,sort_order) values (".$product.",'drop_down',0,'tmp',0,0,0)";
    $res1 = mysql_query($query);
}

$res = mysql_query('select * from catalog_product_option WHERE file_extension = "tmp"');
while ($ret = mysql_fetch_array($res)) {
    $query = "insert into catalog_product_option_title (option_id,store_id,title) values ($ret[0], 0, 'Ring Size')";
    $res1 = mysql_query($query);
    $query = "insert into catalog_product_option_price (option_id,store_id,price,price_type) values ($ret[0], 0, 0.0000, 'fixed')";
    $res1 = mysql_query($query);
    
}

$sizes = array("4", "4 1/2", "5", "5 1/2", "6", "6 1/2", "7", "7 1/2", "8", "8 1/2", "9", "9 1/2", "10", "10 1/2", "11", "11 1/2", "12", "12 1/2", "13", "13 1/2", "14", ); 

$res = mysql_query("SELECT option_id, product_id FROM catalog_product_option WHERE product_id IN (".implode(",", $products).") AND file_extension = 'tmp'");
while ($ret = mysql_fetch_array($res)) {
    $ic = 0;

    $__res = mysql_query("SELECT o.*, d.value AS price, v.value AS dsize
                          FROM catalog_product_option o
                          LEFT JOIN catalog_product_entity_decimal d ON (o.product_id = d.entity_id AND d.attribute_id=567)
                          LEFT JOIN catalog_product_entity_varchar v ON (o.product_id = v.entity_id AND v.attribute_id=892)
                          WHERE o.option_id = " . $ret["option_id"]);
    while ($__ret = mysql_fetch_array($__res)) 
    {
        foreach ($sizes AS $xindex=>$xsize) 
        {
            $query = "insert into catalog_product_option_type_value (option_id,sku,sort_order) values (".$__ret['option_id'].", '', $xindex)";
            $res1 = mysql_query($query);
            $otid = mysql_insert_id();

            if (!$__ret['dsize']) 
            {
                $r = mysql_query("SELECT o.*, d.value AS price, v.value AS dsize
                                    FROM catalog_product_option o
                                    LEFT JOIN catalog_product_entity_decimal d ON (o.product_id = d.entity_id AND d.attribute_id=567)
                                    LEFT JOIN catalog_product_entity_varchar v ON (o.product_id = v.entity_id AND v.attribute_id=892)
                                    WHERE o.option_id = " . $ret["option_id"]);
                $l = mysql_fetch_assoc($r);
                $__ret['dsize'] = $l['dsize'];
            }
            if (!$__ret['dsize']) {
                $__ret['dsize'] = 7;
@$NOSIZE[] = $__ret['product_id'];
            }
echo $__ret['dsize'].' =&gt; '.(int)$__ret['dsize'].' =&gt; ';
            $__ret['dsize'] = (int)$__ret['dsize'];
            if (!in_array($__ret['dsize'], $sizes)) {
                $t = $sizes[0];
                foreach ($sizes as $s) {
                    if ($s <= $__ret['dsize']) {
                        $t = $s;
                    }
echo'<i>'.$__ret['dsize'].'vs'.$s.'('.$t.')</i> ';
                }
                $__ret['dsize'] = $t;
            }

echo $__ret['dsize'].' <b> | </b>';

	    $dindex = array_search($__ret['dsize'], $sizes);
	    $xpercent = ($xindex - $dindex) * 5;

echo'dsize#: [' . $dindex . '] '.$sizes[$dindex].'; xsize#: [' . $xindex . '] ' . $sizes[$xindex] . ' =&gt; %: ' . $xpercent . '<br>';

//            $s_price = round(($__ret['price'] / $__ret['dsize']) * $xsize);//, 2);
//            $s_diff = $s_price - $__ret['price'];

            $query = "insert into catalog_product_option_type_price (option_type_id,store_id,price,price_type) values ($otid, 0, $xpercent, 'percent')";
            $res1 = mysql_query($query);

            $query = "insert into catalog_product_option_type_title (option_type_id,store_id,title) values ($otid, 0, '$xsize')";
            $res1 = mysql_query($query);
	    $ic++;
        }
    }
    mysql_query("UPDATE catalog_product_option SET file_extension = NULL WHERE option_id=".$ret['option_id']);
}

echo '<hr><hr><hr>';
if (count($NOSIZE)) {
    echo 'NOSIZE has '.count($NOSIZE).'el-s. ';
    var_dump($NOSIZE);
}
?>