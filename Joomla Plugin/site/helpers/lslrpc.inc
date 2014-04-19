<?php
    // function to return the data between two tokens (VERY USEFUL)
    function get_data_between($src, $left, $right)
    {
        $ids = stripos($src,$left) + strlen($left);
        if($ids === false) return false;
    
        $ide = stripos($src,$right,$ids);
        if($ide === false) return false;
    
        return(substr($src,$ids,$ide - $ids));
    }
    
    // function to emulate xml-rpc for SL remote data connections
    function sl_rpc ($channel, $intVal, $stringVal)
    {
        // encapsulate request data in xml
        $xml = "<?xml version=\"1.0\"?><methodCall><methodName>llRemoteData</methodName><params><param><value><struct><member><name>Channel</name>
        <value><string>{$channel}</string></value></member><member><name>IntValue</name>
        <value><int>{$intVal}</int></value></member><member><name>StringValue</name>
        <value><string>{$stringVal}</string></value></member>
        </struct></value></param></params></methodCall>";
    
        // open connection to xmlrpc server
		//$url = 'xmlrpc.aditi.lindenlab.com';
		$url = 'xmlrpc.secondlife.com';
		$port = 80;
		$socket = fsockopen($url, $port);
        // send HTTP header
        fputs($socket, "POST /cgi-bin/xmlrpc.cgi HTTP/1.1\r\n");
        fputs($socket, "Host: ".$url."\r\n");
        fputs($socket, "Content-type: text/xml\r\n");
        fputs($socket, "Content-length: ".strlen($xml)."\r\n");
        fputs($socket, "Connection: close\r\n\r\n");
        // send xml data
        fputs($socket, $xml);
        
        // read response from rpc server
        while(!feof($socket)) $response .= fgets($socket);
        
        // close the connection
        fclose($socket);
        
        // parse out the returned data
        $sVal = get_data_between($response, 'StringValue</name><value><string>','</string>');
        $iVal = get_data_between($response, '<value><int>','</int></value>');

        // return the response data in an array
        return array('channel' => $channel, 'string' => $sVal, 'integer' => $iVal);
    }
?>