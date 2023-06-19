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
namespace Telephone;

/*
 * Create a Looking Glass with output buffering.
 */
class LookingGlass {
    /*
     * Execute a 'dig' command against given host:
     * dig - DNS lookup utility.
     *
     * @param  string $addressFamily
     *   The address family of the host.
     * @param  string $dstPort
     *   The destination port.
     * @param  string $dnsLookupProtocol
     *   The protocol of DNS lookup.
     * @param  string $dnsLookupMode
     *   The mode of DNS lookup.
     * @param  string $dnsLookupEncryptionMode
     *   The encryption mode of DNS queries.
     * @param  string $dnsLookupQueryUri
     *   The URI of DNS over HTTPS (DOH) queries.
     * @param  string $rrType
     *   The record type of DNS lookup.
     * @param  string $clientSubnet
     *   The EDNS client subnet.
     * @param  string $host
     *   IP/URL to perform command against.
     * @param  string $server
     *   The upstream DNS server.
     * @return boolean
     *   True on success
     */
    public function dig($addressFamily, $dstPort, $dnsLookupProtocol, $dnsLookupMode, $dnsLookupEncryptionMode, $dnsLookupQueryUri,
                        $rrType, $clientSubnet, $host, $server) {
        $extraArguments = "";
        switch ($addressFamily) {
            case "ipv4":
                $extraArguments .= " ";
                $extraArguments .= "-4";
                break;
            case "ipv6":
                $extraArguments .= " ";
                $extraArguments .= "-6";
                break;
            default:
                break;
        }
        if (ctype_digit($dstPort) === true && ($dstPort > 0 && $dstPort <= 65535)) {
            $extraArguments .= " ";
            $extraArguments .= "-p";
            $extraArguments .= " ";
            $extraArguments .= $dstPort;
        }
        switch ($dnsLookupMode) {
            case "reversed":
                $extraArguments .= " ";
                $extraArguments .= "-x";
                break;
            default:
                break;
        }
        switch ($dnsLookupEncryptionMode) {
            case "doh":
                $extraArguments .= " ";
                $extraArguments .= "+https";
                // sanitize + remove single quotes
                $dnsLookupQueryUri = str_replace('\'', '', filter_var($dnsLookupQueryUri, FILTER_SANITIZE_URL));
                if (Utils::isEmptyString($dnsLookupQueryUri) === false) {
                    $extraArguments .= "=";
                    $extraArguments .= $dnsLookupQueryUri;
                }
                break;
            case "dot":
                $extraArguments .= " ";
                $extraArguments .= "+tls";
                break;
            default:
                if ($dnsLookupProtocol === "tcp") {
                    $extraArguments .= " ";
                    $extraArguments .= "+vc";
                }
                break;
        }
        switch ($rrType) {
            case "A":
            case "AAAA":
            case "ANY":
            case "CAA":
            case "CDS":
            case "CERT":
            case "CNAME":
            case "DNAME":
            case "DNSKEY":
            case "DS":
            case "HINFO":
            case "HTTPS":
            case "IPSECKEY":
            case "KEY":
            case "MX":
            case "NAPTR":
            case "NS":
            case "NSEC":
            case "NSEC3":
            case "NSEC3PARAM":
            case "PTR":
            case "RP":
            case "RRSIG":
            case "SIG":
            case "SOA":
            case "SPF":
            case "SRV":
            case "SSHFP":
            case "SVCB":
            case "TLSA":
            case "TXT":
            case "WKS":
                $extraArguments .= " ";
                $extraArguments .= "-t";
                $extraArguments .= " ";
                $extraArguments .= $rrType;
                break;
            default:
                break;
        }
        if (Utils::isValidCidr($clientSubnet)) {
            $extraArguments .= " ";
            $extraArguments .= "+subnet=";
            $extraArguments .= $clientSubnet;
        }
        return $this->procExecuteAsync('dig' . $extraArguments, $host, $server, 0, 50);
    }

