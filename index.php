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

require 'LookingGlass/BuildInfo.php';
require 'LookingGlass/Utils.php';

$lang = Telephone\Utils::getDisplayLanguage($_GET['lang'] ?? null);
// Failsafe language.
require_once "LookingGlass/langs/en-us.php";
if ($lang !== "en-us") {
    // The display language.
    require_once "LookingGlass/langs/$lang.php";
}

// Lazy config check/load.
if (file_exists('LookingGlass/Config.php')) {
    require 'LookingGlass/Config.php';
    if (!isset($testIPv4Address, $testIPv6Address, $siteName, $siteUrl, $serverLocation, $testFiles, $theme, $httpHeaderNameGetClientIPAddress, $httpHeaderNameGetClientPort)) {
        exit($s['index_error_configuration_variables_missing']);
    }
} else {
    exit($s['index_error_configuration_file_missing']);
}

// Create a session or resume the current one.
if (!isset($_SESSION)) {
    session_start();
}

// if (!isset($_SESSION['csrf_token_server_info'])) {
//     $_SESSION['csrf_token_server_info'] = bin2hex(random_bytes(32));
// }
if (!isset($_SESSION['csrf_token_network_tests'])) {
    $_SESSION['csrf_token_network_tests'] = bin2hex(random_bytes(32));
}

$interfaceList = net_get_interfaces();
$renderTime = floor((microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]) * 1000);
?>
<!DOCTYPE html>
<html lang="<?php echo $lang; ?>">
  <head>
    <meta charset="utf-8">
    <?php
        if (Telephone\Utils::isEmptyString($siteName) === false) {
            echo '<title>' . $siteName . ' - ' . $s['website_name'] . '</title>';
        } else {
            echo '<title>' . $s['website_name'] . '</title>';
        }
    ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LookingGlass - Open source PHP looking glass">
    <meta name="author" content="Telephone, jellybean13">
    <link rel="icon" href="assets/images/favicon.png" type="image/gif" />

    <!-- Styles -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/<?php echo $theme; ?>/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-icons.min.css" rel="stylesheet">

    <!-- JavaScript -->
    <script src="assets/js/preloaded.js"></script>
  </head>
  <body>
    <!-- Header -->
    <nav id="navbar" class="navbar navbar-expand-lg sticky-top navbar-dark bg-primary">
      <div class="container-lg">
        <?php
            if (Telephone\Utils::isEmptyString($siteName) === true) {
                $siteName = $s['website_name'];
            }
            if (Telephone\Utils::isEmptyString($siteUrl) === false) {
                echo '<a class="navbar-brand" href="' . $siteUrl . '">' . $siteName . '</a>';
            } else {
                if (isset($_GET['lang'])) {
                    echo '<a class="navbar-brand" href="' . '/?lang=' . $lang . '">' . $siteName . '</a>';
                } else {
                    echo '<a class="navbar-brand" href="/">' . $siteName . '</a>';
                }
            }
        ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar_responsive" aria-controls="navbar_responsive" aria-expanded="false" aria-label="<?php echo $s['toggle_navigation']; ?>">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar_responsive">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <?php
                  if (isset($_GET['lang'])) {
                      echo '<a class="nav-link" href="' . '/?lang=' . $lang . '">' . '<i class="bi bi-house-door"></i>' . '<span class="ms-2">' . $s['index_home'] . '</span>' . '</a>';
                  } else {
                      echo '<a class="nav-link" href="/">' . '<i class="bi bi-house-door"></i>' . '<span class="ms-2">' . $s['index_home'] . '</span>' . '</a>';
                  }
              ?>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#dialog_about" data-bs-toggle="modal"><?php echo '<i class="bi bi-info-circle"></i>' . '<span class="ms-2">' . $s['index_about'] . '</span>'; ?></a>
            </li>
          </ul>
          <ul class="navbar-nav ms-md-auto">
            <?php
                if (Telephone\BuildInfo::isEngineeringSampleBuild() === true) {
                    echo '<li class="nav-item">';
                    echo '<a class="nav-link" href="#warning_dialog_in_engineering_sample_state" data-bs-toggle="modal">' . '<i class="bi bi-exclamation-triangle"></i>' . '<span class="ms-2">' . $s['warning'] . '</span>' . '</a>';
                    echo '</li>';
                }
            ?>
            <li class="nav-item py-2 py-lg-1 col-12 col-lg-auto">
              <div class="vr d-none d-lg-flex h-100 mx-lg-2 text-white"></div>
              <hr class="d-lg-none my-2 text-white-50">
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo '<i class="bi bi-translate"></i>' . '<span class="ms-2">' . $s['index_language'] . '</span>'; ?></a>
              <div class="dropdown-menu">
                <a class="dropdown-item d-flex align-items-center" data-bs-language-value="auto" href="/">
                  <i class="bi bi-translate"></i><span class="ms-2"><?php echo $s['auto']; ?></span><span class="ms-auto" data-bs-language-value="auto"><i class="bi bi-check2"></i></span>
                </a>
                <a class="dropdown-item d-flex align-items-center" data-bs-language-value="en-us" href="/?lang=en-us">
                  <i class="bi bi-translate"></i><span class="ms-2"><?php echo $s['lang_en-us']; ?></span><span class="ms-auto" data-bs-language-value="en-us"><i class="bi bi-check2"></i></span>
                </a>
                <a class="dropdown-item d-flex align-items-center" data-bs-language-value="zh-cn" href="/?lang=zh-cn">
                  <i class="bi bi-translate"></i><span class="ms-2"><?php echo $s['lang_zh-cn']; ?></span><span class="ms-auto" data-bs-language-value="zh-cn"><i class="bi bi-check2"></i></span>
                </a>
              </div>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo '<i class="bi bi-circle-half"></i>' . '<span class="ms-2">' . $s['index_color_mode'] . '</span>'; ?></a>
              <div class="dropdown-menu">
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false">
                  <i class="bi bi-circle-half"></i><span class="ms-2"><?php echo $s['auto']; ?></span><span class="ms-auto" data-bs-theme-value="auto"><i class="bi bi-check2"></i></span>
                </button>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                  <i class="bi bi-moon-stars-fill"></i><span class="ms-2"><?php echo $s['index_color_mode_dark']; ?></span><span class="ms-auto" data-bs-theme-value="dark"><i class="bi bi-check2"></i></span>
                </button>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                  <i class="bi bi-sun-fill"></i><span class="ms-2"><?php echo $s['index_color_mode_light']; ?></span><span class="ms-auto" data-bs-theme-value="light"><i class="bi bi-check2"></i></span>
                </button>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- Container -->
    <div id="container" class="container-lg">
      <!-- Server Information -->
      <section id="server_information">
        <div class="card border-primary mb-3">
          <div class="card-body">
            <h4 class="card-title text-primary"><?php echo $s['index_server_info']; ?></h4>
            <hr>
            <p class="card-text" style="display:inline"><?php echo $s['index_hostname']; ?>: 
              <?php
                  echo '<p id="hostname" class="card-text" style="display: inline">' . Telephone\Utils::getHostname() . '</p>';
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_server_location']; ?>: 
              <?php
                  if (Telephone\Utils::isEmptyString($serverLocation) === false) {
                      echo '<p id="location" class="card-text" style="display: inline">' . $serverLocation . '</p>';
                  } else {
                      echo '<p id="location" class="card-text" style="display: inline">' . $s['not_available'] . '</p>';
                  }
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_processor_model']; ?>: 
              <?php
                  echo '<p id="processor_model" class="card-text" style="display: inline">' . Telephone\Utils::getProcessorModel() . '</p>';
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_logical_processors']; ?>: 
              <?php
                  echo '<p id="logical_processors" class="card-text" style="display: inline">' . Telephone\Utils::getNumberOfLogicalProcessor() . '</p>';
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_processor_architecture']; ?>: 
              <?php
                  echo '<p id="processor_architecture" class="card-text" style="display: inline">' . Telephone\Utils::getProcessorArchitecture() . '</p>';
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_kernel_version']; ?>: 
              <?php
                  echo '<p id="kernel_version" class="card-text" style="display: inline">' . Telephone\Utils::getKernelVersion() . '</p>';
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_virtualization_type']; ?>: 
              <?php
                  echo '<p id="virtualization_type" class="card-text" style="display: inline">' . Telephone\Utils::getVirtualizationType() . '</p>';
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_load_average']; ?>: 
              <?php
                  $systemLoadArray = Telephone\Utils::getHumanReadableSystemLoad();
                  echo '<p id="load_average" class="card-text" style="display: inline">' . $systemLoadArray["system_load_1min"]
                                                                                         . ', ' . $systemLoadArray["system_load_5min"]
                                                                                         . ', ' . $systemLoadArray["system_load_15min"] . '</p>';
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_processor_utilization']; ?>: 
              <p id="processor_utilization" class="card-text" style="display: inline"><?php echo $s['loading']; ?></p>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_memory_utilization']; ?>: 
              <?php
                  $memoryUtilizationArray = Telephone\Utils::getHumanReadableMemoryUtilization();
                  echo '<p id="memory_utilization" class="card-text" style="display: inline">' . $memoryUtilizationArray["memory_used"]
                                                                                               . ' ' . $memoryUtilizationArray["memory_used_metric"]
                                                                                               . '/' . $memoryUtilizationArray["memory_total"]
                                                                                               . ' ' . $memoryUtilizationArray["memory_total_metric"]
                                                                                               . " " . "("
                                                                                               . $memoryUtilizationArray["memory_utilization_percentage"]
                                                                                               . "%" . ")" . '</p>';
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_uptime']; ?>: 
              <?php
                  $uptimeArray = Telephone\Utils::getHumanReadableUptime();
                  echo '<p id="uptime" class="card-text" style="display: inline">' . $uptimeArray["uptime"] . '</p>';
              ?>
            </p>
          </div>
        </div>
      </section>

      <!-- Client Information -->
      <section id="client_information">
        <div class="card border-primary mb-3">
          <div class="card-body">
            <h4 class="card-title text-primary"><?php echo $s['index_client_info']; ?></h4>
            <hr>
            <p class="card-text" style="display:inline"><?php echo $s['index_client_requested_hostname']; ?>: 
              <?php
                  if (Telephone\Utils::isEmptyString($_SERVER['SERVER_NAME']) === false) {
                      echo '<p id="client_requested_hostname" class="card-text" style="display: inline">' . $_SERVER['SERVER_NAME'] . '</p>';
                  } else {
                      echo '<p id="client_requested_hostname" class="card-text" style="display: inline">' . $_SERVER['SERVER_ADDR'] . '</p>';
                      if (filter_var($_SERVER['SERVER_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                          echo '&nbsp;&nbsp;';
                          echo '<a href="' . 'https://bgp.he.net/ip/' . $_SERVER['SERVER_ADDR'] . '" target="_blank">[' . $s['index_details'] . ']</a>';
                      }
                  }
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_client_requested_port']; ?>: 
              <?php
                  echo '<p id="client_requested_port" class="card-text" style="display: inline">' . $_SERVER['SERVER_PORT'] . '</p>';
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_client_ip_address']; ?>: 
              <?php
                  $clientIPAddress = Telephone\Utils::getClientIPAddress($httpHeaderNameGetClientIPAddress);
                  echo '<p id="client_ip_address" class="card-text" style="display: inline">' . $clientIPAddress . '</p>';
                  if (filter_var($clientIPAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                      echo '&nbsp;&nbsp;';
                      echo '<a href="' . 'https://bgp.he.net/ip/' . $clientIPAddress . '" target="_blank">[' . $s['index_details'] . ']</a>';
                  }
                  echo '&nbsp;&nbsp;';
                  echo '<a id="client_ip_address_test" href="#tests">[' . $s['index_test'] . ']</a>';
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_client_port']; ?>: 
              <?php
                  $clientPort = Telephone\Utils::getClientPort($httpHeaderNameGetClientPort);
                  echo '<p id="client_port" class="card-text" style="display: inline">' . $clientPort . '</p>';
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_client_visit_timestamps']; ?>: 
              <?php
                  echo '<p id="client_visit_timestamps" class="card-text" style="display: inline">' . $_SERVER['REQUEST_TIME_FLOAT'] . '</p>';
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_client_visit_scheme']; ?>: 
              <?php
                  echo '<p id="client_visit_scheme" class="card-text" style="display: inline">' . Telephone\Utils::getClientVisitScheme() . '</p>';
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_client_user_agent']; ?>: 
              <?php
                  echo '<p id="client_user_agent" class="card-text" style="display: inline">' . $_SERVER['HTTP_USER_AGENT'] . '</p>';
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_client_requested_http_version']; ?>: 
              <?php
                  echo '<p id="client_requested_http_version" class="card-text" style="display: inline">' . $_SERVER['SERVER_PROTOCOL'] . '</p>';
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_client_requested_tls_version']; ?>: 
              <?php
                  if (Telephone\Utils::isEmptyString($_SERVER['SSL_PROTOCOL']) === false) {
                      echo '<p id="client_requested_tls_version" class="card-text" style="display: inline">' . $_SERVER['SSL_PROTOCOL'] . '</p>';
                  } else {
                      echo '<p id="client_requested_tls_version" class="card-text" style="display: inline">' . $s['not_available'] . '</p>';
                  }
              ?>
            </p>
            <p class="card-text" style="display:inline"><?php echo $s['index_client_language_preference']; ?>: 
              <?php
                  if (Telephone\Utils::isEmptyString($_SERVER['HTTP_ACCEPT_LANGUAGE']) === false) {
                      echo '<p id="client_language_preference" class="card-text" style="display: inline">' . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . '</p>';
                  } else {
                      echo '<p id="client_language_preference" class="card-text" style="display: inline">' . $s['not_available'] . '</p>';
                  }
              ?>
            </p>
          </div>
        </div>
      </section>

      <!-- Network Information -->
      <section id="network_information">
        <div class="card border-primary mb-3">
          <div class="card-body">
            <h4 class="card-title text-primary"><?php echo $s['index_network_info']; ?></h4>
            <hr>
            <div id="div_network_interface" class="col-md-12 mb-3">
              <div class="input-group">
                <label id="label_network_interface" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_network_interface_network_info']; ?>"><?php echo $s['index_network_interface']; ?></label>
                <select id="network_interface" name="network_interface" class="form-select" aria-labelledby="label_network_interface">
                  <?php
                      if ($interfaceList !== false) {
                          foreach ($interfaceList as $key => $value) {
                              echo '<option value="' . $key . '">' . $key . '</option>';
                          }
                      }
                  ?>
                </select>
              </div>
            </div>
            <div>
              <p class="card-text" style="display:inline"><?php echo $s['index_interface_speed']; ?>: 
                <p id="interface_speed" class="card-text" style="display: inline"><?php echo $s['loading']; ?></p>
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- Network Tests -->
      <section id="tests">
        <div class="card border-primary mb-3">
          <div class="card-body">
            <h4 class="card-title text-primary"><?php echo $s['index_network_tests']; ?></h4>
            <hr>
            <div>
              <p class="card-text" style="display:inline"><?php echo $s['index_test_ipv4_address']; ?>: 
                <?php
                    if (filter_var($testIPv4Address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                        echo '<p id="test_ipv4_address" class="card-text" style="display: inline">' . $testIPv4Address . '</p>';
                        if (filter_var($testIPv4Address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                            echo '&nbsp;&nbsp;';
                            echo '<a href="' . 'https://bgp.he.net/ip/' . $testIPv4Address . '" target="_blank">[' . $s['index_details'] . ']</a>';
                        }
                    } else {
                        echo '<p id="test_ipv4_address" class="card-text" style="display: inline">' . $s['not_available'] . '</p>';
                    }
                ?>
              </p>
              <p class="card-text" style="display:inline"><?php echo $s['index_test_ipv6_address']; ?>: 
                <?php
                    if (filter_var($testIPv6Address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                        echo '<p id="test_ipv6_address" class="card-text" style="display: inline">' . $testIPv6Address . '</p>';
                        if (filter_var($testIPv6Address, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                            echo '&nbsp;&nbsp;';
                            echo '<a href="' . 'https://bgp.he.net/ip/' . $testIPv6Address . '" target="_blank">[' . $s['index_details'] . ']</a>';
                        }
                    } else {
                        echo '<p id="test_ipv6_address" class="card-text" style="display: inline">' . $s['not_available'] . '</p>';
                    }
                ?>
              </p>
              <p class="card-text" style="display: inline"><?php echo $s['index_test_files']; ?>: 
                <?php
                    $numberOfFile = count($testFiles);
                    if ($numberOfFile === 0) {
                        echo '<p class="card-text" style="display: inline">' . $s['not_available'] . '</p>';
                    } else {
                        $currentIndex = 0;
                        foreach ($testFiles as $val) {
                            echo '<a href="' . '/' . $val . '.test' . '">' . $val . '</a>';
                            if ($currentIndex < $numberOfFile - 1) {
                                echo '&nbsp;&nbsp;';
                            }
                            $currentIndex++;
                        }
                    }
                ?>
              </p>
            </div>
            <br>
            <h4 class="card-title text-primary"><?php echo $s['index_test_commands']; ?></h4>
            <hr>
            <form class="form-inline" id="networktest" action="#results" method="post" autocomplete="off">
              <input type="hidden" name="csrf_token_network_tests" value="<?php echo $_SESSION['csrf_token_network_tests']; ?>">
              <fieldset id="network_tests">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <div class="input-group">
                      <label id="label_host" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_hostname']; ?>"><?php echo $s['index_hostname']; ?></label>
                      <input id="host" name="host" type="text" class="form-control" placeholder="<?php echo $s['index_description_hostname']; ?>">
                      <div id="div_error_host" class="invalid-feedback" style="display: none"><?php echo $s['index_error_empty_hostname']; ?></div>
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <div class="input-group">
                      <label id="label_cmd" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_command']; ?>"><?php echo $s['index_command']; ?></label>
                      <select id="cmd" name="cmd" class="form-select" aria-labelledby="label_cmd">
                        <option value="" selected="selected"> -- <?php echo $s['index_select_command']; ?> -- </option>
                        <option value="dig">dig</option>
                        <option value="host">host</option>
                        <option value="mtr">mtr</option>
                        <option value="nslookup">nslookup</option>
                        <option value="ping">ping</option>
                        <option value="traceroute">traceroute</option>
                      </select>
                      <div id="div_error_cmd" class="invalid-feedback" style="display: none"><?php echo $s['index_error_empty_command']; ?></div>
                    </div>
                  </div>
                </div>
                <br>
                <h4 class="card-title text-primary"><?php echo $s['index_command_options']; ?></h4>
                <hr>
                <div class="row">
                  <div id="div_interface" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <label id="label_interface" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_network_interface_command_options']; ?>"><?php echo $s['index_network_interface']; ?></label>
                      <select id="interface" name="interface" class="form-select" aria-labelledby="label_interface">
                        <option value="" selected="selected"><?php echo $s['system_default']; ?></option>
                        <?php
                            if ($interfaceList !== false) {
                                foreach ($interfaceList as $key => $value) {
                                    echo '<option value="' . $key . '">' . $key . '</option>';
                                }
                            }
                        ?>
                      </select>
                    </div>
                  </div>
                  <div id="div_address_family" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <label id="label_address_family" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_address_family']; ?>"><?php echo $s['index_address_family']; ?></label>
                      <select id="address_family" name="address_family" class="form-select" aria-labelledby="label_address_family">
                        <option value="" selected="selected"><?php echo $s['system_default']; ?></option>
                        <option value="ipv4">IPv4</option>
                        <option value="ipv6">IPv6</option>
                      </select>
                    </div>
                  </div>
                  <div id="div_destination_port" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <label id="label_destination_port" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_destination_port']; ?>"><?php echo $s['index_destination_port']; ?></label>
                      <input id="destination_port" name="destination_port" type="text" class="form-control" placeholder="<?php echo $s['index_placeholder_destination_port']; ?>">
                      <div id="div_error_destination_port" class="invalid-feedback" style="display: none"><?php echo $s['index_error_destination_port_out_of_range']; ?></div>
                    </div>
                  </div>
                  <div id="div_dns_lookup_protocol" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <label id="label_dns_lookup_protocol" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_protocol_dns_lookup']; ?>"><?php echo $s['index_protocol']; ?></label>
                      <select id="dns_lookup_protocol" name="dns_lookup_protocol" class="form-select" aria-labelledby="label_dns_lookup_protocol">
                        <option value="tcp">TCP</option>
                        <option value="udp" selected="selected">UDP</option>
                      </select>
                    </div>
                  </div>
                  <div id="div_dns_lookup_mode" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <label id="label_dns_lookup_mode" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_dns_lookup_mode']; ?>"><?php echo $s['index_dns_lookup_mode']; ?></label>
                      <select id="dns_lookup_mode" name="dns_lookup_mode" class="form-select" aria-labelledby="label_dns_lookup_mode">
                        <option value="normal" selected="selected"><?php echo $s['index_select_dns_lookup_mode_normal']; ?></option>
                        <option value="reversed"><?php echo $s['index_select_dns_lookup_mode_reversed']; ?></option>
                      </select>
                    </div>
                  </div>
                  <div id="div_dns_lookup_encryption_mode" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <label id="label_dns_lookup_encryption_mode" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_dns_encryption_mode']; ?>"><?php echo $s['index_dns_encryption_mode']; ?></label>
                      <select id="dns_lookup_encryption_mode" name="dns_lookup_encryption_mode" class="form-select" aria-labelledby="label_dns_lookup_encryption_mode">
                        <option value="none" selected="selected"><?php echo $s['index_select_dns_encryption_mode_no_encryption']; ?></option>
                        <option value="doh"><?php echo $s['index_select_dns_encryption_mode_doh']; ?></option>
                        <option value="dot"><?php echo $s['index_select_dns_encryption_mode_dot']; ?></option>
                      </select>
                    </div>
                  </div>
                  <div id="div_dns_lookup_query_uri" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <label id="label_dns_lookup_query_uri" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_query_uri']; ?>"><?php echo $s['index_query_uri']; ?></label>
                      <input id="dns_lookup_query_uri" name="dns_lookup_query_uri" type="text" class="form-control" placeholder="<?php echo $s['index_placeholder_query_uri']; ?>">
                    </div>
                  </div>
                  <div id="div_dns_lookup_record_type" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <label id="label_dns_lookup_record_type" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_record_type']; ?>"><?php echo $s['index_record_type']; ?></label>
                      <select id="dns_lookup_record_type" name="dns_lookup_record_type" class="form-select" aria-labelledby="label_dns_lookup_record_type">
                        <option value="" selected="selected"><?php echo $s['system_default']; ?></option>
                        <option value="A">A</option>
                        <option value="AAAA">AAAA</option>
                        <option value="ANY">ANY</option>
                        <option value="CAA">CAA</option>
                        <option value="CDS">CDS</option>
                        <option value="CERT">CERT</option>
                        <option value="CNAME">CNAME</option>
                        <option value="DNAME">DNAME</option>
                        <option value="DNSKEY">DNSKEY</option>
                        <option value="DS">DS</option>
                        <option value="HINFO">HINFO</option>
                        <option value="HTTPS">HTTPS</option>
                        <option value="IPSECKEY">IPSECKEY</option>
                        <option value="KEY">KEY</option>
                        <option value="MX">MX</option>
                        <option value="NAPTR">NAPTR</option>
                        <option value="NS">NS</option>
                        <option value="NSEC">NSEC</option>
                        <option value="NSEC3">NSEC3</option>
                        <option value="NSEC3PARAM">NSEC3PARAM</option>
                        <option value="PTR">PTR</option>
                        <option value="RP">RP</option>
                        <option value="RRSIG">RPSIG</option>
                        <option value="SIG">SIG</option>
                        <option value="SOA">SOA</option>
                        <option value="SPF">SPF</option>
                        <option value="SRV">SRV</option>
                        <option value="SSHFP">SSHFP</option>
                        <option value="SVCB">SVCB</option>
                        <option value="TLSA">TLSA</option>
                        <option value="TXT">TXT</option>
                        <option value="WKS">WKS</option>
                      </select>
                    </div>
                  </div>
                  <div id="div_dns_lookup_edns_client_subnet" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <label id="label_dns_lookup_edns_client_subnet" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_edns_client_subnet']; ?>"><?php echo $s['index_edns_client_subnet']; ?></label>
                      <input id="dns_lookup_edns_client_subnet" name="dns_lookup_edns_client_subnet" type="text" class="form-control" placeholder="<?php echo $s['index_placeholder_edns_client_subnet']; ?>">
                    </div>
                  </div>
                  <div id="div_dns_lookup_server" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <label id="label_dns_lookup_server" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_dns_server']; ?>"><?php echo $s['index_dns_server']; ?></label>
                      <input id="dns_lookup_server" name="dns_lookup_server" type="text" class="form-control" placeholder="<?php echo $s['index_placeholder_dns_server']; ?>">
                    </div>
                  </div>
                  <div id="div_ping_hop_limit" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <label id="label_ping_hop_limit" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_hop_limit']; ?>"><?php echo $s['index_hop_limit']; ?></label>
                      <input id="ping_hop_limit" name="ping_hop_limit" type="number" class="form-control" min="1" max="255" value="64" aria-labelledby="label_ping_hop_limit">
                      <div id="div_error_ping_hop_limit" class="invalid-feedback" style="display: none"><?php echo $s['index_error_hop_limit_out_of_range']; ?></div>
                    </div>
                  </div>
                  <div id="div_traceroute_protocol" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <label id="label_traceroute_protocol" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_protocol_traceroute']; ?>"><?php echo $s['index_protocol']; ?></label>
                      <select id="traceroute_protocol" name="traceroute_protocol" class="form-select" aria-labelledby="label_traceroute_protocol">
                        <option value="icmp" selected="selected">ICMP</option>
                        <option value="tcp">TCP</option>
                        <option value="udp">UDP</option>
                      </select>
                    </div>
                  </div>
                  <div id="div_traceroute_first_hop_limit" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <label id="label_traceroute_first_hop_limit" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_first_hop_limit']; ?>"><?php echo $s['index_first_hop_limit']; ?></label>
                      <input id="traceroute_first_hop_limit" name="traceroute_first_hop_limit" type="number" class="form-control" min="1" max="255" value="1" aria-labelledby="label_traceroute_first_hop_limit">
                      <div id="div_error_traceroute_first_hop_limit" class="invalid-feedback" style="display: none"><?php echo $s['index_error_first_hop_limit_out_of_range']; ?></div>
                    </div>
                  </div>
                  <div id="div_traceroute_max_hop_limit" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <label id="label_traceroute_max_hop_limit" class="input-group-text" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_maximum_hop_limit']; ?>"><?php echo $s['index_maximum_hop_limit']; ?></label>
                      <input id="traceroute_max_hop_limit" name="traceroute_max_hop_limit" type="number" class="form-control" min="1" max="255" value="30" aria-labelledby="label_traceroute_max_hop_limit">
                      <div id="div_error_traceroute_max_hop_limit" class="invalid-feedback" style="display: none"><?php echo $s['index_error_maximum_hop_limit_out_of_range']; ?></div>
                    </div>
                  </div>
                  <div id="div_traceroute_display_icmp_extensions" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <div class="form-check form-switch">
                        <input id="traceroute_display_icmp_extensions" name="traceroute_display_icmp_extensions" class="form-check-input" type="checkbox" checked="checked">
                        <label id="label_traceroute_display_icmp_extensions" class="form-check-label" for="traceroute_display_icmp_extensions" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_display_icmp_extensions']; ?>"><?php echo $s['index_display_icmp_extensions']; ?></label>
                      </div>
                    </div>
                  </div>
                  <div id="div_traceroute_display_mtu_info" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <div class="form-check form-switch">
                        <input id="traceroute_display_mtu_info" name="traceroute_display_mtu_info" class="form-check-input" type="checkbox">
                        <label id="label_traceroute_display_mtu_info" class="form-check-label" for="traceroute_display_mtu_info" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_display_mtu_info']; ?>"><?php echo $s['index_display_mtu_info']; ?></label>
                      </div>
                    </div>
                  </div>
                  <div id="div_traceroute_display_as_number" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <div class="form-check form-switch">
                        <input id="traceroute_display_as_number" name="traceroute_display_as_number" class="form-check-input" type="checkbox" checked="checked">
                        <label id="label_traceroute_display_as_number" class="form-check-label" for="traceroute_display_as_number" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_display_as_number']; ?>"><?php echo $s['index_display_as_number']; ?></label>
                      </div>
                    </div>
                  </div>
                  <div id="div_resolve_hostname" class="col-md-6 mb-3" style="display: none">
                    <div class="input-group">
                      <div class="form-check form-switch">
                        <input id="resolve_hostname" name="resolve_hostname" class="form-check-input" type="checkbox" checked="checked">
                        <label id="label_resolve_hostname" class="form-check-label" for="resolve_hostname" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_resolve_hostname']; ?>"><?php echo $s['index_resolve_hostname']; ?></label>
                      </div>
                    </div>
                  </div>
                  <div id="div_command_no_options" class="col-md-6 mb-3 m-auto">
                    <div class="text-center">
                      <p><?php echo $s['index_text_command_no_available_options']; ?></p>
                    </div>
                  </div>
                </div>
                <br>
                <div class="d-flex align-items-center">
                  <button type="submit" id="submit" name="submit" class="btn btn-primary m-auto" disabled="disabled" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="bottom" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['index_description_run_test']; ?>"><?php echo $s['index_run_test']; ?></button>
                </div>
              </fieldset>
            </form>
          </div>
        </div>
      </section>

      <!-- Results -->
      <section id="results">
        <div class="card border-primary mb-3">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
              <h4 class="card-title text-primary"><?php echo $s['index_results']; ?></h4>
              <div id="spinner_loading" class="spinner-border text-primary" role="status" style="display: none">
                <span class="visually-hidden"><?php echo $s['loading']; ?></span>
              </div>
              <div id="div_copy_to_clipboard_results">
                <button type="button" id="button_copy_to_clipboard_results" class="btn border-0" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['copy_to_clipboard']; ?>" aria-label="<?php echo $s['copy_to_clipboard']; ?>" style="display: none">
                  <i id="i_copy_to_clipboard_results" class="bi bi-clipboard text-primary"></i>
                </button>
              </div>
            </div>
            <hr>
            <div class="row">
              <div id="div_command_no_results_initial" class="col-md-6 mb-3 m-auto">
                <div class="text-center">
                  <p><?php echo $s['index_text_command_no_results_initial']; ?></p>
                </div>
              </div>
              <div id="div_command_no_results" class="col-md-6 mb-3 m-auto" style="display: none">
                <div class="text-center">
                  <p><?php echo $s['index_text_command_no_results']; ?></p>
                </div>
              </div>
              <div id="div_command_error_occurred" class="col-md-6 mb-3 m-auto" style="display: none">
                <div class="text-center">
                  <p><?php echo $s['error_occurred']; ?></p>
                </div>
              </div>
            </div>
            <pre id="response" style="display: none"></pre>
          </div>
        </div>
      </section>

      <!-- Footer -->
      <footer class="footer">
        <hr>
        <p style="display: inline">
          <p style="display: inline">Page rendered in <?php echo $renderTime ?> ms. | Powered by <a href="#dialog_about" data-bs-toggle="modal"><?php echo $s['website_name']; ?></a>, version <?php echo Telephone\BuildInfo::getVersionName() . ' ' . '(' . Telephone\BuildInfo::getVersionCode() . ')'; ?>.</p>
        </p>
      </footer>
    </div>
    <!-- /container -->

    <!-- Back to top -->
    <button type="button" id="button_back_to_top" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-html="true" data-bs-placement="top" data-bs-custom-class="tooltip_custom" data-bs-original-title="<?php echo $s['back_to_top']; ?>" aria-label="<?php echo $s['back_to_top']; ?>" style="display: none">
      <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Modal dialogs (warning) -->
    <div id="warning_dialog_in_engineering_sample_state" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="warning_dialog_in_engineering_sample_state" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-warning"><?php echo '<i class="bi bi-exclamation-triangle"></i>' . '<span class="ms-2">' . $s['engineering_sample_warning_title'] . '</span>'; ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo $s['close']; ?>">
              <span aria-hidden="true"></span>
            </button>
          </div>
          <div class="modal-body">
            <p><?php echo $s['engineering_sample_warning_text']; ?></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo $s['got_it']; ?></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal dialogs (error) -->
    <div id="error_dialog_wrong_csrf_token" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="error_dialog_wrong_csrf_token" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger"><?php echo '<i class="bi bi-exclamation-circle"></i>' . '<span class="ms-2">' . $s['error_occurred'] . '</span>'; ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo $s['close']; ?>">
              <span aria-hidden="true"></span>
            </button>
          </div>
          <div class="modal-body">
            <p><?php echo $s['index_error_wrong_csrf_token']; ?></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo $s['ok']; ?></button>
          </div>
        </div>
      </div>
    </div>

    <div id="error_dialog_unauthorized_request" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="error_dialog_unauthorized_request" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger"><?php echo '<i class="bi bi-exclamation-circle"></i>' . '<span class="ms-2">' . $s['error_occurred'] . '</span>'; ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo $s['close']; ?>">
              <span aria-hidden="true"></span>
            </button>
          </div>
          <div class="modal-body">
            <p><?php echo $s['index_error_unauthorized_request']; ?></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo $s['ok']; ?></button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal dialogs (general) -->
    <div id="dialog_about" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="dialog_about" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-primary"><?php echo '<i class="bi bi-info-circle"></i>' . '<span class="ms-2">' . $s['index_about'] . ' ' . $s['website_name'] . '</span>'; ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo $s['close']; ?>">
              <span aria-hidden="true"></span>
            </button>
          </div>
          <div class="modal-body">
            <p><?php echo $s['website_name']; ?></p>
            <p><?php echo $s['version'] . ':' . ' ' . Telephone\BuildInfo::getVersionName() . ' ' . '(' . Telephone\BuildInfo::getVersionCode() . ')'; ?></p>
            <?php
                if (Telephone\BuildInfo::isEngineeringSampleBuild() === true) {
                    echo '<p>' . $s['engineering_sample'] . ':' . ' ' . $s['yes'] . '</p>';
                } else {
                    echo '<p>' . $s['engineering_sample'] . ':' . ' ' . $s['no'] . '</p>';
                }
            ?>
            <p style="display: inline"><?php echo $s['description']; ?>: 
              <p style="display: inline">
                <?php echo $s['website_description']; ?>
                <p></p>
                <?php echo $s['index_project_based_on']; ?> <a href="https://github.com/telephone/LookingGlass" target="_blank">Nick&apos;s LookingGlass</a>.
              </p>
            </p>
            <p style="display: inline"><?php echo $s['index_original_developer']; ?>: 
              <p style="display: inline">
                <a href="https://github.com/telephone" target="_blank">Nick (telephone)</a>.
              </p>
            </p>
            <p style="display: inline"><?php echo $s['index_current_developer']; ?>: 
              <p style="display: inline">
                jellybean13.
              </p>
            </p>
            <p style="display: inline"><?php echo $s['index_what_is_working']; ?>: 
              <p style="display: inline"><?php echo $s['index_description_what_is_working']; ?></p>
            </p>
            <p style="display: inline"><?php echo $s['index_what_is_not_working']; ?>: 
              <p style="display: inline"><?php echo $s['index_description_what_is_not_working']; ?></p>
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-target="#dialog_open_source_licenses" data-bs-toggle="modal" data-bs-dismiss="modal"><?php echo $s['index_open_source_licenses']; ?></button>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo $s['ok']; ?></button>
          </div>
        </div>
      </div>
    </div>

    <div id="dialog_open_source_licenses" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="dialog_open_source_licenses" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-primary"><?php echo '<i class="bi bi-info-circle"></i>' . '<span class="ms-2">' . $s['index_open_source_licenses'] . '</span>'; ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo $s['close']; ?>">
              <span aria-hidden="true"></span>
            </button>
          </div>
          <div class="modal-body">
            <p>LookingGlass:</p>
            <p>
              MIT License
              <p></p>
              Copyright (C) 2015 Nick Adams &lt;nick@iamtelephone.com&gt;
              <br>
              Copyright (C) 2023 jellybean13
              <p></p>
              Permission is hereby granted, free of charge, to any person obtaining a copy
              of this software and associated documentation files (the "Software"), to deal
              in the Software without restriction, including without limitation the rights
              to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
              copies of the Software, and to permit persons to whom the Software is
              furnished to do so, subject to the following conditions:
              <p></p>
              The above copyright notice and this permission notice shall be included in all
              copies or substantial portions of the Software.
              <p></p>
              THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
              IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
              FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
              AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
              LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
              OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
              SOFTWARE.
            </p>
            <hr>
            <p><a href="https://getbootstrap.com" target="_blank">Bootstrap</a>:</p>
            <p>
              MIT License
              <p></p>
              Copyright (C) 2011-2023 The Bootstrap Authors
              <p></p>
              Permission is hereby granted, free of charge, to any person obtaining a copy
              of this software and associated documentation files (the "Software"), to deal
              in the Software without restriction, including without limitation the rights
              to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
              copies of the Software, and to permit persons to whom the Software is
              furnished to do so, subject to the following conditions:
              <p></p>
              The above copyright notice and this permission notice shall be included in all
              copies or substantial portions of the Software.
              <p></p>
              THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
              IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
              FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
              AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
              LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
              OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
              SOFTWARE.
            </p>
            <hr>
            <p><a href="https://icons.getbootstrap.com" target="_blank">Bootstrap Icons</a>:</p>
            <p>
              MIT License
              <p></p>
              Copyright (C) 2019-2023 The Bootstrap Authors
              <p></p>
              Permission is hereby granted, free of charge, to any person obtaining a copy
              of this software and associated documentation files (the "Software"), to deal
              in the Software without restriction, including without limitation the rights
              to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
              copies of the Software, and to permit persons to whom the Software is
              furnished to do so, subject to the following conditions:
              <p></p>
              The above copyright notice and this permission notice shall be included in all
              copies or substantial portions of the Software.
              <p></p>
              THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
              IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
              FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
              AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
              LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
              OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
              SOFTWARE.
            </p>
            <hr>
            <p><a href="https://bootswatch.com" target="_blank">Bootswatch</a>:</p>
            <p>
              MIT License
              <p></p>
              Copyright (C) 2013 Thomas Park
              <p></p>
              Permission is hereby granted, free of charge, to any person obtaining a copy
              of this software and associated documentation files (the "Software"), to deal
              in the Software without restriction, including without limitation the rights
              to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
              copies of the Software, and to permit persons to whom the Software is
              furnished to do so, subject to the following conditions:
              <p></p>
              The above copyright notice and this permission notice shall be included in all
              copies or substantial portions of the Software.
              <p></p>
              THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
              IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
              FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
              AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
              LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
              OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
              SOFTWARE.
            </p>
            <hr>
            <p><a href="https://fonts.google.com" target="_blank">Google Fonts</a> (<a href="https://fonts.google.com/noto/specimen/Noto+Sans+Mono" target="_blank">Noto Sans Mono</a>):</p>
            <p>
              Copyright 2022 The Noto Project Authors (<a href="https://github.com/notofonts/latin-greek-cyrillic" target="_blank">https://github.com/notofonts/latin-greek-cyrillic</a>)
              <p></p>
              This Font Software is licensed under the SIL Open Font License, Version 1.1.
              <br>
              This license is copied below, and is also available with a FAQ at:
              <br>
              <a href="https://scripts.sil.org/OFL" target="_blank">https://scripts.sil.org/OFL</a>
              <p></p>
              <p></p>
              -----------------------------------------------------------
              <br>
              SIL OPEN FONT LICENSE Version 1.1 - 26 February 2007
              <br>
              -----------------------------------------------------------
              <p></p>
              <p></p>
              PREAMBLE
              <br>
              The goals of the Open Font License (OFL) are to stimulate worldwide
              development of collaborative font projects, to support the font creation
              efforts of academic and linguistic communities, and to provide a free and
              open framework in which fonts may be shared and improved in partnership
              with others.
              <p></p>
              The OFL allows the licensed fonts to be used, studied, modified and
              redistributed freely as long as they are not sold by themselves. The
              fonts, including any derivative works, can be bundled, embedded,
              redistributed and/or sold with any software provided that any reserved
              names are not used by derivative works. The fonts and derivatives,
              however, cannot be released under any other type of license. The
              requirement for fonts to remain under this license does not apply
              to any document created using the fonts or their derivatives.
              <p></p>
              DEFINITIONS
              <br>
              "Font Software" refers to the set of files released by the Copyright
              Holder(s) under this license and clearly marked as such. This may
              include source files, build scripts and documentation.
              <p></p>
              "Reserved Font Name" refers to any names specified as such after the
              copyright statement(s).
              <p></p>
              "Original Version" refers to the collection of Font Software components as
              distributed by the Copyright Holder(s).
              <p></p>
              "Modified Version" refers to any derivative made by adding to, deleting,
              or substituting -- in part or in whole -- any of the components of the
              Original Version, by changing formats or by porting the Font Software to a
              new environment.
              <p></p>
              "Author" refers to any designer, engineer, programmer, technical
              writer or other person who contributed to the Font Software.
              <p></p>
              PERMISSION & CONDITIONS
              <br>
              Permission is hereby granted, free of charge, to any person obtaining
              a copy of the Font Software, to use, study, copy, merge, embed, modify,
              redistribute, and sell modified and unmodified copies of the Font
              Software, subject to the following conditions:
              <p></p>
              1) Neither the Font Software nor any of its individual components,
              in Original or Modified Versions, may be sold by itself.
              <p></p>
              2) Original or Modified Versions of the Font Software may be bundled,
              redistributed and/or sold with any software, provided that each copy
              contains the above copyright notice and this license. These can be
              included either as stand-alone text files, human-readable headers or
              in the appropriate machine-readable metadata fields within text or
              binary files as long as those fields can be easily viewed by the user.
              <p></p>
              3) No Modified Version of the Font Software may use the Reserved Font
              Name(s) unless explicit written permission is granted by the corresponding
              Copyright Holder. This restriction only applies to the primary font name as
              presented to the users.
              <p></p>
              4) The name(s) of the Copyright Holder(s) or the Author(s) of the Font
              Software shall not be used to promote, endorse or advertise any
              Modified Version, except to acknowledge the contribution(s) of the
              Copyright Holder(s) and the Author(s) or with their explicit written
              permission.
              <p></p>
              5) The Font Software, modified or unmodified, in part or in whole,
              must be distributed entirely under this license, and must not be
              distributed under any other license. The requirement for fonts to
              remain under this license does not apply to any document created
              using the Font Software.
              <p></p>
              TERMINATION
              <br>
              This license becomes null and void if any of the above conditions are
              not met.
              <p></p>
              DISCLAIMER
              <br>
              THE FONT SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
              EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO ANY WARRANTIES OF
              MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT
              OF COPYRIGHT, PATENT, TRADEMARK, OR OTHER RIGHT. IN NO EVENT SHALL THE
              COPYRIGHT HOLDER BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
              INCLUDING ANY GENERAL, SPECIAL, INDIRECT, INCIDENTAL, OR CONSEQUENTIAL
              DAMAGES, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
              FROM, OUT OF THE USE OR INABILITY TO USE THE FONT SOFTWARE OR FROM
              OTHER DEALINGS IN THE FONT SOFTWARE.
            </p>
            <hr>
            <p><a href="https://jquery.com" target="_blank">jQuery</a>:</p>
            <p>
              MIT License
              <p></p>
              Copyright OpenJS Foundation and other contributors, <a href="https://openjsf.org" target="_blank">https://openjsf.org</a>
              <p></p>
              Permission is hereby granted, free of charge, to any person obtaining a copy
              of this software and associated documentation files (the "Software"), to deal
              in the Software without restriction, including without limitation the rights
              to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
              copies of the Software, and to permit persons to whom the Software is
              furnished to do so, subject to the following conditions:
              <p></p>
              The above copyright notice and this permission notice shall be included in all
              copies or substantial portions of the Software.
              <p></p>
              THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
              IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
              FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
              AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
              LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
              OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
              SOFTWARE.
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-target="#dialog_about" data-bs-toggle="modal" data-bs-dismiss="modal"><?php echo $s['back']; ?></button>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal"><?php echo $s['ok']; ?></button>
          </div>
        </div>
      </div>
    </div>

    <!-- JavaScript -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/LookingGlass.js"></script>
  </body>
</html>
