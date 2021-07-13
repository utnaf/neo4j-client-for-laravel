<?php

namespace App\Service;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class BeerImageService
{
    /**
     * @var string[]
     */
    private array $images;

    /**
     * @var int[]
     */
    private array $usedIndexes = [];

    /**
     * @var string
     */
    private string $imagePath;

    /**
     * BeerImageService constructor.
     * @param string $imagePath
     */
    public function __construct(string $imagePath)
    {
        $this->imagePath = $imagePath;
    }


    public function get(): string
    {
        $images = $this->getImages();

        do {
            $index = rand(0, count($images) - 1);
        } while(in_array($index, $this->usedIndexes));

        array_push($this->usedIndexes, $index);

        return $images[$index];
    }

    /**
     * @param int $amount
     * @return string[]
     */
    private function getImages(): array
    {
        if (!isset($this->images)) {
            $this->images = Arr::shuffle(array_map(function($image) {
                return Storage::url($image);
            }, Storage::files($this->imagePath)));
        }

        return $this->images;
    }
}