    /*
     * Execute a 'host' command against given host:
     * host - DNS lookup utility.
     *
     * @param  string $addressFamily
     *   The address family of the host.
     * @param  string $addressFamily
     *   The address family of the host.
     * @param  string $dnsLookupProtocol
     *   The protocol of DNS lookup.
     * @param  string $rrType
     *   The record type of DNS lookup.
     * @param  string $host
     *   IP/URL to perform command against.
     * @param  string $server
     *   The upstream DNS server.
     * @return boolean
     *   True on success
     */
    public function host($addressFamily, $dstPort, $dnsLookupProtocol, $rrType, $host, $server) {
        $extraArguments = "";
        switch ($addressFamily) {
            case "ipv4":
                $extraArguments .= " ";
                $extraArguments .= "-4";
                break;
            case "ipv6":
                $extraArguments .= " ";
                $extraArguments .= "-6";
                break;
            default:
                break;
        }
        if (ctype_digit($dstPort) === true && ($dstPort > 0 && $dstPort <= 65535)) {
            $extraArguments .= " ";
            $extraArguments .= "-p";
            $extraArguments .= " ";
            $extraArguments .= $dstPort;
        }
        switch ($dnsLookupProtocol) {
            case "tcp":
                $extraArguments .= " ";
                $extraArguments .= "-T";
                break;
            default:
                break;
        }
        switch ($rrType) {
            case "A":
            case "AAAA":
            case "ANY":
            case "CAA":
            case "CDS":
            case "CERT":
            case "CNAME":
            case "DNAME":
            case "DNSKEY":
            case "DS":
            case "HINFO":
            case "HTTPS":
            case "IPSECKEY":
            case "KEY":
            case "MX":
            case "NAPTR":
            case "NS":
            case "NSEC":
            case "NSEC3":
            case "NSEC3PARAM":
            case "PTR":
            case "RP":
            case "RRSIG":
            case "SIG":
            case "SOA":
            case "SPF":
            case "SRV":
            case "SSHFP":
            case "SVCB":
            case "TLSA":
            case "TXT":
            case "WKS":
                $extraArguments .= " ";
                $extraArguments .= "-t";
                $extraArguments .= " ";
                $extraArguments .= $rrType;
                break;
            default:
                break;
        }
        return $this->procExecuteAsync('host' . $extraArguments, $host, $server, 0, 50);
    }

    /*
     * Execute a 'mtr' command against given host:
     * mtr - a network diagnostic tool.
     *
     * @param  string $interface
     *   The interface with a specific name for sending packets.
     * @param  string $addressFamily
     *   The address family of the host.
     * @param  string $dstPort
     *   The destination port of the host.
     * @param  string $tracerouteProtocol
     *   The protocol of traceroute.
     * @param  string $firstHopLimit
     *   The initial hop limit of traceroute.
     * @param  string $maxHopLimit
     *   The maximum hop limit of traceroute.
     * @param  boolean $displayICMPExtensions
     *   Whether to display ICMP extensions (RFC 4884) or not.
     * @param  boolean $displayAsNumber
     *   Whether to display AS number or not.
     * @param  boolean $resolveHostname
     *   Whether to resolve hostname or not.
     * @param  string $host
     *   IP/URL to perform command against.
     * @param  intger $fail
     *   Number of failed hops before exiting command.
     * @return boolean
     *   True on success.
     */
    public function mtr($interface, $addressFamily, $dstPort, $tracerouteProtocol, $firstHopLimit, $maxHopLimit, $displayICMPExtensions, $displayAsNumber, $resolveHostname, $host, $fail = 5) {
        $extraArguments = "";
        if (Utils::isEmptyString($interface) === false) {
            $extraArguments .= " ";
            $extraArguments .= "-I";
            $extraArguments .= " ";
            $extraArguments .= $interface;
        }
        switch ($addressFamily) {
            case "ipv4":
                $extraArguments .= " ";
                $extraArguments .= "-4";
                break;
            case "ipv6":
                $extraArguments .= " ";
                $extraArguments .= "-6";
                break;
            default:
                break;
        }
        switch ($tracerouteProtocol) {
            case "tcp":
                $extraArguments .= " ";
                $extraArguments .= "-T";
                if (ctype_digit($dstPort) === true && ($dstPort > 0 && $dstPort <= 65535)) {
                    $extraArguments .= " ";
                    $extraArguments .= "-P";
                    $extraArguments .= " ";
                    $extraArguments .= $dstPort;
                }
                break;
            case "udp":
                $extraArguments .= " ";
                $extraArguments .= "-u";
                if (ctype_digit($dstPort) === true && ($dstPort > 0 && $dstPort <= 65535)) {
                    $extraArguments .= " ";
                    $extraArguments .= "-P";
                    $extraArguments .= " ";
                    $extraArguments .= $dstPort;
                }
                break;
            default:
                break;
        }
        if (ctype_digit($firstHopLimit) === true && ($firstHopLimit > 0 && $firstHopLimit <= 255)) {
            $extraArguments .= " ";
            $extraArguments .= "-f";
            $extraArguments .= " ";
            $extraArguments .= $firstHopLimit;
        }
        if (ctype_digit($maxHopLimit) === true && ($maxHopLimit > 0 && $maxHopLimit <= 255)) {
            $extraArguments .= " ";
            $extraArguments .= "-m";
            $extraArguments .= " ";
            $extraArguments .= $maxHopLimit;
        }
        if ($displayICMPExtensions === true) {
            $extraArguments .= " ";
            $extraArguments .= "-e";
        }
        if ($displayAsNumber === true) {
            $extraArguments .= " ";
            $extraArguments .= "-z";
        }
        if ($resolveHostname !== true) {
            $extraArguments .= " ";
            $extraArguments .= "-n";
        } else {
            $extraArguments .= " ";
            $extraArguments .= "-b";
        }
        return $this->procExecuteAsync('mtr -w' . $extraArguments, $host, null, $fail, 50);
    }

