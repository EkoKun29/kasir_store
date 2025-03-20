<h1>Selamat datang {{ auth()->user()->name }} !</h1>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-danger">Logout</button>
</form>