<?php

function fb_fans($pid, $token = null, $no_of_retries = 2, $pause = 400000) {
    $ret = array();
    $matches = array();
    $url = 'http://www.facebook.com/plugins/fan.php?connections=100&id=' . $pid;
    $context = stream_context_create(array('http' => array('header' => 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:22.0) Gecko/20100101 Firefox/22.0')));
    for ($a = 0; $a < $no_of_retries; $a++) {
        $like_html = file_get_contents($url, false, $context);
        print_r($like_html);
        preg_match_all('{href="https?://www\.facebook\.com/([a-zA-Z0-9._-]+)"}', $like_html, $matches);
        if (empty($matches[1])) {
            // failed to fetch any fans - convert returning array, cause it might be not empty
            return array_keys($ret);
        } else {
            // merge profiles as array keys so they will stay unique
            $ret = array_merge($ret, array_flip($matches[1]));
        }
        // don't get banned as flooder
        usleep($pause);
    }
    return array_keys($ret);
}

function fetch_fb_fans($fanpage_name, $token, $no_of_retries = 10, $pause = 100000 /* 500ms */) {
    $ret = array();
    // get page info from graph
    $fanpage_data = json_decode(file_get_contents('https://graph.facebook.com/' . $fanpage_name . '?access_token=' . $token), true);
    if (empty($fanpage_data['id'])) {
        // invalid fanpage name
        $ret['status'] = 'failed';
        return $ret;
    }
    $matches = array();
    $url = 'https://www.facebook.com/plugins/fan.php?connections=100&id=' . $fanpage_data['id'];
    //echo $url . "\n";
    for ($a = 0; $a < $no_of_retries; $a++) {
        $randIP = "" . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255) . "." . mt_rand(0, 255);
        $context = stream_context_create(array('http' => array('header' => 'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:22.0) Gecko/20100101 Firefox/22.0\nX-Forwarded-For:' . $randIP)));
        $like_html = file_get_contents($url, false, $context);
        preg_match_all('{href="https?://www\.facebook\.com/([a-zA-Z0-9._-]+)" data-jsid="anchor" target="_blank"}', $like_html, $matches);
        if (empty($matches[1])) {
            // failed to fetch any fans - convert returning array, cause it might be not empty
            // return array_keys($ret);
            $pause = $pause * 2;
            //echo "# failed: {$pause}";
        } else {
            $all = ($matches[1]);
            foreach ($all as $key => $value) {
                if (isset($ret[$value])) {
                    continue;
                } else {
                    $ret[$value] = 1;
                    //echo "{$value}\n<br>";
                }
            }
            // merge profiles as array keys so they will stay unique
            // $ret = array_merge($ret, array_flip($matches[1]));
        }
        // don't get banned as flooder
        usleep($pause);
    }
    return array_keys($ret);
}

function get_likes_count($pid, $token) {
    $fanpage_data = json_decode(file_get_contents('https://graph.facebook.com/' . $pid . '?fields=likes&access_token=' . $token), true);
    return $fanpage_data;
}