    /*
     * Execute a 'nslookup' command against given host:
     * nslookup - query Internet name servers interactively.
     *
     * @param  string $addressFamily
     *   The address family of the host.
     * @param  string $addressFamily
     *   The address family of the host.
     * @param  string $dnsLookupProtocol
     *   The protocol of DNS lookup.
     * @param  string $rrType
     *   The record type of DNS lookup.
     * @param  string $host
     *   IP/URL to perform command against.
     * @param  string $server
     *   The upstream DNS server.
     * @return boolean
     *   True on success
     */
    public function nslookup($addressFamily, $dstPort, $dnsLookupProtocol, $rrType, $host, $server) {
        $extraArguments = "";
        switch ($addressFamily) {
            case "ipv4":
                $extraArguments .= " ";
                $extraArguments .= "-4";
                break;
            case "ipv6":
                $extraArguments .= " ";
                $extraArguments .= "-6";
                break;
            default:
                break;
        }
        if (ctype_digit($dstPort) === true && ($dstPort > 0 && $dstPort <= 65535)) {
            $extraArguments .= " ";
            $extraArguments .= "-port=";
            $extraArguments .= $dstPort;
        }
        switch ($dnsLookupProtocol) {
            case "tcp":
                $extraArguments .= " ";
                $extraArguments .= "-vc";
                break;
            default:
                break;
        }
        switch ($rrType) {
            case "A":
            case "AAAA":
            case "ANY":
            case "CAA":
            case "CDS":
            case "CERT":
            case "CNAME":
            case "DNAME":
            case "DNSKEY":
            case "DS":
            case "HINFO":
            case "HTTPS":
            case "IPSECKEY":
            case "KEY":
            case "MX":
            case "NAPTR":
            case "NS":
            case "NSEC":
            case "NSEC3":
            case "NSEC3PARAM":
            case "PTR":
            case "RP":
            case "RRSIG":
            case "SIG":
            case "SOA":
            case "SPF":
            case "SRV":
            case "SSHFP":
            case "SVCB":
            case "TLSA":
            case "TXT":
            case "WKS":
                $extraArguments .= " ";
                $extraArguments .= "-type=";
                $extraArguments .= $rrType;
                break;
            default:
                break;
        }
        return $this->procExecuteAsync('nslookup' . $extraArguments, $host, $server, 0, 50);
    }

