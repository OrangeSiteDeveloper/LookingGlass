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

// General.
$s['website_name'] = 'LookingGlass';
$s['website_description'] = 'LookingGlass, a user-friendly PHP based graphical tool that allows everyone to view the information of the target server and execute network testing commands with options via a web interface.';
$s['ok'] = 'OK';
$s['cancel'] = 'Cancel';
$s['yes'] = 'Yes';
$s['no'] = 'No';
$s['got_it'] = 'Got it';
$s['toggle_navigation'] = 'Expand/Collapse navigation bar';
$s['back'] = 'Back';
$s['back_to_top'] = 'Back to top';
$s['copy_to_clipboard'] = 'Copy to clipboard';
$s['close'] = 'Close';
$s['error_occurred'] = 'An error occurred';
$s['auto'] = 'Auto';
$s['not_available'] = 'Unavailable';
$s['system_default'] = 'System default';
$s['loading'] = 'Loading...';
$s['version'] = 'Version';
$s['engineering_sample'] = 'Engineering sample';
$s['description'] = 'Description';
$s['warning'] = 'Warning';

// Translatable texts that are from "index.php".
$s['engineering_sample_warning_title'] = 'Fun for some but not for all';
$s['engineering_sample_warning_text'] = 'The current page is in engineering sample state. These experimental features may change, break, or disappear in future releases. Proceed with caution.';
$s['index_home'] = 'Home';
$s['index_about'] = 'About';
$s['index_original_developer'] = 'Original developer';
$s['index_current_developer'] = 'Current developer';
$s['index_project_based_on'] = 'This project is based on';
$s['index_what_is_working'] = 'What&apos;s working';
$s['index_description_what_is_working'] = '1. The following commands with their options: dig, host, mtr (except longer network path), nslookup, ping, traceroute (except TCP traceroute).<br>2. Basic server info, client info and network info.<br>3. IP address based rate limit feature.<br>4. Multi-language support.<br>5. Multiple color modes support.';
$s['index_what_is_not_working'] = 'What&apos;s not working';
$s['index_description_what_is_not_working'] = '1. The following commands with their options: mtr (tracing a longer network path via command mtr will encounter the gateway time-out issue) and traceroute (TCP traceroute only. TCP traceroute requires root privileges to execute).<br>2. Multi-theme support (incompatible with some themes from Bootswatch).';
$s['index_open_source_licenses'] = 'Open source licenses';
$s['index_language'] = 'Language';
$s['index_color_mode'] = 'Color mode';
$s['index_color_mode_dark'] = 'Dark';
$s['index_color_mode_light'] = 'Light';
$s['index_details'] = 'Details';
$s['index_test'] = 'Test';
$s['index_server_info'] = 'Server info';
$s['index_server_location'] = 'Server location';
$s['index_processor_model'] = 'Processor model';
$s['index_logical_processors'] = 'Logical processors';
$s['index_processor_architecture'] = 'Processor architecture';
$s['index_kernel_version'] = 'Kernel version';
$s['index_virtualization_type'] = 'Virtualization type';
$s['index_load_average'] = 'Load average';
$s['index_processor_utilization'] = 'Processor utilization';
$s['index_memory_utilization'] = 'Memory utilization';
$s['index_uptime'] = 'Uptime';
$s['index_client_info'] = 'Client info';
$s['index_client_requested_hostname'] = 'The server hostname you requested';
$s['index_client_requested_port'] = 'The server port you requested';
$s['index_client_ip_address'] = 'Your IP address';
$s['index_client_port'] = 'Your accessing port';
$s['index_client_visit_timestamps'] = 'Your visit timestamps';
$s['index_client_visit_scheme'] = 'Your visit scheme';
$s['index_client_user_agent'] = 'Your user agent (UA)';
$s['index_client_requested_http_version'] = 'The HTTP protocol version you requested';
$s['index_client_requested_tls_version'] = 'The TLS protocol version you requested';
$s['index_client_language_preference'] = 'Your language preferences';
$s['index_network_info'] = 'Network info';
$s['index_network_interface'] = 'Network interface';
$s['index_description_network_interface_network_info'] = 'Specify a network interface to view its info.';
$s['index_interface_speed'] = 'Interface speed';
$s['index_network_tests'] = 'Network tests';
$s['index_test_ipv4_address'] = 'Test IPv4 address';
$s['index_test_ipv6_address'] = 'Test IPv6 address';
$s['index_test_files'] = 'Test file(s)';
$s['index_hostname'] = 'Hostname';
$s['index_description_hostname'] = 'A single domain name or IP address.';
$s['index_command'] = 'Command';
$s['index_description_command'] = 'Supported commands: dig, host, mtr, nslookup, ping, traceroute.';
$s['index_select_command'] = 'Select a command';
$s['index_test_commands'] = 'Test commands';
$s['index_command_options'] = 'Command options';
$s['index_description_network_interface_command_options'] = 'Specify a network interface, which is useful to devices with multiple network interfaces.<br>Note: Specifying a wrong network interface will lead to malfunction.';
$s['index_address_family'] = 'Address family';
$s['index_description_address_family'] = 'Specify an address family, which is useful to devices with dual-stack network.<br>Note: Specifying a wrong address family will lead to malfunction.';
$s['index_destination_port'] = 'Destination port';
$s['index_description_destination_port'] = 'The default value of default destination port is varied by commands.<br>Note: This option is unavailable while using ICMP traceroute.';
$s['index_placeholder_destination_port'] = 'Leave it empty to use the default destination port.';
$s['index_protocol'] = 'Protocol';
$s['index_description_protocol_dns_lookup'] = 'Specify the protocol for DNS lookup.<br>Note: This option is unavailable while using encrypted DNS.';
$s['index_dns_lookup_mode'] = 'DNS lookup mode';
$s['index_description_dns_lookup_mode'] = 'Normal mode: Domain --> IPv4/IPv6 address;<br>Reversed mode: IPv4/IPv6 address --> Domain.';
$s['index_select_dns_lookup_mode_normal'] = 'Normal';
$s['index_select_dns_lookup_mode_reversed'] = 'Reversed';
$s['index_dns_encryption_mode'] = 'Encryption mode';
$s['index_description_dns_encryption_mode'] = 'Specify encryption mode to use DNS over HTTPS (DOH) and DNS over TLS (DOT).<br>Note: Not all upstream DNS servers support DNS over HTTPS (DOH) and DNS over TLS (DOT).';
$s['index_select_dns_encryption_mode_no_encryption'] = 'No encryption';
$s['index_select_dns_encryption_mode_doh'] = 'DNS over HTTPS (DoH)';
$s['index_select_dns_encryption_mode_dot'] = 'DNS over TLS (DoT)';
$s['index_query_uri'] = 'Query URI';
$s['index_description_query_uri'] = 'Specify path for DNS over HTTPS (DOH). The path must start with &quot;&#47;&quot;.<br>Default value: &quot;&#47;dns-query&quot;.';
$s['index_placeholder_query_uri'] = 'Leave it empty to use the default query URI.';
$s['index_record_type'] = 'DNS record type';
$s['index_description_record_type'] = 'Specify the record type for DNS lookup.<br>Note: Not all upstream DNS servers support the following record types.';
$s['index_edns_client_subnet'] = 'EDNS client subnet (ECS)';
$s['index_description_edns_client_subnet'] = 'Specify the client subnet for DNS lookup.<br>Note: Sending queries with unsupported client subnet to upstream DNS server or sending queries with client subnet to upstream DNS servers that don\'t support this feature may lead to malfunction.';
$s['index_placeholder_edns_client_subnet'] = 'Leave it empty to disable this feature.';
$s['index_dns_server'] = 'DNS server';
$s['index_description_dns_server'] = 'Specify the upstream DNS server.<br>Note: Specifying a wrong upstream DNS server will lead to malfunction.';
$s['index_placeholder_dns_server'] = 'Leave it empty to use the default server.';
$s['index_hop_limit'] = 'Hop limit';
$s['index_description_hop_limit'] = 'Specify the hop limit of IP packets.<br>Default value: 64.';
$s['index_description_protocol_traceroute'] = 'Specify the protocol for traceroute.';
$s['index_first_hop_limit'] = 'First hop limit';
$s['index_description_first_hop_limit'] = 'Specify with what hop limit to start.<br>Default value: 1.';
$s['index_maximum_hop_limit'] = 'Maximum hop limit';
$s['index_description_maximum_hop_limit'] = 'Specify the maximum number of hops traceroute will probe.<br>Default value: 30.';
$s['index_display_icmp_extensions'] = 'ICMP extensions';
$s['index_description_display_icmp_extensions'] = 'Whether to display ICMP extensions or not (RFC 4884).<br>Note: This option is only available while using ICMP traceroute.';
$s['index_display_mtu_info'] = 'MTU info';
$s['index_description_display_mtu_info'] = 'Whether to display MTU info or not.';
$s['index_display_as_number'] = 'AS number';
$s['index_description_display_as_number'] = 'Whether to display AS number or not.<br>Note: The AS number may not display correctly if the network connection of the server side is unstable.';
$s['index_resolve_hostname'] = 'Resolve hostname';
$s['index_description_resolve_hostname'] = 'If enabled, the relevant command will attempt to resolve the hostname and output the hostname and the corresponding IP address.';
$s['index_text_command_no_available_options'] = 'Selected command does not provide any options.';
$s['index_run_test'] = 'Run Test';
$s['index_description_run_test'] = 'Tap it to execute the selected command.';
$s['index_results'] = 'Results';
$s['index_text_command_no_results'] = 'Selected command does not return any results.';
$s['index_text_command_no_results_initial'] = 'When you run a test, its return results will be displayed here.';
$s['index_error_configuration_variables_missing'] = 'Missing configuration variable(s). Please run &quot;configure.sh&quot; so as to address this issue.';
$s['index_error_configuration_file_missing'] = '&quot;Config.php&quot; does not exist. Please run &quot;configure.sh&quot; so as to address this issue.';
$s['index_error_empty_hostname'] = 'The hostname can not be empty.';
$s['index_error_empty_command'] = 'The selected command can not be empty.';
$s['index_error_destination_port_out_of_range'] = 'The destination port must be between 1 and 65535.';
$s['index_error_hop_limit_out_of_range'] = 'The hop limit must be between 1 and 255.';
$s['index_error_first_hop_limit_out_of_range'] = 'The first hop limit must be between 1 and 255.';
$s['index_error_maximum_hop_limit_out_of_range'] = 'The maximum hop limit must be between 1 and 255.';
$s['index_error_wrong_csrf_token'] = 'Missing or incorrect CSRF token. Please refresh this page so as to address it.';
$s['index_error_unauthorized_request'] = 'Unauthorized request.';

