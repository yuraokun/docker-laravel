<x-app-layout>
  <div class="container">
    <h2>Create a new post $99</h2>


    @if($errors->any())
    <div>
      <ul>
        @foreach($errors->all() as $error)
        <li>{{$error}}</li>

        @endforeach
      </ul>
    </div>
    @endif

    <form action="{{ route('listings.store')}}" id='payment_form' method="post" enctype="multipart/form-data">
      @guest
      <div>
        <x-label for="email" value="Email Address" />
        <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />
      </div>

      <div>
        <x-label for="name" value="Name" />
        <x-input id="name" type="text" name="name" required autofocus />
      </div>

      <div>
        <x-label for="password" value="Password" />
        <x-input id="password" type="password" name="password" required autofocus />
      </div>

      <div>
        <x-label for="password" value="Password Confrimation" />
        <x-input id="password" type="password" name="password_confirmation" required autofocus />
      </div>
      @endguest

      <div>
        <x-label for="title" value="title" />
        <x-input id="title" type="text" name="title" required autofocus />
      </div>

      <div>
        <x-label for="company" value="company" />
        <x-input id="company" type="text" name="company" required autofocus />
      </div>

      <div>
        <x-label for="logo" value="logo image" />
        <x-input id="logo" type="file" name="logo" required autofocus />
      </div>

      <div>
        <x-label for="location" value="location" />
        <x-input id="location" type="text" name="location" required autofocus />
      </div>


      <div>
        <x-label for="apply_link" value="apply link" />
        <x-input id="apply_link" type="text" name="apply_link" required autofocus />
      </div>

      <div>
        <x-label for="tags" value="Tags (separate by comma)" />
        <x-input id="tags" type="text" name="tags" required autofocus />
      </div>

      <div>
        <x-label for="content" value="Listing Content (Markdown is okay)" />
        <textarea id="content" rows="8" name="content" required autofocus></textarea>
      </div>

      <div>
        <x-label for="is_highlighted" value="highlighted" />
        <input id="is_highlighted" value="yes" type="checkbox" name="is_highlighted">
      </div>
      <div id="card-element">

      </div>
      <div>
        @csrf
        <input type="hidden" id="payment_method_id" name="payment_method_id" vavlue="">
        <button type="submit" id="form_submit">Pay + Continue</button>
      </div>

    </form>


  </div>
  <script src="https://js.stripe.com/v3/"></script>
  <script>
  const stripe = Stripe("{{env('STRIPE_KEY')}}");
  const elements = stripe.elements();
  const cardElement = elements.create('card', {
    classes: {
      base: 'StripeElement rounded-md shadow-sm bg-white px-2 py-3 border border-gray-30'
    }
  })

  cardElement.mount('#card-element');

  document.getElementById('form_submit').addEventListener('click', async (e) => {
    e.preventDefault();
    const {
      paymentMethod,
      error
    } = await stripe.createPaymentMethod(
      'card', cardElement, {}
    )

    if (error) {
      alert(error.message);
    } else {
      document.getElementById('payment_method_id').value =
        paymentMethod.id;
      document.getElementById('payment_form').submit()
    }
  })
  </script>
</x-app-layout>