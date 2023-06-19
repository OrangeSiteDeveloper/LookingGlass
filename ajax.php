<?php
/*
 * Copyright (C) 2023 jellybean13
 * 
 * Modification based on code covered by the mentioned copyright
 * and/or permission notice(s).
 */

/*
 * Copyright (C) 2015 Nick Adams <nick@iamtelephone.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
// Create a session or resume the current one.
if (!isset($_SESSION)) {
    session_start();
}

/*
* Uncomment below to enable debug output.
* --------------------------------------
// error_reporting(E_ALL);
// ini_set('display_errors', 'on');
*/

// Check whether the CSRF token is valid.
if (isset($_SESSION['csrf_token_network_tests']) && isset($_POST['csrf_token_network_tests'])
        && hash_equals($_SESSION['csrf_token_network_tests'], $_POST['csrf_token_network_tests'])) {
    // Define available commands.
    $cmds = array('dig', 'host', 'mtr', 'nslookup', 'ping', 'traceroute');
    // Check whether command and host are set.
    if (isset($_POST['cmd']) && in_array($_POST['cmd'], $cmds) && isset($_POST['host'])) {
        // Include required scripts.
        $required = array('LookingGlass.php', 'RateLimit.php', 'Utils.php', 'Config.php');
        foreach ($required as $val) {
            require 'LookingGlass/' . $val;
        }

        // Lazy check.
        if (!isset($rateLimit)) {
            $rateLimit = 0;
        }

        // Instantiate LookingGlass & RateLimit.
        $lg = new Telephone\LookingGlass();
        $limit = new Telephone\LookingGlass\RateLimit();

        // Check client IP against database.
        $limit->rateLimit($rateLimit, Telephone\Utils::getClientIPAddress($httpHeaderNameGetClientIPAddress));

        // Execute command.
        $output = null;
        switch ($_POST['cmd']) {
            case "dig":
                $output = $lg->{$_POST['cmd']}($_POST['address_family'], $_POST['destination_port'], $_POST['dns_lookup_protocol'], $_POST['dns_lookup_mode'], $_POST['dns_lookup_encryption_mode'], $_POST['dns_lookup_query_uri'], $_POST['dns_lookup_record_type'], $_POST['dns_lookup_edns_client_subnet'], $_POST['host'], $_POST['dns_lookup_server']);
                break;
            case "host":
            case "nslookup":
                $output = $lg->{$_POST['cmd']}($_POST['address_family'], $_POST['destination_port'], $_POST['dns_lookup_protocol'], $_POST['dns_lookup_record_type'], $_POST['host'], $_POST['dns_lookup_server']);
                break;
            case "mtr":
                $output = $lg->{$_POST['cmd']}($_POST['interface'], $_POST['address_family'], $_POST['destination_port'], $_POST['traceroute_protocol'], $_POST['traceroute_first_hop_limit'], $_POST['traceroute_max_hop_limit'], filter_var($_POST['traceroute_display_icmp_extensions'], FILTER_VALIDATE_BOOLEAN), filter_var($_POST['traceroute_display_as_number'], FILTER_VALIDATE_BOOLEAN), filter_var($_POST['resolve_hostname'], FILTER_VALIDATE_BOOLEAN), $_POST['host']);
                break;
            case "traceroute":
                $output = $lg->{$_POST['cmd']}($_POST['interface'], $_POST['address_family'], $_POST['destination_port'], $_POST['traceroute_protocol'], $_POST['traceroute_first_hop_limit'], $_POST['traceroute_max_hop_limit'], filter_var($_POST['traceroute_display_icmp_extensions'], FILTER_VALIDATE_BOOLEAN), filter_var($_POST['traceroute_display_mtu_info'], FILTER_VALIDATE_BOOLEAN), filter_var($_POST['traceroute_display_as_number'], FILTER_VALIDATE_BOOLEAN), filter_var($_POST['resolve_hostname'], FILTER_VALIDATE_BOOLEAN), $_POST['host']);
                break;
            case "ping":
                $output = $lg->{$_POST['cmd']}($_POST['interface'], $_POST['address_family'], $_POST['ping_hop_limit'], filter_var($_POST['resolve_hostname'], FILTER_VALIDATE_BOOLEAN), $_POST['host']);
                break;
        }

        if ($output) {
            exit();
        }
    } else {
        exit('Unauthorized request');
    }
} else {
    exit('Wrong CSRF token');
}
