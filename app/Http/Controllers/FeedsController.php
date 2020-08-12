<?php

namespace App\Http\Controllers;

use App\Feeds;
use Illuminate\Http\Request;

class FeedsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = @file_get_contents($request->url);
        
        if($file == null) 
        {
            return redirect('home')->withErrors('The URL you entered is invalid, please try again!');
        }
        
        $xml = simplexml_load_string($file);
        
        if($xml == null) 
        {
            return redirect('home')->withErrors('Unable to parse RSS from URL, please try again!');
        }
        
        if($xml->channel->title == NULL || $xml->channel->description == NULL) 
        {
            return redirect('home')->withErrors('RSS feed is missing title and/or description, please try again!');
        }
        
        $feeds = Feeds::where('user_id', auth()->user()->id)->where('url', $request->url)->first();
        
        if($feeds === null) 
        {
            $feeds = new Feeds;
            $feeds->user_id = auth()->user()->id;
            $feeds->url = $request->url;
        }
        
        $feeds->title = strip_tags(trim($xml->channel->title));
        $feeds->image = strip_tags(trim($xml->channel->image->url)) ?? 'no image';
        $feeds->description = strip_tags(trim($xml->channel->description));
        $feeds->save();
        
        return redirect('home');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $feed = Feeds::findOrFail($id);
        $xml = simplexml_load_string(@file_get_contents($feed->url));
        
        return view('Feeds', ['feed' => $feed, 'xml' => $xml]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
