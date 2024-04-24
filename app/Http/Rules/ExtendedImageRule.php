<?php

namespace App\Http\Rules;

use App\Factories\ImageCreatorFactory;
use GuzzleHttp\Psr7\MimeType;
use Illuminate\Validation\Rules\ImageFile;

class ExtendedImageRule extends ImageFile
{
    public function __construct()
    {
        parent::__construct();

        $this->clearImageRule();
        $this->registerMimetypesRule();
    }

    private function registerMimetypesRule(): void
    {
        $validMimetypes = array_values(array_unique(array_map(fn(string $extension) => MimeType::fromExtension($extension),
            array_values(ImageCreatorFactory::VALID_EXTENSIONS)
        )));

        $this->rules('mimetypes:' . join(',', $validMimetypes));
    }

    /**
     * The default laravel image rule does not allow the TIFF format.
     *
     * We have to remove it, but we also want to not reinvent the
     * wheel and stay with base ImageFile class
     */
    private function clearImageRule(): void
    {
        $imageRuleIndex = array_search('image', $this->customRules);

        if ($imageRuleIndex >= 0) {
            unset($this->customRules[$imageRuleIndex]);
            $this->customRules = array_values($this->customRules);
        }
    }
}
