<?php

namespace App\Http\Traits;

use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File as FileSystem;

trait UploadImage
{
	// $model is the object provided by id with the model data related to the polymorphic table File
	private function uploadImage($model, $request)
	{
		if (!isset($request->image)) return;
		$random = Str::random(20);
		$path = $this->getRoute($model);
		$this->deleteFile($model);

		// Save image to server storage
		$imageName = "{$random}.{$request->image->clientExtension()}";
		$request->image->move(storage_path("app/public/{$path}"), $imageName);

		// Save image to database
		$image = new Image(['route' => "/storage/{$path}/{$imageName}"]);
		$model->image()->save($image);
	}

	// Delete the image if it exists and if it is not the default
	private function deleteImage($model)
	{
		$image = Image::where('imageable_id', $model->id)
			->where('imageable_type', get_class($model))
			->first();
		if (!$image) return;
		$imageIsDefault = $image->route == "/storage/{$this->getRoute($model)}/default.png";
		$isSetImage = FileSystem::exists(public_path($image->route));
		if ($isSetImage && !$imageIsDefault) {
			// Delete the image from the server
			FileSystem::delete(public_path($image->route));
		}
		// Delete the record from the server
		$image->delete();
	}

	// Get the image path related to the model of the provided object
	private function getRoute($model)
	{
		$routes = [
			Product::class => 'images/products',
			User::class => 'images/users'
		];
		return $routes[get_class($model)] ?? 'images';
	}
}
