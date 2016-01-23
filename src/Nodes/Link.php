<?php
namespace Bowtie\Grawler\Nodes;

use DOMElement;
use Symfony\Component\DomCrawler\Link as DOMLink;

class Link extends DOMLink
{

    public $rawUri;

    /**
     * Link constructor.
     *
     * @param DOMElement $node
     * @param string $currentUri
     * @param string $rawUri
     */
    public function __construct(DOMElement $node, $currentUri)
    {
        parent::__construct($node, $currentUri);
    }


}