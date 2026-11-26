@extends('layouts.master')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('content')
    <div class="grid grid-cols-12 gap-x-6">
        @foreach ($cards as $card)
            <div class="col-span-12 md:col-span-6 xl:col-span-3">
                <div class="box">
                    <div class="box-body">
                        <p class="mb-1 text-[0.8125rem] text-textmuted">{{ $card['label'] }}</p>
                        <h4 class="mb-0 font-semibold text-[1.5rem]">{{ $card['value'] }}</h4>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12 xl:col-span-6">
            <div class="box">
                <div class="box-header">
                    <div class="box-title">Applications by status</div>
                </div>
                <div class="box-body">
                    <table class="table whitespace-nowrap min-w-full">
                        <tbody>
                            @foreach ($statusRows as $label => $count)
                                <tr class="border-b border-defaultborder">
                                    <td class="py-2">{{ $label }}</td>
                                    <td class="py-2 text-end font-semibold">{{ $count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
