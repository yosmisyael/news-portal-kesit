<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostImgRequest;
use App\Services\PictureService;
use App\Services\UserService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PictureController extends Controller
{
    private Authenticatable $user;
    public function __construct(private readonly PictureService $pictureService, private readonly UserService $userService)
    {
        $this->middleware(function ($request, $next) {
            $this->user = $this->userService->findCurrentUser();
            return $next($request);
        });
    }

    /**
     * Handle the image upload request for tinymce editor.
     */
    public function __invoke(PostImgRequest $request): JsonResponse
    {
        $image = $request->file('image');

        $path = '/images/users/' . $this->user->id . '/post';
        $name = Str::uuid();
        $fileName = $name . '.' . $image->getClientOriginalExtension();

        $image->storePubliclyAs($path, $fileName, 'public');

        $this->pictureService->save($name, url('/storage' . $path . '/' . $fileName), null);

        $tempImages = $request->session()->get('temp_images', []);
        $tempImages[] = $name;
        $request->session()->put('temp_images', $tempImages);

        return response()
            ->json([
                'location' => url('/storage' . $path . '/' . $fileName),
            ]);
    }
}
