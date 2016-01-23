<?php

namespace Bowtie\Grawler;

use Bowtie\Grawler\Config\ConfigAccess;
use Bowtie\Grawler\Nodes\Image;
use Bowtie\Grawler\Nodes\Link;
use Bowtie\Grawler\Nodes\Video;
use Symfony\Component\DomCrawler\Crawler;

class Grawler
{
    use ConfigAccess;

    /** @var Crawler */
    private $DOM;

    /**
     * Current page absolute url
     *
     * @var string
     */
    private $uri;


    /**
     * Create a new Grawler Instance
     *
     * @param Crawler $DOM
     * @param array $paths
     */
    public function __construct(Crawler $DOM, $uri, array $paths = [])
    {
        $this->DOM = $DOM;
        $this->uri = $uri;
    }

    /**
     * @param null|string $path
     * @return string
     */
    public function title($path = 'title')
    {
        $title = $this->DOM->filter($path)->first()->text();

        return $title;
    }

    /**
     * @param $path
     * @return string
     */
    public function body($path)
    {
        $content = $this->DOM->filter($path)->each(function ($node) {
            return trim($node->text());
        });

        return implode("\n", $content);
    }

    public function images($path)
    {
        $attributes = ['data-image','data-url','data-src','data-highres','src','href'];

        $links = $this->generateLinks($path, $attributes);

        $images = array_map(function ($link) {
            return (new Image($link->getUri()))->loadConfig($this->config());
        }, $links);

        return $images;
    }

    public function videos($path)
    {
        $attributes = ['src', 'href', 'content'];

        $links = $this->generateLinks($path, $attributes);

        $videos = array_map(function ($link) {
            return (new Video($link->getUri()))->loadConfig($this->config());
        }, $links);

        return $videos;
    }


    public function links($areas)
    {
    }

    /**
     * @param $path
     * @param $attributes
     * @return array
     */
    private function generateLinks($path, $attributes)
    {
        $links = $this->DOM->filter($path)->each(function ($node) use ($attributes) {
            foreach ($attributes as $attribute) {

                if ($url = $node->attr($attribute)) {

                    $document = new \DOMDocument('1.0');
                    $linkNode = $document->createElement('a');
                    $linkNode->setAttribute('href', $url);
                    $linkNode->setAttribute('alt', $node->attr('alt'));

                    return new Link($linkNode, $this->uri);
                }
            }

            return null;
        });

        return array_unique(array_filter($links));
    }

}