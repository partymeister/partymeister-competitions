@extends('motor-backend::layouts.backend')

@section('htmlheader_title')
    {{ trans('motor-backend::backend/global.home') }}
@endsection

@section('contentheader_title')
    {{ trans('partymeister-competitions::backend/entries.new') }}
    {!! link_to_route('backend.entries.index', trans('motor-backend::backend/global.back'), [], ['class' => 'pull-right float-right btn btn-sm btn-danger']) !!}
@endsection

@section('main-content')
{{--	@include('motor-backend::errors.list')--}}
	@include('partymeister-competitions::backend.entries.form')
@endsection