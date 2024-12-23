@component('mail::message')

   Hi, {{ $user->name }}. Forgot Password?

   <p>It happens</p>

   @component('mail::button', ['url' => url('reset/' .$user->remember_token)])
      Reset your password
   @endcomponent
    
@endcomponent