<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use Illuminate\Http\Request;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Http;
use Oefenweb\DamerauLevenshtein\DamerauLevenshtein;

class SearchController extends Controller
{
    public function removeStopwords($tokens)
    {
        $stopwords = [
            'yang',
            'dan',
            'di',
            'ke',
            'dari',
            'untuk',
            'pada',
            'dengan',
            'sebagai',
            'oleh',
            'atau',
            'itu',
            'ini',
            'adalah',
            'dalam',
            'karena',
            'bahwa',
            'agar',
            'jika',
            'maka',
            'tidak',
            'ya',
            'saja',
            'sudah',
            'belum'
        ];

        return array_filter($tokens, function ($word) use ($stopwords) {
            return !in_array(strtolower($word), $stopwords);
        });
    }
    public function tokenize($data)
    {
        $words = [];
        foreach ($data as $word) {
            $tokenizes = explode(' ', $word);
            $words = array_merge($words, $tokenizes);
        }
        $words = $this->removeStopwords($words);


        return $words;
    }

    function getSynonyms($key)
    {
        $url = "https://www.artikata.com/translate.php?input=$key";

        // Kirim POST request menggunakan Laravel HTTP Client
        $response = Http::post($url);

        $synonyms = [];

        // Periksa apakah request berhasil
        if ($response->successful()) {
            $html = $response->body(); // Ambil isi respons

            $dom = new DOMDocument();
            @$dom->loadHTML($html); // Load HTML dan suppress warning

            // Cari elemen dengan class "contents3"
            $xpath = new DOMXPath($dom);
            $moreWordsNode = $xpath->query('//div[@class="contents3" and contains(text(), "More Word(s)")]')->item(0);

            // Jika elemen ditemukan, cari semua <a> tag setelahnya
            if ($moreWordsNode) {
                // Ambil semua sibling <a> setelah div dengan teks "More Word(s)"
                $sibling = $moreWordsNode->nextSibling;

                // Loop untuk mencari semua elemen <a> di siblings
                while ($sibling) {
                    if ($sibling->nodeName === 'a') {
                        array_push($synonyms, $sibling->nodeValue);
                    }
                    $sibling = $sibling->nextSibling;
                }
            }
        }

        if (count($synonyms) < 1) {
            $spanNodes = $xpath->query('//span[a/@class="related"]');
            foreach ($spanNodes as $span) {
                $synonyms[] = str_replace(',', '', $span->nodeValue);
            }
        }
        return $synonyms;
    }
    public function search($key)
    {
        // $datasets = Dataset::with('subjectArea')
        //     ->where('name', 'like', '%' . $key . '%')
        //     ->where('status', 'valid')
        //     ->get();
        // if ($datasets->count() > 0) {
        //     return response()->json([
        //         'ditemukan' => true,
        //         'data' => $datasets,
        //     ]);
        // }

        try {
            // // damerau
            // $threshold = 2;

            // // data dalam database
            // $datasets = Dataset::pluck('name')->toArray();
            // $data = array_unique(array_map('strtolower', $this->tokenize($datasets)));

            // $distances = [];

            // // data dari kata kunci
            // $inputTokens = array_map('strtolower', $this->tokenize([$key]));

            // foreach ($inputTokens as $inputToken) {
            //     foreach ($data as $datasetToken) {
            //         $damerauLevenshtein = new DamerauLevenshtein($datasetToken, $inputToken);
            //         $similarity = $damerauLevenshtein->getSimilarity();

            //         if ($similarity <= $threshold) {
            //             $distances[$datasetToken] = $similarity;
            //         }
            //     }
            // }

            // // Urutkan berdasarkan similarity (jarak terkecil ke terbesar)
            // asort($distances); // ascending sort, mempertahankan key

            // $suggestions = [];
            // foreach ($distances as $suggestion => $distance) {
            //     $suggestions[] = $suggestion;
            // }

            // if (count($suggestions) > 0) {
            //     $datasets = Dataset::with('subjectArea')
            //         ->where(function ($query) use ($suggestions) {
            //             foreach ($suggestions as $suggestion) {
            //                 $query->orWhere('name', 'like', '%' . $suggestion . '%');
            //             }
            //         })
            //         ->where('status', 'valid')
            //         ->get();

            //     return response()->json([
            //         'ditemukan' => true,
            //         'data' => $datasets,
            //         'saran' => $suggestions, // tambahkan jika ingin melihat saran yang dipakai
            //     ]);
            // }

            //ekspansi sinonim

            $synonyms = $this->getSynonyms($key);
            // dd($synonyms);

            if (count($synonyms) > 0) {
                $datasets = Dataset::with('subjectArea')
                    ->where(function ($query) use ($synonyms) {
                        foreach ($synonyms as $synonym) {
                            $query->orWhere('name', 'like', '%' . str_replace(' ', '', $synonym) . '%');
                        }
                    })
                    ->where('status', 'valid')
                    ->get();

                // dd($datasets);
                return response()->json([
                    'ditemukan' => true,
                    'data' => $datasets,
                ]);
            }
            return response()->json([
                'ditemukan' => false,
                'data' => 'Data tidak ditemukan',
            ]);
        } catch (\Throwable $th) {
            echo $th->getMessage();
            // return response()->json($th->getMessage());
        }
    }
}
