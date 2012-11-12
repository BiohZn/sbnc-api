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
    function success($message, $data=false) {
        $message = json_encode(array(
            'head' => array(
                'success' => true
            ),
            'body' => array(
				'code'	=> 200,
                'message' => $message,
				'data' => $data
            )
        ));
        $this->show($message);
    }

    function error($message, $code=400) {
        $message = json_encode(array(
            'head' => array(
                'success' => false
            ),
            'body' => array(
                'code' => $code,
                'message' => $message,
				'data' => false
            )
        ));
        $this->show($message);
    }
}

?>
