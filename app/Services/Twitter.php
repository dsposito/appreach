<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Models\Contact;
use DOMDocument;
use DOMXPath;
use stdClass;

/**
 * Handles Twitter search interactions.
 */
class Twitter extends Service
{
    /**
     * API Auth Keys
     */
    const API_KEY = 'YOUR_API_KEY_HERE';
    const API_SECRET = 'YOUR_API_SECRET_HERE';

    /**
     * The SDK connection.
     *
     * @var TwitterOAuth
     */
    public $connection;

    /**
     * Initializes the class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->connection = new TwitterOAuth(self::API_KEY, self::API_SECRET);
    }

    /**
     * Authenticates with API via OAuth2 token.
     *
     * @return void
     */
    protected function authenticate()
    {
        $this->connection->oauth2('oauth2/token', array('grant_type'=> 'client_credentials'));
    }

    /**
     * Performs a search based on search parameters.
     *
     * @param array $params https://dev.twitter.com/rest/reference/get/search/tweets
     *
     * @return stdClass
     */
    protected function search(array $params)
    {
        return $this->connection->get('search/tweets', $params);
    }

    /**
     * Parses search results to built a list of contacts.
     *
     * @param stdClass $results The search results to parse.
     *
     * @return array
     */
    protected function parseResults(stdClass $results)
    {
        $contacts = array();
        foreach ($results->statuses as $result) {
            $username = $result->user->screen_name;

            if (!$website = $this->getProfileWebsite($username)) {
                continue;
            }

            if (!$email = $this->getProfileEmail($website)) {
                continue;
            }

            $contacts[] = new Contact(array(
                'name' => $result->user->name,
                'email' => $email,
                'twitter' => $username,
                'website' => $website,
            ));
        }

        return $contacts;
    }

    /**
     * Attempts to get the website for a given username.
     *
     * @param string $username Twitter username.
     *
     * @return string
     */
    protected function getProfileWebsite($username)
    {
        $dom = new DOMDocument();
        if (@$dom->loadHTMLFile('https://twitter.com/' . $username)) {
            $xpath = new DOMXPath($dom);
            $elements = $xpath->query("//span[contains(@class, 'ProfileHeaderCard-urlText')]/a");
            if (!$elements->length) {
                return null;
            }

            return $elements->item(0)->getAttribute('title');
        }
    }

    /**
     * Attempts to get the mailto email address for a given url.
     *
     * @param string $url Website URL.
     *
     * @return string
     */
    protected function getProfileEmail($url)
    {
        $dom = new DOMDocument();
        if (@$dom->loadHTMLFile($url)) {
            $xpath = new DOMXPath($dom);
            $elements = $xpath->query("//a[starts-with(@href, 'mailto:')]");
            if (!$elements->length) {
                return null;
            }

            return trim(str_replace('mailto:', '', $elements->item(0)->getAttribute('href')));
        }
    }

    /**
     * Finds contacts that match the provided search params.
     *
     * @param array $params Search params.
     *
     * @return array
     */
    public static function findContacts(array $params)
    {
        $instance = new self();
        $instance->authenticate();
        $results = $instance->search($params);

        return $instance->parseResults($results);
    }
}
