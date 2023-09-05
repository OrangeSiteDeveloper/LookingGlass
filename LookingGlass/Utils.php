<?php
/*
 * Copyright (C) 2023 jellybean13
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
 * A class that contains some utilities.
 */
class Utils {
    // All supported languages of the current project.
    public static $supportedLanguage = [
        'en-us',
        'zh-cn'
    ];

    private function __construct() {
        // Empty.
    }

    /*
     * Add two arbitrary precision numbers with float rounding option.
     *
     * @param  string $num1
     *   The left operand, as a string.
     * @param  string $num2
     *   The right operand, as a string.
     * @param  string $scale
     *   This optional parameter is used to set the number of
     *       digits after the decimal place in the result.
     *       If omitted, it will default to the scale set globally with the bcscale() function,
     *       or fallback to 0 if this has not been set.
     * @return boolean
     *   The result.
     */
    public static function round_bcadd($num1, $num2, $scale = 0) {
        return round(bcadd($num1, $num2, bcadd($scale, 2)), $scale);
    }

    /*
     * Subtract one arbitrary precision number from another with float rounding option.
     *
     * @param  string $num1
     *   The left operand, as a string.
     * @param  string $num2
     *   The right operand, as a string.
     * @param  string $scale
     *   This optional parameter is used to set the number of
     *       digits after the decimal place in the result.
     *       If omitted, it will default to the scale set globally with the bcscale() function,
     *       or fallback to 0 if this has not been set.
     * @return boolean
     *   The result.
     */
    public static function round_bcsub($num1, $num2, $scale = 0) {
        return round(bcsub($num1, $num2, bcadd($scale, 2)), $scale);
    }

    /*
     * Multiply two arbitrary precision numbers with float rounding option.
     *
     * @param  string $num1
     *   The left operand, as a string.
     * @param  string $num2
     *   The right operand, as a string.
     * @param  string $scale
     *   This optional parameter is used to set the number of
     *       digits after the decimal place in the result.
     *       If omitted, it will default to the scale set globally with the bcscale() function,
     *       or fallback to 0 if this has not been set.
     * @return boolean
     *   The result.
     */
    public static function round_bcmul($num1, $num2, $scale = 0) {
        return round(bcmul($num1, $num2, bcadd($scale, 2)), $scale);
    }

    /*
     * Divide two arbitrary precision numbers with float rounding option.
     *
     * @param  string $num1
     *   The left operand, as a string.
     * @param  string $num2
     *   The right operand, as a string.
     * @param  string $scale
     *   This optional parameter is used to set the number of
     *       digits after the decimal place in the result.
     *       If omitted, it will default to the scale set globally with the bcscale() function,
     *       or fallback to 0 if this has not been set.
     * @return boolean
     *   The result.
     */
    public static function round_bcdiv($num1, $num2, $scale = 0) {
        return round(bcdiv($num1, $num2, bcadd($scale, 2)), $scale);
    }

