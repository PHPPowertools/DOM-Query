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
 * This class offers convenience methods for parsing and serializing HTML5.
 * It is roughly designed to mirror the \DOMDocument class that is
 * provided with most versions of PHP.
 *
 * EXPERIMENTAL. This may change or be completely replaced.
 */
class HTML5 {

    /**
     * Global options for the parser and serializer.
     *
     * @var array
     */
    protected $options = array(
        // If the serializer should encode all entities.
        'encode_entities' => false
    );
    protected $errors = array();

    public function __construct(array $options = array()) {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Get the default options.
     *
     * @return array The default options.
     */
    public function getOptions() {
        return $this->options;
    }

    /**
     * Load and parse an HTML file.
     *
     * This will apply the HTML5 parser, which is tolerant of many
     * varieties of HTML, including XHTML 1, HTML 4, and well-formed HTML
     * 3. Note that in these cases, not all of the old data will be
     * preserved. For example, XHTML's XML declaration will be removed.
     *
     * The rules governing parsing are set out in the HTML 5 spec.
     *
     * @param string $file
     *            The path to the file to parse. If this is a resource, it is
     *            assumed to be an open stream whose pointer is set to the first
     *            byte of input.
     * @return \DOMDocument A DOM document. These object type is defined by the libxml
     *         library, and should have been included with your version of PHP.
     */
    public function load($file) {
        // Handle the case where file is a resource.
        if (is_resource($file)) {
            // FIXME: We need a StreamInputStream class.
            return $this->loadHTML(stream_get_contents($file));
        }

        $input = new HTML5_Inputstream_File($file);

        return $this->parse($input);
    }

    /**
     * Parse a HTML Document from a string.
     *
     * Take a string of HTML 5 (or earlier) and parse it into a
     * DOMDocument.
     *
     * @param string $string
     *            A html5 document as a string.
     * @return \DOMDocument A DOM document. DOM is part of libxml, which is included with
     *         almost all distribtions of PHP.
     */
    public function loadHTML($string) {
        $input = new HTML5_Inputstream_String($string);

        return $this->parse($input);
    }

    /**
     * Convenience function to load an HTML file.
     *
     * This is here to provide backwards compatibility with the
     * PHP DOM implementation. It simply calls load().
     *
     * @param string $file
     *            The path to the file to parse. If this is a resource, it is
     *            assumed to be an open stream whose pointer is set to the first
     *            byte of input.
     *
     * @return \DOMDocument A DOM document. These object type is defined by the libxml
     *         library, and should have been included with your version of PHP.
     */
    public function loadHTMLFile($file) {
        return $this->load($file);
    }

    /**
     * Parse a HTML fragment from a string.
     *
     * @param string $string
     *            The html5 fragment as a string.
     *
     * @return \DOMDocumentFragment A DOM fragment. The DOM is part of libxml, which is included with
     *         almost all distributions of PHP.
     */
    public function loadHTMLFragment($string) {
        $input = new HTML5_Inputstream_String($string);

        return $this->parseFragment($input);
    }

    /**
     * Return all errors encountered into parsing phase
     *
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Return true it some errors were encountered into parsing phase
     *
     * @return bool
     */
    public function hasErrors() {
        return count($this->errors) > 0;
    }

    /**
     * Parse an input stream.
     *
     * Lower-level loading function. This requires an input stream instead
     * of a string, file, or resource.
     */
    public function parse(HTML5_Inputstream_Interface $input) {
        $this->errors = array();
        $events = new HTML5_Parser_DOMTreeBuilder(false, $this->options);
        $scanner = new HTML5_Parser_Scanner($input);
        $parser = new HTML5_Parser_Tokenizer($scanner, $events);

        $parser->parse();
        $this->errors = $events->getErrors();

        return $events->document();
    }

    /**
     * Parse an input stream where the stream is a fragment.
     *
     * Lower-level loading function. This requires an input stream instead
     * of a string, file, or resource.
     */
    public function parseFragment(HTML5_Inputstream_Interface $input) {
        $events = new HTML5_Parser_DOMTreeBuilder(true, $this->options);
        $scanner = new HTML5_Parser_Scanner($input);
        $parser = new HTML5_Parser_Tokenizer($scanner, $events);

        $parser->parse();
        $this->errors = $events->getErrors();

        return $events->fragment();
    }

    /**
     * Save a DOM into a given file as HTML5.
     *
     * @param mixed $dom
     *            The DOM to be serialized.
     * @param string $file
     *            The filename to be written.
     * @param array $options
     *            Configuration options when serializing the DOM. These include:
     *            - encode_entities: Text written to the output is escaped by default and not all
     *            entities are encoded. If this is set to true all entities will be encoded.
     *            Defaults to false.
     */
    public function save($dom, $file, $options = array()) {
        $close = true;
        if (is_resource($file)) {
            $stream = $file;
            $close = false;
        } else {
            $stream = fopen($file, 'w');
        }
        $options = array_merge($this->getOptions(), $options);
        $rules = new HTML5_Serializer_OutputRules($stream, $options);
        $trav = new HTML5_Serializer_Traverser($dom, $stream, $rules, $options);

        $trav->walk();

        if ($close) {
            fclose($stream);
        }
    }

    /**
     * Convert a DOM into an HTML5 string.
     *
     * @param mixed $dom
     *            The DOM to be serialized.
     * @param array $options
     *            Configuration options when serializing the DOM. These include:
     *            - encode_entities: Text written to the output is escaped by default and not all
     *            entities are encoded. If this is set to true all entities will be encoded.
     *            Defaults to false.
     *
     * @return string A HTML5 documented generated from the DOM.
     */
    public function saveHTML($dom, $options = array()) {
        $stream = fopen('php://temp', 'w');
        $this->save($dom, $stream, array_merge($this->getOptions(), $options));

        return stream_get_contents($stream, - 1, 0);
    }

}
