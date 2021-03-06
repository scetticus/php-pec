<?php

namespace PhpPec\Tests;

use PhpPec\Parser\PostacertParser;
use PHPUnit\Framework\TestCase;

/**
 * PhpPec Test.
 */
class ParserTest extends TestCase
{
    /**
     * @param $filename
     * @return PostacertParser
     */
    private function getParser($filename)
    {
        $file = dirname(__FILE__).DIRECTORY_SEPARATOR.'emails'.DIRECTORY_SEPARATOR.$filename;
        $parser = new PostacertParser(file_get_contents($file));
        return $parser;
    }

    public function testParsingTestoHtml()
    {
        $parser = $this->getParser('postacert_standard_html.eml');
        $fragments = $parser->getFragments();

        $this->assertEquals('<p>Testo libero contenente HTML</p>', $fragments[0]['contenuto']);
        $this->assertEquals('html', $fragments[0]['tipo']);
    }

    public function testParsingTestoPlain()
    {
        $parser = $this->getParser('postacert_standard_plain.eml');
        $fragments = $parser->getFragments();

        $this->assertEquals('Testo libero plain', $fragments[0]['contenuto']);
        $this->assertEquals('plain', $fragments[0]['tipo']);
    }

    public function testParsingTestoEtHtml()
    {
        $parser = $this->getParser('postacert_standard_plain_and_html.eml');
        $fragments = $parser->getFragments();

        $this->assertEquals('<p>Testo libero contenente HTML</p>', $fragments[0]['contenuto']);
        $this->assertEquals('html', $fragments[0]['tipo']);

        $this->assertEquals('Contento del Testo libero', $fragments[1]['contenuto']);
        $this->assertEquals('plain', $fragments[1]['tipo']);
    }

    public function testConSoloNewlinePlain()
    {
        $parser = $this->getParser('postacert_standard_newline.eml');
        $fragments = $parser->getFragments();

        $this->assertEquals("Contenti di provare\n\nTesto libero plain\n\nContenente diverse\nlinee con\n\n\nDoppio a capo\n\nProva", $fragments[0]['contenuto']);
        $this->assertEquals('plain', $fragments[0]['tipo']);
    }
}
