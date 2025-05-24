<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssociatedTask;
use App\Models\Characteristic;
use App\Models\Dataset;
use App\Models\DatasetAssociatedTask;
use App\Models\DatasetCharacteristic;
use App\Models\DatasetFeatureType;
use App\Models\Download;
use App\Models\FeatureType;
use App\Models\Paper;
use App\Models\SubjectArea;
use App\Models\UrlFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KelolaDatasetController extends Controller
{
    public function index()
    {
        $datasets = Dataset::all();
        $status = Dataset::where('status', 'pending')->count();
        $user = Auth::user();
        if ($user->role != 'admin') {
            $datasets = Dataset::where('id_user', $user->id)->get();
        }
        return view('admin.dataset.index', compact(['datasets', 'status']));
    }

    public function show($id)
    {
        $user = Auth::user();
        $dataset = Dataset::with('featuresType.feature', 'characteristics.characteristic', 'associatedTask.associated')->findOrFail($id);
        if ($user->role != 'admin') {
            $dataset = Dataset::where('id', $id)
                ->where('id_user', $user->id)
                ->firstOrFail();
        }
        $papers = Paper::where('id_dataset', $id)->get();

        // Lokasi folder tempat dataset disimpan
        $folderPath = 'public/datasets/' . $id;
        $files = Storage::files($folderPath);

        return view('admin.dataset.show', compact(['dataset', 'papers', 'id', 'files']));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'file' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $dataset = new Dataset();
            $dataset->id_user = Auth::user()->id;
            $dataset->id_subject_area = $request->subjectArea ?? 11;
            $dataset->name = $request->name;
            $dataset->abstract = $request->abstract;
            $dataset->instances = $request->instances;
            $dataset->features = $request->features;
            $dataset->information = $request->information;
            if (Auth::user()->role === 'admin') {
                $dataset->status = 'valid';
            }
            $dataset->save();

            foreach ($request->file('file') as $file) {
                $urlFiles = new UrlFile();
                $urlFiles->id_dataset = $dataset->id;
                $path = $file->storeAs('public/datasets/' . $dataset->id, $file->getClientOriginalName());
                $urlFiles->url_file = str_replace('public/', '', $path);
                $urlFiles->save();
            }

            if ($request->characteristics) {
                foreach ($request->characteristics as $characteristic) {
                    $newCharacteristic = new DatasetCharacteristic();
                    $newCharacteristic->id_dataset = $dataset->id;
                    $newCharacteristic->id_characteristic = $characteristic;
                    $newCharacteristic->save();
                }
            }

            if ($request->associatedTasks) {
                foreach ($request->associatedTasks as $associatedTasks) {
                    $newAssociatedTasks = new DatasetAssociatedTask();
                    $newAssociatedTasks->id_dataset = $dataset->id;
                    $newAssociatedTasks->id_associated_task = $associatedTasks;
                    $newAssociatedTasks->save();
                }
            }

            if ($request->featureTypes) {
                foreach ($request->featureTypes as $featureType) {
                    $newfeatureType = new DatasetFeatureType();
                    $newfeatureType->id_dataset = $dataset->id;
                    $newfeatureType->id_feature_type = $featureType;
                    $newfeatureType->save();
                }
            }

            DB::commit();

            return back()->with([
                'message' => 'Dataset berhasil ditambahkan',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->withErrors([
                // 'error' => 'Terjadi kesalahan'
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $dataset = Dataset::findOrFail($id);
            if ($user->role != 'admin') {
                $dataset = Dataset::where('id', $id)
                    ->where('id_user', $user->id)
                    ->firstOrFail();
            }

            $id = $dataset->id;
            $dataset->delete();

            $characteristics = DatasetCharacteristic::where('id_dataset', $id)->get();
            foreach ($characteristics as $characteristic) {
                $characteristic->delete();
            }

            $associatedTasks = DatasetAssociatedTask::where('id_dataset', $id)->get();
            foreach ($associatedTasks as $associatedTask) {
                $associatedTask->delete();
            }

            $featureTypes = DatasetFeatureType::where('id_dataset', $id)->get();
            foreach ($featureTypes as $featureType) {
                $featureType->delete();
            }

            $downloads = Download::where('id_dataset', $id)->get();
            foreach ($downloads as $download) {
                $download->delete();
            }

            $papers = Paper::where('id_dataset', $id)->get();
            foreach ($papers as $paper) {
                $paper->delete();
            }

            $urlFiles = UrlFile::where('id_dataset', $id)->get();
            foreach ($urlFiles as $urlFile) {
                // Storage::delete('public/' . $urlFile->url_file);
                $urlFile->delete();
            }
            Storage::deleteDirectory('public/datasets/' . $id);

            DB::commit();
            $datasets = Dataset::with('user')->get();
            // $datasets = Dataset::join('users', 'users.id', '=', 'datasets.id_user')->select('datasets.id', 'name', 'full_name', 'datasets.status', 'note')->get();
            return response()->json([
                'status' => 200,
                'message' => 'Deleted successfully',
                'datasets' => $datasets,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage(),
            ]);
        }
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->status != 'on' && $user->role != 'admin') {
            return view('info.akun-off');
        }
        $myDataset = Dataset::where('id_user', Auth::user()->id)
            ->where('status', 'pending')
            ->first();

        if (optional($myDataset)->count() > 0 && $user->role != 'admin') {
            return view('info.pending-dataset');
        }
        $characteristics = Characteristic::all();
        $subjectAreas = SubjectArea::all();
        $associatedTasks = AssociatedTask::all();
        $featureTypes = FeatureType::all();
        return view('admin.dataset.create', compact(['characteristics', 'subjectAreas', 'associatedTasks', 'featureTypes']));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $dataset = Dataset::findOrFail($id);
        if ($user->role != 'admin') {
            $dataset = Dataset::where('id', $id)
                ->where('id_user', $user->id)
                ->firstOrFail();
        }
        $status = Dataset::where('status', 'pending')->count();
        if ($user->status === 'off' || ($status > 0 && $user->role != 'admin')) {
            return redirect()->route('admin.dataset.index');
        }
        $characteristics = Characteristic::all();
        $subjectAreas = SubjectArea::all();
        $associatedTasks = AssociatedTask::all();
        $featureTypes = FeatureType::all();

        // $dataset = Dataset::leftJoin('subject_areas', 'subject_areas.id', '=', 'datasets.id_subject_area')->select('datasets.id as id_dataset', 'datasets.*', 'subject_areas.*')->find($id);
        $datasetCharacteristics = DatasetCharacteristic::join('characteristics', 'characteristics.id', '=', 'dataset_characteristics.id_characteristic')->where('id_dataset', $id)->get();
        $datasetFeatureTypes = DatasetFeatureType::join('feature_types', 'feature_types.id', '=', 'dataset_feature_types.id_feature_type')->where('id_dataset', $id)->get();
        $datasetAssociatedTasks = DatasetAssociatedTask::join('associated_tasks', 'associated_tasks.id', '=', 'dataset_associated_tasks.id_associated_task')->where('id_dataset', $id)->get();
        return view('admin.dataset.edit', compact('characteristics', 'datasetCharacteristics', 'dataset', 'subjectAreas', 'associatedTasks', 'featureTypes', 'datasetFeatureTypes', 'datasetAssociatedTasks', 'id'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            if ($user->status === 'off') {
                return redirect()->route('admin.dataset.index');
            }
            $dataset = Dataset::findOrFail($id);
            if ($user->role != 'admin') {
                $dataset = Dataset::where('id', $id)
                    ->where('id_user', $user->id)
                    ->firstOrFail();
            }

            $dataset->name = $request->name;
            $dataset->abstract = $request->abstract;
            $dataset->instances = $request->instances;
            $dataset->features = $request->features;
            $dataset->id_subject_area = $request->subjectArea;
            $dataset->information = $request->information;
            if ($user->role != 'admin') {
                $dataset->status = 'pending';
            }
            $dataset->save();

            $oldCharacteristic = DatasetCharacteristic::where('id_dataset', $id)->get();
            if ($oldCharacteristic) {
                foreach ($oldCharacteristic as $value) {
                    $value->delete();
                }
            }
            if ($request->characteristics) {
                foreach ($request->characteristics as $characteristic) {
                    $newCharacteristic = new DatasetCharacteristic();
                    $newCharacteristic->id_dataset = $id;
                    $newCharacteristic->id_characteristic = $characteristic;
                    $newCharacteristic->save();
                }
            }

            $oldAssociatedTasks = DatasetAssociatedTask::where('id_dataset', $id)->get();
            if ($oldAssociatedTasks) {
                foreach ($oldAssociatedTasks as $value) {
                    $value->delete();
                }
            }
            if ($request->associatedTasks) {
                foreach ($request->associatedTasks as $associatedTask) {
                    $newAssociatedTask = new DatasetAssociatedTask();
                    $newAssociatedTask->id_dataset = $id;
                    $newAssociatedTask->id_associated_task = $associatedTask;
                    $newAssociatedTask->save();
                }
            }

            $oldFeatureType = DatasetFeatureType::where('id_dataset', $id)->get();
            if ($oldFeatureType) {
                foreach ($oldFeatureType as $value) {
                    $value->delete();
                }
            }
            if ($request->featureTypes) {
                foreach ($request->featureTypes as $featureType) {
                    $newfeatureType = new DatasetFeatureType();
                    $newfeatureType->id_dataset = $id;
                    $newfeatureType->id_feature_type = $featureType;
                    $newfeatureType->save();
                }
            }

            if ($request->file('file')) {
                if ($request->status === 'timpa') {
                    $urlFiles = UrlFile::where('id_dataset', $id)->get();
                    foreach ($urlFiles as $urlFile) {
                        Storage::delete('public/' . $urlFile->url_file);
                        $urlFile->delete();
                    }
                }

                foreach ($request->file('file') as $file) {
                    $urlFiles = new UrlFile();
                    $urlFiles->id_dataset = $dataset->id;
                    $path = $file->storeAs('public/datasets/' . $dataset->id, $file->getClientOriginalName());
                    $urlFiles->url_file = str_replace('public/', '', $path);
                    $urlFiles->save();
                }
            }

            DB::commit();
            return back()->with([
                'message' => 'Dataset Anda berhasil diupdate.',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());
            return back()->withErrors([
                'message' => 'There is an error',
            ]);
        }
    }
}
