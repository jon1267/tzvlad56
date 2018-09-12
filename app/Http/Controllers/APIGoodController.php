<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Good;
use App\Http\Resources\Good as GoodResource;

class APIGoodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get all goods
        $goods = Good::paginate(12);

        // return collections goods as resource
        return GoodResource::collection($goods);
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
        $good = new Good();

        $good->id = $request->input('id');
        $good->name = $request->input('name');
        $good->price = $request->input('price');
        $good->number = $request->input('number');
        $good->category_id = $request->input('category_id');
        $good->description = $request->input('description');

        if($good->save()) {
            return new GoodResource($good);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // get good
        $good = Good::findOrFail($id);

        // return property as a resource
        return new GoodResource($good);
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
        $good = Good::findOrFail($id);

        $good->id = $request->input('id');
        $good->name = $request->input('name');
        $good->price = $request->input('price');
        $good->number = $request->input('number');
        $good->category_id = $request->input('category_id');
        $good->description = $request->input('description');

        if($good->save()) {
            return new GoodResource($good);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // get good & delete one
        $good = Good::findOrFail($id);

        if($good->delete()) {
            return new GoodResource($good);
        }
    }

    /**
     * API search
     *
     * @param Request $request
     * @return  mixed - \Illuminate\Http\JsonResponse|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     *          or simple json array.
     */
    public function apiSearch(Request $request)
    {
        $r = $request->all();

        $query = Good::query();

        if(request('name') !== null) {
            $query->when($r, function($query, $r)   {
                return $query->where('name', 'like', '%'.$r['name'].'%');
            });
        }
        if(request('price_min')!== null && request('price_max')!==null){
            $query->when($r, function($query, $r) {
                return $query->whereBetween('price', [$r['price_min'], $r['price_max']]);
            });
        }
        if(request('number')!== null ){
            $query->when($r, function($query, $r) {
                return $query->where('number', $r['number']);
            });
        }
        if(request('date_from')!== null && request('date_to')!==null){
            $query->when($r, function($query, $r) {
                return $query->whereBetween(
                    'created_at', [Carbon::parse($r['date_from']), Carbon::parse($r['date_to'])]
                );
            });
        }

        // date_sort == 1 то $query->latest() [(==0)...->oldest()]
        if(request('date_sort') !== null) {
            (int) $r['date_sort'] ? $query->latest() : $query->oldest();
        }

        // name_sort == 1 то $query->orderBy('name', 'asc') [(==0)...->orderBy('name', 'desc')]
        if(request('name_sort') !== null) {
            (int) $r['name_sort'] ? $query->orderBy('name', 'asc') : $query->orderBy('name', 'desc');
        }

        $data = $query->get();
        //dd($data);

        if(!count($data)) {
            return response()->json(['errors'=>true ,'message' => 'No data for this request' ],404);
        }

        return GoodResource::collection($data);
    }

}
