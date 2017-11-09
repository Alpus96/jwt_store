<?php
    //  Solve require issue.
    
    $ts = new TokenStore();
    $user_data = (object)['name' => 'John Doe', 'other' => 'some other information about user John Doe. (1)'];
    
    $token = $ts->create(1, $user_data);
    echo json_encode($token);
    
    $valid = $ts->verify($token);
    echo $valid != false ? 'token is valid' : 'token is not valid';
    
    $new = $ts->update($token);
    echo json_encode($new);
    
    $gone = $ts->destroy($new);
    echo $gone != false ? 'token has been removed' : 'token remains';
    
    $still_valid = $ts->verify($new);
    echo $still_valid != false ? 'the token was still active' : 'the token could not be verified'

?>