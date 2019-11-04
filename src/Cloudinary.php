<?php

namespace BBSLab\CloudinaryField;

use Carbon\Carbon;
use Laravel\Nova\Fields\Field;

class Cloudinary extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-cloudinary-field';

    public function __construct(string $name, ?string $attribute = null, ?mixed $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $signature = $this->signature();

        $this->withMeta([
            'api_key' => config('nova-cloudinary.api_key'),
            'cloud_name' => config('nova-cloudinary.cloud_name'),
            'signature' => $signature['signature'],
            'timestamp' => $signature['timestamp'],
            'username' => $signature['username'],
            'string' => $signature['string'],
        ]);
    }

    protected function signature()
    {
        $cloudName = config('nova-cloudinary.cloud_name');
        $username = config('nova-cloudinary.username');
        $apiSecret = config('nova-cloudinary.api_secret');
        $timestamp = Carbon::now()->timestamp;
        $string = 'cloud_name='.$cloudName.'&timestamp='.$timestamp.'&username='.$username.$apiSecret;
        return [
            'signature' => hash('sha256',$string),
            'timestamp' => $timestamp,
            'username' => $username,
            'string' => $string,
        ];
    }
}
