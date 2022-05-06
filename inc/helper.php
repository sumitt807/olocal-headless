<?php
if (!function_exists('pr')) {
    function pr($data = array())
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}

function getRewriteRulesFromDB()
{
    $response = array();
    $data = excuteQuery("SELECT * FROM `" . TABLE_PREFIX . "options` WHERE option_name = 'rewrite_rules';");
    $rewrite = unserialize($data['option_value']);
    $match = '';
    if (!empty($rewrite)) {

        // If we match a rewrite rule, this will be cleared.
        $error               = '404';
        $wp_rewrite_index = 'index.php';
        $wp_rewrite_use_verbose_page_rules = '';


        $pathinfo         = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
        list($pathinfo) = explode('?', $pathinfo);
        $pathinfo         = str_replace('%', '%25', $pathinfo);

        list($req_uri) = explode('?', $_SERVER['REQUEST_URI']);
        $self            = $_SERVER['PHP_SELF'];
        $home_path       = trim(parse_url(home_url(), PHP_URL_PATH), '/');
        $home_path_regex = sprintf('|^%s|i', preg_quote($home_path, '|'));

        /*
			 * Trim path info from the end and the leading home path from the front.
			 * For path info requests, this leaves us with the requesting filename, if any.
			 * For 404 requests, this leaves us with the requested permalink.
			 */
        $req_uri  = str_replace($pathinfo, '', $req_uri);
        $req_uri  = trim($req_uri, '/');
        $req_uri  = preg_replace($home_path_regex, '', $req_uri);
        $req_uri  = trim($req_uri, '/');
        $pathinfo = trim($pathinfo, '/');
        $pathinfo = preg_replace($home_path_regex, '', $pathinfo);
        $pathinfo = trim($pathinfo, '/');
        $self     = trim($self, '/');
        $self     = preg_replace($home_path_regex, '', $self);
        $self     = trim($self, '/');


        $response = explode("/", $req_uri);
        // The requested permalink is in $pathinfo for path info requests and
        // $req_uri for other requests.
        if (!empty($pathinfo) && !preg_match('|^.*' . $wp_rewrite_index . '$|', $pathinfo)) {
            $requested_path = $pathinfo;
        } else {
            // If the request uri is the index, blank it out so that we don't try to match it against a rule.
            if ($req_uri == $wp_rewrite_index) {
                $req_uri = '';
            }
            $requested_path = $req_uri;
        }
        $requested_file = $req_uri;

        $request = $requested_path;

        // Look for matches.
        $request_match = $requested_path;
        if (empty($request_match)) {
            // An empty request could only match against ^$ regex.
            if (isset($rewrite['$'])) {
                $matched_rule = '$';
                $query              = $rewrite['$'];
                $matches            = array('');
            }
        } else {

            foreach ((array) $rewrite as $match => $query) {
                // If the requested file is the anchor of the match, prepend it to the path info.
                if (!empty($requested_file) && strpos($match, $requested_file) === 0 && $requested_file != $requested_path) {
                    $request_match = $requested_file . '/' . $requested_path;
                }

                if (
                    preg_match("#^$match#", $request_match, $matches) ||
                    preg_match("#^$match#", urldecode($request_match), $matches)
                ) {

                    if ($wp_rewrite_use_verbose_page_rules && preg_match('/pagename=\$matches\[([0-9]+)\]/', $query, $varmatch)) {
                        // This is a verbose page match, let's check to be sure about it.

                        $page = get_page_by_path($matches[$varmatch[1]]);
                        if (!$page) {
                            continue;
                        }

                        $post_status_obj = get_post_status_object($page->post_status);
                        if (
                            !$post_status_obj->public && !$post_status_obj->protected
                            && !$post_status_obj->private && $post_status_obj->exclude_from_search
                        ) {
                            continue;
                        }
                    }


                    break;
                }
            }
        }
    }



    $template_array = array(
        '^events/category/([^/]*)/?$' => 'events_cate'
    );



    if (isset($template_array[$match]) && !empty($rewrite[$match])) {
        $response['template_name'] = $template_array[$match];
    } else if (isset($match)) {
        $response['template_name'] = 'Template is not define. ' . $match;
        $response['template'] = 'error';
    } else {
        $response['template_name'] = 'main';
    }

    return $response;
}


function home_url()
{
    return SITE_URL;
}
