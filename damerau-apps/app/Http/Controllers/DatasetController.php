<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\DatasetAssociatedTask;
use App\Models\DatasetCharacteristic;
use App\Models\DatasetFeatureType;
use App\Models\Download;
use App\Models\Paper;
use App\Models\SubjectArea;
use Oefenweb\DamerauLevenshtein\DamerauLevenshtein;

class DatasetController extends Controller
{
    public function index()
    {
        $datasets = Dataset::where('status', 'valid')->get();
        $countDownloads = [];
        foreach ($datasets as $dataset) {
            $downloads = Download::where('id_dataset', $dataset->id)->get();
            foreach ($downloads as $download) {
                $countDownloads[] = $dataset->id;
            }
        }
        $subjectAreas = SubjectArea::all();
        return view('dataset.index', compact('datasets', 'countDownloads', 'subjectAreas'));
    }

    public function show($id)
    {
        $dataset = Dataset::findOrFail($id);
        // $dataset = Dataset::leftJoin('subject_areas', 'subject_areas.id', '=', 'datasets.id_subject_area')->join('users', 'users.id', '=', 'datasets.id_user')->find($id);
        $characteristics = DatasetCharacteristic::join('characteristics', 'characteristics.id', '=', 'dataset_characteristics.id_characteristic')->where('id_dataset', $id)->get();
        $featureTypes = DatasetFeatureType::join('feature_types', 'feature_types.id', '=', 'dataset_feature_types.id_feature_type')->where('id_dataset', $id)->get();
        $associatedTasks = DatasetAssociatedTask::join('associated_tasks', 'associated_tasks.id', '=', 'dataset_associated_tasks.id_associated_task')->where('id_dataset', $id)->get();
        $papers = Paper::where('id_dataset', $id)->get();
        return view('dataset.show', compact(['dataset', 'characteristics', 'featureTypes', 'associatedTasks', 'papers', 'id']));
    }

    public function filter($id)
    {
        $datasets = Dataset::with('subjectArea')->all();
        $datasets = Dataset::with('subjectArea')->get();

        if ($id != 'all') {
            $datasets = Dataset::with('subjectArea')->where('id_subject_area', $id)->get();
        }
        $countDownloads = [];
        foreach ($datasets as $dataset) {
            $downloads = Download::where('id_dataset', $dataset->id)->get();
            foreach ($downloads as $download) {
                $countDownloads[] = $dataset->id;
            }
        }
        return response()->json([
            'datasets' => $datasets,
            'countDownloads' => $countDownloads,
        ]);

        // $pattern = 'contoh';
        // $string = 'conto';

        // $damerauLevenshtein = new DamerauLevenshtein($pattern, $string);

        // $distance = $damerauLevenshtein->getSimilarity(); // Jarak absolut; hasilnya adalah 1
        // $relativeDistance = $damerauLevenshtein->getRelativeDistance(); // Jarak relatif; hasilnya adalah 0.1667
    }
}
