@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/representative.css') }}"> 
@endsection

@section('content')
<div class="container">
    <div class="card mt-4">
        <div class="card-header">予約一覧</div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Shop</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Number of People</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->user->name }}</td>
                            <td>{{ $reservation->user->email }}</td>
                            <td>{{  $reservation->shop->name}}</td>
                            <td>{{ $reservation->date }}</td>
                            <td>{{ $reservation->time }}</td>
                            <td>{{ $reservation->number_of_people }}人</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination justify-content-center">
                {{ $reservations->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
