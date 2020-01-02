<?php

namespace SwedbankJson;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use SwedbankJson\Exception\ApiException;

/**
 * Class AppData
 * @package SwedbankJson
 */
class AppData
{
    /** @var array Bank type with appID and user-agent */
    private $appData;

    /** @var string Full oath to cache file */
    private $cacheFilePath;

    /** @var int Cache timeout in minutes */
    private $cacheTimeout;

    /** @var string URI to JSON app data file to download */
    private $cacheDownloadUri;

    /** @var string Selected bank app */
    private $appId;

    /**
     * AppData constructor.
     *
     * @param string $bankAppId      Bank app to be used for authentication.
     * @param string $cacheFilePath  Path to cache file. Empty string to disable cache.
     * @param int    $cacheTimeout   Number of minutes to keep the cache from last update. Set zero (0) to never expire cache.
     * @param string $remoteDownload URI for remote download of AppData Json file. Empty string to disabled remote download.
     */
    public function __construct(
        $bankAppId,
        $cacheFilePath,
        $cacheTimeout = 1440,
        $remoteDownload = 'https://raw.githubusercontent.com/walle89/SwedbankJson/files/appdata.json'
    )
    {
        $this->appId            = $bankAppId;
        $this->cacheTimeout     = $cacheTimeout;
        $this->cacheFilePath    = $cacheFilePath;
        $this->cacheDownloadUri = $remoteDownload;
    }

    /**
     * @return string AppID string
     * @throws Exception
     */
    public function getAppID()
    {
        $bankAppData = $this->getBankAppData();
        return $bankAppData->appID;
    }

    /**
     * @return string User-Agent string
     * @throws Exception
     */
    public function getUserAgent()
    {
        $bankAppData = $this->getBankAppData();
        return $bankAppData->useragent;
    }

    /**
     * @param string $downloadUri
     *
     * @return object|bool
     * @throws Exception
     */
    public function remoteFetch($downloadUri = '')
    {
        // Get from web and save if cachePath is provided
        $client = new Client([
            'headers' => [
                'User-Agent'      => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_1) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/13.0.3 Safari/605.1.15',
                'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                'Accept-Language' => 'en-us',
                'Connection'      => 'keep-alive',
                'Accept-Encoding' => 'gzip, deflate',
            ],
        ]);

        $request  = new Request('GET', ($downloadUri) ?: $this->cacheDownloadUri);
        $response = $client->send($request);

        if(!in_array($response->getStatusCode(),[200,301,302,304]))
        {
            throw new ApiException("Can't fetch remote AppData. Try again later. (Status code {$response->getStatusCode()})", 101);;
            return false;
        }

        $data = $response->getBody();

        if (!empty($this->cacheFilePath))
        {
            $appData = json_decode($data);
            if (json_last_error() !== JSON_ERROR_NONE OR !isset($appData->apps))
            {
                throw new Exception('Malformed AppData JSON downloaded.');
            }

            if (
                !is_writable(dirname($this->cacheFilePath))
                OR (file_exists($this->cacheFilePath) AND !is_writable($this->cacheFilePath))
            )
            {
                throw new Exception('Cannot write to cache file to file system.');
            }

            file_put_contents($this->cacheFilePath, $data);
        }

        return $data;
    }

    /**
     * @return object
     * @throws Exception
     */
    private function getBankAppData()
    {
        $appData = $this->getAppData();

        if (!isset($appData->apps->{$this->appId}))
        {
            throw new Exception('Bank type does not exists, use one of the following: '.implode(', ', array_keys((array)$appData)), 2);
        }

        return $appData->apps->{$this->appId};
    }

    /**
     * @return object
     * @throws Exception
     */
    private function getAppData()
    {
        if (is_null($this->appData))
        {
            $this->appData = $this->fetch();
        }
        return $this->appData;
    }

    /**
     * @return object
     * @throws Exception
     */
    private function fetch()
    {
        // Get AppData from local cache
        if (is_readable($this->cacheFilePath))
        {
            $expireTime = filemtime($this->cacheFilePath) + $this->cacheTimeout * 60;

            // Always use cache or use it until expired
            if ($this->cacheTimeout < 1 OR $expireTime > time())
            {
                $appData = json_decode(file_get_contents($this->cacheFilePath));

                if (json_last_error() !== JSON_ERROR_NONE OR !isset($appData->apps))
                {
                    throw new Exception('Malformed AppData JSON from cache.');
                }

                return $appData;
            }
        }
        // Download disabled and cache is unreadable.
        elseif (empty($this->cacheDownloadUri))
        {
            throw new Exception('Cache do not exist.');
        }

        // Download remote (and cache result)
        $appData = json_decode($this->remoteFetch());

        if (!$appData)
        {
            throw new ApiException("Can't fetch remote AppData. Try again later.", 100);
        }

        return $appData;
    }
}