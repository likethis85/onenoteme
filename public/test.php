<?php

phpinfo();
exit;
    $deviceToken = '7e88716c b7323807 515b8a12 03fe41e3 71382da5 77d55caa 991db8b4 f0610f44'; // 可以用你获得的DeviceToken替换
    $pass = '';   // Passphrase for the private key (ck.pem file)
       
    // Get the parameters from http get or from command line
    $message = $_GET['message'] or $message = $argv[1] or $message = 'A test message from worldcup';
    $badge = (int)$_GET['badge'] or $badge = (int)$argv[2];
    $sound = $_GET['sound'] or $sound = $argv[3];
    
    $message = 'test message';
    $badge = 8;
    $sound = '';
       
    // Construct the notification payload
    $body = array();
    $body['aps'] = array('alert' => $message);
    if ($badge)
      $body['aps']['badge'] = $badge;
    if ($sound)
      $body['aps']['sound'] = $sound;
       
    /* End of Configurable Items */
    $ctx = stream_context_create();
    stream_context_set_option($ctx, 'ssl', 'local_cert', 'waduanzi_ck.pem');
    // assume the private key passphase was removed.
    stream_context_set_option($ctx, 'ssl', 'passphrase', $pass);
       
    // connect to apns
    $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT, $ctx);
    if (!$fp) {
        print "Failed to connect $err $errstr\n";
        return;
    }
    else {
       print "Connection OK\n<br/>";
    }
       
    // send message
    $payload = json_encode($body);
    $msg = chr(0) . pack("n",32) . pack('H*', str_replace(' ', '', $deviceToken)) . pack("n",strlen($payload)) . $payload;
    print "Sending message :" . $payload . "\n";
    fwrite($fp, $msg);
    fclose($fp);