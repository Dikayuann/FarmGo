<?php

namespace App\Services;

use Jenssegers\Agent\Agent;

class DeviceDetector
{
    protected $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    /**
     * Detect device information from user agent.
     */
    public function detect()
    {
        return [
            'device_type' => $this->getDeviceType(),
            'device_name' => $this->getDeviceName(),
            'browser' => $this->agent->browser(),
            'platform' => $this->getPlatform(),
        ];
    }

    /**
     * Get device type (mobile, tablet, desktop).
     */
    protected function getDeviceType()
    {
        if ($this->agent->isMobile()) {
            return 'mobile';
        } elseif ($this->agent->isTablet()) {
            return 'tablet';
        } else {
            return 'desktop';
        }
    }

    /**
     * Get device name.
     */
    protected function getDeviceName()
    {
        if ($this->agent->isAndroidOS()) {
            return 'Android';
        } elseif ($this->agent->isIOS()) {
            return 'iOS';
        } elseif ($this->agent->is('Windows')) {
            return 'Windows';
        } elseif ($this->agent->is('OS X')) {
            return 'macOS';
        } elseif ($this->agent->is('Linux')) {
            return 'Linux';
        }

        return 'Unknown';
    }

    /**
     * Get platform with version.
     */
    protected function getPlatform()
    {
        $platform = $this->agent->platform();
        $version = $this->agent->version($platform);

        if ($version) {
            return $platform . ' ' . $version;
        }

        return $platform ?: 'Unknown';
    }
}
