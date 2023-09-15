<?php

namespace Tests\Unit;

use App\Services\FileService;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class FileServiceTest extends TestCase
{
    private $firstFolder = 'first-folder';
    private $secondFolder = 'second-folder';

    private $disk;

    public function setUp(): void
    {
        parent::setUp();

        $this->disk = Storage::disk('test');
    }

    public function makeService()
    {
        return new FileService();
    }

    public function testResultEmpty()
    {
        $this->fakeAndMoveFiles(1, 1);

        $result = $this->makeService()->getIntersectFiles(
            $this->disk,
            $this->firstFolder,
            $this->secondFolder
        );

        $this->assertTrue(sizeof($result) === 0);
        $this->cleanDisk();
    }

    public function testResultWithSame100Files()
    {
        $this->fakeAndMoveFiles(100, 150, 50);

        $result = $this->makeService()->getIntersectFiles(
            $this->disk,
            $this->firstFolder,
            $this->secondFolder
        );

        $this->assertTrue(sizeof($result) === 50);
        $this->cleanDisk();
    }

    private function fakeAndMoveFiles(
        $totalFirstFile,
        $totalSecondFile,
        $sameFileTotal = 0
    ) {
        if ($sameFileTotal) {
            for ($count = 0; $count < $sameFileTotal; $count++) {
                $name = Str::random(6) . '.jpg';

                $file = UploadedFile::fake()->image($name);
                $this->disk->putFileAs(
                    $this->firstFolder,
                    $file,
                    $file->getClientOriginalName()
                );

                $this->disk->putFileAs(
                    $this->secondFolder,
                    $file,
                    $file->getClientOriginalName()
                );
            }
        }

        for ($count = 0; $count < ($totalFirstFile - $sameFileTotal); $count++) {
            $file = UploadedFile::fake()->image(Str::random(20) . '.jpg');

            $this->disk->put($this->firstFolder, $file);
        }

        for ($count = 0; $count < ($totalSecondFile - $sameFileTotal); $count++) {
            $file = UploadedFile::fake()->image(Str::random(20) . '.jpg');

            $this->disk->put($this->secondFolder, $file);
        }
    }

    private function cleanDisk()
    {
        $this->disk->deleteDirectory($this->firstFolder);
        $this->disk->deleteDirectory($this->secondFolder);
    }

}