// Languages.
$s['lang_ar-sa'] = 'Arabic (Saudi Arabia)';
$s['lang_bg-bg'] = 'Bulgarian';
$s['lang_cs-cz'] = 'Czech';
$s['lang_da-dk'] = 'Danish';
$s['lang_de-de'] = 'German';
$s['lang_el-gr'] = 'Greek';
$s['lang_en-gb'] = 'English (United Kingdom)';
$s['lang_en-us'] = 'English (United States)';
$s['lang_es-es'] = 'Spanish (Spain)';
$s['lang_es-mx'] = 'Spanish (Mexico)';
$s['lang_et-ee'] = 'Estonian';
$s['lang_fi-fi'] = 'Finnish';
$s['lang_fr-ca'] = 'French (Canada)';
$s['lang_fr-fr'] = 'French (France)';
$s['lang_he-il'] = 'Hebrew';
$s['lang_hr-hr'] = 'Croatian';
$s['lang_hu-hu'] = 'Hungarian';
$s['lang_it-it'] = 'Italian';
$s['lang_ja-jp'] = 'Japanese';
$s['lang_ko-kr'] = 'Korean';
$s['lang_lt-lt'] = 'Lithuanian';
$s['lang_lv-lv'] = 'Latvian';
$s['lang_nb-no'] = 'Norwegian (Bokmal)';
$s['lang_nl-nl'] = 'Dutch';
$s['lang_pl-pl'] = 'Polish';
$s['lang_pt-br'] = 'Portuguese (Brazil)';
$s['lang_pt-pt'] = 'Portuguese (Portugal)';
$s['lang_qps-ploc'] = 'Pseudo';
$s['lang_ro-ro'] = 'Romanian';
$s['lang_ru-ru'] = 'Russian';
$s['lang_sk-sk'] = 'Slovak';
$s['lang_sl-si'] = 'Slovenian';
$s['lang_sr-latn-rs'] = 'Serbian (Latin)';
$s['lang_sv-se'] = 'Swedish';
$s['lang_th-th'] = 'Thai';
$s['lang_tr-tr'] = 'Turkish';
$s['lang_uk-ua'] = 'Ukrainian';
$s['lang_zh-cn'] = 'Chinese (Simplified)';
$s['lang_zh-hk'] = 'Chinese (Hong Kong)';
$s['lang_zh-tw'] = 'Chinese (Traditional)';
