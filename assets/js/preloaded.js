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

/*
 * This file will be loaded before the website is loaded.
 */
function setColorMode(colorMode) {
    switch (colorMode) {
        case 'dark':
        case 'light':
            document.documentElement.setAttribute('data-bs-theme', colorMode);
            break;

        default:
            if (window.matchMedia && window.matchMedia('(prefers-color-scheme: light)').matches) {
                document.documentElement.setAttribute('data-bs-theme', 'light');
            } else {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
            }
            break;
    }
}

function updateColorModeSettings(colorMode) {
    switch (colorMode) {
        case 'dark':
        case 'light':
            localStorage.setItem('colorMode', colorMode);
            break;

        default:
            localStorage.setItem('colorMode', 'auto');
            break;
    }
}

// Set the color mode if it is available.
setColorMode(localStorage.getItem('colorMode'));
