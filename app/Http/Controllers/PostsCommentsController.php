<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class PostsCommentsController extends Controller
{
    public function create(Request $request, $postId)
    {
        // Validar la data enviada
        $this->validate($request, [
            'comment' => 'required'
        ]);

        // Persistir el comentario
        $comment = new Comment;
        $comment->text = $request->get('comment');
        $comment->post_id = $postId;
        $comment->user_id = \Auth::user()->id;
        $comment->save();

        // Redireccionar a la publicación
        return redirect()->route('post_path', ['post' => $postId]);
    }
}
