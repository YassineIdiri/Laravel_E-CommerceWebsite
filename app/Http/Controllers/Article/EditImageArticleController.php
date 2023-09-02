<?php

namespace App\Http\Controllers\Article;
use Carbon\Carbon;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\TemporaryImage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class EditImageArticleController extends Controller
{    
    /**
     * editImage
     *
     * @param  $req
     * @return view
     */
    public function editImage(Request $req)
    {
            $image = Image::findOrFail($req->id);
            
            if ($req->has('reset') && $image->main == false) 
            {
                if($image->article->user->id == session('user') )
                {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                }
            }
            else if($req->has('main'))
            {
                $idArticle = $image->article->id;
                $mainImage = Image::where('main', true)->where('article_id', $idArticle)->first();

                if ($mainImage) {
                    $mainImage->main = false;
                    $mainImage->save();
                }

                $image->main = true; 
                $image->save();
            }
            
            if($req->has('reset') && $image->main == true)
            {
                return back()->with('defineMain', 'defineMain');
            }

            return back()->with('edit', 'defineMain');
    }
    
    /**
     * isImageFile
     *
     * @param  $file
     * @return void
     */
    public function isImageFile($file)
    {
        $imageInfo = @getimagesize($file);
        return ($imageInfo !== false);
    }
    
    /**
     * uploadEdit
     *
     * @param  $request
     * @return json
     */
    public function uploadEdit(Request $request)
    {   
        if($request->hasFile('image') && $this->isImageFile($request->file('image')))
        {
            $image = $request->file('image');

            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $extension = strtolower($image->getClientOriginalExtension());
                                
            if (!in_array($extension, $allowedExtensions)) {
                return response()->json(["error" => "Le format de l'image doit être JPG, JPEG ou PNG"], 422);
            }

            $maxSizeInBytes = 2 * 1024 * 1024; // 2 Mo en octets
            if ($image->getSize() > $maxSizeInBytes) {
                return response()->json(["error" => "L'image ne doit pas dépasser 2 Mo"], 422);
            }

            $fileName = $image->getClientOriginalName();
            $folder = uniqid('image-',true);
            $image->storeAs('ImageArticles/tmp/' . $folder . $fileName);

            TemporaryImage::create([
                'folder' => $folder,
                'file' => $fileName,
                'date' => Carbon::now(),
                'user' => session('user'),
            ]);
            return $folder;
        }
        else{
                return response()->json(['error' => 'L\'image nest pas une img'], 422);
        }
       
    }
    
    /**
     * deleteImage
     *
     * @return json
     */
    public function deleteImage()
    {
        $content = request()->getContent();
        $extractedValue = '';

        if (preg_match('/image-\w+\.\w+/', $content, $matches)) {
            $extractedValue = $matches[0];
        }

        $temporaryImage = TemporaryImage::where('folder', $extractedValue)->first();
        
        if ($temporaryImage) {
            Storage::disk('public')->delete('ImageArticles/tmp/'.$temporaryImage->folder . $temporaryImage->file);
            $temporaryImage->delete();
        }
        return response()->noContent();
    }
    
    /**
     * saveImage
     *
     * @param  $req
     * @return redirect
     */
    public function saveImage(Request $req) 
    { 
            $temporaryImages = TemporaryImage::where('user', session('user'))->get();

            foreach($temporaryImages as $temporaryImage)
            {
            Storage::copy('ImageArticles/tmp/'.$temporaryImage->folder . $temporaryImage->file, 'ImageArticles/'.$temporaryImage->folder . $temporaryImage->file);

                Image::create([
                    'path' => 'ImageArticles/' . $temporaryImage->folder . $temporaryImage->file,
                    'article_id' => $req->id,
                ]);
                
                Storage::disk('public')->delete('ImageArticles/tmp/'.$temporaryImage->folder . $temporaryImage->file);
                $temporaryImage->delete();
            }

            return redirect(url() -> previous());
    }

}
