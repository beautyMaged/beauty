<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\CPU\ImageManager;
use Intervention\Image\Image;

class ImageManagerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAddLogoReturnsImageInstance()
    {
        $imageManager = new ImageManager();
        $image = UploadedFile::fake()->image('image.jpg');

        $result = $imageManager->addLogo($image);

        $this->assertInstanceOf(Image::class, $result);
    }
}
