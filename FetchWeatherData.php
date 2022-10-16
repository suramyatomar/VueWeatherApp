
<?php

    // Function to get the client IP address
    // Since different servers put the info in different files, we check all options
    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

// Start off by Getting the Client IP
      $client_ip = get_client_ip();
      $url="https://api.ip2location.com/v2/?ip=$client_ip&addon=city&key=YNZTHB8WO3&package=WS3&format=json";

      $data = file_get_contents($url); // put the contents of the API Response into a variable
      $ip_data = json_decode($data,true); // decode the JSON feed

      // Check if we got a response. Else default to Bangalore, India for Now
      if(strcmp($ip_data["response"], "OK") && !isset($ip_data["response"]) && !is_null($ip_data["response"]))
      {
        print "here";
        $city=$ip_data["city_name"] . ',' . $ip_data["country_name"]; // We are only interested in City & Country for now
        var_dump($ip_data);
      }
      else
      {
        $city = "Bangalore, India";
      }

// Now that we have the City we can call the API to get the weather info

    $url = "https://api.openweathermap.org/data/2.5/weather?q=$city&appid=15c6182bd5823a3eceaa21b38e3f36bb&lang=pl&units=metric"; // path to your JSON file
    $data = file_get_contents($url); // put the contents of the file into a variable
    $characters = json_decode($data,true); // decode the JSON feed

// To Do: We need to add error check and failover code here for when the API Call fails

    // Create a JSON feed with the data we want
    $return_value = "{\"weather\": \"" . $characters["weather"][0]["main"] . "\",";
    $return_value = $return_value . "\"location\": \"" . $city . "\",";
    $return_value = $return_value . "\"temp\": \"" . $characters["main"]["temp"] . "\",";
    $return_value = $return_value . "\"humidity\": \"" . $characters["main"]["humidity"] . "\",";
    $return_value = $return_value . "\"wind\": \"" . $characters["wind"]["speed"] . "\",";
    $return_value = $return_value . "\"sunrise\": \"" . $characters["sys"]["sunrise"] . "\",";
    $return_value = $return_value . "\"sunset\": \"" . $characters["sys"]["sunset"] . "\"}";

    print ($return_value);

?>
