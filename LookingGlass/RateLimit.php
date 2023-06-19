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
namespace Telephone\LookingGlass;

/*
 * Implement rate limiting of network commands.
 */
class RateLimit {
    /**
     * Check rate limit against SQLite database.
     *
     * @param  integer $limit
     *   The maximum number of command that is allowed to be executed in one hour per IP address.
     * @param  string $clientIPAddress
     *   The IP address of the client.
     * @return boolean
     *   True on success.
     */
    public function rateLimit($limit, $clientIPAddress) {
        // Check if rate limit feature is disabled.
        if ($limit === 0) {
            return false;
        }

        /*
         * check for database file.
         * if nonexistent, no rate limit is applied.
         */
        if (!file_exists('LookingGlass/ratelimit.db')) {
            return false;
        }

        // Connect to database.
        try {
            $dbh = new \PDO('sqlite:LookingGlass/ratelimit.db');
        } catch (PDOException $e) {
            exit($e->getMessage());
        }

        // Check for client IP address.
        $q = $dbh->prepare('SELECT * FROM RateLimit WHERE ip = ?');
        $q->execute(array($clientIPAddress));
        $row = $q->fetch(\PDO::FETCH_ASSOC);

        // Save time by declaring time().
        $time = time();

        // If the client IP address does not exist.
        if (!isset($row['ip'])) {
            // Create a new record.
            $q = $dbh->prepare('INSERT INTO RateLimit (ip, hits, accessed) VALUES (?, ?, ?)');
            $q->execute(array($clientIPAddress, 1, $time));
            return true;
        }

        // Typecast SQLite results.
        $accessed = (int) $row['accessed'] + 3600;
        $hits = (int) $row['hits'];

        // Apply rate limit settings.
        if ($accessed > $time) {
            if ($hits >= $limit) {
                $reset = (int) (($accessed - $time) / 60);
                if ($reset <= 1) {
                    exit('Rate limit exceeded. Please try again in 1 minute.');
                } else {
                    exit('Rate limit exceeded. Please try again in ' . $reset . ' minutes.');
                }
            }
            // Update hits.
            $q = $dbh->prepare('UPDATE RateLimit SET hits = ? WHERE ip = ?');
            $q->execute(array(($hits + 1), $clientIPAddress));
        } else {
            // Reset hits & accessed time.
            $q = $dbh->prepare('UPDATE RateLimit SET hits = ?, accessed = ? WHERE ip = ?');
            $q->execute(array(1, time(), $clientIPAddress));
        }

        $dbh = null;
        return true;
    }
}
