<?php

declare(strict_types=1);

namespace App\Twig;

use App\LocalizationHelper;
use Harentius\BlogBundle\Entity\AbstractPost;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AbstractPostLocalizationExtension extends AbstractExtension
{
    /**
     * @var LocalizationHelper
     */
    private $localizationHelper;
    /**
     * @var PropertyAccessor
     */
    private $propertyAccessor;

    /**
     * AbstractPostLocalizationExtension constructor.
     * @param LocalizationHelper $localizationHelper
     * @param PropertyAccessor $propertyAccessor
     */
    public function __construct(LocalizationHelper $localizationHelper, PropertyAccessor $propertyAccessor)
    {
        $this->localizationHelper = $localizationHelper;
        $this->propertyAccessor = $propertyAccessor;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('get_field', [$this, 'getField']),
        ];
    }

    /**
     * @param AbstractPost $post
     * @param string $field
     * @return string
     */
    public function getField(AbstractPost $post, string $field): string
    {
        if (!$this->localizationHelper->isArticleOldAndTranslatedToEn($post)) {
            return $this->propertyAccessor->getValue($post, $field);
        }

        return $post->getTranslation($field, LocalizationHelper::DEFAULT_LOCALE);
    }
}
