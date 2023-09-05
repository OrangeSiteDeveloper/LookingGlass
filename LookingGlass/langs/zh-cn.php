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
$s['website_description'] = 'LookingGlass, 一个基于 PHP 的, 允许所有人查看目标服务器信息, 并通过网页执行网络测试命令的用户友好型的图形化工具.';
$s['ok'] = '确定';
$s['cancel'] = '取消';
$s['yes'] = '是';
$s['no'] = '否';
$s['got_it'] = '知道了';
$s['toggle_navigation'] = '展开/折叠导航栏';
$s['back'] = '返回';
$s['back_to_top'] = '回到顶端';
$s['copy_to_clipboard'] = '复制到剪贴板';
$s['close'] = '关闭';
$s['error_occurred'] = '出现错误';
$s['auto'] = '自动';
$s['not_available'] = '不可用';
$s['system_default'] = '系统默认';
$s['loading'] = '正在加载...';
$s['version'] = '版本';
$s['engineering_sample'] = '工程样品';
$s['description'] = '描述';
$s['warning'] = '警告';

// Translatable texts that are from "index.php".
$s['engineering_sample_warning_title'] = '并不适合所有用户';
$s['engineering_sample_warning_text'] = '当前页面目前处于工程样品阶段. 在日后推出的版本中, 这些实验性功能可能会变更, 失效或消失. 操作时请务必谨慎.';
$s['index_home'] = '主页';
$s['index_about'] = '关于';
$s['index_original_developer'] = '原作者';
$s['index_current_developer'] = '现作者';
$s['index_project_based_on'] = '此项目基于';
$s['index_what_is_working'] = '运行正常的内容';
$s['index_description_what_is_working'] = '1. 带有相关命令选项的以下命令: dig, host, mtr (较长网络路径除外), nslookup, ping, traceroute (TCP traceroute 除外).<br>2. 基础的服务器信息, 客户端信息和网络信息.<br>3. 基于 IP 地址的频率限制.<br>4. 多语言支持.<br>5. 多色彩模式支持.';
$s['index_what_is_not_working'] = '运行异常的内容';
$s['index_description_what_is_not_working'] = '1. 带有相关命令选项的以下命令: mtr (通过 mtr 命令追踪一个较长的网络路径会遇到网关超时的问题) 和 traceroute (仅 TCP traceroute. TCP traceroute 需要 root 权限才能执行).<br>3. 多主题支持 (与一些来自 Bootswatch 的主题不兼容).';
$s['index_open_source_licenses'] = '开放源代码许可';
$s['index_language'] = '语言';
$s['index_color_mode'] = '色彩模式';
$s['index_color_mode_dark'] = '暗色';
$s['index_color_mode_light'] = '亮色';
$s['index_details'] = '详细信息';
$s['index_test'] = '测试';
$s['index_server_info'] = '服务器信息';
$s['index_server_location'] = '服务器位置';
$s['index_processor_model'] = '处理器型号';
$s['index_logical_processors'] = '逻辑处理器';
$s['index_processor_architecture'] = '处理器架构';
$s['index_kernel_version'] = '内核版本';
$s['index_virtualization_type'] = '虚拟化类型';
$s['index_load_average'] = '平均负载';
$s['index_processor_utilization'] = '处理器利用率';
$s['index_memory_utilization'] = '内存利用率';
$s['index_uptime'] = '运行时间';
$s['index_client_info'] = '客户端信息';
$s['index_client_requested_hostname'] = '你请求的服务器主机名';
$s['index_client_requested_port'] = '你请求的服务器端口';
$s['index_client_ip_address'] = '你的 IP 地址';
$s['index_client_port'] = '你的访问端口';
$s['index_client_visit_timestamps'] = '你的访问时间戳';
$s['index_client_visit_scheme'] = '你的访问方式';
$s['index_client_user_agent'] = '你的用户代理 (UA)';
$s['index_client_requested_http_version'] = '你请求的 HTTP 协议版本';
$s['index_client_requested_tls_version'] = '你请求的 TLS 协议版本';
$s['index_client_language_preference'] = '你的语言偏好';
$s['index_network_info'] = '网络信息';
$s['index_network_interface'] = '网络接口';
$s['index_description_network_interface_network_info'] = '指定一个网络接口以查看信息.';
$s['index_interface_speed'] = '接口速率';
$s['index_network_tests'] = '网络测试';
$s['index_test_ipv4_address'] = '测试 IPv4 地址';
$s['index_test_ipv6_address'] = '测试 IPv6 地址';
$s['index_test_files'] = '测试文件';
$s['index_hostname'] = '主机名';
$s['index_description_hostname'] = '域名或 IP 地址';
$s['index_command'] = '命令';
$s['index_description_command'] = '支持的命令: dig, host, mtr, nslookup, ping, traceroute.';
$s['index_select_command'] = '选择一个命令';
$s['index_test_commands'] = '测试命令';
$s['index_command_options'] = '命令选项';
$s['index_description_network_interface_command_options'] = '指定一个网络接口, 这对多网卡设备特别有用.<br>注意: 指定一个错误的网络接口会导致异常.';
$s['index_address_family'] = '地址族';
$s['index_description_address_family'] = '指定一个地址族, 这对支持双栈网络的设备特别有用.<br>注意: 指定一个错误的地址族会导致异常.';
$s['index_destination_port'] = '目的端口';
$s['index_description_destination_port'] = '目的端口的默认值因命令而异.<br>注意: 此选项在使用 ICMP traceroute 时不可用.';
$s['index_placeholder_destination_port'] = '留空以使用默认目的端口.';
$s['index_protocol'] = '协议';
$s['index_description_protocol_dns_lookup'] = '指定 DNS 查询使用的协议.<br>注意: 此选项在使用加密 DNS 时不可用.';
$s['index_dns_lookup_mode'] = 'DNS 查询模式';
$s['index_description_dns_lookup_mode'] = '常规模式: 域名 --> IPv4/IPv6 地址;<br>反向模式: IPv4/IPv6 地址 --> 域名.';
$s['index_select_dns_lookup_mode_normal'] = '常规';
$s['index_select_dns_lookup_mode_reversed'] = '反向';
$s['index_dns_encryption_mode'] = '加密模式';
$s['index_description_dns_encryption_mode'] = '指定加密模式以使用 DNS over HTTPS (DOH) 和 DNS over TLS (DOT).<br>注意: 不是所有的上游 DNS 服务器都支持 DNS over HTTPS (DOH) 和 DNS over TLS (DOT).';
$s['index_select_dns_encryption_mode_no_encryption'] = '不加密';
$s['index_select_dns_encryption_mode_doh'] = 'DNS over HTTPS (DoH)';
$s['index_select_dns_encryption_mode_dot'] = 'DNS over TLS (DoT)';
$s['index_query_uri'] = '查询 URI';
$s['index_description_query_uri'] = '指定使用 DNS over HTTPS (DOH) 查询的路径. 这个路径必须以 &quot;&#47;&quot; 开头.<br>默认值: &quot;&#47;dns-query&quot;.';
$s['index_placeholder_query_uri'] = '留空以使用默认查询 URI.';
$s['index_record_type'] = 'DNS 记录类型';
$s['index_description_record_type'] = '指定要进行 DNS 查询的记录类型.<br>注意: 不是所有的上游 DNS 服务器都支持以下记录类型.';
$s['index_edns_client_subnet'] = 'EDNS 客户端子网 (ECS)';
$s['index_description_edns_client_subnet'] = '指定客户端所在的子网以进行 DNS 查询.<br>注意: 向上游 DNS 服务器发送带有不支持的客户端所在子网信息的查询请求或者是向不支持此特性的上游 DNS 服务器发送带有客户端所在子网信息的查询请求可能会导致异常.';
$s['index_placeholder_edns_client_subnet'] = '留空以禁用此特性.';
$s['index_dns_server'] = 'DNS 服务器';
$s['index_description_dns_server'] = '指定上游 DNS 服务器.<br>注意: 指定一个错误的上游 DNS 服务器会导致异常.';
$s['index_placeholder_dns_server'] = '留空以使用默认服务器.';
$s['index_hop_limit'] = '跳数限制';
$s['index_description_hop_limit'] = '指定 IP 数据包的跳数限制.<br>默认值: 64.';
$s['index_description_protocol_traceroute'] = '指定路由追踪使用的协议.';
$s['index_first_hop_limit'] = '首次运行的跳数限制';
$s['index_description_first_hop_limit'] = '指定以何种跳数限制开始.<br>默认值: 1.';
$s['index_maximum_hop_limit'] = '最大跳数限制';
$s['index_description_maximum_hop_limit'] = '指定 traceroute 探测的最大跳数限制.<br>默认值: 30.';
$s['index_display_icmp_extensions'] = 'ICMP 扩展';
$s['index_description_display_icmp_extensions'] = '是否显示 ICMP 扩展 (RFC 4884).<br>注意: 此选项仅在使用 ICMP traceroute 时可用.';
$s['index_display_mtu_info'] = 'MTU 信息';
$s['index_description_display_mtu_info'] = '是否显示 MTU 信息.';
$s['index_display_as_number'] = 'AS 编号';
$s['index_description_display_as_number'] = '是否显示 AS 编号.<br>注意: 如果服务器端的网络连接不稳定, AS 编号可能无法正确显示.';
$s['index_resolve_hostname'] = '解析主机名';
$s['index_description_resolve_hostname'] = '如果启用, 相关命令将尝试解析主机名并输出主机名和相应的 IP 地址.';
$s['index_text_command_no_available_options'] = '选中的命令未提供任何选项.';
$s['index_run_test'] = '运行测试';
$s['index_description_run_test'] = '点按以执行选中的命令.';
$s['index_results'] = '运行结果';
$s['index_text_command_no_results'] = '选中的命令未返回任何结果.';
$s['index_text_command_no_results_initial'] = '当你运行测试时, 它的返回结果将显示在这里.';
$s['index_error_configuration_variables_missing'] = '配置变量缺失. 请运行 &quot;configure.sh&quot; 以解决此问题.';
$s['index_error_configuration_file_missing'] = '&quot;Config.php&quot; 不存在. 请运行 &quot;configure.sh&quot; 以解决此问题.';
$s['index_error_empty_hostname'] = '主机名不能为空.';
$s['index_error_empty_command'] = '选择的命令不能为空.';
$s['index_error_destination_port_out_of_range'] = '目的端口必须在 1 到 65535 之间.';
$s['index_error_hop_limit_out_of_range'] = '跳数限制必须在 1 到 255 之间.';
$s['index_error_first_hop_limit_out_of_range'] = '首次运行的跳数限制必须在 1 到 255 之间.';
$s['index_error_maximum_hop_limit_out_of_range'] = '最大跳数限制必须在 1 到 255 之间.';
$s['index_error_wrong_csrf_token'] = 'CSRF 令牌丢失或不正确. 请刷新页面以解决此问题.';
$s['index_error_unauthorized_request'] = '未经授权的请求.';

