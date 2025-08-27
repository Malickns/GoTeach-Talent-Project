@extends("layouts.app")
@section("title", "Supprimer l'offre d'emploi")
@section("content")


<form method="POST" action="{{ route('offreEmplois.destroy', $post) }}" >
	<!-- CSRF token -->
	@csrf
	<!-- <input type="hidden" name="_method" value="DELETE"> -->
	@method("DELETE")
	<input type="submit" value="x Supprimer" >
</form>

@endsection