# LookingGlass

## Overview

This project is based on [Nick's LookingGlass](https://github.com/telephone/LookingGlass).

LookingGlass, a user-friendly PHP based graphical tool that allows everyone to view the information of the target server and execute network testing commands with options via a web interface.

Current version: 1.0.0 (1).

It's recommended that everyone updates their existing installations.

## What's working

* The following commands with their options: dig, host, mtr, nslookup, ping, traceroute (except TCP traceroute).
* Basic server info, client info and network info.
* IP address based rate limit feature.
* Multi-language support.
* Multiple color modes support.

## What's not working

* The following commands with their options:

    mtr (tracing a longer network path via command mtr will encounter the gateway time-out issue).

    traceroute (TCP traceroute only. TCP traceroute requires root privileges to execute).

* Multi-theme support (incompatible with some themes from [Bootswatch](https://bootswatch.com)).

## Implemented commands

* dig
* host
* mtr
* nslookup
* ping
* traceroute

__Supported options are varied from other options and the kind of the relevant command.__

## Requirements

* PHP >= 8.1.
* PHP PDO with SQLite driver (required for rate-limit feature).
* SSH/Terminal access.

## Installation

1. Install all required dependencies. All of them in Linux Ubuntu 22.04 LTS are `php-bcmath`, `php-fpm`, `php-sqlite3`, `sqlite3`, `traceroute`.
2. Clone this project to the intended folder within your web directory.
3. Navigate to the `LookingGlass` subdirectory in terminal
4. Edit the editable environment variables in `configure.sh` so as to make it adjust to your networking environments. All editable environment variables can be found below.
5. Run `sh configure.sh` to configure your environment.
6. The `configure.sh` will take care of the rest.

_Missing variables? Simply run the `configure.sh` script again._

## Apache

An .htaccess is included which protects the rate-limit database, disables indexes, and disables gzip on test files.
Ensure `AllowOverride` is on for .htaccess to take effect.

Output buffering __should__ work by default.

For an HTTPS setup, please visit:
- [Mozilla SSL Configuration Generator](https://ssl-config.mozilla.org)

## Nginx

To enable output buffering, and disable gzip on test files please refer to the provided configuration:

[HTTP setup](LookingGlass/lookingglass-http.nginx.conf)

The provided config is setup for LookingGlass to be on a subdomain/domain root.

For an HTTPS setup, please visit:
- [Mozilla SSL Configuration Generator](https://ssl-config.mozilla.org)

## Editable environment variables

[configure.sh](LookingGlass/configure.sh)

```sh
# The maximum number of command that is allowed to be executed in one hour per IP address.
# Default: "0" (Disabled).
CONFIG_RATE_LIMIT="0"
# The location of your server.
# Default: (Not defined).
CONFIG_SERVER_LOCATION="Local Virtualization Node #1"
# The site name of your server.
# Default: (Not defined).
CONFIG_SITE_NAME="LookingGlass Demo #1"
# The site URL of your server.
# Default: (Not defined).
CONFIG_SITE_URL="https://example.com"
# The test file(s) in your server.
# If you want to generate more than one test files, you need to use " " to split them.
# Default: (Not defined).
CONFIG_TEST_FILES="100MB 1GB 5GB"
# An IPv4 address for testing.
# Default: (Not defined).
CONFIG_TEST_IPv4_ADDRESS="192.168.0.1"
# An IPv6 address for testing.
# Default: (Not defined).
CONFIG_TEST_IPv6_ADDRESS="2001:db8::192:168:0:1"
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
# The name of HTTP header that will be used to get the port of client.
# Available values: "HTTP_X_FORWARDED_PORT", "REMOTE_PORT".
# Default: "REMOTE_PORT".
CONFIG_HTTP_HEADER_NAME_GET_CLIENT_PORT="REMOTE_PORT"
# The owner user of this website (for file permissions).
# Default: "www-data".
CONFIG_WEBSITE_OWNER_USER="www-data"
# The owner group of this website (for file permissions).
# Default: "www-data".
CONFIG_WEBSITE_OWNER_GROUP="www-data"
```

## License

Code is licensed under MIT Public License.