    /*
     * Execute a 'ping' command against given host:
     * ping - send ICMP ECHO_REQUEST to network hosts.
     *
     * @param  string $interface
     *   The interface with a specific name for sending packets.
     * @param  string $addressFamily
     *   The address family of the host.
     * @param  string $hopLimit
     *   The hop limit of IP packet.
     * @param  boolean $resolveHostname
     *   Whether to resolve hostname or not.
     * @param  string $host
     *   IP/URL to perform command against.
     * @param  intger $count
     *   Number of ping requests.
     * @return boolean
     *   True on success.
     */
    public function ping($interface, $addressFamily, $hopLimit, $resolveHostname, $host, $count = 5) {
        $extraArguments = "";
        if (Utils::isEmptyString($interface) === false) {
            $extraArguments .= " ";
            $extraArguments .= "-I";
            $extraArguments .= " ";
            $extraArguments .= $interface;
        }
        switch ($addressFamily) {
            case "ipv4":
                $extraArguments .= " ";
                $extraArguments .= "-4";
                break;
            case "ipv6":
                $extraArguments .= " ";
                $extraArguments .= "-6";
                break;
            default:
                break;
        }
        if (ctype_digit($hopLimit) === true && ($hopLimit > 0 && $hopLimit <= 255)) {
            $extraArguments .= " ";
            $extraArguments .= "-t";
            $extraArguments .= " ";
            $extraArguments .= $hopLimit;
        }
        if ($resolveHostname !== true) {
            $extraArguments .= " ";
            $extraArguments .= "-n";
        }
        return $this->procExecuteAsync('ping -c ' . $count . ' -w 15' . $extraArguments, $host, null, 0, 50);
    }

    /*
     * Execute a 'traceroute' command against given host:
     * traceroute - print the route packets trace to network host
     *
     * @param  string $interface
     *   The interface with a specific name for sending packets.
     * @param  string $addressFamily
     *   The address family of the host.
     * @param  string $dstPort
     *   The destination port of the host.
     * @param  string $tracerouteProtocol
     *   The protocol of traceroute.
     * @param  string $firstHopLimit
     *   The initial hop limit of traceroute.
     * @param  string $maxHopLimit
     *   The maximum hop limit of traceroute.
     * @param  boolean $displayICMPExtensions
     *   Whether to display ICMP extensions (RFC 4884) or not.
     * @param  boolean $displayMtuInfo
     *   Whether to display MTU info or not.
     * @param  boolean $displayAsNumber
     *   Whether to display AS number or not.
     * @param  boolean $resolveHostname
     *   Whether to resolve hostname or not.
     * @param  string $host
     *   IP/URL to perform command against.
     * @param  intger $fail
     *   Number of failed hops before exiting command.
     * @return boolean
     *   True on success.
     */
    public function traceroute($interface, $addressFamily, $dstPort, $tracerouteProtocol, $firstHopLimit, $maxHopLimit, $displayICMPExtensions, $displayMtuInfo, $displayAsNumber, $resolveHostname, $host, $fail = 5) {
        $extraArguments = "";
        if (Utils::isEmptyString($interface) === false) {
            $extraArguments .= " ";
            $extraArguments .= "-i";
            $extraArguments .= " ";
            $extraArguments .= $interface;
        }
        switch ($addressFamily) {
            case "ipv4":
                $extraArguments .= " ";
                $extraArguments .= "-4";
                break;
            case "ipv6":
                $extraArguments .= " ";
                $extraArguments .= "-6";
                break;
            default:
                break;
        }
        switch ($tracerouteProtocol) {
            case "icmp":
                $extraArguments .= " ";
                $extraArguments .= "-I";
                break;
            case "tcp":
                $extraArguments .= " ";
                $extraArguments .= "-T";
                if (ctype_digit($dstPort) === true && ($dstPort > 0 && $dstPort <= 65535)) {
                    $extraArguments .= " ";
                    $extraArguments .= "-p";
                    $extraArguments .= " ";
                    $extraArguments .= $dstPort;
                }
                break;
            case "udp":
                $extraArguments .= " ";
                $extraArguments .= "-U";
                if (ctype_digit($dstPort) === true && ($dstPort > 0 && $dstPort <= 65535)) {
                    $extraArguments .= " ";
                    $extraArguments .= "-p";
                    $extraArguments .= " ";
                    $extraArguments .= $dstPort;
                }
                break;
            default:
                break;
        }
        if (ctype_digit($firstHopLimit) === true && ($firstHopLimit > 0 && $firstHopLimit <= 255)) {
            $extraArguments .= " ";
            $extraArguments .= "-f";
            $extraArguments .= " ";
            $extraArguments .= $firstHopLimit;
        }
        if (ctype_digit($maxHopLimit) === true && ($maxHopLimit > 0 && $maxHopLimit <= 255)) {
            $extraArguments .= " ";
            $extraArguments .= "-m";
            $extraArguments .= " ";
            $extraArguments .= $maxHopLimit;
        }
        if ($displayICMPExtensions === true) {
            $extraArguments .= " ";
            $extraArguments .= "-e";
        }
        if ($displayMtuInfo === true) {
            $extraArguments .= " ";
            $extraArguments .= "--mtu";
        }
        if ($displayAsNumber === true) {
            $extraArguments .= " ";
            $extraArguments .= "-A";
        }
        if ($resolveHostname !== true) {
            $extraArguments .= " ";
            $extraArguments .= "-n";
        }
        return $this->procExecute('traceroute -w 2' . $extraArguments, $host, null, $fail);
    }

