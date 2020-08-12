@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    @if($errors->any())
                        <span style="color: red">{{ __($errors->first()) }}</span>
                    @else
                        {{ __('RSS Dashboard') }}
                    @endif
                </div>
                <div class="card-header">
                    <form method="POST" action="{{ route('feeds') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="url" class="col-md-2 col-form-label text-md-right">{{ __('URL') }}</label>

                            <div class="col-md-6">
                                <input id="url" type="url" class="form-control" name="url" required autofocus>
                            </div>
                            <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Add') }}
                                </button>
                            </div>
                        </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(count($feeds) > 0)
                        @foreach($feeds as $feed)
                            <div class="row p-3">
                                <div class="col-md-2">
                                    <a style="color: black;" href="{{ route('feed', ['id' => $feed->id]) }}"><img style="max-width:100px; max-height:100px; height:75%;" src="{{$feed->image}}" /></a>
                                </div>
                                <div class="col-md-4">
                                    <h3><a style="color: black;" href="{{ route('feed', ['id' => $feed->id]) }}">{{$feed->title}}</a></h3>
                                </div>
                                <div class="col-md-6">
                                    <a style="color: black;" href="{{ route('feed', ['id' => $feed->id]) }}">{{strip_tags($feed->description)}}</a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{ __('You are logged in, add some feeds to get started!') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
