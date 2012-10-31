<?php

/*
200 OK
    Command completed successfully.
400 Bad Request
    Invalid URL, missing argument etc.
404 Not found
    object does not exist.
500 Internal Server Error
    an error has occurred.
*/

class json {
        
    function show($message) {
        header('Content-Type: application/json');
        print $message;
    }
    function success($message) {
        $message = json_encode(array(
            'head' => array(
                'status' => 200
            ),
            'body' => array(
                'message' => $message
            )
        ));
        $this->show($message);
    }
    
    function error($message, $code=400) {
        $message = json_encode(array(
            'head' => array(
                'status' => 0
            ),
            'body' => array(
                'error_code' => $code,
                'error_message' => $message
            )
        ));
        $this->show($message);
    }
}

?>