    /*
     * Validates the format of a IPv4/IPv6 string.
     *
     * @param  string $addressFamily
     *   The address family of the host.
     * @param  string $host
     *   The IP address string to validate.
     * @return boolean
     *   The result.
     */
    public static function isValidIPAddress($addressFamily, $host) {
        $ret = false;
        switch ($addressFamily) {
            case "ipv4":
                $ret = filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
                break;
            case "ipv6":
                $ret = filter_var($host, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
                break;
            default:
                $ret = isValidIPAddress("ipv4");
                if ($ret === false) {
                    $ret = isValidIPAddress("ipv6");
                }
        }
        return $ret;
    }

    /*
     * Validates the format of a CIDR notation string.
     *
     * @param string $cidr
     *   The CIDR notation string to validate.
     * @return boolean
     *   The result.
     */
    public static function isValidCidr($cidr) {
        $parts = explode('/', $cidr);
        if (count($parts) != 2) {
            return false;
        }
        $ip = $parts[0];
        $netmask = $parts[1];
        if (!preg_match("/^\d+$/", $netmask)) {
            return false;
        }
        $netmask = intval($parts[1]);
        if ($netmask < 0) {
            return false;
        }
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return $netmask <= 32;
        }
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return $netmask <= 128;
        }
        return false;
    }

    /*
     * Validates whether the string is empty.
     *
     * @param string $str
     *   The string to validate.
     * @return boolean
     *   The result.
     */
    public static function isEmptyString($str) {
        return ($str === null || trim($str) === '');
    }

    /*
     * Obtain the system average load.
     *
     * @return string
     *   An array containing the system average load over the last 1, 5 and 15 minutes, respectively if success or false if failed.
     */
    public static function getSystemLoad() {
        $output = sys_getloadavg();
        if ($output === false) return false;
        foreach ($output as &$value) {
            $value = sprintf("%.2f", $value);
        }
        return $output;
    }

    /*
     * Obtain the system average load (human readable).
     *
     * @return string
     *   An array containing the system average load over the last 1, 5 and 15 minutes, respectively if success or false if failed.
     */
    public static function getHumanReadableSystemLoad() {
        return array_combine(["system_load_1min", "system_load_5min", "system_load_15min"], self::getSystemLoad());
    }

    /*
     * Obtain the hostname of the server.
     *
     * @return string
     *   The hostname if success or false if failed.
     */
    public static function getHostname() {
        $output = php_uname("n");
        return is_null($output) ? false : $output;
    }

    /*
     * Obtain the version of kernel.
     *
     * @return string
     *   The kernel version if success or false if failed.
     */
    public static function getKernelVersion() {
        $output = php_uname("r");
        return is_null($output) ? false : $output;
    }

    /*
     * Obtain the architecture of the processor.
     *
     * @return string
     *   The architecture of the processor if success or false if failed.
     */
    public static function getProcessorArchitecture() {
        // $output = shell_exec('uname -p | tr -d "\n"');
        $output = php_uname("m");
        return is_null($output) ? false : $output;
    }

    /*
     * Obtain the model of the processor.
     *
     * @return string
     *   The model if success or false if failed.
     */
    public static function getProcessorModel() {
        $output = shell_exec('cat /proc/cpuinfo | grep \'Hardware\' | head -1 | cut -f 2 -d ":" | awk \'{$1=$1};1\' | tr -d "\n"');
        if (!is_null($output)) {
            return $output;
        }
        $output = shell_exec('cat /proc/cpuinfo | grep \'model name\' | head -1 | cut -f 2 -d ":" | awk \'{$1=$1};1\' | tr -d "\n"');
        return is_null($output) ? false : $output;
    }

    /*
     * Obtain the number of the logical processor.
     *
     * @return string
     *   The number if success or false if failed.
     */
    public static function getNumberOfLogicalProcessor() {
        $output = shell_exec('cat /proc/cpuinfo | grep \'cpu cores\' | head -1 | cut -f 2 -d ":" | awk \'{$1=$1};1\' | tr -d "\n"');
        return is_null($output) ? false : $output;
    }

    /*
     * Obtain the number (human readable) of the logical processor.
     *
     * @return string
     *   The number if success or false if failed.
     */
    public static function getHumanReadableNumberOfLogicalProcessor() {
        return ["logical_processors" => self::getNumberOfLogicalProcessor()];
    }

    /*
     * Obtain the virtualization type of this device.
     *
     * @return string
     *   The virtualization type of this device if success or false if failed.
     */
    public static function getVirtualizationType() {
        $output = shell_exec('systemd-detect-virt | tr -d "\n"');
        return is_null($output) ? false : $output;
    }

    /*
     * Obtain the utilization (human readable) of processor.
     *
     * @return string
     *   An array containing the utilization of processor with metric (excl. percentage) if success.
     */
    public static function getHumanReadableProcessorUtilization() {
        //       user      nice      system      idle      iowait      irq      softirq      steal      guest      guest_nice
        // cpu  21946       662       20606   4265497        3988        0          944          0          0               0

        // PrevIdle = previdle + previowait
        // Idle = idle + iowait

        // PrevNonIdle = prevuser + prevnice + prevsystem + previrq + prevsoftirq + prevsteal
        // NonIdle = user + nice + system + irq + softirq + steal

        // PrevTotal = PrevIdle + PrevNonIdle
        // Total = Idle + NonIdle

        // Differentiate: Actual value minus the previous one.
        // totald = Total - PrevTotal
        // idled = Idle - PrevIdle

        // CPU_Percentage = (totald - idled) / totald
        $output = shell_exec('cat /proc/stat | head -n 1 | tr -d "\n"');
        $output = preg_replace("/\s(?=\s)/", "\\1", $output);
        $output = explode(" ", $output);
        // We skip the first element as the value of it is "cpu" rather than a number.
        $previousUser = $output[1];
        $previousNice = $output[2];
        $previousSystem = $output[3];
        $previousIdle = $output[4];
        $previousIOWait = $output[5];
        $previousIRQ = $output[6];
        $previousSoftIRQ = $output[7];
        $previousSteal = $output[8];
        $previousNonIdle = 0;
        $previousTotal = 0;
        usleep(950000);
        $output = shell_exec('cat /proc/stat | head -n 1 | tr -d "\n"');
        $output = preg_replace("/\s(?=\s)/", "\\1", $output);
        $output = explode(" ", $output);
        // We skip the first element as the value of it is "cpu" rather than a number.
        $currentUser = $output[1];
        $currentNice = $output[2];
        $currentSystem = $output[3];
        $currentIdle = $output[4];
        $currentIOWait = $output[5];
        $currentIRQ = $output[6];
        $currentSoftIRQ = $output[7];
        $currentSteal = $output[8];
        $currentNonIdle = 0;
        $currentTotal = 0;
        // Reckon required parameters.
        $previousNonIdle = bcadd(bcadd(bcadd(bcadd(bcadd($previousUser, $previousNice), $previousSystem), $previousIRQ), $previousSoftIRQ), $previousSteal);
        $currentNonIdle = bcadd(bcadd(bcadd(bcadd(bcadd($currentUser, $currentNice), $currentSystem), $currentIRQ), $currentSoftIRQ), $currentSteal);
        $previousTotal = bcadd(bcadd($previousIdle, $previousIOWait), $previousNonIdle);
        $currentTotal = bcadd(bcadd($currentIdle, $currentIOWait), $currentNonIdle);
        $diffTotal = bcsub($currentTotal, $previousTotal);
        $diffIdle = bcsub(bcadd($currentIdle, $currentIOWait), bcadd($previousIdle, $previousIOWait));
        // Obtain the result.
        $processorUtilizationPercentage = self::round_bcdiv(bcmul(100, bcsub($diffTotal, $diffIdle)), $diffTotal, 2);
        return ["processor_utilization_percentage" => sprintf("%.2f", $processorUtilizationPercentage)];
    }

    /*
     * Obtain the utilization (human readable) of processor.
     *
     * @return string
     *   An array containing the utilization of processor with metric (excl. percentage) if success.
     */
    // public static function getHumanReadableProcessorUtilization() {
    //     $output = shell_exec('vmstat 1 2 | tail -1 | awk \'{print $15}\' | tr -d "\n"');
    //     return ["processor_utilization_percentage" => bcsub(100, $output)];
    // }

    /*
     * Obtain the total size (KB) of memory.
     *
     * @return string
     *   The total size (KB) of memory if success or false if failed.
     */
    public static function getTotalMemory() {
        $output = shell_exec('cat /proc/meminfo | grep \'MemTotal\' | head -1 | cut -f 2 -d ":" | awk \'{$1=$1};1\' | cut -f 1 -d " " | tr -d "\n"');
        return is_null($output) ? false : $output;
    }

    /*
     * Obtain the total size (human readable) of memory.
     *
     * @return string
     *   An array containing the total size of memory with metric if success.
     */
    public static function getHumanReadableTotalMemory() {
        $output = self::getTotalMemory();
        $divisionCount = 0;
        while ($output >= 1024) {
            $output = bcdiv($output, 1024, 2);
            $divisionCount++;
        }
        return ["memory_total" => $output, "memory_total_metric" => self::getDividedMetricResult(1, $divisionCount)];
    }

    /*
     * Obtain the used size (KB) of memory.
     *
     * @return string
     *   The used size (KB) of memory if success or false if failed.
     */
    public static function getUsedMemory() {
        $memTotal = -1;
        $memFree = -1;
        $memBuffers = -1;
        $memCached = -1;
        $memSReclaimable = -1;
        $memShmem = -1;
        $handler = fopen('/proc/meminfo', 'r');
        if ($handler === false) return false;
        while ($line = fgets($handler)) {
            $pieces = array();
            if (preg_match('/^MemTotal:\s+(\d+)\skB$/', $line, $pieces)) {
                $memTotal = $pieces[1];
            } else if (preg_match('/^MemFree:\s+(\d+)\skB$/', $line, $pieces)) {
                $memFree = $pieces[1];
            } else if (preg_match('/^Buffers:\s+(\d+)\skB$/', $line, $pieces)) {
                $memBuffers = $pieces[1];
            } else if (preg_match('/^Cached:\s+(\d+)\skB$/', $line, $pieces)) {
                $memCached = $pieces[1];
            } else if (preg_match('/^SReclaimable:\s+(\d+)\skB$/', $line, $pieces)) {
                $memSReclaimable = $pieces[1];
            } else if (preg_match('/^Shmem:\s+(\d+)\skB$/', $line, $pieces)) {
                $memShmem = $pieces[1];
            }
            if ($memTotal >= 0 && $memFree >= 0 && $memBuffers >= 0 && $memCached >= 0
                               && $memSReclaimable >= 0 && $memShmem >= 0) break;
        }
        fclose($handler);
        return bcsub(bcsub($memTotal, $memFree), bcadd($memBuffers, bcsub(bcadd($memCached, $memSReclaimable), $memShmem)));
    }

    /*
     * Obtain the used size (human readable) of memory.
     *
     * @return string
     *   An array containing the used size of memory with metric if success.
     */
    public static function getHumanReadableUsedMemory() {
        $output = self::getUsedMemory();
        $divisionCount = 0;
        while ($output >= 1024) {
            $output = bcdiv($output, 1024, 2);
            $divisionCount++;
        }
        return ["memory_used" => $output, "memory_used_metric" => self::getDividedMetricResult(1, $divisionCount)];
    }

    /*
     * Obtain the utilization (human readable) of memory.
     *
     * @return string
     *   An array containing the utilization of memory with metric (excl. percentage) if success.
     */
    public static function getHumanReadableMemoryUtilization() {
        $memTotal = -1;
        $memFree = -1;
        $memBuffers = -1;
        $memCached = -1;
        $memSReclaimable = -1;
        $memShmem = -1;
        $handler = fopen('/proc/meminfo','r');
        if ($handler === false) return false;
        while ($line = fgets($handler)) {
            $pieces = array();
            if (preg_match('/^MemTotal:\s+(\d+)\skB$/', $line, $pieces)) {
                $memTotal = $pieces[1];
            } else if (preg_match('/^MemFree:\s+(\d+)\skB$/', $line, $pieces)) {
                $memFree = $pieces[1];
            } else if (preg_match('/^Buffers:\s+(\d+)\skB$/', $line, $pieces)) {
                $memBuffers = $pieces[1];
            } else if (preg_match('/^Cached:\s+(\d+)\skB$/', $line, $pieces)) {
                $memCached = $pieces[1];
            } else if (preg_match('/^SReclaimable:\s+(\d+)\skB$/', $line, $pieces)) {
                $memSReclaimable = $pieces[1];
            } else if (preg_match('/^Shmem:\s+(\d+)\skB$/', $line, $pieces)) {
                $memShmem = $pieces[1];
            }
            if ($memTotal >= 0 && $memFree >= 0 && $memBuffers >= 0 && $memCached >= 0
                               && $memSReclaimable >= 0 && $memShmem >= 0) break;
        }
        fclose($handler);
        $memUsed = bcsub(bcsub($memTotal, $memFree), bcadd($memBuffers, bcsub(bcadd($memCached, $memSReclaimable), $memShmem)));
        $memUtilizationPercentage = self::round_bcdiv(bcmul($memUsed, 100), $memTotal, 2);
        $divisionCount = 0;
        while ($memUsed >= 1024) {
            $memUsed = bcdiv($memUsed, 1024, 2);
            $divisionCount++;
        }
        $memUsedMetric = self::getDividedMetricResult(1, $divisionCount);
        $divisionCount = 0;
        while ($memTotal >= 1024) {
            $memTotal = bcdiv($memTotal, 1024, 2);
            $divisionCount++;
        }
        $memTotalMetric = self::getDividedMetricResult(1, $divisionCount);
        return ["memory_used" => $memUsed, "memory_used_metric" => $memUsedMetric, "memory_total" => $memTotal,
                "memory_total_metric" => $memTotalMetric, "memory_utilization_percentage" => sprintf("%.2f", $memUtilizationPercentage)];
    }

    /*
     * Obtain the uptime of system.
     *
     * @return string
     *   The uptime of system if success or false if failed.
     */
    public static function getUptime() {
        $output = @file_get_contents("/proc/uptime");
        return ($output === false) ? false : floatval($output);
    }

    /*
     * Obtain the uptime (human readable) of system.
     *
     * @return string
     *   A time with "dd hh:mm:ss" format if success.
     *   An array containing the uptime of system if success.
     */
    public static function getHumanReadableUptime() {
        $output = self::getUptime();
        $seconds = fmod($output, 60);
        $output = intdiv($output, 60);
        $minutes = $output % 60;
        $output = intdiv($output, 60);
        $hours = $output % 24;
        $output = intdiv($output, 24);
        $days = $output;
        $output = ($days > 0) ? sprintf("%dd %02d:%02d:%02d", $days, $hours, $minutes, $seconds)
                              : sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
        return ["uptime" => $output];
    }

    /*
     * Obtain the utilization (human readable) of processor.
     *
     * @param  string $interface
     *   The interface with a specific name for gathering data.
     * @return string
     *   An array containing the interface speed with metric if success.
     */
    public static function getHumanReadableInterfaceSpeed($interface) {
        $previousRx = trim(file_get_contents('/sys/class/net/' . $interface . '/statistics/rx_bytes'));
        $previousTx = trim(file_get_contents('/sys/class/net/' . $interface . '/statistics/tx_bytes'));
        usleep(950000);
        $currentRx = trim(file_get_contents('/sys/class/net/' . $interface . '/statistics/rx_bytes'));
        $currentTx = trim(file_get_contents('/sys/class/net/' . $interface . '/statistics/tx_bytes'));

        $interfaceRx = bcsub($currentRx, $previousRx);
        $interfaceTx = bcsub($currentTx, $previousTx);
        $divisionCount = 0;
        while ($interfaceRx >= 1024) {
            $interfaceRx = bcdiv($interfaceRx, 1024, 2);
            $divisionCount++;
        }
        $interfaceRxMetric = self::getDividedMetricResult(0, $divisionCount) . '/s';
        $divisionCount = 0;
        while ($interfaceTx >= 1024) {
            $interfaceTx = bcdiv($interfaceTx, 1024, 2);
            $divisionCount++;
        }
        $interfaceTxMetric = self::getDividedMetricResult(0, $divisionCount) . '/s';
        return ["interface_rx_rate" => $interfaceRx, "interface_rx_rate_metric" => $interfaceRxMetric,
                "interface_tx_rate" => $interfaceTx, "interface_tx_rate_metric" => $interfaceTxMetric];
    }

    /*
     * Obtain the display language.
     *
     * @param  string $preferredLocale
     *   The language that the user chooses or prefers.
     * @return string
     *   The display language.
     */
    public static function getDisplayLanguage($preferredLocale) {
        if (self::isEmptyString($preferredLocale) === false && in_array($preferredLocale, self::$supportedLanguage)) {
            return strtolower($preferredLocale);
        }
        $userLocales = array_reduce(explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']),
            function ($res, $el) {
                list($l, $q) = array_merge(explode(';q=', $el), [1]);
                $res[$l] = (float) $q;
                return $res;
            },
        []);
        arsort($userLocales);
        $locale = strtolower(array_key_first($userLocales));
        if ($locale === false || self::isEmptyString($locale) === true) {
            $locale = "en-us";  // Fallback to the default language.
        }
        return $locale;
    }

    /*
     * Obtain the client IP address.
     *
     * @return string
     *   The client ip address if success.
     */
    // public static function getClientIPAddress() {
    //     if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    //         return $_SERVER['HTTP_CF_CONNECTING_IP'];
    //     } else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //         return $_SERVER['HTTP_X_FORWARDED_FOR'];
    //     } else {
    //         return $_SERVER['REMOTE_ADDR'];
    //     }
    // }

    /*
     * Obtain the IP address of client.
     *
     * @param  string $httpHeaderName
     *   The name of HTTP header that will be used to get the IP address of client.
     * @return string
     *   The IP address of client.
     */
    public static function getClientIPAddress($httpHeaderName) {
        if (!empty($_SERVER[$httpHeaderName])) {
            return $_SERVER[$httpHeaderName];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /*
     * Obtain the port of client.
     *
     * @param  string $httpHeaderName
     *   The name of HTTP header that will be used to get the port of client.
     * @return string
     *   The port of client.
     */
    public static function getClientPort($httpHeaderName) {
        if (!empty($_SERVER[$httpHeaderName])) {
            return $_SERVER[$httpHeaderName];
        } else {
            return $_SERVER['REMOTE_PORT'];
        }
    }

    /*
     * Obtain the visit scheme of client.
     *
     * @return string
     *   The visit scheme of client.
     */
    public static function getClientVisitScheme() {
        return (self::isHttpsVisitScheme() === true) ? 'https' : 'http';
    }

    /*
     * Validates whether the visit scheme of client is HTTPS.
     *
     * @return boolean
     *   The result.
     */
    public static function isHttpsVisitScheme() {
        if (self::isEmptyString($_SERVER['REQUEST_SCHEME']) === false && $_SERVER['REQUEST_SCHEME'] == 'https') {
            return true;
        } else if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            return true;
        } else if (self::isEmptyString($_SERVER['HTTP_X_FORWARDED_PROTO']) === false &&
                   $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ||
                   self::isEmptyString($_SERVER['HTTP_X_FORWARDED_SSL']) === false &&
                   $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
            return true;
        }
        return false;
    }

    /*
     * Execute fgets() asynchronously.
     *
     * @param  string $stream
     *   A valid file pointer.
     * @param  integer $timeout
     *   An upper bound on the amount of time that stream_select() will wait before it returns.
     * @param  integer $length
     *   Reading ends when (length - 1) bytes have been read, or a newline (which is included in the return value), or an EOF (whichever comes first).
     *   If no length is specified, it will keep reading from the stream until it reaches the end of the line.
     * @return string
     *   The result that is from fgets() if success or false if failed or zero if the timeout expires.
     */
    public static function fgetsAsync($stream, $timeout, $length = null) {
        $read = array($stream);
        $write = null;
        $except = null;
        return (stream_select($read, $write, $except, $timeout) === false) ? false : fgets($stream, $length);
    }

    /*
     * @hide
     */
    private static function getDividedMetricResult($baseLayer, $count) {
        $currentLayer = bcadd($baseLayer, $count);
        switch ($currentLayer) {
            case 0:
                return "B";
            case 1:
                return "KB";
            case 2:
                return "MB";
            case 3:
                return "GB";
            case 4:
                return "TB";
            case 5:
                return "PB";
            case 6:
                return "EB";
            case 7:
                return "ZB";
            case 8:
                return "YB";
            case 9:
                return "RB";
            case 10:
                return "QB";
            default:
                return "Unknown";
        }
    }
}
