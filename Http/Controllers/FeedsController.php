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
        $file = $this->getFileContents($request->url);
        
        if($file == NULL) 
        {
            return redirect('home')->withErrors('The URL you entered is invalid, please try again!');
        }
        
        $xml = $this->parseXml($file);
        
        if($xml == NULL) 
        {
            return redirect('home')->withErrors('Unable to parse RSS feed from URL, please try again!');
        }
        
        switch($xml->rss['version']) 
        {
            default:
                //No version information so probably v1.0 (can test here), but for now just return a message.
                return redirect('home')->withErrors('RSS feed is not version 2.0, please try again!');
                
                break;
                
            case "2.0":
                if($xml->channel->title == NULL || $xml->channel->description == NULL) 
                {
                    return redirect('home')->withErrors('RSS feed is missing title and/or description, please try again!');
                }

                $feeds = Feeds::where('user_id', auth()->user()->id)->where('url', $request->url)->first();

                if($feeds === NULL) 
                {
                    $feeds = new Feeds;
                    $feeds->user_id = auth()->user()->id;
                    $feeds->url = $request->url;
                }

                $feeds->title = strip_tags(trim($xml->channel->title));
                $feeds->image = strip_tags(trim($xml->channel->image->url)) ?? 'no image';
                $feeds->description = strip_tags(trim($xml->channel->description));
                $feeds->save();
                
                break;
                
            case "3.0":
                //Some future RSS version...
                return redirect('home')->withErrors('RSS feed is not version 2.0, please try again!');
                
                break;
        }
        
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
        $xml = $this->parseXml($this->getFileContents($feed->url));
        
        switch($xml->rss['version']) 
        {
            default:
                //Setup 1.0 template view.
                break;
                
            case "2.0":
                return view('Feeds', ['feed' => $feed, 'xml' => $xml]);
                
                break;
                
            case "3.0":
                //Setup 3.0 template view.
                
                break;
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
    
    private function getFileContents(String $url, $supressErrors = true) 
    {
        if($supressErrors)
        {
             return @file_get_contents($url);
        }
        
        return file_get_contents($url);
    }
    
    private function parseXml(String $file) 
    {
        return simplexml_load_string($file);
    }
}
