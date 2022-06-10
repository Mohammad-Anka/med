<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsEditRequest;
use App\Http\Requests\NewsRequest;
use App\Http\Traits\ApiTrait;
use App\Http\Traits\ImgTrait;

use App\Models\Newt;
use App\Models\ReadNew;

use Illuminate\Http\Request;

class NewsController extends Controller
{use ApiTrait;
    use ImgTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $news=Newt::where('hide',false)->paginate(pag);
       
      
        foreach ($news as $newt) {
            $newt->subject=html_entity_decode($newt->subject);
            
           //set image with url
          $newt= $this-> editHasImage($newt,'nots');
    
       }
      return  $this->returnJson($news);
     //return response()->json($news);
    }

    public function all()
    {
        $news= Newt::paginate(pagadmin);
        return view('Admin.news',compact('news'));
       // return view(,compact('news'))
         
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('Admin.createnews');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsRequest $request)
    {
        $file_name=null;
        $news=new Newt();
        $news->title=$request->title;
        $news->subject=htmlentities($request->subject);
        if($request->file('photo')!=null){
            $file_name = $this -> saveImage($request -> photo,'images/news');
            $news->image=$file_name;
            $news->isImage=true;
        }
        
        $news->save();
        return  redirect()->back()->with(['success' => 'create successfully']);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {  
        $newt=Newt::find($id);
       
        if($newt){
            $newt->subject=html_entity_decode($newt->subject);
            $user= auth('api')->user();
            $readnew=ReadNew::where('new_id',$id)->where('user_id',$user->id)->first();
            if(!$readnew){
                $readcreate=new ReadNew();
                $readcreate->user_id=$user->id;
                $readcreate->new_id=$newt->id;
                $readcreate->read=true;
                $readcreate->save();

            }
            $newt= $this-> editHasImage($newt,'nots');
            return  $this->returnJson($newt);
        }
        else{
         return   $this->returnJson('',404,'notfound');  
        }
    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $news=Newt::find($id);
        if(!$news){
            return  redirect()->back()->with(['error' =>"not found"]);
        }
        return view('Admin.editnews',compact('news'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NewsEditRequest $request, $id)
    {
        $news=Newt::find($id);
        if(!$news){
            return  redirect()->back()->with(['error' =>"not found"]);
        }

        if($request->file('photo')!=null){
            $file_name = $this -> saveImage($request -> photo,'images/news');
            $news->image=$file_name;
            $news->isImage=true;
        }
        $news->title=$request->title;
        $news->subject=htmlentities($request->subject);
        $news->update();
        return  redirect()->back()->with(['success' =>"successfly update"]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $news=Newt::find($request->id);
        if(!$news){
          
            return response()->json(['status'=>false,'msg'=>'error',$request->id]);
        }
        $news->delete();
        return response()->json(['status'=>true,'msg'=>'successfully deleted','id'=>$request->id]);
    }
}
