<x-app-layout>

  <div class="container">
    <h2>Hello, job listings! <span>All jobs {{ $listings->count()}}</span></h2>
    <form action="{{route('listings.index')}}" method="get">
      <div>
        <input type="text" id="s" name='s' value="{{request()->get('s')}}">
        <button>Search</button>
      </div>
    </form>


    @foreach($tags as $tag)
    <a href="{{ route('listings.index', ['tag' => $tag->slug])}}"
      class="tag {{ $tag->slug == request()->get('tag') ? 'teal' : 'orange';}}">{{ $tag->name }}</a>

    @endforeach
    <div>
      @foreach($listings as $listing)
      <a href="{{ route('listings.show', $listing->slug) }}" class="listing">

        <div class="card {{$listing->is_highlighted ? 'highlighted' : '';}}">
          <img src="/storage/{{$listing->logo}}" alt="">
          <h3>{{$listing->title}}</h3>
          <p>location: {{$listing->location}}</p>
          <div>
            @foreach($listing->tags as $tag)
            <span class="tag {{ $tag->slug == request()->get('tag') ? 'teal' : 'orange';}}">{{$tag->name}}</span>
            @endforeach
          </div>
          <div>
            <span>{{$listing->created_at->diffForHumans()}}</span>
          </div>

        </div>



      </a>


      @endforeach
    </div>

  </div>






</x-app-layout>