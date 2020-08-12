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
                        <div class="float-left">
                            <h2>{{ __($feed->title) }}</h2>
                        </div>
                        <div class="float-right">
                            <a href="{{ url()->previous() }}">Go back</a>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @foreach($xml->channel->item as $item)
                        <div class="row p-3">
                            <div class="col-md-2">
                                <h5>{{$item->pubDate}}</h5>
                            </div>
                            <div class="col-md-4">
                                <h3><a href="{{$item->link}}" target="_blank">{{$item->title}}</a></h3>
                            </div>
                            <div class="col-md-6">
                                {{strip_tags($item->description)}}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
