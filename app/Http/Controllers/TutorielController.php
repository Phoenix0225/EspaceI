<?php

namespace App\Http\Controllers;

use App\Models\Parametre;
use App\Models\Tutoriel;
use Exception;
use Google_Client;
use Google_Service_YouTube;
use Illuminate\Http\Request;

class TutorielController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $parametres = Parametre::find(1);
        $apikey = $parametres->api_key;
        $channelId = $parametres->channel_id;

        $video = file_get_contents('https://youtube.googleapis.com/youtube/v3/search?part=snippet&channelId=' . $channelId . '&maxResults=9&order=viewCount&key=' . $apikey);
        $tutoriels = json_decode($video, true);

        $tutos = [];
        $ctr = 0;
        foreach ($tutoriels["items"] as $item) {
            $videoid = $item["id"]["videoId"]; // change this// change this

            $json = file_get_contents('https://www.googleapis.com/youtube/v3/videos?id=' . $videoid . '&key=' . $apikey . '&part=snippet');
            $data = json_decode($json, true);
            $tutos[$ctr] = $data;
            $ctr++;
        }

        return view('tutoriels.index', compact('tutos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
