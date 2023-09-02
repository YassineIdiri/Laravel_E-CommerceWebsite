<?php

namespace App\Http\Controllers\Article;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Image;
use App\Models\TemporaryImage;
use Illuminate\Support\Facades\Validator;
use App\Rules\categoryArticle;

class StoreController extends Controller
{    
    /**
     * clearTemporaryImages
     *
     * @return void
     */
    public static function clearTemporaryImages()
    {
        $user = session('user');
        $temporaryImages = TemporaryImage::where('user', $user)
                          //->whereDate('date', '<=', Carbon::now()->subHours(48))
                          ->get();

        foreach ($temporaryImages as $temporaryImage) {
            Storage::disk('public')->delete('ImageArticles/tmp/'.$temporaryImage->folder.$temporaryImage->file);
            $temporaryImage->delete();
        }
    }
    
    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $this->clearTemporaryImages();
        return view('article.sell');
    }
    
    /**
     * isImageFile
     *
     * @param  $file
     * @return bool
     */
    public function isImageFile($file)
    {
        $imageInfo = @getimagesize($file);
        return ($imageInfo !== false);
    }
    
    /**
     * upload
     *
     * @param  $request
     * @return json
     */
    public function upload(Request $request)
    {
       if( ($request->hasFile('image') && $this->isImageFile($request->image)) || 
           ($request->hasFile('mainimage') && $this->isImageFile($request->mainimage)) ){

            if($request->hasFile('image')){
                $image = $request->file('image');
            }
            else{
                $image = $request->file('mainimage');
            }
         
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
            //Storage::disk('public')->put('ImageArticles/tmp/' . $folder . $fileName,$image);
            //image->move(public_path('/assets/Images/ImageArticles/tmp/'), $folder . $fileName);


                           
            if($request->hasFile('image')){
                TemporaryImage::create([
                    'folder' => $folder,
                    'file' => $fileName,
                    'date' => Carbon::now(),
                    'user' => session('user'),
                    ]);
            }
            else{
                TemporaryImage::create([
                    'folder' => $folder,
                    'file' => $fileName,
                    'date' => Carbon::now(),
                    'user' => session('user'),
                    'main' => true,
                ]);
            }

            return $folder;
        }
        else
        {
            return response()->json(['error' => 'L\'image nest pas une img'], 422);
        } 
    }
    
    /**
     * save
     *
     * @param  $request
     * @return redirect
     */
    public function save(Request $request) 
    { 
        $validator = Validator::make($request->all(), [
            'name' => ['required','max:50'],
            'price' => ['required','regex:/^\d{1,6}(\.\d{1,2})?$/'],
            'category' => ['required',new categoryArticle],
            'description' => ['required'],
        ]);
        
        $temporaryImages = TemporaryImage::where('user', session('user'))->get();

        if($validator->fails())
        {
            foreach($temporaryImages as $temporaryImage)
            {
                Storage::disk('public')->delete('ImageArticles/tmp/'.$temporaryImage->folder . $temporaryImage->file);
                $temporaryImage->delete();
            }
            return back()->withErrors($validator)->withinput();
        }

        $article = new Article;
        $article->name = $request->input('name');
        $article->price = $request->input('price');
        $article->category = $request->input('category');
        $article->description = htmlspecialchars($request->input('description'));
        $article->dateOfSale = Carbon::now();
        $article->user_id = session('user');
        
        $mainImageIsPresent = false;

        foreach($temporaryImages as $temporaryImage)
        {
            if($temporaryImage->main == true)
            {
                $mainImageIsPresent = true;
            }
        }

        if ($mainImageIsPresent === false) {
            return back()->withErrors(['mainimage' => 'Le champ Image principale est obligatoire'])->withInput();
        }
        $article->save();
        foreach($temporaryImages as $temporaryImage)
        {
            Storage::copy('ImageArticles/tmp/'.$temporaryImage->folder . $temporaryImage->file, 
                           'ImageArticles/'.$temporaryImage->folder . $temporaryImage->file);

            //$image->move(public_path('/assets/Images/ImageArticles/tmp/'), $temporaryImage->folder . $temporaryImage->file);


            if($temporaryImage->main == true)
            {
                Image::create([
                'path' => 'ImageArticles/' . $temporaryImage->folder . $temporaryImage->file,
                'main' => true,
                'article_id' => $article->id,
                ]);
            }
            else
            {
                Image::create([
                'path' => 'ImageArticles/' . $temporaryImage->folder . $temporaryImage->file,
                'article_id' => $article->id,
                ]);
            }
            
            Storage::disk('public')->delete('ImageArticles/tmp/'.$temporaryImage->folder . $temporaryImage->file);
            $temporaryImage->delete();
        }
        return redirect()->route('article', ['id' => $article->id])->with('articleSell', 'Your article was');
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
}