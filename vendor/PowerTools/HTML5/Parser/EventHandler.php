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
 * Standard events for HTML5.
 *
 * This is roughly analogous to a SAX2 or expat-style interface.
 * However, it is tuned specifically for HTML5, according to section 8
 * of the HTML5 specification.
 *
 * An event handler receives parser events. For a concrete
 * implementation, see HTML5_Parser_DOMTreeBuilder.
 *
 * Quirks support in the parser is limited to close-in syntax (malformed
 * tags or attributes). Higher order syntax and semantic issues with a
 * document (e.g. mismatched tags, illegal nesting, etc.) are the
 * responsibility of the event handler implementation.
 *
 * See HTML5 spec section 8.2.4
 */
interface HTML5_Parser_EventHandler {

    const DOCTYPE_NONE = 0;
    const DOCTYPE_PUBLIC = 1;
    const DOCTYPE_SYSTEM = 2;

    /**
     * A doctype declaration.
     *
     * @param string $name
     *            The name of the root element.
     * @param int $idType
     *            One of DOCTYPE_NONE, DOCTYPE_PUBLIC, or DOCTYPE_SYSTEM.
     * @param string $id
     *            The identifier. For DOCTYPE_PUBLIC, this is the public ID. If DOCTYPE_SYSTEM,
     *            then this is a system ID.
     * @param boolean $quirks
     *            Indicates whether the builder should enter quirks mode.
     */
    public function doctype($name, $idType = 0, $id = null, $quirks = false);

    /**
     * A start tag.
     *
     * IMPORTANT: The parser watches the return value of this event. If this returns
     * an integer, the parser will switch TEXTMODE patters according to the int.
     *
     * This is how the Tree Builder can tell the HTML5_Parser_Tokenizer when a certain tag should
     * cause the parser to go into RAW text mode.
     *
     * The HTML5 standard requires that the builder is the one that initiates this
     * step, and this is the only way short of a circular reference that we can
     * do that.
     *
     * Example: if a startTag even for a `script` name is fired, and the startTag()
     * implementation returns HTML5_Parser_Tokenizer::TEXTMODE_RAW, then the tokenizer will
     * switch into RAW text mode and consume data until it reaches a closing
     * `script` tag.
     *
     * The textmode is automatically reset to HTML5_Parser_Tokenizer::TEXTMODE_NORMAL when the
     * closing tag is encounter. **This behavior may change.**
     *
     * @param string $name
     *            The tag name.
     * @param array $attributes
     *            An array with all of the tag's attributes.
     * @param boolean $selfClosing
     *            An indicator of whether or not this tag is self-closing (<foo/>)
     * @return numeric One of the HTML5_Parser_Tokenizer::TEXTMODE_* constants.
     */
    public function startTag($name, $attributes = array(), $selfClosing = false);

    /**
     * An end-tag.
     */
    public function endTag($name);

    /**
     * A comment section (unparsed character data).
     */
    public function comment($cdata);

    /**
     * A unit of parsed character data.
     *
     * Entities in this text are *already decoded*.
     */
    public function text($cdata);

    /**
     * Indicates that the document has been entirely processed.
     */
    public function eof();

    /**
     * Emitted when the parser encounters an error condition.
     */
    public function parseError($msg, $line, $col);

    /**
     * A CDATA section.
     *
     * @param string $data
     *            The unparsed character data.
     */
    public function cdata($data);

    /**
     * This is a holdover from the XML spec.
     *
     * While user agents don't get PIs, server-side does.
     *
     * @param string $name
     *            The name of the processor (e.g. 'php').
     * @param string $data
     *            The unparsed data.
     */
    public function processingInstruction($name, $data = null);
}
