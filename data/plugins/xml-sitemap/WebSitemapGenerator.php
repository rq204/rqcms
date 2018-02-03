<?php

class WebSitemapGenerator
{

    const SCHEMA = 'http://www.sitemaps.org/schemas/sitemap/0.9';
    const DEFAULT_DIRECTORY = './';
    const DEFAULT_FILENAME = 'sitemap';

    /**
     * @var XMLWriter
     */
    protected $xmlWriter;

    /**
     * @var string
     */
    protected $baseUrl;

    public function closeXml()
    {
        $this->xmlWriter->endElement();
        $this->xmlWriter->endDocument();
    }


    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        $this->xmlWriter = new XMLWriter();
        $this->xmlWriter->openMemory();
        $this->xmlWriter->startDocument('1.0', 'UTF-8');
        $this->xmlWriter->setIndent(true);
        $this->xmlWriter->startElement('urlset');
        $this->xmlWriter->writeAttribute('xmlns', self::SCHEMA);
    }

    protected function getFormattedLastModifiedDate(\DateTime $dateTime)
    {
        return $dateTime->format(\DateTime::W3C);
    }

    public function addItem(WebSitemapItem $item)
    {
        $this->xmlWriter->startElement('url');
        $this->xmlWriter->writeElement('loc', $this->baseUrl.$item->getLocation());

        if ($item->getPriority()) {
            $this->xmlWriter->writeElement('priority', $item->getPriority());
        }

        if ($item->getChangeFrequency()) {
            $this->xmlWriter->writeElement('changefreq', $item->getChangeFrequency());
        }

        if ($item->getLastModified()) {
            $this->xmlWriter->writeElement(
                'lastmod',
                $this->getFormattedLastModifiedDate($item->getLastModified())
            );
        }

        $this->xmlWriter->endElement();

        return $this;
    }

    public function outxml()
    {
        echo $this->xmlWriter->outputMemory(); 
    }


}

class WebSitemapItem
{
    const DEFAULT_PRIORITY = 0.5;

    const CHANGE_FREQ_ALWAYS = 'always';
    const CHANGE_FREQ_HOURLY = 'hourly';
    const CHANGE_FREQ_DAILY = 'daily';
    const CHANGE_FREQ_WEEKLY = 'weekly';
    const CHANGE_FREQ_MONTHLY = 'monthly';
    const CHANGE_FREQ_YEARLY = 'yearly';
    const CHANGE_FREQ_NEVER = 'never';

    /**
     * @var string
     */
    private $location;

    /**
     * @var float
     */
    private $priority;

    /**
     * @var string
     */
    private $changeFrequency;

    /**
     * @var \DateTime
     */
    private $lastModified;

    /**
     * WebSitemapItem constructor.
     * @param string $location
     */
    public function __construct($location)
    {
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return float
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param float $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return string
     */
    public function getChangeFrequency()
    {
        return $this->changeFrequency;
    }

    /**
     * @param string $changeFrequency
     */
    public function setChangeFrequency($changeFrequency)
    {
        $this->changeFrequency = $changeFrequency;
    }

    /**
     * @return \DateTime
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }

    /**
     * @param \DateTime $lastModified
     */
    public function setLastModified($lastModified)
    {
        if(is_string($lastModified)) $lastModified=date_create($lastModified);
        $this->lastModified = $lastModified;
    }
    
}