<?php

namespace App\Http\Controllers;

use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function url_request(
        int $len,
        string $start,
        string $end,
        string $contains,
        string $excludes
    ) {
        $content = [
            'data' => [],
            'errors' => [],
        ];

        $words = Word::getWords($len,$start,$end,$contains,$excludes);

        if (!$words->count())
        {
            $content['data']['words'] = 'No such word found';
            return response($content, 200);
        }
        foreach ($words as $word)
        {
            $content['data']['words'][] = $word->word; 
        }

        return response($content, 200);
        // return view('welcome', [
        //     'words' => $words
        // ]);
    }

    public function form_request(Request $request)
    {
        // $test = $request->all();
        // dd($test['errors']);
        $content = [
            'data' => [],
            'errors' => [],
        ];

        if(!$request->length && is_numeric($request->length))
        {
            $content['errors'] = 'Word length is required';
            return response($content, 400);
        }
        
        $len = floor($request->length);
        $start = $request->starts ?? '_';
        $end = $request->ends ?? '_';
        $includes = $request->includes ?? '_';
        $excludes = $request->excludes ?? '_';
        $excluded_characters = [];
        $included_characters = [];

        $pattern = '/[^a-z\_]/';
        $start = preg_replace($pattern,'', $start);
        $end = preg_replace($pattern,'', $end);

        if(strlen($start) > $len)
        {
            $content['errors'] = 'The start of the word is longer than ' . $len;
            return response($content, 400);
        }

        if(strlen($end) > $len)
        {
            $content['errors'] = 'The end of the word is longer than ' . $len;
            return response($content, 400);
        }

        if($includes != '_')
        {
            preg_match_all('/[a-z]/', $includes, $included_characters);
            $includes = implode('-', $included_characters[0]);
        }
        
        if($excludes != '_')
        {
            preg_match_all('/[a-z]/', $excludes, $excluded_characters);
            $excludes = implode('-', $excluded_characters[0]);
            
            foreach ($excluded_characters[0] as $char)
            {
                if(str_contains($start, $char))
                {
                    $content['errors'] = 'You exclude a character that should be at the start of the word';
                    return response($content, 400);
                }
                if(str_contains($end, $char))
                {
                    $content['errors'] = 'You exclude a character that should be at the end of the word';
                    return response($content, 400);
                }
                if(str_contains($includes, $char))
                {
                    $content['errors'] = 'You exclude a character that should be included in the word';
                    return response($content, 400);
                }
            }
        }

        $words = Word::getWords($len,$start,$end,$includes,$excludes);

        if (!$words->count())
        {
            $content['data']['words'] = 'No such word found';
            return response($content, 200);
        }
        foreach ($words as $word)
        {
            $content['data']['words'][] = $word->word; 
        }

        return response($content, 200);
    }
  
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Word  $word
     * @return \Illuminate\Http\Response
     */
    public function show(string $word)
    {
        $words = Word::where('word', $word)->get();
        if(!$words->count())
        {
           return response(['error' => 'No such word found'], 200);
        }
        return response($words->first(), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Word  $word
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Word $word)
    {
        if (!$request->description) {
           return response(['error' =>'No description'], 400);
        }

        $word->description = $request->description;
        $word->save();

        return response(['data' => 'Description updated successfully'], 201);
    }
}
