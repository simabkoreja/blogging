<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Requests\BlogRequest;
use App\Models\Comment;
use App\Models\User;

class BlogController extends Controller
{

    public function index()
    {
        $index['blogs'] = Blog::with('comments')->latest()->paginate(20);
        $index['title'] = 'Blogs';
        return view('blogs.index', $index);        
    }

    public function create()
    {
        $index['title'] = 'Create Blog';
        return view('blogs.create', $index);
    }

    public function store(BlogRequest $request)
    {
        $blog = Blog::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        if($file = $request->file('image')){
            //Move Uploaded File
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $blog->image = $filePath;
            $blog->save();
        }

        return redirect(route('blogs.index'))->with('status', 'Blog Created!');
    }

    public function show(Blog $blog)
    {
        //
    }

    public function edit(Blog $blog)
    {
        $index['title'] = 'Edit Blog';
        $index['blog'] = $blog;
        return view('blogs.edit', $index);
    }

    public function update(BlogRequest $request, Blog $blog)
    {
        $blog->update([
            'title' => $request->title,
            'content' => $request->content,
            // 'user_id' => auth()->id(),
        ]);

        if($file = $request->file('image')){
            //Move Uploaded File
            $fileName = time().'_'.$file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');
            $blog->image = $filePath;
            $blog->save();
        }

        return redirect(route('blogs.index'))->with('status', 'Blog Updated!');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect(route('blogs.index'))->with('status', 'Blog Deleted!');
    }

    public function comment_store(Request $request) 
    {
        $request->validate([
            'comment' => 'required',
            'blog_id' => 'required|integer'
        ]);

        $comment = Comment::create([
            'comment' => $request->comment,
            'blog_id' => $request->blog_id,
            'user_id' => auth()->id()
        ]);

        return redirect(route('blogs.index'))->with('status', 'Comment Added!');
    }

    public function comment_update(Request $request) 
    {
        $request->validateWithBag('commentUpdation',[
            'comment' => 'required',
            'comment_id' => 'required',
        ]);
        
        $comment = Comment::findOrFail($request->comment_id);
        if($comment->user_id != auth()->id() && auth()->user()->role != User::ROLE_ADMIN){
            abort('401');
        }
        $comment->update([
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('status', 'Comment Updated!');
    }

    public function comment_delete(Request $request){

        $request->validate([
            'comment_id' => 'required',
        ]);

        $comment = Comment::findOrFail($request->comment_id);
        if($comment->user_id != auth()->id() && auth()->user()->role != User::ROLE_ADMIN){
            abort('401');
        }
        if(!is_null($comment)){
            $comment->delete();
        }
        return redirect(route('blogs.index'))->with('status', 'Comment Deleted!');
    }
}
