@if(!empty($ad))
	<div class="ad-item bg-w">
		@foreach($ad as $v)
			<a href="{{$v['url']}}"><image src="../images/ad/{{$v['image']}}"></a>
		@endforeach
	</div>
@endif