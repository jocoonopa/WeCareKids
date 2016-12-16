<?php

namespace App\Utility\Services\AmtAlsRpt;

use App\Model\AmtCategory;
use App\Model\CategorySuggestion;

trait SuggestionTrait
{
    public function getSuggestion($categoryName, $defaultLevel, $isBetter)
    {
        $category = AmtCategory::where('content', $categoryName)->first();

        $desc = CategorySuggestion::where('is_better', $isBetter)
            ->where('category_id', $category->id)
            ->where('min_level', '<=', $defaultLevel)
            ->where('max_level', '>=', $defaultLevel)
            ->first()
            ->desc
        ;

        return str_replace($categoryName, '<strong>' . $categoryName . '</strong>', $desc);
    }
}