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
 * The build info of the current project.
 */
class BuildInfo {
    // The engineering sample state.
    public const BUILD_STATE_ENGINEERING_SAMPLE = 0;
    // The qualification sample state.
    public const BUILD_STATE_QUALIFICATION_SAMPLE = 1;
    // The general availability state.
    public const BUILD_STATE_GENERAL_AVAILABILITY = 2;
    // The version name of the project that is in engineering sample state.
    public const BUILD_VERSION_NAME_ENGINEERING_SAMPLE = "0000";

    // The version code of the current project.
    private const BUILD_CURRENT_VERSION_CODE = 1;
    // The version name of the current project.
    private const BUILD_CURRENT_VERSION_NAME = "1.0.0";
    // The state of the current project.
    private const BUILD_CURRENT_STATE = self::BUILD_STATE_ENGINEERING_SAMPLE;

    /*
     * Validates whether the current project is in engineering sample state.
     *
     * @return boolean
     *   The result.
     */
    public static function isEngineeringSampleBuild() {
        switch (self::BUILD_CURRENT_STATE) {
            case self::BUILD_STATE_GENERAL_AVAILABILITY:
                return false;
            default:
                return true;
        }
    }

    /*
     * Get the version code of the current project.
     *
     * @return string
     *   The result.
     */
    public static function getVersionCode() {
        return self::BUILD_CURRENT_VERSION_CODE;
    }

    /*
     * Get the version name of the current project.
     *
     * @return string
     *   The result.
     */
    public static function getVersionName() {
        return (self::BUILD_CURRENT_STATE === self::BUILD_STATE_ENGINEERING_SAMPLE) ? self::BUILD_VERSION_NAME_ENGINEERING_SAMPLE
                                                                                    : self::BUILD_CURRENT_VERSION_NAME;
    }

    /*
     * Get the state of the current project.
     *
     * @return string
     *   The result.
     */
    public static function getBuildState() {
        return self::BUILD_CURRENT_STATE;
    }
}
