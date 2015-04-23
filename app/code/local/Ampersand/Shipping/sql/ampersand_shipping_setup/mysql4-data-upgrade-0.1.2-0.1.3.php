<?php
$this->startSetup();

$websitesShippingTrackUrl = array(
    'base' => 'http://www.dpd.co.uk/apps/tracking/?reference=%s#results',
    'us' => 'https://www.fedex.com/fedextrack/html/index.html?tracknumbers=%s&r=g',
    'eu' => 'https://tracking.dpd.de/cgi-bin/delistrack?pknr=%s&typ=32&lang=en',
    'roe' => 'https://tracking.dpd.de/cgi-bin/delistrack?pknr=%s&typ=32&lang=en',
    'ru' => 'https://www.fedex.com/fedextrack/html/index.html?tracknumbers=%s&r=g',
    'au' => 'https://www.fedex.com/fedextrack/html/index.html?tracknumbers=%s&r=g',
    'jp' => 'https://www.fedex.com/fedextrack/html/index.html?tracknumbers=%s&r=g',
    'hk' => 'https://www.fedex.com/fedextrack/html/index.html?tracknumbers=%s&r=g',
    'cn' => 'https://www.fedex.com/fedextrack/html/index.html?tracknumbers=%s&r=g',
    'kr' => 'https://www.fedex.com/fedextrack/html/index.html?tracknumbers=%s&r=g',
    'row' => 'https://www.fedex.com/fedextrack/html/index.html?tracknumbers=%s&r=g',
);

foreach ($websitesShippingTrackUrl as $code => $trackingUrl) {
    Mage::getModel('ampersand_system/config')
        ->setWebsite($code)
        ->setNode(
            array(
                'carriers/standard/tracking_url' => $trackingUrl
            )
        )
        ->save();
}


$this->endSetup();