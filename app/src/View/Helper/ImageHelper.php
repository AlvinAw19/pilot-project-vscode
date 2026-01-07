<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;

/**
 * Image Helper
 *
 * Handles image display logic including default images
 *
 * @property \Cake\View\Helper\HtmlHelper $Html
 */
class ImageHelper extends Helper
{
    /**
     * Helpers used by this helper
     *
     * @var array<string>
     */
    protected array $helpers = ['Html'];
    /**
     * Default product image URL
     */
    private const DEFAULT_PRODUCT_IMAGE =
        'https://img.freepik.com/premium-vector/file-folder-mascot-character-design-vector' .
        '_166742-4413.jpg?semt=ais_hybrid&w=740&q=80';

    /**
     * Generate product image HTML with default fallback
     *
     * @param string|null $imageUrl The product image URL
     * @param string $alt Alt text for the image
     * @param array<string, mixed> $options Additional HTML options
     * @return string
     */
    public function productImageHtml(?string $imageUrl, string $alt = '', array $options = []): string
    {
        $url = $imageUrl ?: self::DEFAULT_PRODUCT_IMAGE;
        $options['alt'] = $alt;

        return $this->Html->image($url, $options);
    }
}