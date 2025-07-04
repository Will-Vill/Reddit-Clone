<article class="post" data-reddit-id="{{ $post->reddit_id }}" data-db-id="{{ $post->id }}">
    <div class="header-post">
        <div class="post-info">
            <a href="{{ route('postSingolo', ['reddit_id' => $post->reddit_id]) }}" class="titolo-post-link">
                <h3 class="titolo-post">{{ $post->titolo }}</h3>
            </a>
            <div class="user-info">
                <p class="utente-post-ricerca">Posted by {{ $post->autore }}</p>
            </div>
        </div>
        <div class="subreddit-container">
            <div class="avatar-subreddit">
                <img src="{{ asset('assets/images/' . $post->subreddit . '.png') }}" alt="logo" onerror="this.src='{{ asset('assets/images/reddit-logo.png') }}'">
            </div>
            <a class="pulsante-subreddit">r/{{ $post->subreddit }}</a>
        </div>
    </div>
</article>