<?php


function time_elapsed_string($datetime, $full = false) {
    $now = new \DateTime;
    $ago = new \DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);

    return $string ? implode(', ', $string) . ' ago' : 'just now';
}


/**
 * Parses a template argument to the specified value
 * Template variables are defined using double curly brackets: {{ [a-zA-Z] }}
 * Returns the query back once the instances has been replaced
 * 
 * @param string $string
 * @param string $find
 * @param string $replace
 * 
 * @return string
 * 
 * @throws \Exception
 */
if (!function_exists('findReplace')) {
    function findReplace($string, $find, $replace) {
        if (preg_match("/[a-zA-Z\_]+/", $find)) {
            return (string) preg_replace("/\{\{(\s+)?($find)(\s+)?\}\}/", $replace, $string);
        } else {
            throw new \Exception("Find statement must match regex pattern: /[a-zA-Z]+/");
        }
    }
}

function admin_user() {
    $session = session()->get(\App\Http\Controllers\Backend\Authentication\LoginController::BACKEND_LOGGED_IN_SESSION_NAME);

    return $session;
}