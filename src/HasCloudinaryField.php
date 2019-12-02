<?php

namespace BBSLab\CloudinaryField;

use Illuminate\Support\Carbon;

trait HasCloudinaryField
{
    /**
     * Return Cloudinary field meta.
     *
     * @return array
     */
    protected function cloudinaryMeta()
    {
        $signature = $this->cloudinarySignature();

        return [
            'string' => $signature['string'],
            'api_key' => config('nova-cloudinary.api_key'),
            'username' => $signature['username'],
            'signature' => $signature['signature'],
            'timestamp' => $signature['timestamp'],
            'cloud_name' => config('nova-cloudinary.cloud_name'),
        ];
    }

    /**
     * Compute Cloudinary signature from environment.
     *
     * @return array
     */
    protected function cloudinarySignature()
    {
        $cloudName = config('nova-cloudinary.cloud_name');
        $username = config('nova-cloudinary.username');
        $apiSecret = config('nova-cloudinary.api_secret');
        $timestamp = Carbon::now()->timestamp;
        $string = 'cloud_name='.$cloudName.'&timestamp='.$timestamp.'&username='.$username.$apiSecret;

        return [
            'string' => $string,
            'username' => $username,
            'timestamp' => $timestamp,
            'signature' => hash('sha256',$string),
        ];
    }
}
