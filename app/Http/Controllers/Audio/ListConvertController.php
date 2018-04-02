<?php

namespace App\Http\Controllers\Audio;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AudioConvertResult;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ListConvertController extends Controller
{
    public $totalItems = 10;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $totalItems = $this->totalItems;
        if ($request->has(['dateFrom', 'dateTo'])) {
            $input = $request->only(['dateFrom', 'dateTo']);
            $input['dateFrom'] = $input['dateFrom'] == '' ? Carbon::now()->subYears(10) : $input['dateFrom'];
            $input['dateTo'] = $input['dateTo'] == '' ? Carbon::now()->addYears(10) : $input['dateTo'];

            $data = User::find(Auth::user()->id)->audioConvertResults()
                ->whereBetween('audio_convert_results.created_at', [$input['dateFrom'], $input['dateTo']])
                ->orderBy('created_at', 'desc')
                ->with('audioFile')
                ->paginate($totalItems);
        } else {
            $data = User::find(Auth::user()->id)->audioConvertResults()
                ->orderBy('created_at', 'desc')
                ->with('audioFile')
                ->paginate($totalItems);
        }

        return view('layouts.list_convert.index', compact('data', 'totalItems'));
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
        $data = AudioConvertResult::find($id)->load('audioFile');
        return view('layouts.list_convert.edit', compact('data'));
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
        if ($request->has(['result_convert'])) {
            AudioConvertResult::find($id)->update(['result_convert' => $request->input('result_convert')]);
        }

        return redirect()->route('admin.listConvert.edit', $id)->with('message', [
            'value' => 'Edit Success!',
            'type' => 'success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = AudioConvertResult::find($id)->load('audioFile');
        if (Storage::delete($data->audioFile->path)) {
            $data->delete();
            return redirect()->route('admin.listConvert.index', $_GET)->with('message', [
                'value' => 'Delete Success!',
                'type' => 'success',
            ]);
        }
    }
}
