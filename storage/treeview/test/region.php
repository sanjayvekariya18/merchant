<?php
$servername = "localhost";
$username = "root";
$password = "123456";
$dbname = "dev_v400";

// Create connection
$databaseConnection = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($databaseConnection->connect_error) {
    die("Connection failed: " . $databaseConnection->connect_error);
}
$regionJson = file_get_contents('region.json');
$regionJsonArray = json_decode($regionJson, true);
// First, start by looping through each 'parent':
foreach($regionJsonArray as $regionJsonRegionKey => $regionJsonRegionValue)
{
	if(isset($regionJsonRegionValue['items']))
	foreach($regionJsonRegionValue['items'] as $continentKey => $continentValue)
	{
		if($continentValue['text'] == 'Asia' || $continentValue['text'] == 'Europe')
		{
			if(isset($continentValue['items']))
			foreach($continentValue['items'] as $subContinentKey => $subContinentValue)
			{
				if(isset($subContinentValue['items']))
				foreach($subContinentValue['items'] as $countryKey => $countryValue)
				{
					$countryValue['text'] = mysqli_real_escape_string($databaseConnection,preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $countryValue['text']));
					$selectCountry = "select * from location_country where country_name = '".$countryValue['text']."' LIMIT 1";
					$countryResult=mysqli_query($databaseConnection,$selectCountry);
					$countryExist = mysqli_fetch_array($countryResult,MYSQLI_NUM);
					if($countryExist)
					{
						$lastInsertedCountryId = $countryExist[0];
					} else {
						$insertCountry = "INSERT INTO location_country(country_name) VALUES ('".$countryValue['text']."')";
						$databaseConnection->query($insertCountry);
						$lastInsertedCountryId = $databaseConnection->insert_id;
					}
					if(isset($countryValue['items']))
					{
						foreach($countryValue['items'] as $stateKey => $stateValue)
						{
							$stateValue['text'] = mysqli_real_escape_string($databaseConnection,preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $stateValue['text']));
							$selectState = "select * from location_state where state_name = '".$stateValue['text']."' AND country_id=".$lastInsertedCountryId." LIMIT 1";
							$stateResult=mysqli_query($databaseConnection,$selectState);
							$stateExist = mysqli_fetch_array($stateResult,MYSQLI_NUM);
							if($stateExist)
							{
								$lastInsertedStateId = $stateExist[0];
							} else {
								$insertState = "INSERT INTO location_state(state_name,country_id) VALUES ('".$stateValue['text']."',".$lastInsertedCountryId.")";
								$databaseConnection->query($insertState);
								$lastInsertedStateId = $databaseConnection->insert_id;
							}
							if(isset($stateValue['items']))
							{
								foreach($stateValue['items'] as $cityKey => $cityValue)
								{
									$cityValue['text'] = mysqli_real_escape_string($databaseConnection,preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $cityValue['text']));
									$selectCity = "select * from location_city where city_name = '".$cityValue['text']."' AND state_id=".$lastInsertedStateId." AND country_id=".$lastInsertedCountryId." LIMIT 1";
									$cityResult=mysqli_query($databaseConnection,$selectCity);
									$cityExist = mysqli_fetch_array($cityResult,MYSQLI_NUM);
									if($cityExist)
									{
										$lastInsertedCityId = $cityExist[0];
									} else {
										$insertCity = "INSERT INTO location_city(city_name,state_id,county_id,country_id) VALUES ('".$cityValue['text']."',".$lastInsertedStateId.",0,".$lastInsertedCountryId.")";
										$databaseConnection->query($insertCity);
										$lastInsertedCityId = $databaseConnection->insert_id;
									}
								}
							} else {
								$selectCity = "select * from location_city where city_name = '".$stateValue['text']."' AND state_id=".$lastInsertedStateId." AND country_id=".$lastInsertedCountryId." LIMIT 1";
								$cityResult=mysqli_query($databaseConnection,$selectCity);
								$cityExist = mysqli_fetch_array($cityResult,MYSQLI_NUM);
								if(!$cityExist)
								{
									$insertCity = "INSERT INTO location_city(city_name,state_id,county_id,country_id) VALUES ('".$stateValue['text']."',".$lastInsertedStateId.",0,".$lastInsertedCountryId.")";
									$databaseConnection->query($insertCity);
									$lastInsertedCityId = $databaseConnection->insert_id;
								}
							}
						}
					} else {
						$selectState = "select * from location_state where state_name = '".$countryValue['text']."' AND country_id=".$lastInsertedCountryId." LIMIT 1";
							$stateResult=mysqli_query($databaseConnection,$selectState);
							$stateExist = mysqli_fetch_array($stateResult,MYSQLI_NUM);

						if($stateExist)
						{
							$lastInsertedStateId = $stateExist[0];
						} else {
							$insertState = "INSERT INTO location_state(state_name,country_id) VALUES ('".$countryValue['text']."',".$lastInsertedCountryId.")";
							$databaseConnection->query($insertState);
							$lastInsertedStateId = $databaseConnection->insert_id;
						}
						$selectCity = "select * from location_city where city_name = '".$countryValue['text']."' AND state_id=".$lastInsertedStateId." AND country_id=".$lastInsertedCountryId." LIMIT 1";
						$cityResult=mysqli_query($databaseConnection,$selectCity);
						$cityExist = mysqli_fetch_array($cityResult,MYSQLI_NUM);
						if(!$cityExist)
						{
							$insertCity = "INSERT INTO location_city(city_name,state_id,county_id,country_id) VALUES ('".$countryValue['text']."',".$lastInsertedStateId.",0,".$lastInsertedCountryId.")";
							$databaseConnection->query($insertCity);
							$lastInsertedCityId = $databaseConnection->insert_id;
						}
					}
				}
			}
		} else {
			if(isset($continentValue['items']))
			foreach($continentValue['items'] as $countryKey => $countryValue)
			{
				$countryValue['text'] = mysqli_real_escape_string($databaseConnection,preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $countryValue['text']));
				$selectCountry = "select * from location_country where country_name = '".$countryValue['text']."' LIMIT 1";
				$countryResult=mysqli_query($databaseConnection,$selectCountry);
				$countryExist = mysqli_fetch_array($countryResult,MYSQLI_NUM);
				if($countryExist)
				{
					$lastInsertedCountryId = $countryExist[0];
				} else {
					$insertCountry = "INSERT INTO location_country(country_name) VALUES ('".$countryValue['text']."')";
					$databaseConnection->query($insertCountry);
					$lastInsertedCountryId = $databaseConnection->insert_id;
				}
				if(isset($countryValue['items']))
				{
					foreach($countryValue['items'] as $stateKey => $stateValue)
					{
						$stateValue['text'] = mysqli_real_escape_string($databaseConnection,preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $stateValue['text']));
						$selectState = "select * from location_state where state_name = '".$stateValue['text']."' AND country_id=".$lastInsertedCountryId." LIMIT 1";
						$stateResult=mysqli_query($databaseConnection,$selectState);
						$stateExist = mysqli_fetch_array($stateResult,MYSQLI_NUM);
						if($stateExist)
						{
							$lastInsertedStateId = $stateExist[0];
						} else {
							$insertState = "INSERT INTO location_state(state_name,country_id) VALUES ('".$stateValue['text']."',".$lastInsertedCountryId.")";
							$databaseConnection->query($insertState);
							$lastInsertedStateId = $databaseConnection->insert_id;
						}
						if(isset($stateValue['items']))
						{
							foreach($stateValue['items'] as $cityKey => $cityValue)
							{
								$cityValue['text'] = mysqli_real_escape_string($databaseConnection,preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $cityValue['text']));
								$selectCity = "select * from location_city where city_name = '".$cityValue['text']."' AND state_id=".$lastInsertedStateId." AND country_id=".$lastInsertedCountryId." LIMIT 1";
								$cityResult=mysqli_query($databaseConnection,$selectCity);
								$cityExist = mysqli_fetch_array($cityResult,MYSQLI_NUM);
								if($cityExist)
								{
									$lastInsertedCityId = $cityExist[0];
								} else {
									$insertCity = "INSERT INTO location_city(city_name,state_id,county_id,country_id) VALUES ('".$cityValue['text']."',".$lastInsertedStateId.",0,".$lastInsertedCountryId.")";
									$databaseConnection->query($insertCity);
									$lastInsertedCityId = $databaseConnection->insert_id;
								}
							}
						} else {
							$selectCity = "select * from location_city where city_name = '".$stateValue['text']."' AND state_id=".$lastInsertedStateId." AND country_id=".$lastInsertedCountryId." LIMIT 1";
							$cityResult=mysqli_query($databaseConnection,$selectCity);
							$cityExist = mysqli_fetch_array($cityResult,MYSQLI_NUM);
							if(!$cityExist)
							{
								$insertCity = "INSERT INTO location_city(city_name,state_id,county_id,country_id) VALUES ('".$stateValue['text']."',".$lastInsertedStateId.",0,".$lastInsertedCountryId.")";
								$databaseConnection->query($insertCity);
								$lastInsertedCityId = $databaseConnection->insert_id;
							}
						}
					}
				} else {
					$selectState = "select * from location_state where state_name = '".$countryValue['text']."' AND country_id=".$lastInsertedCountryId." LIMIT 1";
						$stateResult=mysqli_query($databaseConnection,$selectState);
						$stateExist = mysqli_fetch_array($stateResult,MYSQLI_NUM);

					if($stateExist)
					{
						$lastInsertedStateId = $stateExist[0];
					} else {
						$insertState = "INSERT INTO location_state(state_name,country_id) VALUES ('".$countryValue['text']."',".$lastInsertedCountryId.")";
						$databaseConnection->query($insertState);
						$lastInsertedStateId = $databaseConnection->insert_id;
					}
					$selectCity = "select * from location_city where city_name = '".$countryValue['text']."' AND state_id=".$lastInsertedStateId." AND country_id=".$lastInsertedCountryId." LIMIT 1";
					$cityResult=mysqli_query($databaseConnection,$selectCity);
					$cityExist = mysqli_fetch_array($cityResult,MYSQLI_NUM);
					if(!$cityExist)
					{
						$insertCity = "INSERT INTO location_city(city_name,state_id,county_id,country_id) VALUES ('".$countryValue['text']."',".$lastInsertedStateId.",0,".$lastInsertedCountryId.")";
						$databaseConnection->query($insertCity);
						$lastInsertedCityId = $databaseConnection->insert_id;
					}
				}
			}
		}
	}
}
?>