// Languages.
$s['lang_ar-sa'] = '阿拉伯语 (沙特阿拉伯)';
$s['lang_bg-bg'] = '保加利亚语';
$s['lang_cs-cz'] = '捷克语';
$s['lang_da-dk'] = '丹麦语';
$s['lang_de-de'] = '德语';
$s['lang_el-gr'] = '希腊语';
$s['lang_en-gb'] = '英语 (英国)';
$s['lang_en-us'] = '英语 (美国)';
$s['lang_es-es'] = '西班牙语 (西班牙)';
$s['lang_es-mx'] = '西班牙语 (墨西哥)';
$s['lang_et-ee'] = '爱沙尼亚语';
$s['lang_fi-fi'] = '芬兰语';
$s['lang_fr-ca'] = '法语 (加拿大)';
$s['lang_fr-fr'] = '法语 (法国)';
$s['lang_he-il'] = '希伯来语';
$s['lang_hr-hr'] = '克罗地亚语';
$s['lang_hu-hu'] = '匈牙利语';
$s['lang_it-it'] = '意大利语';
$s['lang_ja-jp'] = '日语';
$s['lang_ko-kr'] = '朝鲜语';
$s['lang_lt-lt'] = '立陶宛语';
$s['lang_lv-lv'] = '拉脱维亚语';
$s['lang_nb-no'] = '挪威语 (博克马尔语)';
$s['lang_nl-nl'] = '荷兰语';
$s['lang_pl-pl'] = '波兰语';
$s['lang_pt-br'] = '葡萄牙语 (巴西)';
$s['lang_pt-pt'] = '葡萄牙语 (葡萄牙)';
$s['lang_ro-ro'] = '罗马尼亚语';
$s['lang_ru-ru'] = '俄语';
$s['lang_sk-sk'] = '斯洛伐克语';
$s['lang_sl-si'] = '斯洛文尼亚语';
$s['lang_sr-latn-rs'] = '塞尔维亚语 (拉丁语)';
$s['lang_sv-se'] = '瑞典语';
$s['lang_th-th'] = '泰语';
$s['lang_tr-tr'] = '土耳其语';
$s['lang_uk-ua'] = '乌克兰语';
$s['lang_vi-vn'] = '越南语 (越南)';
$s['lang_zh-cn'] = '中文 (简体)';
$s['lang_zh-hk'] = '中文 (香港)';
$s['lang_zh-tw'] = '中文 (繁体)';
