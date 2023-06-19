#!/usr/bin/env sh

# Copyright (C) 2023 jellybean13
# 
# Modification based on code covered by the mentioned copyright
# and/or permission notice(s).

# Copyright (C) 2015 Nick Adams <nick@iamtelephone.com>
#
# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is furnished
# to do so, subject to the following conditions:
#
# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.
#
# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
# THE SOFTWARE.

# Required packages:
#    Linux Ubuntu 22.04 LTS:
#        php-bcmath
#        php-fpm
#        php-sqlite3
#        sqlite3
#        traceroute

# Switch working directory.
cd "$(dirname "$0")" || exit 1

# The maximum number of command that is allowed to be executed in one hour per IP address.
# Default: "0" (Disabled).
CONFIG_RATE_LIMIT="0"
# The location of your server.
# Default: (Not defined).
CONFIG_SERVER_LOCATION=""
# The site name of your server.
# Default: (Not defined).
CONFIG_SITE_NAME=""
# The site URL of your server.
# Default: (Not defined).
CONFIG_SITE_URL=""
# The test file(s) in your server.
# If you want to generate more than one test files, you need to use " " to split them.
# Default: (Not defined).
CONFIG_TEST_FILES=""
# An IPv4 address for testing.
# Default: (Not defined).
CONFIG_TEST_IPv4_ADDRESS=""
# An IPv6 address for testing.
# Default: (Not defined).
CONFIG_TEST_IPv6_ADDRESS=""
# The default theme of current website.
# Available values: "bootstrap", "cerulean", "cosmo", "cyborg", "darkly", "flatly",
#                   "journal", "litera", "lumen", "lux", "materia", "minty", "morph",
#                   "pulse", "quartz", "sandstone", "simplex", "sketchy", "slate",
#                   "solar", "spacelab", "superhero", "united", "vapor", "yeti", "zephyr".
# Default: "cerulean".
# Bugs: Incompatible with some themes from Bootswatch.
CONFIG_THEME="cerulean"
# The name of HTTP header that will be used to get the IP address of client.
# Available values: "HTTP_CF_CONNECTING_IP", "HTTP_X_FORWARDED_FOR", "REMOTE_ADDR".
# Default: "REMOTE_ADDR".
CONFIG_HTTP_HEADER_NAME_GET_CLIENT_IP_ADDRESS="REMOTE_ADDR"
# The owner user of this website (for file permissions).
# Default: "www-data".
CONFIG_WEBSITE_OWNER_USER="www-data"
# The owner group of this website (for file permissions).
# Default: "www-data".
CONFIG_WEBSITE_OWNER_GROUP="www-data"

# The file path of setup script. DO NOT modify.
SETUP_SCRIPT_FILE_PATH="configure.sh"
# The file path of php file storing configurations. DO NOT modify.
CONFIG_FILE_PATH="Config.php"
# The file path of db file storing rate limit records. DO NOT modify.
CONFIG_RATE_LIMIT_DATABASE_FILE_PATH="ratelimit.db"

writeConfig() {
    echo "I: Writing configurations..."
    cat > "${CONFIG_FILE_PATH}" <<EOF
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

// An IPv4 address for testing.
\$testIPv4Address = '${CONFIG_TEST_IPv4_ADDRESS}';
// An IPv6 address for testing.
\$testIPv6Address = '${CONFIG_TEST_IPv6_ADDRESS}';
// The maximum number of command that is allowed to be executed in one hour per IP address.
\$rateLimit = (int) '${CONFIG_RATE_LIMIT}';
// The site name of your server.
\$siteName = '${CONFIG_SITE_NAME}';
// The site URL of your server.
\$siteUrl = '${CONFIG_SITE_URL}';
// The location of your server.
\$serverLocation = '${CONFIG_SERVER_LOCATION}';
// The default theme of current website.
\$theme = '${CONFIG_THEME}';
// The name of HTTP header that will be used to get the IP address of client.
\$httpHeaderNameGetClientIPAddress = '${CONFIG_HTTP_HEADER_NAME_GET_CLIENT_IP_ADDRESS}';
// The test file(s) in your server.
\$testFiles = array();
EOF

    echo "${CONFIG_TEST_FILES}" | tr ' ' '\n' | while read -r item; do
        echo "\$testFiles[] = '${item}';" >> "${CONFIG_FILE_PATH}"
    done
    printf "\n" >> "${CONFIG_FILE_PATH}"
    # chown "${CONFIG_WEBSITE_OWNER_USER}":"${CONFIG_WEBSITE_OWNER_GROUP}" "${CONFIG_FILE_PATH}"
}

createDatabase() {
    echo "I: Creating database..."
    if [ ! -f "${CONFIG_RATE_LIMIT_DATABASE_FILE_PATH}" ]; then
        sqlite3 "${CONFIG_RATE_LIMIT_DATABASE_FILE_PATH}" 'CREATE TABLE RateLimit (ip TEXT UNIQUE NOT NULL, hits INTEGER NOT NULL DEFAULT 0, accessed INTEGER NOT NULL);'
        sqlite3 "${CONFIG_RATE_LIMIT_DATABASE_FILE_PATH}" 'CREATE UNIQUE INDEX "RateLimit_ip" ON "RateLimit" ("ip");'
        # chown "${CONFIG_WEBSITE_OWNER_USER}":"${CONFIG_WEBSITE_OWNER_GROUP}" "${CONFIG_RATE_LIMIT_DATABASE_FILE_PATH}"
    fi
}

removeLegacyTestFiles() {
    TEMP_LEGACY_TEST_FILES_FOR_REMOVAL="$(ls ../*.test 2>/dev/null)"
    if [ -n "${TEMP_LEGACY_TEST_FILES_FOR_REMOVAL}" ]; then
        echo "I: Removing legacy test file(s)..."
        echo "${TEMP_LEGACY_TEST_FILES_FOR_REMOVAL}" | tr ' ' '\n' | while read -r item; do
            if [ -f "${item}" ]; then
                rm -f "${item}"
            fi
        done
    fi
}

createNewTestFiles() {
    if [ -n "${CONFIG_TEST_FILES}" ]; then
        echo "I: Creating new test file(s)..."
        echo "${CONFIG_TEST_FILES}" | tr ' ' '\n' | while read -r item; do
            if [ -n "${item}" ] && [ ! -f "../${item}.test" ]; then
                shred --exact --iterations=1 --size="${item}" - > "../${item}.test"
                # chown "${CONFIG_WEBSITE_OWNER_USER}":"${CONFIG_WEBSITE_OWNER_GROUP}" "../${item}.test"
            fi
        done
    fi
}

fixFilePermissions() {
    echo "I: Fixing file permissions..."
    chown -R "${CONFIG_WEBSITE_OWNER_USER}":"${CONFIG_WEBSITE_OWNER_GROUP}" "$(dirname "$(pwd)")"
    find "$(dirname "$(pwd)")" -type d -exec chmod 0755 {} \;
    find "$(dirname "$(pwd)")" -type f -exec chmod 0644 {} \;
    chmod +x "${SETUP_SCRIPT_FILE_PATH}"
}

removeLegacyTestFiles
createNewTestFiles
writeConfig
createDatabase
fixFilePermissions
