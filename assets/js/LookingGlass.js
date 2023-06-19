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

/*
 * LookingGlass jQuery file.
 */
$(document).ready(function() {
    // Set the client IP address to input value.
    $('#client_ip_address_test').click(function () {
        $('#host').val(document.getElementById("client_ip_address").innerText.trim());
    });

    $(window).on("scroll", function() {
        if (window.scrollY > 32 || document.documentElement.scrollTop > 32) {
            $('#button_back_to_top').fadeIn();
        } else {
            $('#button_back_to_top').fadeOut();
        }
    });

    $('#button_back_to_top').click(function() {
        // Hide all tooltips.
        $('[data-bs-toggle="tooltip"]').tooltip('hide');

        // Scroll to the top.
        document.documentElement.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });

    $('#button_copy_to_clipboard_results').click(function() {
        // Hide all tooltips.
        $('[data-bs-toggle="tooltip"]').tooltip('hide');

        // Handle the relevant event.
        copyToClipboard("response", "i_copy_to_clipboard_results");
    });

    document.querySelectorAll('button[data-bs-theme-value]').forEach(value => {
        value.addEventListener('click', () => {
            const colorMode = value.getAttribute('data-bs-theme-value');
            // First, set color mode.
            setColorMode(colorMode);
            // Then, update color mode settings.
            updateColorModeSettings(colorMode);
            // Ultimately, synchronize the active menu item of color mode.
            setActiveColorModeMenuItem(colorMode);
        });
    });

    // Form submit.
    $('#networktest').submit(function() {
        // Define variables.
        var host = $('input[name=host]').val().trim();
        var cmd = $('select[name=cmd]').val().trim();
        var dstPortOriginal = $('input[name=destination_port]').val().trim();
        var dstPortConverted = parseInt(dstPortOriginal, 10);
        var hopLimitOriginal = $('input[name=ping_hop_limit]').val().trim();
        var hopLimitConverted = parseInt(hopLimitOriginal, 10);
        var firstHopLimitOriginal = $('input[name=traceroute_first_hop_limit]').val().trim();
        var firstHopLimitConverted = parseInt(firstHopLimitOriginal, 10);
        var maxHopLimitOriginal = $('input[name=traceroute_max_hop_limit]').val().trim();
        var maxHopLimitConverted = parseInt(maxHopLimitOriginal, 10);
        var data = 'csrf_token_network_tests=' + $('input[name=csrf_token_network_tests]').val().trim()
                 + '&cmd=' + cmd + '&host=' + host
                 + '&interface=' + $('select[name=interface]').val().trim()
                 + '&address_family=' + $('select[name=address_family]').val().trim()
                 + '&destination_port=' + dstPortOriginal
                 + '&dns_lookup_protocol=' + $('select[name=dns_lookup_protocol]').val().trim()
                 + '&dns_lookup_mode=' + $('select[name=dns_lookup_mode]').val().trim()
                 + '&dns_lookup_encryption_mode=' + $('select[name=dns_lookup_encryption_mode]').val().trim()
                 + '&dns_lookup_query_uri=' + $('input[name=dns_lookup_query_uri]').val().trim()
                 + '&dns_lookup_record_type=' + $('select[name=dns_lookup_record_type]').val().trim()
                 + '&dns_lookup_edns_client_subnet=' + $('input[name=dns_lookup_edns_client_subnet]').val().trim()
                 + '&dns_lookup_server=' + $('input[name=dns_lookup_server]').val().trim()
                 + '&ping_hop_limit=' + hopLimitOriginal
                 + '&traceroute_protocol=' + $('select[name=traceroute_protocol]').val().trim()
                 + '&traceroute_first_hop_limit=' + firstHopLimitOriginal
                 + '&traceroute_max_hop_limit=' + maxHopLimitOriginal
                 + '&traceroute_display_icmp_extensions=' + $('input[name=traceroute_display_icmp_extensions]').prop('checked')
                 + '&traceroute_display_mtu_info=' + $('input[name=traceroute_display_mtu_info]').prop('checked')
                 + '&traceroute_display_as_number=' + $('input[name=traceroute_display_as_number]').prop('checked')
                 + '&resolve_hostname=' + $('input[name=resolve_hostname]').prop('checked');
        // Hide all tooltips.
        $('[data-bs-toggle="tooltip"]').tooltip('hide');
        // Remove previous validation results.
        $('#host').removeClass("is-invalid");
        $('#div_error_host').hide();
        $('#cmd').removeClass("is-invalid");
        $('#div_error_cmd').hide();
        $('#destination_port').removeClass("is-invalid");
        $('#div_error_destination_port').hide();
        $('#ping_hop_limit').removeClass("is-invalid");
        $('#div_error_ping_hop_limit').hide();
        $('#traceroute_first_hop_limit').removeClass("is-invalid");
        $('#div_error_traceroute_first_hop_limit').hide();
        $('#traceroute_max_hop_limit').removeClass("is-invalid");
        $('#div_error_traceroute_max_hop_limit').hide();
        // Quick validation.
        if (host == '') {
            $('#host').addClass("is-invalid");
            $('#div_error_host').show();
            $('#host').focus();
        } else if (cmd == '') {
            $('#cmd').addClass("is-invalid");
            $('#div_error_cmd').show();
            $('#cmd').focus();
        } else if (!$('#div_destination_port').prop('hidden') && dstPortOriginal != '' && (isNaN(dstPortConverted) || dstPortConverted <= 0 || dstPortConverted > 65535)) {
            $('#destination_port').addClass("is-invalid");
            $('#div_error_destination_port').show();
            $('#destination_port').focus();
        } else if (!$('#div_ping_hop_limit').prop('hidden') && hopLimitOriginal != '' && (isNaN(hopLimitConverted) || hopLimitConverted <= 0 || hopLimitConverted > 255)) {
            $('#ping_hop_limit').addClass("is-invalid");
            $('#div_error_ping_hop_limit').show();
            $('#ping_hop_limit').focus();
        } else if (!$('#div_traceroute_first_hop_limit').prop('hidden') && firstHopLimitOriginal != '' && (isNaN(firstHopLimitConverted) || firstHopLimitConverted <= 0 || firstHopLimitConverted > 255)) {
            $('#traceroute_first_hop_limit').addClass("is-invalid");
            $('#div_error_traceroute_first_hop_limit').show();
            $('#traceroute_first_hop_limit').focus();
        } else if (!$('#div_traceroute_max_hop_limit').prop('hidden') && maxHopLimitOriginal != '' && (isNaN(maxHopLimitConverted) || maxHopLimitConverted <= 0 || maxHopLimitConverted > 255)) {
            $('#traceroute_max_hop_limit').addClass("is-invalid");
            $('#div_error_traceroute_max_hop_limit').show();
            $('#traceroute_max_hop_limit').focus();
        } else {
            // Disable submit button + blank response.
            $('#network_tests').attr('disabled', 'true');
            $('#div_copy_to_clipboard_results').hide();
            $('#spinner_loading').show();
            $('#div_command_no_results_initial').hide();
            $('#div_command_no_results').hide();
            $('#div_command_error_occurred').hide();
            $('#response').html();

            // Call asynchronous request.
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.send(data);
            var timer;
            timer = window.setInterval(function() {
                // onCompletion().
                if (xhr.readyState == XMLHttpRequest.DONE) {
                    window.clearTimeout(timer);
                    $('#network_tests').removeAttr('disabled');
                    $('#spinner_loading').hide();
                    $('#div_copy_to_clipboard_results').show();
                    if (xhr.responseText == '') {
                        $('#button_copy_to_clipboard_results').hide();
                        $('#div_command_no_results').show();
                    }
                }

                // Output command execution results.
                if (xhr.responseText == 'Unauthorized request') {
                    $('#div_command_error_occurred').show();
                    $('#response').hide();
                    $('#button_copy_to_clipboard_results').hide();
                    new bootstrap.Modal('#error_dialog_unauthorized_request').show();
                } else if (xhr.responseText == 'Wrong CSRF token') {
                    $('#div_command_error_occurred').show();
                    $('#response').hide();
                    $('#button_copy_to_clipboard_results').hide();
                    new bootstrap.Modal('#error_dialog_wrong_csrf_token').show();
                } else {
                    $('#response').show();
                    $('#button_copy_to_clipboard_results').show();
                    $('#response').html(xhr.responseText.replace(/<br \/> +/g, '<br />'));
                }
            }, 500);
        }

        // Cancel the default behavior.
        return false;
    });

    $('#cmd').on('change', function() {
        initializeCommandSelection(this.value);
    });

    $('#dns_lookup_encryption_mode').on('change', function() {
        initializeDNSLookupEncryptionModeSelection(this.value);
    });

    $('#traceroute_protocol').on('change', function() {
        initializeTracerouteProtocolSelection(this.value);
    });

    window.setInterval(function() {
        // var data = 'csrf_token_server_info=' + $('input[name=csrf_token_server_info]').val().trim()
        //          + '&network_interface=' + $('select[name=network_interface]').val().trim();
        var data = 'network_interface=' + $('select[name=network_interface]').val().trim();
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'server_info.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send(data);
        var timer;
        timer = window.setInterval(function() {
            // onCompletion().
            if (xhr.readyState == XMLHttpRequest.DONE) {
                window.clearTimeout(timer);
                if (xhr.responseText == 'Unauthorized request') {
                    new bootstrap.Modal('#error_dialog_unauthorized_request').show();
                } else {
                    try {
                        var data = JSON.parse(xhr.responseText);
                        document.getElementById("logical_processors").innerHTML = data.logical_processors;
                        document.getElementById("load_average").innerHTML = data.system_load_1min + ", " + data.system_load_5min + ", "
                                                                          + data.system_load_15min;
                        var processorUtilizationPercentage = data.processor_utilization_percentage;
                        setUtilizationTextColor(processorUtilizationPercentage, "processor_utilization");
                        document.getElementById("processor_utilization").innerHTML = processorUtilizationPercentage + "%";
                        var memoryUtilizationPercentage = data.memory_utilization_percentage;
                        setUtilizationTextColor(memoryUtilizationPercentage, "memory_utilization");
                        document.getElementById("memory_utilization").innerHTML = data.memory_used + " " + data.memory_used_metric + "/"
                                                                                + data.memory_total + " " + data.memory_total_metric + " "
                                                                                + "(" + memoryUtilizationPercentage + "%" + ")";
                        document.getElementById("uptime").innerHTML = data.uptime;
                        document.getElementById("interface_speed").innerHTML = "RX: " + data.interface_rx_rate + " " + data.interface_rx_rate_metric + " | "
                                                                             + "TX: " + data.interface_tx_rate + " " + data.interface_tx_rate_metric;
                    } catch(e) {
                        // console.log("An error occurred. Is network connected?");
                    }
                }
            }
        }, 1000);
    }, 1000);

    function setUtilizationTextColor(utilization, textElementId) {
        $('#' + textElementId).removeClass("text-danger");
        $('#' + textElementId).removeClass("text-warning");
        if (utilization >= 90 && utilization <= 100) {
            $('#' + textElementId).addClass("text-danger");
        } else if (utilization >= 80 && utilization < 90) {
            $('#' + textElementId).addClass("text-warning");
        }
    }

    function initializeCommandSelection(cmd) {
        switch (cmd) {
            case "dig":
                $('#div_interface').hide();
                $('#div_address_family').show();
                $('#div_destination_port').show();
                initializeDNSLookupEncryptionModeSelection($('select[name=dns_lookup_encryption_mode]').val().trim());
                $('#div_dns_lookup_mode').show();
                $('#div_dns_lookup_encryption_mode').show();
                // $('#div_dns_lookup_query_uri').show();
                $('#div_dns_lookup_record_type').show();
                $('#div_dns_lookup_edns_client_subnet').show();
                $('#div_dns_lookup_server').show();
                $('#div_ping_hop_limit').hide();
                $('#div_traceroute_protocol').hide();
                $('#div_traceroute_first_hop_limit').hide();
                $('#div_traceroute_max_hop_limit').hide();
                $('#div_traceroute_display_icmp_extensions').hide();
                $('#div_traceroute_display_mtu_info').hide();
                $('#div_traceroute_display_as_number').hide();
                $('#div_resolve_hostname').hide();
                $('#div_command_no_options').hide();
                $('#submit').removeAttr('disabled');
                break;
            case "host":
                $('#div_interface').hide();
                $('#div_address_family').show();
                $('#div_destination_port').show();
                $('#div_dns_lookup_protocol').show();
                $('#div_dns_lookup_mode').hide();
                $('#div_dns_lookup_encryption_mode').hide();
                $('#div_dns_lookup_query_uri').hide();
                $('#div_dns_lookup_record_type').show();
                $('#div_dns_lookup_edns_client_subnet').hide();
                $('#div_dns_lookup_server').show();
                $('#div_ping_hop_limit').hide();
                $('#div_traceroute_protocol').hide();
                $('#div_traceroute_first_hop_limit').hide();
                $('#div_traceroute_max_hop_limit').hide();
                $('#div_traceroute_display_icmp_extensions').hide();
                $('#div_traceroute_display_mtu_info').hide();
                $('#div_traceroute_display_as_number').hide();
                $('#div_resolve_hostname').hide();
                $('#div_command_no_options').hide();
                $('#submit').removeAttr('disabled');
                break;
            case "mtr":
                $('#div_interface').show();
                $('#div_address_family').show();
                initializeTracerouteProtocolSelection($('select[name=traceroute_protocol]').val().trim());
                $('#div_dns_lookup_protocol').hide();
                $('#div_dns_lookup_mode').hide();
                $('#div_dns_lookup_encryption_mode').hide();
                $('#div_dns_lookup_query_uri').hide();
                $('#div_dns_lookup_record_type').hide();
                $('#div_dns_lookup_edns_client_subnet').hide();
                $('#div_dns_lookup_server').hide();
                $('#div_ping_hop_limit').hide();
                $('#div_traceroute_protocol').show();
                $('#div_traceroute_first_hop_limit').show();
                $('#div_traceroute_max_hop_limit').show();
                // $('#div_traceroute_display_icmp_extensions').show();
                $('#div_traceroute_display_mtu_info').hide();
                $('#div_traceroute_display_as_number').show();
                $('#div_resolve_hostname').show();
                $('#div_command_no_options').hide();
                $('#submit').removeAttr('disabled');
                break;
            case "nslookup":
                $('#div_interface').hide();
                $('#div_address_family').hide();
                $('#div_destination_port').show();
                $('#div_dns_lookup_protocol').show();
                $('#div_dns_lookup_mode').hide();
                $('#div_dns_lookup_encryption_mode').hide();
                $('#div_dns_lookup_query_uri').hide();
                $('#div_dns_lookup_record_type').show();
                $('#div_dns_lookup_edns_client_subnet').hide();
                $('#div_dns_lookup_server').show();
                $('#div_ping_hop_limit').hide();
                $('#div_traceroute_protocol').hide();
                $('#div_traceroute_first_hop_limit').hide();
                $('#div_traceroute_max_hop_limit').hide();
                $('#div_traceroute_display_icmp_extensions').hide();
                $('#div_traceroute_display_mtu_info').hide();
                $('#div_traceroute_display_as_number').hide();
                $('#div_resolve_hostname').hide();
                $('#div_command_no_options').hide();
                $('#submit').removeAttr('disabled');
                break;
            case "ping":
                $('#div_interface').show();
                $('#div_address_family').show();
                $('#div_destination_port').hide();
                $('#div_dns_lookup_protocol').hide();
                $('#div_dns_lookup_mode').hide();
                $('#div_dns_lookup_encryption_mode').hide();
                $('#div_dns_lookup_query_uri').hide();
                $('#div_dns_lookup_record_type').hide();
                $('#div_dns_lookup_edns_client_subnet').hide();
                $('#div_dns_lookup_server').hide();
                $('#div_ping_hop_limit').show();
                $('#div_traceroute_protocol').hide();
                $('#div_traceroute_first_hop_limit').hide();
                $('#div_traceroute_max_hop_limit').hide();
                $('#div_traceroute_display_icmp_extensions').hide();
                $('#div_traceroute_display_mtu_info').hide();
                $('#div_traceroute_display_as_number').hide();
                $('#div_resolve_hostname').show();
                $('#div_command_no_options').hide();
                $('#submit').removeAttr('disabled');
                break;
            case "traceroute":
                $('#div_interface').show();
                $('#div_address_family').show();
                initializeTracerouteProtocolSelection($('select[name=traceroute_protocol]').val().trim());
                $('#div_dns_lookup_protocol').hide();
                $('#div_dns_lookup_mode').hide();
                $('#div_dns_lookup_encryption_mode').hide();
                $('#div_dns_lookup_query_uri').hide();
                $('#div_dns_lookup_record_type').hide();
                $('#div_dns_lookup_edns_client_subnet').hide();
                $('#div_dns_lookup_server').hide();
                $('#div_ping_hop_limit').hide();
                $('#div_traceroute_protocol').show();
                $('#div_traceroute_first_hop_limit').show();
                $('#div_traceroute_max_hop_limit').show();
                // $('#div_traceroute_display_icmp_extensions').show();
                $('#div_traceroute_display_mtu_info').show();
                $('#div_traceroute_display_as_number').show();
                $('#div_resolve_hostname').show();
                $('#div_command_no_options').hide();
                $('#submit').removeAttr('disabled');
                break;
            default:
                $('#div_interface').hide();
                $('#div_address_family').hide();
                $('#div_destination_port').hide();
                $('#div_dns_lookup_protocol').hide();
                $('#div_dns_lookup_mode').hide();
                $('#div_dns_lookup_encryption_mode').hide();
                $('#div_dns_lookup_query_uri').hide();
                $('#div_dns_lookup_record_type').hide();
                $('#div_dns_lookup_edns_client_subnet').hide();
                $('#div_dns_lookup_server').hide();
                $('#div_ping_hop_limit').hide();
                $('#div_traceroute_protocol').hide();
                $('#div_traceroute_first_hop_limit').hide();
                $('#div_traceroute_max_hop_limit').hide();
                $('#div_traceroute_display_icmp_extensions').hide();
                $('#div_traceroute_display_mtu_info').hide();
                $('#div_traceroute_display_as_number').hide();
                $('#div_resolve_hostname').hide();
                $('#div_command_no_options').show();
                $('#submit').attr('disabled', 'true');
                break;
        }
    }

    function initializeDNSLookupEncryptionModeSelection(mode) {
        switch (mode) {
            case "doh":
                $('#div_dns_lookup_protocol').hide();
                $('#div_dns_lookup_query_uri').show();
                break;
            case "dot":
                $('#div_dns_lookup_protocol').hide();
                $('#div_dns_lookup_query_uri').hide();
                break;
            default:
                $('#div_dns_lookup_protocol').show();
                $('#div_dns_lookup_query_uri').hide();
                break;
        }
    }

    function initializeTracerouteProtocolSelection(protocol) {
        switch (protocol) {
            case "icmp":
                $('#div_destination_port').hide();
                $('#div_traceroute_display_icmp_extensions').show();
                break;
            default:
                $('#div_destination_port').show();
                $('#div_traceroute_display_icmp_extensions').hide();
                break;
        }
    }

    function copyToClipboard(textElementId, iconElementId) {
        if (textElementId.length > 0 && iconElementId.length > 0) {
            var copiedText = document.getElementById(textElementId).innerText;

            if (!navigator.clipboard) {
                // Use legacy "document.execCommand('copy')".
                copyToClipboardLegacy(copiedText);

                // Change the color of clipboard icon.
                $('#' + iconElementId).removeClass("text-primary");
                $('#' + iconElementId).addClass("text-success");
                // Change the content of clipboard icon.
                $('#' + iconElementId).removeClass("bi-clipboard");
                $('#' + iconElementId).addClass("bi-check2");

                setTimeout(() => {
                    // Change the content of clipboard icon.
                    $('#' + iconElementId).removeClass("bi-check2");
                    $('#' + iconElementId).addClass("bi-clipboard");
                    // Change the color of clipboard icon.
                    $('#' + iconElementId).removeClass("text-success");
                    $('#' + iconElementId).addClass("text-primary");
                }, 3000);
            } else {
                navigator.clipboard.writeText(copiedText).then(function() {
                    // Status: Successful.
                    // Change the color of clipboard icon.
                    $('#' + iconElementId).removeClass("text-primary");
                    $('#' + iconElementId).addClass("text-success");
                    // Change the content of clipboard icon.
                    $('#' + iconElementId).removeClass("bi-clipboard");
                    $('#' + iconElementId).addClass("bi-check2");

                    setTimeout(() => {
                        // Change the content of clipboard icon.
                        $('#' + iconElementId).removeClass("bi-check2");
                        $('#' + iconElementId).addClass("bi-clipboard");
                        // Change the color of clipboard icon.
                        $('#' + iconElementId).removeClass("text-success");
                        $('#' + iconElementId).addClass("text-primary");
                    }, 3000);
                }).catch(function() {
                    // Status: Failed.
                    // Change the color of clipboard icon.
                    $('#' + iconElementId).removeClass("text-primary");
                    $('#' + iconElementId).addClass("text-danger");
                    // Change the content of clipboard icon.
                    $('#' + iconElementId).removeClass("bi-clipboard");
                    $('#' + iconElementId).addClass("bi-x");

                    setTimeout(() => {
                        // Change the content of clipboard icon.
                        $('#' + iconElementId).removeClass("bi-x");
                        $('#' + iconElementId).addClass("bi-clipboard");
                        // Change the color of clipboard icon.
                        $('#' + iconElementId).removeClass("text-danger");
                        $('#' + iconElementId).addClass("text-primary");
                    }, 3000);
                });
            }
        }
    }

    async function copyToClipboardLegacy(text) {
        const textAreaObject = document.createElement('textarea');
        textAreaObject.value = text;
        document.body.appendChild(textAreaObject);
        textAreaObject.select();
        document.execCommand('copy');
        document.body.removeChild(textAreaObject);
    }

    function setActiveLanguageMenuItem(language) {
        document.querySelectorAll('a[data-bs-language-value]').forEach(element => {
            element.classList.remove('active');
        });
        document.querySelectorAll('span[data-bs-language-value]').forEach(element => {
            element.style.display = 'none';
        });
    
        switch (language) {
            case 'en-us':
            case 'zh-cn':
                document.querySelector('a[data-bs-language-value=' + language + ']').classList.add('active');
                document.querySelector('span[data-bs-language-value=' + language + ']').style.display = 'block';
                break;
    
            default:
                document.querySelector('a[data-bs-language-value=auto]').classList.add('active');
                document.querySelector('span[data-bs-language-value=auto]').style.display = 'block';
                break;
        }
    }

    function setActiveColorModeMenuItem(colorMode) {
        document.querySelectorAll('button[data-bs-theme-value]').forEach(element => {
            element.classList.remove('active');
        });
        document.querySelectorAll('span[data-bs-theme-value]').forEach(element => {
            element.style.display = 'none';
        });
    
        switch (colorMode) {
            case 'dark':
            case 'light':
                document.querySelector('button[data-bs-theme-value=' + colorMode + ']').classList.add('active');
                document.querySelector('span[data-bs-theme-value=' + colorMode + ']').style.display = 'block';
                break;
    
            default:
                document.querySelector('button[data-bs-theme-value=auto]').classList.add('active');
                document.querySelector('span[data-bs-theme-value=auto]').style.display = 'block';
                break;
        }
    }

    // Initialize tooltips.
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    // Synchronize the active menu item of language option.
    const urlSearchParams = new URLSearchParams(window.location.search);
    setActiveLanguageMenuItem(urlSearchParams.get('lang'));

    // Synchronize the active menu item of color mode option.
    setActiveColorModeMenuItem(localStorage.getItem('colorMode'));
});