    // ==================================================================
    //
    // Internal functions.
    //
    // ------------------------------------------------------------------

    /*
     * Execute command, and open pipe for input/output.
     * This is a work around to terminate the command if X consecutive timeouts occur (traceroute).
     *
     * @param  string  $cmd
     *   The command to perform.
     * @param  string  $host
     *   IP/URL to issue command against.
     * @param  string $server
     *   The upstream server (dig, host, nslookup).
     * @param  integer $failCount
     *   Number of consecutive failed hops (traceroute).
     * @return boolean
     *   True on success or false on fail.
     */
    private function procExecute($cmd, $host, $server = null, $failCount = 5) {
        // Define output pipes.
        $spec = array(
            0 => array("pipe", "r"),  // stdin.
            1 => array("pipe", "w"),  // stdout.
            2 => array("pipe", "w")   // stderr.
        );

        // Sanitize & Remove single quotes.
        $host = str_replace('\'', '', filter_var($host, FILTER_SANITIZE_URL));
        if (Utils::isEmptyString($server) === false) {
            $server = str_replace('\'', '', filter_var($server, FILTER_SANITIZE_URL));
            if (strpos($cmd, 'dig') !== false) {
                // Keep the format of "@server" for command "dig".
                $server = "@" . $server;
            }
            // Execute the relevant command.
            $process = proc_open("{$cmd} '{$host}' '{$server}'", $spec, $pipes, null);
        } else {
            // Execute the relevant command.
            $process = proc_open("{$cmd} '{$host}'", $spec, $pipes, null);
        }
        // Check whether pipe exists.
        if (!is_resource($process)) {
            return false;
        }

        // Check for command type.
        if (strpos($cmd, 'dig') !== false) {
            $type = 'dig';
        } else if (strpos($cmd, 'host') !== false) {
            $type = 'host';
        } else if (strpos($cmd, 'mtr') !== false) {
            $type = 'mtr';
        } else if (strpos($cmd, 'nslookup') !== false) {
            $type = 'nslookup';
        } else if (strpos($cmd, 'ping') !== false) {
            $type = 'ping';
        } else if (strpos($cmd, 'traceroute') !== false) {
            $type = 'traceroute';
        } else {
            $type = null;
        }

        $fail = 0;
        $match = 0;
        $traceCount = 0;
        $lastFail = 'start';
        $stdoutStr = null;
        $stderrStr = null;
        // Iterate stdout and stderr.
        while ((($stdoutStr = fgets($pipes[1], 1024)) != null) || (($stderrStr = fgets($pipes[2], 1024)) != null)) {
            // Check for output buffer.
            if (ob_get_level() == 0) {
                ob_start();
            }

            if ($stdoutStr != null) {
                // Address RDNS XSS (outputs non-breakble space correctly) attack.
                $stdoutStr = htmlspecialchars(trim($stdoutStr));

                if ($type === 'mtr') {
                    // Correct output for mtr.
                    if ($match < 10 && preg_match('/^[0-9]\. /', $stdoutStr, $string)) {
                        $stdoutStr = preg_replace('/^[0-9]\. /', '&nbsp;&nbsp;' . $string[0], $stdoutStr);
                        $match++;
                    } else {
                        $stdoutStr = preg_replace('/^[0-9]{2}\. /', '&nbsp;' . substr($stdoutStr, 0, 4), $stdoutStr);
                    }
                } else if ($type === 'traceroute') {
                    // Correct output for traceroute.
                    if ($match < 10 && preg_match('/^[0-9] /', $stdoutStr, $string)) {
                        $stdoutStr = preg_replace('/^[0-9] /', '&nbsp;' . $string[0], $stdoutStr);
                        $match++;
                    }
                    // Check for consecutive failed hops.
                    if (strpos($stdoutStr, '* * *') !== false) {
                        $fail++;
                        if ($lastFail !== 'start' && ($traceCount - 1) === $lastFail && $fail >= $failCount) {
                            echo str_pad($stdoutStr . '<br />-- traceroute: Request timed out. --<br />', 1024, ' ', STR_PAD_RIGHT);

                            // Flush output buffering.
                            @ob_flush();
                            flush();

                            break;
                        }
                        $lastFail = $traceCount;
                    } else {
                        // Reset the counter of failed hops.
                        $fail = 0;
                    }
                    $traceCount++;
                }

                // Pad string for live output.
                echo str_pad($stdoutStr . '<br />', 1024, ' ', STR_PAD_RIGHT);
            }

            if ($stderrStr != null) {
                // Address RDNS XSS (outputs non-breakble space correctly) attack.
                $stderrStr = htmlspecialchars(trim($stderrStr));

                // Pad string for live output.
                echo str_pad($stderrStr . '<br />', 1024, ' ', STR_PAD_RIGHT);
            }

            // Flush output buffering.
            @ob_flush();
            flush();
        }

        $status = proc_get_status($process);
        if ($status['running']) {
            // Close pipes that are still open.
            foreach ($pipes as $pipe) {
                fclose($pipe);
            }
            // Retrieve parent pid.
            $ppid = $status['pid'];
            // Use ps to get all the children of this process.
            $pids = preg_split('/\s+/', `ps -o pid --no-heading --ppid $ppid`);
            // Kill remaining processes.
            foreach ($pids as $pid) {
                if (is_numeric($pid)) {
                    posix_kill($pid, 9);
                }
            }
            proc_close($process);
        }
        return true;
    }

