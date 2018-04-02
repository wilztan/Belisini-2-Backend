<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;

class TransactionController extends Controller
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
        $user = request()->user()->id;
        $transaction = Transaction::create([
          'item_id'=>$request->item_id,
          'user_id'=>$user,
          'status'=>$request->status,
        ]);
        return $data=['trasaction'=>$transaction];
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
        if(Transaction::where('id','=',$id)->exists()){
          $transaction = Transaction::findOrFail($id)->update([
            'status'=>'deleted',
          ]);
          return $data=['status'=>'deleted'];
        }
        return $data=['status'=>'failed'];
    }

    public function getTransactionTable($join)
    {
        return Transaction::Join('products','transactions.item_id','=','products.id')
          ->join('users','users.id','=',$join);
    }

    public function getTransactionTableView($transaction)
    {

        return $transaction->select(
          'transactions.id as id',
          'products.id as product_id',
          'products.name as name',
          'stock',
          'price',
          'status',
          'users.name as users'
        )
        ->get();
    }

    public function findPersonalTransaction()
    {
        $user = request()->user()->id;
        $transaction = $this->getTransactionTableView(
          $this->getTransactionTable('transactions.user_id')
          ->where('owner_id','=',$user)
        );
        return $data=['transaction'=>$transaction];
    }

    public function findPersonalOrder()
    {
        $user = request()->user()->id;
        $transaction = $this->getTransactionTableView(
          $this->getTransactionTable('products.owner_id')
          ->where('user_id','=',$user)
        );
        return $data=['transaction'=>$transaction];
    }

    public function getTransactionInfo()
    {
        $user = request()->user()->id;
        $transaction = getTransactionTable('transactions.user_id')
          ->where('products.owner_id','=',$user);
        $count = $transaction->count();
        $sales = 0;
        foreach ($transaction->get() as $product) {
          $sales = $sales + $product->price;
        }
        return $data=[
          'count'=>$count,
          'sales'->$sales,
        ];
    }
}
