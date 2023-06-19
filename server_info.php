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
// Set HTTP headers.
header('Cache-Control: no-cache');

// if (!isset($_SESSION)) {
//     session_start();
// }

// if ((isset($_SESSION['csrf_token_server_info']) && isset($_POST['csrf_token_server_info'])
//         && hash_equals($_SESSION['csrf_token_server_info'], $_POST['csrf_token_server_info']))) {
//     require 'LookingGlass/Utils.php';
//     $result = array_merge(Telephone\Utils::getHumanReadableSystemLoad(), Telephone\Utils::getHumanReadableProcessorUtilization(),
//                           Telephone\Utils::getHumanReadableMemoryUtilization(), Telephone\Utils::getHumanReadableUptime(),
//                           Telephone\Utils::getHumanReadableInterfaceSpeed($_POST['network_interface']));
//     echo json_encode($result);
//     exit();
// }

// exit('Unauthorized request');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && realpath(__FILE__) === realpath($_SERVER['SCRIPT_FILENAME'])) {
    exit('Unauthorized request');
}

require 'LookingGlass/Utils.php';
$result = array_merge(Telephone\Utils::getHumanReadableNumberOfLogicalProcessor(), Telephone\Utils::getHumanReadableSystemLoad(),
                      Telephone\Utils::getHumanReadableProcessorUtilization(), Telephone\Utils::getHumanReadableMemoryUtilization(),
                      Telephone\Utils::getHumanReadableUptime(), Telephone\Utils::getHumanReadableInterfaceSpeed($_POST['network_interface']));
echo json_encode($result);
exit();
