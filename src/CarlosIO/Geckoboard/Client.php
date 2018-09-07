<?php

namespace CarlosIO\Geckoboard;

use GuzzleHttp\Client as Guzzle;
use CarlosIO\Geckoboard\Widgets\Widget;
use GuzzleHttp\RequestOptions;

/**
 * Class Client.
 */
class Client
{
    const URI = 'https://push.geckoboard.com';

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $api;

    /**
     * Construct a new Geckoboard Client.
     */
    public function __construct()
    {
        $config = array('base_uri' => self::URI);

        $this->api = '';
        $this->client = new Guzzle($config);
    }

    /**
     * @param array $config
     *
     * @return Client $this
     */
    public function setGuzzleConfig($config)
    {
        $this->client->setConfig($config);

        return $this;
    }

    /**
     * @param string|bool $key
     *
     * @return mixed
     */
    public function getGuzzleConfig($key = false)
    {
        return $this->client->getConfig($key);
    }

    /**
     * Set Geckoboard API key.
     *
     * @param $apiKey
     *
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->api = $apiKey;

        return $this;
    }

    /**
     * Get Geckoboard API key.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->api;
    }

    /**
     * Send the widget info to Geckboard.
     *
     * @param $widget
     *
     * @return $this
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function push($widget)
    {
        $this->pushWidgets(
            $this->getWidgetsArray($widget)
        );

        return $this;
    }

    /**
     * @param $widget
     *
     * @return array
     */
    private function getWidgetsArray($widget)
    {
        $widgets = $widget;
        if (! is_array($widget)) {
            $widgets = array($widget);
        }

        return $widgets;
    }

    /**
     * @param $widgets
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function pushWidgets($widgets)
    {
        foreach ($widgets as $widget) {
            $this->pushWidget($widget);
        }
    }

    /**
     * @param $widget
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function pushWidget(Widget $widget)
    {
        $this->client->request('POST', '/v1/send/' . $widget->getId(), array(
            RequestOptions::JSON => array(
                'api_key' => $this->getApiKey(),
                'data' => $widget->getData(),
            ),
        ));
    }
}
