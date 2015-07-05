<?php

ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);
error_reporting(E_ALL);

require_once('libs/Psr/MessageInterface.php');
require_once('libs/Psr/ResponseInterface.php');
require_once('libs/Psr/RequestInterface.php');
require_once('libs/Psr/UriInterface.php');
require_once('libs/Psr/StreamInterface.php');

require_once('libs/Phly/RequestTrait.php');
require_once('libs/Phly/MessageTrait.php');
require_once('libs/Phly/Request.php');
require_once('libs/Phly/Uri.php');
require_once('libs/Phly/Stream.php');
require_once('libs/Phly/HeaderSecurity.php');
require_once('libs/Phly/Response.php');

require_once('libs/Ivory/Asset/AbstractUninstantiableAsset.php');
require_once('libs/Ivory/Parser/HeadersParser.php');

require_once('libs/Ivory/Message/MessageTrait.php');
require_once('libs/Ivory/Message/MessageInterface.php');
require_once('libs/Ivory/Message/ResponseInterface.php');
require_once('libs/Ivory/Message/Response.php');
require_once('libs/Ivory/Normalizer/BodyNormalizer.php');
require_once('libs/Ivory/Extractor/ProtocolVersionExtractor.php');
require_once('libs/Ivory/Extractor/StatusLineExtractor.php');
require_once('libs/Ivory/Extractor/StatusCodeExtractor.php');
require_once('libs/Ivory/Normalizer/HeadersNormalizer.php');

require_once('libs/Ivory/Message/RequestInterface.php');
require_once('libs/Ivory/Message/InternalRequestInterface.php');


require_once('libs/Ivory/Message/Request.php');
require_once('libs/Ivory/Message/InternalRequest.php');
require_once('libs/Ivory/Message/MessageFactoryInterface.php');
require_once('libs/Ivory/Message/MessageFactory.php');
require_once('libs/Ivory/ConfigurationInterface.php');
require_once('libs/Ivory/Configuration.php');
require_once('libs/Ivory/HttpAdapterTrait.php');
require_once('libs/Ivory/PsrHttpAdapterInterface.php');
require_once('libs/Ivory/HttpAdapterInterface.php');
require_once('libs/Ivory/AbstractHttpAdapter.php');
require_once('libs/Ivory/AbstractCurlHttpAdapter.php');
require_once('libs/Ivory/PsrHttpAdapterInterface.php');
require_once('libs/Ivory/HttpAdapterInterface.php');
require_once('libs/Ivory/AbstractHttpAdapter.php');
require_once('libs/Ivory/CurlHttpAdapter.php');

require_once('libs/igorw/get_in.php');
require_once('libs/Geocoder/Exception/Exception.php');
require_once('libs/Geocoder/Exception/NoResult.php');
require_once('libs/Geocoder/Model/AddressCollection.php');
require_once('libs/Geocoder/Model/Country.php');
require_once('libs/Geocoder/Model/AdminLevelCollection.php');
require_once('libs/Geocoder/Model/Bounds.php');
require_once('libs/Geocoder/Model/Coordinates.php');
require_once('libs/Geocoder/Model/Address.php');
require_once('libs/Geocoder/Model/AdminLevel.php');
require_once('libs/Geocoder/Model/AddressFactory.php');
require_once('libs/Geocoder/Provider/LocaleTrait.php');
require_once('libs/Geocoder/Geocoder.php');
require_once('libs/Geocoder/Provider/Provider.php');
require_once('libs/Geocoder/Provider/LocaleAwareProvider.php');
require_once('libs/Geocoder/Provider/AbstractProvider.php');
require_once('libs/Geocoder/Provider/AbstractHttpProvider.php');
require_once('libs/Geocoder/Provider/GoogleMaps.php');

try {
    $curl = new \Ivory\HttpAdapter\CurlHttpAdapter();
    $geocoder = new \Geocoder\Provider\GoogleMaps($curl);


    $result = $geocoder->reverse($_GET['lat'], $_GET['long'])->first();
    $result = $result->toArray();

    echo implode(' ', array($result['streetNumber'], $result['streetName'])) . ', ' . $result['adminLevels'][2]['name'] . ', ' . $result['adminLevels'][1]['name'] . ', ' . $result['country'];
} catch (Exception $e) {
    var_dump($e);
    echo '';
}