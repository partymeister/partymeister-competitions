@extends('motor-backend::layouts.backend')

@section('htmlheader_title')
    {{ trans('motor-backend::backend/global.home') }}
@endsection

@section('contentheader_title')
    {{ trans('partymeister-competitions::backend/access_keys.edit') }}
    {!! link_to_route('backend.access_keys.index', trans('motor-backend::backend/global.back'), [], ['class' => 'pull-right float-right btn btn-sm btn-danger']) !!}
@endsection

@section('main-content')
	@include('motor-backend::errors.list')
	@include('partymeister-competitions::backend.access_keys.form')
@endsection