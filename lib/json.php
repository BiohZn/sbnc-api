<?php

class json {
        
    function show($message) {
        header('Content-Type: application/json');
        print $message;
    }
    function success($message) {
        $message = json_encode(array(
            'head' => array(
                'status' => 1
            ),
            'body' => array(
                'message' => $message
            )
        ));
        $this->show($message);
    }
    
    function error($message, $code=404) {
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
