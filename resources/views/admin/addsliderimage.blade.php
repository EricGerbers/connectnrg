@extends('admin.layout')
@section('content')
<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> {{ trans('labels.AddSliderImage') }} <small>{{ trans('labels.AddSliderImage') }}...</small> </h1>
    <ol class="breadcrumb">
       <li><a href="{{ URL::to('admin/dashboard/this_month') }}"><i class="fa fa-dashboard"></i> {{ trans('labels.breadcrumb_dashboard') }}</a></li>
      <li><a href="{{ URL::to('admin/sliders')}}"><i class="fa fa-bars"></i> {{ trans('labels.Sliders') }}</a></li>
      <li class="active">{{ trans('labels.AddSliderImage') }}</li>
    </ol>
  </section>
  
  <!-- Main content -->
  <section class="content"> 
    <!-- Info boxes --> 
    
    <!-- /.row -->

    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">{{ trans('labels.AddSliderImage') }}</h3>
          </div>
          
          <!-- /.box-header -->
          <div class="box-body">
            <div class="row">
              <div class="col-xs-12">
              		<div class="box box-info">
                        <br>                       
                        @if (count($errors) > 0)
                              @if($errors->any())
                                <div class="alert alert-success alert-dismissible" role="alert">
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  {{$errors->first()}}
                                </div>
                              @endif
                          @endif
                        
                        <!-- form start -->                        
                         <div class="box-body">
                         
                            {!! Form::open(array('url' =>'admin/addNewSlide', 'method'=>'post', 'class' => 'form-horizontal form-validate', 'enctype'=>'multipart/form-data')) !!}
                            	<div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Language') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                      <select class="form-control" name="languages_id">
                                          @foreach($result['languages'] as $language)
                                              <option value="{{$language->languages_id}}">{{ $language->name }}</option>
                                          @endforeach
                                      </select>
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.ChooseLanguageText') }}</span>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Title') }} </label>
                                  <div class="col-sm-10 col-md-4">
                                    {!! Form::text('sliders_title', '', array('class'=>'form-control field-validate','id'=>'sliders_title')) !!}
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.SliderTitletext') }}</span>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Image') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                    {!! Form::file('newImage', array('id'=>'newImage')) !!}
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">{{ trans('labels.ImageSliderText') }}</span>
                                    <br>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Type') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                      <select class="form-control" name="type" id="bannerType">
                                          <option value="category">{{ trans('labels.ChooseSubCategory') }}</option>
                                          <option value="product">{{ trans('labels.Product') }}</option>
                                          <option value="topseller">{{ trans('labels.TopSeller') }}</option>
                                          <option value="mostliked">{{ trans('labels.MostLiked') }}</option>
                                          <option value="homepage">Home Page Desktop</option>
                                          <option value="homepagemobile">Home Page Mobile</option>   
                                      </select>
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.ChooseSliderToAsscociateWith') }}</span>
                                  </div>
                                </div>
                                
                                <!--<div class="form-group slider-link">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">Sliders Link </label>
                                  <div class="col-sm-10 col-md-4">
                                    {!! Form::text('sliders_url', '', array('class'=>'form-control','id'=>'sliders_url')) !!}
                                  </div>
                                </div>-->
                                
                                <div class="form-group categoryContent">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Categories') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                      <select class="form-control" name="categories_id" id="categories_id">
                                      @foreach($result['categories'] as $category)
                                		<option value="{{ $category->slug}}">{{ $category->name}}</option>
                                      @endforeach
                                      </select>
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.CategoriessliderText') }}</span>
                                  </div>
                                </div>
                                
                                <div class="form-group productContent" style="display: none">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Products') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                      <select class="form-control" name="products_id" id="products_id">
                                      @foreach($result['products'] as $products_data)
                                		<option value="{{ $products_data->products_slug }}">{{ $products_data->products_name }}</option>
                                      @endforeach
                                      </select>
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.ProductsSliderText') }}</span>
                                  </div>
                                </div>
                                
                                
                                
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.ExpiryDate') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                    <input readonly class="form-control datepicker field-validate" type="text" name="expires_date" value="">
                                    <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                    {{ trans('labels.ExpiryDateSlider') }}</span>
                                  </div>
                                </div>
                                
                                <div class="form-group">
                                  <label for="name" class="col-sm-2 col-md-3 control-label">{{ trans('labels.Status') }}</label>
                                  <div class="col-sm-10 col-md-4">
                                      <select class="form-control" name="status">
                                          <option value="1">{{ trans('labels.Active') }}</option>
                                          <option value="0">{{ trans('labels.InActive') }}</option>
                                      </select>
                                      <span class="help-block" style="font-weight: normal;font-size: 11px;margin-bottom: 0;">
                                      {{ trans('labels.StatusSliderText') }}</span>
                                  </div>
                                </div>
                                
                              <!-- /.box-body -->
                              <div class="box-footer text-center">
                                <button type="submit" class="btn btn-primary">{{ trans('labels.AddSliderImage') }}</button>
                                <a href="{{ URL::to('admin/sliders')}}" type="button" class="btn btn-default">{{ trans('labels.back') }}</a>
                              </div>
                              <!-- /.box-footer -->
                            {!! Form::close() !!}
                        </div>
                  </div>
              </div>
            </div>
            
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      <!-- /.col --> 
    </div>
    <!-- /.row --> 
    
    <!-- Main row --> 
    
    <!-- /.row --> 
  </section>
  <!-- /.content --> 
</div>
@endsection 