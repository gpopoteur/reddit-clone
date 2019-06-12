@extends('layouts.app')

@section('content')
     <div class="row">
        <div class="col-md-12">
            <h2>{{ $post->title }}</h2>
            <p>{{ $post->description }}</p>
            <p>Posted {{ $post->created_at->diffForHumans() }}</p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            @if(Auth::guest())
                Please log in to your account to comment.
            @else
                <form action="{{ route('create_comment_path', ['post' => $post->id]) }}" method="POST">
                    {!! csrf_field() !!}
                    <!-- Comment Input -->
                    <div class="form-group">
                        <label for="comment">Comment:</label>
                        <textarea rows="5" name="comment" class="form-control"/></textarea>
                    </div>

                    <div class="form-group">
                        <button type="submit" class='btn btn-primary'>Post comment</button>
                    </div>
                </form>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @foreach($post->comments as $comment)
                <div class="row">
                    <div class="col-md-12">
                        <div class="well">
                            {{ $comment->text }} - {{ $comment->user->name }} - {{ $comment->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection