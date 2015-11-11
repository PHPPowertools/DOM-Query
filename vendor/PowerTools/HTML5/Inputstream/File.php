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
 * The HTML5_Inputstream_File loads a file to be parsed.
 *
 * So right now we read files into strings and then process the
 * string. We chose to do this largely for the sake of expediency of
 * development, and also because we could optimize toward processing
 * arbitrarily large chunks of the input. But in the future, we'd
 * really like to rewrite this class to efficiently handle lower level
 * stream reads (and thus efficiently handle large documents).
 *
 * @todo A buffered input stream would be useful.
 */
class HTML5_Inputstream_File extends HTML5_Inputstream_String implements HTML5_Inputstream_Interface {

    /**
     * Load a file input stream.
     *
     * @param string $data
     *            The file or url path to load.
     */
    public function __construct($data, $encoding = 'UTF-8', $debug = '') {
        // Get the contents of the file.
        $content = file_get_contents($data);

        parent::__construct($content, $encoding, $debug);
    }

}
