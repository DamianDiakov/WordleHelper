<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Word extends Model
{
    use HasFactory;



    public static function getWords(
        int $len,
        string $start,
        string $end,
        string $contains,
        string $excludes
    ) {
        $lenght = str_repeat('_', $len);
        return Word::select("word")
            ->where("word", "like", $lenght)
            ->when($start && $start != '_', function ($query) use ($start) {
                return $query->where("word", "like", "{$start}%");
            })
            ->when($end && $end != '_', function ($query) use ($end) {
                return $query->where("word", "like", "%{$end}");
            })
            ->when($contains && $contains != '_', function ($query) use ($contains) {
                $letters = explode('-', $contains);
                foreach($letters as $letter)
                {
                    $query->where("word", "like", "%{$letter}%");
                }
                return;
            })
            ->when($excludes && $excludes != '_', function ($query) use ($excludes) {
                $letters = explode('-', $excludes);
                foreach($letters as $letter)
                {
                    $query->where("word", "not like", "%{$letter}%");
                }
                return;
            })
            ->get();
    }
}
