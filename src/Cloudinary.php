<?php

namespace BBSLab\CloudinaryField;

use Laravel\Nova\Fields\Field;

class Cloudinary extends Field
{
    use HasCloudinaryField;

    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-cloudinary-field';

    /**
     * Cloudinary constructor.
     *
     * @param  string  $name
     * @param  string|null  $attribute
     * @param  \BBSLab\CloudinaryField\mixed|null  $resolveCallback
     * @return void
     */
    public function __construct(string $name, ?string $attribute = null, ?mixed $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->withMeta($this->cloudinaryMeta());
    }
}