    /*
     * Execute command, and open pipe for input/output asynchronously.
     * This is a work around to terminate the command if X consecutive timeouts occur (traceroute).
     *
     * @param  string  $cmd
     *   The command to perform.
     * @param  string  $host
     *   IP/URL to issue command against.
     * @param  string $server
     *   The upstream server (dig, host, nslookup).
     * @param  integer $failCount
     *   Number of consecutive failed hops (traceroute).
     * @param  integer $timeout
     *   The timeout of executing the relevant command.
     * @return boolean
     *   True on success or false on fail.
     */
    private function procExecuteAsync($cmd, $host, $server = null, $failCount = 5, $timeout = 50) {
        // Define output pipes.
        $spec = array(
            0 => array("pipe", "r"),  // stdin.
            1 => array("pipe", "w"),  // stdout.
            2 => array("pipe", "w")   // stderr.
        );

        // Sanitize & Remove single quotes.
        $host = str_replace('\'', '', filter_var($host, FILTER_SANITIZE_URL));
        if (Utils::isEmptyString($server) === false) {
            $server = str_replace('\'', '', filter_var($server, FILTER_SANITIZE_URL));
            if (strpos($cmd, 'dig') !== false) {
                // Keep the format of "@server" for command "dig".
                $server = "@" . $server;
            }
            // Execute the relevant command.
            $process = proc_open("{$cmd} '{$host}' '{$server}'", $spec, $pipes, null);
        } else {
            // Execute the relevant command.
            $process = proc_open("{$cmd} '{$host}'", $spec, $pipes, null);
        }
        // Check whether pipe exists.
        if (!is_resource($process)) {
            return false;
        }
        // DO NOT block stdout.
        stream_set_blocking($pipes[1], 0);
        // DO NOT block stderr.
        stream_set_blocking($pipes[2], 0);

        // Check for command type.
        if (strpos($cmd, 'dig') !== false) {
            $type = 'dig';
        } else if (strpos($cmd, 'host') !== false) {
            $type = 'host';
        } else if (strpos($cmd, 'mtr') !== false) {
            $type = 'mtr';
        } else if (strpos($cmd, 'nslookup') !== false) {
            $type = 'nslookup';
        } else if (strpos($cmd, 'ping') !== false) {
            $type = 'ping';
        } else if (strpos($cmd, 'traceroute') !== false) {
            $type = 'traceroute';
        } else {
            $type = null;
        }

        $remainingTime = $timeout + 1;
        $remainingTime *= 1000000;
        while ($remainingTime > 0) {
            $startTime = microtime(true);

            // Wait until we have output or the timer expired.
            $read  = array($pipes[1], $pipes[2]);
            $other = array();
            stream_select($read, $other, $other, 0, $timeout);

            // Read the contents from the buffer.
            // This function will always return immediately as the stream is non-blocking.
            $fail = 0;
            $match = 0;
            $traceCount = 0;
            $lastFail = 'start';

            // Check for output buffer.
            if (ob_get_level() == 0) {
                ob_start();
            }

            // The output of command "traceroute" will display abnormally on line breaking if we use "fgets()".
            // So, we use "stream_get_line()" for obtaining output while executing command "traceroute".
            // Otherwise, we use "fgets()" for obtaining output.
            $stdoutStr = ($type === 'traceroute') ? stream_get_line($pipes[1], 1024, PHP_EOL) : fgets($pipes[1], 1024);
            $stderrStr = ($type === 'traceroute') ? stream_get_line($pipes[2], 1024, PHP_EOL) : fgets($pipes[2], 1024);
            if ($stdoutStr != null) {
                // Reset the remaining time to the initial value.
                $remainingTime = $timeout + 1;
                $remainingTime *= 1000000;

                // Address RDNS XSS (outputs non-breakble space correctly) attack.
                $stdoutStr = htmlspecialchars(trim($stdoutStr));

                if ($type === 'mtr') {
                    // Correct output for mtr.
                    if ($match < 10 && preg_match('/^[0-9]\. /', $stdoutStr, $string)) {
                        $stdoutStr = preg_replace('/^[0-9]\. /', '&nbsp;&nbsp;' . $string[0], $stdoutStr);
                        $match++;
                    } else {
                        $stdoutStr = preg_replace('/^[0-9]{2}\. /', '&nbsp;' . substr($stdoutStr, 0, 4), $stdoutStr);
                    }
                } else if ($type === 'traceroute') {
                    // Correct output for traceroute.
                    if ($match < 10 && preg_match('/^[0-9] /', $stdoutStr, $string)) {
                        $stdoutStr = preg_replace('/^[0-9] /', '&nbsp;' . $string[0], $stdoutStr);
                        $match++;
                    }
                    // Check for consecutive failed hops.
                    if (strpos($stdoutStr, '* * *') !== false) {
                        $fail++;
                        if ($lastFail !== 'start' && ($traceCount - 1) === $lastFail && $fail >= $failCount) {
                            echo str_pad($stdoutStr . '<br />-- traceroute: Request timed out. --<br />', 1024, ' ', STR_PAD_RIGHT);

                            // Flush output buffering.
                            @ob_flush();
                            flush();

                            break;
                        }
                        $lastFail = $traceCount;
                    } else {
                        // Reset the counter of failed hops.
                        $fail = 0;
                    }
                    $traceCount++;
                }

                // Pad string for live output.
                echo str_pad($stdoutStr . '<br />', 1024, ' ', STR_PAD_RIGHT);
            }

            if ($stderrStr != null) {
                // Reset the remaining time to the initial value.
                $remainingTime = $timeout + 1;
                $remainingTime *= 1000000;

                // Address RDNS XSS (outputs non-breakble space correctly) attack.
                $stderrStr = htmlspecialchars(trim($stderrStr));

                // Pad string for live output.
                echo str_pad($stderrStr . '<br />', 1024, ' ', STR_PAD_RIGHT);
            }

            // Flush output buffering.
            @ob_flush();
            flush();

            if (feof($pipes[1]) && feof($pipes[2])) {
                // Break from this loop if all file pointers are at EOF before the timeout.
                break;
            }

            // Subtract the number of microseconds that we waited.
            $remainingTime -= (microtime(true) - $startTime) * 1000000;
        }

        if ($remainingTime <= 0) {
            // Check for output buffer.
            if (ob_get_level() == 0) {
                ob_start();
            }

            echo str_pad('E: Request timed out while executing command ' . '"' . $type . '"' . '.<br />', 1024, ' ', STR_PAD_RIGHT);

            // Flush output buffering.
            @ob_flush();
            flush();
        }

        $status = proc_get_status($process);
        if ($status['running']) {
            // Close pipes that are still open.
            foreach ($pipes as $pipe) {
                fclose($pipe);
            }
            // Retrieve parent pid.
            $ppid = $status['pid'];
            // Use ps to get all the children of this process.
            $pids = preg_split('/\s+/', `ps -o pid --no-heading --ppid $ppid`);
            // Kill remaining processes.
            foreach ($pids as $pid) {
                if (is_numeric($pid)) {
                    posix_kill($pid, 9);
                }
            }
            proc_close($process);
        }
        return true;
    }
}
