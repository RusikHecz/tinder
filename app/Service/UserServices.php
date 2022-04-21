<?php


namespace App\Service;


use App\Models\SaveImage;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UserServices
{
    public function store($data)
    {
        try {
            DB::beginTransaction();

            if (isset($data['tag_ids'])) {
                $tagIds = $data['tag_ids'];
                unset($data['tag_ids']);
            }

            $user = User::firstOrCreate($data);
            if (isset($tagIds)) {
                $user->tags()->attach($tagIds);
            }

            if (isset($data['image'])) {
                $gallery = $data['image'];

                $saveImage = SaveImage::sv($gallery);

                $user->image = $saveImage;
            }
            $user->save();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            abort(500);
        }
    }

    public function update($data, $id)
    {
        try {
            DB::beginTransaction();

            $user = User::find($id);

            if (isset($data['tag_ids'])) {
                $tagIds = $data['tag_ids'];
                unset($data['tag_ids']);
            }

            if (isset($data['image'])) {
                $gallery = $data['image'];

                $saveImage = SaveImage::sv($gallery);

                $user->image = $saveImage;
            }

            $user->save();

            if (isset($tagIds)) {
                $user->tags()->sync($tagIds);
            }

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            abort(500);
        }

    }
}
