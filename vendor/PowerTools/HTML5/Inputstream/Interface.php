<?php
/* !
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *
 *               PACKAGE : PHP POWERTOOLS
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *
 *               COMPONENT : HTML5 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 *               DESCRIPTION :
 *
 *               A library for easy HTML5 parsing
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 *               REQUIREMENTS :
 *
 *               PHP version 5.4+
 *               PSR-0 compatibility
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *
 *               CREDITS : 
 *
 *               This library started out as a fork of Masterminds/html5-php
 *
 *               Contributors of that Masterminds/html5-php :
 *               ---------------------------------------------
 *               Matt Butcher [technosophos]
 *               Matt Farina  [mattfarina]
 *               Asmir Mustafic [goetas]
 *               Edward Z. Yang [ezyang]
 *               Geoffrey Sneddon [gsnedders]
 *               Kukhar Vasily [ngreduce]
 *               Rune Christensen [MrElectronic]
 *               Mišo Belica [miso-belica]
 *               Asmir Mustafic [goetas]
 *               KITAITI Makoto [KitaitiMakoto]
 *               Jacob Floyd [cognifloyd]
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * 
 *               LICENSE :
 *
 * LICENSE: Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *
 *  @category  HTML5 parsing
 *  @package   HTML5
 *  @author    John Slegers
 *  @copyright MMXIV John Slegers
 *  @license   http://www.opensource.org/licenses/mit-license.html MIT License
 *  @link      https://github.com/jslegers
 * 
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

namespace PowerTools;

/**
 * Interface for stream readers.
 *
 * The parser only reads from streams. Various input sources can write
 * an adapter to this HTML5_Parser_InputStream.
 *
 * Currently provided HTML5_Parser_InputStream implementations include
 * HTML5_Inputstream_File and HTML5_Inputstream_String.
 */
interface HTML5_Inputstream_Interface extends \Iterator {

    /**
     * Returns the current line that is being consumed.
     *
     * TODO: Move this to the scanner.
     */
    public function currentLine();

    /**
     * Returns the current column of the current line that the tokenizer is at.
     *
     * Newlines are column 0. The first char after a newline is column 1.
     *
     * @TODO Move this to the scanner.
     *
     * @return int The column number.
     */
    public function columnOffset();

    /**
     * Get all characters until EOF.
     *
     * This consumes characters until the EOF.
     */
    public function remainingChars();

    /**
     * Read to a particular match (or until $max bytes are consumed).
     *
     * This operates on byte sequences, not characters.
     *
     * Matches as far as possible until we reach a certain set of bytes
     * and returns the matched substring.
     *
     * @see strcspn
     * @param string $bytes
     *            Bytes to match.
     * @param int $max
     *            Maximum number of bytes to scan.
     * @return mixed Index or false if no match is found. You should use strong
     *         equality when checking the result, since index could be 0.
     */
    public function charsUntil($bytes, $max = null);

    /**
     * Returns the string so long as $bytes matches.
     *
     * Matches as far as possible with a certain set of bytes
     * and returns the matched substring.
     *
     * @see strspn
     * @param string $bytes
     *            A mask of bytes to match. If ANY byte in this mask matches the
     *            current char, the pointer advances and the char is part of the
     *            substring.
     * @param int $max
     *            The max number of chars to read.
     */
    public function charsWhile($bytes, $max = null);

    /**
     * Unconsume one character.
     *
     * @param int $howMany
     *            The number of characters to move the pointer back.
     */
    public function unconsume($howMany = 1);

    /**
     * Retrieve the next character without advancing the pointer.
     */
    public function peek();
}
