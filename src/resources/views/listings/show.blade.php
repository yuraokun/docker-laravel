<x-app-layout>
  <div class="container">
    <h2>{{$listing->title}}</h2>

    <div>
      @foreach($listing->tags as $tag)
      <span class="tag orange">{{ $tag->name }}</span>
      @endforeach
    </div>
    <div style="display: flex; align-items: center;">
      <div style="margin-right: 20px;">
        <p>company name: {{$listing->company}}</p>
        <p>location: {{$listing->location}}</p>
      </div>
      <div>
        <img style=" width:100px;" src="/storage/{{$listing->logo}}" alt="">
      </div>
    </div>

    <hr style="margin: 20px 0;">
    <div>
      {!! $listing->content !!}
    </div>
    <a href="{{ route('listings.apply', $listing->slug)}}" target="_blank"><button
        style="width:300px; font-size: 24px;">Apply
        Now</button></a>
  </div>






</x-app-layout>