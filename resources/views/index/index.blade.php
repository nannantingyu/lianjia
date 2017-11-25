<!DOCTYPE html>
<html>
    <head>
        <title>链家-粮叔叔</title>
        <meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0;" name="viewport" />
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('plugin/bootstrap-select/css/bootstrap-select.min.css') }}">
        <script src="{{ asset('js/jquery.js') }}"></script>
        <script src="{{ asset('js/bootstrap.js') }}"></script>
        <script src="{{ asset('plugin/bootstrap-select/js/bootstrap-select.min.js') }}"></script>
        <script src="{{ asset('js/main.js') }}"></script>
    </head>
    <body style="max-width:100%;over-flow:hidden;">
        <div class="container" style="max-width:95%;">
            <div class="row">
                <div class="col-md-3 col-xs-3 col-sm-3 nopadding">
                    <div class="form-group nomargin">
                        <select data-name="area" class="selectpicker form-control" multiple title="区域">
                            <option value="和平">和平</option>
                            <option value="南开">南开</option>
                            <option value="河西">河西</option>
                            <option value="河东">河东</option>
                            <option value="河北">河北</option>
                            <option value="津南">津南</option>
                            <option value="北辰">北辰</option>
                            <option value="西青">西青</option>
                            <option value="东丽">东丽</option>
                            <option value="塘沽">塘沽</option>
                            <option value="开发区">开发区</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-xs-3 col-sm-3 nopadding">
                    <select data-name="order" class="selectpicker form-control" title="排序">
                        <option>价格</option>
                        <option>面积</option>
                        <option>均价</option>
                        <option>关注人数</option>
                        <option>带看人数</option>
                        <option>发布时间</option>
                    </select>
                </div>
                <div class="col-md-3 col-xs-3 col-sm-3 nopadding">
                    <select data-name="price" class="selectpicker form-control" multiple title="价格">
                        <option value="50-80">50-80万</option>
                        <option value="80-100">80-100万</option>
                        <option value="100-130">100-130万</option>
                        <option value="130-150">130-150万</option>
                        <option value="150-160">150-160万</option>
                        <option value="160-180">160-180万</option>
                        <option value="180-200">180-200万</option>
                        <option value="200-250">200-250万</option>
                        <option value="250-300">250-300万</option>
                        <option value="300-350">300-350万</option>
                        <option value="350-400">350-400万</option>
                        <option value="400-10000">400万以上</option>
                    </select>
                </div>
                <div class="col-md-3 col-xs-3 col-sm-3 nopadding">
                    <select data-name="huxing" class="selectpicker form-control" multiple title="户型">
                        <option value="1">一室</option>
                        <option value="2">二室</option>
                        <option value="3">三室</option>
                        <option value="4">四室</option>
                        <option value="5">五室</option>
                        <option value="6">五室以上</option>
                    </select>
                </div>
            </div>
            <div class="row nopadding">
                <div class="col-md-12 col-sm-12 col-xs-12 nopadding">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            其他选项
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" style="min-width:100%;" aria-labelledby="dropdownMenu1">
                            <li><button class="btn btn-default toggle btn-subway">地铁</button></li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <button class="btn btn-default toggle btn-direct" data-val="南">朝南</button>
                                <button class="btn btn-default toggle btn-direct" data-val="西">朝西</button>
                                <button class="btn btn-default toggle btn-direct" data-val="东">朝东</button>
                                <button class="btn btn-default toggle btn-direct" data-val="北">朝北</button>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <button class="btn btn-default toggle btn-flood" data-val="低">低楼层</button>
                                <button class="btn btn-default toggle btn-flood" data-val="中">中楼层</button>
                                <button class="btn btn-default toggle btn-flood" data-val="高">高楼层</button>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <button class="btn btn-default toggle btn-elevator">有电梯</button>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li>
                                <button class="btn btn-primary" id="sure">确定</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            @foreach($houses as $house)
            <div class="row">
                <div class="col-sm-4 col-md-4 col-xs-4 padding5 margin10">
			@php
				$img = basename(str_replace('/uploads/crawler', '', explode(',', $house->images)[0]));
				$img = str_replace("900x600", "240x160", $img);
			@endphp
                    <img style="width:100%;" src="http://ads.yjshare.cn/img?name={{ $img }}" alt="house.jpg">
                </div>
                <div class="col-sm-8 col-md-8 col-xs-8 padding5">
                    <p class="title">{{ $house->title }}</p>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-xs-6">
                            <span>{{ $house->price }}万/{{ $house->unit_price }}元</span>
                        </div>
                        <div class="col-sm-6 col-md-6 col-xs-6">
                            <span>{{ $house->area }}</span>
                            <span>{{ $house->direction }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-xs-6">
                            <span>{{ $house->district }}</span>
                            <span>{{ $house->street }}</span>
                        </div>
                        <div class="col-sm-6 col-md-6 col-xs-6">
                            <span>{{ $house->residential }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-xs-6">
                            <span>{{ $house->flood }}</span>
                        </div>
                        <div class="col-sm-6 col-md-6 col-xs-6">
                            <span>{{ $house->list_time }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-xs-6">
                            <span>{{ $house->hold_years }}</span>
                        </div>
                        <div class="col-sm-6 col-md-6 col-xs-6">
                            <span><a href="https://tj.lianjia.com/ershoufang/{{$house->house_id}}.html">链家地址</a></span>
                        </div>
                    </div>
		    <div class="row">
			<div class="col-sm-12 col-md-12 col-xs-12">
			    <span>小区年代：{{$house->build_year}}</span>
			</div>
		    </div>
		    <div class="row">
			<div class="col-sm-12 col-md-12 col-xs-12">
			    <span>小区均价：{{$house->uprice}}</span>
			</div>
		    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-xs-12">
                            <span>{{ $house->tag }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="row text-center">
		<ul class='pagination'>
			@if($page['now'] != 1)
				<li><a href="{{$url}}&page=1">首页</a></li>
			@endif
			@if($page['pre'] > 0)
				<li><a href="{{$url}}&page={{$page['pre']}}">上一页</a></li>
			@endif
			<li class='active'><a href-"{{$url}}&page={{$page['now']}}">{{$page['now']}}</a></li>
			@if($page['next'] <= $page['last'])
				<li><a href="{{$url}}&page={{$page['next']}}">下一页</a></li>
			@endif
			@if($page['now'] < $page['last']-1)
				<li><a href="{{$url}}&page={{$page['last']-1}}">{{$page['last']-1}}</a></li>
			@endif
			@if($page['now'] != $page['last'])
				<li><a href="{{$url}}&page={{$page['last']}}">末页</a></li>
			@endif
			
		</ul>
            </div>
        </div>
    </body>
</html>
