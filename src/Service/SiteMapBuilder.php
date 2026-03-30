<?php

namespace App\Service;

use App\Entity\SiteSettings;
use Symfony\UX\Map\Bridge\Leaflet\LeafletOptions;
use Symfony\UX\Map\Bridge\Leaflet\Option\TileLayer;
use Symfony\UX\Map\InfoWindow;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Marker;
use Symfony\UX\Map\Point;

final class SiteMapBuilder
{
    public function buildContactMap(SiteSettings $siteSettings): ?Map
    {
        if (!$siteSettings->hasCoordinates()) {
            return null;
        }

        $point = new Point($siteSettings->getLatitudeAsFloat(), $siteSettings->getLongitudeAsFloat());
        $leafletOptions = (new LeafletOptions())
            ->tileLayer(new TileLayer(
                url: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                options: [
                    'maxZoom' => 19,
                ],
            ));

        return (new Map())
            ->center($point)
            ->zoom(16)
            ->minZoom(5)
            ->maxZoom(19)
            ->options($leafletOptions)
            ->addMarker(new Marker(
                position: $point,
                title: $siteSettings->getBusinessName(),
                infoWindow: new InfoWindow(
                    headerContent: $siteSettings->getBusinessName(),
                    content: $siteSettings->getShortAddress() ?? $siteSettings->getFullAddress(),
                    opened: false,
                ),
            ));
    }

    public function buildItineraryUrl(SiteSettings $siteSettings): ?string
    {
        if (!$siteSettings->hasCoordinates()) {
            return null;
        }

        return sprintf(
            'https://www.google.com/maps/dir/?api=1&destination=%s,%s',
            $siteSettings->getLatitudeAsFloat(),
            $siteSettings->getLongitudeAsFloat(),
        );
    }
}
