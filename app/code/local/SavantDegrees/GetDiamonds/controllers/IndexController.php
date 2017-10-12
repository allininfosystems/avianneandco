<?php

class SavantDegrees_GetDiamonds_IndexController extends Mage_Core_Controller_Front_Action
{
	private function replaceNames($old, $new="", &$array){
		foreach ($array as $key => $value) {
			if($value == $old) {
				$array[$key] = $new ? $new : strtolower($old);
				return;
			}
		}
	}
	public function indexAction()
	{
		set_time_limit(0);
		ini_set("memory_limit","720M");
		
		if(!file_exists("script_state2")) file_put_contents("script_state2","0,100,0");
		
		$script_file=file_get_contents("script_state2");
	
		
		
		$scriptState = explode(",",$script_file);

		//if($scriptState[0] == $scriptState[1] ) die;
		if($scriptState[0] == $scriptState[1]) {
			$db = Mage::getSingleton('core/resource')->getConnection('core_write');
			$result = $db->query("DELETE FROM dev_products");
		} else {
			$continueFrom = $scriptState[0];
		}

		$model = Mage::getModel('product/product');
		//
		if($scriptState[0] == $scriptState[1]) {
			$model->pullFromRapnet(39);
			$model->pullFromRapnet(37);
			file_put_contents("script_state","0,100,0");
		}
		
		$products = $model->readFeedFile(39);
		$products2 = $model->readFeedFile(37);
		array_shift($products2); // just clean header
		$fields = array_shift($products); // get fields which are in the header

		$products = array_merge($products, $products2);
		//		 fix names

		$this->replaceNames("Lot #", "lot", $fields);
		$this->replaceNames("Ratio", "", $fields);
		$this->replaceNames("Owner", "", $fields);
		$this->replaceNames("Shape", "", $fields);
		$this->replaceNames("Carat", "", $fields);
		$this->replaceNames("Color", "", $fields);
		$this->replaceNames("Clarity", "", $fields);
		$this->replaceNames("Cut Grade", "cut", $fields);
		$this->replaceNames("Price", "", $fields);
		$this->replaceNames("%/Rap", "Rap", $fields);
		$this->replaceNames("Table", "TablePercent", $fields);
		$this->replaceNames("Fluor", "Flour", $fields);
		$this->replaceNames("# Stones", "Stones", $fields);
		$this->replaceNames("Cert #", "Cert_n", $fields);
		$this->replaceNames("Stock #", "Stock_n", $fields);
		$this->replaceNames("Image", "certimage", $fields);
		//		echo "<pre>";
		//		print_r($fields);
		//print_r($fields2);
		//exit;
		$fields2 = array_flip($fields);


		$cnt = count($products);
		$cntFields = count($fields);
		//		echo "<pre>";
		//		print_r($fields);exit;

		$rulesShape = array(
		"Ro" => "B",
		"Pr" => "PR",
		"Ra" => "R",
		"Em" => "E",
		"As" => "AS",
		"Ov" => "O",
		"Ma" => "M",
		"Pe" => "P",
		"He" => "H",
		"Cu" => "C");

		$check = array(
		"Sym"=>array(
		'0' => 'ID',
		'1' => 'EX',
		'2' => 'VG',
		'3' => 'GD',
		'4' => 'F'
		),

		"Polish" =>array(
		'0' => 'ID',
		'1' => 'EX',
		'2' => 'VG',
		'3' => 'GD',
		'4' => 'F'
		),

		'Flour'=>array(
		'0' => 'NO',
		'1' => 'FB',
		'2' => 'MB',
		'3' => 'MED',
		'4' => 'ST BLUE',
		'5' => 'VST BLUE'
		),

		'clarity'=>array(
		'0' => 'IF',
		'1' => 'VVS1',
		'2' => 'VVS2',
		'3' => 'VS1',
		'4' => 'VS2',
		'5' => 'SI1',
		'6' => 'SI2',
		'7' => 'I1'
		),

		'color'=>array(
		'0' => 'D',
		'1' => 'E',
		'2' => 'F',
		'3' => 'G',
		'4' => 'H',
		'5' => 'I',
		'6' => 'J'
		)
		,'cut'=>array(
		'0' => 'Good',
		'1' => 'Very Good',
		'2' => 'Ideal',
		'3' => 'Premium'
		));

		for ($i = isset($continueFrom) ? $continueFrom+1 : 0; $i < $cnt; $i++){
			file_put_contents("script_state","$i," . ($cnt-1 ). ",". time() );
			//						echo "<pre>";
			//			print_r($products[$i]);
			if(!isset($products[$i][$fields2['Cert']])) continue;
			if( !stristr($products[$i][$fields2['Cert']],'gia')  and  !stristr($products[$i][$fields2['Cert']],'egl') ) continue;

			//			$products[$i][23] = date("Y-m-d", strtotime($products[$i][23]));	 // convert to mysql date
			for ($j = 0; $j < $cntFields; $j++){
				$continue = 0;
				if($fields[$j] == 'price') {
					$products[$i][$j] = $products[$i][$j] * $products[$i][$fields2['carat']];
					$mul = 0;
					if($products[$i][$j] > 250 and $products[$i][$j] < 499) $mul = 1.35;
					if($products[$i][$j] > 500 and $products[$i][$j] < 999) $mul = 1.25;
					if($products[$i][$j] > 1000 and $products[$i][$j] < 9999) $mul = 1.17;
					if($products[$i][$j] > 10000 and $products[$i][$j] < 14999) $mul = 1.16;
					if($products[$i][$j] > 20000 and $products[$i][$j] < 39999) $mul = 1.14;
					if($products[$i][$j] > 40000 and $products[$i][$j] < 44999) $mul = 1.12;
					if($products[$i][$j] > 45000 and $products[$i][$j] < 100000) $mul = 1.12;
					if($products[$i][$j] > 100001 and $products[$i][$j] < 1000000) $mul = 1.11;

					if($mul)
					$products[$i][$j] *= $mul;
					else
					$continue = true;
				}


				if($fields[$j] == 'shape' ) {
					$products[$i][$j] = substr($products[$i][$j],0,2);
					$products[$i][$j] = isset($rulesShape[$products[$i][$j]]) ? $rulesShape[$products[$i][$j]] : $products[$i][$j];
				}				

				if($fields[$j] != 'cut' and $fields[$j] != 'Girdle') {
					if (stristr($products[$i][$j], "Very Good")) {
						$products[$i][$j] = 'VG';
					} elseif (stristr($products[$i][$j], "Good")){
						$products[$i][$j] = 'GD';
					} elseif (stristr($products[$i][$j], "Excellent")){
						$products[$i][$j] = 'EX';
					} elseif (stristr($products[$i][$j], "Fair")){
						$products[$i][$j] = 'F';
					} elseif (stristr($products[$i][$j], "None")){
						$products[$i][$j] = 'NO';
					} elseif (stristr($products[$i][$j], "Faint Blu")){
						$products[$i][$j] = 'FB';
					} elseif (stristr($products[$i][$j], "Very Slig")){
						$products[$i][$j] = 'VS';
					} elseif (stristr($products[$i][$j], "Faint")){
						$products[$i][$j] = 'F';
					} elseif (stristr($products[$i][$j], "Medium")){
						$products[$i][$j] = 'MED';
					} elseif (stristr($products[$i][$j], "Medium Bl")){
						$products[$i][$j] = 'MB';
					} elseif (stristr($products[$i][$j], "Medium Wl")){
						$products[$i][$j] = 'MW';
					} elseif (stristr($products[$i][$j], "Strong")){
						$products[$i][$j] = 'S';
					} elseif (stristr($products[$i][$j], "Small")){
						$products[$i][$j] = 'SM';
					} elseif (stristr($products[$i][$j], "Very Smal")){
						$products[$i][$j] = 'VS';
					}
				}

				//				if(($fields[$j] == 'Sym' or $fields[$j] == 'Polish' ) and !in_array($products[$i][$j],$check[$fields[$j]]))
				//					$products[$i][$j] = 'GD';
				////
				//				if(($fields[$j] == 'color')) {
				//					$products[$i][$j] = str_replace("+","",$products[$i][$j]);
				//				}
				//				if(($fields[$j] == 'color') and !in_array($products[$i][$j],$check[$fields[$j]]))
				//					$products[$i][$j] = 'I';
				////
				////				if(($fields[$j] == 'clarity') and !in_array($products[$i][$j],$check[$fields[$j]]))
				////					$products[$i][$j] = 'VVS1';
				////
				//				if(($fields[$j] == 'Flour') and !in_array($products[$i][$j],$check[$fields[$j]]))
				//					$products[$i][$j] = 'NO';
				////
				//				if(($fields[$j] == 'cut') and stristr($products[$i][$j],'Excellent')) {
				//					$products[$i][$j] = 'Premium';
				//				}
				//				if(($fields[$j] == 'cut') and !in_array($products[$i][$j],$check[$fields[$j]]))
				//					$products[$i][$j] = 'Good';

				//				echo "$fields[$j] = ".$products[$i][$j]."<br>";
				$model->setData($fields[$j],$products[$i][$j]);
			}
			if(!$continue) {
				$model->save();
			}

			$model->cleanModelCache();
			
			//			if($i == 2) exit;
		}
	}
}
