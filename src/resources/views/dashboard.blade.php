<x-app-layout>
  <div class="container">
    <h2>Your current listings ({{ $listings->count()}})</h2>
  </div>
  <form action="{{route('logout')}}" method="post">
    @csrf
    <button type="submit">Sign Out</button>
  </form>
  <div>
    @foreach($listings as $listing)
    <a href="{{ route('listings.show', $listing->slug) }}">
      <div style="display: flex;border:1px solid black;margin: 0px auto 10px; width: 70%;">
        <div style="background-color: orange;width: 30%;">
          <img style="width: 30%;vertical-align:bottom;" src="/storage/{{$listing->logo}}" alt="">

          <div style="display: flex;">
            <h3 style="margin-right: 5px;">{{$listing->company}}</h3>
            @foreach($listing->tags as $tag)
            <span style="border: 1px solid black; margin-right: 3px;">{{$tag->name}}</span>

            @endforeach
          </div>
        </div>
        <div style="background-color: teal;width: 70%;">
          <p><strong>{{$listing->created_at->diffForHumans()}}</strong></p>
          <p><strong>{{$listing->clicks()->count()}} apply button clicks</strong></p>
        </div>
      </div>
    </a>

    @endforeach
  </div>
</x-app-layout>